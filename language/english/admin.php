<?php
// $Id: admin.php 10 2006-12-12 18:38:57Z BitC3R0 $
// --------------------------------------------------------
// RMSOFT MiniShop
// Módulo para el manejo de catálogos en línea
// CopyRight © 2005 - 2006. Red México Soft
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
// --------------------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2006 - 2007 Red México Soft
// $Author: BitC3R0 $
// @package: RMSOFT MiniShop

global $lng_location;

define('_AM_NEWPRD','New Product');
define('_AM_MODPRD','Edit Product');
define('_AM_EXISTPRD','Existing Products');
define('_AM_NEWCATEGO','New Category');
define('_AM_EDITCATEGO','Edit Category');
define('_AM_EXISTCATEGO','Existing Categories');
define('_AM_DELETE','Delete');
define('_AM_MODIFY','Modify');
define('_AM_HACKATTEMPT','NOT ALLOWED');
define('_AM_CANCEL','Cancel');
define('_AM_OK','Send');
define('_AM_DB_ERROR','Error in this action:');
define('_AM_DBOK','Database updated successfully');

if ($lng_location=='categos'){
	define('_AM_NAME','Name');
	define('_AM_PRDS','Products');
	define('_AM_OPTIONS','Options');
	define('_AM_DESC','Description');
	define('_AM_IMAGEFILE','Image File');
	define('_AM_IMAGEFILETIP','If this field is provided the next filed will be ignored.');
	define('_AM_IMAGEURL','Image URL');
	define('_AM_IMAGEURLTIP','this option only is valid when the previos field is not used.');
	define('_AM_PARENT','Parent Category');
	define('_AM_NOCATEGOS','There are not categories yet.');
	define('_AM_ERRORNAME','You must provide the category name');
	define('_AM_SAVEOK','Category created successfully');
	define('_AM_CATEGOEXIST','The specified category already exists.');
	define('_AM_CATEGONOEXIST','The specified category does not exists.');
	define('_AM_CATEGOMODIFIED','Category modified successfully');
	define('_AM_CONFIRMDELETE','Do you really want to delete this category?');
	define('_AM_DELETED','Category Deleted Successfully');
	define('_AM_CURRENTIMG','Current Image');
	define('_AM_DELETE_CURRIMG','Delete current Image?');
}

if ($lng_location=='productos'){
	define('_AM_EMPTY','There are not products at this moment.<br>Redirecting to new producto form.');
	define('_AM_NAME','Product Name');
	define('_AM_PRDCODE','Product Code');
	define('_AM_PRDQUANTITY','Quantity per Pack');
	define('_AM_PRDSIZE','Size');
	define('_AM_PRDWEIGHT','Weight');
	define('_AM_SHORTDESC','Short Description');
	define('_AM_LONGDESC','Long Description');
	define('_AM_PRDMINIMO','Minimum Purchase');
	define('_AM_PRDPRECIO','Price');
	define('_AM_PRDBIG','Product Image');
	define('_AM_SHOWINBLOCK','Show in Block');
	define('_AM_CATEGO','Category');
	define('_AM_ERRNOMBRE','You must provide the product Name');
	define('_AM_ERRCODE','You must specify the product code');
	define('_AM_ERRQUANT','You must provide the quantity per pack');
	define('_AM_ERRSIZE','You must to specify the producto size');
	define('_AM_ERRPESO','You must to specify the producto Weight');
	define('_AM_ERRMIN','You must to specify the minimum purchase');
	define('_AM_ERRSHORT','You must to specify a product description');
	define('_AM_ERRLONG','Please provide a long description for this product');
	define('_AM_ERRPRICE','Please. provide the product price');
	define('_AM_ERRCATEGO','You must to select a cetegory');
	define('_AM_PRDCREATED','Product created successfully');
	define('_AM_PRDMODIFIED','Product modified successfully');
	define('_AM_NOCATEGOS','There are not categories yet.');
	define('_AM_PRDNAME','Name');
	define('_AM_OPTIONS','Options');
	define('_AM_HCATEGO','Category');
	define('_AM_PRDEXIST','The specified product already exists');
	define('_AM_CATEGOS','Categories');
	define('_AM_NOEXIST','The specified product does not exists');
	define('_AM_CATEGOASSIGN','Assigned Categories');
	define('_AM_CATASSIGN','Category');
	define('_AM_ASSIGN','Assign');
	define('_AM_CONFIRMDELETE','Do you really want to delete this product?');
	define('_AM_DELETED','Product deleted successfully');
	define('_AM_PRDDATA','Product Data');
	define('_AM_PRODUCTOS','Related Products');
	define('_AM_ORDERNAME', 'Order by Name');
	define('_AM_ORDERLAST', 'Order by Date');
	define('_AM_CATEGO_SELECT','Select');
	define('_AM_CURRENTIMG','Current Image');
	define('_AM_IMAGES','Images');
	define('_AM_MAGESFOR','"%s" Images');
	define('_AM_PIMAGE','Image');
	define('_AM_ITITLE','Title');
	define('_AM_NEWIMAGE','Add image to "%s"');
	define('_AM_CONFIRMDEL','Delete this image:');
	define('_AM_PAGE','Page: ');
}
?>
