<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto asociado a una tabla de la base de datos.
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: Escrito_caso.php,v 1.14.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla escrito_caso.
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/**
 * Definicion para la tabla escrito_caso.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Escrito_caso extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'escrito_caso';                    // table name
    var $fecha;                           // date(4)  multiple_key
    var $ubicacion;                       // varchar(-1)
    var $clasificacion;                   // varchar(-1)
    var $ubicacion_fisica;                // varchar(-1)
    var $id_prensa;                       // int4(4)  multiple_key
    var $id_caso;                         // int4(4)  multiple_key

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE

    var $fb_selectAddEmpty = array('id_prensa');
    var $fb_hidePrimaryKey = false;
    var $fb_preDefOrder = array('id_prensa', 'fecha', 'ubicacion',
        'clasificacion', 'ubicacion_fisica'
    );
    var $fb_fieldsToRender = array('id_prensa', 'fecha', 'ubicacion',
        'clasificacion', 'ubicacion_fisica'
    );
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('fecha', 'id_prensa');
    var  $fb_fieldLabels = array(
        'fecha' => 'Fecha',
        'ubicacion' => 'Ubicación',
        'clasificacion' => 'Clasificación',
        'ubicacion_fisica' => 'Ubicación Física',
        'id_prensa' => 'Fuente'
    );


    /**
     * Opciones de fecha para un campo
     *
     * @param string $fieldName campo
     *
     * @return arreglo de opciones
     */
    function dateOptions($fieldName)
    {
        $fv = isset($GLOBALS['fechaPuedeSerVacia'])
            && $GLOBALS['fechaPuedeSerVacia'];
        return array('minYear' => $GLOBALS['anio_min'],
            'addEmptyOption' => $fv
        );
    }

    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$formbuilder)
    {
        $this->fb_preDefElements = array('id_caso' =>
                    HTML_QuickForm::createElement('hidden', 'id_caso')
        );
    }

    /**
     * Ajusta formulario generado.
     *
     * @param object &$form        Formulario HTML_QuickForm
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);

/*        $sel =& $form->getElement('id_prensa');
        if (isset($sel) && !PEAR::isError($sel)
            && $sel->getType() == 'select'
        ) {
            if (isset($GLOBALS['etiqueta']['id_prensa'])) {
                $sel->setLabel($GLOBALS['etiqueta']['id_prensa']);
            }
            $sel->_options = htmlentities_array($sel->_options);
        }
 */
        $e =& $form->getElement('ubicacion');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(100);
            if (isset($GLOBALS['etiqueta']['ubicacion_fuente'])) {
                $e->setLabel($GLOBALS['etiqueta']['ubicacion_fuente']);
            }
        }

        $e =& $form->getElement('clasificacion');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(100);
            if (isset($GLOBALS['etiqueta']['clasificacion_fuente'])) {
                $e->setLabel($GLOBALS['etiqueta']['clasificacion_fuente']);
            }
        }

        $e =& $form->getElement('fecha');
        if (isset($e) && !PEAR::isError($e)) {
            $e->_options['language'] = 'es';
            $e->_options['format'] = 'd-M-Y';
            $e->_options['addEmptyOption'] = true;
            $e->_options['minYear'] = $GLOBALS['anio_min'];
            $e->_options['maxYear'] = date('Y');
            if (isset($GLOBALS['etiqueta']['fecha_fuente'])) {
                $e->setLabel($GLOBALS['etiqueta']['fecha_fuente']);
            }
        }

        $e =& $form->getElement('ubicacion_fisica');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(100);
        }
    }

}

?>
