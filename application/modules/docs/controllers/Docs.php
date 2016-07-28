<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends CI_Controller {

    public function __construct(){

        parent::__construct();

        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }
        
        $this->load->model('docs_model');
        $this->load->library('form');
        $this->load->library('user_agent');
        
    }


    public function index(){

        $slug = $this->uri->segment(2);

        if(!$slug || strlen(trim($slug)) == 0){
            $slug = 'root';
        }

        if(!$dir = $this->docs_model->get_doc_by_slug($slug)){
            log_message('error', 'provided slug did not contain an id.');
            show_404();
        }

        $id = $dir->id;


        $data['permissions'] = $this->docs_model->get_doc_permissions($id);


        if(!$data['permissions']['read']){
            show_404();
        }

        $data['docs'] = $this->docs_model->get_docs_by_dir_id($id);
        $data['breadcrumb'] = $this->docs_model->get_breadcrumb_by_dir_id($id);

        $this->form
        ->open()
        ->hidden('dir_id', $id)
        ->text('folder','', 'trim|required|max_length[60]', '',array('class' => 'form-control-sm', 'placeholder' => 'Add new folder...'))
        ->submit('Create')
        ->add_class('btn btn-success btn-sm')
        ->html('<button class="btn btn-primary btn-sm" ngf-select="uploadFiles($files, $invalidFiles)" multiple accept="*" ngf-max-size="20MB" data-toggle="tooltip" data-placement="top" title="Upload files"><i class="fa fa-upload" aria-hidden="true"></i></button>')
        ->onsuccess('redirect', 'docs/'.$slug)
        ->model('docs_model', 'create_folder');

       
        $data['form'] = $this->form->get();
         

       


        $data['errors'] = $this->form->errors;
        $data['angular_app'] =  'fileManager';
        $data['dir_id'] = $id;

        $data['js_foot'] = array('jquery.dataTables.min', 'dataTables.bootstrap4.min','ng-file-upload.min','bootbox.min');
        
        $this->load->frontend('list', $data);
    }


     public function permission(){

        $doc = $this->get_doc_by_slug();

        $doc_type = ($doc->type == 1) ? 'Folder' : 'File';

        if($doc->type == 2){
            //not setting permission on files
            show_404();
        }

        

        $users = array();

        $query = $this->db->get_where('user_groups',  array('id<>' => 1) );
        foreach ($query->result() as $row) {
            $key = 'G-'.$row->id; 
            $users[$key] = $row->name;
        }

        $query = $this->db->get_where('user', array('status' => 1, 'id<>' => $doc->owner_id) );
        foreach ($query->result() as $row) {
            $key = 'U-'.$row->id; 
            $users[$key] = $row->name;
        }


        $this->form
        ->open()
        ->hidden('dir_id', $doc->id)
        ->select('type', $users,  'Select user or group')
        ->checkbox('read', '1', 'Can view', FALSE, 'trim')
        ->checkbox('edit', '1', 'Can edit (Delete or Rename)', FALSE, 'trim')
        ->checkbox('download', '1', 'Can download', FALSE, 'trim')
        ->checkbox('upload', '1', 'Can upload', FALSE, 'trim')
        ->submit('Add')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', 'docs/permission/'.$doc->slug)
        ->model('docs_model', 'add_permission');


        $data['doc'] = $doc;

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;


        $query = $this->db->get_where('permission', array('folder_id' => $doc->id));

        foreach ($query->result() as $row) {
            $row->type_name = $this->_get_type_name($row);
        }

        $data['permissions'] = $query->result();



         $data['css'] = array('select2.min');
       $data['js_foot'] = array('select2.min','bootbox.min');

        $this->load->frontend('permissions', $data);

    }


    public function delete_permission(){


        $id = $this->uri->segment(3);

        if(!$id || !is_numeric($id)){
            show_404();
        }

        $user_id = $this->session->userdata('user_id');

        //check if owner
        
        $sql = "SELECT documents.id,documents.name,documents.owner_id, documents.slug from permission 
                LEFT JOIN documents  ON (permission.folder_id = documents.id)
                WHERE permission.id = ".$id;

        $query = $this->db->query($sql);

        if($query->num_rows() == 0){
            show_404();
        }

        $row = $query->row();



        if($row->owner_id !=  $user_id){
            //maybe message here
            show_404();
        }


        //log
        $log = array(
            'doc_id' => $row->id,
            'event' => 'Permission removed',
            'doc_name' => $row->name,
            'user_id' => $this->session->userdata('user_id'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->agent->agent_string(),
            'created_at' =>  date('Y-m-d H:i:s')
        );

        $this->db->insert('document_logs', $log);


        $this->db->delete('permission', array('id' => $id) );
        $this->session->set_flashdata('success_message', 'Permission removed successfully.');
        redirect('docs/permission/'.$row->slug);

    }


    private function _get_type_name($row){


        $tbl = ($row->type == 'user') ? "user" : "user_groups";
        $query = $this->db->get_where($tbl, array('id' => $row->type_id));
        $result = $query->row(); 

        return $result->name;

    }





    private function get_doc_by_slug($segment = 3){

        $slug = $this->uri->segment($segment);

        if(!$slug || strlen(trim($slug)) == 0){
            show_404();
        }

        if(!$doc = $this->docs_model->get_doc_by_slug($slug)){
            log_message('error', 'provided slug did not contain an id.');
            show_404();
        }

        return $doc;

    }





















    public function download(){

        $this->load->helper('download');

        $doc = $this->get_doc_by_slug();

        $data['permissions'] = $this->docs_model->get_doc_permissions($doc->id);

        if(!$data['permissions']['download']){
            show_404();
        }


        if($doc->type == 1) { // folder
            $dirs = $this->docs_model->download_folder($doc);
        } else {
            $this->docs_model->download_file($doc);
        }

    }

   


    public function delete(){

        $doc = $this->get_doc_by_slug();

        $data['permissions'] = $this->docs_model->get_doc_permissions($doc->id);

        if(!$data['permissions']['edit']){
            show_404();
        }


        $doc_type = ($doc->type == 1) ? 'Folder' : 'File';

        if($doc->type == 2){ // file

            $this->docs_model->delete_file_by_id($doc->id);
            $this->session->set_flashdata('success_message','File has been deleted successfully.');

        } else { //folder
            $this->docs_model->delete_folder_by_id($doc->id);
            $this->session->set_flashdata('success_message','Folder and containing files has been deleted successfully.');

        }

        $parent = $this->db->get_where('documents', array('id' => $doc->parent_id),1)->row();
        redirect('docs/'.$parent->slug);

    }


    public function rename(){

        $doc = $this->get_doc_by_slug();

        if($doc->type == 1){
            $file_name = $doc->name;
        } else{
            $path_info = pathinfo($doc->path);
            $file_name = $path_info['filename'];
        }


        $data['permissions'] = $this->docs_model->get_doc_permissions($doc->id);

        if(!$data['permissions']['edit']){
            show_404();
        }


         $this->form
        ->open()
        ->hidden('doc_id', $doc->id)
        ->text('name', 'New name',  'trim|required|max_length[60]')->set_value($file_name)
        ->submit('Update')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', 'docs/'.$doc->slug)
        ->model('docs_model', 'rename');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        $this->load->frontend('rename', $data);

    }


     public function upload(){

        $upload_path = $this->config->item('upload_path').date("Y").'/'.date("m").'/'.date("d");
        if (!file_exists($upload_path)) {  mkdir($upload_path, 0777, true); }


        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = '*';
        $config['max_size']      = 20000; 

         $this->load->library('upload', $config);
            
         if ( ! $this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors()); 
            echo $this->upload->display_errors();
            //$this->load->view('upload_form', $error); 
         }
            
         else { 
            $data = array('upload_data' => $this->upload->data());


            $new_file = array(
                'type' => 2,
                'name' => $data['upload_data']['file_name'],
                'slug' => md5(rand().microtime()),
                'owner_id' => $this->session->userdata('user_id'),
                'path' => $upload_path.'/'.$data['upload_data']['file_name'],
                'parent_id' =>  $this->input->post('dir_id') ,
                'created_at' =>  date('Y-m-d H:i:s')
            );

            $this->db->insert('documents', $new_file);


            //add log
            $this->load->library('user_agent');

            $log = array(
                'doc_id' => $this->db->insert_id(),
                'event' => 'Created',
                'doc_name' => $new_file['name'],
                'user_id' => $this->session->userdata('user_id'),
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->agent->agent_string(),
                'created_at' =>  date('Y-m-d H:i:s')
            );
        

            $this->db->insert("document_logs", $log);

            $this->output->set_content_type('application/json')->set_output(json_encode($data));

         } 

     }



    public function history(){


        $doc = $this->get_doc_by_slug(3);


        
        $data['history'] = $this->docs_model->get_doc_history($doc->id);

        $this->load->frontend('history', $data);

    }





}