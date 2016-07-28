<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class API extends CI_Controller {


    private $doc_tbl    = 'documents';

    public function __construct(){

        parent::__construct();


        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }
        
        //$this->load->model('docs_model');

    }


    public function create_folder(){

        if($this->input->post('folder_name') && $this->input->post('parent_folder_id')){

            $pid = $this->input->post('parent_folder_id');
            $name = $this->input->post('folder_name');

            //check if already exists
            $query = $this->db->get_where($this->doc_tbl, array('name' => $name ));

            if($query->num_rows() > 0){
                echo "Folder with similar name already exists.";
                return;
            }

            $folder = array(
                'type' => 1,
                'name' => $name ,
                'parent_id' => $pid,
                'created_at' =>  date('Y-m-d H:i:s')
            );

            $this->db->insert($this->doc_tbl, $folder);

            echo $this->db->insert_id();
            return;
            
        } else{
            echo "Please enter folder name.";
            return;
        }


    }





    public function get_folder_permission(){


        if(!$this->input->get('fid')){

            $data = false;

        } else {

          $id = $this->input->get('fid');

          if(!$this->session->userdata('folder_ids')){ // if not set at all

                $this->session->set_userdata('folder_ids', array( $id ));
                $data = false;
          
          } else if(in_array($id, $this->session->userdata('folder_ids'))){ // already exists
                
                $data = false;

          } else if($this->parent_not_added($id)){ // if not exists

            $dd = $this->session->userdata('folder_ids');
            $dd[] = $id;

             $this->session->set_userdata('folder_ids', $dd);
             $data = false;

          } else{


            $data = false;

          }

            
        }


        //$this->session->unset_userdata('folder_ids');


        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));

        
        
    }


    private function parent_not_added($id){


        $added_folders = $this->session->userdata('folder_ids');

        //1 => 7
        foreach ($added_folders as $added_folder) {
            echo $id;
        }

        return false;
    }


    public function get_child_folder_ids(){

        $data = array();

        $refs = array();
        $list = array();

        $query = $this->db->query('SELECT * FROM documents WHERE type = 1');

        foreach ($query->result() as $row) {

            $ref = &$refs[$row->id];

            
            

            if ($row->parent_id == 0){
                $list[$row->id] = & $ref;
            } else {
                $refs[$row->parent_id][$row->id] = & $ref;
            }
        }

        echo '<pre>'; print_r( $list);
    }


    public function get_folders(){


        $search = $this->input->get('q');

        $sql = "SELECT id,name FROM documents WHERE type = 1 AND name LIKE '%".$this->db->escape_like_str($search)."%'";


        $query = $this->db->query($sql);


        $data = array();

        foreach ($query->result() as $row) {

            $data[] = array('id' => $row->id, 'name' => $row->name);
             
        } 


        $result['items'] = $data; 
        $result['total_count'] = 22; 

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));

    }



}