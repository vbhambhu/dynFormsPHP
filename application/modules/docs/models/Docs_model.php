<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Docs_model extends CI_Model{

	private $doc_tbl = 'documents';
	private $doc_log_tbl = 'document_logs';


	public function create_folder($id){

		$post = $this->form->get_post();
		$query = $this->db->get_where($this->doc_tbl, array('name' => $post['folder'], 'parent_id' => $post['dir_id'] ));

		if($query->num_rows() > 0){
			$this->form->add_error('Folder already exists.');
			return;
		}

		$folder = array(
			'type' => 1,
			'name' => $post['folder'],
			'slug' => $this->get_slug($post['folder']),
			'parent_id' => $post['dir_id'],
			'owner_id' => $this->session->userdata('user_id'),
			'created_at' =>  date('Y-m-d H:i:s')
		);

		$this->db->insert($this->doc_tbl, $folder);

		$folder_id = $this->db->insert_id();

		//set owner permission
		$permission = array(
			'folder_id' => $folder_id,
			'type' => 'user',
			'type_id' => $this->session->userdata('user_id'),
			'permissions' => 1111,
			'is_protected' => 1
		);


		$this->db->insert('permission', $permission);



		//add log
		$this->load->library('user_agent');

		$log = array(
			'doc_id' => $folder_id,
			'event' => 'Create',
			'doc_name' => $post['folder'],
			'user_id' => $this->session->userdata('user_id'),
			'ip_address' => $this->input->ip_address(),
			'user_agent' => $this->agent->agent_string(),
			'created_at' =>  date('Y-m-d H:i:s')
		);
		
		$this->db->insert($this->doc_log_tbl, $log);

		$this->session->set_flashdata('success_message', 'Folder created successfully.');
	}




	public function delete_file_by_id($id){

		$this->db->where('id', $id);
		$this->db->update($this->doc_tbl , array('status' => 0));

		//add log
		$doc = $this->db->get_where($this->doc_tbl , array('id' => $id))->row();

        $this->load->library('user_agent');

        $log = array(
			'doc_id' => $id,
			'event' => 'Delete',
			'doc_name' => $doc->name,
			'user_id' => $this->session->userdata('user_id'),
			'ip_address' => $this->input->ip_address(),
			'user_agent' => $this->agent->agent_string(),
			'created_at' =>  date('Y-m-d H:i:s')
		);
		
		$this->db->insert($this->doc_log_tbl, $log);


		return true;
		
	}

	public function delete_folder_by_id($id){


		$this->db->where('id', $id);
		$this->db->update($this->doc_tbl , array('status' => 0));

		$doc = $this->db->get_where($this->doc_tbl , array('id' => $id))->row();

		$log = array(
			'doc_id' => $id,
			'event' => 'Delete',
			'doc_name' => $doc->name,
			'user_id' => $this->session->userdata('user_id'),
			'ip_address' => $this->input->ip_address(),
			'user_agent' => $this->agent->agent_string(),
			'created_at' =>  date('Y-m-d H:i:s')
		);
		
		$this->db->insert($this->doc_log_tbl, $log);

		$query = $this->db->get_where( 'documents' , array('parent_id' => $id, 'status' => 1) );

		foreach ($query->result() as $row) {

            if($row->type == 1){ //folder
                $this->delete_folder_by_id($row->id);
            } else {
               	$this->db->where('id', $row->id);
				$this->db->update($this->doc_tbl , array('status' => 0));

				//log
				$log = array(
					'doc_id' => $row->id,
					'event' => 'Delete',
					'doc_name' => $row->name,
					'user_id' => $this->session->userdata('user_id'),
					'ip_address' => $this->input->ip_address(),
					'user_agent' => $this->agent->agent_string(),
					'created_at' =>  date('Y-m-d H:i:s')
				);

				$this->db->insert($this->doc_log_tbl, $log);

            }
        }

		return true;
	}


	public function get_doc_by_slug($slug = null){

		$query = $this->db->get_where($this->doc_tbl , array('slug' => $slug), 1);
		return ($query->num_rows() == 1) ? $query->row() : NULL;
	}

	public function add_permission(){

		$post = $this->form->get_post();

		if(!$post['read'] && !$post['edit'] && !$post['download'] && !$post['upload']){
			$this->form->add_error('Please select atleast one permission from checkbox.');
			return;
		}

		$read = (isset($post['read'][0])) ? 1 : 0;
		$edit = (isset($post['edit'][0])) ? 1 : 0;
		$download = (isset($post['download'][0])) ? 1 : 0;
		$upload = (isset($post['upload'][0])) ? 1 : 0;
					
		$p = explode('-', $post['type'][0]);
		$type = ($p[0] == 'G') ? 'group' : 'user';
		$type_id = $p[1];

		$query = $this->db->get_where('permission', array('folder_id' => $post['dir_id'],'type' => $type,'type_id' => $type_id) );

		$permission = array(
			'folder_id' => $post['dir_id'],
			'type' => $type,
			'type_id' => $type_id,
			'permissions' => $read.$edit.$download.$upload
		);

		if($query->num_rows() == 0){

			$this->db->insert('permission', $permission);

		} else{

			$this->db->where('folder_id', $post['dir_id']);
			$this->db->where('type', $type);
			$this->db->where('type_id', $type_id);
			$this->db->update('permission', array('permissions' => $read.$edit.$download.$upload) );
		}


		//log
		$doc = $this->db->get_where($this->doc_tbl , array('id' => $post['dir_id']))->row();

        $log = array(
            'doc_id' => $doc->id,
            'event' => 'Permission added',
            'doc_name' => $doc->name,
            'user_id' => $this->session->userdata('user_id'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->agent->agent_string(),
            'created_at' =>  date('Y-m-d H:i:s')
        );

        $this->db->insert($this->doc_log_tbl, $log);

		
		$this->session->set_flashdata('success_message', 'Permission added successfully.');

	}


	public function rename(){

		$post = $this->form->get_post();

		$query = $this->db->get_where($this->doc_tbl, array('id' => $post['doc_id'] ));
		$row = $query->row();

		if($post['name'] == $row->name){
			$this->form->add_error("Can't change with same name.");
			return;
		}


		$query = $this->db->get_where($this->doc_tbl, array('id<>' => $post['doc_id'], 'parent_id' => $row->parent_id, 'name' => $post['name'] ));

		if($query->num_rows() > 0){
			$this->form->add_error('Another file or folder with this name alreay exists.');
			return;
		}


		if($row->type == 2){ //file
			$path_info = pathinfo($row->path);
			$new_path = $path_info['dirname'].'/'.$post['name'].'.'.$path_info['extension'];
			rename($row->path , $new_path);

			$this->db->where('id', $post['doc_id']);
			$this->db->update($this->doc_tbl, array('name' => $post['name'].'.'.$path_info['extension'], 'path' => $new_path) );
		} else{
			$this->db->where('id', $post['doc_id']);
			$this->db->update($this->doc_tbl, array('name' => $post['name']) );
		}

		

		
		
	}




	public function get_doc_history($id){


		$sql = 'SELECT lg.event,lg.doc_name,lg.ip_address, lg.user_agent, lg.created_at, usr.name
		FROM document_logs lg 
		LEFT JOIN user usr ON (lg.user_id = usr.id) 
		WHERE lg.doc_id ='.$id.' ORDER BY lg.created_at DESC';

		$query = $this->db->query($sql);
		return $query->result();
	}




	

	public function get_doc_permissions($folder_id){

		//echo $folder_id;

		$perms = array('read' => false, 'edit' => false, 'download' => false, 'upload' => false);

		//if admin then all permissions
		if($this->is_admin()){
			return array('read' => true, 'edit' => true, 'download' => true, 'upload' => true);
		}

		//if root folder
		if($folder_id == 1){
			return array('read' => true, 'edit' => false, 'download' => false, 'upload' => false);
		}

		//if owner then all permissions
		if($this->is_owner($folder_id)){
			return array('read' => true, 'edit' => true, 'download' => true, 'upload' => true);
		}

		//check by groups and user
		$combined_perms = array();

		//for user
		$user_id = $this->session->userdata('user_id');
		$query = $this->db->get_where('permission', array('folder_id' => $folder_id , 'type' => 'user', 'type_id' => $user_id ), 1);

		if($query->num_rows() > 0){
			$row = $query->row();
			$combined_perms[] = array(
				'read' => substr($row->permissions, 0, 1),
				'edit' => substr($row->permissions, 1, 1), 
				'download' => substr($row->permissions, 2, 1),
				'upload' => substr($row->permissions, 3, 1)
			);
		}

		//for groups

		$effective_folder_id = $this->get_parent_with_group_permission($folder_id);
		$sql = "SELECT permission.permissions FROM group_to_users 
				LEFT join permission ON (group_to_users.group_id = permission.type_id)
				where group_to_users.user_id = ".$user_id."
				AND permission.type = 'group'
				AND permission.folder_id =". $effective_folder_id;

		$query = $this->db->query($sql);

		foreach ($query->result() as $row) {
			$combined_perms[] = array(
				'read' => substr($row->permissions, 0, 1),
				'edit' => substr($row->permissions, 1, 1), 
				'download' => substr($row->permissions, 2, 1),
				'upload' => substr($row->permissions, 3, 1)
			);
		}

		//union permissions
		foreach ($combined_perms as $key => $value) {
			if(!$perms['read'] && $value['read'] == 1) $perms['read'] = true;
			if(!$perms['edit'] && $value['edit'] == 1) $perms['edit'] = true;
			if(!$perms['download'] && $value['download'] == 1) $perms['download'] = true;
			if(!$perms['upload'] && $value['upload'] == 1) $perms['upload'] = true;
		}


		return $perms;
	}


	private function get_parent_with_group_permission($folder_id){

        $query = $this->db->get_where('permission', array('folder_id' => $folder_id, 'type' => 'group' ),1);
        if($query->num_rows() == 1){
        	return $folder_id;
        } else {
        	$query = $this->db->get_where('documents', array('id' => $folder_id, 'status' => 1 ),1);
        	$doc = $query->row();
        	return $this->get_parent_with_group_permission($doc->parent_id);
        }

    }
	
	
	public function get_docs_by_dir_id($parent_id){

		$sql = "SELECT d.*,u.name as owner
		FROM documents d
		LEFT JOIN user u ON (d.owner_id = u.id)
		WHERE d.status = 1 AND d.parent_id = ".$parent_id;
		
		$query = $this->db->query($sql);

		foreach ($query->result() as $row) {

			$row->permissions = $this->get_doc_permissions($row->id);

			// if($row->type == 1){
			// 	$row->permissions = $this->get_doc_permissions($row->id);
			// } else{
			// 	$row->permissions = array('read' => true, 'edit' => true, 'download' => true, 'upload' => true);
			// }
		}

		return $query->result();

	}


	private function is_owner($folder_id){

		$user_id = $this->session->userdata('user_id');
		$query = $this->db->get_where($this->doc_tbl ,  array('id' => $folder_id,'owner_id' => $user_id),1);
		return ($query->num_rows() == 1) ? TRUE : FALSE;

	}


	private function is_admin(){

		$user_id = $this->session->userdata('user_id');
		$query = $this->db->get_where("group_to_users" ,  array('user_id' => $user_id,'group_id' => 1),1);
		return ($query->num_rows() == 1) ? TRUE : FALSE;

	}

	
	public function download_file($doc){

		$data = file_get_contents($doc->path);
		$name = $doc->name;
		$this->doc_log($doc->id, 'Download', $doc->name); //log
		force_download($name, $data);

	}

	public function download_folder($doc){

		$this->doc_log($doc->id, 'Download', $doc->name); //log
		$this->load->library('zip');
		$this->zip->add_dir($doc->name);
		$this->inner_download_folder($doc->id, $doc->name);
		$this->zip->download($doc->name.'.zip');
	}

	public function inner_download_folder($id, $path = ""){
	
		$query = $this->db->get_where( 'documents' , array('parent_id' => $id,'status' => 1) );

	    foreach ($query->result() as $row) {

        	if($row->type == 1){
        		$this->zip->add_dir($path . '/' . $row->name);
				$this->doc_log($row->id, 'Download', $row->name); //log
        		$this->inner_download_folder($row->id, $path . '/' . $row->name);
        	} else{
				$this->doc_log($row->id, 'Download', $row->name); //log
            	$data = file_get_contents($row->path);
            	$this->zip->add_data($path . '/' . $row->name, $data);
        	}
        }

	}


	public function doc_log($doc_id, $event = null, $doc_name){

		//log
		$log = array(
            'doc_id' => $doc_id,
            'event' => $event,
            'doc_name' => $doc_name,
            'user_id' => $this->session->userdata('user_id'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->agent->agent_string(),
            'created_at' =>  date('Y-m-d H:i:s')
        );

        $this->db->insert($this->doc_log_tbl, $log);

	}

	


	public function get_breadcrumb_by_dir_id($id) {

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
			$data[] = array('id' => $parent->id, 'name' => $parent->name, 'slug' => $parent->slug);
			$current_pid = $parent->parent_id;
		}


		//order array
		$order = array();
		foreach ($data as $key => $row){
			$order[$key] = $row['id'];
		}

		array_multisort($order, SORT_ASC, $data);
		$data[] = array('name' => $current->name);
		return $data;
	}


	//if chid dir contains folder with same name then try to generate uniques slug

	public function get_slug($folder_name) {

		$slug = seo_url($folder_name);

		$query = $this->db->get_where($this->doc_tbl , array('slug' =>  $slug ) ); 

		if($query->num_rows() == 0){
			return $slug;
		} else{
			return $slug.'-'.time();
		}

	}

	

	

}