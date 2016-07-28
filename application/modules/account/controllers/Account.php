<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->library('form');

    }

    public function login(){

        // If users is already login
        if($this->session->userdata('user_id')){
            redirect('docs');
        }
        
        $this->form
        ->open()
        ->text('username','Username', 'trim|required|max_length[60]')
        ->password('password','Password', 'trim|required|max_length[100]');

        if($this->_is_login_limit_exceed()){
            $this->form->recaptcha('Verify text');
        }

        $this->form
        ->submit('Login')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', 'docs')
        ->model('account_model', 'login');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        //Meta info
        $data['meta_title'] = 'Filestore :: Kennedy';

        $this->load->basic('account/login', $data);
    }

   
    public function forgot(){

        if($this->session->userdata('user_id')){
            redirect('docs');
        }

        $this->form
            ->open()
            ->text('email','Your email', 'trim|required|valid_email')
            ->recaptcha('Verify text')
            ->submit('Submit') 
            ->add_class('btn btn-success')   
            ->onsuccess('redirect', 'account/forgot')
            ->model('account_model', 'forgot_password');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        //Meta info
        $data['meta_title'] = 'Forgot Password';
        $data['meta_description'] = 'Reset your password on orooms';

        $this->load->basic('account/forgot', $data);
    }


    public function set_password(){

        if($this->session->userdata('user_id')){
            redirect('docs');
        }

        if(!$this->session->userdata('reset_password_id')){
            show_404();
        }

        
        $this->form
        ->open()
        ->password('new_password','New password', 'trim|required|min_length[5]|max_length[50]|matches[confirm_password]')
        ->password('confirm_password','Confirm new password', 'trim|required|min_length[5]|max_length[50]')
        ->submit('Update')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', 'account/login')
        ->model('account_model', 'set_new_password');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        //Meta info
        $data['meta_title'] = 'Set your new password.';
        $this->load->frontend('user/set_new_password', $data);
    }


    public function reset_password(){

        if($this->session->userdata('user_id')){
            redirect('docs');
        }

        $user_id        = $this->uri->segment(3);
        $new_pass_key   = $this->uri->segment(4);

        if(!is_numeric($user_id) || strlen($new_pass_key) < 5){
            show_error('Can"t perform this action.' , 500);
            return;
        }

        $this->load->model('account_model');

       if(!$this->account_model->can_reset_password($user_id, $new_pass_key) ){
            show_error('Password reset link invalid or expired.' , 500);
            return;
       }

       $this->session->set_userdata('reset_user_id', $user_id);
       $this->session->set_userdata('reset_new_pass_key', $new_pass_key );
        
        $this->form
        ->open()
        ->password('new_password','New password', 'trim|required|min_length[5]|max_length[50]|matches[confirm_password]')
        ->password('confirm_password','Confirm new password', 'trim|required|min_length[5]|max_length[50]')
        ->submit('Update')
        ->add_class('btn btn-success')
        ->onsuccess('redirect', 'account/login')
        ->model('account_model', 'reset_password');

        $data['form'] = $this->form->get();
        $data['errors'] = $this->form->errors;

        //Meta info
        $data['meta_title'] = 'Reset your new password.';
        $data['meta_description'] = 'Reset password.';
        $this->load->frontend('user/reset_password', $data);
    }



    public function logout(){

        //first unset and then destroy
        $this->session->unset_userdata(array(
            'user_id' => '',
            )
        );

        $this->session->sess_destroy();
        redirect('/', 'location');
    }

    private function _is_login_limit_exceed(){

        $this->load->model('account_model');

        if($this->account_model->get_attempts_count('') >= 3) return TRUE;
        return FALSE;
    }

}