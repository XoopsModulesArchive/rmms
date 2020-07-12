<?php
// $Id: productos.php 13 2006-12-14 18:47:03Z BitC3R0 $
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

$lng_location = 'productos';
include '../../../include/cp_header.php';
if (!file_exists("../language/".$xoopsConfig['language']."/admin.php") ) {
	include "../language/spanish/admin.php";
}

$myts =& MyTextSanitizer::getInstance();
include_once XOOPS_ROOT_PATH.'/rmcommon/form.class.php';
include_once('functions.php');

switch ($xoopsModuleConfig['sizeunit']){
	case 1:
		$maxsize = $xoopsModuleConfig['maxfilesize'];
		break;
	case 2:
		$maxsize = $xoopsModuleConfig['maxfilesize'] * 1024;
		break;
	case 3:
		$maxsize = $xoopsModuleConfig['maxfilesize'] * 1024 * 1024;
		break;
}

function ShowProds(){
	global $xoopsDB;
	
	$limit = 20;
	
	$pag = isset($_GET['pag']) ? $_GET['pag'] : 0;
	$keyw = isset($_GET['keyw']) ? $_GET['keyw'] : '';
	if ($pag > 0){ $pag -= 1; }
	$start = $pag * $limit;
	
	$order = isset($_GET['order']) ? $_GET['order'] : '';
	if ($order == ''){ $order = 'nombre'; }
	
	list($num) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("rmms_productos")));

	if ($num <= 0){
		redirect_header('productos.php?op=new', 1, _AM_EMPTY);
		die();
	}
	
	$rtotal = $num; // Numero total de resultados
	$tpages = (int)($num / $limit);
	
	if (($num % $limit) > 0){ $tpages++; }
	
	$pactual = $pag + 1;
	
	if ($pactual>$tpages){
		$rest = $pactual - $tpages;
		$pactual = $pactual - $rest + 1;
		$start = ($pactual - 1) * $limit;
	}
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_productos")." ORDER BY $order LIMIT $start,$limit");
	
	xoops_cp_header();
	ShowNav();
	echo "<table width='100%' cellpadding='4' cellspacing='0' border='0'>\n
			<tr>
			<td align='left'>"._AM_PAGE;
	for ($i=1;$i<=$tpages;$i++){
		if ($i==1){
			echo "<a href='?pag=$i&amp;order=$order'>$i</a>";
		} else {
			echo " &raquo; <a href='?pag=$i&amp;order=$order'>$i</a>";
		}
    }
	echo 	"</td>
			<td align='right'>\n
			<a href='productos.php?order=nombre&amp;pag=$pactual'>"._AM_ORDERNAME."</a>&nbsp;
			| &nbsp;<a href='productos.php?order=id_prd&amp;pag=$pactual'>"._AM_ORDERLAST."</a>\n
			</td></tr></table>";
	echo "<table width='100%' cellspacing='1' class='outer'>\n
			<tr><th colspan='3'>"._AM_EXISTPRD."</th></tr>\n
			<tr><td class='head' align='center'>"._AM_PRDNAME."</td>\n
			<td class='head' align='center'>"._AM_HCATEGO."</td>\n
			<td class='head' align='center'>"._AM_OPTIONS."</td></tr>";
	$class = 'even';
	while ($row=$xoopsDB->fetchArray($result)){
		echo "<tr class=$class><td align='left'><a href='productos.php?op=view&amp;idp=".$row['id_prd']."'>$row[nombre]</a><br /><span style='font-size: 9px;'>\n
			  $row[shortdesc]</span></td>\n
			  <td align='center'>".categoOwner($row['id_cat'])."</td>\n
			  <td align='center'>
			  <a href='productos.php?op=mod&amp;idp=".$row['id_prd']."'>"._AM_MODIFY."</a> |\n
			  <a href='productos.php?op=delete&amp;idp=".$row['id_prd']."'>"._AM_DELETE."</a> |
			  <a href='?op=images&amp;idp=".$row['id_prd']."'>"._AM_IMAGES."</a></td></tr>";
		if ($class=='even'){ $class='odd'; } else { $class='even'; }
	}
	
	echo "</table><br />";
	
	makeFoot();
	xoops_cp_footer();
}

function NewForm(){
	global $xoopsDB, $xoopsModuleConfig, $maxsize;
	
	$sql = "SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE `parent`='0' ORDER BY id_cat";
	$result = $xoopsDB->query($sql);
	$num = $xoopsDB->getRowsNum($result);
	
	if ($num<=0){
		redirect_header("categos.php?op=new", 1, _AM_NOCATEGOS);
		die();
	}
	
	xoops_cp_header();
	
	ShowNav();
	
	$form = new RMForm(_AM_NEWPRD, 'frmNew', 'productos.php');
	$form->setExtra("enctype='multipart/form-data'");
	$form->addElement(new RMText(_AM_NAME, 'nombre', 50, 255), true);
	$form->addElement(new RMText(_AM_PRDCODE, 'codigo', 20, 50), true);
	$form->addElement(new RMText(_AM_PRDQUANTITY, 'cantidad', 10, 20), true, 'Num');
	$form->addElement(new RMText(_AM_PRDSIZE, 'medidas', 20, 100), true);
	$form->addElement(new RMText(_AM_PRDWEIGHT, 'peso', 20, 100), true, 'Num');
	$form->addElement(new RMText(_AM_PRDMINIMO, 'minimo', 10, 50), true, 'Num');
	$form->addElement(new RMText(_AM_PRDPRECIO, 'precio', 20, 50), true, 'Num');
	$form->addElement(new RMText(_AM_SHORTDESC, 'shortdesc', 50, 255), true);
	$ele = new RMSelect(_AM_CATEGO, 'catego', 0);
	$ele->addOption(0, _AM_CATEGO_SELECT, 1);
	while ($row = $xoopsDB->fetchArray($result)){
		$ele->addOption($row['id_cat'], $row['nombre']);
		ChildCategoParent($ele, $row['id_cat'], 2);
	}
	$form->addElement($ele, true, "Select:0");
	$form->addElement(new RMEditor(_AM_LONGDESC, 'longdesc', '100%','300px','',$xoopsModuleConfig['editor']), true);
	$form->addElement(new RMFile(_AM_PRDBIG, 'big', 45));
	$form->addElement(new RMHidden('MAX_FILE_SIZE', $maxsize));
	$form->addElement(new RMYesNo(_AM_SHOWINBLOCK, 'block', 1));
	$form->addElement(new RMHidden('op', 'save'));
	$form->addElement(new RMButton('submit', _SUBMIT));
	$form->display();
	
	makeFoot();
	xoops_cp_footer();
}

function ModForm(){
	global $xoopsDB, $myts, $xoopsModuleConfig, $maxsize;
	
	$idp = $_GET['idp'];
	
	if ($idp == ""){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_prd='$idp'");
	$num = $xoopsDB->getRowsNum($result);
	
	if ($num <= 0){
		redirect_header('productos.php', 1, _AM_NOEXIST);
		die();
	}
	
	$row = $xoopsDB->fetchArray($result);
	
	xoops_cp_header();
	ShowNav();
	
	$form = new RMForm(_AM_MODPRD, 'frmNew', 'productos.php');
	$form->setExtra("enctype='multipart/form-data'");
	$form->addElement(new RMText(_AM_NAME, 'nombre', 50, 255, $row['nombre']), true);
	$form->addElement(new RMText(_AM_PRDCODE, 'codigo', 20, 50, $row['codigo']), true);
	$form->addElement(new RMText(_AM_PRDQUANTITY, 'cantidad', 10, 20, $row['cantidad']), true, 'Num');
	$form->addElement(new RMText(_AM_PRDSIZE, 'medidas', 20, 100, $row['medidas']), true);
	$form->addElement(new RMText(_AM_PRDWEIGHT, 'peso', 20, 100, $row['peso']), true, 'Num');
	$form->addElement(new RMText(_AM_PRDMINIMO, 'minimo', 10, 50, $row['minimo']), true, 'Num');
	$form->addElement(new RMText(_AM_PRDPRECIO, 'precio', 20, 50, $row['precio']), true, 'Num');
	$form->addElement(new RMText(_AM_SHORTDESC, 'shortdesc', 50, 255, $row['shortdesc']), true);
	$ele = new RMSelect(_AM_CATEGO, 'catego', 0);
	$ele->addOption(0, _AM_CATEGO_SELECT, 1);
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." ORDER BY nombre");
	while ($rw = $xoopsDB->fetchArray($result)){
		$ele->addOption($rw['id_cat'], $rw['nombre'], $row['id_cat']==$rw['id_cat'] ? 1 : 0);
		ChildCategoParent($ele, $row['id_cat'], 2, '', $row['id_cat']);
	}
	$form->addElement($ele, true, "Select:0");
	$form->addElement(new RMEditor(_AM_LONGDESC, 'longdesc', '100%','300px',$row['longdesc'],$xoopsModuleConfig['editor']), true);
	$form->addElement(new RMFile(_AM_PRDBIG, 'big', 45));
	$form->addElement(new RMHidden('MAX_FILE_SIZE', $maxsize));
	$form->addElement(new RMLabel(_AM_CURRENTIMG, $row['imgtype']==0 ? "<img src='../uploads/ths/$row[img]' alt='$row[img]' />" : "<img src='$row[img]' alt='$row[img]' />"));
	$form->addElement(new RMYesNo(_AM_SHOWINBLOCK, 'block', $row['inblock']));
	$form->addElement(new RMHidden('op', 'savemod'));
	$form->addElement(new RMHidden('idp', $idp));
	$form->addElement(new RMButton('submit', _SUBMIT));
	$form->display();
	
	makeFoot();
	xoops_cp_footer();
}

function SaveProd(){
	global $xoopsDB, $myts, $xoopsModuleConfig, $xoopsModule;
	$mc =& $xoopsModuleConfig;

	$logo_is_url = false;
	$location = 0;
	
	foreach($_POST as $k=>$v){
		$$k = $v;
	}
	
	if ($nombre==""){ redirect_header('productos.php?op=new', 1, _AM_ERRNOMBRE); die(); }
	if ($codigo==""){ redirect_header('productos.php?op=new', 1, _AM_ERRCODE); die(); }
	if ($cantidad<=0){	redirect_header('productos.php?op=new', 1, _AM_ERRQUANT);	die(); }
	if ($medidas==""){ redirect_header('productos.php?op=new', 1, _AM_ERRSIZE); die(); }
	if ($peso==""){ redirect_header('productos.php?op=new', 1, _AM_ERRPESO); die(); }
	if ($minimo<=0){ redirect_header('productos.php?op=new', 1, _AM_ERRMIN); die(); }
	if ($precio<=0){ redirect_header('productos.php?op=new', 1, _AM_ERRPRICE); die(); }
	if ($shortdesc==""){ redirect_header('productos.php?op=new', 1, _AM_ERRSHORT); die(); }
	if ($longdesc==""){ redirect_header('productos.php?op=new', 1, _AM_ERRLONG); die(); }
	if ($catego<=0){ redirect_header('productos.php?op=new', 1, _AM_ERRCATEGO); die();  }
	
	list($num) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("rmms_productos")." WHERE codigo='$codigo'"));
	
	if ($num >= 1){
		redirect_header('productos.php?op=new', 1, _AM_PRDEXIST);
		die();
	}

	$dir_uploads = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/uploads/";
	if (is_uploaded_file($_FILES['big']['tmp_name'])){
		$filedata = explode(".", $_FILES['big']['name']);
		do{
			$newname = $filedata[0] . '_' . randomWord(8) . '.' . $filedata[1];
		} while(file_exists($dir_uploads . $newname));
		move_uploaded_file($_FILES['big']['tmp_name'], $dir_uploads.$newname);
		
		imageResize($dir_uploads.$newname,$dir_uploads.$newname,$mc['imgwidth'], $mc['imgheight']);
		imageResize($dir_uploads.$newname,$dir_uploads.'ths/'.$newname,$mc['thwidth'], $mc['thheight']);
	}
	
	$sql = "INSERT INTO ".$xoopsDB->prefix("rmms_productos")." (`nombre`, `shortdesc`, 
			`longdesc`, `codigo`, `cantidad`, `medidas`, `peso`, `minimo`, `precio`, 
			`img`, `inblock`,`id_cat`) VALUES ('$nombre','$shortdesc','$longdesc','$codigo',
			'$cantidad','$medidas','$peso','$minimo','$precio','$newname','$block','$catego') ;";
	$xoopsDB->query($sql);
	redirect_header('productos.php', 1, _AM_PRDCREATED);
	
}

function SaveModProd(){
	global $xoopsDB, $myts, $xoopsModule, $xoopsModuleConfig;
	$mc =& $xoopsModuleConfig;
	foreach($_POST as $k=>$v){
		$$k = $v;
	}
	
	if ($idp<=0){ redirect_header('productos.php', 1, _AM_HACKATTEMPT); die(); }
	if ($nombre==""){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRNOMBRE); die(); }
	if ($codigo==""){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRCODE); die(); }
	if ($cantidad<=0){	redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRQUANT);	die(); }
	if ($medidas==""){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRSIZE); die(); }
	if ($peso==""){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRPESO); die(); }
	if ($minimo<=0){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRMIN); die(); }
	if ($precio<=0){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRPRICE); die(); }
	if ($shortdesc==""){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRSHORT); die(); }
	if ($longdesc==""){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRLONG); die(); }
	if ($catego<=0){ redirect_header('productos.php?op=mod&amp;id='.$idp, 1, _AM_ERRCATEGO); die(); }
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_prd='$idp'");
	$num = $xoopsDB->getRowsNum($result);
	if ($num <= 0){
		redirect_header('productos.php', 1, _AM_NOEXIST);
		die();
	}
	$row = $xoopsDB->fetchArray($result);

	$dir_uploads = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/uploads/";
	if (is_uploaded_file($_FILES['big']['tmp_name'])){
		if (file_exists($dir_uploads . $row['img'])) unlink($dir_uploads . $row['img']);
		if (file_exists($dir_uploads . 'ths/' . $row['img'])) unlink($dir_uploads . 'ths/'. $row['img']);
		$filedata = explode(".", $_FILES['big']['name']);
		do{
			$newname = $filedata[0] . '_' . randomWord(8) . '.' . $filedata[1];
		} while(file_exists($dir_uploads . $newname));
		move_uploaded_file($_FILES['big']['tmp_name'], $dir_uploads.$newname);
		imageResize($dir_uploads.$newname,$dir_uploads.$newname,$mc['imgwidth'], $mc['imgheight']);
		imageResize($dir_uploads.$newname,$dir_uploads.'ths/'.$newname,$mc['thwidth'], $mc['thheight']);
	} else {
		$newname = $row['img'];
	}
	$sql = "UPDATE ".$xoopsDB->prefix("rmms_productos")." SET `nombre`='$nombre',
			`codigo`='$codigo',`cantidad`='$cantidad',`peso`='$peso',`medidas`='$medidas',
			`peso`='$peso',`precio`='$precio',`shortdesc`='$shortdesc',
			`longdesc`='$longdesc',`img`='$newname',
			`inblock`='$blok', `id_cat`='$catego' WHERE id_prd='$idp' ;";
	$xoopsDB->query($sql);
	//echo $xoopsDB->error();
	redirect_header('productos.php', 1, _AM_PRDMODIFIED);
	
}

function DeleteProd(){
	global $xoopsDB, $xoopsModule;
	
	if (!stristr($_SERVER['HTTP_REFERER'], XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/")){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$idp = isset($_GET['idp']) ? $_GET['idp'] : (isset($_POST['idp']) ? $_POST['idp'] : 0);
	
	if ($idp<=0){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	if ($ok){
		$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_prd='$idp'");
		redirect_header('productos.php', 1, _AM_DELETED);
	} else {
		xoops_cp_header();
		
		ShowNav();
		echo "<table align='center' width='60%' class='outer' cellspacing='1'>\n
				<tr><form name='frmDel' method='post' action='productos.php'>\n
				<td class='even' align='center'><br /><br />"._AM_CONFIRMDELETE."<br /><br />\n
				<input type='button' name='cancel' value='"._AM_CANCEL."' onclick='javascript: history.go(-1)'>\n
				<input type='submit' name='sbt' value='"._AM_OK."'>\n
				<input type='hidden' name='op' value='delete'>\n
				<input type='hidden' name='ok' value='1'>\n
				<input type='hidden' name='idp' value='$idp'>\n
				<br /><br /></td></form></tr></table>";
		xoops_cp_footer();
	}

}

function ViewProd(){
	global $xoopsDB, $myts, $xoopsModule;
	
	if (!stristr($_SERVER['HTTP_REFERER'], XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/")){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	$idp = $_GET['idp'];
	if ($idp<=0){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_productos")." WHERE id_prd='$idp'");
	$num = $xoopsDB->getRowsNum($result);
	if ($num<=0){
		redirect('productos.php', 1, _AM_NOEXIST);
		die();
	}
	
	$row = $xoopsDB->fetchArray($result);
	xoops_cp_header();
	ShowNav();
	echo "<table width='100%' class='outer' cellspacing='1'>\n
			<tr><th colspan='2'>"._AM_PRDDATA."</th></tr>\n
			<tr><td class='even' valign='top' align='center' width='30%'>\n";
			echo "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/uploads/".$row['img']."' border='1'>";
	echo "  </td><td valign='top' class='odd' align='left'>\n
			<input type='button' name='mod' value='"._AM_MODIFY."' onClick=\"window.location = 'productos.php?op=mod&idp=".$idp."'\">\n
			<input type='button' name='del' value='"._AM_DELETE."' onClick=\"window.location = 'productos.php?op=delete&idp=".$idp."'\"><br /><br />\n
			"._AM_NAME." <strong>$row[nombre]</strong><br />\n
			"._AM_PRDCODE." <strong>$row[codigo]</strong><br />\n
			"._AM_PRDQUANTITY." <strong>$row[cantidad]</strong><br />\n
			"._AM_PRDSIZE." <strong>$row[medidas]</strong><br />\n
			"._AM_PRDWEIGHT." <strong>$row[peso]</strong><br />\n
			"._AM_PRDMINIMO." <strong>$row[minimo]</strong><br />\n
			"._AM_PRDPRECIO." <strong>$row[precio]</strong><br /><br />\n
			"._AM_CATASSIGN."<br />";
			
			$rstcat = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("rmms_categos")." WHERE id_cat='$row[id_cat]'");
			while ($rwc=$xoopsDB->fetchArray($rstcat)){
				echo "<a href='categos.php?op=view&amp;idc=$rwc[id_cat]'>$rwc[nombre]</a> &nbsp;";
			}
			
	echo    "<br /><br />"._AM_LONGDESC."<br /><br />".$myts->makeTareaData4Show($row['longdesc'])."<br /><br />
			<input type='button' name='mod' value='"._AM_MODIFY."' onClick=\"window.location = 'productos.php?op=mod&idp=".$idp."'\">\n
			<input type='button' name='mod' value='"._AM_DELETE."' onClick=\"window.location = 'productos.php?op=delete&idp=".$idp."'\">\n
			</td></tr></table><br />";
	makeFoot();
	xoops_cp_footer();
}

/**
 * Muestra las imágenes existentes del producto
 */
function showImages(){
	global $xoopsModule, $myts, $maxsize;
	
	$db =& Database::getInstance();
	$idp = isset($_GET['idp']) ? $_GET['idp'] : 0;
	if ($idp<=0){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	$result = $db->query("SELECT nombre FROM ".$db->prefix("rmms_productos")." WHERE id_prd='$idp'");
	if ($db->getRowsNum($result)<=0){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	list($nombre) = $db->fetchRow($result);
	
	xoops_cp_header();
	echo "<script type='text/javascript'>
			<!--
				function decision(message, url){
					if(confirm('"._AM_CONFIRMDEL."' + ' \"' + message + '\"')) location.href = url;
				}
			-->
		   </script>";
	ShowNav();
	
	echo "<table cellspacing='3' cellpadding='0' border='0' width='100%'>
			<tr><td valign='top'>
			<table class='outer' cellspacing='1' width='100%'>
				<tr><th colspan='4'>".sprintf(_AM_MAGESFOR, $nombre)."</th></tr>
				<tr class='head' align='center'><td>"._AM_PIMAGE."</td>
				<td>"._AM_ITITLE."</td>
				<td>"._OPTIONS."</td></tr>";
		$result = $db->query("SELECT * FROM ".$db->prefix("rmms_images")." WHERE idp='$idp'");
		$class = 'odd';
	while ($row = $db->fetchArray($result)){
		$class = $class=='odd' ? 'even' : 'odd';
		echo "<tr class='$class'><td align='center'><img src='../uploads/ths/$row[img]' alt='' /></td>
				<td align='left'>$row[titulo]</td><td align='center'>
				<a href=\"javascript:decision('$row[titulo]', '?op=delimage&amp;idp=$idp&amp;idi=$row[id_img]')\">"._DELETE."</a>
				</td></tr>";
	}
	
	echo "	</table>
			</td><td valign='top'>";
	
	$form = new RMForm(sprintf(_AM_NEWIMAGE, $nombre), 'frmNew', 'productos.php');
	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new RMFile(_AM_PIMAGE, 'img', 45), true);
	$form->addElement(new RMHidden('MAX_FILE_SIZE', $maxsize));
	$form->addElement(new RMText(_AM_ITITLE, 'titulo', 50, 255), true);
	$form->addElement(new RMHidden('op', 'saveimg'));
	$form->addElement(new RMHidden('idp', $idp));
	$form->addElement(new RMButton('sbt', _SUBMIT));
	$form->display();
	
	echo "	</td></tr>
		 </table>";
	
	xoops_cp_footer();
	
}

function saveImage(){
	global $maxsize, $xoopsModuleConfig, $xoopsConfig;
	$mc =& $xoopsModuleConfig;
	$db =& Database::getInstance();
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($idp<=0){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	include_once XOOPS_ROOT_PATH.'/rmcommon/images.class.php';
	$up = new RMUploader();
	$mime = $up->listMimeTypes();
	$dir = XOOPS_ROOT_PATH.'/modules/rmms/uploads';
	$up->prepareUpload($dir, array($mime['gif'],$mime['jpg'], $mime['png']), $maxsize);
	if ($up->fetchMedia('img')){
		if (!$up->upload()){
			redirect_header('?op=images&amp;idp='.$idp, _AM_DB_ERROR ."<br />".$up->getErrors());
		} else {
			$archivo = $up->getSavedFileName();
			$ruta = $up->getSavedDestination();
		}
	} else {
		redirect_header('?op=images&amp;idp='.$idp, 2, _AM_DB_ERROR ."<br />".$up->getErrors());
	}
	
	$imgcon = new RMImageControl($ruta, $dir . '/ths/' . $archivo);
	$imgcon->resizeAndCrop($mc['thwidth'],$mc['thwidth'],255,255,255);
	$imgcon->setTargetFile($dir . '/' . $archivo);
	$imgcon->resizeWidth($mc['imgwidth']);
	
	$sql = "INSERT INTO ".$db->prefix("rmms_images")." (`idp`,`titulo`,`img`) VALUES ('$idp','$titulo','$archivo')";
	$db->query($sql);
	if ($db->error()!=''){
		redirect_header('?op=images&amp;idp='.$idp, 2, _AM_DB_ERROR ."<br />".$db->error());
	} else {
		redirect_header('?op=images&amp;idp='.$idp, 1, _AM_DBOK);
	}
	
}

function deleteImage(){
	
	$idp = isset($_GET['idp']) ? $_GET['idp'] : 0;
	if ($idp<=0){
		redirect_header('productos.php',1,_AM_HACKATTEMPT);
		die();
	}
	
	$idi = isset($_GET['idi']) ? $_GET['idi'] : 0;
	if ($idi<=0){
		redirect_header('?op=images&amp;idp='.$idp,1,_AM_HACKATTEMPT);
		die();
	}
	
	$db =& Database::getInstance();
	$db->queryF("DELETE FROM ".$db->prefix("rmms_images")." WHERE id_img='$idi'");
	
	if ($db->error()!=''){
		redirect_header('?op=images&amp;idp='.$idp, 2, _AM_DB_ERROR ."<br />".$db->error());
	} else {
		redirect_header('?op=images&amp;idp='.$idp, 1, _AM_DBOK);
	}
}

///////////////////////////////////////////

$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : '');

switch ($op){
	case "new":
		NewForm();
		break;
	case "save":
		SaveProd();
		break;
	case "categos":
		ShowCategos();
		break;
	case "addcatego":
		AddCatego();
		break;
	case "delcatego":
		DeleteCatego();
		break;
	case "mod":
		ModForm();
		break;
	case "savemod":
		SaveModProd();
		break;
	case "delete":
		DeleteProd();
		break;
	case "view":
		ViewProd();
		break;
	case 'images':
		showImages();
		break;
	case 'saveimg':
		saveImage();
		break;
	case 'delimage':
		deleteImage();
		break;
	default:
		ShowProds();
		break;
}

?>
