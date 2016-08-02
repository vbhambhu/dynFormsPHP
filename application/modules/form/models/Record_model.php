<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Record_model extends CI_Model{

	private $log_tbl = 'document_logs';


	public function save_data(){

		$post = $this->form->get_post();

		$query = $this->db->get_where('cubes', array('id' => $post['cube_id']) );
		$cube = $query->row();

		if(!$this->db->table_exists($cube->tbl_name)){
			$this->create_tbl($cube->id, $cube->tbl_name);
		}

		unset($post['submit']);


		$this->db->insert($cube->tbl_name, $post );

		$this->session->set_flashdata('success_message', 'Data submitted successfully.');
	}


	private function create_tbl($cube_id, $tbl){


		$query = $this->db->get_where('cube_attributes', array('cube_id' => $cube_id) );

			$this->load->dbforge();

			$fields['cube_id'] = array(
                'type' => 'INT'
        	);

			$input_types = array('text');

			$this->dbforge->add_field('id');


			foreach ($query->result() as $row) {

				if(in_array($row->type, $input_types)){

					$fields[$row->identifier] = array(
						'type' => 'VARCHAR',
						'constraint' => '255',
						);
				}

			}

			$this->dbforge->add_field($fields);

			$this->dbforge->create_table($tbl);


	}
}