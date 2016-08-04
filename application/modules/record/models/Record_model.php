<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Record_model extends CI_Model{

	private $log_tbl = 'document_logs';


	public function save_data(){

		$post = $this->form->get_post();

		//print_r($post); die;

		$query = $this->db->get_where('forms', array('id' => $post['form_id']) );
		$form = $query->row();

		if(!$this->db->table_exists($form->slug)){
			$this->create_tbl($form->id, $form->slug);
		}

		unset($post['submit']);


		$this->db->insert($form->slug, $post );

		$this->session->set_flashdata('success_message', 'Data submitted successfully.');
	}


	private function create_tbl($form_id, $tbl){


		$query = $this->db->get_where('fields', array('form_id' => $form_id) );

			$this->load->dbforge();

			$fields['form_id'] = array('type' => 'INT');

			$input_types = array('text', 'textarea');

			$this->dbforge->add_field('id');

			foreach ($query->result() as $row) {

				if(in_array($row->type, $input_types)){

					$fields[$row->identifier] = array('type' => 'VARCHAR','constraint' => '255');
				}


				if($row->type == 'multiple')){
	
				}

			}

			//echo '<pre>'; print_r($fields); die;

			$this->dbforge->add_field($fields);

			$this->dbforge->create_table($tbl);

	}
}