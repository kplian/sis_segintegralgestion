<?php
/**
 *@package pXP
 *@file ReporteRegistroVentas
 *@author  (Miguel Mamani)
 *@date 19/12/2108
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 * HISTORIAL DE MODIFICACIONES:
 * ISSUE 		   FECHA   			 AUTOR				 DESCRIPCION:
 *
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.FormReporte = Ext.extend(Phx.frmInterfaz, {
        Atributos : [
            {
                config: {
                    name: 'id_encuesta',
                    fieldLabel: 'Cuestionario',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_segintegralgestion/control/Cuestionario/listarRepCuestionario',
                        id: 'id_encuesta',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_encuesta', 'nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'enc.nombre'}
                    }),
                    valueField: 'id_encuesta',
                    displayField: 'nombre',
                    gdisplayField: 'nombre',
                    hiddenName: 'id_encuesta',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '50%',
                    gwidth: 150,
                    minChars: 2,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['nombre']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 0,
                filters: {pfiltro: 'enc.nombre',type: 'string'},
                grid: true,
                form: true
            }
        ],
        topBar : true,
        botones : false,
        labelSubmit : 'Generar',
        tooltipSubmit : '<b>Reporte Proyectoa</b>',
        constructor : function(config) {
            Phx.vista.FormReporte.superclass.constructor.call(this, config);
            this.init();
            this.addHelp();
        },
        tipo : 'reporte',
        clsSubmit : 'bprint',
        ActSave:'../../sis_segintegralgestion/control/Cuestionario/reporteCuestionario',
        agregarArgsExtraSubmit: function() {
            this.argumentExtraSubmit.encuesta = this.Cmp.id_encuesta.getRawValue();
        },
        addHelp: function () {
            this.addButton('lbl-color', {
                xtype: 'label',
                disabled: false,
                style: {
                    position: 'absolute',
                    top: '5px',
                    right: 0,
                    width: '300px',
                    'margin-right': '30px',
                    float: 'right'
                },
                html: '<div style="display: inline-flex">' +
                '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/loguito360-01.png" width="200px"></div>'
            });
        }
    })
</script>

