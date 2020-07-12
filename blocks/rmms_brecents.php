<?php
// $Id: rmms_brecents.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

function b_rmms_show_recents($options){
	global $xoopsDB;
	$block = array();
	$result = $xoopsDB->query("SELECT id_prd, nombre FROM ".$xoopsDB->prefix('rmms_productos')." ORDER BY id_prd DESC LIMIT 0, $options[0]");
	while ($row=$xoopsDB->fetchArray($result)){
		$rtn = array();
		$rtn['id'] = $row['id_prd'];
		$rtn['nombre'] = $row['nombre'];
		$block['rmcat_recents'][] = $rtn;
	}
	return $block;
}

function b_rmms_recents_edit($options){
	$form = _MI_RMCAT_BRECENTS."<br><input type='text' name='options[]' value='$options[0]'>";
	return $form;
}
?>
