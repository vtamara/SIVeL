<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Objeto asociado a una tabla de la base de datos.
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Vinculo_estado.php,v 1.11.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla vinculo_estado
 * Ver documentaci�n de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Vinculo_estado extends DataObjects_Basica
{
    var $__table = 'vinculo_estado';                       // table name

    var $nom_tabla = 'Vinculo con el Estado';

    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return integer Id del registro SIN INFORMACI�N
     */
    static function idSinInfo()
    {
        return 38;
    }

}
?>