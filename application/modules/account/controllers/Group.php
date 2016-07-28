<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }
        
        $this->load->model('group_model');
    }

    public function index(){
      
        $data['my_groups'] = $this->group_model->get_my_groups();
        $data['other_groups'] = $this->group_model->get_other_groups();
        $data['js_foot'] = array('jquery.dataTables.min', 'dataTables.bootstrap4.min','bootbox.min');
        $this->load->frontend('group/list', $data);
    }


    public function create(){


      $this->load->library('form');


    $this->form
      ->open()
      ->text('name', 'Group name' , 'required|trim|min_length[3]|max_length[30]')
      ->submit('Create','btn',"class=btn-success")
      ->onsuccess('redirect', 'account/group')
      ->model('group_model', 'create');

      $data['form'] = $this->form->get();
      $data['errors'] = $this->form->errors;
     
      $this->load->frontend('group/create',$data);


    }


    public function edit(){

        $this->load->library('form');

        if(!$this->uri->segment(4) || strlen(trim($this->uri->segment(4) )) == 0 || !is_numeric($this->uri->segment(4)) ) {
            show_404();
        }

        $group_id = $this->uri->segment(4);
        $query = $this->db->get_where('user_groups', array('id' => $group_id ),1);

        if($query->num_rows() == 0) {
            show_404();
        }

        $group = $query->row();

        $query = $this->db->get_where('user', array('status' => 1 ));



        foreach ($query->result() as $row) {
            $users[$row->id] = $row->name; 
        }

         $query = $this->db->get_where('group_to_users',  array('group_id' => $group->id));


        $selected_users = array();

          foreach ($query->result() as $row) {
            $selected_users[] = $row->user_id;
          }



        $this->form
        ->open()
        ->hidden('group_id', $group->id)
        ->text('name', 'Group name' , 'required|trim|min_length[3]|max_length[30]')->set_value($group->name)
        ->select('owner_id',$users, 'Owner')->set_value($group->owner_id)
        ->select('members',$users, 'Members','','','multiple=multiple')->set_value(implode(',', $selected_users))
        ->submit('Update','btn',"class=btn-success")
        ->onsuccess('redirect', 'account/group/edit/'.$group->id)
        ->model('group_model', 'update');

        $data['group'] = $group;

      $data['form'] = $this->form->get();
      $data['errors'] = $this->form->errors;


       $data['css'] = array('select2.min');
       $data['js_foot'] = array('select2.min');

     
      $this->load->frontend('group/edit',$data);


    }


    public function delete(){

        if(!$this->uri->segment(4) || strlen(trim($this->uri->segment(4) )) == 0 || !is_numeric($this->uri->segment(4)) ) {
            show_404();
        }

        $group_id = $this->uri->segment(4);
        $query = $this->db->get_where('user_groups', array('id' => $group_id ),1);

        if($query->num_rows() == 0) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');

        $group = $query->row();

        if($group->owner_id != $user_id){
            die("This action not allowed!");
        }


        //check if 


    }

   



}