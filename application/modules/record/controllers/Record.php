<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Record extends CI_Controller {

    public function __construct(){

        parent::__construct();

        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }

    }

    public function entry(){


        $slug = $this->uri->segment(3);

        if(is_null($slug)){
            show_404();
        }

        $query = $this->db->get_where('forms', array('slug' => $slug), 1);

        if($query->num_rows() == 0){
            show_404();
        }

        $this->load->library('form');

        $form = $query->row();

        $query = $this->db->get_where('fields', array('form_id' => $form->id));

        $this->form->open('record/entry/'.$form->slug);
        $this->form->hidden('form_id', $form->id);
        //->html('<h3>'.$cube->name.'</h3>')
        //->html($cube->description.'<hr>');
        //->text('username','Username', 'trim|required|max_length[60]')
        //->password('password','Password', 'trim|required|max_length[100]')

        foreach ($query->result() as $row) {
            
            if($row->type == 'text'){
                $this->form->text($row->identifier, $row->label , $row->validation_rule);
            }

            if($row->type == 'textarea'){
                $this->form->textarea($row->identifier, $row->label , $row->validation_rule);
            }

            if($row->type == 'multiple'){
                $this->form->radio($row->identifier, $row->label , array('' => 'sss','dd' => 'ddd'), $row->validation_rule);
            }

            if($row->type == 'check'){
                $this->form->form_checkbox($row->identifier, $row->label , array('' => 'sss','dd' => 'ddd'), $row->validation_rule);
            }

        }



        $this->form->submit('Save')
        //->add_class('btn btn-success')
        //->onsuccess('redirect', '/data/record?id=1')
        ->model('record_model', 'save_data');

        $data['form'] = $this->form->get();
       // $data['errors'] = $this->form->errors;

        $this->load->frontend('entry', $data);
       
    }



    public function datatable(){

        $start = (int)$this->input->get('start');
        $limit = (int)$this->input->get('length');
        $search_arr = $this->input->get('search');

        //$fields = array('ar.title', 'c.name', 'a.name', 'ar.status', 'ar.view_count', 'ar.created_at');

        $order_arr = json_decode(json_encode($this->input->get('order')));
        $order_arr = $order_arr[0];
        //$order_field = $fields[$order_arr->column];

        $results = array();


        $sql = "SELECT * FROM forms";

        if(isset($search_arr["value"]) && strlen($search_arr["value"]) > 0){

            $search_query = $search_arr["value"];

            //  foreach ( $fields as $field_key => $field_value) {

            //     if($field_key == 0){
            //         $sql .= ' WHERE  '.$field_value.' LIKE "%'.$search_query.'%" ';
            //     } else{
            //          $sql .= ' OR  '.$field_value.' LIKE "%'.$search_query.'%" ';
            //     }
            // }
        }


        $total_records = $this->db->query($sql)->num_rows();


        //$sql .= ' ORDER BY '.$fields[$order_arr->column].' '.strtoupper($order_arr->dir).' ';
        $sql .= ' LIMIT  '.$start.', '.$limit;


        $query = $this->db->query($sql);

        foreach ($query->result() as $row) {


        $delete_attr = array(
          'class' => "btn btn-danger btn-xs confirmx",
          'src' => "admin/articles/edit/".$row->id,
          );


      

        $action_btns = '<div class="btn-group" role="group">';
        $action_btns .= anchor('admin/articles/edit/'.$row->id,'<i class="fa fa-pencil"></i> edit','class="btn btn-default btn-xs"');
        $action_btns .= anchor("admin/articles/edit/".$row->id,'<i class="fa fa-trash"></i> delete', $delete_attr);
        $action_btns .= '</div>';

           $results[] = array(
            $row->name,
            $row->description,
            $row->name,
            $row->name,
            $row->name,
            date("j F Y, H:i", $row->created_at),
            $action_btns
            );
        }




        $data['draw'] = (int)$this->input->get('draw');
        $data['recordsTotal'] = $total_records;
        $data['recordsFiltered'] =  $total_records;
        $data['data'] = $results;


        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
  

  }


    public function create(){

        $slug = uniqid();

        $new_form = array(
            'slug' => $slug,
            'name' => 'Untitled',
            'description' => 'Write description here ...',
            'owner' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('forms', $new_form);

        $id = $this->db->insert_id();
        redirect('form/edit/'.$slug);

    }

     public function edit(){

        //$slug = $this->input->get('id');

        $slug = $this->uri->segment(3);

        if(is_null($slug)){
            show_404();
        }

        $query = $this->db->get_where('forms', array('slug' => $slug), 1);

        if($query->num_rows() == 0){
            show_404();
        }

        $form = $query->row();


       // echo $this->db->count_all($form->tbl_name);


        $data['form'] = $form;

        $this->load->frontend('edit', $data);
       
    }


     public function view(){

        $id = $this->input->get('id');

        if(!is_numeric($id) || is_null($id)){
            show_404();
        }

        $query = $this->db->get_where('cubes', array('id' => $id), 1);

        if($query->num_rows() == 0){
            show_404();
        }

        $cube = $query->row();

        $query = $this->db->get($cube->tbl_name);



        $data['cube'] = $cube;

        $data['cube_items'] = $query->result();

        $this->load->backend('cubes/data_view', $data);
       
    }


}