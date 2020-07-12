<?php
// $Id: search.php 8 2006-12-12 06:47:55Z BitC3R0 $
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
include('include/functions.php');

$key = isset($_GET['key']) ? $_GET['key'] : (isset($_POST['key']) ? $_POST['key'] : '');
$idc = isset($_GET['idc']) ? $_GET['idc'] : (isset($_POST['idc']) ? $_POST['idc'] : '');

if ($key==''){
	redirect_header('index.php', 1, _MC_PROD_NOKEY);
	die();
}

$tblprod = $xoopsDB->prefix("rmms_productos");
$tblcat = $xoopsDB->prefix("rmms_categos");
$tblrel = $xoopsDB->prefix("rmms_relations");

$xoopsOption['template_main'] = 'rmms_search.html';
$sql = "SELECT COUNT(*) FROM $tblprod";
if ($idc>0){ $sql .= ", $tblrel "; }
	$sql .= " WHERE (nombre LIKE '%$key%' OR shortdesc LIKE '%$key%' OR
			longdesc LIKE '%$key%' OR codigo LIKE '%$key%')";
if ($idc>0){
	$sql .= " AND $tblrel.id_prd = $tblprod.id_prd AND $tblrel.id_cat = '$idc'";
}

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

list($num) = $xoopsDB->fetchRow($xoopsDB->query($sql));
$pag = isset($_GET['pag']) ? $_GET['pag'] : 0;
if ($pag > 0){ $pag -= 1; }
$start = $pag * $limit;
$rtotal = $num; // Numero total de resultados
$tpages = (int)($num / $limit);
$pactual = $pag + 1;

$xoopsTpl->assign('lng_founded', sprintf(_MC_PROD_FOUND, $num, $key));

if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * 15;
}

$sql = "SELECT $tblprod.nombre, $tblprod.id_prd, $tblprod.img, $tblprod.shortdesc, $tblprod.precio FROM $tblprod";
$sql .= " WHERE ($tblprod.nombre LIKE '%$key%' OR $tblprod.shortdesc LIKE '%$key%' OR
			$tblprod.longdesc LIKE '%$key%' OR $tblprod.codigo LIKE '%$key%')";
if ($idc>0){ $sql .= " AND id_cat='$idc'"; }
$sql .= " ORDER BY $tblprod.nombre LIMIT $start, $limit";
$result = $xoopsDB->query($sql);
if (($num % $limit) > 0){ $tpages += 1; }
echo $xoopsDB->error();
while ($row=$xoopsDB->fetchArray($result)){
	$precio = number_format($row['precio'], $xoopsModuleConfig['decimales'], $xoopsModuleConfig['decsep'], $xoopsModuleConfig['milsep']);
	$precio = sprintf($xoopsModuleConfig['curformat'], $precio);
	$img = ($row['img']!='') ? XOOPS_URL."/modules/".$xoopsModule->dirname()."/uploads/ths/".$row['img'] : '';
	$xoopsTpl->append('productos', array('id'=>$row['id_prd'], 'nombre'=>$row['nombre'], 'desc'=>$row['shortdesc'], 'precio'=>$precio,'img'=>$img));
}
$xoopsTpl->assign('lng_totalpages', sprintf(_MC_CATEGO_TOTALPAG, $pactual, $tpages));
$nav = _MC_RMMS_PAGE;
for ($i=1;$i<=$tpages;$i++){
	$nav .= "<a href='search.php?pag=$i&amp;key=$key'>$i</a> ";
}
$xoopsTpl->assign('nav_pages',$nav);

// Cargamos las opciones de lenguaje
$xoopsTpl->assign('catalog_name',$xoopsModuleConfig['modtitle']);
$xoopsTpl->assign('max_width', $xoopsModuleConfig['thwidth']);
$xoopsTpl->assign('catalog_cols',$xoopsModuleConfig['cols']);
$xoopsTpl->assign('lng_recent', _MC_RECENT_PRODUCTS);
$xoopsTpl->assign('max_cols', $xoopsModuleConfig['cols']);
$xoopsTpl->assign('col_width', (int)(100/$xoopsModuleConfig['cols']));
$xoopsTpl->assign('lng_precio', _MC_CATEGO_PRICE);
$xoopsTpl->assign('lng_prodxpage', _MC_RMCAT_PRODXPAG);
$xoopsTpl->assign('lang_go', _MC_RMCAT_GO);
$xoopsTpl->assign('lng_goto', _MC_RMCAT_GOTO);
$xoopsTpl->assign('lng_resultslist', sprintf(_MC_PROD_RESULT, $key));
$xoopsTpl->assign('key', $key);
$xoopsTpl->assign('catego_id', $idc);

MakeNavSearch();
rmcat_items_xpagina();
$xoopsTpl->assign('rmsoft_footer', makeFoot());
include XOOPS_ROOT_PATH."/footer.php";

?>