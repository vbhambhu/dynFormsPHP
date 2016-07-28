<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Group_model extends CI_Model{

	private $grp_tbl = 'user_groups';
	private $grp_2_usr = 'group_to_users';


	public function get_my_groups(){

		$user_id = $this->session->userdata('user_id');


		$sql = 'SELECT grp.id, grp.name, grp.created_at,grp.owner_id, u.name as owned_by,
		 (SELECT COUNT(*) FROM group_to_users WHERE group_id = grp.id) AS members
		FROM group_to_users  g2u
		INNER JOIN user_groups grp ON (g2u.group_id = grp.id)
		INNER JOIN user u ON (grp.owner_id = u.id)
		WHERE g2u.user_id='.$user_id.' ORDER BY grp.created_at DESC';

		$query = $this->db->query($sql);

		return $query->result();

	}


	public function get_other_groups(){

		$user_id = $this->session->userdata('user_id');

		$sql = 'SELECT grp.id, grp.name, grp.created_at, grp.owner_id, u.name as owned_by,
				(SELECT COUNT(*) FROM group_to_users WHERE group_id = grp.id) AS members
				FROM user_groups grp 
				LEFT JOIN user u ON (grp.owner_id = u.id)
				WHERE grp.id NOT IN (SELECT group_id FROM group_to_users WHERE user_id = '.$user_id.')
				ORDER BY grp.created_at DESC';

		$query = $this->db->query($sql);

		return $query->result();




	}


	// public function memebers_count_group($group_id){
	// 	$query = $this->db->get_where($this->grp_2_usr, array('group_id' => $group_id));
	// 	return $query->num_rows();

	// }
	


	public function create(){
		
		$post = $this->form->get_post();

		$query = $this->db->get_where($this->grp_tbl, array('name' => $post['name']));

		if($query->num_rows() > 0){
			$this->form->add_error('Group with this name already exists.');
		 	return;
		}

        $group = array(
        	'name' => $post['name'],
        	'owner_id' => $this->session->userdata('user_id'),
        	'created_at' => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->grp_tbl, $group); 

        $group_id = $this->db->insert_id();


        //link group to user

        $group_user = array(
        	'user_id' => $this->session->userdata('user_id'),
        	'group_id' => $group_id
        );

        $this->db->insert($this->grp_2_usr, $group_user); 

		$this->session->set_flashdata('success_message', 'New group created successfully.');
       
	}


	public function update(){


		$post = $this->form->get_post();

		$query = $this->db->get_where($this->grp_tbl, array('name' => $post['name'], 'id<>' => $post['group_id'] ));

		if($query->num_rows() > 0){
			$this->form->add_error('Group with this name already exists.');
		 	return;
		}

		// echo '<pre>'; print_r($post); die;
		// $query = $this->db->get_where($this->grp_tbl, array('id' => $post['group_id']));
		// $group = $query->row();

		// if($group->owner_id != $this->session->userdata('user_id')){
		// 	$this->form->add_error('Only group owner can update group.');
		//  	return;
		// }

		$group = array('name' => $post['name'], 'owner_id' => $post['owner_id'][0]);
        $this->db->where('id', $post['group_id']); 
        $this->db->update($this->grp_tbl, $group);


        if(isset($post['members'])){


		        $this->db->where('group_id', $post['group_id']); 
		        //$this->db->where('user_id<>',  $this->session->userdata('user_id')   ); 
		        $this->db->delete($this->grp_2_usr);

		        foreach ($post['members'] as $member_id) {

					$group_user = array(
						'user_id' => $member_id,
						'group_id' => $post['group_id']
					);

					$this->db->insert($this->grp_2_usr, $group_user); 
		         	
		        } 
		}

		$this->session->set_flashdata('success_message', 'Group updated successfully.');

 
	}





		
		

	
}