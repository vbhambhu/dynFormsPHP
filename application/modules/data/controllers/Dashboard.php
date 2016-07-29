<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();


        $this->session->set_userdata('user_id', 1);

        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }
    }

    public function index(){

        $data['meta_title'] = 'Dashboard';
        $this->load->backend('dashboard', $data);
       
    }


}