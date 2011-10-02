<?php
/**
*
* Class name: epig
* Copyright (c) 2005,2006 Apostolos Dountsis
*
* The Controller class of the MVC architecture
* The core of the application. It interprets the
* URL requests and controls the View and Model classes
*
* @author		Apostolos (Soumis) Dountsis
*
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

// Application version
define('VERSION', '2');

class epig
{
	// Array that holds the user defined settings
	var $settings = array();
	
	// Config Filename
	var $config_file = 'config.xml';
	
	function epig()
	{
		// Test if GD is installed on the server
		if(!common::gd_loaded())
		{
			$msg = "EPIG requires the GD library (available at http://www.boutell.com/gd/).<br />";
			$msg .= "I cannot locate the GD library on your PHP installation. 
				Please, contact your webserver administrator if GD should be present.";
			common::msg_box($msg);
			exit();
		}
		
		// Load user settings from config file
		$this->settings = common::load_xml($this->config_file);
		
		// Determine which style to use
		
		// Instance of Model
		$gal =& new gallery($this->settings['sort_type'], $this->settings['sort_order']);

		// Instance of View
		switch($this->settings['style'])
		{
			case 1:
				$tpl =& new style_classic($this->settings['template']);
				break;
			case 2:
				$tpl =& new style_preview($this->settings['template']);
				break;
			case 3:
				$tpl =& new style_kes($this->settings['template']);
				break;
			default:
				$tpl =& new style_preview($this->settings['template']);
				break;
		}

		// Set the member attributes for the View
		$tpl->set_Images($gal->Images);
		$tpl->set_rows($this->settings['rows']);
		$tpl->set_columns($this->settings['columns']);
		$tpl->set_filename($this->settings['filename']);
		$tpl->set_thumbnail_dimensions($this->settings['width'], 
							$this->settings['height']);
		$tpl->set_preview_dimensions($this->settings['preview_width'], 
							$this->settings['preview_height']);
		
		// Handle User Requests

		if($_REQUEST['thumbnail'])
		{
			$gal->generate_thumbnail($_REQUEST['thumbnail'], 
									$this->settings['width'],
									$this->settings['height'],
									$this->settings['quality']);
		}
		elseif($_REQUEST['preview'])
		{
			$gal->generate_thumbnail($_REQUEST['preview'], 
									$this->settings['preview_width'],
									$this->settings['preview_height'],
									$this->settings['quality']);		
		}
		else
		{
			$tpl->populate_template($this->settings);
			$tpl->render();
		
		}
	}
}
?>
