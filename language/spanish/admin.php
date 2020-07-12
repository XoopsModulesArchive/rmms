<?php
// $Id: admin.php 10 2006-12-12 18:38:57Z BitC3R0 $
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

global $lng_location;

define('_AM_NEWPRD','Nuevo Producto');
define('_AM_MODPRD','Modificar Producto');
define('_AM_EXISTPRD','Productos Existentes');
define('_AM_NEWCATEGO','Nueva Categor&iacute;a');
define('_AM_EDITCATEGO','Editar Categor&iacute;a');
define('_AM_EXISTCATEGO','Categor&iacute;as Existentes');
define('_AM_DELETE','Eliminar');
define('_AM_MODIFY','Modificar');
define('_AM_HACKATTEMPT','Operáción no permitida');
define('_AM_CANCEL','Cancelar');
define('_AM_OK','Enviar');
define('_AM_DB_ERROR','No se pudo completar esta operación:');
define('_AM_DBOK','Base de datos actualizada satisfactoriamente');

if ($lng_location=='index'){
	define('_AM_ACTUALSTATUS','Estado Actual del Sistema');
	define('_AM_TOTALPRDILIATES', 'Total de Productos:');
	define('_AM_TOTALCATEGOS','Total de Categor&iacute;as:');
}

if ($lng_location=='categos'){
	define('_AM_NAME','Nombre');
	define('_AM_PRDS','Productos');
	define('_AM_OPTIONS','Opciones');
	define('_AM_DESC','Descripci&oacute;n');
	define('_AM_IMAGEFILE','Archivo de Imágen');
	define('_AM_IMAGEFILETIP','Si llena este campo el siguiente será ignorado.');
	define('_AM_IMAGEURL','URL de la imágen');
	define('_AM_IMAGEURLTIP','Esta opción solo es válida cuando el campo anterior no se ha utilizado.');
	define('_AM_PARENT','Categoría Superior');
	define('_AM_NOCATEGOS','No existe ninguna categoría aún. Es necesario crear categorías para poder afiliar empresas.');
	define('_AM_ERRORNAME','No especificaste el nombre para esta categoría');
	define('_AM_SAVEOK','Categoria creada correctamente');
	define('_AM_CATEGOEXIST','Ya existe la categoría especificada');
	define('_AM_CATEGONOEXIST','No existe la categoría especificada.');
	define('_AM_CATEGOMODIFIED','Categor&iacute;a modificada correctamente');
	define('_AM_CONFIRMDELETE','¿Realmente deseas eliminar esta categoría?');
	define('_AM_DELETED','Categor&iacute;a eliminada');
	define('_AM_CURRENTIMG','Imágen Actual');
	define('_AM_DELETE_CURRIMG','¿Borrar imágen Actual?');
}

if ($lng_location=='productos'){
	define('_AM_EMPTY','No existen Productos en este momento.<br>Enviando al formulario de creación de Productos.');
	define('_AM_NAME','Nombre del Producto:');
	define('_AM_PRDCODE','Código del Producto:');
	define('_AM_PRDQUANTITY','Cantidad por Empaque:');
	define('_AM_PRDSIZE','Medidas Físicas:');
	define('_AM_PRDWEIGHT','Peso:');
	define('_AM_SHORTDESC','Descripción Corta:');
	define('_AM_LONGDESC','Descripción Amplia:');
	define('_AM_PRDMINIMO','Pedido Minimo:');
	define('_AM_PRDPRECIO','Precio:');
	define('_AM_ONLYNUMBERS','(Solo Números)');
	define('_AM_PRDBIG','Imágen del Producto:');
	define('_AM_SHOWINBLOCK','Mostrar en Bloque:');
	define('_AM_CATEGO','Categor&iacute;a:');
	define('_AM_FILETIP','Si llena este campo la imágen anterior será eliminada y reemplazada.');
	define('_AM_URLTIP','Esta opción solo es válida cuando el campo anterior no se ha utilizado.');
	define('_AM_ERRNOMBRE','ERROR: No especificaste el nombre para el producto');
	define('_AM_ERRCODE','ERROR: No especificaste el código para el producto');
	define('_AM_ERRQUANT','ERROR: No especificaste la cantidad por empaque para el producto');
	define('_AM_ERRSIZE','ERROR: No especificaste las medidas fisicas del producto');
	define('_AM_ERRPESO','ERROR: No proporcionaste el peso del producto');
	define('_AM_ERRMIN','ERROR: No propocionaste el pedido minimo para este producto');
	define('_AM_ERRSHORT','ERROR: Por favor proporciona una descripción corta para este producto');
	define('_AM_ERRLONG','ERROR: Por favor proporciona una descripción amplia y extendida para este producto');
	define('_AM_ERRPRICE','ERROR: Por favor especifica el precio para el producto');
	define('_AM_ERRCATEGO','ERROR: Por favor selecciona una categoría');
	define('_AM_PRDCREATED','Producto creado correctamente');
	define('_AM_PRDMODIFIED','Producto modificado correctamente');
	define('_AM_NOCATEGOS','No existe ninguna categoría aún. Es necesario crear categorías para poder afiliar empresas.');
	define('_AM_PRDNAME','Nombre');
	define('_AM_OPTIONS','Opciones');
	define('_AM_HCATEGO','Categor&iacute;a');
	define('_AM_PRDEXIST','Ya existe el producto especificado');
	define('_AM_CATEGOS','Categor&iacute;as');
	define('_AM_NOEXIST','No existe el producto especificado');
	define('_AM_CATEGOASSIGN','Categor&iacute;as Asignadas');
	define('_AM_CATASSIGN','Categor&iacute;a:');
	define('_AM_ASSIGN','Asignar');
	define('_AM_RELATIONEXIST','Esta categoría ya ha sido asignada al producto seleccionado');
	define('_AM_RELATIONOK','Categoria asignada correctamente');
	define('_AM_RELATIONDELETED','Relación eliminada correctamente');
	define('_AM_CONFIRMDELETE','¿Realmente deseas eliminar este producto?');
	define('_AM_DELETED','Producto eliminado correctamente');
	define('_AM_PRDDATA','Datos del producto');
	define('_AM_PRODUCTOS','Productos Relacionados:');
	define('_AM_ORDERNAME', 'Ordenar por Nombre');
	define('_AM_ORDERLAST', 'Ordenar por Fecha');
	define('_AM_CATEGO_SELECT','Seleccionar...');
	define('_AM_CURRENTIMG','Imágen Actual');
	define('_AM_IMAGES','Imágenes');
	define('_AM_MAGESFOR','Imágenes de "%s"');
	define('_AM_PIMAGE','Imágen');
	define('_AM_ITITLE','Título');
	define('_AM_NEWIMAGE','Agregar imágen a "%s"');
	define('_AM_CONFIRMDEL','Eliminar esta imágen:');
	define('_AM_PAGE','Página: ');
}
?>