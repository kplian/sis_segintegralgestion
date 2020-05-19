<?php
/**
 *@package pXP
 *@file gen-MODCuestionario.php
 *@author  (mguerra)
 *@date 21-04-2020 08:31:41
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
#0				21-04-2020 08:31:41								CREACION
#16            19/05/2020				manuel guerra		correcciones en correo
 */

class MODCuestionario extends MODbase{

    function __construct(CTParametro $pParam){
        parent::__construct($pParam);
    }

    function listarCuestionario(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='ssig.ft_cuestionario_sel';
        $this->transaccion='SSIG_CUE_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->setParametro('pes_estado','pes_estado','varchar');
        //Definicion de la lista del resultado del query
        $this->captura('id_cuestionario','int4');
        $this->captura('estado_reg','varchar');
        $this->captura('cuestionario','varchar');
        $this->captura('habilitar','boolean');
        $this->captura('observacion','varchar');
        $this->captura('id_usuario_reg','int4');
        $this->captura('fecha_reg','timestamp');
        $this->captura('id_usuario_ai','int4');
        $this->captura('usuario_ai','varchar');
        $this->captura('id_usuario_mod','int4');
        $this->captura('fecha_mod','timestamp');
        $this->captura('estado','varchar');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');
        $this->captura('funcionarios','varchar');
        $this->captura('id_funcionarios','varchar');
        $this->captura('peso','numeric');
        $this->captura('tipo','varchar');
        $this->captura('id_tipo_evalucion','int4');
        $this->captura('desc_nombre','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function insertarCuestionario(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='ssig.ft_cuestionario_ime';
        $this->transaccion='SSIG_CUE_INS';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('cuestionario','cuestionario','varchar');
        $this->setParametro('habilitar','habilitar','boolean');
        $this->setParametro('id_funcionarios','id_funcionarios','varchar');
        $this->setParametro('observacion','observacion','varchar');
        $this->setParametro('peso','peso','numeric');
        $this->setParametro('id_tipo','id_tipo','int4');
        $this->setParametro('id_tipo_evalucion','id_tipo_evalucion','int4');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function modificarCuestionario(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='ssig.ft_cuestionario_ime';
        $this->transaccion='SSIG_CUE_MOD';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_cuestionario','id_cuestionario','int4');
        $this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('cuestionario','cuestionario','varchar');
        $this->setParametro('habilitar','habilitar','boolean');
        $this->setParametro('id_funcionarios','id_funcionarios','varchar');
        $this->setParametro('observacion','observacion','varchar');
        $this->setParametro('peso','peso','numeric');
        $this->setParametro('id_tipo','id_tipo','int4');
        $this->setParametro('id_tipo_evalucion','id_tipo_evalucion','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function eliminarCuestionario(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='ssig.ft_cuestionario_ime';
        $this->transaccion='SSIG_CUE_ELI';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_cuestionario','id_cuestionario','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    //#16
    function enviarCorreo(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='ssig.ft_cuestionario_ime';
        $this->transaccion='SSIG_ENVCOR_IME';
        $this->tipo_procedimiento='IME';//tipo de transaccion

        $this->setParametro('id_cuestionario','id_cuestionario','int4');
        $this->setParametro('url_alarma','url_alarma','codigo_html');
        //Definicion de la lista del resultado del query

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    //
    function finCuestionario(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='ssig.ft_cuestionario_ime';
        $this->transaccion='SSIG_FINCUE_IME';
        $this->tipo_procedimiento='IME';//tipo de transaccion

        $this->setParametro('id_cuestionario','id_cuestionario','int4');
        //Definicion de la lista del resultado del query

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    //
    function listarRepCuestionario(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='ssig.ft_cuestionario_sel';
        $this->transaccion='SSIG_CUEREP_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->captura('id_encuesta','int4');
        $this->captura('nombre','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function reporteCuestionario(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='ssig.ft_cuestionario_sel';
        $this->transaccion='SSIG_REEN_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setCount(false);

        $this->setParametro('id_encuesta', 'id_encuesta', 'int4');

        $this->captura('id_encuesta_padre','integer');
        $this->captura('titulo','varchar');
        $this->captura('tipo','varchar');
        $this->captura('grupo','varchar');
        $this->captura('pesoxpregunta','numeric');
        $this->captura('nombre_cat','varchar');
        $this->captura('gerencia','varchar');
        $this->captura('evaluado','text');
        $this->captura('evaluador','text');
        $this->captura('descripcion_cargo','varchar');
        $this->captura('resp','numeric');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        // var_dump($this->respuesta);exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
}
?>