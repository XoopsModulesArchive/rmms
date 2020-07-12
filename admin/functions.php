<?php
// $Id: functions.php 13 2006-12-14 18:47:03Z BitC3R0 $
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

function ShowNav(){
	echo "<table width='100%'  border='0' cellspacing='1' cellpadding='0' class='outer'>
  			<tr align='center' class='odd'>
   			 <td width='25%'><img src='../images/add.gif' border='0' align='absmiddle' /> <a href='productos.php?op=new'>"._AM_NEWPRD."</a> </td>
    		 <td width='25%'><img src='../images/prods.gif' border='0' align='absmiddle' /> <a href='productos.php'>"._AM_EXISTPRD."</a> </td>
    		 <td width='25%'><img src='../images/catego.gif' border='0' align='absmiddle' /> <a href='categos.php?op=new'>"._AM_NEWCATEGO."</a> </td>
    		 <td width='25%'><img src='../images/categos.gif' border='0' align='absmiddle' /> <a href='categos.php'>"._AM_EXISTCATEGO."</a> </td>
  			</tr>
		</table><br>";
}

function makeFoot(){
	echo "<div style='font-size: 10px; padding: 4px; text-align: center;'>
			CopyRight &copy; 2005 - 2006.
			<strong><a href='http://www.redmexico.com.mx'>Red México Soft</a></strong>.
			Powered by <strong><a href='http://www.xoops-mexico.net'>RMSOFT MiniShop 1.0</a></strong>.
		</div>";
}

function ChildCatego($parent = 0, $tabs = 0, $class = 'even'){
	global $xoopsDB;
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('rmms_categos')." WHERE parent=$parent");
	while ($row=$xoopsDB->fetchArray($result)){
		echo "<tr><td class='$class' align='left'>".str_repeat('&nbsp;',$tabs)."<a href='categos.php?op=view&amp;idc=$row[id_cat]'>$row[nombre]</a></td>\n
				<td class='$class' align='center'><strong>".ProdsNumber($row['id_cat'])."</strong></td>\n
				<td class='$class' align='center'><a href='categos.php?op=mod&amp;idc=$row[id_cat]'>"._AM_MODIFY."</a> | <a href='categos.php?op=del&amp;idc=$row[id_cat]'>"._AM_DELETE."</a></td></tr>";
		ChildCatego($row['id_cat'], $tabs + 2, $class='odd');
		if ($tabs==0){ $class = 'even'; }
	}
}

function ChildCategoOption(RMSelect &$ele, $parent = 0, $tabs = 0, $select = 0){
	global $xoopsDB;
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('rmms_categos')." WHERE parent=$parent");
	while ($row=$xoopsDB->fetchArray($result)){
		$ele->addOption($row['id_cat'], str_repeat('-',$tabs)." $row[nombre]", $row['id_cat']==$select ? 1 : 0);
		ChildCategoOption($ele, $row['id_cat'], $tabs + 2, $select);
	}
}

function ChildCategoParent(&$ele, $parent = 0, $tabs = 0, $class = 'even', $p = 0){
	global $xoopsDB;
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('rmms_categos')." WHERE parent=$parent");
	while ($row=$xoopsDB->fetchArray($result)){
		if ($p==$row['id_cat']){
			$ele->addOption($row['id_cat'], str_repeat('-',$tabs)." $row[nombre]", 1);
		} else {
			$ele->addOption($row['id_cat'], $row['nombre']);
		}
		ChildCategoParent($ele, $row['id_cat'], $tabs + 2, $class='odd',$p);
		if ($tabs==0){ $class = 'even'; }
	}
}

function ProdsNumber($idc){
	global $xoopsDB;
	
	if ($idc<=0){ return; }
	
	list($num) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_cat='$idc'"));
	return $num;
}

function CategoName($idc){
	global $xoopsDB;
	
	if ($idc<=0){ return; }
	
	$result = $xoopsDB->query("SELECT nombre FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat='$idc'");
	list($nombre) = $xoopsDB->fetchRow($result);
	return $nombre;
}

function ProdName($idp, $col = 'nombre'){
	global $xoopsDB;
	if ($idp<=0){ return; }
	
	$result = $xoopsDB->query("SELECT ".$col." FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_prd='$idp'");
	list($nombre) = $xoopsDB->fetchRow($result);
	return $nombre;
}

function categoOwner($idc){
	global $xoopsDB;
	
	$result = $xoopsDB->query("SELECT nombre FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat = '$idc'");
	
	if ($xoopsDB->getRowsNum($result)<=0){ return; }
	$row = $xoopsDB->fetchArray($result);
	return $row['nombre'];
}

/**
 * Cadena aleatoria
 */
function randomWord($size=8){
	$chars = "abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$ret = '';
	$len = strlen($chars);
	for($i=1;$i<=$size;$i++){
		mt_srand((double) microtime() * 1000000);
		$sel = mt_rand(0, $len);
		$ret .= substr($chars, $sel, 1);
	}
	return $ret;
}

function imageResize($source,$target,$width, $height){
      //calculamos la altura proporcional
      $datos = getimagesize($source);
      if ($datos[0] >= $datos[1]){
	  	$ratio = ($datos[0] / $width);
		$height = round($datos[1] / $ratio);
	  } else {
	  	$ratio = ($datos[1] / $height);
		$width = round($datos[0] / $ratio);
	  }
	  $type = strrchr($target, ".");
	  $type = strtolower($type);
	  
	  if ($width >= $datos[0] && $height >= $datos[1]){
	  	if ($source != $target){
			copy($source, $target);
			return;
		}
	  }
      // esta será la nueva imagen reescalada
      $thumb = imagecreatetruecolor($width,$height);

	  switch ($type){
	  	case '.jpg':
			$img = imagecreatefromjpeg($source);
			break;
		case '.gif':
			$img = imagecreatefromgif($source);
			break;
		case '.png':
			$img = imagecreatefrompng($source);
			break;
	  }

      // con esta función la reescalamos
      imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $width, $height, $datos[0], $datos[1]);
      // la guardamos con el nombre y en el lugar que nos interesa.
	  switch ($type){
	  	case '.jpg':
      		imagejpeg($thumb,$target,80);
			break;
		case '.gif':
			imagegif($thumb,$target,80);
			break;
		case '.png':
			imagepng($thumb,$target);
			break;
	  }
	  
}
?>
