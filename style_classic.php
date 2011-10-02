<?php
/**
*
* Class name: style_classic
* Copyright (c) 2005,2006 Apostolos Dountsis
*
* The Classic style is the style used until version 1.08
* It displays a list of thumbnails.
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

class style_classic extends template
{
	function style_classic($template_file)
	{
		$this->load($template_file);
	}
	
	function populate_template(&$settings)
	{
		$this->populate('{preview}', '');
		$this->populate('{gallery}', $this->build_thumbnail_page($this->get_current_page(),'actual','_blank'));
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
