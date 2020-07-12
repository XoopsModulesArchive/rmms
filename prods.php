<?php
// $Id: prods.php 8 2006-12-12 06:47:55Z BitC3R0 $
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
include_once('include/functions.php');
$myts =& MyTextSanitizer::getInstance();
$xoopsOption['template_main'] = 'rmms_productos.html'; //Plantilla para esta página

$idp = $_GET['idp'];
if ($idp<=0){
	redirect_header('index.php', 2, _MC_PRODID_MISSING);
	die();
}

$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_prd='$idp'");
$num = $xoopsDB->getRowsNum($result);
if ($num <= 0){
	redirect_header("index.php", 1, _MC_RMCAT_PRODNOTFOUND);
	die();
}
$row = $xoopsDB->fetchArray($result);
$img = XOOPS_URL."/modules/".$xoopsModule->dirname()."/uploads/".$row['img'];
$precio = number_format($row['precio'], $xoopsModuleConfig['decimales'], $xoopsModuleConfig['decsep'], $xoopsModuleConfig['milsep']);
$precio = sprintf($xoopsModuleConfig['curformat'], $precio);
$xoopsTpl->assign('producto',array('id'=>$row['id_prd'],'nombre'=>$row['nombre'],
			'codigo'=>$row['codigo'],'minimo'=>$row['minimo'],'medidas'=>$row['medidas'],
			'cantidad'=>$row['cantidad'],'peso'=>sprintf($xoopsModuleConfig['formatpeso'], $row['peso']),'img'=>$img,'precio'=>$precio,
			'desc'=>$myts->makeTareaData4Show($row['longdesc']),'images'=>rmmsGetImages($row['id_prd'])));

// Cargamos las opciones de lenguaje
$xoopsTpl->assign('catalog_name',$xoopsModuleConfig['modtitle']);
$xoopsTpl->assign('lng_prod_title', sprintf(_MC_RMMS_PRODDETAILS, $row['nombre']));
$xoopsTpl->assign('max_width', $xoopsModuleConfig['imgcategow']);
$xoopsTpl->assign('catalog_cols',$xoopsModuleConfig['cols']);
$xoopsTpl->assign('lng_recent', _MC_RECENT_PRODUCTS);
$xoopsTpl->assign('max_cols', $xoopsModuleConfig['cols']);
$xoopsTpl->assign('col_width', (int)(100/$xoopsModuleConfig['cols']));
$xoopsTpl->assign('lng_precio', _MC_CATEGO_PRICE);
$xoopsTpl->assign('lng_prodxpage', _MC_RMCAT_PRODXPAG);
$xoopsTpl->assign('lang_go', _MC_RMCAT_GO);
$xoopsTpl->assign('lng_goto', _MC_RMCAT_GOTO);

$xoopsTpl->assign('lng_codigo',_MC_PROD_CODE);
$xoopsTpl->assign('lng_precio',_MC_PROD_PRICE);
$xoopsTpl->assign('lng_cantidad',_MC_PROD_CANTIDAD);
$xoopsTpl->assign('lng_medidas',_MC_PROD_SIZE);
$xoopsTpl->assign('lng_peso', _MC_PROD_PESO);
$xoopsTpl->assign('lng_minimo', _MC_PROD_MINIMO);
$xoopsTpl->assign('lng_desc', _MC_PROD_DESCRIPTION);
$xoopsTpl->assign('lng_moreinfo',_MC_PROD_MOREINFO);
$xoopsTpl->assign('lang_images', _MC_PROD_IMAGES);
$xoopsTpl->assign('viewimg_width', $xoopsModuleConfig['imgwidth'] + 50);
$xoopsTpl->assign('viewimg_height', $xoopsModuleConfig['imgheight'] + 50);

// MOstramos o no el precio
$mc =& $xoopsModuleConfig;
$xoopsTpl->assign('show_price', ($mc['show_price']==0) ? 1 : (is_object($xoopsUser) ? 1 : 0));
$xoopsTpl->assign('tax_legend', ($mc['taxincluded']==1) ? _MC_PROD_TAXYES : _MC_PROD_TAXNO);

MakeNavSearch();
$xoopsTpl->assign('rmsoft_footer', makeFoot());
include XOOPS_ROOT_PATH."/footer.php";
?>
