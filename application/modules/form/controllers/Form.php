<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Form extends CI_Controller {

    public function __construct(){

        parent::__construct();

        if(!$this->session->userdata('user_id')){
            redirect('account/login');
        }

    }

    public function index(){

        $this->load->library('Datatables');

        $data['forms'] = $this->db->get('forms')->result();

        $data['meta_title'] = "Forms";



        $data['js_foot'] = array('jquery.dataTables.min', 'dataTables.bootstrap4.min');

        $this->load->frontend('list', $data);
       
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