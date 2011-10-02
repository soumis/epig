<?php
/**
*
* Class name: common
* Copyright (c) 2005,2006 Apostolos Dountsis
*
* A general-purpose function library
* It contains static functions
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

class common
{

	/**
	 * Reads all images in the current directory
	 *
	 * @param void
	 * @return array
	 */	
	function load_images()
	{
		$image_array = array();
		$dir = opendir(dirname(__FILE__));
	
		while(false != ($file = readdir($dir)))
		{
		    if(($file != ".") && ( $file != ".." ))
		    {
		        if(common::validate_image_extension($file))
		        {
		            $image_array[] = $file;
		        }
		    }
		}
		
		return $image_array;
	}

	/**
	 * Checks allowed image extensions
	 *
	 * @param string $file
	 * @return boolean
	 */
	function validate_image_extension($file)
	{
		switch(strtolower(substr($file, -4)))
		{
			case '.jpg':	
				return true;
			case '.gif':	
				return true;
			case '.png':	
				return true;
			default :	
				return false;
		}
	
	}

	/**
	 * Wrapper for print_r() that formats the array for HTML output
	 * Debug Function
	 *
	 * @return void
	 */
	function pre_print_r($item)
	{
		print("<pre>\n"); 
		print_r($item); 
		print("</pre>\n");
	}
	
	/**
	* Checks whether the graphics library GD has been loaded
	*
	* @return boolean
	*/
	function gd_loaded()
	{
		if (!extension_loaded('gd')) 
		{
		   if (!dl('gd.so'))
		   {
			   return false;
		   }
		}
		
		return true;
	}

	/**
	 * Get contents from XML file and insert into an array
	 *
	 * @param string $file
	 * @return mixed
	 */
	function xml2php($file) 
	{
		$arr_vals = array();
		$xml_parser = xml_parser_create();
	   
		if (!($fp = fopen($file, "r")))
		{
			common::msg_box("I am unable to locate your configuration file with filename <b>$file</b>.
							The file should be placed on ". dirname(__FILE__));
			exit();
		}
	   
		$contents = fread($fp, filesize($file));
		fclose($fp);
		
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
		
		xml_parse_into_struct($xml_parser, $contents, $arr_vals);
		xml_parser_free($xml_parser);
		
		return $arr_vals;
	}

	/**
	* Load an XML file and return an array populated with the tags
	*
	* @param string $file
	* @return mixed
	*/
	function load_xml($file)
	{		
		$xml_array = array();
		$xml = common::xml2php($file);

		foreach($xml as $element)
		{
			if(common::is_extant($element['value']))
			{
				$tag = $element['tag'];
				$value = $element['value'];
				$xml_array[$tag] = $value;
			}
		}
		
		return $xml_array;
	}
	
	/**
	* Display an error message and terminate the application
	*
	* @param string $msg
	* @return void
	*/
	function msg_box($msg, $title = "EPIG Error", $type = 'ERROR')
	{
		switch($type)
		{
			case 'ERROR':
				$bgd = '#FFE4E1';
				$border = '#FF0000';
				$text = '#000';
				break;	
			default:
				$bgd = '#B5BEC4';
				$border = '#336699';
				$text = '#fff';			
				break;
		}
		
		// Message title 
		$title_html = "<div style=\"text-align:center; padding-bottom: 5px; font-size: large;\" >";
		$title_html .= $title. "</div>";
		
		// Format the message body
		$html = "<div style=\"font-family: Courrier; padding: 5px; border: 2px solid $border; background-color: $bgd;font-weight: normal; width: 300px; color: $text;\" >";
		$html .= $title_html . $msg ."</div>";
		
		// Output the message
		print($html);
		
		// Terminate the application
//		exit;
	}

	/**
	 * Sorts an array that contains filenames.
	 *
	 * Type of Supported Sort:
	 *	'NAME': Orders alphanumeric strings in natural sort.
	 *	'DATE': Orders the files by the modification date.
	 *
	 * Order of sorting:
	 *	'ASC': Ascending Order
	 *	'DESC': Descending Order
	 *
	 */
	function sort_files(&$file_array, $type, $order = 'ASC')
	{
		// Sort files based on the type
		switch($type)
		{
			case 'DATE':
				foreach($file_array as $f)
				{
					if(file_exists($f)) 
					{
						$Files[$f] = filemtime($f);
					}
				}

				// Newest images go last 
				asort($Files);

				$file_array = array();
				foreach($Files as $f => $t)
				{
					$file_array[] = $f;
				}
				break;
				
			case 'NAME':
			
				// Sort in natural order
				natcasesort($file_array);
				
				// natcasesort maintains the key-value associations of the array
				// Re-index the key-value associations by using array_values 
				// on the sorted array
				$file_array = array_values($file_array);
				break;
		}
		
		// Sort based on order
		if($order == 'DESC')
		{
			// Reverse the order of the array
			$file_array = array_reverse($file_array);
		}
	}

	/**
	* Checks the variable is empty, but allow for it to be zero
	*
	* @return boolean
	*/
	function is_extant($var)
	{
		if (isset($var))
		{
			if ( empty($var) && ($var !== 0) && ($var !== '0') )
				return FALSE;
			else
				return TRUE;
		}
		else
		   return FALSE;
	}
	
	/**
	* Returns the current page from the URL
	*
	* @return string
	*/	
	function get_current_page()
	{
		return $_REQUEST['page'];
	}
}
?>
