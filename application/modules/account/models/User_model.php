<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends CI_Model{

	private $usr_tbl = 'user';
	private $g2u_tbl = 'group_to_users';

	/**
	 * Return list of all users
	 *
	 * @param	null
	 * @return	int
	 */
	public function get_users(){

		$this->db->select('id,username,name,email,status,last_login,created_at');
		$query = $this->db->get($this->usr_tbl);
		return $query->result();

	}


	/**
	 * Return bool if logged in user is part of super admin group
	 *
	 * @param	null
	 * @return	boolean
	 */
	public function is_super_admin(){

		if(!$this->session->userdata('user_id')){
			return FALSE;
		}

		$this->db->select('1', FALSE);
		$this->db->where('user_id', $this->session->userdata('user_id') );
		$this->db->where('group_id', 1);
		$query = $this->db->get($this->g2u_tbl);

		if($query->num_rows() == 0){
			return FALSE;
		}

		return TRUE;
	}
		
		

	
}