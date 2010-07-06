<?php

if ( ! defined('EXT')) exit('Invalid file request');


/**
 * Easy Language Select FieldFrame Class
 *
 * @package   Easy Language Select
 * @author    Aaron Gustafson <aaron@easy-designs.net>
 * @copyright Copyright (c) 2010 Aaron Gustafson
 * @license   MIT
 */
class Easy_language_select extends Fieldframe_Multi_Fieldtype {

  /**
   * Fieldtype Info
   * @var array
   */
  var $info = array(
    'name'             => 'Easy Language Select',
    'version'          => '1.0.0',
    'desc'             => 'A language drop-down list built upon EE\'s internal language selector',
    'docs_url'         => '',
    'versions_xml_url' => ''
  );

  /**
   * Displays the field settings form.
   * @param     array     $field_settings       Previously-saved field settings.
   * @return    array     An associative array.
   */
  function display_field_settings( $field_settings )
  {
    $SD = new Fieldframe_SettingsDisplay();
    
    $r = $SD->block() .
         $SD->row( array(
           $SD->label('translate_languages'),
           $SD->radio_group('localize', 'y', array('n' => 'no', 'y' => 'yes'))
         ) ) .
         $SD->block_c();
    
    // Set our return data.
    return array(
      'cell1'                 => '',
      'cell2'                 => $r,
      'formatting_available'  => FALSE,
      'direction_available'   => FALSE
    );
  }
   
  /**
   * Display Field
   * 
   * @param  string  $field_name      The field's name
   * @param  mixed   $field_data      The field's current value
   * @param  array   $field_settings  The field's settings
   * @return string  The field's HTML
   */
  function display_field($field_name, $field_data, $field_settings)
  {
    global $DSP, $PREFS, $FNS, $LANG;
    $LANG->fetch_language_file(strtolower(__CLASS__));
    
    if ( empty( $field_data ) ) $field_data = $PREFS->ini('xml_lang');
    
    $file = PATH.'lib/languages'.EXT;
    if ( ! file_exists($file) ) return FALSE;
    include($file);
    
		$r = $DSP->input_select_header($field_name);
		foreach ( $languages as $key => $val )
		{
			$localized = $LANG->line($key);
			if ( $field_settings['localize'] == 'y' &&
			     $PREFS->ini('xml_lang') != $val &&
			     ! empty( $localized ) ) $key = "{$localized} ({$key})";
			$r .= $DSP->input_select_option( $val, $key, ($field_data == $val) ? 1 : '');
		}
		$r .= $DSP->input_select_footer();
    
    return $r;
  }

  /**
   * Display Cell
   * 
   * @param  string  $cell_name      The cell's name
   * @param  mixed   $cell_data      The cell's current value
   * @param  array   $cell_settings  The cell's settings
   * @return string  The cell's HTML
   */
  function display_cell($cell_name, $cell_data, $cell_settings)
  {
    return $this->display_field($cell_name, $cell_data, $cell_settings);
  }

  /**
   * Display Tag
   *
   * @param  array   $params          Name/value pairs from the opening tag
   * @param  string  $tagdata         Chunk of tagdata between field tag pairs
   * @param  string  $field_data      Currently saved field value
   * @param  array   $field_settings  The field's settings
   * @return string  relationship references
   */
  function display_tag($params, $tagdata, $field_data, $field_settings)
  {
    global $LANG;
    $LANG->fetch_language_file(strtolower(__CLASS__));
    
    $file = PATH.'lib/languages'.EXT;
    if ( ! file_exists($file) ) return FALSE;
    include($file);
    
    $languages = array_flip( $languages );
    $language = $languages[$field_data];
    
    $localized = $LANG->line($language);
    
    return ! empty( $localized ) ? $localized : $language;
  }

}
