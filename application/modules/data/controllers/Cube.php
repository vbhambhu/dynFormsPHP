<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cube extends CI_Controller {

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

        $data['dasdsa'] = "ds";

        $this->load->backend('cubes/create', $data);
       
    }


}