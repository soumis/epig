Easy Peasy Image Gallery (EPIG) version 2.0

Copyright(c) 2011 Apostolos (Soumis) Dountsis
http://www.dountsis.com
http://www.dountsis.com/projects/epig

Description
-----------
Ever had a collection of photos from your holidays or parties that you wanted to make them available on the web for your friends and relatives to enjoy? With EPIG, you can do just that. All that you need is web space to host your EPIG album with PHP & GD support. Simply upload the EPIG files on the same folder where your photos are and you are done!

EPIG creates an image gallery with automatically generated thumbnails for all the image files (supports JPEG, GIF and PNG files). You can then click on the thumbnails to see the actual images. You can navigate page-by-page or by page numbers. 

Features
---------

* Automatic generation of an image gallery
* Three Presentation Styles
* Custom thumbnail dimensions
* Custom preview Image dimensions
* Custom image quality
* Display a description and/or filename for each image
* Sort your gallery by filename or date in either ascending or descending order
* Specify number of thumbnail rows & columns in each page
* NO Database is required. Only a webserver with PHP and GD installed
* Gallery template for easy integration with the rest of your web site


Customisation
-------------
EPIG has been designed to work without any modification. However, it is very simple to customise it. All the customisation can be done through the config file. 

You can also customise the dimensions of the generated thumbnails and/or their quality (applies only to JPG images). If you want to customise it further, you can modify the supplied template or create your own, based on the instructions provided below.
You can display the filename of the image below its thumbnail and/or display an image description as a caption. Finally, you can sort your album by filename or date in ascending or descending order.

EPIG can display your album in three different using presentation styles. EPIG 2.0 offers three presentation styles for your albums.

Classic style: Displays pages of image thumbnails. When a thumbnail is selected and the image on its original size is displayed on a new window.

Preview style: Displays pages of image thumbnails that allow the user to click on a thumbnail and a preview is displayed on the same page (no need for the pop-up page anymore). The dimensions and quality of the preview are configurable through config.xml.

Kes style: Once the user clicks on a thumbnail, the selected image is displayed on its own page with navigation to the next and previous image. A button back to the thumbnails is also provided.


Requirements
------------
EPIG requires PHP4 with GD support. It does not require the presence of a database.


Installation
------------
EPIG comprises of the following files:

    * The php files comprise the EPIG kernel. You simply need to upload all the files to your web server. No modification is required on any of the PHP files.

    * config.xml is the configuration file. Edit this XML file with a text editor (like notepad or textedit) to personalise your EPIG album. Save your changes and upload config.xml file on the same location where the EPIG kernel resides and your pictures.

    * template.html is the default template. You can simply upload the file as provided or you can edit it first. You can always point to a different template by amending the <template> tag value in the config.xml.

After you have dealt with the EPIG files as described above, upload all your photos in the same location as the EPIG files.


Custom Template Instructions
----------------------------
You can create your own templates by creating an html file and applying the EPIG markers on it.

Do not forget to change the value for the <template> tag in config.xml if the filename is other than 
"template.html". The EPIG markers are:

	{gallery}	: Your thumbnails
	{preview}	: The preview image used in Preview & Kes styles
	{next}		: The link to the next page
	{back}		: The link to the back page
	{pages}		: A list with all your pages as links
	{title}		: The title of your album (optional)
	{description}	: A description for your album (optional)
	{author}	: Your Name (optional)

EPIG warps the images around a CSS class "thumbnail". This class allows you to redefine the margins and padding of the thumbnails, or provide a user-made border around them. If caption is used then you can specify a CSS class "caption" to tweak the captions.


Developer's Notes
------------------
EPIG 2.0 has been designed based on the MVC architecture (http://en.wikipedia.org/wiki/Model-view-controller). Gallery.php is the Model class. It loads the images of the current directory and it contains the functions that handle the image manipulation. Epig.php is the Controller class. This class is the core of the application. It interprets the URL requests and controls the View and Model classes. Finally, template.php is the View class. It produces all the HTML needed to display the image gallery. common.php is a collection of a general-purpose functions that can be called statically from any of the other classes.

You can develop your own styles by extending either the template.php or any of the supplied styles. My suggestion would be to have a look on the code of the provided styles before you start.


Feedback & Suggestions
----------------------
I welcome your feedback and suggestions on EPIG. My goal was to develop an image gallery which would be easy to setup and maintain.

You can send me any ideas on improvements and/or problems that you may encounter with EPIG though the project web site: http://www.dountsis.com/projects/epig


EPIG License
------------
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details (http://www.gnu.org/licenses/gpl.html).

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.