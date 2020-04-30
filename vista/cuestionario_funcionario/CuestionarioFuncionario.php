<?php
/**
*@package pXP
*@file gen-CuestionarioFuncionario.php
*@author  (mguerra)
*@date 22-04-2020 06:47:37
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-04-2020				 (mguerra)				CREACION	

*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CuestionarioFuncionario=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.CuestionarioFuncionario.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}});
		this.addButton('btnImprimir', {
			text : 'Imprimir',
			iconCls : 'bprint',
			disabled : false,
			handler : this.imprimirCue,
			tooltip : '<b>Imprimir en cuestionario</b>'
		});
		
	},
	//
	onReloadPage: function (m) {     
	   this.maestro = m;	   
       this.store.baseParams = {id_cuestionario: this.maestro.id_cuestionario};
       this.load({params: {start: 0, limit: 50}});	
	},
	//
	loadValoresIniciales: function () {    	
       Phx.vista.CuestionarioFuncionario.superclass.loadValoresIniciales.call(this);        
       this.Cmp.id_cuestionario.setValue(this.maestro.id_cuestionario);
	},
	//
	imprimirCue: function() {		        
		var rec = this.sm.getSelected();
		var data = rec.data;
		console.log('--',data);
		if (data.sw_final=='si') {
			Phx.CP.loadingShow();
			Ext.Ajax.request({
				url : '../../sis_segintegralgestion/control/CuestionarioFuncionario/reporteCuestionario',
				params : {
					'id_cuestionario' : data.id_cuestionario,
					'id_funcionario' : data.id_funcionario,
				},
				success : this.successExport,
				failure : this.conexionFailure,
				timeout : this.timeout,
				scope : this
			});
		}else{
			alert('El funcionario no concluyó su evaluacion');
		}
	},
	//		
	Atributos:[
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
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cuestionario'
			},
			type:'Field',
			form:true 
		}, 
		{
			config: {
				name: 'id_funcionarios',
				fieldLabel: 'Funcionario',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
					id: 'id_funcionario',
					root: 'datos',
					sortInfo:{
						field: 'desc_person',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_funcionario','codigo','desc_person','ci','documento','telefono','celular','correo'],					
					remoteSort: true,
					baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}

				}),
				valueField: 'id_funcionario',
				displayField: 'desc_person',
				tpl:'<tpl for="."> <div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}</div> <p>{desc_person}</p> <p>CI:{ci}</p> </div></tpl>',
				gdisplayField: 'desc_person',
				hiddenName: 'id_funcionario',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 250,
				minChars: 2,
				enableMultiSelect: true,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_person']);
				}
			},
			type: 'AwesomeCombo',
			id_grupo: 0,
			filters: {pfiltro: 'PERSON.desc_person',type: 'string'},
			grid: true,
			form: true
		},
		{
			config:{
				name: 'sw_final',
				fieldLabel: 'Evaluo?',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'cuefun.sw_final',type:'string'},
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
            filters:{pfiltro:'cuefun.estado_reg',type:'string'},
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
				filters:{pfiltro:'cuefun.fecha_reg',type:'date'},
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
				filters:{pfiltro:'cuefun.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'cuefun.usuario_ai',type:'string'},
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
				filters:{pfiltro:'cuefun.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'cuefun.id_usuario_reg',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
	],
	tam_pag:50,	
	title:'Cuestionario Funcionario',
	ActSave:'../../sis_segintegralgestion/control/CuestionarioFuncionario/insertarCuestionarioFuncionario',
	ActDel:'../../sis_segintegralgestion/control/CuestionarioFuncionario/eliminarCuestionarioFuncionario',
	ActList:'../../sis_segintegralgestion/control/CuestionarioFuncionario/listarCuestionarioFuncionario',
	id_store:'id_cuestionario_funcionario',
	fields: [
		{name:'id_cuestionario_funcionario', type: 'numeric'},
		{name:'estado_reg', type: 'string'},		
		{name:'id_cuestionario', type: 'numeric'},
		{name:'id_funcionarios', type: 'numeric'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'desc_person', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'sw_final', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_cuestionario_funcionario',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bnew:false,
	bedit:false,
	bprint:false,
    tabeast:[
        {
            url:'../../../sis_segintegralgestion/vista/evaluados/Evaluados.php',
            title:'Evaluados',
            width:'50%',
            cls:'Evaluados'
        }
    ]
	}
)
</script>
		
		