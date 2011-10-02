<?php
/**
*
* Class name: gallery
* Copyright (c) 2005,2006 Apostolos Dountsis
*
* The Model class of the MVC architecture
* Loads the images in the current directory
* Contains the functions that handle
* the image manipulations
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

class gallery
{
	// The album photos
	var $Images = array();
	
	function gallery($sort_type, $sort_order)
	{
		// Load the images
		$this->Images = common::load_images();
		if(empty($this->Images))
		{
			common::msg_box("Your directory does not contain any images. Please upload at least one image at <br />".dirname(__FILE__)."<br />and EPIG will do the rest.");
			exit();
		}
		
		// Sort the Images
		common::sort_files($this->Images, $sort_type, $sort_order);
		
//		$this->cache_images();
	}
	
	function cache_images()
	{
		$this->write_file($this->Images[0]);
	}
	
	/**
	* Write a file
	*
	* @return string
	*/	
	function write_file($file)
	{
		$dir = "cache/";
		// does the file exist
		if(!file_exists($dir.$file))
		{
			// create file
			if(!$handle = fopen($dir.$file, 'x+'))
			{
				die("Failed to create file $file");
			}
		}
		else
		{
			// Open the log file in "append" mode
			if (!$handle = fopen($dir.$file, 'a+')) 
			{
				  die("Failed to open file $file");
			}	 		
		}

		// Write $logline to our logfile.
		if (fwrite($handle, $this->generate_thumbnail($file, 200, 100, 75)) === FALSE) {
			  die("Failed to write file $file");
		}

		// Close log file
		fclose($handle);


		// Read and write for owner, read for everybody else
		chmod($dir.$file, 0644);	
	}
	
	function set_images(&$i)
	{
		$this->Images = $i;
	}
	
	/**
	 * Generates the thumbnail for the specified image file
	 *
	 * @param string $image_file
	 * @param int $thumb_width
	 * @param int $thumb_height
	 * @param int $thumb_quality
	 * @return void
	 */
	function generate_thumbnail($image_file, $width, $height, $quality)
	{
		
		if($image_size['mime'] == 'image/jpeg')
		{
			Header("Content-type: image/jpeg");
		}
		elseif($image_size['mime'] == 'image/gif')
		{
			Header("Content-type: image/gif");
		}
		elseif($image_size['mime'] == 'image/png')
		{
			Header("Content-type: image/png");
		}

		// Actual Image Dimensions
		$actual_size = GetImageSize($image_file);
		$actual_width = $actual_size[0];
		$actual_height = $actual_size[1];
		
		// Thumbnail Dimensions

		// Aspect Ratio
		$x_ratio = $width / $actual_width;
		$y_ratio = $height / $actual_height;
	
		if( ($actual_width <= $width) && ($actual_height <= $height) )
		{
			$width = $actual_width;
			$height = $actual_height;
		}
		elseif (($x_ratio * $actual_height) < $height)
		{
			$height = ceil($x_ratio * $actual_height);
		}
		else
		{
			$width = ceil($y_ratio * $actual_width);
		}

		if($actual_size['mime'] == 'image/jpeg')	// JPEG
		{
			$src_image = imagecreatefromjpeg($image_file);
			$dst_image = imagecreatetruecolor($width, $height);
			
			// copy a rectangular portion of one image to another image, 
			// smoothly interpolating pixel values so that, in particular, 
			// reducing the size of an image still retains a great deal of clarity
			imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $actual_width, $actual_height);	

			Header("Content-type: image/jpeg");
			imagejpeg($dst_image,'', $quality);
		}
		elseif($actual_size['mime'] == 'image/png')	// PNG
		{
			$src_image = imagecreatefrompng($image_file);
			$dst_image = imagecreatetruecolor($width, $height);
			
			// copy a rectangular portion of one image to another image, 
			// smoothly interpolating pixel values so that, in particular, 
			// reducing the size of an image still retains a great deal of clarity
			imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $actual_width, $actual_height);
	
			Header("Content-type: image/png");
			imagepng($dst_image);
		}	     	
	     elseif($actual_size['mime'] == 'image/gif')	// GIF
	     {
			$src_image = imagecreatefromgif($image_file);
			$dst_image = imagecreatetruecolor($width, $height);

			// copy a rectangular portion of one image to another image, 
			// smoothly interpolating pixel values so that, in particular, 
			// reducing the size of an image still retains a great deal of clarity
			imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $actual_width, $actual_height);
	
			Header("Content-type: image/gif");
			imagegif($dst_image);
		}
		
		imagedestroy($src_image);
		imagedestroy($dst_image);
	}
}
?>
