<?php
// $Id: send.php 8 2006-12-12 06:47:55Z BitC3R0 $
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

$nombre = $_POST['nombre'];
$producto = $_POST['producto'];
$codigo = $_POST['codigo'];
$empresa = $_POST['empresa'];
$mail = $_POST['mail'];
$tel = $_POST['tel'];
$comentarios = $_POST['comentarios'];
$idp = $_POST['idp'];

if ($nombre==''){ redirect_header('moreinfo.php?idp='.$idp, 1, _MC_PROD_ERRNOMBRE); die(); }
if ($producto==''){ redirect_header('moreinfo.php?idp='.$idp, 1, _MC_PROD_ERRPROD); die(); }
if ($codigo==''){ redirect_header('moreinfo.php?idp='.$idp, 1, _MC_PROD_ERRPROD); die(); }
if ($mail=='' && $tel==''){ redirect_header('moreinfo.php?idp='.$idp, 1, _MC_PROD_TELMAIL); die(); }
if ($mail!='' && !checkEmail($mail)){ redirect_header('moreinfo.php?idp='.$idp, 1, _MC_PROD_INVALIDMAIL); die(); }

$xoopsMailer =& getMailer();
$xoopsMailer->useMail();
$xoopsMailer->setToEmails($xoopsModuleConfig['infoemail']);
$xoopsMailer->setFromEmail($xoopsConfig['from']);
$xoopsMailer->setFromName($xoopsConfig['fromname']);
$xoopsMailer->setSubject(_MC_INFO_SUBJECT);
$xoopsMailer->setBody(sprintf(_MC_PROD_INFOBODY, $producto, $codigo, $nombre, $mail, $empresa, $tel, $comentarios));
$xoopsMailer->send();

redirect_header('index.php', 2, _MC_PROD_SOLSENDED);

?>