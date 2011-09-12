<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Easy Language Select Plugin Class
 *
 * @package   Easy Language Select
 * @author    Aaron Gustafson <aaron@easy-designs.net> / Eli Van Zoeren <eli@elivz.com>
 * @copyright Copyright (c) 2010 Aaron Gustafson
 * @license   MIT
 */

$plugin_info = array(
	'pi_name'			=> 'Easy Language Select',
	'pi_version'		=> '1.2',
	'pi_author'			=> 'Aaron Gustafson',
	'pi_author_url'		=> 'https://github.com/easy-designs/ft.easy_language_select.php',
	'pi_description'	=> 'Displays a select box of langauges, taken from EE\'s languages file',
	'pi_usage'			=> Easy_language_select::usage()
);

class Easy_language_select
{
	
	public $return_data = "";
	
	/**
	 * Main plugin function
	 */
    public function Easy_language_select()
    {
        $this->EE =& get_instance(); 
        
        # get the helper files
        $this->EE->lang->loadfile('easy_language_select');
        $this->EE->load->helper('form');
        
        if ( empty($data) ) $data = $this->EE->config->item('xml_lang');
        
        $file = APPPATH.'config/languages'.EXT;
        if ( ! file_exists($file) ) return FALSE;
        include($file);
        
        $title = $this->EE->TMPL->fetch_param('title', $this->EE->lang->line('select_title'));
        $select_name = $this->EE->TMPL->fetch_param('name', 'language');
        $id = $this->EE->TMPL->fetch_param('id', FALSE);
        $class = $this->EE->TMPL->fetch_param('class', 'easy_language_select');
        $tabindex = $this->EE->TMPL->fetch_param('tabindex', FALSE);
        $dir = $this->EE->TMPL->fetch_param('dir', FALSE);
        $selected = $this->EE->TMPL->fetch_param('selected', '');
        $localize = $this->EE->TMPL->fetch_param('localize', FALSE);
        $type = $this->EE->TMPL->fetch_param('type', 'code');
        
        $extra = 'class="'.trim($class).'"';
        if($id) $extra .= ' id="'.trim($id).'"';
        if($tabindex) $extra .= ' tabindex="'.intval($tabindex).'"';
        if($dir) $extra .= ' dir="'.trim($dir).'"';
        
        $options = array($title, '--------------------');
        
        # options
        foreach ( $languages as $key => $val )
        {
            $local = $key;
            if ( $localize == 'yes' && $this->EE->config->item('xml_lang') != $val) 
            {
                $lang = $this->EE->lang->line($key);
                if (!empty($lang)) $local = "{$lang} ({$key})";
            }
            
            if ($type == 'code')
            {
                $options[$val] = $local;
            }
            elseif ($type = 'full')
            {
                $options[$key] = $local;
            }
        }
        
        # return the field
        $this->return_data = form_dropdown($select_name, $options, $selected, $extra);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Usage instructions
	 */
	function usage()
	{
		ob_start(); 
	?>
Easy Language Select will display a list of languages, taken from Expression Engine's own language file.

Usage:

{exp:easy_language_select}
		
Optional parameters:
		
name / title / id / class / tabindex / dir - All these can be used to set attributes on the <select> tag.

title - Text for the un-set state of the drop-down. Defaults to "Select a language".

selected - The value that should be selected by default.

localize - Set to "yes" to display each language using the local spelling.

type - Set to "code" to use language codes for the <option> values; set to "full" for full language names as the values. Defaults to "code".
	<?php
		$buffer = ob_get_contents();
		ob_end_clean(); 
		return $buffer;
	}

}