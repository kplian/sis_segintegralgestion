<?php
/**
*@package pXP
*@file gen-IndicadorValor.php
*@author  (admin)
*@date 21-11-2016 14:01:15
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
var semaforo1=null;
Phx.vista.IndicadorValor=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.IndicadorValor.superclass.constructor.call(this,config);
		this.init();

        
		this.grid.addListener('cellclick', this.oncellclick,this);
		this.grid.addListener('afteredit', this.onAfterEdit, this);
		//para que no cargue cuando es detalle
		//this.load({params:{start:0, limit:this.tam_pag}})

        this.finCons = true;
        
	},

	/*oncellclick : function(grid, rowIndex, columnIndex, e) {
        var record = this.store.getAt(rowIndex),
            fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
   
        if(fieldName == 'no_reporta') {
            var sw1 =record.data['no_reporta'];
            sw1= record.data['no_reporta']=='f'?'t':'f';
            record.set('no_reporta', sw1);
        }
        record.set('semaforo1', "");
        console.log("record juan " ,record.store);
        record.store.events.clear();
        
    },*/

	loadValoresIniciales: function () {
	    	
	    	//detalle
           Phx.vista.IndicadorValor.superclass.loadValoresIniciales.call(this);
            //
           this.Cmp.id_indicador.setValue(this.maestro.id_indicador);


    },

        
         	
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_indicador_valor'
			},
			type:'Field',
			form:true 
		},
	    {
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_indicador'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'fecha',
				fieldLabel: 'fecha',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'inva.fecha',type:'date'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
	    {
			config:{
				name: 'hito',
				fieldLabel: 'hito',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'inva.hito',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
		{
			config:{
				name: 'semaforo1',
				fieldLabel: 'semaforo1',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50,

			},
				type: 'TextField',
				filters:{pfiltro:'inva.semaforo1',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
		{
			config:{
				name: 'semaforo2',
				fieldLabel: 'semaforo2',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50,

				//maskRe: validarInput,
				//regex: validarInput

			},
				type:'TextField',
				filters:{pfiltro:'inva.semaforo2',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
		{
			config:{
				name: 'semaforo3',
				fieldLabel: 'semaforo3',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'inva.semaforo3',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
		{
			config:{
				name: 'semaforo4',
				fieldLabel: 'semaforo4',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'inva.semaforo4',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
		{
			config:{
				name: 'semaforo5',
				fieldLabel: 'semaforo5',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'inva.semaforo5',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
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
				filters:{pfiltro:'inva.estado_reg',type:'string'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
                config: {
                    name: 'valor',
                    fieldLabel: 'Valor',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    //format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value : ''
                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'inva.valor', type: 'string'},
                id_grupo: 1,
                grid: true,
                egrid:true,
                form: true
        },
		{
			config:{
				name: 'justificacion',
				fieldLabel: 'justificacion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:1000
			},
				type:'TextField',
				filters:{pfiltro:'inva.justificacion',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
	    {
	            config: {
	                   name: 'no_reporta',
	                   fieldLabel: 'no_reporta',
	                   allowBlank: true,
	                   emptyText: 'Elija una opción...',
	                   
				       store: new Ext.data.ArrayStore({
				                id: 0,
				                fields: [
				                    'no_reporta'
				                ],
				                data: [['Reporta'], ['No reporta'], ['No se hizo']]
				       }),
				        
	                   valueField: 'no_reporta',
	                   displayField: 'no_reporta',
	                   gdisplayField: 'no_reporta',
	                   hiddenName: 'no_reporta',
	                   //forceSelection: true,
	                   typeAhead: false,
	                   triggerAction: 'all',
	                   lazyRender: true,
	                   mode: 'local',
	                   pageSize: 15,
	                   queryDelay: 1000,
	                   anchor: '80%',
	                   gwidth: 150,
	                   minChars: 2,
	                   renderer : function(value, p, record) {
	                          return String.format('{0}', record.data['no_reporta']);
	                   }
	            },
	            type: 'ComboBox',
	            id_grupo: 0,
	            filters: {pfiltro: 'inva.no_reporta',type: 'string'},
	            grid: true,
	            form: true,
	            egrid:true,
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
				filters:{pfiltro:'inva.fecha_reg',type:'date'},
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
				filters:{pfiltro:'inva.usuario_ai',type:'string'},
				id_grupo:1,
				grid:false,
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
				grid:false,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'inva.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'inva.fecha_mod',type:'date'},
				id_grupo:1,
				grid:false,
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
				grid:false,
				form:false
		}

	],
	tam_pag:50,	
	title:'Indicador valor',
	ActSave:'../../sis_segintegralgestion/control/IndicadorValor/insertarIndicadorValor',
	ActDel:'../../sis_segintegralgestion/control/IndicadorValor/eliminarIndicadorValor',
	ActList:'../../sis_segintegralgestion/control/IndicadorValor/listarIndicadorValor',
	id_store:'id_indicador_valor',
	fields: [
		{name:'id_indicador_valor', type: 'numeric'},
		{name:'id_indicador', type: 'numeric'},
		{name:'semaforo3', type: 'string'},
		{name:'semaforo5', type: 'string'},
		{name:'no_reporta', type: 'string'},
		{name:'semaforo4', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'semaforo2', type: 'string'},
		{name:'valor', type: 'string'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'hito', type: 'string'},
		{name:'semaforo1', type: 'string'},
		{name:'justificacion', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
		{name:'semaforo', type: 'string'},
		{name:'varchar', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_indicador_valor',
		direction: 'ASC'
	},
	
	    bedit:false,
	    bdel:false,
	    bsave:true,
	    bnew:false,
	    

	onButtonEdit: function () {

     //   Phx.vista.IndicadorValor.superclass.onButtonEdit.call(this);
     //   this.ocultarComponente(this.Cmp.valor);
     //   this.ocultarComponente(this.Cmp.justificacion);
     //   this.ocultarComponente(this.Cmp.no_reporta);
    }
	
	
}

        
	
)
</script>
		
		