<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Errores de validaci�n al insertar en pesta�a b�sicos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: novalida-basicos.php,v 1.4.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserci�n de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** B�SICOS ***/
$post = array();
$post['_qf_basicos_localizacion'] = 'Localizaci�n';
$post['busid'] = '';
$post['titulo'] = 'T�tulo';
$post['fecha']['d'] = '';
$post['fecha']['M'] = '';
$post['fecha']['Y'] = '';
$post['hora'] = '15:00';
$post['duracion'] = '3:00';
$post['id_intervalo'] = '1';
$post['tipo_ubicacion'] = 'S';
$post['id_caso'] = '';
$post['evita_csrf'] = '1234';
$post['_qf_default'] = 'basicos:siguiente';

pasaPestanaFicha($db, array("caso"), $post, null, false);

?>