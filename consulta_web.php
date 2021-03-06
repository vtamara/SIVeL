<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Consulta para la página web.
 * Una versión inicial que sirvió de referencia fue desarrollada por
 * Mauricio Rivera (mauricio.rivera.p@gmail.com) en 2004.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: consulta_web.php,v 1.119.2.6 2011/12/31 19:28:47 vtamara Exp $
 * @link      http://sivel.sf.net
 */

/**
 * Consulta web.
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'HTML/QuickForm/Controller.php';

require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';

require_once 'PagTipoViolencia.php';
require_once 'ResConsulta.php';

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    if (($d = strrpos($c, "/"))>0) {
        $c = substr($c, $d+1);
    }
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}


/**
 * Responde a botón para hacer consulta.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscaId
 */
class AccionConsultaWeb extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        $d = objeto_tabla('departamento');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        $fb =& DB_DataObject_FormBuilder::create($d);
        $db =& $d->getDatabaseConnection();

        $pFini      = var_req_escapa('fini', $db);
        $pFfin      = var_req_escapa('ffin', $db);
        $pFiini     = var_req_escapa('fiini', $db);
        $pFifin     = var_req_escapa('fifin', $db);
        $pMostrar   = var_req_escapa('mostrar', $db, 32);
        $pOrdenar   = substr(var_req_escapa('ordenar', $db), 0, 32);
        $pUsuario   = substr(var_req_escapa('usuario', $db), 0, 32);
        $pIdCasos   = substr(var_req_escapa('id_casos', $db), 0, 1024);
        $pIdClase   = (int)var_req_escapa('id_clase', $db);
        $pIdMunicipio   = (int)var_req_escapa('id_municipio', $db);
        $pIdDepartamento= (int)var_req_escapa('id_departamento', $db);
        $pClasificacion = var_req_escapa('clasificacion', $db);
        $pPresponsable  = (int)var_req_escapa('presponsable', $db);
        $pSsocial   = (int)var_req_escapa('ssocial', $db);
        $pNomvic    = substr(var_req_escapa('nomvic', $db), 0, 32);
        $pMFuentes  = (int)var_req_escapa('m_fuentes', $db);
        $pRetroalimentacion = (int)var_req_escapa('retroalimentacion', $db);
        $pVarLineas = (int)var_req_escapa('m_varlineas', $db);
        $pTeX       = (int)var_req_escapa('m_tex', $db);
        $pTitulo    = substr(var_req_escapa('titulo', $db), 0, 32);

        $campos = array(); //'caso_id' => 'Cód.');
        $tablas = "caso";
        $where = "";
        $ordCasos = array();
        if ($pIdCasos != '') {
            $ordCasos = explode(' ', $pIdCasos);
            $wc = "";
            foreach ($ordCasos as $cc) {
                consulta_and($db, $wc, "caso.id", (int)$cc, "=", "OR");
            }
            if ($wc != "") {
                $where .= "(" . $wc . ") ";
            }
        }
        if (trim($pTitulo) != '') {
            if ($where != "") {
                $where .= " AND ";
            }
            $where .= " (caso.titulo ILIKE '%" . trim($pTitulo) . "%') ";
        }
        if (isset($pFini['Y']) && $pFini['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFini, true), ">="
            );
        }
        if (isset($pFfin['Y']) && $pFfin['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFfin, false), "<="
            );
        }

        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_min'], ">="
        );
        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_max'], "<="
        );

        if ($pClasificacion != '') {
            $ini = '(';
            $so = '';
            $tind = false;
            $tcol = false;
            $totr = false;
            foreach ($pClasificacion as $cla) {
                $r = explode(":", $cla);
                $so2='';
                $dcatc = objeto_tabla('categoria');
                $dcatc->get((int)$r[2]);
                if ($dcatc->tipocat == 'I') {
                    consulta_and(
                        $db, $so2, "acto.id_categoria",
                        (int)$r[2]
                    );
                    $tind = true;
                } else if ($dcatc->tipocat == 'C') {
                    consulta_and(
                        $db, $so2, "actocolectivo.id_categoria",
                        (int)$r[2]
                    );
                    $tcol = true;
                } else if ($dcatc->tipocat == 'O') {
                    consulta_and(
                        $db, $so2,
                        "categoria_p_responsable_caso.id_categoria",
                        (int)$r[2]
                    );
                    $totr = true;
                } else {
                    die(
                        "Falta especificar tipo de categoria {$dcatc->id}" .
                        " ({$dcatc->tipocat})"
                    );
                }
                $so .= $ini . $so2 . ')';
                $ini = ' OR (';
            }
            if ($so != '') {
                $where .= ' AND (' . $so . ')';
            }
            $nt = 0;
            if ($tind) {
                $tablas .= " LEFT JOIN acto ON caso.id=acto.id_caso";
                $nt++;
            }
            if ($tcol) {
                $tablas .= " LEFT JOIN actocolectivo ON " .
                    "caso.id=actocolectivo.id_caso";
                $nt++;
            }
            if ($totr) {
                $tablas .= " LEFT JOIN categoria_p_responsable_caso ON " .
                    "caso.id=categoria_p_responsable_caso.id_caso";
                $nt++;
            }
        }


        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebCreaConsulta'))) {
                call_user_func_array(
                    array($c, 'consultaWebCreaConsulta'),
                    array(
                        $db, $pMostrar, &$where, &$tablas,
                        &$pOrdenar, &$campos
                    )
                );
            } else {
                echo_esc("Falta consultaWebCreaConsulta en $n, $c");
            }
        }
        if (isset($GLOBALS['gancho_cw_creaconsulta'])) {
            foreach ($GLOBALS['gancho_cw_creaconsulta'] as $k => $f) {
                if (is_callable($f)) {
                    call_user_func_array(
                        $f,
                        array(
                            $db, $pMostrar, &$where, &$tablas,
                        &$pOrdenar, &$campos, $page
                        )
                    );
                } else {
                    echo_esc("Falta $f de gancho_cw_creaconsulta[$k]");
                }
            }
        }


        if ($pIdClase != '') {
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and(
                $db, $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and($db, $where, "ubicacion.id_municipio", $pIdMunicipio);
            consulta_and($db, $where, "ubicacion.id_clase", $pIdClase);
            $tablas .= ", ubicacion ";
        } else if ($pIdMunicipio != '') {
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and(
                $db, $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and($db, $where, "ubicacion.id_municipio", $pIdMunicipio);
            $tablas .= ", ubicacion";
        } else if ($pIdDepartamento != '') {
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and(
                $db, $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            $tablas .= ", ubicacion";
        }

        if ($pPresponsable != '') {
            consulta_and_sinap(
                $where, "presuntos_responsables_caso.id_caso",
                "caso.id"
            );
            consulta_and(
                $db, $where, "presuntos_responsables_caso.id_p_responsable",
                $pPresponsable
            );
            $tablas .= ', presuntos_responsables_caso';
        }


        if (in_array(42, $page->opciones)
            && ($pUsuario != '' || (isset($pFiini['Y']) && $pFiini['Y'] != '')
            || (isset($pFifin['Y']) && $pFifin['Y'] != ''))
        ) {
            $tablas .= ", funcionario_caso";
            consulta_and_sinap($where, "funcionario_caso.id_caso", "caso.id");
        }
        if (in_array(42, $page->opciones)
            && isset($pFiini['Y']) && $pFiini['Y'] != ''
        ) {
            consulta_and(
                $db, $where, "funcionario_caso.fecha_inicio",
                arr_a_fecha($pFiini, true), ">="
            );
        }
        if (in_array(42, $page->opciones)
            && isset($pFifin['Y']) && $pFifin['Y'] != ''
        ) {
            consulta_and(
                $db, $where, "funcionario_caso.fecha_inicio",
                arr_a_fecha($pFifin, false), "<="
            );
        }

        if (in_array(42, $page->opciones) && $pUsuario != '') {
            consulta_and(
                $db, $where, "funcionario_caso.id_funcionario", $pUsuario
            );
        }

        if ($pNomvic != "") {
            $tablas .= ", persona";
            consulta_and_sinap($where, "victima.id_persona", "persona.id");
        }
        if ($pNomvic != "" || $pSsocial != "") {
            $tablas .= ", victima";
            consulta_and_sinap($where, "victima.id_caso", "caso.id");
        }
        if ($pSsocial != '') {
            consulta_and($db, $where, "victima.id_sector_social", $pSsocial);
        }

        if (trim($pNomvic) != '') {
            if ($where != "") {
                $where .= " AND";
            }
            $where .= " trim(trim(persona.nombres) || ' ' || " .
                "trim(persona.apellidos)) ILIKE '%" . trim($pNomvic) . "%'";
        }

        // Búsqueda por víctima no incluye combatientes para evitar sobreconteos
        // Emplear consulta_externa
        $conv = array('caso_id' => 0, 'caso_fecha' => 1, 'caso_memo' =>2);
        $q = "SELECT DISTINCT ";
        $sep = "";
        foreach ($conv as $k => $v) {
            $q .= $sep . str_replace("_", ".", $k);
            $sep = ", ";
        }
        $q .= " FROM " . $tablas
            ."  WHERE caso.id<>'" . $GLOBALS['idbus'] . "'" ;
        if ($where != "") {
            $q .= " AND " . $where;
        }
        consulta_orden($q, $pOrdenar);

        //echo "OJO q es $q"; die("x");

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebOrden'))) {
                call_user_func_array(
                    array($c, 'consultaWebOrden'),
                    array($q, &$pOrdenar)
                );
            } else {
                echo_esc("Falta consultaWebOrden en $n, $c");
            }
        }


        $result = hace_consulta($db, $q);

        foreach ($GLOBALS['cw_ncampos'] as $idc => $dc) {
            if (isset($_REQUEST[$idc]) && $_REQUEST[$idc] == 1) {
                $campos[$idc] = $dc;
            }
        }
        if ($pMFuentes == 1 && in_array(42, $page->opciones)) {
            $campos['m_fuentes'] = 'Fuentes';
        }

        if ($pMostrar != 'csv'
            && $pMostrar != 'revista'
            && $pMostrar != 'tabla'
            && $pMostrar != 'relato'
            && !in_array(42, $page->opciones)
        ) {
            die('No es posible');
        }


        $ar =& $result;
        $r = new ResConsulta(
            $campos, $db, $ar, $conv, $pMostrar,
            array('varlineas' => $pVarLineas, 'tex' => $pTeX),
            $ordCasos, null, $pOrdenar
        );
        $r->aHtml($pRetroalimentacion == 1);
    }
}


/**
 * Fórmulario para consulta web.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class ConsultaWeb extends HTML_QuickForm_Page
{

    /**
     * Opciones
     */
    var $opciones;

    /**
     * Constructora
     *
     * @param array $opciones opciones
     *
     * @return void
     */
    function ConsultaWeb($opciones)
    {
        $this->opciones = $opciones;
        $this->HTML_QuickForm_Page('consultaWeb', 'post', '_self', null);

        $this->addAction('id_departamento', new CamDepartamento());
        $this->addAction('id_municipio', new CamMunicipio());

        $this->addAction('consulta', new AccionConsultaWeb());
    }

    /**
     * Construye formulario.
     *
     * @return void
     */
    function buildForm()
    {
        $this->_formBuilt = true;

        $pMostrar   =var_req_escapa('mostrar');
        $pCategoria =var_req_escapa('categoria');
        $pSinCampos =var_req_escapa('sincampos');
        $pOrden     =var_req_escapa('orden');


        encabezado_envia('Consulta Web', $GLOBALS['cabezote_consulta_web']);
        $x =&  objeto_tabla('departamento');
        $db = $x->getDatabaseConnection();
        if (PEAR::isError($db)) {
            die($db->getMessage() . " - " . $db->getUserInfo());
        }

        $e =& $this->addElement(
            'header', null, 'Periodo de Consulta ' .
            $GLOBALS['consulta_web_fecha_min'].
            ' hasta ' .
            $GLOBALS['consulta_web_fecha_max']
        );

        if ($this->opciones != array()) {
            $cod =& $this->addElement('text', 'id_casos', 'Código(s): ');
            $cod->setSize(80);
        }

        $sel =& $this->addElement('text', 'titulo', 'Titulo del caso:');
        $sel->setSize(80);

        $dep =& $this->addElement(
            'select', 'id_departamento',
            'Departamento: ', array()
        );
        $options= array('' => '') + htmlentities_array(
            $db->getAssoc(
                "SELECT  id, nombre FROM departamento " .
                "ORDER BY nombre"
            )
        );
        $dep->loadArray($options);
        $dep->updateAttributes(
            array('onchange' =>
            'envia(\'consultaWeb:id_departamento\')'
            )
        );

        $mun =& $this->addElement(
            'select', 'id_municipio',
            'Municipio: ', array()
        );
        $mun->updateAttributes(
            array('onchange' =>
            'envia(\'consultaWeb:id_municipio\')'
            )
        );

        $cla =& $this->addElement(
            'select', 'id_clase',
            'Centro Poblado: ', array()
        );

        $ndepartamento = ret_id_departamento($this);
        if ($ndepartamento != null) {
            $dep->setValue($ndepartamento);
            $options= array('' => '') + htmlentities_array(
                $db->getAssoc(
                    "SELECT  id, nombre FROM municipio " .
                    "WHERE id_departamento='$ndepartamento' ORDER BY nombre"
                )
            );
            $mun->loadArray($options);
            $cla->loadArray(array());
        }
        $nmunicipio = ret_id_municipio($this);
        if ($nmunicipio != null && $ndepartamento != null) {
            $mun->setValue($nmunicipio);
            $options = array('' => '') + htmlentities_array(
                $db->getAssoc(
                    "SELECT id, nombre FROM clase " .
                    "WHERE id_departamento='$ndepartamento' AND " .
                    "id_municipio='$nmunicipio' ORDER BY nombre"
                )
            );
            $cla->loadArray($options);
        }

        $sel =& $this->addElement(
            'text', 'nomvic',
            'Nombre o apellido de la víctima'
        );
        $sel->setSize(80);

        $cy = date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
        $ay = explode('-', $GLOBALS['consulta_web_fecha_min']);
        $e =& $this->addElement(
            'date', 'fini', 'Desde: ',
            array(
                'language' => 'es', 'addEmptyOption' => true,
            'minYear' => $ay[0], 'maxYear' => $cy
            )
        );
        $e =& $this->addElement(
            'date', 'ffin', 'Hasta:',
            array(
                'language' => 'es', 'addEmptyOption' => true,
            'minYear' => $ay[0], 'maxYear' => $cy
            )
        );


        $sel =& $this->addElement(
            'select',
            'presponsable', 'Presunto Responsable'
        );
        $lpr = htmlentities_array(
            $db->getAssoc(
                "SELECT id, nombre FROM presuntos_responsables " .
                "WHERE fechadeshabilitacion is null"
            )
        );
        if (PEAR::isError($lpr)) {
            die($lpr->getMessage() . " - " . $lpr->getUserInfo());
        }
        $options = array('' => '') + $lpr;
        $sel->loadArray($options);

        $sel =& $this->addElement(
            'select', 'clasificacion',
            'Clasificación de Violencia'
        );
        $sel->setMultiple(true);
        $sel->setSize(5);
        ResConsulta::llenaSelCategoria(
            $db,
            "SELECT id_tipo_violencia, id_supracategoria, " .
            "id FROM categoria ORDER BY id_tipo_violencia," .
            "id_supracategoria, id;",
            $sel
        );
        if ($pCategoria == 'belicas') {
            $valscc = array();
            $d =&  objeto_tabla('categoria');
            $d->id_tipo_violencia = 'C';
            $d->find();
            while ($d->fetch()) {
                $fc = PagTipoViolencia::cadenaDeCodcat(
                    $d->id_tipo_violencia,
                    $d->id_supracategoria,
                    $d->id
                );
                $valscc[] = $fc;
            }
            $sel->setValue($valscc);
        }
        if ($pCategoria == 'nobelicas') {
            $valscc = array();
            $d =&  objeto_tabla('categoria');
            $d->whereAdd('id_tipo_violencia<>\'C\'');
            $d->find();
            while ($d->fetch()) {
                $fc = PagTipoViolencia::cadenaDeCodcat(
                    $d->id_tipo_violencia,
                    $d->id_supracategoria,
                    $d->id
                );
                $valscc[] = $fc;
            }
            $sel->setValue($valscc);
        }

        $sel =& $this->addElement(
            'select',
            'ssocial', 'Sector Social Víctima'
        );
        $options = array('' => '') + htmlentities_array(
            $db->getAssoc("SELECT id, nombre FROM sector_social")
        );
        $sel->loadArray($options);

        $aut_usuario = "";
        if (isset($_SESSION['id_funcionario'])) {
            include $_SESSION['dirsitio'] . "/conf.php";
            autenticaUsuario($dsn, $accno, $aut_usuario, 0);
        }


        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebFiltro'))) {
                call_user_func_array(
                    array($c, 'consultaWebFiltro'),
                    array(&$db, &$this)
                );
            }
        }

        if (isset($_SESSION['id_funcionario'])) {
            if (in_array(42, $_SESSION['opciones'])) {
                $sel =& $this->addElement(
                    'select',
                    'usuario', 'Usuario'
                );
                $options= array(''=>' ') + htmlentities_array(
                    $db->getAssoc(
                        "SELECT id, nombre FROM funcionario ORDER by nombre"
                    )
                );
                $sel->loadArray($options);
                $e =& $this->addElement(
                    'date', 'fiini', 'Ingreso Desde: ',
                    array(
                        'language' => 'es', 'addEmptyOption' => true,
                    'minYear' => $ay[0], 'maxYear' => $cy
                    )
                );
                $e =& $this->addElement(
                    'date', 'fifin', 'Ingreso Hasta:',
                    array(
                        'language' => 'es', 'addEmptyOption' => true,
                    'minYear' => $ay[0], 'maxYear' => $cy
                    )
                );

            }
        }

        $ae = array();
        $x =& $this->createElement(
            'radio', 'ordenar', 'fecha',
            'Fecha', 'fecha'
        );
        $ae[] =&  $x;
        if ($pOrden == '' || $pOrden == 'fecha') {
            $t =& $x;
        }
        $x =& $this->createElement(
            'radio', 'ordenar', 'ubicacion',
            'Ubicación', 'ubicacion'
        );
        $ae[] =& $x;
        if ($pOrden == 'ubicacion') {
            $t =& $x;
        }

        if ($this->opciones != array()) {
            $x =& $this->createElement(
                'radio', 'ordenar', 'codigo',
                'Código', 'codigo'
            );
            $ae[] =& $x;
            if ($pOrden == 'codigo') {
                $t =& $x;
            }
        }
        if (isset($GLOBALS['consultaweb_ordenarpor'])) {
            foreach ($GLOBALS['consultaweb_ordenarpor'] as $k => $f) {
                if (is_callable($f)) {
                    $r .= call_user_func_array(
                        $f,
                        array($pOrden, $this->opciones, $this, &$ae, &$t)
                    );
                } else {
                    echo_esc("Falta $f de consultaweb_ordenarpor[$k]");
                }
            }
        }


        $this->addGroup($ae, null, 'Ordenar por', '&nbsp;', false);
        $t->setChecked(true);

        $ae = array();
        $t =& $this->createElement(
            'radio', 'mostrar', 'tabla',
            'Tabla', 'tabla'
        );
        $ae[] =&  $t;

        $x =&  $this->createElement(
            'radio', 'mostrar', 'revista',
            'Reporte Revista', 'revista'
        );
        $ae[] =& $x;
        if ($pMostrar == 'revista') {
            $t =& $x;
        }

        if (isset($this->opciones) && in_array(42, $this->opciones)) {
            $x =&  $this->createElement(
                'radio', 'mostrar',
                'general', 'Reporte General', 'general'
            );
            $ae[] =& $x;
            if ($pMostrar == 'general') {
                $t =& $x;
            }
        }
        $x =&  $this->createElement(
            'radio', 'mostrar',
            'relato', 'Relato XML', 'relato'
        );
        $ae[] =& $x;
        if ($pMostrar == 'relato') {
            $t =& $x;
        }

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebFormaPresentacion'))) {
                call_user_func_array(
                    array($c, 'consultaWebFormaPresentacion'),
                    array(
                        $pMostrar, $this->opciones, &$this,
                        &$ae, &$t
                    )
                );
            } else {
                echo_esc("Falta consultaWebFormaPresentacion en $n, $c");
            }
        }
        if (isset($GLOBALS['gancho_cw_formapresentacion'])) {
            foreach ($GLOBALS['gancho_cw_formapresentacion'] as $k => $f) {
                if (is_callable($f)) {
                    call_user_func_array(
                        $f,
                        array($pMostrar, $this->opciones, $this, &$ae, &$t)
                    );
                } else {
                    echo_esc("Falta $f de consultaWebFormaPresentacion[$k]");
                }
            }
        }

        $x =& $this->createElement('radio', 'mostrar', 'csv', 'CSV', 'csv');
        $ae[] =&  $x;
        if ($pMostrar == 'csv') {
            $t =& $x;
        }

        $this->addGroup($ae, null, 'Forma de presentación', '&nbsp;', false);
        $t->setChecked(true);

        $asinc = array();
        if ($pSinCampos != '') {
            $asinc = explode(',', $pSinCampos);
        }
        $opch = array();
        foreach ($GLOBALS['cw_ncampos'] as $idc => $dc) {
            if ($this->opciones != array() || $idc != 'caso_id') {
                $sel =& $this->createElement(
                    'checkbox',
                    $idc, $dc, $dc
                );
                if (!in_array($idc, $asinc)) {
                    $sel->setValue(true);
                }
                $opch[] =& $sel;
            }
        };

        if (in_array(42, $this->opciones)) { // Podría ver rep. gen?
            $sel =& $this->createElement(
                'checkbox',
                'm_fuentes', 'Fuentes', 'Fuentes'
            );
            if (!in_array('m_fuentes', $asinc)) {
                $sel->setValue(false);
            }
            $opch[] =& $sel;
        }
        $sel =& $this->createElement(
            'checkbox',
            'retroalimentacion', 'Retroalimentación', 'Retroalimentación'
        );
        $sel->setValue(false);
        $opch[] =& $sel;

        $this->addGroup($opch, null, 'Campos por mostrar', '&nbsp;', false);

        $opch = array();
        $sel =& $this->createElement(
            'checkbox',
            'm_varlineas', 'Memo en varias lineas', 'Memo en varias lineas'
        );
        if (!in_array('m_varlineas', $asinc)) {
            $sel->setValue(false);
        }
        $opch[] =& $sel;
        $sel =& $this->createElement(
            'checkbox',
            'm_tex', 'Conversión a TeX', 'Conversión a TeX'
        );
        if (!in_array('m_tex', $asinc)) {
            $sel->setValue(false);
        }
        $opch[] =& $sel;

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebDetalle'))) {
                call_user_func_array(
                    array($c, 'consultaWebDetalle'),
                    array($pMostrar, $this->opciones, $this, $opch)
                );
            } else {
                echo_esc("Falta consultaWebDetalle en $n, $c");
            }
        }

        $this->addGroup(
            $opch, null, 'Detalles de la presentación',
            '&nbsp;', false
        );

        $opch = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('consulta'), 'Consulta'
        );
        $opch[] =& $sel;

        $this->addGroup($opch, null, '', '&nbsp;', false);

        if (isset($this->opciones) && in_array(42, $this->opciones)) {
            $tpie = "<div align=right><a href=\"index.php\">" .
                "Menú Principal</a></div>";
        } else if (isset($GLOBALS['pie_consulta_web_publica'])) {
            $tpie = $GLOBALS['pie_consulta_web_publica'];
        } else {
            $tpie = "&nbsp;";
        }
        $e =& $this->addElement('header', null, $tpie);

        agrega_control_CSRF($this);

        $this->setDefaultAction('consulta');

    }

}


/**
 * Inicia Controlador del formulario
 *
 * @return void
 */
function runController()
{
    $snru = nomSesion();
    if (!isset($_SESSION) || session_name()!=$snru) {
        session_name($snru);
        session_start();
    }

    /* No autenticamos porque pueden usarlo usuarios no autenticados */
    $nv = "_auth_" . $snru;
    $opciones = array();
    if (isset($_SESSION[$nv]['username'])) {
        $d = objeto_tabla('caso');
        $db =& $d->getDatabaseConnection();
        $rol = "";
        sacaOpciones($_SESSION[$nv]['username'], $db, $opciones, $rol);
    }

    $wizard =& new HTML_QuickForm_Controller('Consulta', false);
    $consweb = new ConsultaWeb($opciones);

    $wizard->addPage($consweb);

    $wizard->addAction('display', new HTML_QuickForm_Action_Display());
    $wizard->addAction('jump', new HTML_QuickForm_Action_Jump());
    $wizard->addAction('process', new AccionConsultaWeb());

    $wizard->run();
}

runController();

?>
