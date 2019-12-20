<?php
/**
*@package pXP
*@file gen-LineaAvance.php
*@author  (admin)
*@date 19-02-2017 02:21:07
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
var arrayMeses=null;
var v_id_plan=null;

Phx.vista.LineaAvance=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
        console.log("id_plan recibiendo 0 ");

		this.configMaestro=config;
		this.config=config;
		v_id_plan=this.configMaestro.data.id_plan;
		arrayMeses=this.configMaestro.data.meses;
        console.log("id_plan recibiendo 1 ", this.configMaestro.data.id_plan);

        // Inicio de columanas generadas
		Phx.CP.loadingShow();
		console.log("id_plan recibiendo 2 ");
		this.storeAtributos= new Ext.data.JsonStore({
			          			url:'../../sis_segintegralgestion/control/LineaAvance/listarLineaAvance',
							    id: 'id_linea',
			   					root: 'datos',
			   				    totalProperty: 'total',
			   					fields: [
			   					                'id_linea',
										        'id_linea_avance', 
												{name:'nombre_linea', type: 'string'},
												{name:'peso', type: 'string'},
												{name:'peso_acumulado', type: 'string'},
												{name:'peso_restante', type: 'string'},
												{name:'id_funcionarios', type: 'string'},
												{name:'funcionarios', type: 'string'},
												{name:'mes', type: 'string'},
												
												{name:'avance_previsto', type: 'string'},
												{name:'avance_real', type: 'string'},
												{name:'comentario', type: 'string'},
												{name:'aprobado_real', type: 'string'},
												
												{name:'estado_reg', type: 'string'},
												{name:'id_usuario_ai', type: 'numeric'},
												{name:'usuario_ai', type: 'string'},
												{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
												{name:'id_usuario_reg', type: 'numeric'},
												{name:'id_usuario_mod', type: 'numeric'},
												{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
												{name:'usr_reg', type: 'string'},
												{name:'usr_mod', type: 'string'},
												{name:'nivel', type: 'numeric'},
											    {name:'linea_padre', type: 'string'},
											    'id_linea_padre',
											    {name:'id_plan', type: 'numeric'}],
									sortInfo:{
										field: 'id_linea',
										direction: 'ASC'
									}});
			//evento de error
			console.log("id_plan recibiendo 3 ");
			this.storeAtributos.on('loadexception',this.conexionFailure);				
		    console.log("id_plan recibiendo 4 ");
			this.storeAtributos.load({params:{
				                              "sort":"id_linea",
				                              "dir":"ASC",
				                              //'id_uni_cons':config.id_uni_cons,
				                               start:0, 
				                               limit:500},callback:this.successConstructor,scope:this})			
							                               
			//fin columnas generadas	                               
		
	},	

	successConstructor:function(rec,con,res){
		 console.log("id_plan recibiendo 5 ");
		 
		this.recColumnas = rec;
		this.Atributos=[];
		this.fields=[];
		this.id_store='id_linea'
		
		this.sortInfo={
			field: 'id_linea',
			direction: 'ASC'
		};
		 console.log("id_plan recibiendo 5.1 ");
		this.fields.push(this.id_store)
		this.fields.push('id_linea')
		this.fields.push({name:'nombre_linea', type: 'TextField'})
		this.fields.push('peso')
		this.fields.push('peso_acumulado')
		this.fields.push('peso_restante')
		this.fields.push('funcionarios')
		this.fields.push('id_funcionarios')
		this.fields.push('nivel')
		this.fields.push('linea_padre')
		this.fields.push('id_linea_padre')
		this.fields.push('id_plan')
		
		
		if(res)
		{
			this.Atributos[0]={
			//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: this.id_store
								},
								type:'Field',
								form:true 
						};
			this.Atributos[1]={
			//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: this.id_linea
								},
								type:'Field',
								form:true 
						};
			this.Atributos[2]={
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: 'id_linea_padre'
								},
								type:'Field',
								form:true 
						};		
			this.Atributos[3]={
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: 'id_plan'
								},
								type:'Field',
								form:true 
						};	
			this.Atributos[4]={
								config:{
									name: 'linea_padre',
									fieldLabel: 'Linea padre',
									allowBlank: true,
									anchor: '80%',
									gwidth: 100,
									maxLength:655370,
									disabled: true
								},
									type:'TextField',
									//filters:{pfiltro:'liav.avance_real',type:'numeric'},
									id_grupo:1,
									grid:false,
									form:true
						};
			this.Atributos[5]={
								//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: 'nivel'
								},
								type:'Field',
								form:true 
						};
			this.Atributos[6]={
			//configuracion del componente
								config:{
										
										name: 'nombre_linea',
										fieldLabel: 'Nombre',
										allowBlank: false,
										anchor: '80%',
						                renderer: function (value, p, record, rowIndex, colIndex){
						
						                   var espacion_blanco="";
						                   var duplicar="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						                   var nivel = record.data.nivel==null?0:record.data.nivel;
						                   var espacion_blanco = duplicar.repeat(nivel);
						
						                   if(record.data.nivel ==1 || record.data.nivel==null){
						                   	    return  String.format('<div style="vertical-align:middle;text-align:left;"> '+espacion_blanco+' <img src="../../../lib/imagenes/a_form_edit.png"> '+ record.data.nombre_linea+' </div>');
						                   }
						                   else{
						                    	if(record.data.nivel == 2 ){
						                   	        return  String.format('<div style="vertical-align:middle;text-align:left;"> '+espacion_blanco+' <img src="../../../lib/imagenes/a_form.png"> '+ record.data.nombre_linea+' </div>');
						                    	}
						                    	else{
						                    		return  record.data.nombre_linea;
						                    	}
						                   }
						                }
								},
								type:'TextField',
								filters:{pfiltro:'nombre_linea',type:'date'},
								grid:true,
								form:true 
						};
						
			this.Atributos[7]={
			//configuracion del componente
								config:{
										
										name: 'peso',
										fieldLabel: 'Peso',
										//format:'H:i:s',
										allowBlank: false
								},
								type:'NumberField',
								grid:true,
								form:true 
						};
			this.Atributos[8]={
								config:{
									name: 'peso_acumulado',
									fieldLabel: 'Peso acumulado',
									allowBlank: true,
									anchor: '80%',
									gwidth: 100,
									maxLength:655370,
					                renderer: function (value, p, record, rowIndex, colIndex){
					                   if(record.data.nivel !=2 && record.data.peso_acumulado!=null){
					                   	    return "<font color='#228b22'>ACUM.: "+record.data.peso_acumulado+" %</font>";
					                   }
					                   else{
					                   	return "";
					                   }
					                }
								},
									type:'TextField',
									//filters:{pfiltro:'liav.avance_real',type:'numeric'},
									id_grupo:1,
									grid:true,
									form:false
						};
			this.Atributos[9]={
								config:{
									name: 'peso_restante',
									fieldLabel: 'Peso restante',
									allowBlank: true,
									anchor: '80%',
									gwidth: 100,
									maxLength:655370,
					                renderer: function (value, p, record, rowIndex, colIndex){
					
					                   var espacion_blanco="";
					                   var duplicar="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					                   var nivel = record.data.nivel==null?0:record.data.nivel;
					                   var espacion_blanco = duplicar.repeat(nivel);
					                   if(record.data.nivel !=2 && record.data.peso_restante!=null){
					                   	    //return record.data.peso_restante;
					                   	    return "<font color='red'>REST.: "+record.data.peso_restante+" %</font>";
					                   }
					                   else{
					                   	return "";
					                   }
					                   
					                }
								},
									type:'TextField',
									//filters:{pfiltro:'liav.avance_real',type:'numeric'},
									id_grupo:1,
									grid:true,
									form:false
						};
			this.Atributos[10]={
			//configuracion del componente
			                    config: {
			                        //enviar erreglo
			                        name: 'id_funcionarios',
			                        fieldLabel: 'Responsable',
			                        allowBlank: true,
			                        emptyText: 'Elija una opción...',
			                        store: new Ext.data.JsonStore({
			                            url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
			                            id: 'id_funcionario',
			                            root: 'datos',
			                            sortInfo: {
			                                field: 'desc_person',
			                                direction: 'ASC'
			                            },
			                            totalProperty: 'total',
			                            fields: ['id_funcionario', 'codigo', 'desc_person', 'ci', 'documento', 'telefono', 'celular', 'correo'],
			                            // turn on remote sorting
			                            remoteSort: true,
			                            baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}
			
			                        }),
			                        valueField: 'id_funcionario',//valor que se le dara de acuerdo al sotre del combo
			                        displayField: 'desc_person',
			                        tpl: '<tpl for="."> <div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}</div> <p>{desc_person}</p><p>CI:{ci}</p> </div> </tpl>',
			                        gdisplayField: 'funcionarios',//poner el parametro que viene de la BD del grid
			                        hiddenName: 'id_funcionarios',//es el mismo nombre del name
			                        forceSelection: true,
			                        typeAhead: false,
			                        triggerAction: 'all',
			                        lazyRender: true,
			                        mode: 'remote',
			                        pageSize: 15,
			                        queryDelay: 1000,
			                        anchor: '80%',
			                        gwidth: 200,
			                        minChars: 2,
			                        //para multiples
			                        enableMultiSelect: true,
			                        renderer: function (value, p, record) {
			                        	//var res = record.data['funcionarios'].replace(",","<br>");
			                            //return String.format('{0}', res);
			                            return String.format('{0}', record.data['funcionarios']);
			                        }
			                    },
			                    type: 'AwesomeCombo',
			                    id_grupo: 0,
			                    filters: {pfiltro: 'PERSON.desc_funcionario1', type: 'string'},
			                    grid: true,
			                    form: true,
						};
			this.Atributos[11]={
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: 'id_linea_avance'
								},
								type:'Field',
								form:true 
						};	

									
			var recText = this.id_store+'#integer';			
			
			for (var i=0;i<rec.length;i++){
				alert(rec[i].data.nombre_plan);
			}
			console.log("Probar rec ",rec);
			for(i = 0; i < arrayMeses.length; i++){
						//var configDef={};
						
					var codigo_col = 'col_'+arrayMeses[i];
						//alert(codigo_col);
						this.fields.push(arrayMeses[i]);
						//this.fields.push(arrayMeses[i]+'_key');
						this.fields.push(arrayMeses[i]);
						
					   // recText=recText+'@'+codigo_col+'#varchar'+'@'+codigo_col+'_key#integer'
						
						this.Atributos.push({config:{
											 name: arrayMeses[i],
											 fieldLabel: arrayMeses[i],
											 allowBlank: true,
											 anchor: '80%',
											 gwidth: 100,
											 maxLength:100
											},
											type:'NumberField',
											filters:{pfiltro:arrayMeses[i],type:'string'},
											id_grupo:1,
											egrid:true,
											grid:true,
											form:false
									});
									
						
						
						this.Atributos.push({config:{
											 //name: arrayMeses[i]+'_key',
											 name: arrayMeses[i],
											 inputType:'hidden'
											},
											type:'Field',
											form:true
									});
									
			}

			console.log("id_plan recibiendo 6 ");
			Phx.CP.loadingHide();
			Phx.vista.LineaAvance.superclass.constructor.call(this, this.config);
			this.argumentExtraSubmit={'id_plan':v_id_plan};
		    
			
            console.log("id_plan recibiendo 7 ");
		  /* this.tbar.add('Desde: ',this.dateFechaIni);
		    this.tbar.add('Hasta: ',this.dateFechaFin);
		    this.tbar.add('Limite: ',this.cmbLimit); 
		    
		
		    this.addButton('btnGrafica',{
            text : 'Gráficar',
            iconCls : 'bstatistics',
            disabled : false,
            handler : this.onButtonGrafica,
            tooltip : '<b>Gráfica</b><br/><b>Genera gráfica (La ordenación de los resultados afecta la gráfica)</b>'
             });*/
        
		
			this.init();
			
            this.grid.addListener('cellclick', this.oncellclick, this);
			/*this.dateFechaIni.setValue(fechaini);
			this.dateFechaFin.setValue(fechaActual);*/
			
			this.store.baseParams={'id_plan': this.configMaestro.data.id_plan};			               
		
            this.load();
		}
		
	},
    oncellclick: function (grid, rowIndex, columnIndex, e) {
                var record = this.store.getAt(rowIndex),
                    fieldName = grid.getColumnModel().getDataIndex(columnIndex); 

		        if(record.data['nivel']=='2') {
		        	Ext.getCmp('b-new-' + this.idContenedor).hide()
		        	//esta bandera servira para mostrar boton al actualizar y desceleccionar en caso de ser nivel 2
		        }
		        else{
		        	 Ext.getCmp('b-new-' + this.idContenedor).show()
		        }
               

				console.log("testear columnas", e);

                if(record.data['nivel']!=2 ){
                     alert("Solo se permite registrar en el nivel 3");
                }
                else{
                	//alert(record.data['id_linea']);
                }

    },

	MostrarBotones :function(){
		Ext.getCmp('b-new-' + this.idContenedor).show()
		Ext.getCmp('b-edit-' + this.idContenedor).show()
		Ext.getCmp('b-save-' + this.idContenedor).show()
	},
	OcultarBotones :function(){
		Ext.getCmp('b-new-' + this.idContenedor).hide()
		Ext.getCmp('b-edit-' + this.idContenedor).hide()
		Ext.getCmp('b-save-' + this.idContenedor).hide()
	},
    onButtonNew: function () {
	            Phx.vista.LineaAvance.superclass.onButtonNew.call(this);
	            
			    if(this.sm.selections.items==''){
			        this.Cmp.nivel.setValue(null);
                    this.Cmp.id_plan.setValue(v_id_plan);  
			        this.Cmp.id_linea_padre.setValue(null);  
			        this.Cmp.id_linea.setValue(null);  
		    	}
		    	else{
		    		this.Cmp.linea_padre.setValue(this.sm.selections.items[0].data.nombre_linea);
			        this.Cmp.id_plan.setValue(this.sm.selections.items[0].data.id_plan);
			        this.Cmp.nivel.setValue(parseInt((this.sm.selections.items[0].data.nivel==null?0:this.sm.selections.items[0].data.nivel))+1);
			        this.Cmp.id_linea.setValue(null);
			        this.Cmp.id_linea_padre.setValue(this.sm.selections.items[0].data.id_linea);   
		    	}
    },
	onButtonAct:function(){
		this.store.rejectChanges();
		Phx.CP.varLog=false;
		this.reload();
		this.grid.getSelectionModel().clearSelections();
			this.MostrarBotones();

	},
	/*onButtonSave:function(o){
		    
		   
			var filas=this.store.getModifiedRecords();
			if(filas.length>0){	
				
			
					    var banderaFecha=false;
					    var banderaHora=false;
					    var banderaNumero=false;
					    var FormatoNumero="";
						var NumeroFila="";
						var banderaGenerica=false;
						var data={};
						
						for(var i=0;i<filas.length;i++){
							 data[i]=filas[i].data;
							 data[i]._fila=this.store.indexOf(filas[i])+1
							 this.agregarArgsExtraSubmit(filas[i].data);
							 Ext.apply(data[i],this.argumentExtraSubmit);

                             alert(data[i].semaforo1);
                             
						}
	
						 	
									Phx.CP.loadingShow();
							        Ext.Ajax.request({
							        	// form:this.form.getForm().getEl(),
							        	url:this.ActSave,
							        	params:{_tipo:'matriz','row':String(Ext.util.JSON.encode(data)).replace(/&/g, "%26")},
									
										isUpload:this.fileUpload,
										success:this.successSaveFileUpload,
										//argument:this.argumentSave,
										failure: this.conexionFailure,
										timeout:this.timeout,
										scope:this
							        });
                          

						
			}

	   },	*/
	tam_pag:50,	
	title:'Linea avance',
	ActSave:'../../sis_segintegralgestion/control/LineaAvance/insertarLineaAvance',
	ActDel:'../../sis_segintegralgestion/control/LineaAvance/eliminarLineaAvance',
	ActList:'../../sis_segintegralgestion/control/LineaAvance/listarLineaAvanceDinamico',
	bdel:true,
	bsave:true, 

	}
)
</script>
		
		