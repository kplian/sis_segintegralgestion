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

        constructor:function(config){
            this.maestro=config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.Encuesta.superclass.constructor.call(this,config);
            this.init();
            this.iniciarEvento();

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
                    name: 'tipo_nombre'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    name: 'nro_order',
                    fieldLabel: 'Nro',
                    allowBlank: true,
                    anchor: '80%',
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
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 300
                },
                type:'TextField',
                filters:{pfiltro:'eta.nombre',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'tipo',
                    fieldLabel: 'Tipo',
                    allowBlank: false,
                    emptyText:'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width:100,
                    store:['auto_evaluacion','superior','medio','inferior','ninguno']
                },
                type:'ComboBox',
                id_grupo:0,
                valorInicial: 'ninguno',
                form:true,
                grid: true
            },
            {
                config:{
                    name: 'grupo',
                    fieldLabel: 'Grupo',
                    allowBlank: false,
                    emptyText:'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width:150,
                    gwidth:100,
                    store:['no','si']
                },
                type:'ComboBox',
                filters:{pfiltro:'eta.grupo',type:'string'},
                id_grupo:0,
                valorInicial: 'no',
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'categoria',
                    fieldLabel: 'Categoria',
                    allowBlank: false,
                    emptyText:'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width:150,
                    gwidth:100,
                    store:['no','si']
                },
                type:'ComboBox',
                filters:{pfiltro:'eta.categoria',type:'string'},
                id_grupo:0,
                valorInicial: 'no',
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'pregunta',
                    fieldLabel: 'Pregunta',
                    allowBlank: false,
                    emptyText:'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width:150,
                    gwidth:100,
                    store:['no','si']
                },
                type:'ComboBox',
                filters:{pfiltro:'eta.pregunta',type:'string'},
                id_grupo:0,
                valorInicial: 'no',
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
                    width: 50,
                    gwidth: 100,
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
                    fieldLabel: 'Tipo',
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
                    gdisplayField: 'tipo',
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
            this.Cmp.grupo.on('select', function( combo, record, index){
                if (record.data.field1 == 'si'){
                    this.Cmp.pregunta.reset();
                    this.Cmp.categoria.reset();
                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                }else{
                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                }
            },this);

            this.Cmp.categoria.on('select', function( combo, record, index){
                if (record.data.field1 == 'si'){
                    this.Cmp.pregunta.reset();
                    this.Cmp.grupo.reset();
                    this.mostrarComponente(this.Cmp.habilitado_categoria);
                    this.mostrarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                }else{

                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                }
            },this);

            this.Cmp.pregunta.on('select', function( combo, record, index){
                if (record.data.field1 == 'si'){
                    this.Cmp.grupo.reset();
                    this.Cmp.categoria.reset();
                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.mostrarComponente(this.Cmp.habilitado_pregunta);
                    this.mostrarComponente(this.Cmp.tipo_pregunta);
                }else{
                    this.ocultarComponente(this.Cmp.habilitado_categoria);
                    this.ocultarComponente(this.Cmp.peso_categoria);
                    this.ocultarComponente(this.Cmp.habilitado_pregunta);
                    this.ocultarComponente(this.Cmp.tipo_pregunta);
                }
            },this);
        },
        /*onButtonEdit:function(n) {
            Phx.vista.Encuesta.superclass.onButtonEdit.call(this);
        }*/
        })
</script>