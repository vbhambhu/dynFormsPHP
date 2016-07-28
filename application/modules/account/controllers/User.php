<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){

        parent::__construct();

        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }

        $this->load->library('form');
        $this->load->model('user_model');

        //Allow this class to super admin only.
        if(!$this->user_model->is_super_admin()){
            show_404();
        }
    }

    public function index(){

        $data['users'] = $this->user_model->get_users();
        $data['js_foot'] = array('jquery.dataTables.min', 'dataTables.bootstrap4.min', 'bootbox.min');
        $data['meta_title'] = "All users";

        $this->load->frontend('user/list', $data);

    }

    public function create(){

        $this->form
        ->open()
        ->text('name','Full name', 'trim|required|max_length[100]')
        ->text('username','Username', 'trim|required|max_length[60]')
        ->text('email','Email address', 'trim|required|valid_email|max_length[100]')
        ->password('password','New password', 'trim|required|max_length[100]|matches[cnf_password]')
        ->password('cnf_password','Confirm password', 'trim|required|max_length[100]')
        ->submit('Create account')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', 'account/user')
        ->model('account_model', 'create');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        //Meta info
        $data['meta_title'] = 'Create new user';
        $this->load->frontend('user/create', $data);

    }


    public function edit(){

        if(!$this->uri->segment(4) || strlen(trim($this->uri->segment(4) )) == 0 || !is_numeric($this->uri->segment(4)) ) {
            show_404();
        }

        $user_id = $this->uri->segment(4);
        $query = $this->db->get_where('user', array('id' => $user_id ),1);

        if($query->num_rows() == 0) {
            show_404();
        }

        $user = $query->row();
        $status = array('1' => 'Active', '2' => 'Disable');


        $this->form
        ->open()
        ->hidden('user_id', $user->id)
        ->text('name','Full name', 'trim|required|max_length[100]')->set_value($user->name)
        ->text('username','Username', 'trim|required|max_length[60]')->set_value($user->username)
        ->text('email','Email address', 'trim|required|valid_email|max_length[100]')->set_value($user->email)
        ->select('status', $status, 'Status')->set_value($user->status)
        ->password('password','New password (Optional)', 'trim|max_length[100]|matches[cnf_password]')
        ->html('<p>Leave <b>New password</b> & <b>Confirm password</b> field blank if you do not wish to update password.</p><hr>')
        ->password('cnf_password','Confirm password', 'trim|max_length[100]')
        ->submit('Update')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', 'account/user/edit/'.$user->id)
        ->model('account_model', 'update');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        //Meta info
        $data['meta_title'] = 'Edit user';
        
        $this->load->frontend('user/edit', $data);

    }


    public function delete() {


        if(!$this->permission_model->is_permitted('user', 'delete')){
            echo "No direct access";
            return;
        }


        if(!$this->uri->segment(4) || strlen(trim($this->uri->segment(4) )) == 0 || !is_numeric($this->uri->segment(4)) ) {
            show_404();
        }

        $id = $this->uri->segment(4);
        $query = $this->db->get_where('user', array('id' => $id ),1);

        if($query->num_rows() == 0) {
            show_404();
        }

        $user = $query->row();

        //if admin group then dont delete
        if($user->group_id == 1){
            $this->session->set_flashdata('error_message', 'User in admin group can not deleted.');
            return redirect('account/user');
        }


        $this->db->where('id', $id);
        $this->db->delete('user');
        $this->session->set_flashdata('success_message', 'User has been deleted successfully.');
        redirect('account/user');
    }

}