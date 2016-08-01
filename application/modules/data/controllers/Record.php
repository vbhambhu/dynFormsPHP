<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Record extends CI_Controller {

    public function __construct(){

        parent::__construct();


        $this->load->library('form');

        // if(!$this->session->userdata('user_id')){
        //     redirect('account/login');
        // }

    }

    public function index(){

        $id = $this->input->get('id');

        if(!is_numeric($id) || is_null($id)){
            show_404();
        }

        $query = $this->db->get_where('cubes', array('id' => $id), 1);

        if($query->num_rows() == 0){
            show_404();
        }

        $cube = $query->row();


        $query = $this->db->get_where('cube_attributes', array('cube_id' => $cube->id));

        $this->form->open('/data/record?id=1')
        ->hidden('cube_id', 1)
        ->html('<h3>'.$cube->name.'</h3>')
        ->html($cube->description.'<hr>');
        //->text('username','Username', 'trim|required|max_length[60]')
        //->password('password','Password', 'trim|required|max_length[100]')

        foreach ($query->result() as $row) {
            
            if($row->type == 'text'){
                $this->form->text($row->identifier,$row->label, $row->validation_rule);
            }

        }



        $this->form->submit('Save')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', '/data/record?id=1')
        ->model('record_model', 'save_data');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        $this->load->backend('form/insert', $data);
       
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