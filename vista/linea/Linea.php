<?php
/**
 * @package pXP
 * @file gen-Linea.php
 * @author  (admin)
 * @date 11-04-2017 20:20:49
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
var arrayMeses=[];
var v_id_plan=null;
var v_this=null;
var tipo='';
var v_maestro=null;
var v_id_gestion;
var v_peso_cambiado=0;
var v_bandera_avance_previsto="";
    Phx.vista.Linea = Ext.extend(Phx.arbGridInterfaz, {

            constructor: function (config) {
                this.maestro = config.maestro;
                //llama al constructor de la clase padre
                
                
                Phx.vista.Linea.superclass.constructor.call(this, config);
                this.loaderTree.baseParams = {id_plan: 0};
                this.init();
                this.tbar.items.get('b-new-' + this.idContenedor).disable()
                this.tbar.items.get('b-edit-' + this.idContenedor).disable()
                this.tbar.items.get('b-del-' + this.idContenedor).disable()
                
                
                this.addButton('btnLineaAvancee', {
                    text: 'Avance previsto',
                    iconCls: 'blist',
                    disabled: true,
                    handler: this.AsignarLineaAvance,
                    tooltip: '<b>Avance previsto</b><br/>'
                });
                this.addButton('btnAvanceReal', {
                    text: 'Avance real',
                    iconCls: 'blist',
                    disabled: true,
                    handler: this.AsignarLineaAvanceReal,
                    tooltip: '<b>Avance real</b><br/>'
                });

            },
            onReloadPage: function (m) {
            	v_bandera_avance_previsto="";
		        v_maestro =m;
                this.maestro = m;
                this.loaderTree.baseParams = {id_plan: this.maestro.id_plan};
                v_id_plan=this.maestro.id_plan;
                v_id_gestion=this.maestro.id_gestion;
                this.sm.clearSelections();
                this.root.reload();
                this.treePanel.expandAll();	
                

             //validar aumentando el peso acumulado del tercer nivel el peso acumulado del tercer nivel de la linea 
                if(this.maestro.nivel==2 && this.maestro.porcentaje_acumulado_aux>0 ){
                	
                	
                	if(parseInt(this.maestro.aprobado)==0){
                	   this.getBoton('btnAvanceReal').disable();
                	   this.getBoton('btnLineaAvancee').enable();
                       
                	}
                	else{
                		this.getBoton('btnAvanceReal').enable();
                		this.getBoton('btnLineaAvancee').disable();
                	}
                	//console.log("seleccion ",this);
                	//alert(this.root.childNodes);
                }
                else{
                	this.getBoton('btnLineaAvancee').disable();
                	this.getBoton('btnAvanceReal').disable();
                }
                
            },
            DesplegarArbol:function(){
            	this.treePanel.expandAll();	
            },
            AsignarLineaAvanceReal: function (record) {
                this.openFormLineaAvanceReal('new', this);
            },
	        openFormLineaAvanceReal: function () {

                this.maestro=v_maestro;
                var me = this;

                me.objSolForm = Phx.CP.loadWindows('../../../sis_segintegralgestion/vista/linea_avance/FormAvanceReal.php',
                    'Formulario de avance real',
                    {
                        modal: true,
                        //width: '100%',
                         width: '80%',
                        height: '60%'
                    }, 
                    {
                        data: {
                            id_plan: v_id_plan

                        }
                    },
                    this.idContenedor,
                    'FormAvanceReal',
                    );

	        },

            AsignarLineaAvance: function (record) {
            	
            	if(v_bandera_avance_previsto!=""){
            		alert(v_bandera_avance_previsto);
            	}
            	else{
                    this.openFormLineaAvance('new', this);
            	}
            },

	        openFormLineaAvance: function () {
                this.GenerarColumnas();
                var me = this;
                
                console.log("Ver errores de formulario ",this);
                
                console.log("prueba de datos ", me);
                me.objSolForm = Phx.CP.loadWindows('../../../sis_segintegralgestion/vista/linea_avance/LineaAvance.php',
                    'Formulario de avance previsto',
                    {
                        modal: true,
                        //width: '100%',
                        width: '90%',
                        height: '60%'
                    }, 
                    {
                        data: {
                            id_plan: v_id_plan,
                             meses: arrayMeses,
                             this:this
                            //tipo_form: tipo,
                            //datos_originales: record
                        }
                    },
                    this.idContenedor,
                    'LineaAvance',
                    );

	        },
            onSaveForm: function (interface,valores,id) {
                alert('Guardado Correctamente');
                interface.panel.close();
            },
			GenerarColumnas: function (){
				       	            Ext.Ajax.request({
		                        url: '../../sis_segintegralgestion/control/LineaAvance/GenerarColumnaMeses',
		                        params: {
		                            'id_plan': v_id_plan,
		                        },
		                        success: this.RespuestaColumnas,
		                        failure: this.conexionFailure,
		                        timeout: this.timeout,
		                        scope: this
		            });
			},
			RespuestaColumnas: function (s,m){
		        this.maestro = m;
		        var meses = s.responseText.split('%');
				arrayMeses = meses[1].split(",");
					          
			},
            Atributos: [
                {
                    //configuracion del componente
                    config: {
                        labelSeparator: '',
                        inputType: 'hidden',
                        name: 'id_linea'
                    },
                    type: 'Field',
                    form: true
                },

                {
                    config: {
                        fieldLabel: 'id_linea_padre',
                        inputType: 'hidden',
                        name: 'id_linea_padre'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    config: {
                        fieldLabel: 'id_plan',
                        inputType: 'hidden',
                        name: 'id_plan'
                    },
                    type: 'Field',
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
                    filters: {pfiltro: 'linea.estado_reg', type: 'string'},
                    id_grupo: 1,

                    form: false
                },
                {
                    config: {
                        fieldLabel: 'nivel',
                        inputType: 'hidden',
                        name: 'nivel'
                    },
                    type: 'NumberField',
                    form: true
                },
                {
                    config: {
                        name: 'nombre_linea_padre',
                        fieldLabel: 'Nombre linea padre',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 150,
                        maxLength: 150,
                        disabled: true
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'lpa.nombre_linea', type: 'string'},
                    id_grupo: 1,
                    grid: false,
                    form: true
                },
                {
                    config: {
                        name: 'nombre_linea',
                        fieldLabel: 'Nombre',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 150,
                        maxLength: 500, //--#3 endetr juan 28/05/2019 incrementar  lineas a 500 caracter

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'linea.nombre_linea', type: 'string'},
                    id_grupo: 1,

                    form: true,
                    grid: true
                },
                {
                    config: {
                        name: 'peso',
                        fieldLabel: 'Peso',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'NumberField',
                    filters: {pfiltro: 'linea.peso', type: 'numeric'},
                    id_grupo: 1,

                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'porcentaje_acumulado',
                        fieldLabel: 'Peso acumulado',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4,
                        gtpl: function (p){
		       		    	//juan jimeenez
		       		    	//console.log(arguments);
		       		    	if(this.nivel <=1 && this.porcentaje_acum !=100){
		       		            v_bandera_avance_previsto="Alerta!! El peso acumulado de la linea "+this.nombre_linea+" no esta igual a 100";
		       		    	}
                            return this.porcentaje_acumulado;
						}
                    },
                    type: 'NumberField',
                    filters: {pfiltro: 'linea.peso', type: 'numeric'},
                    id_grupo: 1,
                    grid: true,
                    form: false
                },

                {
                    config: {
                        name: 'porcentaje_restante',
                        fieldLabel: 'Peso restante',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'NumberField',
                    filters: {pfiltro: 'linea.peso', type: 'numeric'},
                    id_grupo: 1,
                    grid: true,
                    form: false
                },


                {
                    config: {
                        //enviar erreglo
                        name: 'id_funcionarios',
                        fieldLabel: 'Responsable',
                        allowBlank: true,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
                            id: 'id_funcionario',
                            root: 'datos',
                            sortInfo: {
                                field: 'desc_person',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_funcionario', 'codigo', 'desc_person', 'ci', 'documento', 'telefono', 'celular', 'correo'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}

                        }),
                        valueField: 'id_funcionario',//valor que se le dara de acuerdo al sotre del combo
                        displayField: 'desc_person',
                        tpl: '<tpl for="."> <div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}</div> <p>{desc_person}</p> <p>CI:{ci}</p> </div></tpl>',
                        gdisplayField: 'funcionarios',//poner el parametro que viene de la BD del grid
                        hiddenName: 'id_funcionarios',//es el mismo nombre del name
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 5000,
                        queryDelay: 1000,
                        anchor: '80%',
                        gwidth: 200,
                        minChars: 2,
                        //para multiples
                        enableMultiSelect: true,
                        renderer: function (value, p, record) {
                        	return "juan";
                            //return String.format('{0} datossssss', record.data['funcionarios']);
                        }
                    },
                    //cambiar el tipo de combo
                    type: 'AwesomeCombo',
                    id_grupo: 0,
                    filters: {pfiltro: 'PERSON.desc_funcionario1', type: 'string'},
                    grid: true,
                    form: true
                },
                {
                    config:{
                        name: 'orden_logico',
                        fieldLabel: 'Orden Lógico',
                        allowBlank: false,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength:4,
                        maxValue:100,
                        minValue:0
                    },
                    type:'TextField',
                    filters:{pfiltro:'orden_logico_temporal',type:'string'},
                    id_grupo:1,
                    grid:false,
                    form:true
                },
                {
                    config: {
                        name: 'orden_logico_temporal',
                        fieldLabel: 'Orden Lógico',
                        allowBlank: false,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 50,
                        gtpl: function (p){
                          
                            return this.orden_logico;
                        }
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'orden_logico_temporal', type: 'string'},
                    id_grupo: 0,
                    grid: true,
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
                            return value ? value.dateFormat('d/m/Y H:i:s') : ''
                        }
                    },
                    type: 'DateField',
                    filters: {pfiltro: 'linea.fecha_reg', type: 'date'},
                    id_grupo: 1,

                    form: false
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
                    filters: {pfiltro: 'linea.usuario_ai', type: 'string'},
                    id_grupo: 1,

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
                    filters: {pfiltro: 'linea.id_usuario_ai', type: 'numeric'},
                    id_grupo: 1,
                    grid: false,
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
                            return value ? value.dateFormat('d/m/Y H:i:s') : ''
                        }
                    },
                    type: 'DateField',
                    filters: {pfiltro: 'linea.fecha_mod', type: 'date'},
                    id_grupo: 1,

                    form: false
                }
            ],

            NodoCheck: false,//si los nodos tienen los valores para el check
            baseParams: {clasificacion: true},
            //textRoot: 'Planes',
            id_nodo: 'id_linea',
            id_nodo_p: 'id_linea_padre',
            enableDD: false,
            bnew: true,
            bsave: false,
            bedit: true,
            bdel: true,
            rootVisible: false,
            fwidth: 420,
            fheight: 300,

            title: 'Definición de líneas',
            ActSave: '../../sis_segintegralgestion/control/Linea/insertarLinea',
            ActDel: '../../sis_segintegralgestion/control/Linea/eliminarLinea',
            ActList: '../../sis_segintegralgestion/control/Linea/listarLineaArb',

            id_store: 'id_linea',

            fields: [
                {name: 'id_linea', type: 'numeric'},
                {name: 'id_linea_padre', type: 'numeric'},
                {name: 'id_plan', type: 'numeric'},
                {name: 'nivel', type: 'numeric'},
                {name: 'nombre_linea', type: 'string'},
                {name: 'peso', type: 'numeric'},
                {name: 'id_funcionarios', type: 'string'},
                {name: 'funcionarios', type: 'string'},
                {name: 'nombre_linea_padre', type: 'string'},
                {name: 'porcentaje_acumulado', type: 'string'},
                {name: 'porcentaje_restante', type: 'string'},
                {name: 'porcentaje_acum', type: 'numeric'},
                {name: 'orden_logico', type: 'string'},
                {name: 'orden_logico_temporal', type: 'string'},
            ],

            sortInfo: {
                field: 'id_linea',
                direction: 'ASC'
            },


            loadValoresIniciales: function () {
                Phx.vista.Linea.superclass.loadValoresIniciales.call(this);
                this.Cmp.id_plan.setValue(this.maestro.id_plan);
                //this.menuBotonesLinea();

            },
            onButtonNew: function () {
            	
                    v_peso_cambiado=-1;
                    
                    this.maestro=v_maestro;
                    
                    tipo='new';
                    this.bloquearMenuPlan();
                    v_bandera_avance_previsto="";

		                    //si es infenido es por no seleecciono un plan

            }, 
            bloquearMenuPlan: function () {

                Ext.Ajax.request({
                    url: '../../sis_segintegralgestion/control/Plan/estadoGestion',
                    params: {
                        'id_gestion': v_id_gestion,
                    },
                    success: this.successBloquearMenu,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });

            },

            successBloquearMenu: function (s, m) {
                //this.tbar.items.get('b-new-' + this.idContenedor).disable()

                //alert('ingrese al bloqueador de menus')
                estadoGestion = s.responseText.split('%');
                console.log('mostrando el vaor del estado de gestion', estadoGestion)

                //alert(estadoGestion[1].toString().trim());
                if(estadoGestion[1].toString().trim()=='0'){
                	
                    if(tipo=='new'){
                    	// para nuevo
		                    if (this.maestro.id_plan && this.maestro.nivel == 2) {
		                        var nodo = this.sm.getSelectedNode();
		                        console.log("nodo de linea ",this.sm);
		                        if (nodo) {
		                            if (nodo.attributes.nivel < 2) {
		                                Phx.vista.Linea.superclass.onButtonNew.call(this);
		                                this.Cmp.nivel.setValue((nodo.attributes.nivel > 0) ? (parseInt(nodo.attributes.nivel) + 1) : 1);
		                                this.getComponente('nombre_linea_padre').setValue(nodo.attributes.nombre_linea);
		
		                            }
		                            else {
		                                Ext.MessageBox.alert('ERROR', 'NO PUEDE crear mas SUB-NIVLELES.');
		
		                            }
		                        } else {
		
		                            Phx.vista.Linea.superclass.onButtonNew.call(this);
		                            this.Cmp.nivel.setValue('NULL');
		
		                        }
		                    } else {
		                        Ext.MessageBox.alert('ADVERTENCIA!!!', 'No tiene un PLAN VALIDO seleccionado ')
		                    }
                    }
                    else{
                    	//var editar
                    }

                }
                else{
                	 alert("ALERTA!! No se puede editar si la gestion está aprobado");
                }
            },

		    onSubmit: function(o, x, force) {
		    	    var me = this;
		            if(parseInt(this.Cmp.peso.value)!=parseInt(v_peso_cambiado) && v_peso_cambiado!=-1){
						if (confirm('ALERTA!! SE VOLVERÁN A RECALCULAR LOS AVANCES PREVISTOS Y AVANCES REALES ¿ESTA SEGURO DE EDITAR?')) {
						    	if (me.form.getForm().isValid() || force===true) {
						
						            Phx.CP.loadingShow();
						            // arma json en cadena para enviar al servidor
						            Ext.apply(me.argumentSave, o.argument);
						            if (me.fileUpload) {
						
						                Ext.Ajax.request({
						                    form: me.form.getForm().getEl(),
						                    url: me.ActSave,
						                    params: me.getValForm,
						                    headers: {
										        'Accept': 'application/json',
										    },
										    isUpload: me.fileUpload,
						                    success: me.successSaveFileUpload,
						                    argument: me.argumentSave,
						                    failure: function(r) {
						                        console.log('falla upload', r)
						                        },
						                    timeout: me.timeout,
						                    scope: me
						                })
						
						                } else {
						                	
						
						                Ext.Ajax.request({
						                    url: me.ActSave,
						                    params: me.getValForm,
						                    isUpload: me.fileUpload,
						                    success: me.successSave,
						                    argument: me.argumentSave,
						
						                    failure: me.conexionFailure,
						                    timeout: me.timeout,
						                    scope: me
						                });
						            }
						
						        }
						}

					}
					else{
					    	if (me.form.getForm().isValid() || force===true) {
					
					            Phx.CP.loadingShow();
					            // arma json en cadena para enviar al servidor
					            Ext.apply(me.argumentSave, o.argument);
					            if (me.fileUpload) {
					
					                Ext.Ajax.request({
					                    form: me.form.getForm().getEl(),
					                    url: me.ActSave,
					                    params: me.getValForm,
					                    headers: {
									        'Accept': 'application/json',
									    },
									    isUpload: me.fileUpload,
					                    success: me.successSaveFileUpload,
					                    argument: me.argumentSave,
					                    failure: function(r) {
					                        console.log('falla upload', r)
					                        },
					                    timeout: me.timeout,
					                    scope: me
					                })
					
					                } else {
					                	
					
					                Ext.Ajax.request({
					                    url: me.ActSave,
					                    params: me.getValForm,
					                    isUpload: me.fileUpload,
					                    success: me.successSave,
					                    argument: me.argumentSave,
					
					                    failure: me.conexionFailure,
					                    timeout: me.timeout,
					                    scope: me
					                });
					            }
					
					        }
					}
		
		    },
			onButtonEdit : function() {
				//Funcion editar del toolbar
				v_bandera_avance_previsto="";
				this.swBtn = 'edit';
				var nodo = this.sm.getSelectedNode();
				if (nodo.attributes && nodo.attributes.allowEdit) {
					this.window.show();
					//llenamos datos del formulario
					for (var i = 0; i < this.Atributos.length; i++) {
						var ac = this.Atributos[i];
						//iniciamos un componente del tipo "Atributos[i].tipo" con laconnfiguracion Atributos.config
						//fields.push({header:ac.fieldLabel,width:ac.gwidth,dataIndex:ac.name});
						ac.config.id = this.idContenedor + '-C-' + i;
						if (ac.form) {
							//El componente es parte del formulario
							if (ac.form) {
								if ((this.Atributos[i].type == 'ComboBox' && this.Atributos[i].config.mode == 'remote'  ) || this.Atributos[i].type == 'TrigguerCombo') {
									var _id = ac.config.valueField;
									//nombre del atributo del combo que recibe el valor
									var _dis = ac.config.displayField;
									//define el origen del displayField en el grid
									var _df = ac.config.displayField;
									if (this.Atributos[i].config.gdisplayField) {
										_df = ac.config.gdisplayField;
									}
									if (!this.Componentes[i].store.getById(nodo.attributes[_id])) {
										var recTem = new Array();
										recTem[_id] = nodo.attributes[_id];
										recTem[_dis] = nodo.attributes[_df];
										this.Componentes[i].store.add(new Ext.data.Record(recTem, nodo.attributes[_id]));
										this.Componentes[i].store.commitChanges();
									}
									//this.Componentes[i].setValue(rec.data[_id])
									this.Componentes[i].setValue(nodo.attributes[ac.config.name]);
								} else {
									this.Componentes[i].setValue(nodo.attributes[ac.config.name]);
								}
							}
						}
					}//fin for componentes
					
					v_peso_cambiado=parseInt(nodo.attributes.peso);

					
					this.form.getForm().loadRecord(nodo.attributes);
					this.window.buttons[0].hide();

				}
			},
            preparaMenu: function (n) {

                this.maestro=v_maestro;

                console.log('observando los atributo de n', n.attributes)
                if (this.maestro.aprobado == 0) {


                    //n.attributes.id_gestion > 0 ||
                    if (n.attributes.tipo_nodo == 'hijo' || n.attributes.tipo_nodo == 'raiz' || n.attributes.id == 'id') {
                        this.tbar.items.get('b-new-' + this.idContenedor).enable()
                        //this.getBoton('btnLineaAvancee').disable();
                    }
                    else {
                        this.tbar.items.get('b-new-' + this.idContenedor).disable()
                        //this.getBoton('btnLineaAvancee').enable();
                    }
                    // llamada funcion clase padre
                    Phx.vista.Linea.superclass.preparaMenu.call(this, n);
                }else
                {
                    this.tbar.items.get('b-new-' + this.idContenedor).disable()
                    this.tbar.items.get('b-edit-' + this.idContenedor).disable()
                    this.tbar.items.get('b-del-' + this.idContenedor).disable()

                }
            },

            liberaMenu: function () {
            	
            	this.maestro=v_maestro;
            	
                this.tbar.items.get('b-new-' + this.idContenedor).disable()
                if (this.maestro.nivel > 1 && this.maestro.aprobado == 0) {
                    this.tbar.items.get('b-new-' + this.idContenedor).enable()
                }
                else {
                    this.tbar.items.get('b-new-' + this.idContenedor).disable()

                }

                Phx.vista.Linea.superclass.preparaMenu.call(this);

                this.tbar.items.get('b-edit-' + this.idContenedor).disable()
                this.tbar.items.get('b-del-' + this.idContenedor).disable()

            },

            //funcion que corre cuando se elimina con exito
            successDel: function (resp) {
            	
            	
            	console.log("Veririvar estados ",resp);
            	
            	//var nodo = this.sm.getSelectedNode();
            
            	//nodo.attributes.nivel


            	Phx.vista.Linea.superclass.successSave.call(this, resp);
            	Phx.CP.getPagina(this.idContenedorPadre).root.reload();
                Phx.CP.getPagina(this.idContenedorPadre).treePanel.expandAll();	
                
                this.sm.clearSelections();

                Phx.CP.loadingHide();
                resp.argument.nodo.reload()
                this.onButtonAct()
                this.DesplegarArbol();
            },
            onButtonAct: function () {
            	v_bandera_avance_previsto="";
            	this.sm.clearSelections();
                this.root.reload();
                this.DesplegarArbol();
                //ponemos estos datos par que recarge el panel de la derecha, mandando el id_plan 0
                //Phx.CP.getPagina(this.idContenedor + '-east-' + 0).loaderTree.baseParams = {id_plan: 0};
                //Phx.CP.getPagina(this.idContenedor + '-east-' + 0).root.reload();
            },
			onButtonDel : function() {
                v_bandera_avance_previsto="";
				console.log("testear  parametros ", this.sm.getSelectedNode().attributes.nivel);
				//alert(nodo);
				if (confirm('ALERTA!! SE ELIMINARÁN LOS AVANCES PREVISTOS Y AVANCES REALES PERTENECIENTES A LA FILA SELECCIONADA ¿ESTÁ SEGURO DE ELIMINAR EL REGISTRO?')) {
					Phx.CP.loadingShow();
					var params = {					
						    'nivel':this.sm.getSelectedNode().attributes.nivel,
							'id_linea':this.sm.getSelectedNode().attributes.id_linea,
							'id_linea_padre':this.sm.getSelectedNode().attributes.id_linea_padre,
							'peso':this.sm.getSelectedNode().attributes.peso
							};
					params[this.id_nodo] = this.sm.getSelectedNode().attributes[this.id_nodo];
					params[this.id_nodo_p] = this.sm.getSelectedNode().attributes[this.id_nodo_p];
					params['tipo_meta'] = this.sm.getSelectedNode().attributes.tipo_meta;
		
					Ext.Ajax.request({
						url : this.ActDel,
						success : this.successDel,
						failure : this.conexionFailure,
						params : params,
						argument : {
							'nodo' : this.sm.getSelectedNode().parentNode
						},
						timeout : this.timeout,
						scope : this
					});
					this.sm.clearSelections()
					
					this.root.reload();
					this.DesplegarArbol();
				}
			},  
			successSave : function(resp) {
				
				Phx.CP.loadingHide();
				if (resp.argument.news) {
					if (resp.argument.def == 'reset') {
						 this.onButtonNew()
					}
					//this.loadValoresIniciales()
					//this.form.getForm().reset();
					//this.loadValoresIniciales() //RAC 02/06/2017  esta funcion se llama dentro del boton NEW
					//del nuevo nodo, en el segundo componente (id_p)
					var nodo = this.sm.getSelectedNode();
					this.Cmp[this.id_nodo_p].setValue(nodo.attributes[this.id_nodo])
				} else {
					this.window.hide();
				}
				//actualiza el nodo padre
				if (this.swBtn == 'new') {
					var sno
					sno = this.sm.getSelectedNode();
					if (sno) {
						sno.reload();
					} else {
						//es el nodo raiz
						this.onButtonAct();
					}
				} else {
					if (resp.argument.btnCheck) {
						resp.argument.nodo.parentNode.reload()
					} else {
						var sn = this.sm.getSelectedNode();
						if (sn && sn.parentNode) {
							sn.parentNode.reload();
						} else {
							this.root.reload();
						}
					}
				}
				
				// avtualizamos el grid despues de editar la linea
				this.root.reload();
				this.DesplegarArbol();
			},        
            
        },
    )
</script>
		
		