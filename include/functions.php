<?php
// $Id: functions.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

function catalog_ChildCatego($parent = 0, $tabs = 0, $class = 'even'){
	global $xoopsDB, $xoopsTpl;
	
	$result = $xoopsDB->query("SELECT id_cat, nombre FROM ".$xoopsDB->prefix('rmms_categos')." WHERE parent=$parent");
	while ($row=$xoopsDB->fetchArray($result)){
		$xoopsTpl->append('jumpnav', array('id'=>$row['id_cat'],'nombre'=>str_repeat('-',$tabs).$row['nombre']));
		catalog_ChildCatego($row['id_cat'], $tabs + 2, $class='odd');
		if ($tabs==0){ $class = 'even'; }
	}
}

function MakeNavSearch(){
	global $xoopsDB, $xoopsTpl;
	catalog_ChildCatego();
	$xoopsTpl->assign('lng_searchprods', _MC_SEARCH_PROD);
	$xoopsTpl->assign('lng_allcategos', _MC_ALL_CATEGOS);
	$xoopsTpl->assign('lng_search', _MC_SEARCH);
}

function catalog_data_product($row){
	global $xoopsDB, $myts, $xoopsModuleConfig, $xoopsModule;
	$rtn = array();
	$rtn['id'] = $row['id_prd'];
	$rtn['nombre'] = $row['nombre'];
	$precio = number_format($row['precio'], $xoopsModuleConfig['decimales'], $xoopsModuleConfig['decsep'], $xoopsModuleConfig['milsep']);
	$precio = sprintf($xoopsModuleConfig['curformat'], $precio);
	$rtn['precio'] = $precio;
	$rtn['codigo'] = $row['codigo'];
	$rtn['img'] = XOOPS_URL."/modules/".$xoopsModule->dirname()."/uploads/ths/".$row['img'];
	$rtn['desc'] = $myts->makeTareaData4Show($row['shortdesc']);
	return $rtn;
}

function rmcat_items_xpagina(){
	global $xoopsModuleConfig, $xoopsTpl;
	$itemsxpag = $_SESSION['itemsxpag'];
	/* Lista de items por páginas */
	if ($itemsxpag <= 0){
		$itemsxpag = $xoopsModuleConfig['cols'] * 3;
	}

	$items = $xoopsModuleConfig['cols'];
	$i = 1;
	for ($i==1;$i<=10;$i++){
		if (($items * $i) == $itemsxpag){
			$xoopsTpl->append('itemsxpag', array('num'=>$items * $i, 'selected'=>'selected'));
		} else {
			$xoopsTpl->append('itemsxpag', array('num'=>$items * $i, 'selected'=>''));
		}	
	}
	return;
}

function makeFoot(){
	$ret = "<div style='font-size: 10px; padding: 4px; text-align: center;'>
			CopyRight &copy; 2005 - 2006.
			<strong><a href='http://www.redmexico.com.mx'>Red México Soft</a></strong>.
			Powered by <strong><a href='http://www.xoops-mexico.net'>RMSOFT MiniShop 1.0</a></strong>.<br /><br />
			<script language='JavaScript' type='text/javascript' src='http://ads.xoops-mexico.net/adx.js'></script>
			<script language='JavaScript' type='text/javascript'>
			<!--
			   if (!document.phpAds_used) document.phpAds_used = ',';
			   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
			   
			   document.write (\"<\" + \"script language='JavaScript' type='text/javascript' src='\");
			   document.write (\"http://ads.xoops-mexico.net/adjs.php?n=\" + phpAds_random);
			   document.write (\"&amp;what=zone:18&amp;block=1&amp;blockcampaign=1\");
			   document.write (\"&amp;exclude=\" + document.phpAds_used);
			   if (document.referrer)
				  document.write (\"&amp;referer=\" + escape(document.referrer));
			   document.write (\"'><\" + \"/script>\");
			//-->
			</script><noscript><a href='http://ads.xoops-mexico.net/adclick.php?n=afb1a97e' target='_blank'><img src='http://ads.xoops-mexico.net/adview.php?what=zone:18&amp;n=afb1a97e' border='0' alt=''></a></noscript>
		</div>";
	return $ret;
}

function rmmsGetImages($id){
	$db =& Database::getInstance();
	
	$result = $db->query("SELECT * FROM ".$db->prefix("rmms_images")." WHERE idp='$id'");
	$rtn = array();
	while ($row = $db->fetchArray($result)){
		$ret = array();
		$ret['th'] = 'uploads/ths/'.$row['img'];
		$ret['img'] = 'uploads/'.$row['img'];
		$ret['titulo'] = $row['titulo'];
		$rtn[] = $ret;
	}
	
	return $rtn;
}
?>
