<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Collection extends CI_Controller {

    public function __construct(){

        parent::__construct();

        // if(!$this->session->userdata('user_id')){
        //     redirect('account/login');
        // }

    }

    public function index(){

        $data['collections'] = $this->db->get('collections')->result();

        $this->load->backend('collections/list', $data);
       
    }


}