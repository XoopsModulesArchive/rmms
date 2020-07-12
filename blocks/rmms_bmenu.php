<?php
// $Id: rmms_bmenu.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

$b_menu_global = array();

function b_rmms_subcategos($idc, $sep){
	global $xoopsDB, $b_menu_global;
	$block = array();
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE parent='$idc'");
	while ($row=$xoopsDB->fetchArray($result)){
		$rtn = array();
		$rtn['id'] = $row['id_cat'];
		$rtn['saltos'] = str_repeat("&nbsp;&nbsp;", $sep);
		$rtn['nombre'] = $row['nombre'];
		$b_menu_global['bcategos'][] = $rtn;
		b_rmms_subcategos($row['id_cat'], $sep + 1);
	}
	//return $block;
}

function b_rmms_show_menu($options){
	global $xoopsDB, $b_menu_global;
	
	$block = array();
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('rmms_categos')." WHERE parent='0' ORDER BY nombre");
	while ($row=$xoopsDB->fetchArray($result)){
		$rtn = array();
		$rtn['id'] = $row['id_cat'];
		$rtn['nombre'] = $row['nombre'];
		$rtn['saltos'] = '';
		$b_menu_global['bcategos'][] = $rtn;
		b_rmms_subcategos($row['id_cat'], 1);
	}
	
	return $b_menu_global;
}
?>