<?php
/**
 * Name as ID
 * 
 * If set to TRUE an 'id' attribute with the value of the 'name' will be
 * generated automatically in all elements (where applicable)
 */
$config['nameasid'] = TRUE;

/**
 * Replace
 * 
 * These parameters set the replace vs. combine behaviour for
 * multi-value attributes defined in $config['globals'] and $config['defaults']
 *
 * 1st parameter sets CLASSES replacement behaviour
 * 2nd parameter sets STYLES replacement behaviour
 * 3rd parameter sets SCRIPTS ('on...') replacement behaviour
 */
$config['replace'] = 'FALSE|TRUE|TRUE';

/**
 * XSS Clean Post Data
 * 
 * If set to TRUE all post data retrieved via $this->get_post()
 * will be xss_cleaned automatically
 */
$config['xss_clean_post'] = TRUE;

/**
 * Prefixes and Suffixes
 * 
 * Wraps these prefixes and suffixes around elements
 * Please use double quotes when using control characters (e.g. "\n")
 */
$config['group_prefix'] 	= '';		// wraps around checkgroups and radiogroups
$config['group_suffix'] 	= '';
$config['element_prefix'] 	= '<div class="form-group">';		// wraps around all elements
$config['element_suffix'] 	= '</div>';
$config['label_prefix'] 	= '';		// wraps around labels
$config['label_suffix'] 	= '';
$config['field_prefix'] 	= ''; 		// wraps around fieldsets (inside), inputs, buttons, textareas, selects, captchas & recaptchas
$config['field_suffix'] 	= '';

/**
 * Error Tags
 * 
 * Opening tag of a single error, e.g. '<span class="error">- '
 * Closing tag of a signle error, e.g. '</span>'
 *
 * This allows you to wrap all single errors within a styled element
 */
$config['error_open'] = '';
$config['error_close'] = '';

/**
 * Error String Tags
 * 
 * Opening tag of the error string, e.g. '<div id="errors">'
 * Closing tag of the error string, e.g. '</div>'
 *
 * This allows you to wrap all single errors within a styled element
 */
$config['error_string_open'] = '<div id="alert" class="alert alert-danger animated shake"><button type="button" class="close" data-dismiss="alert" id="dismiss_alert" >&times;</button>';
$config['error_string_close'] = '</div>';

/**
 * Error Class
 * 
 * This class will be added to the element and its label if validation rules do not pass
 */
$config['error_class'] = 'error_form';

/**
 * Inline Error
 * 
 * If set to 'TRUE' the error message will be shown directly after the element
 * You can also set the inline error message opening and closing tag.
 */
$config['error_inline'] = FALSE;
$config['error_inline_open'] = '';
$config['error_inline_close'] = '';

/**
 * Error Flag
 * 
 * This class will be added to the element and its label if validation rules do not pass
 * To include the error message with an image title attribute you can use the placeholder {error}
 */
$config['error_flag'] = '';

/**
 * Global Attributes
 * 
 * Define global standard attributes
 */
$config['globals'] = array();

/**
 * Default Attributes
 * 
 * Define standard attributes to element types or specific elements (by name)
 * 
 * NEW!! 
 * To change label attributes for an element type or specific element name
 * add 'label' => array('attribute' => 'value') to the respective array
 * (see 'checkbox' for example)
 */
$config['defaults'] = array(
	'label' => array(										// all label configuration is done here now!!
		'position' => 'before',
		'class' => 'left',
		'required_class' => 'required',
		'required_flag' => ''
	),
	'fieldset' => array(
		'element_suffix' => '</div>'								// do not use an element_suffix for fieldsets
	),
	'text' => array(
	    'class' => 'form-control',
	),
	'textarea' => array(
		 'class' => 'form-control lngtxt',	 
	),
	'password' => array(
		'class' => 'form-control',
	),
	'upload' => array(

		'upload_path' => './uploads/',
		'allowed_types' => 'pdf|doc|jpg|gif|png|csv'
	),
	'select' => array(
		 'class' => 'form-control selectpicker',	 
	),
	'checkbox' => array(
		'element_prefix'=> '<div class="form-group">',
		'element_suffix' => '</div>',								// do not use an element_suffix for checkboxes
		'class' => 'check',									// assign a CSS class to the <input type="checkbox" />
		'label' => array(
			'position' => 'after',							// display the label after the actual checkbox
			'class' => 'check'								// assign a CSS class to the <label>
		)
	),
	'radio' => array(
		'element_suffix' => '</div>',
		'class' => 'check',
		'label' => array(
			'position' => 'after',
			'class' => 'check'
		)
	),
	'submit' => array(
		'element_suffix' => '</div>',								// do not use an element_suffix for submit buttons
		'class' => 'btn btn-default btn-bld'
	),
	'reset' => array(
		'class' => 'button'
	),
	// always define defaults for specific elements (below) after defaults by element type (above)
	'multiple' => array(
	'style' => 'height: 120px'
	)
);

/**
 * Recaptcha Settings
 * 
 * Please provide your public and private key
 * to make the recaptcha element work
 */
$config['recaptcha_api_server']			= 'http://api.recaptcha.net';
$config['recaptcha_api_secure_server']	= 'https://api-secure.recaptcha.net';
$config['recaptcha_ssl']				= 'TRUE';
$config['recaptcha_verify_server']		= 'api-verify.recaptcha.net';
$config['recaptcha_key_public']			= '6LdEUiQTAAAAAIGQD7w0eDl98-t-xsFmHgET4eyN';	// Complete with your key
$config['recaptcha_key_private']		= '6LdEUiQTAAAAAMUMgBsvjhjdeZz30aeKgXsF--wm';	// Complete with your key
$config['recaptcha_theme']				= 'white';
$config['recaptcha_lang']				= 'en';	// Valid language codes on http://recaptcha.net/apidocs/captcha/client.html

/* End of file form.php */
/* Location: ./application/config/form.php */