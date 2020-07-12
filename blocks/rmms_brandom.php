<?php
// $Id: rmms_brandom.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

function b_rmms_show_random($options){
	global $xoopsDB, $xoopsModule;
	
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix('rmms_productos')." WHERE inblock='1'");
	list($num) = $xoopsDB->fetchRow($result);
	if ($num>1) {
		$num = $num - 1;
    	mt_srand((double)microtime()*1000000);
    	$prdnum = mt_rand(0, $num);
 	} else {
    	$prdnum = 0;
 	}
	$result = $xoopsDB->query("SELECT id_prd, nombre, img FROM ".$xoopsDB->prefix('rmms_productos')." WHERE inblock='1' LIMIT $prdnum, 1 ;");
	$row = $xoopsDB->fetchArray($result);
	$block = array();
	$prd = array();
	$prd['id'] = $row['id_prd'];
	$prd['nombre'] = $row['nombre'];
	$prd['img'] = XOOPS_URL."/modules/rmms/uploads/ths/".$row['img'];
	$block['rmcat_producto'][] = $prd;
	return $block;
}
?>
