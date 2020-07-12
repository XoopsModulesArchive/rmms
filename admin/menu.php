<?php
// $Id: menu.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

$adminmenu[0]['title'] = _MI_MENU1;
$adminmenu[0]['link'] = "admin/categos.php?op=new";
$adminmenu[0]['icon'] = "images/newcatego.png";
$adminmenu[1]['title'] = _MI_MENU2;
$adminmenu[1]['link'] = "admin/categos.php";
$adminmenu[1]['icon'] = "images/categos.png";
$adminmenu[2]['title'] = _MI_MENU3;
$adminmenu[2]['link'] = "admin/productos.php?op=new";
$adminmenu[2]['icon'] = "images/newprod.png";
$adminmenu[3]['title'] = _MI_MENU4;
$adminmenu[3]['link'] = "admin/productos.php";
$adminmenu[3]['icon'] = "images/prods.png";
?>
