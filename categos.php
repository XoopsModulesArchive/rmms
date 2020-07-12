<?php
// $Id: categos.php 8 2006-12-12 06:47:55Z BitC3R0 $
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
$xoopsOption['template_main'] = 'rmms_categos.html'; //Plantilla para esta página

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

$idc = $_GET['idc'];
if ($idc<=0){
	redirect_header("index.php", 1, _MC_CATEGOID_MISSING);
	die();
}

// Cargamos las subcategorías
$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE parent='$idc' ORDER BY nombre");
$num = $xoopsDB->getRowsNum($result);
$xoopsTpl->assign('subcategos_num', $num);
while($row=$xoopsDB->fetchArray($result)){
	$img = '';
	if ($row['imgtype']){
		$img = ($row['img']!='') ? $row['img'] : '';
	} else {
		if ($row['img']!=''){
			$img = XOOPS_URL."/modules/".$xoopsModule->dirname()."/uploads/cats/".$row['img'];
		}
	}
	$xoopsTpl->append('categos', array('id'=>$row['id_cat'], 'nombre'=>$row['nombre'],
					  'desc'=>$row['desc'],'img'=>$img,'subcats'=>SubCategos($row['id_cat'])));
}

$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat='$idc'");
$num = $xoopsDB->getRowsNum($result);
if ($num <= 0){
	redirect_header("index.php", 1, _MC_CATEGO_NOTFOUND);
	die();
}
$row = $xoopsDB->fetchArray($result);

$xoopsTpl->assign('catego_id', $row['id_cat']);
$xoopsTpl->assign('catego_nombre', $row['nombre']);
$xoopsTpl->assign('lng_subcategos', sprintf(_MC_RMMS_SUBCATEGOS, $row['nombre']));
$xoopsTpl->assign('catego_desc', $myts->makeTareaData4Show($row['desc']));
if ($row['imgtype']==0){
	$xoopsTpl->assign('catego_img', XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/uploads/cats/".$row['img']);
} else {
	$xoopsTpl->assign('catego_img', $row['img']);
}

$xoopsTpl->assign('lng_prdexist', sprintf(_MC_PROD_EXIST, $row['nombre']));

//NUmero de resultados por página
if (isset($_GET['itemsxpag'])){
	//setcookie('itemsxpag', $_GET['itemsxpag'], 86400);
	$_SESSION['itemsxpag'] = $_GET['itemsxpag'];
	$limit = $_GET['itemsxpag'];
} else {
	$limit = $_SESSION['itemsxpag'];
}

if ($limit <= 0){
	$limit = $xoopsModuleConfig['cols'] * 3;
	$_SESSION['itemsxpag'] = $limit;
}

// Paginamos los productos Existentes
$pag = isset($_GET['pag']) ? $_GET['pag'] : 0;
if ($pag > 0){ $pag -= 1; }
$start = $pag * $limit;

$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_cat='$idc'");
list($num) = $xoopsDB->fetchRow($result);
$rtotal = $num; // Numero total de resultados
$tpages = (int)($num / $limit);
$pactual = $pag + 1;

$xoopsTpl->assign('total_result', sprintf(_MC_TOTAL_RESULTS, $num));

if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * $limit;
}

$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_cat='$idc' ORDER BY id_prd DESC LIMIT $start,$limit");

if (($num % $limit) > 0){ $tpages += 1; }

while ($row=$xoopsDB->fetchArray($result)){
	$data = array();
	$xoopsTpl->append('productos', catalog_data_product($row));
}

$xoopsTpl->assign('lng_totalpages', sprintf(_MC_CATEGO_TOTALPAG, $pactual, $tpages));
$nav = _MC_RMMS_PAGE;
for ($i=1;$i<=$tpages;$i++){
	$nav .= "<a href='categos.php?idc=$idc&amp;pag=$i'>$i</a>&nbsp;";
}
$xoopsTpl->assign('nav_pages',$nav);

// Cargamos las opciones de lenguaje
$xoopsTpl->assign('catalog_name',$xoopsModuleConfig['modtitle']);
$xoopsTpl->assign('categos_cols',$xoopsModuleConfig['categocols']);
$xoopsTpl->assign('catcol_width', (int)(100/$xoopsModuleConfig['categocols']));
$xoopsTpl->assign('total_catcols', $xoopsModuleConfig['categocols'] *2);
$xoopsTpl->assign('max_width', $xoopsModuleConfig['thwidth']);
$xoopsTpl->assign('catalog_cols',$xoopsModuleConfig['cols']);
$xoopsTpl->assign('lng_recent', _MC_RECENT_PRODUCTS);
$xoopsTpl->assign('max_cols', $xoopsModuleConfig['cols']);
$xoopsTpl->assign('col_width', (int)(100/$xoopsModuleConfig['cols']));
$xoopsTpl->assign('lng_precio', _MC_CATEGO_PRICE);
$xoopsTpl->assign('lng_codigo', _MC_PROD_CODE);
$xoopsTpl->assign('lng_prodxpage', _MC_RMCAT_PRODXPAG);
$xoopsTpl->assign('lang_go', _MC_RMCAT_GO);
$xoopsTpl->assign('lng_goto', _MC_RMCAT_GOTO);

MakeNavSearch();
rmcat_items_xpagina();
$xoopsTpl->assign('rmsoft_footer', makeFoot());

include XOOPS_ROOT_PATH."/footer.php";
?>
