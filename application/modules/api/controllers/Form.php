<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Form extends REST_Controller {


    private $doc_tbl    = 'documents';

    public function __construct(){

        parent::__construct();

    }

    public function searchById_get(){

        $id = $this->get('id');


        if($id === NULL || !is_numeric($id) || $id < 1){

            $this->response([
                    'status' => FALSE,
                    'message' => 'Invalid form id'
                ], REST_Controller::HTTP_BAD_REQUEST); 
        }

        $q = $this->db->get_where('forms', array('id' => $id),1);

        if($q->num_rows() == 0){

             $this->response([
                    'status' => FALSE,
                    'message' => 'No form were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }

        $cube = $q->row();

        $result = array(
            'id' => $cube->id,
            'name' => $cube->name,
            'description' => $cube->description
        );

        $query = $this->db->get_where('fields', array('form_id' => $id));
    
        //echo '<pre>'; print_r($result['attributes']);

        $fields = array();

        foreach ($query->result() as $row) {
           $fields[] = array(
            'id' => $row->id,
            'identifier' => $row->identifier,
            'label' => $row->label,
            'type' => $row->type,
            'is_required' => ($row->is_required == 1) ? true : false,
            'default_value' => $row->default_value,
            'options' => json_decode($row->options), 
            'help_text' => $row->help_text,
            'validation_rule' => $row->validation_rule,
            'form_id' => $row->form_id
            );
        }

        $result['fields'] = $fields;

        $this->set_response( $result, REST_Controller::HTTP_OK);
    }



    public function save_post(){

        if($this->post("form")) {

            $form = $this->post("form");

            $new_form = array(
                'name' => $form['name'],
                'description' => $form['description'],
            );

            $this->db->where('id', $form['id'] );
            $this->db->update('forms', $new_form);


            $this->db->where('form_id', $form['id'] );
            $this->db->delete('fields');

            $fields = $form['fields'];

            $att_id = 1;

            foreach ($fields as $key => $value) {

                $validation_rules = array();

                if($value['is_required'] == 'true'){
                    $validation_rules[] = 'required';
                }


                $field = array(
                    'id' => $att_id, 
                    'identifier' => $value['identifier'], 
                    'label' => $value['label'], 
                    'type' => $value['type'], 
                    'is_required' => ($value['is_required'] == 'true') ? 1 : 0, 
                    'default_value' => $value['default_value'],
                    'options' => (isset($value['options'])) ? json_encode($value['options']) : NULL, 
                    'help_text' => $value['help_text'],
                    'validation_rule' => implode("|", $validation_rules), 
                    'form_id' =>  $form['id']
                );

                $this->db->insert('fields', $field);

                $att_id++;
                
            }



            $this->set_response( [
                    'status' => FALSE,
                    'message' => 'Cube saved'
            ], REST_Controller::HTTP_OK);


        } else{
             $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
    }



}