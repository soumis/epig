<?php
/**
*
* Class name: style_kes
* Copyright (c) 2005,2006 Apostolos Dountsis
*
* Displays the thumbnails as in the classic style.
* When the user clicks on a thumbnail then
* the preview of it is being displayed without
* the thumbnail gallery.
* Clicking on the Back/Next button allows the user
* to progress on the next image. A link back to the
* current thumbnail page is provided through the
* Index/"Table of Contents" link.
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

class style_kes extends template
{
	function style_kes($template_file)
	{
		$this->load($template_file);
	}
	
	function populate_template(&$settings)
	{

		if(!isset($_REQUEST['view']))
		{
			// Thumbnail Mode
			$url = "{$_SERVER['PHP_SELF']}?view=img_name&key=img_index";
			$this->populate('{gallery}', $this->build_thumbnail_page($this->get_current_page(), $url, '_self'));
			$this->populate('{next}', $this->build_next('Next Page', 'nav/next.gif'));
			$this->populate('{back}', $this->build_back('Previous Page', 'nav/back.gif'));
			$this->populate('{pages}', $this->build_page_numbers($this->get_total_pages()));
		}
		else
		{
			// Solo Mode
			$this->populate('{gallery}', $this->build_preview($_REQUEST['view'], false));
			$this->populate('{pages}', $this->build_index('nav/toc.gif', 'Table of Contents'));
			$this->populate('{back}', $this->build_back_solo('Previous Image', 'nav/back.gif'));
			$this->populate('{next}', $this->build_next_solo('Next Image', 'nav/next.gif'));					
		}
		
		$this->populate('{preview}', '');		
//		$this->populate('{preview}', $this->build_preview($_REQUEST['preview_file']));
//		$this->populate('{gallery}', $this->build_thumbnail_page($this->get_current_page()));
//		$this->populate('{pages}', $this->build_page_numbers($this->get_total_pages()));
		
		// Optional markers
		if(!empty($settings['email']))
		{
			$settings['description'] .= "<br /> You can always email the author of the album at <a href=\"mailto:{$settings['email']}\">{$settings['email']}</a>";
		}
		
		$this->populate('{title}', $settings['title'], $template_file);
		$this->populate('{description}', $settings['description'], $template_file);
		$this->populate('{author}', $settings['author'], $template_file);
	}


	function build_index($toc_graphic = "", $toc_text)
	{
		$page = $this->get_page_of_image($_REQUEST['view']);
		if($toc_graphic == "")
		{
			return("<a alt= \"$toc_text\" title=\"$toc_text\" href=\"{$_SERVER['PHP_SELF']}?page=$page\">$toc_text</a>");
		}
		else
		{
			$toc_url = "<img alt= \"$toc_text\" title=\"$toc_text\" border=\"0\" src=\"$toc_graphic\" />";
			return("<a href=\"{$_SERVER['PHP_SELF']}?page=$page\">$toc_url</a>");
		}		
	}
	
	/**
	 * Displays the preview of the selected image
	 *
	 * @param string, boolean
	 * @return string
	 */	
	function build_preview($preview_file, $popup=false)
	{
		$html = "";
		
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
		
		$html .= "<div class=\"gallery\">\n";
		// HTML for Preview
		if($popup == true)
		{
			$html .= "<a href=\"#\" onClick=\"javascript:window.open('$preview_file','actualimage','toolbar=0,menubar=0,width=$image_width,height=$image_height,resizable=1,scrollbars=0,status=0');\" title=\"Click on the preview to see the image in actual size\">";
		}
		
		$html .= "<img class=\"thumbnail\" border=\"0\" src=\"{$_SERVER['PHP_SELF']}?preview=$preview_file\">"; //{$this->Images[$i]}\">";
		
		if($popup == true)
		{
			$html .= "</a>";
		}
		$html .="</div>";
		// Build the caption for the thumbnail
		$html .= $this->build_caption($preview_file, $this->filename, 1);		
		
		return $html;
	}

	/**
	 * Displays the 'Next Page' link
	 *
	 * @param void
	 * @return string
	 */
	function build_next_solo($text = "Next Image", $graphic_next = "")
	{	
		$html = "";
//		$graphic_next = "nav/next.gif";
		$current_img = $_REQUEST['key'];
		
		if($current_img < sizeof($this->Images)-1)
		{
			$next_img = $current_img + 1;
			$html .= "<a href=\"index.php?view={$this->Images[$next_img]}&key=$next_img\">";
			if($graphic_next != "")
			{
				$html .= "<img title=\"$text\" alt=\"$text\" src=\"$graphic_next\" border=\"0\"  />";
			}
			else
			{
				$html .= $text;
			}
			$html .= "</a>";
		}
		else
		{
			$html .= "&nbsp;"; // There is't a next image
		}
	
		return $html;
	}
	
	/**
	 * Displays the 'Previous Page' link
	 *
	 * @param void
	 * @return string
	 */
	function build_back_solo($text = "Previous Image", $graphic_back = "")
	{	
		$html = "";
//		$graphic_back = "nav/back.gif";
		// $pages = ceil(sizeof($this->Images) / ($this->settings['epig_rows'] * $this->settings['epig_columns']));
		$current_img = $_REQUEST['key'];
		
		if($current_img > 0)
		{
			$back_img = $current_img - 1;
			$html .= "<a href=\"index.php?view={$this->Images[$back_img]}&key=$back_img\">";

			if($graphic_back != "")
			{
				$html .= "<img title=\"$text\" alt=\"$text\" src=\"$graphic_back\" border=\"0\"  />";
			}
			else
			{
				$html .= $text;
			}

			$html .= "</a>";
		}
		else
		{
			$html .= "&nbsp;"; // There is no next page
		}
	
		return $html;
	}
}
?>
