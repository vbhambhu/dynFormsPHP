<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();


        $this->session->set_userdata('user_id', 1);

        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }

        $this->load->library('form');
    }

    public function index(){


        $this->form->open();
        $this->form->text("username",'Username','required','', array('id' => 'hello', 'class' => 'world'));
        $this->form->text("password",'Password','required');

        $this->form->submit('Save');
       
        //$this->form->onsuccess('redirect', 'docs')
        $this->form->model('Record_model', 'test');

    
        $data['form'] = $this->form->get();

        $data['meta_title'] = 'Dashboard';
        $this->load->frontend('dashboard', $data);
       
    }


}