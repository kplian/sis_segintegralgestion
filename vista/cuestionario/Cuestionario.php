<?php
/**
*@package pXP
*@file gen-Cuestionario.php
*@author  (mguerra)
*@date 21-04-2020 08:31:41
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020				 (mguerra)				CREACION	

*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Cuestionario=Ext.extend(Phx.gridInterfaz,{
	
	gruposBarraTareas:[{name:'borrador',title:'<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Borradores</h1>',grupo:0,height:0},
                       {name:'enviado',title:'<H1 align="center"><i class="fa fa-eye"></i> Enviados</h1>',grupo:1,height:0}],	
	actualizarSegunTab: function(name, indice){		
    	if(this.finCons){			
			this.store.baseParams.pes_estado = name;
			this.load({params:{start:0, limit:this.tam_pag}});
		}
    },

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Cuestionario.superclass.constructor.call(this,config);
		this.init();		
		this.load({params:{start:0, limit:this.tam_pag, pes_estado: 'borrador'}})		
		this.finCons = true;
		this.addButton('btnenviarCorreo', {
			text : 'Enviar Correo',
			iconCls : 'bprint',
			disabled : false,			
			handler : this.onEnviarCorreo,
			tooltip : '<b>Enviar Correo</b>'
		});
	},
	//
	onReloadPage: function (m) {
		this.maestro = m;        
	},

	onEnviarCorreo : function() {
        var rec = this.sm.getSelected();
        var data = rec.data;
        Ext.Msg.show({
            title:'Confirmación',
            scope: this,
            msg: 'Esta seguro de enviar una notifiacion? Cuestionario, Si esta de acuerdo presione el botón "Si"',
            buttons: Ext.Msg.YESNO,
            fn: function(id, value, opt) {
                if (id == 'yes') {
                    Phx.CP.loadingShow();
                    Ext.Ajax.request({
                        url : '../../sis_segintegralgestion/control/Cuestionario/enviarCorreo',
                        params : {
                            'id_cuestionario' : data.id_cuestionario
                        },
                        success : this.successActual,
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
    successActual:function(){
        Phx.CP.loadingHide();
        this.reload();
    },
	//
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cuestionario'
			},
			type:'Field',
			form:true 
		},
		/*{
			config:{
				name: 'cuestionario',
				fieldLabel: 'Cuestionario',
				allowBlank: true,
				anchor: '80%',
				gwidth: 150
			},
				type:'TextArea',
				filters:{pfiltro:'cue.cuestionario',type:'string'},
				id_grupo:1,
				grid:false,
				form:false
		},*/
        {
            config: {
                name: 'id_funcionarios',
                fieldLabel: 'Funcionario',
                allowBlank: false,
                emptyText: 'Elija una opción...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_formacion/control/Curso/listarFuncionarioCombos',
                    id: 'id_funcionario',
                    root: 'datos',
                    sortInfo: {
                        field: 'desc_person',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_funcionario', 'codigo', 'desc_person', 'ci'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'p.nombre_completo2'}
                }),
                valueField: 'id_funcionario',
                displayField: 'desc_person',
                tpl: '<tpl for="."> <div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}</div> <p>{desc_person}</p> <p>CI:{ci}</p> </div></tpl>',
                gdisplayField: 'funcionarios',
                hiddenName: 'id_funcionario',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 100,
                queryDelay: 1000,
                anchor: '60%',
                gwidth: 150,
                minChars: 2,
                enableMultiSelect: true,
                renderer: function (value, p, record) {
                    return String.format('{0}', record.data['funcionarios']);
                }
            },
            type: 'AwesomeCombo',
            id_grupo: 1,
            filters: {pfiltro: 'fun.funcionarios', type: 'string'},
            grid: false,
            form: true,
            bottom_filter: true
        },
        {
            config: {
                name: 'id_tipo_evalucion',
                fieldLabel: 'Encuesta',
                allowBlank: true,
                emptyText: 'Elija una opción...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_segintegralgestion/control/Encuesta/listarEncuesta',
                    id: 'id_encuesta',
                    root: 'datos',
                    sortInfo: {
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_encuesta', 'nombre', 'tipo'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'ten.nombre' ,raiz:'si'}
                }),
                valueField: 'id_encuesta',
                displayField: 'nombre',
                gdisplayField: 'desc_nombre',
                hiddenName: 'id_encuesta',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombre}</p>' +
                '<p><b>Tipo: </b>{tipo}</p></div></tpl>',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000,
                width:350,
                gwidth: 200,
                minChars: 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['desc_nombre']);
                }
            },
            type: 'ComboBox',
            id_grupo: 0,
            filters: {pfiltro: 'movtip.nombre',type: 'string'},
            grid: true,
            form: true
        },
		{
			config:{
				name: 'habilitar',
				fieldLabel: 'Habilitar',
				allowBlank: true,
				anchor: '80%',
				gwidth: 80,
				renderer: function (value){                    
					var checked = '',state='';					
                    if(value){
						checked = 'checked';
						disabled = 'disabled';
						return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:15px;width:15px;" type="checkbox" disabled {0}{1}></div>',checked,state);
                    }else{
						return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:15px;width:15px;" type="checkbox" disabled {0}{1}></div>',checked,state);
					}                
            	}
			},
				type:'Checkbox',
				filters:{pfiltro:'cue.habilitar',type:'string'},
				id_grupo:1,
				//egrid: true,
				grid:true,
				form:true
		},

		/*{
			config: {
				name: 'peso',
				fieldLabel: 'Peso',
				allowBlank: true,
				width: 50,
				gwidth: 50,				
			},
			type: 'NumberField',
			filters: {pfiltro: 'cue.peso',type: 'numeric'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config:{
				name: 'observacion',
				fieldLabel: 'Observaciones',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100
			},
				type:'TextArea',
				filters:{pfiltro:'cue.observacion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},*/
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
            filters:{pfiltro:'cue.estado_reg',type:'string'},
            id_grupo:1,
            grid:true,
            form:false
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
				filters:{pfiltro:'cue.fecha_reg',type:'date'},
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
				filters:{pfiltro:'cue.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'cue.usuario_ai',type:'string'},
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
				filters:{pfiltro:'cue.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'estado',
				fieldLabel: 'Estado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100
			},
			type:'Field',
			filters:{pfiltro:'cue.estado',type:'string'},
			id_grupo:1,
			grid:false,
			form:false
		},
	],
	tam_pag:50,	
	title:'Cuestionario',
	ActSave:'../../sis_segintegralgestion/control/Cuestionario/insertarCuestionario',
	ActDel:'../../sis_segintegralgestion/control/Cuestionario/eliminarCuestionario',
	ActList:'../../sis_segintegralgestion/control/Cuestionario/listarCuestionario',
	id_store:'id_cuestionario',
	fields: [
		{name:'id_cuestionario', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'cuestionario', type: 'string'},
		{name:'habilitar', type: 'string'},
		{name:'observacion', type: 'string'},		
		{name: 'tipo', type: 'string'},
		{name:'peso', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name: 'id_funcionarios', type: 'string'},
		{name: 'funcionarios', type: 'string'},
		{name: 'estado', type: 'string'},
        {name: 'id_tipo_evalucion', type: 'numeric'},
        {name: 'desc_nombre', type: 'string'}
		
	],
	sortInfo:{
		field: 'id_cuestionario',
		direction: 'ASC'
	},
	bnewGroups: [0],
	beditGroups: [0,1],
    bdelGroups:  [0],
    bactGroups:  [0,1,2],    
    bexcelGroups: [0,1,2],


    south:
		{
			url: '../../../sis_segintegralgestion/vista/cuestionario_funcionario/CuestionarioFuncionario.php',
			title: 'Evaluador(es)',
			height: '50%',
			cls: 'CuestionarioFuncionario'
		}	
}	
)
</script>
		
		