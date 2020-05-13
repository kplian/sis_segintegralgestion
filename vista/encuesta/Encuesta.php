<?php
/**
 *@package pXP
 *@file Encuesta.php
 *@author  Gonzalo Sarmiento Sejas
 *@date 21-02-2013 15:04:03
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Encuesta=Ext.extend(Phx.arbGridInterfaz,{
        fwidth: '50%',
        constructor:function(config){
            this.maestro=config.maestro;
            Phx.vista.Encuesta.superclass.constructor.call(this,config);
            this.init();
            this.iniciarEvento();
            this.addHelp();
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
        Atributos:[
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_encuesta'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_encuesta_padre'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'pregunta'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'grupo'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'categoria'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'pregunta'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    name: 'tipo_nombre',
                    fieldLabel: 'Tipo Accion',
                    allowBlank: false,
                    emptyText:'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '50%',
                    store:['encuesta','grupo','categoria','pregunta']
                },
                type:'ComboBox',
                id_grupo:0,
                valorInicial: 'encuesta',
                form:true,
                grid: false
            },
            {
                config:{
                    name: 'nro_order',
                    fieldLabel: 'Nro',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 100
                },
                type:'TextField',
                filters:{pfiltro:'eta.nro_order',type:'numeric'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'nombre',
                    fieldLabel: 'Nombre',
                    allowBlank: false,
                    anchor: '50%',
                    gwidth: 400
                },
                type:'TextArea',
                filters:{pfiltro:'eta.nombre',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name:'tipo',
                    fieldLabel:'Tipo Evaluación',
                    allowBlank:false,
                    emptyText:'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '50%',
                    gwidth: 100,
                    store:new Ext.data.ArrayStore({
                        fields: ['ID', 'valor'],
                        data :	[

                            ['auto_evaluacion','Auto Evaluación'],
                            ['superior','Supervisor'],
                            ['medio','pares '],
                            ['inferior','Colaboradores']
                        ]

                    }),
                    valueField:'ID',
                    displayField:'valor'
                },
                type:'ComboBox',
                id_grupo:0,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'habilitado_categoria',
                    fieldLabel: 'Habilitar Catalogo',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer: function (value){
                        var checked = '',state='';
                        if(value){
                            checked = 'checked';
                            disabled = 'disabled';
                            return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:10px;width:10px;" type="checkbox" disabled {0}{1}></div>',checked,state);
                        }else{
                            return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:10px;width:10px;" type="checkbox" disabled {0}{1}></div>',checked,state);
                        }
                    }
                },
                type:'Checkbox',
                filters:{pfiltro:'eta.habilitado_categoria',type:'boolean'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config: {
                    name: 'peso_categoria',
                    fieldLabel: 'Peso',
                    allowBlank: true,
                    anchor: '40%',
                    gwidth: 100
                },
                type: 'NumberField',
                filters: {pfiltro: 'eta.peso_categoria',type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config:{
                    name: 'habilitado_pregunta',
                    fieldLabel: 'Habilitar Preguna',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer: function (value){
                        var checked = '',state='';
                        if(value){
                            checked = 'checked';
                            disabled = 'disabled';
                            return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:30px;width:30px;" type="checkbox" disabled {0}{1}></div>',checked,state);
                        }else{
                            return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:30px;width:30px;" type="checkbox" disabled {0}{1}></div>',checked,state);
                        }
                    }
                },
                type:'Checkbox',
                filters:{pfiltro:'eta.habilitado_pregunta',type:'boolean'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config: {
                    name: 'tipo_pregunta',
                    fieldLabel: 'Tipo Pregunta',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.ArrayStore({
                        id: 0,
                        fields: [
                            'tipo'
                        ],
                        data: [['Selección'], ['Texto']]
                    }),
                    valueField: 'tipo',
                    displayField: 'tipo',
                    gdisplayField: 'tipo_pregunta',
                    hiddenName: 'tipo',
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    pageSize: 15,
                    queryDelay: 1000,
                    width:150,
                    gwidth: 150,
                    minChars: 2,
                },
                type: 'ComboBox',
                id_grupo: 1,
                filters: {pfiltro: 'eta.tipo_pregunta',type: 'string'},
                grid: true,
                form: true
            },

            {
                config:{
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu1.cuenta',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
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
                filters:{pfiltro:'eta.fecha_reg',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:10
                },
                type:'TextField',
                filters:{pfiltro:'eta.estado_reg',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'obs_dba',
                    fieldLabel: 'Obs',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100
                },
                type:'TextField',
                filters:{pfiltro:'eta.obs_dba',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
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
                filters:{pfiltro:'eta.id_usuario_ai',type:'numeric'},
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
                filters:{pfiltro:'eta.usuario_ai',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu2.cuenta',type:'string'},
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
                filters:{pfiltro:'eta.fecha_mod',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            }
        ],
        title:'Encuesta',
        ActSave:'../../sis_segintegralgestion/control/Encuesta/insertarEncuesta',
        ActDel:'../../sis_segintegralgestion/control/Encuesta/eliminarEncuesta',
        ActList:'../../sis_segintegralgestion/control/Encuesta/listarEncuestaArb',
        id_store:'id_encuesta',
        textRoot:'Encuesta',
        id_nodo:'id_encuesta',
        id_nodo_p:'id_encuesta_padre',
        rootVisible: false,
        expanded: false,
        fields: [
            {name:'id_encuesta', type: 'numeric'},
            {name:'estado_reg', type: 'string'},
            {name:'obs_dba', type: 'string'},
            {name:'nro_order', type: 'TextField'},
            {name:'nombre', type: 'string'},
            {name:'grupo', type: 'string'},
            {name:'categoria', type: 'string'},
            {name:'habilitado_categoria', type: 'boolean'},
            {name:'peso_categoria', type: 'numeric'},
            {name:'pregunta', type: 'string'},
            {name:'habilitado_pregunta', type: 'boolean'},
            {name:'tipo_pregunta', type: 'string'},
            {name:'id_encuesta_padre', type: 'numeric'},
            {name:'id_usuario_reg', type: 'numeric'},
            {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name:'id_usuario_ai', type: 'numeric'},
            {name:'usuario_ai', type: 'string'},
            {name:'id_usuario_mod', type: 'numeric'},
            {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name:'usr_reg', type: 'string'},
            {name:'usr_mod', type: 'string'},
            {name:'tipo', type: 'string'},
            {name:'tipo_nombre', type: 'string'}

        ],
        sortInfo:{
            field: 'id_encuesta',
            direction: 'ASC'
        },
        bdel:true,
        bsave:false,

        preparaMenu:function(n){
            if(n.attributes.tipo_nodo == 'hijo' || n.attributes.tipo_nodo == 'raiz' || n.attributes.id == 'id'){
                this.tbar.items.get('b-new-'+this.idContenedor).enable()
            }
            else {
                this.tbar.items.get('b-new-'+this.idContenedor).disable()
            }
            console.log(n.attributes.tipo_nodo);
            Phx.vista.Encuesta.superclass.preparaMenu.call(this,n);
        },
        liberaMenu:function(n){
            Phx.vista.Encuesta.superclass.liberaMenu.call(this,n);
        },
        iniciarEvento:function () {
            this.ocultarComponente(this.Cmp.obs_dba);
            this.ocultarComponente(this.Cmp.habilitado_categoria);
            this.ocultarComponente(this.Cmp.peso_categoria);
            this.ocultarComponente(this.Cmp.habilitado_pregunta);
            this.ocultarComponente(this.Cmp.tipo_pregunta);
        },
        getTipoCuentaPadre: function(n) {
            var padre = n.parentNode;
            if (padre) {
                if (padre.attributes.id != 'id') {
                    return this.getTipoCuentaPadre(padre);
                } else {
                    return n.attributes.tipo_nombre;
                }
            } else {
                return undefined;
            }
        },
        onButtonNew:function(n){
            Phx.vista.Encuesta.superclass.onButtonNew.call(this);
            this.eventoForm();
        },

        onButtonEdit:function(n){

            Phx.vista.Encuesta.superclass.onButtonEdit.call(this);
            this.iniciarEvento();

            if (this.Cmp.tipo_nombre.getValue()  === 'encuesta'){
                this.mostrarComponente(this.Cmp.tipo);
                this.ocultarComponente(this.Cmp.habilitado_categoria);
                this.ocultarComponente(this.Cmp.peso_categoria);
                this.ocultarComponente(this.Cmp.habilitado_pregunta);
                this.ocultarComponente(this.Cmp.tipo_pregunta);
                this.mostrarComponente(this.Cmp.nro_order);

            }
            if (this.Cmp.tipo_nombre.getValue() === 'grupo'){
                this.ocultarComponente(this.Cmp.tipo);
                this.ocultarComponente(this.Cmp.habilitado_categoria);
                this.ocultarComponente(this.Cmp.peso_categoria);
                this.ocultarComponente(this.Cmp.habilitado_pregunta);
                this.ocultarComponente(this.Cmp.tipo_pregunta);
                this.mostrarComponente(this.Cmp.nro_order);

            }
            if (this.Cmp.tipo_nombre.getValue() === 'categoria'){
                this.ocultarComponente(this.Cmp.tipo);
                this.mostrarComponente(this.Cmp.habilitado_categoria);
                this.mostrarComponente(this.Cmp.peso_categoria);
                this.ocultarComponente(this.Cmp.habilitado_pregunta);
                this.ocultarComponente(this.Cmp.tipo_pregunta);
                this.mostrarComponente(this.Cmp.nro_order);

            }
            if (this.Cmp.tipo_nombre.getValue() === 'pregunta'){
                this.ocultarComponente(this.Cmp.tipo);
                this.ocultarComponente(this.Cmp.habilitado_categoria);
                this.ocultarComponente(this.Cmp.peso_categoria);
                this.mostrarComponente(this.Cmp.habilitado_pregunta);
                this.mostrarComponente(this.Cmp.tipo_pregunta);
                this.ocultarComponente(this.Cmp.nro_order);
                this.mostrarComponente(this.Cmp.nro_order);

            }
            this.eventoForm();
        },
        eventoForm:function () {
            this.Cmp.tipo_nombre.on('select', function( combo, record, index){
                if (record.data.field1 === 'encuesta'){
                    this.mostrarComponente(this.Cmp.tipo);
                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                    this.mostrarComponente(this.Cmp.nro_order);

                }
                if (record.data.field1 === 'grupo'){
                    this.ocultarComponente(this.Cmp.tipo);
                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                    this.mostrarComponente(this.Cmp.nro_order);

                }
                if (record.data.field1 === 'categoria'){
                    this.ocultarComponente(this.Cmp.tipo);
                    this.mostrarComponente(this.Cmp.habilitado_categoria);
                    this.mostrarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                    this.mostrarComponente(this.Cmp.nro_order);

                }
                if (record.data.field1 === 'pregunta'){
                    this.ocultarComponente(this.Cmp.tipo);
                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.mostrarComponente(this.Cmp.habilitado_pregunta);
                    this.mostrarComponente(this.Cmp.tipo_pregunta);
                    this.ocultarComponente(this.Cmp.nro_order);
                }
            },this);

        }
    })
</script>