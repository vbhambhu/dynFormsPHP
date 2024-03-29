<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Form {


	private $form_html;
	private $open_parms;
	private $errors;
	private $validated = FALSE;
	private $validation_rules = array();
	private $fields = array();
	private $hidden = array();
	

	public function __construct(){
           
        $this->CI =& get_instance();
		$this->CI->load->helper('form');

    }

	public function open($action = FALSE, $attributes=array()){
		
		$this->open_parms = array('action' => $action, 'attributes' => $attributes);
		//form_open($action, $attributes, $hidden);
    }

    public function text($name, $label='', $rules = null, $value = null, $atts = array() ) {

    	$atts['name'] = $name;
    	$atts['value'] = ($this->CI->input->post($name)) ? set_value($name) : $value;
    	$atts['class'] = (isset($atts['class'])) ?  $atts['class'] . ' form-control' : 'form-control';

		//add validation to array
		if(!is_null($rules))
		$this->validation_rules[] = array('field' => $name,'label' => $label,'rules' => $rules);

		$html = form_label($label, $name);
		$html .= form_input($atts);

		$this->fields[] = array('name' => $name, 'html' => $html);
		return $this;
    }

    public function textarea($name, $label='', $rules = null, $value = null, $atts = array() ) {

    	$atts['name'] = $name;
    	$atts['value'] = ($this->CI->input->post($name)) ? set_value($name) : $value;
    	$atts['class'] = (isset($atts['class'])) ?  $atts['class'] . ' form-control' : 'form-control';

		//add validation to array
		if(!is_null($rules))
		$this->validation_rules[] = array('field' => $name,'label' => $label,'rules' => $rules);

		$html = form_label($label, $name);
		$html .= form_textarea($atts);

		$this->fields[] = array('name' => $name, 'html' => $html);
		return $this;
    }

     public function radio($name, $label='', $options = array(),$rules = null , $atts = array()) {


     	//form_radio([$data = ''[, $value = ''[, $checked = FALSE[, $extra = '']]]])

    	$atts['name'] = $name;
    	//$atts['value'] = ($this->CI->input->post($name)) ? set_value($name) : $value;
    	$atts['class'] = (isset($atts['class'])) ?  $atts['class'] . ' form-control' : 'form-control';

		//add validation to array
		if(!is_null($rules))
		$this->validation_rules[] = array('field' => $name,'label' => $label,'rules' => $rules);


		$html = '<p>'.form_label($label, $name).'</p>';

		foreach ($options as $key => $value) {
			$data = array('name' => $name,'value' => $key);
			$html .= '<div class="radio"><label>';
			$html .= form_radio($data);
			$html .= $value;
			$html .= '</label></div>';
		}
		

		$this->fields[] = array('name' => $name, 'html' => $html);
		return $this;
    }


    public function form_checkbox($name, $label='', $options = array(),$rules = null , $atts = array()) {


     	//form_radio([$data = ''[, $value = ''[, $checked = FALSE[, $extra = '']]]])

    	$atts['name'] = $name;
    	//$atts['value'] = ($this->CI->input->post($name)) ? set_value($name) : $value;
    	$atts['class'] = (isset($atts['class'])) ?  $atts['class'] . ' form-control' : 'form-control';

		//add validation to array
		if(!is_null($rules))
		$this->validation_rules[] = array('field' => $name,'label' => $label,'rules' => $rules);


		$html = '<p>'.form_label($label, $name).'</p>';

		foreach ($options as $key => $value) {
			$data = array('name' => $name,'value' => $key);
			$html .= '<div class="radio"><label>';
			$html .= form_checkbox($data);
			$html .= $value;
			$html .= '</label></div>';
		}
		

		$this->fields[] = array('name' => $name, 'html' => $html);
		return $this;
    }

    // private function  add_input_attrs(){
    // 	$this->_output .= '<div class="form-group">';
    // }

    private function open_form_group($name = null){


    	if(isset($this->errors[$name])){ 
    		$this->form_html .= '<div class="form-group has-danger">';
    	} else{
    		$this->form_html .= '<div class="form-group">';
    	}
    	
    }

    private function close_form_group($name = null){

    	if(isset($this->errors[$name])){ 
    		$this->form_html .= '<div class="form-control-feedback">'.$this->errors[$name].'</div></div>';
    	} else{
    		$this->form_html .= '</div>';
    	}
    }

    public function get(){

    	$this->form_html = form_open($this->open_parms['action'], $this->open_parms['attributes'], $this->hidden);

		if (!$this->validated) $this->validate();

		foreach ($this->fields as $field) {
			$this->open_form_group($field['name']);
			$this->form_html .= $field['html'];
			$this->close_form_group($field['name']);
		}


		$this->form_html .= $this->form_submit;
		$this->form_html .=form_close();
		return $this->form_html;


    }

    public function hidden($name, $value='') {
		$this->hidden[$name] = $value;
	}


    public function submit($value='Submit', $name='submit', $atts=array()) {

    	$atts['class'] = (isset($atts['class'])) ?  $atts['class'] . ' btn btn-success' : 'btn btn-success';
		$this->form_submit = form_submit($name, $value, $atts);
		return $this;
	}


	public function model($model='', $method='index') {

		if (!$model) show_error("model: No model specified");

		//$data = $this->_make_array($data);
		
		$this->model[] = array(
			'name' => $model,
			'method' => $method
		);
		
		return $this;
	}

	public function get_post(){
		return $this->CI->input->post();
	}


	private function validate(){

		if($this->check_post()){

			$this->CI->load->library('form_validation');

			//set validation rules
			$this->CI->form_validation->set_rules($this->validation_rules);


			if ($this->CI->form_validation->run() == FALSE) {

				$this->errors = $this->CI->form_validation->error_array();

			} else{

				$this->load_model();

			}

		}

	}


	private function load_model() {


		foreach ($this->model as $model){

			// combine data provided with POST data (validated at this time)
			$model_data = $_POST;

			// add file upload data
			//$model_data = array_merge($model_data, $this->_data);
				
			$this->CI->load->model($model['name']);
				
			// check if the model is in a subfolder
	        if (($last_slash = strrpos($model['name'], '/')) !== FALSE){
	        	$model['name'] = substr($model['name'], $last_slash + 1);
            }
            	
			$this->CI->{$model['name']}->{$model['method']}($this, $model_data); // $this = form library handle
		} 

	}

	/**
	 * Check Post
	 * 
	 * Checks if form was submitted
	 */				
	private function check_post() {

		return ($this->CI->input->post()) ? TRUE : FALSE;

	}

	private function _make_array($list) {
		if (!is_array($list)) 
		{
			$list = explode(',', $list);
			foreach ($list as $key=>$value)
			{
				$list[$key] = trim($value);
			}
		}
		return $list;
	}	

}