<?php
/**
 * @package pXP
 * @file gen-Indicador.php
 * @author  (admin)
 * @date 21-11-2016 14:51:35
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Indicador = Ext.extend(Phx.gridInterfaz, {
            constructor: function (config) {
                this.maestro = config.maestro;
                this.initButtons = [this.cmbGestion];
                this.buildGrupos();
                //llama al constructor de la clase padre
                Phx.vista.Indicador.superclass.constructor.call(this, config);
                this.init();

                //this.store.baseParams = {id_gestion: 0};
                this.load({params: {start: 0, limit: this.tam_pag}})
                this.cmbGestion.on('select', this.capturaFiltros, this);
                //this.buildComponentesDetalle();
                this.iniciarEventos();
            },
            capturaFiltros: function (combo, record, index) {
                this.store.baseParams = {id_gestion: this.cmbGestion.getValue()};
         
                //para utilizar en la vista IndicadorValorSeguimiento
	         	Ext.Ajax.request({
                    url: '../../sis_segintegralgestion/control/Indicador/estadoGestion',
                    params: {
                        'id_gestion': this.cmbGestion.getValue(),
                    },
                    success: this.RespuestaIndicadorValorEstadoGestion,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
	            });
                //
                
                this.store.reload();
            },

	         RespuestaIndicadorValorEstadoGestion: function (s,m) {
	
	        	this.maestro = m;
	            estadoGestion = s.responseText.split('%');
	
	        },
            loadValoresIniciales: function () {
                Phx.vista.Indicador.superclass.loadValoresIniciales.call(this);
                this.getComponente('id_gestion').setValue(this.cmbGestion.getValue());
                this.Cmp.num_decimal.setValue(2);
                
            },
            aprobarPlanes: function () {
                //verifica si se seleccion al guna gestión
                console.log('revisanado si es un dato de gestion', this.cmbGestion.getValue())
                if (this.cmbGestion.getValue()) {
                	alert(this.cmbGestion.getValue());
                    //var rec = this.sm.getSelected();
                    //var data = rec.data;
                    Phx.CP.loadingShow();
                    Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Plan/aprobarPlanes',
                        params: {
                            'id_gestion': this.cmbGestion.getValue()
                        },
                        success: this.successSave,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
                    });
                }
                else {
                    alert('No se selecciono ninguna gestión')
                }
            },
            cmbGestion: new Ext.form.ComboBox({
                fieldLabel: 'Gestion',
                allowBlank: true,
                emptyText: 'Gestion...',
                store: new Ext.data.JsonStore(
                    {
                        url: '../../sis_parametros/control/Gestion/listarGestion',
                        id: 'id_gestion',
                        root: 'datos',
                        sortInfo: {
                            field: 'gestion',
                            direction: 'DESC'
                        },
                        totalProperty: 'total',
                        fields: ['id_gestion', 'gestion'],
                        // turn on remote sorting
                        remoteSort: true,
                        baseParams: {par_filtro: 'gestion'}
                    }),
                valueField: 'id_gestion',
                triggerAction: 'all',
                displayField: 'gestion',
                hiddenName: 'id_gestion',
                mode: 'remote',
                pageSize: 50,
                queryDelay: 500,
                listWidth: '280',
                width: 80
            }),
            Atributos: [
                {
                    //configuracion del componente
                    config: {
                        labelSeparator: '',
                        inputType: 'hidden',
                        name: 'id_indicador'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    //configuracion del componente
                    config: {
                        labelSeparator: '',
                        inputType: 'hidden',
                        name: 'registro_completado'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    //configuracion del componente
                    config: {
                        labelSeparator: '',
                        inputType: 'hidden',
                        name: 'tipo'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    config: {
                        fieldLabel: 'id_gestion',
                        inputType: 'hidden',
                        name: 'id_gestion'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    config: {
                        name: 'sigla',
                        fieldLabel: 'Sigla',
                        allowBlank: false,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 50,
	                    renderer: function (value, p, record) {
	                    	console.log('....oooo', p)
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4; text-aling:left";
	                    	}
                            return record.data['sigla'];
	                    },
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'orden_sigla', type: 'string'},
                    id_grupo: 0,
                    grid: false,
                    //egrid: true,
                    form: true
                },
                //se realizo el intercambio de siglas duplicadas para el ordenamiendo 
                {
                    config: {
                        name: 'orden_sigla',
                        fieldLabel: 'Sigla',
                        allowBlank: false,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 50,
                        renderer: function (value, p, record) {
                            console.log('....oooo', p)
                            if(record.data['registro_completado']>=1){
                                p.style="background-color:#F9E4C4; text-aling:left";
                            }
                            return record.data['sigla'];
                        },
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'orden_sigla', type: 'string'},
                    id_grupo: 0,
                    grid: true,
                    //egrid: true,
                    form: false
                },
                {
                    config: {
                        name: 'indicador',
                        fieldLabel: 'Indicador',
                        allowBlank: false,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 1000,
	                    renderer: function (value, p, record) {
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4;  text-align: left;";
	                    	}
	                    	return  record.data['indicador'];
	                    },
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'ind.indicador', type: 'string'},
                    id_grupo: 1,
                    grid: true,
                    //egrid: true,
                    id_grupo: 0,
                    form: true
                },
                {
                    config: {
                        name: 'id_indicador_unidad',
                        fieldLabel: 'Unidad',
                        allowBlank: false,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_segintegralgestion/control/IndicadorUnidad/listarIndicadorUnidad',
                            id: 'id_indicador_unidad',
                            root: 'datos',
                            sortInfo: {
                                field: 'unidad',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_indicador_unidad', 'unidad'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'unidad'}
                        }),
                        valueField: 'id_indicador_unidad',
                        displayField: 'unidad',
                        gdisplayField: 'unidad',
                        hiddenName: 'id_indicador_unidad',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 15,
                        queryDelay: 1000,
                        anchor: '80%',
                        gwidth: 150,
                        minChars: 2,
                        renderer: function (value, p, record) {
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4; text-align: left";
	                    	}
	                    	return record.data['unidad'];
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {
                        pfiltro: 'inun.unidad',
                        type: 'string'
                    },
                    grid: true,
                    //egrid: true,
                    id_grupo: 0,
                    form: true
                },
                {
                    config: {
                        name: 'num_decimal',
                        fieldLabel: 'Num_decimal',
                        allowBlank: false,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4,
                        renderer: function (value, p, record) {
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4; text-align: left";
	                    	}
	                    	return record.data['num_decimal'];
                        }
                    },
                    type: 'NumberField',
                    filters: {pfiltro: 'ind.num_decimal', type: 'numeric'},
                    id_grupo: 1,
                    grid: true,
                    //egrid: true,
                    id_grupo: 0,
                    form: true
                },
                {
                    config: {
                        name: 'id_indicador_frecuencia',
                        fieldLabel: 'Frecuencia',
                        allowBlank: false,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_segintegralgestion/control/IndicadorFrecuencia/listarIndicadorFrecuencia',
                            id: 'id_indicador_frecuencia',
                            root: 'datos',
                            sortInfo: {
                                field: 'frecuencia',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_indicador_frecuencia', 'frecuencia'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'frecuencia'}
                        }),
                        valueField: 'id_indicador_frecuencia',
                        displayField: 'frecuencia',
                        gdisplayField: 'frecuencia',
                        hiddenName: 'id_indicador_frecuencia',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 15,
                        queryDelay: 1000,
                        anchor: '80%',
                        gwidth: 150,
                        minChars: 2,
                        renderer: function (value, p, record) {
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4; text-align: left";
	                    	}
	                        return record.data['frecuencia'];
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {pfiltro: 'ifr.frecuencia', type: 'string'},
                    grid: true,
                    //egrid: true,
                    id_grupo: 0,
                    form: true
                },
                {
                    config: {
                        name: 'semaforo',
                        fieldLabel: 'Semaforo',
                        allowBlank: false,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'semaforo'
                            ],
                            data: [['Simple'], ['Compuesto']]
                        }),
                        valueField: 'semaforo',
                        displayField: 'semaforo',
                        gdisplayField: 'semaforo',
                        hiddenName: 'semaforo',
                        //forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'local',
                        pageSize: 15,
                        queryDelay: 1000,
                        anchor: '80%',
                        gwidth: 150,
                        minChars: 2,
                        renderer: function (value, p, record) {
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4; text-align: left";
	                    	}
	                        return record.data['semaforo'];
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {pfiltro: 'ind.semaforo', type: 'string'},
                    grid: true,
                    //egrid: true,
                    id_grupo: 0,
                    form: true
                },
                {
                    config: {
                        name: 'comparacion',
                        fieldLabel: 'comparacion',
                        allowBlank: false,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'comparacion'
                            ],
                            data: [['Asc'], ['Desc']]
                        }),
                        valueField: 'comparacion',
                        displayField: 'comparacion',
                        gdisplayField: 'comparacion',
                        hiddenName: 'comparacion',
                        //forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'local',
                        pageSize: 15,
                        queryDelay: 1000,
                        anchor: '80%',
                        gwidth: 150,
                        minChars: 2,
                        renderer: function (value, p, record) {
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4; text-align: left";
	                    	}
	                        return record.data['comparacion'];
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {pfiltro: 'ind.comparacion', type: 'string'},
                    grid: true,
                    //egrid: true,
                    id_grupo: 0,
                    form: true
                    //,egrid:true,
                },
				{
					config: {
						name: 'id_funcionario_ingreso',
						fieldLabel: 'Funcionario Ingreso',
						allowBlank: false,
						emptyText: 'Elija una opcion...',
						store: new Ext.data.JsonStore({
							url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
							id: 'id_funcionario',
							root: 'datos',
							sortInfo: {
								field: 'desc_person',
								direction: 'ASC'
							},
							totalProperty: 'total',
							fields: ['id_funcionario', 'desc_person'],
							remoteSort: true,
							baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}
						}),
						valueField: 'id_funcionario',
						displayField: 'desc_person',
						gdisplayField: 'desc_person',
						hiddenName: 'id_funcionario',
						forceSelection: true,
						typeAhead: false,
						triggerAction: 'all',
						lazyRender: true,
						mode: 'remote',
						pageSize: 15,
						queryDelay: 1000,
						anchor: '80%',
						gwidth: 100,
						minChars: 2,
						renderer : function(value, p, record) {
							return String.format('{0} ', record.data['desc_person']);
						}
					},
					type: 'ComboBox',
					id_grupo: 0,
					filters: {pfiltro: 'PERSON.desc_funcionario1', type: 'string'},
					grid: true,
					form: true
				},
				{
					config: {
						name: 'id_funcionario_evaluacion',
						fieldLabel: 'Funcionario evaluación',
						allowBlank: false,
						emptyText: 'Elija una opcion...',
						store: new Ext.data.JsonStore({
							url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
							id: 'id_funcionario',
							root: 'datos',
							sortInfo: {
								field: 'desc_person',
								direction: 'ASC'
							},
							totalProperty: 'total',
							fields: ['id_funcionario', 'desc_person'],
							remoteSort: true,
							baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}
						}),
						valueField: 'id_funcionario',
						displayField: 'desc_person',
						gdisplayField: 'desc_person',
						hiddenName: 'id_funcionario',
						forceSelection: true,
						typeAhead: false,
						triggerAction: 'all',
						lazyRender: true,
						mode: 'remote',
						pageSize: 15,
						queryDelay: 1000,
						anchor: '80%',
						gwidth: 100,
						minChars: 2,
						renderer : function(value, p, record) {
							return String.format('{0} ', record.data['desc_person2']);
						}
					},
					type: 'ComboBox',
					id_grupo: 0,
					filters: {pfiltro: 'PERSON.desc_funcionario1', type: 'string'},
					grid: true,
					form: true
				},
                {
                    config: {
                        name: 'estado_reg',
                        fieldLabel: 'Estado Reg.',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 10
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'ind.estado_reg', type: 'string'},
                    id_grupo: 1,
                    //grid: false,
                    id_grupo: 0,
                    form: false
                },
                {
                    config: {
                        name: 'descipcion',
                        fieldLabel: 'Descripción',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 1000,
                        renderer: function (value, p, record) {
	                    	if(record.data['registro_completado']>=1){
	                    		p.style="background-color:#F9E4C4; text-align: left";
	                    	}
	                    	return record.data['descipcion'];
                        }
                    },
                    type: 'TextArea',
                    filters: {pfiltro: 'ind.descipcion', type: 'string'},
                    id_grupo: 1,
                    grid: true,
                    //egrid: true,
                    id_grupo: 0,
                    form: true
                    //,inputType:'hidden',
                },
                {
                    config: {
                        name: 'usuario_ai',
                        fieldLabel: 'Funcionaro AI',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 300
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'ind.usuario_ai', type: 'string'},
                    id_grupo: 1,
                    grid: true,
                    id_grupo: 0,
                    form: false
                },
                {
                    config: {
                        name: 'fecha_reg',
                        fieldLabel: 'Fecha creación',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        format: 'd/m/Y',
                        renderer: function (value, p, record) {
                            return value ? value.dateFormat('d/m/Y H:i:s') : '';
                        }
                    },
                    type: 'DateField',
                    filters: {pfiltro: 'ind.fecha_reg', type: 'date'},
                    id_grupo: 1,
                    grid: false,
                    id_grupo: 0,
                    form: false
                },
                {
                    config: {
                        name: 'usr_reg',
                        fieldLabel: 'Creado por',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'Field',
                    filters: {pfiltro: 'usu1.cuenta', type: 'string'},
                    id_grupo: 1,
                    grid: false,
                    id_grupo: 0,
                    form: false
                },
                {
                    config: {
                        name: 'id_usuario_ai',
                        fieldLabel: 'Creado por',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'Field',
                    filters: {pfiltro: 'ind.id_usuario_ai', type: 'numeric'},
                    id_grupo: 1,
                    grid: false,
                    id_grupo: 0,
                    form: false
                },
                {
                    config: {
                        name: 'fecha_mod',
                        fieldLabel: 'Fecha Modif.',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        format: 'd/m/Y',
                        renderer: function (value, p, record) {
                            return value ? value.dateFormat('d/m/Y H:i:s') : '';
                        }
                    },
                    type: 'DateField',
                    filters: {pfiltro: 'ind.fecha_mod', type: 'date'},
                    id_grupo: 1,
                    grid: false,
                    id_grupo: 0,
                    form: false
                },
                {
                    config: {
                        name: 'usr_mod',
                        fieldLabel: 'Modificado por',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'Field',
                    filters: {pfiltro: 'usu2.cuenta', type: 'string'},
                    id_grupo: 1,
                    grid: false,
                    id_grupo: 0,
                    form: false
                },
            ],
            
            onButtonEdit: function () {

               Phx.vista.Indicador.superclass.onButtonEdit.call(this);
               this.cargarImagen(this.Cmp.semaforo.getValue(), this.Cmp.comparacion.getValue());
                           
            },
    
            buildGrupos: function () {
                var me = this;
                this.panelResumen = new Ext.Panel({
                    padding: '0 0 0 20',
                    //html: '<div id="semaforos" align="center"> <div>',
                    split: true,
                    layout: 'fit'
                });
                me.Grupos = [{
                    xtype: 'fieldset',
                    border: false,
                    split: true,
                    //layout: 'column',
                    autoScroll: true,
                    autoHeight: true,
                    collapseFirst: false,
                    collapsible: true,
                    collapseMode: 'mini',
                    width: '100%',
                    padding: '0 0 0 10',
                    items: [
                        {
                            bodyStyle: 'padding-right:5px;',
                            width: '60%',
                            autoHeight: true,
                            border: true,
                            items: [
                                {
                                    xtype: 'fieldset',
                                    frame: true,
                                    border: false,
                                    layout: 'form',
                                    title: 'REGISTRO DE INDICADORES',
                                    width: '100%',
                                    //margins: '0 0 0 5',
                                    padding: '0 0 0 10',
                                    bodyStyle: 'padding-left:5px;',
                                    id_grupo: 0,
                                    html: '<div id="semaforos" align="center"> <div>',
                                    items: [],
                                }]
                        },
                        {
                            bodyStyle: 'padding-right:5px;',
                            width: '40%',
                            border: true,
                            autoHeight: true,
                            items: [me.panelResumen]
                        }
                    ]
                }];
            },
            iniciarEventos: function () {
            	
                var semaforo = "";
                var comparacion = "";
                this.Cmp.semaforo.on('select',
                    function (cmb, dat) {
                        console.log();
                        semaforo = dat.data.semaforo;
                        this.cargarImagen(semaforo, comparacion);
                    }, this);
                this.Cmp.comparacion.on('select',
                    function (cmb, dat) {
                        console.log();
                        comparacion = dat.data.comparacion;
                        this.cargarImagen(semaforo, comparacion);
                    }, this);
               

                   this.cmbGestion.on('select',
                       function (cmb, dat) {
                       	
                       	try{
                   			this.getBoton('btnAbrirGestion').show();
					        this.getBoton('btnCerrarGestion').show();
                	        this.BloqueMenuIndicadores();
                       	}
                       	catch(error){
                       		
                       	}
                       	
					


                    }, this);
                    
            },
            cargarImagen: function (semaforo, comparacion) {
            	
            	document.getElementById("semaforos").innerHTML = '<div></div>';
            	console.log("revisar semaforo al editar", this.Cmp.comparacion.value);
            	
            	//verificar el or de cada if al parecer no es neceario el primer or de 4 ifs
                if ((semaforo == "Simple" && comparacion == "Asc") || (this.Cmp.semaforo.value=="Simple" && this.Cmp.comparacion.value=="Asc") ) {
                    document.getElementById("semaforos").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/SIMPLE ASC.png" width="250px">';
                }
                if ((semaforo == "Simple" && comparacion == "Desc") || (this.Cmp.semaforo.value=="Simple" && this.Cmp.comparacion.value=="Desc")) {
                    document.getElementById("semaforos").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/SIMPLE DESC.png" width="250px">';
                }
                if ((semaforo == "Compuesto" && comparacion == "Asc") || (this.Cmp.semaforo.value=="Compuesto" && this.Cmp.comparacion.value=="Asc")) {
                    document.getElementById("semaforos").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/COMPUESTO ASC.png" width="400px">';
                }
                if ((semaforo == "Compuesto" && comparacion == "Desc") || (this.Cmp.semaforo.value=="Compuesto" && this.Cmp.comparacion.value=="Desc") ) {
                    document.getElementById("semaforos").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/COMPUESTO DESC.png" width="400px">';
                }
            },
            VerEstadoGestion: function(){
            	
            	Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/estadoGestion',
                        params: {
                            'id_gestion': this.maestro.id_gestion,
                        },
                        success: this.RespuestaEstadoGestion,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
                });
          	
            },
           
            RespuestaEstadoGestion: function (s,m){
        	//alert(s);
        	this.maestro = m;
        	//this.store
            var estadoGestion = s.responseText.split('%');


            if(estadoGestion[1]=='true'){
            	Ext.getCmp('b-new-' + this.idContenedor).hide()
            	Ext.getCmp('b-del-' + this.idContenedor).hide()
            	Ext.getCmp('b-save-' + this.idContenedor).hide()
             }
            },
            tam_pag: 100,
            title: 'Indicador',
            ActSave: '../../sis_segintegralgestion/control/Indicador/insertarIndicador',
            ActDel: '../../sis_segintegralgestion/control/Indicador/eliminarIndicador',
            ActList: '../../sis_segintegralgestion/control/Indicador/listarIndicador',
            id_store: 'id_indicador',
            fields: [
                {name: 'id_indicador', type: 'numeric'},
                {name: 'id_indicador_unidad', type: 'numeric'},
                {name: 'id_indicador_frecuencia', type: 'numeric'},
                {name: 'id_gestion', type: 'numeric'},
                {name: 'num_decimal', type: 'numeric'},
                {name: 'semaforo', type: 'string'},
                {name: 'estado_reg', type: 'string'},
                {name: 'sigla', type: 'string'},
                {name: 'descipcion', type: 'string'},
                {name: 'comparacion', type: 'string'},
                {name: 'indicador', type: 'string'},
                {name: 'usuario_ai', type: 'string'},
                {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
                {name: 'id_usuario_reg', type: 'numeric'},
                {name: 'id_usuario_ai', type: 'numeric'},
                {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
                {name: 'id_usuario_mod', type: 'numeric'},
                {name: 'usr_reg', type: 'string'},
                {name: 'usr_mod', type: 'string'},
                {name: 'unidad', type: 'string'},
                {name: 'tipo', type: 'string'},
                {name: 'frecuencia', type: 'string'},
                {name: 'gestion', type: 'numeric'},
                {name: 'registro_completado', type: 'numeric'},
                
                //{name: 'fecha', type: 'date', dateFormat: 'Y-m-d'},
                //{name:'hito', type: 'string'},
                //{name:'semaforo1', type: 'string'},
                //{name:'semaforo2', type: 'string'},
                //{name:'semaforo3', type: 'string'},
                //{name:'semaforo4', type: 'string'},
                //{name:'semaforo5', type: 'string'},
                //{name:'valor', type: 'string'},
                //{name:'justificacion', type: 'string'},
                //{name:'no_reporta', type: 'bit'},
                //{name:'id_indicador_valor', type: 'numeric'},
				{name:'id_funcionario_ingreso', type: 'numeric'},
				{name:'desc_person', type: 'string'},
				{name:'id_funcionario_evaluacion', type: 'numeric'},
				{name:'desc_person2', type: 'string'},
                {name:'orden_sigla', type: 'string'}
            ],
            sortInfo: {
                field: 'id_indicador',
                direction: 'ASC'
            },
            bdel: true,
            bsave: true,
			/*successSave:function(resp){
				
				this.store.rejectChanges();
				Phx.CP.loadingHide();
				
				
				Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/estadoGestion',
                        params: {
                            'id_gestion': this.maestro.id_gestion,
                        },
                        success: this.RespuestaEstadoGestion,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
                });
            
				if(resp.argument && resp.argument.news){
					if(resp.argument.def == 'reset'){
					  //this.form.getForm().reset();
					  this.onButtonNew();
					}
					
					//this.loadValoresIniciales() //RAC 02/06/2017  esta funcion se llama dentro del boton NEW
				}
				else{
					this.window.hide();
				}
		
		
		
				this.reload(); 
		
			},*/
        }
    )
</script>
		