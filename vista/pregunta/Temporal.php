<?php
/**
*@package pXP
*@file gen-Temporal.php
*@author  (mguerra)
*@date 24-04-2020 00:16:08
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				24-04-2020				 (mguerra)				CREACION	

*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
var v_maestro=null;
var v_id_usuario=null;
var v_id_funcionario=0;
var v_id_cuestionario_funcionario=0;
Phx.vista.Temporal=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
		this.initButtons = [this.contenidoImagen,this.cmbGestion];
		v_maestro = config;
    	//llama al constructor de la clase padre
		Phx.vista.Temporal.superclass.constructor.call(this,config);
		Phx.CP.loadingHide();
		this.init();
		this.grid.addListener('cellclick', this.oncellclick,this);
		this.grid.addListener('afteredit', this.onAfterEdit, this);
		Ext.getCmp('b-save-' + this.idContenedor).show();   					
		v_id_cuestionario_funcionario=v_maestro.data.id_cuestionario_funcionario;		
		v_id_usuario=v_maestro.data.id_usuario;
		this.store.baseParams = {id_cuestionario: v_maestro.data.id_cuestionario, id_usuario: v_id_usuario, id_funcionario: v_id_funcionario};
		this.load({params:{start:0, limit:this.tam_pag}});
		this.iniciarEventos(); 
		this.cmbGestion.on('select', function(){			
			if(this.validarFiltros()){				
				this.capturaFiltros();
			}
		},this);
	},
	//
	iniciarEventos: function(){
		this.CargarEncabezado();
		this.cmbGestion.store.baseParams = {id_cuestionario_funcionario: v_id_cuestionario_funcionario};
		this.cmbGestion.on('select',function (cmb, dat, index) {			
			this.sm.clearSelections();			
			this.store.baseParams = {id_funcionario: dat.data.id_funcionario, id_usuario: Phx.CP.config_ini.id_usuario};			
			v_id_funcionario=dat.data.id_funcionario;								
			this.store.reload();
		}, this);
		
	},
	//
	getDatosKeyPres:function(){
		return tipo_pregunta;
	},
	//
	contenidoImagen: new Ext.form.FormPanel({
		name: 'encabezado',
		id:'encabezado'
	}),
	//
	CargarEncabezado:function(){
		var encab='<br><div style="margin: 0 auto;  width: 400px; padding: 1em; border: 1px solid #CCC; border-radius: 1em;">';
		encab=encab+'<div> <label for="name"><b>CUESTIONARIO &nbsp;:</b></label> <label for="name">'+v_maestro.data.cuestionario+'</label> </div>';			
		encab=encab+'<div> <label for="name"><b>FUNCIONARIO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</b></label> <label for="name">'+v_maestro.data.usuario+'</label> </div>  </div><br>';			    			    	
		document.getElementById("encabezado").innerHTML = encab;		
	},
	//
	selectRecords : function(records, keepExisting){		
		if(!keepExisting){
			this.clearSelections();
		}
		var ds = this.grid.store,
			i = 0,
			len = records.length;
		for(; i < len; i++){
			this.selectRow(ds.indexOf(records[i]), true);
		}
	},
	//
	validarTipoInput:function(record){
		prueba.record.set('respuesta','');
	},
	//
	onButtonNew: function() {
		Phx.vista.Cuestionario.superclass.onButtonNew.call(this);		       
	},
	//
	onButtonEdit: function () {
		Phx.vista.Temporal.superclass.onButtonEdit.call(this);
	},
	//
	loadValoresIniciales: function () {
		Phx.vista.Temporal.superclass.loadValoresIniciales.call(this);		                                             
	},
	//
	validarFiltros : function() {
		if (this.cmbGestion.getValue()!= '') {					
			return true;
		} else {
			return false;
		}
	},
	//
	capturaFiltros : function(combo, record, index) {		
		this.desbloquearOrdenamientoGrid();				
		this.store.baseParams = {id_cuestionario: v_maestro.data.id_cuestionario, id_usuario: v_id_usuario};
		this.store.baseParams.id_funcionario = this.cmbGestion.getValue();
		this.load();
	},
	//
	onButtonAct : function() {
		if (!this.validarFiltros()) {
			alert('Especifique los filtros antes')
		}
		else{
			this.capturaFiltros();
		}
	},
	//
	onAfterEdit:function(prueba,x){		
		var columna=prueba.field;
		if(prueba.record.data['sw_nivel']==0 && prueba.record.data['tipo'] == 'Selección'){
			if(prueba.record.data['respuesta']=='Excelente'|| 
				  prueba.record.data['respuesta']=='Destacable' || 
				  prueba.record.data['respuesta']=='Acorde a la posición'|| 
				  prueba.record.data['respuesta']=='En desarrollo'||
				  prueba.record.data['respuesta']=='A desarrollo'){							 
			}
			else{
				prueba.record.set('respuesta',prueba.originalValue);
				//alert("");
			}
		}
	},
	//
	oncellclick : function(grid, rowIndex, columnIndex, e) {
		var record = this.store.getAt(rowIndex);
		var	fieldName = grid.getColumnModel().getDataIndex(columnIndex);

		if(record.data.tipo=='Selección'){
			this.Cmp.respuesta.store.events.expand=true;
			this.Cmp.respuesta.store.loadData(this.arrayStore.Selección) ;
		}
		if(record.data.tipo=='Texto'){			
			this.Cmp.respuesta.store.loadData(this.arrayStore.Texto) ;
		}
		if(record.data.tipo!='Selección' && record.data.tipo!='Texto' && fieldName=='respuesta'){
			alert("No clickee aqui");
		}
		if(record.data.sw_nivel==1){
			//alert("corregir que no se edite los campos de nivel 1");
		}
	},
	//
	onButtonSave:function(o){		
		var filas=this.store.getModifiedRecords();
		if(filas.length>0){	
			if(confirm("Está seguro de guardar los cambios?")){
				var data={};
				for(var i=0;i<filas.length;i++){
					data[i]=filas[i].data;
					data[i]._fila=this.store.indexOf(filas[i])+1
					this.agregarArgsExtraSubmit(filas[i].data);
					Ext.apply(data[i],this.argumentExtraSubmit);					
				}
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					url:this.ActSave,
					params:{	
						_tipo:'matriz',
						'row':String(Ext.util.JSON.encode(data)), 
						id_cuestionario: v_maestro.data.id_cuestionario,
						'id_funcionario':v_id_funcionario
					},			
					isUpload:this.fileUpload,
					success:this.successSaveFileUpload,				
					failure: this.conexionFailure,
					timeout:this.timeout,
					scope:this
				});
			}			
		}
	},
	//
	arrayStore :{
		'Selección':[
			['Excelente','Excelente'],
			['Destacable','Destacable'],
			['Acorde a la posición','Acorde a la posición'],
			['En desarrollo','En desarrollo'],		
			['A desarrollo','A desarrollo'],
		],			                	
		'Texto':[ ],			                                
	},
	//		
	Atributos:[
		{
			//configuracion del componente
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_temporal'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_pregunta'
			},
			type:'Field',
			form:true	
		},
		{
			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'tipo'
			},
			type: 'Field',
			form: true
		},
		{
			config: {
				name: 'pregunta',
				fieldLabel: 'Pregunta',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength: 500,
				renderer: function (value, p, record, rowIndex, colIndex){					
					var duplicar="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					var nivel = record.data.sw_nivel==null?0:record.data.sw_nivel;									
					if(record.data.sw_nivel == 1){
						p.style="background-color:#cce6ff; width: 500px;";
						return  String.format('<div style="vertical-align:middle;text-align:left;"> '+''+' <img src="../../../lib/imagenes/a_form_edit.png"> '+ record.data.pregunta+' </div>');
					}
					else{
						return  String.format('<div style="vertical-align:middle;text-align:left;"> '+duplicar+' <img src="../../../lib/imagenes/a_form.png"> '+ record.data.pregunta+' </div>');
					}					
				},
				gwidth: 500,
				sortable:false
			},
			type: 'TextField',
			filters: {pfiltro: 'pregunta', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true,
		},
		{
			config: {
				name: 'respuesta',
				fieldLabel: 'Respuesta',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.ArrayStore({
					id: 0,
					fields: [
						'respuesta'
					],
					data: [
						['Excelente'], 
						['Destacable'], 
						['Acorde a la posición'], 
						['En desarrollo'],
						['A desarrollo']						
					]
				}),
				valueField: 'respuesta',
				displayField: 'respuesta',
				gdisplayField: 'respuesta',
				hiddenName: 'respuesta',					
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'local',
				pageSize: 15,
				forceSelection:true,
				queryDelay: 1000,
				anchor: '80%',
				gwidth: 100,
				minChars: 2,
				renderer : function(value, p, record) {
					if(record.data.sw_nivel == 0){
						p.style="background-color:#cce6ff;";
					}
					if(record.data.respuesta =='' && record.data.sw_nivel !=1){
						return 'Doble click aqui' 
					}
					else{
						return String.format('{0}', record.data['respuesta']);
					}
				},
				gwidth: 200,
				sortable:false
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'respuesta',type: 'string'},
			grid: true,
			form: true,
			egrid:true,
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
				name: 'id_categoria'
			},
			type: 'Field',
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
				grid:false,
				form:false
		},
		{
			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'sw_nivel'
			},
			type: 'Field',
			form: true
		},
		{
			config: {
				name: 'desc_funcionario1',
				fieldLabel: 'Funcionario',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength: 500
			},
			type: 'TextField',
			filters: {pfiltro: 'desc_funcionario1', type: 'string'},
			id_grupo: 1,
			grid: false,
			form: false
		},
	],
	tam_pag:50,	
	title:'Temporal',
	timeout: Phx.CP.config_ini.timeout,
    conexionFailure: Phx.CP.conexionFailure,            
	ActSave:'../../sis_segintegralgestion/control/Temporal/insertarTemporal',	
	ActList:'../../sis_segintegralgestion/control/Temporal/listarTemporal',	
	id_store:'id_temporal',
	fields: [
		{name:'id_temporal', type: 'numeric'},
		{name:'id_pregunta', type: 'numeric'},
		{name:'pregunta', type: 'string'},
		{name:'respuesta', type: 'string'},
		{name:'id_cuestionario', type: 'numeric'},
		{name:'id_categoria', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'sw_nivel', type: 'numeric'},
		{name:'id_funcionario', type: 'numeric'},
	],
	sortInfo:{
		field: 'id_temporal',
		direction: 'ASC'
	},
	bdel:false,
	bsave:true, 
	bedit:false, 
	bnew:false,
	bexcel:false,
	//
	cmbGestion: new Ext.form.ComboBox({
		fieldLabel: 'Funcionario',
		allowBlank: true,
		emptyText: 'Seleccione funcionario...',
		store: new Ext.data.JsonStore(
		{
			url: '../../sis_segintegralgestion/control/Evaluados/listarEvaluados',
			id: 'id_funcionario',
			root: 'datos',
			sortInfo: {
				field: 'desc_funcionario1',
				direction: 'DESC'
			},
			totalProperty: 'total',
			fields: ['id_funcionario', 'desc_funcionario1'],
			// turn on remote sorting
			remoteSort: true,
			baseParams: {par_filtro: 'desc_funcionario1'}			
		}),
		valueField: 'id_funcionario',
		triggerAction: 'all',
		displayField: 'desc_funcionario1',
		hiddenName: 'id_funcionario',
		mode: 'remote',
		pageSize: 50,
		queryDelay: 500,
		listWidth: '280',
		width: 200
	}),


	}
)
</script>
		
		