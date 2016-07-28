<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Account_model extends CI_Model{

	private $usr_tbl	= 'user';
	private $login_attempts_table_name = 'login_attempts';
	
	/**
	 * Get location by google place id
	 *
	 * @param	NULL
	 * @return	login
	 * OK
	 */

	public function login(){
		
		$post = $this->form->get_post();

		// login by username
		if(!$user = $this->get_user_by_username($post['username'])){
			$this->increase_attempt($post['username']);
			$this->form->add_error('Invalid login');
			return;
		}
		
		//chk if banned
		if($user->status != 1){
			$this->form->add_error('Your account is not authorized. Please contact admin.');
		 	return;
		}

		//chk password
	    if(md5($post['password']) !== $user->user_pass){
	      $this->increase_attempt($post['username']);
	      $this->form->add_error('Invalid login');
	      return;
	    }

	    //set password if first login
		if($user->login_count == 0){
			$this->session->set_userdata('reset_password_id', $user->id);
			redirect('account/set_password');
		 	return;
		}


	    //clear login attempts
	    $this->clear_attempts();

	    //login updates
		$this->db->where('id', $user->id);
		$this->db->set('login_count', 'login_count+1', FALSE);
		$this->db->set('last_ip', $this->input->ip_address() );
		$this->db->set('last_login', date('Y-m-d H:i:s') );
		$this->db->set('new_pass_key', NULL );
		$this->db->update($this->usr_tbl);	


        $query = $this->db->get_where("group_to_users" ,  array('user_id' => $user->id,'group_id' => 1),1);
        
        if($query->num_rows() == 0){
            $is_admin = FALSE;
        } else {
        	$is_admin = TRUE;
        }

	    $this->session->set_userdata(array(
			'user_id' => $user->id,
			'is_admin' => $is_admin
		));

	}

	public function create(){

		$post = $this->form->get_post();

		//check if email already exists
		if($user = $this->get_user_by_email($post['email'])){
			$this->form->add_error('The email address you specified is already associated with other account.');
			return;
		}

		//check if username exists
		if($user = $this->get_user_by_username($post['username'])){
			$this->form->add_error('The username you specified is already associated with other account.');
			return;
		}

		$data = array(
			'username' => strtolower($post['username']),
			'email' => $post['email'],
			'user_pass' => md5($post['password']),
			'name' => ucwords(strtolower($post['name'])),
			'last_ip' => $this->input->ip_address(),
			'status' => 1,
			'last_login' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'created_at' =>  date('Y-m-d H:i:s')
		);

		$this->db->insert($this->usr_tbl, $data); 
		$this->session->set_flashdata('success_message', 'New user created successfully.');
	}


	public function update(){

		$post = $this->form->get_post();

		//check if email already exists
		if($user = $this->get_other_user_by_email($post['user_id'], $post['email'])){
			$this->form->add_error('The email address you specified is already associated with other account.');
			return;
		}

		//check if username exists
		if($user = $this->get_other_user_by_username($post['user_id'], $post['username'])){
			$this->form->add_error('The username you specified is already associated with other account.');
			return;
		}

		$data = array(
			'name' => ucwords(strtolower($post['name'])),
			'username' => strtolower($post['username']),
			'email' => $post['email'],
			'status' => $post['status'][0],
			'updated_at' => date('Y-m-d H:i:s')
		);


		if(isset($post['password']) && strlen(trim($post['password'])) > 0){
			$data['user_pass'] = md5($post['password']);
		}

		$this->db->where('id', $post['user_id']); 
		$this->db->update($this->usr_tbl, $data); 
		$this->session->set_flashdata('success_message', 'User updated successfully.');
	}


	public function forgot_password(){

		$post = $this->form->get_post();

		if(!$user = $this->get_user_by_email($post['email'])){
			$this->form->add_error('Invalid email, the email you entered is not correct.');
			return;
		}


		$pass = array(
			'new_pass_key' => md5(rand().microtime()),
		); 

		$this->db->where('id', $user->id);
		$this->db->update($this->usr_tbl, $pass);


		//Send email
		$email_data['name'] = $user->name;
		$email_data['password_reset_url'] = base_url('account/reset_password/'.$user->id.'/'.$pass['new_pass_key']);	
		$this->load->library('email');
		$this->load->library('parser');
		$this->email->set_newline("\r\n");
		$this->email->from('no-reply@flamma.kennedy.ox.ac.uk', 'File Store');
		$this->email->to($user->email);
		$this->email->subject('Password reset');
		$htmlMessage = $this->parser->parse('emails/password_reset', $email_data, true);
		$this->email->message($htmlMessage);	
		$this->email->send();



		$this->session->set_flashdata('success_message', 'An email with instructions for creating a new password has been sent to your email.');
	}

	public function set_new_password(){

		$post = $this->form->get_post();
        
		//update password
		$this->db->set('user_pass', md5($post['new_password']));
		$this->db->set('login_count', 1);
		$this->db->where('id', $this->session->userdata('reset_password_id'));
		$this->db->update($this->usr_tbl);

		$this->session->unset_userdata('reset_user_id');
		$this->session->unset_userdata('reset_new_pass_key');

		$this->session->set_flashdata('success_message', 'Password updated successfully, now you can login using your new password.');
	}



	public function reset_password(){

		$post = $this->form->get_post();

		$user_id        = $this->session->userdata('reset_user_id');
        $new_pass_key   = $this->session->userdata('reset_new_pass_key');


        $query = $this->db->get_where('user', array('id' => $user_id, 'new_pass_key' => $new_pass_key),1);

     	
		if($query->num_rows() == 0){
			$this->form->add_error('Unable to update your password.');
			return FALSE;
		}

		

		$new_pass = md5($post['new_password']);
		//update password
		$this->db->set('user_pass', $new_pass);
		$this->db->set('new_pass_key', NULL);
		$this->db->where('id', $user_id);
		$this->db->update($this->usr_tbl);

		$this->session->unset_userdata('reset_user_id');
		$this->session->unset_userdata('reset_new_pass_key');


		$this->session->set_flashdata('success_message', 'Password updated successfully, now you can login using your new password.');
	}

	public function get_permissions(){

		if(!$this->session->userdata('user_id') || !$this->session->userdata('group_id')){
			return null;
		}

		$query = $this->db->get_where('user_groups', array('id' => $this->session->userdata('group_id') ));
		$group = $query->row();

		$data['group_id'] = $group->id;
		$data['group_name'] = $group->name;
		$data['permissions'] = json_decode($group->permissions, TRUE);
		return $data;

	}


	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	public function get_user_by_id($user_id){

		$this->db->where('user_id', $user_id);
		$query = $this->db->get($this->usr_tbl);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;

	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	public function get_other_user_by_username($user_id, $username){

		$this->db->where('id<>', $user_id);
		$this->db->where('username', $username);
		$query = $this->db->get($this->usr_tbl);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;

	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	public function get_user_by_username($username){

		$this->db->where('username', $username);
		$query = $this->db->get($this->usr_tbl);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;

	}


	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	public function get_other_user_by_email($user_id,$email){

		$this->db->where('id<>', $user_id);
		$this->db->where('LOWER(email)=', strtolower($email));

		$query = $this->db->get($this->usr_tbl);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;

	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	public function get_user_by_email($email){

		$this->db->where('LOWER(email)=', strtolower($email));

		$query = $this->db->get($this->usr_tbl);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;

	}

	
	/**
	 * Get number of attempts to login occured from given IP-address or login
	 *
	 * @param	string
	 * @return	int
	 */
	public function get_attempts_count($login){

		$ip_address = $this->input->ip_address();

		$this->db->select('1', FALSE);
		$this->db->where('ip_address', $ip_address);
		if (strlen($login) > 0) $this->db->or_where('login', $login);

		$qres = $this->db->get($this->login_attempts_table_name);
		return $qres->num_rows();
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 *
	 * @param	string
	 * @return	void
	 */
	private function increase_attempt($login){

		$this->load->library('user_agent');

		$ip_address = $this->input->ip_address();

		$this->db->insert($this->login_attempts_table_name, array(
			'ip_address' => $ip_address, 
			'user_agent' => $this->agent->agent_string(), 
			'login' => $login, 
			'created_at' => date('Y-m-d H:i:s')
			 ));

	}

	/**
	 * Clear all attempt records for given IP-address and login.
	 * Also purge obsolete login attempts (to keep DB clear).
	 *
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	private function clear_attempts(){

		$ip_address = $this->input->ip_address();
		$this->db->where(array('ip_address' => $ip_address));
		$this->db->delete($this->login_attempts_table_name);
	}


	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function can_reset_password($user_id, $new_pass_key){

		if ((strlen($user_id) == 0) && (strlen($new_pass_key) == 0)) {
			return FALSE;
		}


		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('new_pass_key', $new_pass_key);
		$query = $this->db->get($this->usr_tbl);
		
		if($query->num_rows() == 0){
			return FALSE;
		}

		return TRUE;

	}





}