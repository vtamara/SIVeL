<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
* Actualiza modulo mapag
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   $$
 * @link      http://sivel.sf.net
 */

/** Actualiza base de datos despu�s de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";
require_once "misc_actualiza.php";


$aut_usuario = "";
$db = autenticaUsuario($dsn, $accno, $aut_usuario, 21);


$act = objeto_tabla('Actualizacion_base');

$idac = 'mg-1';
if (!aplicado($idac)) {

    hace_consulta($db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('491', 'Mapa en Googlemap', '40', 'opcion?num=200')", false);

    aplicaact(
        $act, $idac, 'Opci�n del men� para ingresar al mapa'
    );
}

?>