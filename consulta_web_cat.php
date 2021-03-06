<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Consulta de categoria para consulta web
 * Una versión inicial fue desarrollada en 2004 por Mauricio Rivera
 * (mauricio.rivera.p@gmail.com).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público.  Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: consulta_web_cat.php,v 1.25.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: CONSULTA PÚBLICA
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "misc.php";
require_once 'DataObjects/Categoria.php';

encabezado_envia('Categoria');
if (!isset($_REQUEST['t']) || !isset($_REQUEST['s'])
    || !isset($_REQUEST['c'])
) {
        die("Faltan datos para efectuar consulta");
}

$t = var_escapa($_REQUEST['t']);
$s = (int)var_escapa($_REQUEST['s']);
$c = (int)var_escapa($_REQUEST['c']);

$dt =& objeto_tabla('tipo_violencia');
$dt->id = $t;
if ($dt->find() == 0) {
    die("No existe tipo de violencia " . htmlentities($t));
}
$dt->fetch();

$ds =& objeto_tabla('supracategoria');
$ds->id_tipo_violencia = $t;
$ds->id = $s;
if ($ds->find() == 0) {
    die("No existe supracategoria " . htmlentities($s));
}
$ds->fetch();

$dc =& objeto_tabla('categoria');
$dc->id_tipo_violencia = $t;
$dc->id_supracategoria = $s;
$dc->id = $c;
if ($dc->find() == 0) {
    die("No existe categoria " . htmlentities($c));
}
$dc->fetch();

echo "<table border='1'>";
echo "<tr><th>Código</th><th>Descripción</th></tr>";
echo "<tr><td>" . htmlentities($t) . htmlentities($c) . "</td>";
echo "<td>" . htmlentities($dt->nombre) . " / " . htmlentities($ds->nombre)
    . " / " . htmlentities($dc->nombre) . "</td></tr>";
echo "</table>";

pie_envia();
?>
