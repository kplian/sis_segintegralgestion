<?php
/**
 * @package pXP
 * @file gen-SistemaDist.php
 * @author  (rarteaga)
 * @date 20-09-2011 10:22:05
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    var estadoGestion=null;
    Phx.vista.FormSeguimientoIndicador = {
        //bsave: false,
        require: '../../../sis_segintegralgestion/vista/indicador/Indicador.php',
        requireclase: 'Phx.vista.Indicador',
        title: 'Proyecto Actividad',

        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.FormSeguimientoIndicador.superclass.constructor.call(this, config);
            this.init();

            this.BloqueMenuIndicadores();   
        },

        BloqueMenuIndicadores: function(){

       	            Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/estadoGestion',
                        params: {
                            'id_gestion': this.cmbGestion.getValue(),
                        },
                        success: this.IndicadorRespuestaEstadoGestion,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
            });
       	
       },
       IndicadorRespuestaEstadoGestion: function (s,m){

           this.maestro = m;
           estadoGestion = s.responseText.split('%');
 
       },
        
       tabeast: [
            {
                // url: '../../../sis_segproyecto/vista/actividad/ActividadNieto.php',
                url: '../../../sis_segintegralgestion/vista/indicador_valor/IndicadorValorSeguimiento.php',
                title: 'Indicador valor seguimiento',
                width: 500,
                cls: 'IndicadorValorSeguimiento'
            }
        ]
        
       ,bdel:false,
	   bsave:false,
	   bnew:false,
	   bedit:false,
        
        
    };
</script>
