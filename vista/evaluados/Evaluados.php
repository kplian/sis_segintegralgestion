<?php
/**
*@package pXP
*@file gen-Evaluados.php
*@author  (admin.miguel)
*@date 28-04-2020 01:32:33
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				28-04-2020				 (admin.miguel)				CREACION	

*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Evaluados=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Evaluados.superclass.constructor.call(this,config);
		this.init();
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_evaluados'
			},
			type:'Field',
			form:true 
		},
        {
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cuestionario_funcionario'
			},
			type:'Field',
			form:true
		},
        {
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_funcionario'
			},
			type:'Field',
			form:true
		},
        {
            config:{
                name: 'evaluar',
                fieldLabel: 'Evaluar',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:10
            },
            type:'TextField',
            filters:{pfiltro:'evs.evaluar',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'desc_funcionario1',
                fieldLabel: 'Funcionario',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200
            },
            type:'TextField',
            filters:{pfiltro:'fun.desc_funcionario1',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'nombre_unidad',
                fieldLabel: 'Cargo',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200
            },
            type:'TextField',
            filters:{pfiltro:'fun.nombre_unidad',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
		/*{
			config: {
				name: 'id_funcionario',
				fieldLabel: 'Funcionario',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_/control/Clase/Metodo',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_',
				displayField: 'nombre',
				gdisplayField: 'desc_',
				hiddenName: 'id_funcionario',
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
					return String.format('{0}', record.data['desc_']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: true,
			form: true
		},*/
        {
            config:{
                name: 'obs_dba',
                fieldLabel: 'Obs',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100
            },
            type:'TextField',
            filters:{pfiltro:'evs.obs_dba',type:'string'},
            id_grupo:1,
            grid:true,
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
            filters:{pfiltro:'evs.estado_reg',type:'string'},
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
				filters:{pfiltro:'evs.fecha_reg',type:'date'},
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
				filters:{pfiltro:'evs.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'evs.usuario_ai',type:'string'},
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
				filters:{pfiltro:'evs.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Evaluados',
	ActSave:'../../sis_segintegralgestion/control/Evaluados/insertarEvaluados',
	ActDel:'../../sis_segintegralgestion/control/Evaluados/eliminarEvaluados',
	ActList:'../../sis_segintegralgestion/control/Evaluados/listarEvaluados',
	id_store:'id_evaluados',
	fields: [
		{name:'id_evaluados', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'obs_dba', type: 'string'},
		{name:'id_cuestionario_funcionario', type: 'numeric'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'evaluar', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
        {name:'desc_funcionario1', type: 'string'},
		{name:'nombre_unidad', type: 'string'}
		
	],
	sortInfo:{
		field: 'id_evaluados',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
    bedit:false,
    bnew:false,
    onReloadPage:function(m){
        this.maestro = m;
        this.store.baseParams = {id_cuestionario_funcionario: this.maestro.id_cuestionario_funcionario};
        this.load({params:{start:0, limit:50}});
    },
    loadValoresIniciales: function () {
        Phx.vista.Evaluados.superclass.loadValoresIniciales.call(this);
        this.Cmp.id_cuestionario_funcionario.setValue(this.maestro.id_cuestionario_funcionario);
    }
	}
)
</script>
		
		