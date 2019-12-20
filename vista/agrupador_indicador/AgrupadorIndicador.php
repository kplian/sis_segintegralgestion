<?php
/**
*@package pXP
*@file gen-AgrupadorIndicador.php
*@author  (admin)
*@date 08-06-2017 10:36:34
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
var vnivel=null;
var v_this=null;
Phx.vista.AgrupadorIndicador=Ext.extend(Phx.gridInterfaz,
	{
		////
		constructor:function(config){
			this.maestro=config.maestro;
			this.initButtons = [this.contenidoImagen];
	    	//llama al constructor de la clase padre
			Phx.vista.AgrupadorIndicador.superclass.constructor.call(this,config);
			this.init();
			this.load({params:{start:0, limit:this.tam_pag}});
			 
            this.store.baseParams = {id_agrupador: 0, id_periodo: 0};
            this.load({params: {start: 0, limit: 50}});
           
			   
            Ext.getCmp('b-new-' + this.idContenedor).hide()	
            Ext.getCmp('b-edit-' + this.idContenedor).hide()	
            this.grid.addListener('cellclick', this.oncellclick,this);            	
		},
        contenidoImagen: new Ext.form.FormPanel({
          name: 'imagen',
          id:'imagen'
         //inputType: 'hidden',
        }),
       	oncellclick : function(grid, rowIndex, columnIndex, e) {
		        var record = this.store.getAt(rowIndex),
		            fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
		   
		   if(record.data['semaforo1']==''){
		   	    document.getElementById("imagen").innerHTML = '';
		   }
		   else{
                if (record.data['semaforo']=='Simple' && record.data['comparacion']=='Asc') {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/SIMPLE ASC.png" width="120px">';
                }
                if (record.data['semaforo']=='Simple' && record.data['comparacion']=='Desc') {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/SIMPLE DESC.png" width="120px">';
                }
                if (record.data['semaforo']=='Compuesto' && record.data['comparacion']=='Asc') {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/COMPUESTO ASC.png" width="200px">';
                }
                if (record.data['semaforo']=='Compuesto' && record.data['comparacion']=='Desc') {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/COMPUESTO DESC.png" width="200px">';
                }
		   }


        
        },
		//funciones
		onButtonNew: function () {            
			Phx.vista.AgrupadorIndicador.superclass.onButtonNew.call(this);  
			this.getComponente('nombre_padre').setValue(this.maestro.nombre); 
        	//this.getComponente('id_agrupador_indicador_padre').setValue(this.maestro.id_agrupador);    
        	this.Cmp.id_indicador.store.setBaseParam('id_gestion',this.maestro.id_gestion);
	        this.Cmp.id_indicador.modificado = true;  
        },
        successSave: function (resp) {        	
            Phx.vista.AgrupadorIndicador.superclass.successSave.call(this, resp);
            Phx.CP.getPagina(this.idContenedorPadre).root.reload();
            Phx.CP.getPagina(this.idContenedorPadre).treePanel.expandAll();	
        },  
        
		//carga la grilla
		onReloadPage: function (m) {
           this.maestro = m;
           vnivel=this.maestro.nivel;
           v_this=this;

           
           
           this.store.baseParams = {id_agrupador: this.maestro.id_agrupador, id_periodo: this.maestro.id_periodo};
           this.load({params: {start: 0, limit: 50}});
           //Ext.getCmp('b-new-' + this.idContenedor).show()	
           if(this.maestro.nivel==2 && (this.maestro.aprobado+'')!='1' ){  
				Ext.getCmp('b-new-' + this.idContenedor).show()	
				Ext.getCmp('b-edit-' + this.idContenedor).show()	
				Ext.getCmp('b-del-'+ this.idContenedor).show()	
           }else{
           		Ext.getCmp('b-new-' + this.idContenedor).hide()	
				Ext.getCmp('b-edit-' + this.idContenedor).hide()		
          	    Ext.getCmp('b-del-'+ this.idContenedor).hide()
           }
           

           document.getElementById("imagen").innerHTML = '';
           
           console.log("ver datos de padre ",this.maestro)
           
       	   this.setColumnHeader('semaforo1',String.format('<div style="background-color: {0};"> {1}</div>','#F9CAC4', 'semaforo1'));
       	   this.setColumnHeader('semaforo2',String.format('<div style="background-color: {0};"> {1}</div>','#F9F7C4', 'semaforo2'));
       	   this.setColumnHeader('semaforo3',String.format('<div style="background-color: {0};"> {1}</div>','#CEF9C4', 'semaforo3'));
           
        },
        //carga valores, oculta/muestra botones
        loadValoresIniciales: function () {
            Phx.vista.AgrupadorIndicador.superclass.loadValoresIniciales.call(this);
            this.Cmp.id_agrupador.setValue(this.maestro.id_agrupador);         
        },
     	//formulario		
		Atributos:[
			{
				//configuracion del componente
				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_agrupador_indicador'
				},
				type:'Field',
				form:true 
			},
			{
				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_agrupador'
				},
				type:'Field',
				form:true 
			},/*
			{
                config: {
                    fieldLabel: 'id_agrupador_indicador_padre',
                    inputType: 'hidden',
                    name: 'id_agrupador_indicador_padre'
                },
                type: 'Field',
                form: true
            },*/
			{
				config: {
                    name: 'nombre_padre',
                    fieldLabel: 'Nombre',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'padre.nombre', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: true
			},
			{
				config:{
					labelSeparator:'',					
					name: 'id_agrupador',
					inputType:'hidden',
				},
				type:'Field',
				form:true 		
			},			
			{
				config: {
					name: 'id_indicador',
					fieldLabel: 'Indicador',
					allowBlank: false,
					emptyText: 'Elija una opcion...',
					store: new Ext.data.JsonStore({
						url: '../../sis_segintegralgestion/control/Indicador/listarIndicadorAgrupador',
						id: 'id_indicador',
						root: 'datos',
						sortInfo: {
							field: 'indicador',
							direction: 'ASC'
						},
						totalProperty: 'total',
						fields: ['id_indicador', 'indicador','sigla','descripcion'],
						remoteSort: true,						
						baseParams: {par_filtro: 'sigla#indicador'} 						
					}),
					tpl: '<tpl for="."><div class="x-combo-list-item" >{sigla}<p style="padding-left: 20px;">{indicador}</p> </div></tpl>',
					valueField: 'id_indicador',
					displayField: 'indicador',
					gdisplayField: 'indicador', 
					hiddenName: 'id_indicador',
					forceSelection: true,
					typeAhead: false,
					triggerAction: 'all',
					lazyRender: true,
					mode: 'remote',
					pageSize: 15,
					queryDelay: 1000,
					anchor: '80%',
					gwidth: 100,
					minChars: 2,
					renderer: function (value, p, record) {
                    	return String.format('{0}', record.data['indicador']);
                    }
				},
				type: 'ComboBox',
				id_grupo: 0,
				filters: {pfiltro: 'ind.indicador',type: 'string'},
				grid: true,
				form: true
			},
			{
				config:{
					name: 'sigla',
					fieldLabel: 'Sigla',				
					inputType:'hidden',
				},
				type:'Field',
				filters: {pfiltro: 'orden_sigla', type: 'string'},
				grid: false,
				form: false 		
			},	
            {
                    config: {
                        name: 'orden_sigla',
                        fieldLabel: 'Sigla',
                        allowBlank: false,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 50,
                        renderer: function (value, p, record) {
                            console.log('....oooo', p)
                            if(record.data['registro_completado']>=1){
                                p.style="background-color:#F9E4C4; text-aling:left";
                            }
                            return record.data['sigla'];
                        },
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'orden_sigla', type: 'string'},
                    id_grupo: 0,
                    grid: true,
                    //egrid: true,
                    form: false
            },
			/*{
				config: {
					name: 'id_funcionario_ingreso',
					fieldLabel: 'Funcionario Ingreso',
					allowBlank: false,
					emptyText: 'Elija una opcion...',
					store: new Ext.data.JsonStore({
						url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
						id: 'id_funcionario',
						root: 'datos',
						sortInfo: {
							field: 'desc_person',
							direction: 'ASC'
						},
						totalProperty: 'total',
						fields: ['id_funcionario', 'desc_person'],
						remoteSort: true,
						baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}
					}),
					valueField: 'id_funcionario',
					displayField: 'desc_person',
					gdisplayField: 'desc_person',
					hiddenName: 'id_funcionario',
					forceSelection: true,
					typeAhead: false,
					triggerAction: 'all',
					lazyRender: true,
					mode: 'remote',
					pageSize: 15,
					queryDelay: 1000,
					anchor: '80%',
					gwidth: 100,
					minChars: 2,
					renderer : function(value, p, record) {
						return String.format('{0} datos', record.data['desc_person']);
					}
				},
				type: 'ComboBox',
				id_grupo: 0,
				filters: {pfiltro: 'PERSON.desc_funcionario1', type: 'string'},
				grid: true,
				form: true
			},
			{
				config: {
					name: 'id_funcionario_evaluacion',
					fieldLabel: 'Funcionario evaluación',
					allowBlank: false,
					emptyText: 'Elija una opcion...',
					store: new Ext.data.JsonStore({
						url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
						id: 'id_funcionario',
						root: 'datos',
						sortInfo: {
							field: 'desc_person',
							direction: 'ASC'
						},
						totalProperty: 'total',
						fields: ['id_funcionario', 'desc_person'],
						remoteSort: true,
						baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}
					}),
					valueField: 'id_funcionario',
					displayField: 'desc_person',
					gdisplayField: 'desc_person',
					hiddenName: 'id_funcionario',
					forceSelection: true,
					typeAhead: false,
					triggerAction: 'all',
					lazyRender: true,
					mode: 'remote',
					pageSize: 15,
					queryDelay: 1000,
					anchor: '80%',
					gwidth: 100,
					minChars: 2,
					renderer : function(value, p, record) {
						return String.format('{0} datos', record.data['desc_person2']);
					}
				},
				type: 'ComboBox',
				id_grupo: 0,
				filters: {pfiltro: 'PERSON.desc_funcionario1', type: 'string'},
				grid: true,
				form: true
			},*/
			{
				config:{
					name: 'peso',
					fieldLabel: 'peso',
					allowBlank: false,
					anchor: '80%',
					gwidth: 100,
					maxLength:4,
					maxValue:100,
					minValue:0
				},
				type:'NumberField',
				filters:{pfiltro:'agrupa.peso',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
			},
			{
				config:{
					labelSeparator:'totalidad',
					inputType:'hidden',
					name: 'totalidad'
				},
				type:'Field',
				form:true,
				grid:false 
			},
			{
				config:{
					name: 'resultado',
					fieldLabel: 'resultado',
					allowBlank: false,
					anchor: '80%',
					gwidth: 100,
					maxLength:4,
					maxValue:100,
					minValue:0,
                    renderer: function (value, p, record) {
                    	
                    	if(record.data['semaforo1']=='' || record.data['valor_real']==''){

                    	}
                    	else{
	                    	
	                    	p.style="background-color:"+record.data['ruta_icono']+"; text-align: left";
	                    	
	                        return record.data['resultado'];
                    		//return String.format('<p><img src="'+record.data['ruta_icono']+'"  width="60%" alt="Completado" /></p>');
                    	}
                    	
                    	
                    	/*if(record.data['resultado']==null){
                    		 return 'No reporta';
                    	}
                    	else{
                    		 return record.data['resultado'];
                    	}*/
                    }
				},
				type:'NumberField',
				filters:{pfiltro:'agin.resultado',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:false
			},
			/*{
				config: {
                    name: 'ruta_icono',
                    fieldLabel: '',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 50,
                    maxLength: 150,
                    disabled: true,
                    renderer: function (value, p, record) {
                    	if(record.data['semaforo1']=='' || record.data['valor_real']==''){

                    	}
                    	else{
	                    	
	                    	p.style="background-color:"+record.data['ruta_icono']+"; text-align: left";
	                    	
	                        return '';
                    		//return String.format('<p><img src="'+record.data['ruta_icono']+'"  width="60%" alt="Completado" /></p>');
                    	}
                    	
                    },
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.ruta_icono', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},	*/
			{
				config: {
                    name: 'valor_real',
                    fieldLabel: 'Valor real',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.valor_real', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},
			{
				config: {
                    name: 'semaforo1',
                    fieldLabel: 'Semaforo1',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.semaforo1', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},
			{
				config: {
                    name: 'semaforo2',
                    fieldLabel: 'Semaforo2',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.semaforo2', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},
			{
				config: {
                    name: 'semaforo3',
                    fieldLabel: 'Semaforo3',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.semaforo3', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},
			{
				config: {
                    name: 'semaforo4',
                    fieldLabel: 'Semaforo4',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.semaforo4', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},
			{
				config: {
                    name: 'semaforo5',
                    fieldLabel: 'Semaforo5',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.semaforo5', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},
			{
				config:{
					name: 'orden_logico',
					fieldLabel: 'Orden lógico',
					allowBlank: true,
					anchor: '80%',
					gwidth: 100,
					maxLength:9,
					maxValue:100,
					minValue:0,
				},
				type:'NumberField',
				filters:{pfiltro:'agin.orden_logico',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
			},
			{
				config: {
                    name: 'semaforo',
                    fieldLabel: 'Semaforo',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.semaforo', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: false
			},
			{
				config: {
                    name: 'comparacion',
                    fieldLabel: 'Comparación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.comparacion', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: false
			},
			{
				config: {
                    name: 'justificacion',
                    fieldLabel: 'Justificacion',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'agin.justificacion', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
			},
			
		],
		tam_pag:50,	
		title:'Agrupador',
		ActSave:'../../sis_segintegralgestion/control/AgrupadorIndicador/insertarAgrupadorIndicador',
		ActDel:'../../sis_segintegralgestion/control/AgrupadorIndicador/eliminarAgrupadorIndicador',
		ActList:'../../sis_segintegralgestion/control/AgrupadorIndicador/listarAgrupadorIndicador',
		id_store:'id_agrupador_indicador',
		fields: [
			{name:'id_agrupador_indicador', type: 'numeric'},
			{name:'id_agrupador', type: 'numeric'},
			{name:'id_indicador', type: 'numeric'},
			
			/*{name:'id_funcionario_ingreso', type: 'numeric'},
			{name:'desc_person', type: 'string'},
			{name:'id_funcionario_evaluacion', type: 'numeric'},
			{name:'desc_person2', type: 'string'},*/
			
			{name:'peso', type: 'numeric'},
			{name:'estado_reg', type: 'string'},
			{name:'id_usuario_ai', type: 'numeric'},
			{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
			{name:'usuario_ai', type: 'string'},
			{name:'id_usuario_reg', type: 'numeric'},
			{name:'id_usuario_mod', type: 'numeric'},
			{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
			{name:'usr_reg', type: 'string'},
			{name:'usr_mod', type: 'string'},
			{name:'indicador', type: 'string'},
			{name:'sigla', type: 'string'},
			{name:'nombre_padre', type: 'string'},
			//{name:'id_agrupador_indicador_padre', type: 'string'},
			{name:'totalidad', type: 'numeric'},
			{name:'resultado', type: 'numeric'},
			{name:'semaforo1', type: 'string'},
			{name:'semaforo2', type: 'string'},
			{name:'semaforo3', type: 'string'},
			{name:'semaforo4', type: 'string'},
			{name:'semaforo5', type: 'string'},
			{name:'valor_real',type:'string'},	
			{name:'semaforo',type:'string'},	
			{name:'comparacion',type:'string'},		
			{name:'ruta_icono',type:'string'},	
			{name:'justificacion',type:'string'},		
			{name:'orden_sigla', type: 'string'},
			{name:'orden_logico', type: 'numeric'}		
							
		],
		sortInfo:{
			field: 'id_agrupador_indicador',
			direction: 'ASC'
		},
		bdel:true,
		bsave:false,
		bedit:true,
		bnew:true				
	}
)
</script>
		
		