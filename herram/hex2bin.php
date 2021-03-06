<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Convierte de hexadecimal a binario
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-utilidades
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: hex2bin.php,v 1.8.2.2 2011/10/18 16:05:04 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Convierte de hexadecimal a binario
 */

    if (PHP_SAPI !== 'cli') {
        die("Acceso: INTERPRETE DE COMANDOS");
    }
    $cad = "";
    fscanf(STDIN, "%s\n", $cad);
    $adjunto_r = pack("H*", $cad);
    echo "$adjunto_r";
?>
