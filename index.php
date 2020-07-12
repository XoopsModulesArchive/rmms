<?php
// $Id: index.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

include("../../mainfile.php");
include XOOPS_ROOT_PATH."/header.php";
$myts =& MyTextSanitizer::getInstance();
$xoopsOption['template_main'] = 'rmms_index.html'; //Plantilla para esta página

function SubCategos($idc){
	global $xoopsDB;
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE parent='$idc' LIMIT 0,5");
	$rtn = '';
	while ($row=$xoopsDB->fetchArray($result)){
		$rtn .= "<a href='categos.php?idc=$row[id_cat]'>$row[nombre]</a>, ";
	}
	if (substr($rtn, strlen($rtn) - 2, 2) == ", "){
		$rtn = substr($rtn, 0, strlen($rtn) - 2);
	}
	return $rtn;
}


// Cargamos las categorías
$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE parent='0' ORDER BY nombre");
while($row=$xoopsDB->fetchArray($result)){
	$img = '';
	if ($xoopsModuleConfig['showcategoimages']){
		if ($row['imgtype']){
			$img = ($row['img']!='') ? $row['img'] : '';
		} else {
			if ($row['img']!=''){
				$img = XOOPS_URL."/modules/".$xoopsModule->dirname()."/uploads/cats/".$row['img'];
			}
		}
	}
	$xoopsTpl->append('categos', array('id'=>$row['id_cat'], 'nombre'=>$row['nombre'],
					  'desc'=>$row['desc'],'img'=>$img,'subcats'=>SubCategos($row['id_cat'])));
}

// Cargamos los productos nuevos
$total = $xoopsModuleConfig['recientes'];
$result = $xoopsDB->query("SELECT id_prd, nombre, shortdesc, img, precio FROM ".$xoopsDB->prefix('rmms_productos')." ORDER BY id_prd DESC LIMIT 0, $total");
$dir = XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/uploads/';
while ($row=$xoopsDB->fetchArray($result)){
	if ($xoopsModuleConfig['show_price']){
		$precio = number_format($row['precio'], $xoopsModuleConfig['decimales'], $xoopsModuleConfig['decsep'], $xoopsModuleConfig['milsep']);
		$precio = sprintf($xoopsModuleConfig['curformat'], $precio);
	}
	$xoopsTpl->append('prodnews', array('id'=>$row['id_prd'], 'nombre'=>$row['nombre'], 'desc'=>$row['shortdesc'], 'img'=>$dir . 'ths/' . $row['img'],'precio'=>$precio));
}

// Cargamos las opciones de lenguaje
$xoopsTpl->assign('catalog_name',$xoopsModuleConfig['modtitle']);
$xoopsTpl->assign('lng_categos',_MC_RMMS_CATEGOS);
$xoopsTpl->assign('max_width', $xoopsModuleConfig['thwidth']);
$xoopsTpl->assign('catalog_cols',$xoopsModuleConfig['cols']);
$xoopsTpl->assign('categos_cols',$xoopsModuleConfig['categocols']);
$xoopsTpl->assign('lng_recent', _MC_RECENT_PRODUCTS);
$xoopsTpl->assign('max_cols', $xoopsModuleConfig['cols']);
$xoopsTpl->assign('col_width', (int)(100/$xoopsModuleConfig['cols']));
$xoopsTpl->assign('catcol_width', (int)(100/$xoopsModuleConfig['categocols']));
$xoopsTpl->assign('total_catcols', $xoopsModuleConfig['categocols']);

include('include/functions.php');
MakeNavSearch();

$xoopsTpl->assign('rmsoft_footer', makeFoot());

include XOOPS_ROOT_PATH."/footer.php";
?>
