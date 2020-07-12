<?php
// $Id: categos.php 11 2006-12-13 03:00:49Z BitC3R0 $
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

$lng_location = 'categos';
include '../../../include/cp_header.php';
if (!file_exists("../language/".$xoopsConfig['language']."/admin.php") ) {
	include "../language/spanish/admin.php";
}

include_once XOOPS_ROOT_PATH.'/rmcommon/form.class.php';
include_once XOOPS_ROOT_PATH.'/rmcommon/images.class.php';

// Comprobamos los directorio
if (!is_dir(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/uploads/cats')){
	mkdir(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/uploads/cats');
}

function ShowCategos(){
	global $xoopsDB;
	
	list($num) = $xoopsDB->fetchRow($xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." ORDER BY nombre"));
		
	// Si no existe ninguna categoria entonces procedemos a crear una
	if ($num<=0){
		header("location: categos.php?op=new&err=1");
		die();
	}
	
	// Mostramos la tabla con las categorías
	include('functions.php');
	xoops_cp_header();
	ShowNav();
	echo "<table width='100%' class='outer' cellspacing='1'>\n
			<tr><th colspan='3'>"._AM_EXISTCATEGO."</th></tr>\n
			<tr class='head'><td align='center'>"._AM_NAME."</td>\n
			<td class='head' align='center'>"._AM_PRDS."</td>\n
			<td class='head' align='center'>"._AM_OPTIONS."</td></tr>";
	
	ChildCatego();
	
	echo "</table><br>";
	makeFoot();
	xoops_cp_footer();
}

function NewForm(){
	global $xoopsDB, $xoopsModuleConfig;
	include('functions.php');
	// Mostramos el formulario para crear una categoría
	xoops_cp_header();
	ShowNav();
	
	if (isset($_GET['err']) && $_GET['err']==1){
		echo "<div class='errorMsg'>"._AM_NOCATEGOS."</div><br>";
	}
	
	$form = new RMForm(_AM_NEWCATEGO, 'frmNew', 'categos.php', 'post', true);
	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new RMText(_AM_NAME, 'nombre', 50), true);
	$form->addElement(new RMEditor(_AM_DESC, 'desc', '100%', '300px', '', $xoopsModuleConfig['editor']), true);
	$ele = new RMFile(_AM_IMAGEFILE, 'imagen', 45);
	$ele->setDescription(_AM_IMAGEFILETIP);
	$form->addElement($ele);
	$ele = new RMText(_AM_IMAGEURL, 'imgurl', 50);
	$ele->setDescription(_AM_IMAGEURLTIP);
	$form->addElement($ele);
	$ele = new RMSelect(_AM_PARENT, 'parent', 0);
	$ele->addOption(0, _SELECT, 1);
	ChildCategoOption($ele);
	$form->addElement($ele);
	$form->addElement(new RMButton('sbt', _SUBMIT));
	$form->addElement(new RMHidden('op', 'save'));
	$form->display();
	
	xoops_cp_footer();
}

function ModifyForm(){
	global $xoopsDB, $xoopsModuleConfig;
	
	$idc = $_GET['idc'];
	if ($idc<=0){
		header('location: categos.php');
		die();
	}
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat='$idc'");
	$num = $xoopsDB->getRowsNum($result);
	
	if ($num<=0){
		redirect_header("categos.php", 1, _AM_CATEGONOEXIST);
		die();
	}
	
	include('functions.php');
	$row = $xoopsDB->fetchArray($result);
	xoops_cp_header();
	ShowNav();
	
	$form = new RMForm(_AM_EDITCATEGO, 'frmNew', 'categos.php', 'post', true);
	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new RMText(_AM_NAME, 'nombre', 50, 150, $row['nombre']), true);
	$form->addElement(new RMEditor(_AM_DESC, 'desc', '100%', '300px', $row['desc'], $xoopsModuleConfig['editor']), true);
	$ele = new RMFile(_AM_IMAGEFILE, 'imagen', 45);
	$ele->setDescription(_AM_IMAGEFILETIP);
	$form->addElement($ele);
	$ele = new RMText(_AM_IMAGEURL, 'imgurl', 50, 255, $row['imgtype']==1 ? $row['img'] : '');
	$ele->setDescription(_AM_IMAGEURLTIP);
	$form->addElement($ele);
	if ($row['img']!=''){
		$form->addElement(new RMLabel(_AM_CURRENTIMG, "<img src='".($row['imgtype']==1 ? $row['img'] : '../uploads/cats/' . $row['img'])."' />"));
		$ele = new RMCheck('');
		$ele->addOption(_AM_DELETE_CURRIMG, 'delimg', 1, 0);
		$form->addElement($ele);
	}
	$ele = new RMSelect(_AM_PARENT, 'parent', 0);
	$ele->addOption(0, _SELECT, $row['parent'] == 0 ? 1 : 0);
	ChildCategoOption($ele, 0, 0, $row['parent']);
	$form->addElement($ele);
	$ele = new RMButtonGroup('', '&nbsp;');
	$ele->addButton('sbt', _EDIT, 'submit');
	$ele->addButton('cancel', _CANCEL, 'button');
	$ele->setExtra('cancel', 'onclick="history.go(-1);"');
	$form->addElement($ele);
	$form->addElement(new RMHidden('op', 'savemod'));
	$form->addElement(new RMHidden('idc', $row['id_cat']));
	$form->display();
	
	xoops_cp_footer();
}

function SaveCatego(){
	global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
	
	$nombre = $_POST['nombre'];
	$desc = $_POST['desc'];
	$imgurl = $_POST['imgurl'];
	$parent = $_POST['parent'];
	$image_is_url = false;
	
	if ($nombre==""){
		redirect_header('categos.php?op=new', 1, _AM_ERRORNAME);
		die();
	}
	
	list($num) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("rmms_categos")." WHERE nombre='$nombre'"));
	
	if ($num>=1){
		redirect_header("categos.php?op=new", 1, _AM_CATEGOEXIST);
		die();
	}
	
	if (!is_uploaded_file($_FILES['imagen']['tmp_name'])){
		if ($imgurl==""){
			$image_is_url = false;
			$location = 0;
			$imagendb = "";
		} else {
			$image_is_url = true;
			$imagendb = $imgurl;
			$location = 1;
		}
	} else {
	
		$dir = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/uploads/cats/";
		$newfile = md5($_FILES['imagen']['name'].time()).substr($_FILES['imagen']['name'], strpos($_FILES['imagen']['name'], "."));
		move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $newfile); 
		$image = new RMImageControl($dir.$newfile, $dir.$newfile);
		if (!$image->resizeAndCrop($xoopsModuleConfig['imgcategow'], $xoopsModuleConfig['imgcategow'])){
			redirect_header('categos.php?op=new', 1, $image->errors());
			die();
		}
		$imagendb = $newfile;
		$location = 0;

	}
	
	$sql = "INSERT INTO ".$xoopsDB->prefix("rmms_categos")." (`nombre`,`desc`,`img`,`imgtype`,`parent`)
			VALUES ('$nombre','$desc','$imagendb','$location','$parent') ;";
	$xoopsDB->query($sql);
	redirect_header("categos.php", 1, _AM_SAVEOK);
}

function SaveModified(){
	global $xoopsDB, $xoopsModule;
	
	$nombre = $_POST['nombre'];
	$desc = $_POST['desc'];
	$imgurl = $_POST['imgurl'];
	$parent = $_POST['parent'];
	$idc = $_POST['idc'];
	$image_is_url = false;
	
	if ($idc<=0){
		redirect_header('categos.php', 1, _AM_CATEGONOEXIST);
		die();
	}
	if ($nombre==""){
		redirect_header('categos.php?op=mod&amp;idc=$idc', 1, _AM_ERRORNAME);
		die();
	}
	
	if (!stristr($_SERVER['HTTP_REFERER'], XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/categos.php?op=mod")){
		redirect_header('categos.php?op=mod&amp;idc=$idc',1,_AM_HACKATTEMPT);
		die();
	}
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat='$idc'");
	$num = $xoopsDB->getRowsNum($result);
	
	if ($num<=0){
		redirect_header('categos.php', 1, _AM_CATEGONOEXIST);
		die();
	}
	
	$row = $xoopsDB->fetchArray($result);
	$dir = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/uploads/cats/";
	if (!is_uploaded_file($_FILES['imagen']['tmp_name'])){
		if ($imgurl==""){
			$image_is_url = false;
			$imagendb = $row['img'];
			$location = $row['imgtype'];
		} else {
			$image_is_url = true;
			$imagendb = $imgurl;
			$location = 1;
			if ($row['imgtype']==0){
			unlink(XOOPS_ROOT_PATH.'/modules/".$xoopsModule->dirname()."/uploads/cats/'.$row['img']);
		}
		}
	} else {
		if ($row['imgtype']==0){
			unlink(XOOPS_ROOT_PATH.'/modules/".$xoopsModule->dirname()."/uploads/cats/'.$row['img']);
		}
		move_uploaded_file($_FILES['imagen']['tmp_name'], $dir.$_FILES['imagen']['name']); 
		$imagendb = $_FILES['imagen']['name'];
		$location = 0;
	}
	
	$sql = "UPDATE ".$xoopsDB->prefix("rmms_categos")." SET `nombre`='$nombre',`desc`='$desc',
			`img`='$imagendb',`imgtype`='$location',`parent`='$parent' WHERE
			id_cat='$idc'";
	$xoopsDB->query($sql);
	//echo $xoopsDB->error();
	redirect_header("categos.php",1,_AM_CATEGOMODIFIED);
	
}

function Delete(){
	global $xoopsDB, $xoopsModule;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	
	if (!stristr($_SERVER['HTTP_REFERER'], XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/categos.php")){
		redirect_header('categos.php?op=mod&amp;idc=$idc',1,_AM_HACKATTEMPT);
		die();
	}
	
	$idc = isset($_GET['idc']) ? $_GET['idc'] : (isset($_POST['idc']) ? $_POST['idc'] : 0);
	
	if ($idc<=0){
		redirect_header('categos.php', 1, _AM_CATEGONOEXIST);
		die();
	}
	
	if ($ok){
		list($img, $imgtype) = $xoopsDB->fetchRow($xoopsDB->query("SELECT img, imgtype FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat=$idc"));
		if ($imgtype==0){
			if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/uploads/cats/'.$img)){
				unlink(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/uploads/cats/'.$img);
			}
		}
		$xoopsDB->query("UPDATE ".$xoopsDB->prefix("rmms_categos")." SET parent='0' WHERE parent='$idc'");
		$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat='$idc'");
		redirect_header('categos.php', 1, _AM_DELETED);
	} else {
		xoops_cp_header();
		include('functions.php');
		ShowNav();
		echo "<table align='center' width='60%' class='outer' cellspacing='1'>\n
				<tr><form name='frmDel' method='post' action='categos.php'>\n
				<td class='even' align='center'><br><br>"._AM_CONFIRMDELETE."<br><br>\n
				<input type='button' name='cancel' value='"._AM_CANCEL."' onclick='javascript: history.go(-1)'>\n
				<input type='submit' name='sbt' value='"._AM_OK."'>\n
				<input type='hidden' name='op' value='del'>\n
				<input type='hidden' name='ok' value='1'>\n
				<input type='hidden' name='idc' value='$idc'>\n
				<br><br></td></form></tr></table>";
		xoops_cp_footer();
	}
}

function View(){
	global $xoopsDB, $xoopsModule;
	
	$idc = isset($_GET['idc']) ? $_GET['id'] : (isset($_POST['idc']) ? $_POST['idc'] : 0);
	if ($idc<=0){
		redirect_header('categos.php', 1, _AM_HACKATTEMPT);
		die();
	}
	
	$row = $xoopsDB->fetchArray($xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat='$idc'"));
	include('functions.php');
	xoops_cp_header();
	ShowNav();
	// Mostramos la tabla con la información de la categoría
	echo "<table width='100%' class='outer' cellspacing='1'>\n
		  <tr><th colspan='2'>$row[nombre]</th></tr>\n
		  <tr><td class='even' align='center' width='20%'>\n";
	
	if ($row['imgtype']){
		echo "<img src='$row[img]' border='1'>";
	} else {
		echo "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/uploads/cats/$row[img]' border='1'>";
	}
	
	echo "</td><td class='odd' align='left' valign='top'>\n
		  $row[desc]<br><br>"._AM_PARENT.": ";
		if ($row['parent']==0){
			echo "&nbsp;";
		} else {
			echo "<a href='categos.php?op=view&amp;idc=$row[parent]'>".CategoName($row['parent'])."</a>";
		}
	echo "</td></tr></table><br>";
	
	// Mostramos la tabla con los productos que pertenecen a esta categoria
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_relations")." WHERE id_cat='$idc'");
	
	echo "<table width='100%' class='outer' cellspacing='1'>\n
		  <tr><th align='left' colspan='2'>"._AM_PRDS."</th></tr>\n";
		$class = 'even';
		while($row=$xoopsDB->fetchArray($result)){
			echo "<tr><td class='$class' align='left'>\n<img src='../images/add.gif' align='absmiddle' border='0'>
				  <a href='productos.php?op=view&amp;idp=".$row['id_prd']."'>".ProdName($row['id_prd'], 'nombre')."</a></td>\n
				  <td class='$class' align='center'>\n
				  <a href='productos.php?op=mod&amp;idp=".$row['id_prd']."'>"._AM_MODIFY."</a> |\n
				  <a href='productos.php?op=delete&amp;idp=".$row['id_prd']."'>"._AM_DELETE."</a></td></tr>";
		} 
	
	echo "</table><br>";
	
	ShowNav();
	
	xoops_cp_footer();
}

// Seleccionamos el caso segun convenga
$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : '');

switch ($op){
	case "new":
		NewForm();
		break;
	case "save":
		SaveCatego();
		break;
	case "mod":
		ModifyForm();
		break;
	case "savemod":
		SaveModified();
		break;
	case "del":
		Delete();
		break;
	case "view":
		View();
		break;
	default:
		ShowCategos();
		break;
}
?>


