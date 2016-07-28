<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

	public function frontend($template_name, $vars = array(), $return = FALSE) {
	        
	    $content  = $this->view('frontend/header', $vars, $return);
	    $content .= $this->view($template_name, $vars, $return);
	    $content .= $this->view('frontend/footer', $vars, $return);

	    if ($return){
	        return $content;
	    }
	}

	public function backend($template_name, $vars = array(), $return = FALSE) {
	        
	    $content  = $this->view('backend/header', $vars, $return);
	    $content .= $this->view($template_name, $vars, $return);
	    $content .= $this->view('backend/footer', $vars, $return);

	    if ($return){
	        return $content;
	    }
	}

}