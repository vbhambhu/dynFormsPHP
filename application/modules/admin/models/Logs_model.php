<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Logs_model extends CI_Model{

	private $log_tbl = 'document_logs';


	public function get_logs(){


		$sql = "SELECT l.*, u.name 
		FROM document_logs l
		LEFT JOIN user u ON (l.user_id = u.id) 
		ORDER BY created_at DESC LIMIT 100";
		$query = $this->db->query($sql);

		return $query->result();
	}
}