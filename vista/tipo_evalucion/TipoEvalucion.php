<?php
/**
*@package pXP
*@file gen-TipoEvalucion.php
*@author  (admin.miguel)
*@date 27-04-2020 14:34:48
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				27-04-2020				 (admin.miguel)				CREACION	

*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.TipoEvalucion=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.TipoEvalucion.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_evalucion'
			},
			type:'Field',
			form:true 
		},

		{
			config:{
				name: 'codigo',
				fieldLabel: 'Codigo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100
			},
				type:'TextField',
				filters:{pfiltro:'ten.codigo',type:'string'},
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
				gwidth: 200
			},
				type:'TextField',
				filters:{pfiltro:'ten.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config: {
				name: 'id_nivel_organizacional',
				fieldLabel: 'Nivel Organizacional',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_organigrama/control/NivelOrganizacional/listarNivelOrganizacional',
					id: 'id_nivel_organizacional',
					root: 'datos',
					sortInfo: {
						field: 'numero_nivel',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_nivel_organizacional', 'nombre_nivel', 'numero_nivel'],
					remoteSort: true,
					baseParams: {par_filtro: 'nivorg.nombre_nivel'}
				}),
				valueField: 'id_nivel_organizacional',
				displayField: 'nombre_nivel',
				gdisplayField: 'desc_nombre_nivel',
				hiddenName: 'id_nivel_organizacional',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombre_nivel} -> nivel ({numero_nivel})</p></div></tpl>',
                forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
                width:300,
				gwidth: 200,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_nombre_nivel']);
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
                name: 'tipo',
                fieldLabel: 'Tipo',
                allowBlank: false,
                emptyText:'Tipo...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                width:150,
                store:['auto_evaluacion','superior','medio','inferior']
            },
            type:'ComboBox',
            id_grupo:0,
            valorInicial: 'ninguno',
            form:true,
            grid: true
        },
        {
            config:{
                name: 'obs_dba',
                fieldLabel: 'Obs Dba',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150
            },
            type:'TextField',
            filters:{pfiltro:'ten.obs_dba',type:'string'},
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
				filters:{pfiltro:'ten.fecha_reg',type:'date'},
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
				filters:{pfiltro:'ten.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'ten.usuario_ai',type:'string'},
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
				filters:{pfiltro:'ten.fecha_mod',type:'date'},
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
            filters:{pfiltro:'ten.estado_reg',type:'string'},
            id_grupo:1,
            grid:true,
            form:false
        }
	],
	tam_pag:50,	
	title:'Tipo Evalucion',
	ActSave:'../../sis_segintegralgestion/control/TipoEvalucion/insertarTipoEvalucion',
	ActDel:'../../sis_segintegralgestion/control/TipoEvalucion/eliminarTipoEvalucion',
	ActList:'../../sis_segintegralgestion/control/TipoEvalucion/listarTipoEvalucion',
	id_store:'id_tipo_evalucion',
	fields: [
		{name:'id_tipo_evalucion', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'obs_dba', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'id_nivel_organizacional', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_nombre_nivel', type: 'string'},
        {name:'tipo', type: 'string'}
	],
	sortInfo:{
		field: 'id_tipo_evalucion',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false
	}
)
</script>
		
		