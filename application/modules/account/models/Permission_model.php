<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Permission_model extends CI_Model{

	
	private $grp_tbl = 'user_groups';
	private $doc_tbl	= 'documents';
	
	
	public function is_permitted($resource = null, $action = null, $folder_id = null){

		if(is_null($resource) || is_null($action) || !$this->session->userdata('user_id') || !$this->session->userdata('group_id')){
			return false;
		}

		$query = $this->db->get_where($this->grp_tbl, array('id' => $this->session->userdata('group_id') ));
		$group = $query->row();

		if($group->permissions == "*"){
			return true;
		}

		$permissions = json_decode($group->permissions, TRUE);

		if($resource == 'folders' && !is_null($folder_id)){


			if(isset($permissions[$resource][$folder_id])){
				return ($permissions[$resource][$folder_id][$action]) ? true : false;
			}

			// if not permission to current folder check permission for parent


			foreach ($this->get_parents($folder_id) as $parent_id) {
				if(isset($permissions[$resource][$parent_id])){
					return ($permissions[$resource][$parent_id][$action]) ? true : false;
					break;
				}
			}


			//if not permisson set at all then make false
			return false;


		
		} else {
			return ($permissions[$resource][$action]) ? true : false;
		}

		return false;



		//echo '<pre>'. print_r($permissions); die;

		

		
	}

	private function get_parents($id){

		$data = array();

		if($id == 0){
			return null;
		}

		$query = $this->db->get_where( $this->doc_tbl , array('id' => $id) );
		$current = $query->row();
		$current_pid = $current->parent_id;
		$parent_exists = true;

		while ($parent_exists) {
			$query = $this->db->get_where( $this->doc_tbl , array('id' => $current_pid) );
			if($query->num_rows() == 0){
				$parent_exists = false;
				break;
			}

			$parent = $query->row();
			$data[] = $parent->id;
			$current_pid = $parent->parent_id;
		}

		//sort($data);

		return $data;
	}




}















