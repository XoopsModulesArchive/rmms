<?php
// $Id: moreinfo.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

$xoopsOption['template_main'] = 'rmms_moreinfo.html'; //Plantilla para esta página

$idp = $_GET['idp'];

if ($idp<=0){ header('location: index.php'); die(); }

$result = $xoopsDB->query("SELECT id_prd, nombre, codigo FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_prd='$idp'");
$num = $xoopsDB->getRowsNum($result);
if ($num<=0){ redirect_header('index.php', 2, _MC_RMCAT_PRODNOTFOUND); die(); }

$row = $xoopsDB->fetchArray($result);

$xoopsTpl->assign('producto', array('id'=>$row['id_prd'], 'nombre'=>$row['nombre'], 'codigo'=>$row['codigo']));

MakeNavSearch();
$xoopsTpl->assign('catalog_name',$xoopsModuleConfig['modtitle']);
$xoopsTpl->assign('lng_prodxpage', _MC_RMCAT_PRODXPAG);
$xoopsTpl->assign('lang_go', _MC_RMCAT_GO);
$xoopsTpl->assign('lng_goto', _MC_RMCAT_GOTO);
$xoopsTpl->assign('lng_requireinfo', _MC_PROD_REQUIRE);
$xoopsTpl->assign('lng_codigo',_MC_PROD_CODE);
$xoopsTpl->assign('lng_prodname',_MC_PROD_NAME);
$xoopsTpl->assign('lng_yourname', _MC_PROD_YOURNAME);
$xoopsTpl->assign('lng_yourmail', _MC_PROD_YOURMAIL);
$xoopsTpl->assign('lng_company', _MC_PROD_COMPANY);
$xoopsTpl->assign('lng_tel', _MC_PROD_TEL);
$xoopsTpl->assign('lng_comment', _MC_PROD_COMMENT);
$xoopsTpl->assign('lng_camposreq', _MC_PROD_CREQ);
$xoopsTpl->assign('rmsoft_footer', makeFoot());

include XOOPS_ROOT_PATH."/footer.php";

?>
