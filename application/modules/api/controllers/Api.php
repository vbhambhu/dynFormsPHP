<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class API extends REST_Controller {


    private $doc_tbl    = 'documents';

    public function __construct(){

        parent::__construct();

    }

    public function cube_by_id_get(){

        $id = $this->get('id');


        if($id === NULL || !is_numeric($id) || $id < 1){

            $this->response([
                    'status' => FALSE,
                    'message' => 'Invalid cube id'
                ], REST_Controller::HTTP_BAD_REQUEST); 
        }

        $q = $this->db->get_where('cubes', array('id' => $id),1);

        if($q->num_rows() == 0){

             $this->response([
                    'status' => FALSE,
                    'message' => 'No cube were found'
                ], REST_Controller::HTTP_NOT_FOUND);
        }

        $cube = $q->row();

        $result = array(
            'id' => $cube->id,
            'name' => $cube->name,
            'description' => $cube->description
        );

        $query = $this->db->get_where('cube_attributes', array('cube_id' => $id));
    
        //echo '<pre>'; print_r($result['attributes']);

        $attributes = array();

        foreach ($query->result() as $row) {
           $attributes[] = array(
            'id' => $row->id,
            'identifier' => $row->identifier,
            'label' => $row->label,
            'type' => $row->type,
            'is_required' => ($row->is_required == 1) ? true : false,
            'default_value' => $row->default_value,
            'options' => json_decode($row->options), 
            'help_text' => $row->help_text,
            'validation' => $row->validation,
            'validation_rule' => $row->validation_rule,
            'cube_id' => $row->cube_id
            );
        }

        $result['attributes'] = $attributes;

        $this->set_response( $result, REST_Controller::HTTP_OK);
    }



    public function save_cube_post(){

        if( $this->post("cube")) {

            $cube = $this->post("cube");

            $new_cube = array(
                'name' => $cube['name'],
                'description' => $cube['description'],
            );

            $this->db->where('id', $cube['id'] );
            $this->db->update('cubes', $new_cube);


            $this->db->where('cube_id', $cube['id'] );
            $this->db->delete('cube_attributes');

            $attributes = $cube['attributes'];

            $att_id = 1;

            foreach ($attributes as $key => $value) {

                $validation_rules = array();

                if($value['is_required'] == 'true'){
                    $validation_rules[] = 'required';
                }


                $attribute = array(
                    'id' => $att_id, 
                    'identifier' => $value['identifier'], 
                    'label' => $value['label'], 
                    'type' => $value['type'], 
                    'is_required' => ($value['is_required'] == 'true') ? 1 : 0, 
                    'default_value' => $value['default_value'],
                    'options' => json_encode($value['options']), 
                    'help_text' => $value['help_text'], 
                    'validation' => $value['validation'], 
                    'validation_rule' => implode("|", $validation_rules), 
                    'cube_id' =>  $cube['id']
                );


                $this->db->insert('cube_attributes', $attribute);

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