<?php

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.FormReporte= Ext.extend(Phx.frmInterfaz, {
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
            },
        ],
        title : 'Generar Reporte',
        ActSave : '../../sis_segintegralgestion/control/Cuestionario/reporteCuestionario',
        topBar : true,
        botones : false,
        labelSubmit : 'Generar',
        tooltipSubmit : '<b>Generar Excel</b>',
        constructor : function(config) {
            Phx.vista.FormReporte.superclass.constructor.call(this, config);
            this.init();
        },

        tipo : 'Reporte',
        clsSubmit : 'bprint',

        agregarArgsExtraSubmit: function() {
             this.argumentExtraSubmit.eventodesc = this.Cmp.evento.getRawValue();
        },

        Grupos:
            [
                {
                    layout: 'column',
                    border: false,
                    defaults: {
                        border: false
                    },
                    items : [{
                        bodyStyle : 'padding-left:5px;padding-left:5px;',
                        border : false,
                        defaults : {
                            border : false
                        },
                        width : 800,
                        items: [
                            {
                            bodyStyle: 'padding-left:5px;',
                            items: [{
                                xtype: 'fieldset',
                                title: 'Filtro de Búsqueda',
                                autoHeight: true,
                                items: [
                                    {
                                        xtype: 'compositefield',
                                        fieldLabel: 'Cuestionario',
                                        msgTarget : 'side',
                                        anchor    : '-100',
                                        defaults: {
                                            flex: 1
                                        },
                                        items: [],
                                        id_grupo:0
                                    },
                                ]
                            }]
                        }]
                    }] 
                }
            ]
    })
</script>
