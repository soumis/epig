<?php
/**
*
* Class name: template
* Copyright (c) 2005,2006 Apostolos Dountsis
*
* The View class of the MVC architecture
* Produces all the HTML needed to display 
* the image gallery
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

class template
{
	var $template_file = '';
	
	var $rows = 0;
	
	var $columns = 0;
	
	var $filename = 0;
	
	var $Images = array();
	
	var $thumbnail_dimensions = array();
	
	var $preview_dimensions = array();
	
	var $navigation_graphic = 0;
	
	var $navigation_next = '';
	
	var $navigation_back = '';
	
	/**
	 * Sets the label for the navigation
	 * to the next page
	 *
	 * @param $str
	 * @return void
	 */
	function set_navigation_next($str)
	{
		$this->navigation_next = $str;
	}
	
	/**
	 * Return the label for the 
	 * navigation to the next page
	 *
	 * @param void
	 * @return string
	 */
	function get_navigation_next()
	{
		return $this->navigation_next;
	}
	
	/**
	 * Sets the label for the 
	 * navigation to the previous page
	 *
	 * @param $str
	 * @return void
	 */
	function set_navigation_back($str)
	{
		$this->navigation_back = $str;
	}
	
	/**
	 * Returns the label for the 
	 * navigation to the previous page
	 *
	 * @param void
	 * @return string
	 */
	function get_navigation_back()
	{
		return $this->navigation_back;
	}
	
	/**
	 * Sets whether graphics should be
	 * used for the navigation.
	 * Valid parameters are 1 and 0
	 *
	 * @param $status
	 * @return void
	 */	
	function use_navigation_graphic($status)
	{
		$this->navigation_graphic = $status;
	}

	/**
	 * Returns the current status
	 * of the graphic navigation
	 *
	 * @param void
	 * @return boolean
	 */	
	function navigation_graphic_enabled()
	{
		return $this->navigation_graphic;
	}

	/**
	 * Default Constructor
	 *
	 * @param $tempate_file
	 * @return void
	 */	
	function template($template_file)
	{
		$this->load($template_file);		
	}

	/**
	 * Loads a supplied template file
	 *
	 * @param $tempate_file
	 * @return void
	 */	
	function load($template_file)
	{
		// Check if the template exists in the filesystem.
		if(!file_exists($template_file))
		{
			common::msg_box("I cannot locate your template with filename <b>$template_file</b>.");
		}
		else
		{
			// Read the template file and load it into the appropriate attribute.
			$this->template_file = implode("", file($template_file));
		}	
	}
	
	// Mutator function for $Images	
	function set_Images(&$Img)
	{
		$this->Images = $Img;
	}
	
	// Mutator function for $rows	
	function set_rows($r)
	{
		if($r <=0 or empty($r))
		{
			common::msg_box('The number of rows in your config file need to be set to a value greater than 0');
		}
		else
		{
			$this->rows = $r;
		}
	}
	
	// Mutator for $filename
	function set_filename($fn)
	{
		$this->filename = $fn;
	}
	
	// Mutator function for $columns
	function set_columns($c)
	{
		$this->columns = $c;
	}
	
	function set_thumbnail_dimensions($width, $height)
	{
		$this->thumbnail_dimensions = array('width' => $width, 'height' => $height);
	}

	function set_preview_dimensions($width, $height)
	{
		$this->preview_dimensions = array('width' => $width, 'height' => $height);
	}
	
	// Populate the template
	function populate($tag, $value)
	{
		$this->template_file = str_replace($tag, $value, $this->template_file);
	}

	// Outputs the template on the browser
	function render()
	{
		// Render the html file in the browser
		print($this->template_file);
	}
	
	// Returns the total number of pages
	// for the specified number of rows, columns
	function get_total_pages()
	{
		$pages = ceil(sizeof($this->Images) / ($this->rows * $this->columns));
		
		return $pages;
	}
	
	// Accessor function for the current page	
	function get_current_page()
	{
		$curr_page;
		
		if(!isset($_REQUEST['page']))
		{
			$curr_page = 1;
		}
		else
		{
			$curr_page = $_REQUEST['page'];
		}
		
		return $curr_page;
	}
	
	// Returns an array containing the images in the specified page
	function get_images_in_page($page_number)
	{
		$range_images = array();
		
		$range_start = 0;		
		$range_end = 0;
		
		// Set the range start
		if($this->get_total_pages($this->rows, $this->columns) > 1)
		{
			$range_start = $this->rows * $this->columns * ($page_number - 1);
		}

		// Set the range end
		$range_end = ($this->rows * $this->columns) * $page_number;
		if(sizeof($this->Images) < $range_end)
		{
			$range_end = sizeof($this->Images);
		}

		// locate the images within the range
		for($i=$range_start; $i<$range_end; $i++)
		{
			$range_images[] = $this->Images[$i];
		}

    	return $range_images;
	}	

	/**
	 * Returns the page number of the supplied image
	 *
	 * @param image_name
	 * @return int
	 */
	function get_page_of_image($image_name)
	{
		$total_pages = $this->get_total_pages($this->rows, $this->columns);
		
		for($page = 1; $page <= $total_pages; $page++)
		{
			$page_images = $this->get_images_in_page($page);

			if(in_array($image_name, $page_images))
			{
				return $page;
			}
		}
		
		return -1;
	}

	/**
	 * Build the caption for an image
	 *
	 * @param image_file
	 * @return string
	 */
	function build_caption($image_file, $filename = 1, $caption = 1)
	{
		$caption_file = "";
		$html ="";
		
		// Derive the caption_file
		$caption_file = substr($image_file, 0, sizeof($image_file)-5);
		
		// Display the filename if needed
		if($filename == 1)
		{
			$html .="<br /><span class=\"captiontitle\" style=\"text-align:centre;\">$caption_file</span>";
		}		
		
		if($caption == 1)
		{
			if(file_exists($caption_file.'.txt'))
			{
				$html .= "<br /><span class=\"caption\">".file_get_contents($caption_file.'.txt')."</span>";
			}
			else
			{
				$html .= "&nbsp;";
			}
		}		
		return $html;
	}
	
	/**
	 * Displays the images as thumbnails in a tabular manner (HTML)
	 *
	 * @param void
	 * @return string
	 */
	function build_thumbnail_page($page_number, $image_href="", $image_target="_parent")
	{	
		$html = "<table cellspacing=\"5\">\n";

		$Images = $this->get_images_in_page($page_number);
		
		$img_index = 0;
		
		for($row = 1; $row <= $this->rows; $row++)
		{
			$html .= "<tr>\n";
			
			for($col = 1; $col <= $this->columns; $col++)
			{
				if(isset($Images[$img_index]))
				{
					$html .= "<td align=\"center\">\n<div class=\"gallery\">\n";
					if($image_href != "")
					{
						if($image_href == 'actual')
						{
							// Popup window dimensions
							$size = GetImageSize($Images[$img_index]);
							$offset = 0; //40;
							$image_width = $size[0] + $offset;
							$image_height = $size[1] + $offset;						

							$html .="<a href=\"#\" onClick=\"javascript:window.open('$Images[$img_index]','actualimage','toolbar=0,menubar=0,width=$image_width,height=$image_height,resizable=1,scrollbars=0,status=0');\" title=\"Click on the thumbnail to see the image in actual size\">";
//							$html .= "<a href=\"{$Images[$img_index]}\" target=\"$image_target\">\n";
						}
						else
						{

							$id = (array_keys($this->Images, $Images[$img_index]));
							$img_href = str_replace('img_index', $id[0], $image_href);
							$img_href = str_replace('img_name', $Images[$img_index], $img_href);							

							$html .= "<a href=\"$img_href\" target=\"$image_target\">\n";
						}
					}
					$html .= "<img class=\"thumbnail\" src=\"{$_SERVER['PHP_SELF']}?thumbnail={$Images[$img_index]}&page=".$this->get_current_page()."\" />";
					if($image_href != "")
					{
						$html .= "</a>\n";
					}
					
					// Build the caption for the thumbnail
					$html .= $this->build_caption($Images[$img_index], $this->filename);
					
					$html .= "</div>\n</td>\n";
				}
				else
				{
					$html .= "<td>&nbsp;</td>\n";
				}
				$img_index++;
			}
			
			$html .= "</tr>\n";
		}
		
		$html .= "</table>\n";
		
		$html .= $this->build_copyright();

		return $html;
	}	
	
	function build_copyright()
	{
		$html ="";
		
		// Copyright footer
		$html .= "<div align=\"center\" style=\"font-size:x-small;color:white;\">Powered by <a style=\"font-size:x-small;color:white;\" href=\"http://www.dountsis.com/epig/\">Easy Peasy Image Gallery (EPIG) ".VERSION." </a></div><br />\n";

		return $html;		
	}
	
	function populate_template(&$settings, $total_pages, $style = '1')
	{
		$this->populate('{preview}', $this->build_preview($_REQUEST['preview']), $template_file);
//		$this->populate('{gallery}', $this->build_preview_gallery(), $template_file); // $this->build_preview_gallery(), $template_file);
		$this->populate('{next}', $this->build_next($total_pages), $template_file);
		$this->populate('{back}', $this->build_back(), $template_file);
		$this->populate('{pages}', $this->build_page_numbers($total_pages), $template_file);
		
		// Optional markers
		if(!empty($settings['email']))
		{
			$settings['description'] .= "<br /> You can always email the author of the album at <a href=\"mailto:{$this->settings['epig_email']}\">{$this->settings['epig_email']}</a>";
		}
		$this->populate('{title}', $settings['title'], $template_file);
		$this->populate('{description}', $settings['description'], $template_file);
		$this->populate('{author}', $settings['email'], $template_file);
	}

	/**
	 * Displays the 'Next Page' link
	 *
	 * @param void
	 * @return string
	 */
	function build_next($text, $graphic = "")
	{	
		$html = "";
	
		if($this->get_current_page() == $this->get_total_pages())
		{
			$html .= "&nbsp;"; // "Next Page";
		}
		else
		{
			$next_page = $this->get_current_page() + 1;
			$html .= "<a href=\"{$_SERVER['PHP_SELF']}?page=$next_page\">";
			if(!empty($graphic))
			{
				$html .= "<img src=\"$graphic\" border=\"0\" alt=\"$text\"  title=\"$text\" />";
			}
			else
			{
				$html .= $text;
			}
			$html .= "</a>";
		}
	
		return $html;
	}
	
	/**
	 * Displays the 'Previous Page' link
	 *
	 * @param void
	 * @return string
	 */
	function build_back($text, $graphic = "")
	{	
		$html = "";
	
		if($this->get_current_page() == 1)
		{
			$html .= "&nbsp;";
		}
		else
		{
			$back_page = $this->get_current_page() - 1;
			$html .= "<a href=\"{$_SERVER['PHP_SELF']}?page=$back_page\">";
			if(!empty($graphic))
			{
				$html .= "<img src=\"$graphic\" border=\"0\" alt=\"$text\" title=\"$text\"/>";
			}
			else
			{
				$html .= $text;
			}
			$html .= "</a>";
		}
	
		return $html;
	}
	
	/**
	 * Displays the page number as links
	 *
	 * @param void
	 * @return string
	 */
	function build_page_numbers($total_pages)
	{
		$html = "";
		
		$i=1;
		while( $i < $total_pages + 1 )
		{
			if($total_pages == 1)
			{
				$html .="&nbsp;";
			}
		    elseif($this->get_current_page() == $i)
		    {
		    	$html .= "<b>$i</b>\n";
		    }
		    else
		    {
		    	$html .= "<a href=\"{$_SERVER['PHP_SELF']}?page=$i\">$i</a>\n";
		    }
	
		    if($i % 5 == 0)	// if the current page can be divided with 5 then line break
	    	{
	    		$html .= "<br />";
	    	}	    
	    	
		    $i++;
		}
	
		return $html;
	}
}
?>
