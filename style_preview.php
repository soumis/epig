<?php
/**
*
* Class name: style_preview
* Copyright (c) 2005,2006 Apostolos Dountsis
*
* The Preview style displays a large preview of the selected
* thumbnail on the same page as the thumbnails.
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

class style_preview extends template
{
	function style_preview($template_file)
	{
		$this->load($template_file);
	}

	/**
	 * Displays the preview of the selected image
	 *
	 * @param string, boolean
	 * @return string
	 */	
	function build_preview($preview_file, $popup=false)
	{
		if(!$preview_file)
		{
			// Load the first image on the current page
			$img = $this->get_images_in_page($this->get_current_page());
			$preview_file = $img[0];
		}
		
		// Popup window dimensions
		$size = GetImageSize($preview_file);//$this->Images[$i]);
		$offset = 0; //40;
		$image_width = $size[0] + $offset;
		$image_height = $size[1] + $offset;
	
		// HTML for Preview
		if($popup == true)
		{
			$html = "<a href=\"#\" onClick=\"javascript:window.open('$preview_file','actualimage','toolbar=0,menubar=0,width=$image_width,height=$image_height,resizable=1,scrollbars=0,status=0');\" title=\"Click on the preview to see the image in actual size\">";
		}
		
		$html .= "<div class=\"gallery\"> <img border=\"0\" src=\"{$_SERVER['PHP_SELF']}?preview=$preview_file\"></div>"; //{$this->Images[$i]}\">";
		
		if($popup == true)
		{
			$html .= "</a>";
		}
		
		// Build the caption for the thumbnail
		$html .= $this->build_caption($preview_file, $this->filename, 1);		
		
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
					$html .= "<td>\n<div class=\"gallery\">\n";
					$image_href = "{$_SERVER['PHP_SELF']}?preview_file={$Images[$img_index]}&page=".$this->get_current_page();
					if($image_href != "")
					{
						$html .= "<a href=\"$image_href\" target=\"$image_target\">\n";
					}
					$html .= "<img class=\"thumbnail\" border=\"0\" src=\"{$_SERVER['PHP_SELF']}?thumbnail={$Images[$img_index]}&page=".$this->get_current_page()."\" />";
					if($image_href != "")
					{
						$html .= "</a>\n";
					}
					
					// Build the caption for the thumbnail	
					$html .= $this->build_caption($Images[$img_index], $this->filename, 0);
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
	
	function populate_template(&$settings)
	{
		$this->populate('{preview}', $this->build_preview($_REQUEST['preview_file']));
		$this->populate('{gallery}', $this->build_thumbnail_page($this->get_current_page()));
		$this->populate('{next}', $this->build_next('Next Page', 'nav/next.gif'));
		$this->populate('{back}', $this->build_back('Back Page', 'nav/back.gif'));
		$this->populate('{pages}', $this->build_page_numbers($this->get_total_pages()));
		
		// Optional markers
		if(!empty($settings['email']))
		{
			$settings['description'] .= "<br /> You can always email the author of the album at <a href=\"mailto:{$settings['email']}\">{$settings['email']}</a>";
		}
		$this->populate('{title}', $settings['title'], $template_file);
		$this->populate('{description}', $settings['description'], $template_file);
		$this->populate('{author}', $settings['author'], $template_file);
	}
}
?>
