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
 * @version   CVS: $Id: Clase_caso.php,v 1.6.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla clase_caso.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla clase_caso.
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Clase_caso extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'clase_caso';                      // table name
    var $id_clase;                        // int4(4)  multiple_key
    var $id_municipio;                    // int4(4)  multiple_key
    var $id_departamento;                 // int4(4)  multiple_key
    var $id_caso;                         // int4(4)  multiple_key

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE
    var $fb_preDefOrder = array('id_clase');
    var $fb_fieldsToRender = array('id_clase');
    var $fb_selectAddEmpty = array('id_clase');
    var $fb_addFormHeader = false;
    var $fb__fieldLabels = array(
        'id_clase' => 'Clase',
        'id_municipio' => 'Municipio',
        'id_departamento' => 'Departamento'
    );

}

?>