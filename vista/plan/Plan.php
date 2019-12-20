<?php
/**
 * @package pXP
 * @file gen-Plan.php
 * @author  (admin)
 * @date 11-04-2017 14:31:46
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
var arrayMeses=[];
var v_root=null;
    Phx.vista.Plan = Ext.extend(Phx.arbGridInterfaz, {

            constructor: function (config) {
                this.maestro = config.maestro;
                //agregando el boton para agregar la gestion
                this.initButtons = [this.cmbGestion];

                //llama al constructor de la clase padre
                Phx.vista.Plan.superclass.constructor.call(this, config);
                //this.loaderTree.baseParams = {id_gestion: 0};
                this.loaderTree.baseParams = {id_gestion: 0};
                this.init();

                this.addButton('btnAprobado', {
                    text: 'Aprobar',
                    iconCls: 'block',
                    disabled: true,
                    handler: function () {
                        this.aprobarPlanes(1)
                    },
                    tooltip: '<b>Realiza la aprobación de los planes</b>'
                });

                this.addButton('btnDesAprobado', {
                    text: 'Desaprobar',
                    iconCls: 'bunlock',
                    disabled: true,
                    handler: function () {
                        this.aprobarPlanes(0)
                    },
                    tooltip: '<b>Realiza la desaprobación de los planes</b>'
                });
                this.addButton('btnPlanGlobal', {
                    text: 'Plan global',
                    iconCls: ' x-btn-text bword',
                    disabled: false,
                    handler: function () {
                        this.PlanGlobal()
                    },  
                    tooltip: '<b>Exporta el plan global en formato excel</b>'
                });
                this.getBoton('btnPlanGlobal').hide();

                this.cmbGestion.on('select', this.capturaFiltros, this);


                this.tbar.items.get('b-new-' + this.idContenedor).disable()

                //bloqueando los botones del menu al inicio del sistema
                this.bloquearMenuPlan();


                
            },
            PlanGlobal: function () {

               var nodo = this.sm.getSelectedNode();
               if(nodo){
                    if(nodo.attributes.nivel!=null){
                        Phx.CP.loadingShow();
                        Ext.Ajax.request({
                                        url: '../../sis_segintegralgestion/control/Plan/reportePlanGlobal',
                                        params: {
                                                'id_gestion': this.cmbGestion.getValue(),
                                                'id_plan': nodo.attributes.id_plan,
                                                'nivel': nodo.attributes.nivel,
                                                'nombre_plan': nodo.attributes.nombre_plan,
                                                'gestion': this.cmbGestion.lastSelectionText
                                        },
                                        success: this.successExport,
                                        failure: this.conexionFailure,
                                        timeout: this.timeout,
                                        scope: this
                                        });
                    }
                    else{
                       alert("La planificacion seleccionada no esta permitido");
                    }   
               }
               else{
                    alert("Seleccione una planificacion");
               }

            },
            successExport : function(resp) {
                Phx.CP.loadingHide();
                var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                var nomRep = objRes.ROOT.detalle.archivo_generado;
                if (Phx.CP.config_ini.x == 1) {
                    nomRep = Phx.CP.CRIPT.Encriptar(nomRep);
                }
                window.open('../../../lib/lib_control/Intermediario.php?r=' + nomRep+'&t='+new Date().toLocaleTimeString())
            },
            onSaveForm: function (interface,valores,id) {
                alert('Guardado Correctamente');
                interface.panel.close();
            },
			GenerarColumnas: function (){
				 var nodo = this.sm.getSelectedNode();
				       	            Ext.Ajax.request({
		                        url: '../../sis_segintegralgestion/control/LineaAvance/GenerarColumnaMeses',
		                        params: {
		                            'id_plan': nodo.attributes.id_plan,
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

            DesplegarArbol:function(){
            	this.treePanel.expandAll();	
            },

            aprobarPlanes: function (valorAprobado) {
                //verifica si se seleccion al guna gestión
                var me = this;
                if (this.cmbGestion.getValue()) {
                    //var rec = this.sm.getSelected();
                    //var data = rec.data;

                    Phx.CP.loadingShow();
                    Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Plan/aprobarPlanes',
                        params: {
                            'id_gestion': this.cmbGestion.getValue(),
                            'aprobado': valorAprobado
                        },
                        success: me.successSaveAprobar,
                        failure: me.conexionFailureAprobar,
                        timeout: me.timeout,
                        scope: me
                    });

                }
                else {
                    Ext.MessageBox.alert('ERROR!!!', 'Seleccione primero una gestion.');

                }
                
                
                
            },
            successSaveAprobar: function (s, m) {
            	
                var estado = s.responseText.split('%');
               
                alert(estado[1]);
                

            	v_root=this.sm;
            	
                Phx.CP.loadingHide();
                this.bloquearMenuPlan();
                this.root.reload();
                //Ext.MessageBox.alert('EXITO!!!', 'Se realizo con exito la operación.');
                this.DesplegarArbol();
                this.sm=v_root;
                  
              
                this.loadValoresIniciales();

                var pw = Phx.CP.getPagina(this.idContenedor+'-east');
                pw.store.removeAll(); 
                //pw.root.removeAll();                 
                pw.getBoton('new').disable();
                pw.getBoton('edit').disable();
                pw.getBoton('del').disable();
                pw.getBoton('act').disable();
                pw.getBoton('btnAvanceReal').disable();
                pw.getBoton('btnLineaAvancee').disable();


 
            },
            conexionFailureAprobar: function () {
                Phx.CP.loadingHide();
                this.bloquearMenuPlan();
                alert('ERROR!! Comuniquese con el administrador')
                this.root.reload();
                this.DesplegarArbol();
            },


            capturaFiltros: function (combo, record, index) {

                //alert("selecciono una gestion");
                this.sm.clearSelections() //--#3 endetr juan 28/05/2019 limpiar selección para crear raiz en diferentes gestiones
                this.loaderTree.baseParams = {id_gestion: this.cmbGestion.getValue()};

                this.bloquearMenuPlan();
                this.root.reload();

                this.DesplegarArbol();
                this.getBoton('btnPlanGlobal').show();
                
                
            },
			
            loadValoresIniciales: function () {
                Phx.vista.Plan.superclass.loadValoresIniciales.call(this);
                this.getComponente('id_gestion').setValue(this.cmbGestion.getValue());

            },
            onButtonNew: function () {

                if (this.cmbGestion.getValue()) {
                    var nodo = this.sm.getSelectedNode();
                    //verificamos si selecciono un dato
                    if (nodo) {
                        //preguntando su no es hoja
                        if (nodo.attributes.nivel < 2) {
                            Phx.vista.Plan.superclass.onButtonNew.call(this);
                            //asignando el valor para el registro del del nivel
                            this.getComponente('nivel').setValue((nodo.attributes.nivel > 0) ? (parseInt(nodo.attributes.nivel) + 1) : 1);
                            this.getComponente('aprobado').setValue(0);
                            this.getComponente('nombre_plan_padre').setValue(nodo.attributes.nombre_plan);
                        } else {
                            Ext.MessageBox.alert('ERROR', 'NO PUEDE crear mas SUB-NIVLELES.');
                        }
                    } else {
                        Phx.vista.Plan.superclass.onButtonNew.call(this);
                        this.getComponente('aprobado').setValue(0);
                        this.getComponente('peso').setValue(100);

                    }
                }
                else {
                    Ext.MessageBox.alert('ADVERTENCIA!!!', "Seleccione una GESTIÓN primero.");
                }
                
                
            },
            cmbGestion: new Ext.form.ComboBox({
                fieldLabel: 'Gestion',
                allowBlank: true,
                emptyText: 'Gestion...',
                store: new Ext.data.JsonStore(
                    {
                        url: '../../sis_segintegralgestion/control/Plan/listarGestion',
                        id: 'id_gestion',
                        root: 'datos',
                        sortInfo: {
                            field: 'gestion',
                            direction: 'DESC'
                        },
                        totalProperty: 'total',
                        fields: ['id_gestion', 'gestion', 'existe_plan'],
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
            //Realizando el recargado de los datos el arbol
            successSave: function (resp) {
                Phx.vista.Plan.superclass.successSave.call(this, resp);
                this.bloquearMenuPlan();
                this.root.reload();
                this.DesplegarArbol();
            },
         /*   tabeast: [
                {
                    url: '../../../sis_segintegralgestion/vista/linea_avance/LineaAvance.php',
                    title: 'Línea avance',
                    width: 700,
                    cls: 'LineaAvance'

                }

            ], */
           east: 
                {
                    url: '../../../sis_segintegralgestion/vista/linea/Linea.php',
                    title: 'Línea',
                    width: 700,
                    cls: 'Linea'

                },
           /*east: 
                {
                    url: '../../../sis_segintegralgestion/vista/linea/PlanLinea.php',
                    title: 'Plan Línea',
                    width: 700,
                    cls: 'PlanLinea'

                },*/

            Atributos: [

                {
                    //configuracion del componente
                    config: {
                        inputType: 'hidden',
                        name: 'id_plan'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    //configuracion del componente
                    config: {
                        inputType: 'hidden',
                        name: 'porcentaje_acumulado_aux'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    config: {
                        fieldLabel: 'id_plan_padre',
                        inputType: 'hidden',
                        name: 'id_plan_padre'
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
                        name: 'nombre_plan_padre',
                        fieldLabel: 'Nombre plan padre',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 150,
                        maxLength: 150,
                        disabled: true,
                        
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'ppa.nombre_plan', type: 'string'},
                    id_grupo: 1,
                    grid: false,
                    form: true
                },
                {
                    config: {
                        name: 'nombre_plan',
                        fieldLabel: 'Nombre',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 150,
                        maxLength: 150,
		       		    gtpl: function (p){
		       		    	//juan jimeenez
		       		    	//console.log(arguments);

                            	return this.nombre_plan;
                            
						}
	                   
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'ssigplan.nombre_plan', type: 'string'},
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'nivel',
                        inputType: 'hidden',
                        fieldLabel: 'nivel',
                    },
                    type: 'NumberField',
                    grid: false,
                    form: true
                },
                {
                    config: {
                        name: 'peso',
                        fieldLabel: 'Peso',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4,
		       		    gtpl: function (p){
                            	return this.peso;
						}
                    },
                    type: 'NumberField',
                    filters: {pfiltro: 'ssigplan.peso', type: 'numeric'},
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
                            	return this.porcentaje_acumulado;
						}
                    },

                    type: 'TextField',
                    filters: {pfiltro: 'ssigplan.peso', type: 'numeric'},
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
                        maxLength: 4,

                    },
                    type: 'NumberField',
                    filters: {pfiltro: 'ssigplan.peso', type: 'numeric'},
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
                        pageSize:5000,
                        queryDelay: 1000,
                        anchor: '80%',
                        gwidth: 200,
                        minChars: 2,
                        //para multiples
                        enableMultiSelect: true,
		       		    gtpl: function (p){
                            	return this.funcionarios;
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
                    //configuracion del componente
                    config: {
                        name: 'aprobado',
                        fieldLabel: 'aprobado',
                        inputType: 'hidden',
                        gwidth: 50,
		       		    gtpl: function (p){
                           return this.aprobado;
						}
                    },
                    type: 'Field',
                    grid: true,
                    form: true
                },
                {
                    //configuracion del componente
                    //../../../lib/imagenes/alma32x32.png
                    config: {
                        name: 'completado',
                        fieldLabel: '',
                        inputType: 'hidden',
                        gwidth: 50,
		       		    gtpl: function (p){
		       		    	
		       		    	
                            if(this.completado==1 && this.nivel==2){
                            	//return String.format('<p><img src="../../../lib/imagenes/alma32x32.png" width="60px" height="60px" alt="Bienvenidos al ejemplo de CCTW" /></p>');
                            	return String.format('<p><img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/advertencia.png" width="50%" alt="Complete los acumulados de las lineas o los avances previstos" /></p>');
                            	//<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/SIMPLE ASC.png" width="250px">
                            }
                            
                            if(this.completado==0 && this.nivel==2){
                            	return String.format('<p><img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/completado.png"  width="50%" alt="Completado" /></p>');
                            }

                            
						}
                    },
                    type: 'Field',
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
                    filters: {pfiltro: 'ssigplan.estado_reg', type: 'string'},
                    id_grupo: 1,
                    grid: false,
                    form: false
                },

                {
                    config: {
                        name: 'id_usuario_ai',
                        fieldLabel: '',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 4
                    },
                    type: 'Field',
                    filters: {pfiltro: 'ssigplan.id_usuario_ai', type: 'numeric'},
                    id_grupo: 1,
                    grid: false,
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
                    filters: {pfiltro: 'ssigplan.fecha_reg', type: 'date'},
                    id_grupo: 1,
                    grid: false,
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
                    filters: {pfiltro: 'ssigplan.usuario_ai', type: 'string'},
                    id_grupo: 1,
                    grid: false,
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
                    filters: {pfiltro: 'ssigplan.fecha_mod', type: 'date'},
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
                    grid: true,
                    form: false
                }
            ],

            NodoCheck: false,//si los nodos tienen los valores para el check
            //ActCheck: '../../sis_seguridad/control/GuiRol/checkGuiRol',
            title: 'Definición de Planes',
            ActSave: '../../sis_segintegralgestion/control/Plan/insertarPlan',
            ActDel: '../../sis_segintegralgestion/control/Plan/eliminarPlan',
            ActList: '../../sis_segintegralgestion/control/Plan/listarPlanArb',

            id_store: 'id_plan',

            baseParams: {clasificacion: true},
            //textRoot: 'Planes',
            id_nodo: 'id_plan',
            id_nodo_p: 'id_plan_padre',
            enableDD: false,
            bnew: true,
            bsave: false,
            bedit: true,
            bdel: true,
            rootVisible: false,
            fwidth: 420,
            fheight: 300,


            fields: [
                {name: 'id_plan', type: 'numeric'},
                {name: 'id_plan_padre', type: 'numeric'},
                {name: 'id_gestion', type: 'numeric'},
                {name: 'nivel', type: 'numeric'},
                {name: 'nombre_plan', type: 'string'},
                {name: 'peso', type: 'numeric'},
                {name: 'aprobado', type: 'string'},
                {name: 'id_funcionarios', type: 'string'},
                {name: 'funcionarios', type: 'string'},
                {name: 'nombre_plan_padre', type: 'string'},
                {name: 'porcentaje_acumulado', type: 'string'},
                {name: 'porcentaje_restante', type: 'string'},
                {name: 'porcentaje_acumulado_aux', type: 'string'},
                {name: 'completado', type: 'numeric'},
                
                


            ],
            sortInfo: {
                field: 'id_plan',
                direction: 'ASC'
            },
            
            

            
            
            preparaMenu: function (n) {

                //console.log('observando los atributo de n', n.attributes)
                // llamada funcion clase padre
                if (n.attributes.aprobado == 0) {

                    this.tbar.items.get('b-edit-' + this.idContenedor).enable()
                    this.tbar.items.get('b-del-' + this.idContenedor).enable()
                    if (n.attributes.id_gestion > 0 || n.attributes.tipo_nodo == 'hijo' || n.attributes.tipo_nodo == 'raiz' || n.attributes.id == 'id') {
                        this.tbar.items.get('b-new-' + this.idContenedor).enable()
                    }
                    else {
                        this.tbar.items.get('b-new-' + this.idContenedor).disable()
                    }
                }
                if (n.attributes.aprobado == 1) {
                    this.tbar.items.get('b-new-' + this.idContenedor).disable()
                    this.tbar.items.get('b-edit-' + this.idContenedor).disable()
                    this.tbar.items.get('b-del-' + this.idContenedor).disable()
                }
            },


            liberaMenu: function () {

                //TODO cambie los datos de Linea plan
                Phx.vista.Plan.superclass.liberaMenu.call(this);
                this.tbar.items.get('b-new-' + this.idContenedor).disable()
                this.bloquearMenuPlan();
            },


            bloquearMenuPlan: function () {

                Ext.Ajax.request({
                    url: '../../sis_segintegralgestion/control/Plan/estadoGestion',
                    params: {
                        'id_gestion': this.cmbGestion.getValue(),
                    },
                    success: this.successBloquearMenu,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });

            },

            successBloquearMenu: function (s, m) {
                this.tbar.items.get('b-new-' + this.idContenedor).disable()

                //alert('ingrese al bloqueador de menus')
                estadoGestion = s.responseText.split('%');
                console.log('mostrando el vaor del estado de gestion', estadoGestion)
                if (estadoGestion[1] == '1') {
                    this.getBoton('btnDesAprobado').enable();
                    this.getBoton('btnAprobado').disable();
                }
                else if (estadoGestion[1] == '0') {
                    this.getBoton('btnDesAprobado').disable();
                    this.getBoton('btnAprobado').enable();
                } else {

                    if (m.params.id_gestion != "") {
                        this.tbar.items.get('b-new-' + this.idContenedor).enable()
                    }

                    this.getBoton('btnDesAprobado').disable();
                    this.getBoton('btnAprobado').disable();
                }
    

            },


        //Se edita la funcion parta que recarge el arbol del tabeast.
            onButtonAct: function () {
                this.root.reload();
                this.DesplegarArbol();
                //ponemos estos datos par que recarge el panel de la derecha, mandando el id_plan 0
                //Phx.CP.getPagina(this.idContenedor + '-east-' + 0).loaderTree.baseParams = {id_plan: 0};
                //Phx.CP.getPagina(this.idContenedor + '-east-' + 0).root.reload();
            },

            //funcion que corre cuando se elimina con exito
            successDel: function (resp) {
                Phx.CP.loadingHide();
                resp.argument.nodo.reload()
                this.onButtonAct()
                this.DesplegarArbol();
            },

			onButtonDel : function() {
				if (confirm('SE ELIMINARÁN LAS LíNEAS Y AVANCE PREVISTO RELACIONADOS AL PLAN SELECCIONADO  ¿ESTÁ SEGURO DE ELIMINAR EL REGISTRO?')) {
					Phx.CP.loadingShow();
					var params = {};
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
				}
			},
        }
    )
</script>

