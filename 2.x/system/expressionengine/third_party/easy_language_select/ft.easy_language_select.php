<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Easy Language Select FieldFrame Class
 *
 * @package   Easy Language Select
 * @author    Aaron Gustafson <aaron@easy-designs.net>
 * @copyright Copyright (c) 2010 Aaron Gustafson
 * @license   MIT
 */
class Easy_language_select_ft extends EE_Fieldtype {

	/**
	 * Fieldtype Info
	 * @var array
	 */
	var $info = array(
  		'name'             => 'Easy Language Select',
  		'version'          => '1.1',
	);

	var $addon_name = 'easy_language_select';
	
	/**
	 * Constructor
	 */
  	function Easy_language_select_ft()
	{
		parent::EE_Fieldtype();
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field($data)
	{
		# get the language file
		$this->EE->lang->loadfile($this->addon_name);
		
		if ( empty($data) ) $data = $this->EE->config->item('xml_lang');
		
		$file = APPPATH.'config/languages'.EXT;
		if ( ! file_exists($file) ) return FALSE;
		include($file);
		
		# text direction
		$dir = ( $this->settings['field_text_direction'] == 'rtl' ) ? 'rtl' : 'ltr';
		
		# options
		$options = array();
		foreach ( $languages as $key => $val )
		{
			$localized = $this->EE->lang->line($key);
			if ( $this->settings['localize'] == 'y' &&
			     $this->EE->config->item('xml_lang') != $val &&
			     ! empty( $localized ) ) $key = "{$localized} ({$key})";
			$options[$val] = $key;
		}
		
		# return the field
		return form_dropdown($this->field_name, $options, $data, 'dir="'.$dir.'" id="'.$this->field_name.'"');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Settings Screen
	 *
	 * @access	public
	 * @return	Displays the field settings form
	 *
	 */
	function display_settings($data)
	{
		# get the language file
		$this->EE->lang->loadfile($this->addon_name);
		
		# settings
		$localize = isset($data['localize']) ? $data['localize'] : $this->settings['localize'];
		
		# create the field
		$this->EE->table->add_row(
			form_label($this->EE->lang->line('translate_languages'),'localize'),
			'<label>'.form_radio('localize', 'y', ($localize=='y')).NBS.'Yes</label>' .
			NBS.NBS.NBS.NBS.NBS .
			'<label>'.form_radio('localize', 'n', ($localize=='n')).NBS.'No</label>'
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Save Settings
	 *
	 * @access	public
	 * @return	field settings
	 *
	 */
	function save_settings($data)
	{
		return array(
			'localize'	=> $this->EE->input->post('localize')
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Global Settings
	 *
	 * @access	public
	 * @return	form contents
	 *
	 */
	function display_global_settings()
	{
		$settings = array_merge($this->settings, $_POST);

		$this->EE->lang->loadfile($this->addon_name);
		
		# create the field
		return	form_label($this->EE->lang->line('translate_languages'),'localize') . ' ' .
				form_dropdown('localize', array('n' => 'no', 'y' => 'yes'), $settings['localize']);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Save Global Settings
	 *
	 * @access	public
	 * @return	global settings
	 *
	 */
	function save_global_settings()
	{
		return array_merge($this->settings, $_POST);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_tag($data, $params=array(), $tagdata=FALSE)
	{
	  if ( ! empty( $params['code'] ) &&
	       $params['code'] == 'yes' )
	  {
	    return $data;
	  }
	  else
	  {
		$this->EE->lang->loadfile($this->addon_name);    
	
	    $file = APPPATH.'config/languages'.EXT;
		if ( ! file_exists($file) ) return FALSE;
		include($file);
		
		$languages = array_flip( $languages );
	    $language = $languages[$data];
	
	    $localized = $this->EE->lang->line($language);
	
	    return ! empty( $localized ) ? $localized : $language;
	  }
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Install Fieldtype
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function install()
	{
		return array(
			'localize'	=> 'y',
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Uninstall Fieldtype
	 * 
	 */
	function uninstall()
	{
		return TRUE;
	}

}

/* End of file ft.easy_language_select.php */
/* Location: ./system/expressionengine/third_party/easy_language_select/ft.easy_language_select.php */