<?php
/**
*@package pXP
*@file gen-Pregunta.php
*@author  (mguerra)
*@date 21-04-2020 08:17:42
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-04-2020				 (mguerra)				CREACION	

*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Pregunta=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Pregunta.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_pregunta'
			},
			type:'Field',
			form:true 
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
				filters:{pfiltro:'pre.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'pregunta',
				fieldLabel: 'Pregunta',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
			},
				type:'TextArea',
				filters:{pfiltro:'pre.pregunta',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'habilitar',
				fieldLabel: 'Habilitar',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				renderer: function (value){                    
					var checked = '',state='';					
                    if(value == 'true'){
						checked = 'checked';
						disabled = 'disabled';
						return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:30px;width:30px;" type="checkbox" disabled {0}{1}></div>',checked,state);
                    }else{
						return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:30px;width:30px;" type="checkbox" disabled {0}{1}></div>',checked,state);
					}                
            	}
			},
				type:'Checkbox',
				filters:{pfiltro:'pre.habilitar',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config: {
				name: 'tipo',
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
				anchor: '80%',
				gwidth: 150,
				minChars: 2,
			},
			type: 'ComboBox',
			id_grupo: 1,
			filters: {pfiltro: 'pre.tipo',type: 'string'},
			grid: true,
			form: true
        },
		{
			config:{
				name: 'resultado',
				fieldLabel: 'Resultado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:655362
			},
				type:'NumberField',
				filters:{pfiltro:'pre.resultado',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},		
		{
			config: {
				name: 'id_categoria',
				fieldLabel: 'Categoria',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_segintegralgestion/control/Categoria/listarCategoria',
					id: 'id_categoria',
					root: 'datos',
					sortInfo: {
						field: 'categoria',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_categoria', 'categoria'],
					remoteSort: true,
					baseParams: {par_filtro: 'cat.categoria'}
				}),
				valueField: 'id_categoria',
				displayField: 'categoria',
				gdisplayField: 'categoria',
				hiddenName: 'id_categoria',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['categoria']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'cat.categoria',type: 'string'},
			grid: true,
			form: true
		},
		{
			config:{
				name: 'observacion',
				fieldLabel: 'Observacion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
			},
				type:'TextArea',
				filters:{pfiltro:'pre.observacion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
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
				filters:{pfiltro:'pre.fecha_reg',type:'date'},
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
				filters:{pfiltro:'pre.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'pre.usuario_ai',type:'string'},
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
				filters:{pfiltro:'pre.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Pregunta',
	ActSave:'../../sis_segintegralgestion/control/Pregunta/insertarPregunta',
	ActDel:'../../sis_segintegralgestion/control/Pregunta/eliminarPregunta',
	ActList:'../../sis_segintegralgestion/control/Pregunta/listarPregunta',
	id_store:'id_pregunta',
	fields: [
		{name:'id_pregunta', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'pregunta', type: 'string'},
		{name:'habilitar', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'resultado', type: 'numeric'},
		{name:'observacion', type: 'string'},
		{name:'id_categoria', type: 'numeric'},
		{name:'categoria', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_pregunta',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		