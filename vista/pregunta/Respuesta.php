<?php 
/**

 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
#12 			13/05/2020			manuel guerra			agregar campo de nombre de evaluacion
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>

    Phx.vista.Respuesta = Ext.extend(Phx.gridInterfaz, {   

        gruposBarraTareas:[{name:'proceso',title:'<H1 align="center"><i class="fa fa-thumbs-o-down"></i> En Proceso</h1>',grupo:0,height:0},
                            {name:'finalizado',title:'<H1 align="center"><i class="fa fa-eye"></i> Finalizados</h1>',grupo:1,height:0}],	
        actualizarSegunTab: function(name, indice){		
            if(this.finCons){			
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },

        constructor: function (config) {
            this.maestro = config.maestro;
            //llama al constructor de la clase padre

            Phx.vista.Respuesta.superclass.constructor.call(this, config);
            this.init();
            this.store.baseParams = {id_usuario: Phx.CP.config_ini.id_usuario};
            //this.load({params: {start: 0, limit: this.tam_pag, pes_estado: 'proceso'}});   
            this.load({params: {start: 0, limit: this.tam_pag, pes_estado: 'proceso'}});
           //
            this.addButton('btnRespCue', {
                text: 'Responder cuestionario',
                iconCls: 'bchecklist',
                disabled: false,
                handler: this.RespCuestionario,
                tooltip: '<b>Mostrara el formulario que tendra que responder</b>'
            });	

            this.addButton('btnFinalizar', {
                text : 'Finalizar',
                iconCls : 'bprint',
                disabled : false,			
                handler : this.onFinalizarCuestionario,
                tooltip : '<b>Finalizar Cuestionario</b>'
            });
            this.addHelp();
            // this.iniciarEventos();

            this.finCons = true; 
                   
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
                    'margin-right': '10px',
                    float: 'right'
                },
                html: '<div style="display: inline-flex">' +
                '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/loguito360-01.png" width="300px"></div>'
            });
        },
       //
        onReloadPage: function (m) {
            this.maestro = m;        
        },
        //
        RespCuestionario: function (record) {
            this.GenerarForm('new', this);             		        
        },
        //
        GenerarForm: function () {  	  	              	        	
            var me = this;
            var verBotonGuardarEnEvaluacion='Si';
            /*if(me.sm.selections.items[0].data.usuario=='admin'){
                verBotonGuardarEnEvaluacion='No';
            }
            else{
                verBotonGuardarEnEvaluacion='Si';
            }    */
            console.log('___>',me.sm.selections.items[0].data);
            if(me.sm.selections.items.length==1){				
                Phx.CP.loadingShow();				
                me.objSolForm = Phx.CP.loadWindows('../../../sis_segintegralgestion/vista/pregunta/Temporal.php',
                    'Cuestionario-Funcionario',
                    {
                        modal: true,
                        width: '85%',
                        frame: true,
                        border: true
                    }, 
                    {
                        data: 
                        {
                            'id_cuestionario': me.sm.selections.items[0].data.id_cuestionario,
                            'cuestionario': me.sm.selections.items[0].data.cuestionario,                           
                            'id_usuario': me.sm.selections.items[0].data.id_usuario_reg,
                            'usuario': me.sm.selections.items[0].data.desc_person,
                            'id_cuestionario_funcionario': me.sm.selections.items[0].data.id_cuestionario_funcionario,
                            'verBotonGuardar':verBotonGuardarEnEvaluacion
                        }
                    },
                    this.idContenedor,
                    'Temporal',
                );
            }
            else {
                alert('Seleccione un cuestionario para evaluar');
            }
        }, 	
        //
        onFinalizarCuestionario : function() {
            var rec = this.sm.getSelected();
            var data = rec.data;
            Ext.Msg.show({
                title:'Confirmación',
                scope: this,
                msg: '¿Esta seguro de finalizar el cuestionario?, Si esta de acuerdo presione el botón "Si"',
                buttons: Ext.Msg.YESNO,
                fn: function(id, value, opt) {
                    if (id == 'yes') {
                        Phx.CP.loadingShow();
                        Ext.Ajax.request({
                            url : '../../sis_segintegralgestion/control/Cuestionario/finCuestionario',
                            params : {
                                'id_cuestionario' : data.id_cuestionario
                            },
                            success : this.successActualizar,
                            failure : this.conexionFailure,
                            timeout : this.timeout,
                            scope : this
                        });
                    } else {
                        opt.hide;
                    }
                },
                animEl: 'elId',
                icon: Ext.MessageBox.WARNING
            }, this);
        },
        //
        successActual:function(resp){
            Phx.CP.loadingHide();
			resp.argument.wizard.panel.destroy()
			this.reload();
        }, 
        //
        successActualizar:function(){
            Phx.CP.loadingHide();			
			this.reload();
        }, 
        //
        preparaMenu: function(n) {			
            var data = this.getSelectedData();
            var tb = this.tbar;
            Phx.vista.Respuesta.superclass.preparaMenu.call(this, n);	
            if (data['estado']== 'borrador'){
                this.getBoton('btnenviarCorreo').disable();
                //this.getBoton('btnenviarCorreo').disable();
            }
            if (data['estado']== 'proceso'){
                this.getBoton('btnFinalizar').enable();
            }			
            return tb
        },
        //			
       /* liberaMenu: function() {
            var tb = Phx.vista.Respuesta.superclass.liberaMenu.call(this);
            if (data['estado']== 'borrador'){
                this.getBoton('btnenviarCorreo').disable();
            }
            if (data['estado']== 'proceso'){
                this.getBoton('btnFinalizar').enable();
            }	
            return tb
	    },*/       
        //
        Atributos: [
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_cuestionario_funcionario'
                },
                type: 'Field',
                form: true
            },                        
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_cuestionario'
                },
                type: 'Field',
                form: true
            },
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_funcionario'
                },
                type: 'Field',
                form: true
            },
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_usuario_reg'
                },
                type: 'Field',
                form: true
            },//#12
            {
                config: {
                    name: 'nombre',
                    fieldLabel: 'Cuestionario',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 400,                    
                },
                type: 'TextField',
                filters: {pfiltro: 'en.nombre', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'tipo',
                    fieldLabel: 'Tipo',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 500
                },
                type: 'TextField',
                filters: {pfiltro: 'en.tipo', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'cuestionario',
                    fieldLabel: 'Cuestionario',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 500
                },
                type: 'TextField',
                filters: {pfiltro: 'cue.cuestionario', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'desc_person',
                    fieldLabel: 'Funcionario',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 500
                },
                type: 'TextField',
                filters: {pfiltro: 'desc_person', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'codigo',
                    fieldLabel: 'Codigo',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 500
                },
                type: 'TextField',
                filters: {pfiltro: 'codigo', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },            
            {
                config: {
                    name: 'cuenta',
                    fieldLabel: 'Cuenta',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'TextField',
                filters: {pfiltro: 'cur.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },                    
            {
                config: {
                    fieldLabel: 'Estado',
                    labelSeparator: 'Estado',                    
                    name: 'estado_reg'
                },
                type: 'Field',
                form: true,
                grid: true
            },              
            {
                config:{
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                                format: 'd/m/Y', 
                                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                    type:'DateField',
                    filters:{pfiltro:'fecha_reg',type:'date'},
                    id_grupo:1,
                    grid:true,
                    form:false
            },
            {
                config:{
                    name: 'id_usuario_ai',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                    type:'Field',
                    filters:{pfiltro:'id_usuario_ai',type:'numeric'},
                    id_grupo:1,
                    grid:false,
                    form:false
            },
            {
                config:{
                    name: 'usuario_ai',
                    fieldLabel: 'Funcionaro AI',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:300
                },
                    type:'TextField',
                    filters:{pfiltro:'usuario_ai',type:'string'},
                    id_grupo:1,
                    grid:true,
                    form:false
            },
            {
                config:{
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                                format: 'd/m/Y', 
                                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                    type:'DateField',
                    filters:{pfiltro:'fecha_mod',type:'date'},
                    id_grupo:1,
                    grid:true,
                    form:false
            },
            {
                config:{
                    name: 'sw_final',
                    fieldLabel: '¿Evaluo?',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                    type:'Field',
                    filters:{pfiltro:'sw_final',type:'string'},
                    id_grupo:1,
                    grid:true,
                    form:false
            },
            
        ],
        tam_pag: 50,
        argumentSave: {},
        timeout: Phx.CP.config_ini.timeout,
        conexionFailure: Phx.CP.conexionFailure,
        title: 'Cuestionarios',
        ActList: '../../sis_segintegralgestion/control/CuestionarioFuncionario/listarCuestionarioEvaluacion',
        id_store: 'id_cuestionario_funcionario',
        fields: [
            {name: 'id_cuestionario_funcionario', type: 'numeric'},
            {name: 'estado_reg', type: 'string'},
            {name: 'id_cuestionario', type: 'numeric'},
            {name: 'id_funcionario', type: 'numeric'},
            {name: 'id_usuario_reg', type: 'numeric'},            
            {name: 'cuestionario', type: 'string'},
            {name: 'fecha_reg',  type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name: 'id_usuario_ai', type: 'numeric'},
            {name: 'usuario_ai', type: 'string'},
            {name: 'fecha_mod',  type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name: 'desc_person', type: 'string'},
            {name: 'codigo', type: 'string'},
            {name: 'cuenta', type: 'string'},
            {name: 'sw_final', type: 'string'},           
            {name: 'nombre', type: 'string'},   //#12
            {name: 'tipo', type: 'string'},   //#12
        ],
        sortInfo: {
            field: 'id_cuestionario_funcionario',
            direction: 'ASC'
        },
        bnewGroups: [],
        beditGroups: [],
        bdelGroups:  [],
        bactGroups:  [0,1],    
        bexcelGroups: [0,1],
    }
)
</script>