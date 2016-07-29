<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CI_Controller {

    public function __construct(){

        parent::__construct();

        // if(!$this->session->userdata('user_id')){
        //     redirect('account/login');
        // }

    }

    public function index(){

        $data['cubes'] = $this->db->get('cubes')->result();

        $this->load->backend('cubes/list', $data);
       
    }


    public function create(){

        $new_cube = array(
            'name' => 'Untitled',
            'description' => 'Write description here ...',
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('cubes', $new_cube);

        $id = $this->db->insert_id();
        redirect('data/cube/edit?id='.$id);

    }

     public function edit(){

        $id = $this->input->get('id');

        if(!is_numeric($id) || is_null($id)){
            show_404();
        }

        $query = $this->db->get_where('cubes', array('id' => $id), 1);

        if($query->num_rows() == 0){
            show_404();
        }

        $cube = $query->row();
        $data['cube'] = $cube;

        $this->load->backend('cubes/edit', $data);
       
    }


}