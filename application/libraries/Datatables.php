<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Datatables {


	private $_output;
	private $validated = FALSE;
	private $validation_rules = array();

	public function __construct(){
           
        $this->CI =& get_instance();
		$this->CI->load->helper('form');

    }

	public function select($action = FALSE, $attributes=array(), $hidden=array()){

		$this->_output .= form_open($action, $attributes, $hidden);
		return $this;

    }

 

}