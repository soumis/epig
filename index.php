<?php
/**
*
* File name: index
* Copyright (c) 2005,2006 Apostolos Dountsis
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

// PHP General Settings
error_reporting(E_ALL ^ E_NOTICE);
ini_set("memory_limit","32M");

require_once('common.php');
require_once('template.php');
require_once('gallery.php');
require_once('epig.php');

// Include Styles
include_once('style_classic.php');
include_once('style_preview.php');
include_once('style_kes.php');

$start =& new epig();
?>