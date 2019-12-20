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
var v_id_plan=null;
var meses=null;
var arrayMeses=[];
var col_generado='';
Phx.vista.LineaAvance=Ext.extend(Phx.gridInterfaz,{
 

	constructor:function(config){
        console.log("id_plan recibiendo 0 ");

		this.configMaestro=config;
		this.config=config;
		v_id_plan=this.configMaestro.data.id_plan;
        arrayMeses=this.configMaestro.data.meses;
 
        console.log("meses probando ",arrayMeses);
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

												{name:'avance_previsto', type: 'string'},

												{name:'aprobado_real', type: 'string'},
												
												{name:'estado_reg', type: 'string'},
												{name:'id_usuario_ai', type: 'numeric'},
												{name:'usuario_ai', type: 'string'},
												{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
												{name:'id_usuario_reg', type: 'numeric'},

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
		onButtonSave:function(o){
		    
		   var bandera=false;
			var filas=this.store.getModifiedRecords();
			if(filas.length>0){	

						var data={};
						
						for(var i=0;i<filas.length;i++){
							 data[i]=filas[i].data;
							 data[i]._fila=this.store.indexOf(filas[i])+1
							 this.agregarArgsExtraSubmit(filas[i].data);
							 Ext.apply(data[i],this.argumentExtraSubmit);

                             if(data[i].total>100){
                             	bandera=true;
                             }
							 
						}
	
						 if(bandera==false ){
						 	
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
						else{
							alert("Los totales no deben ser mayor a 100");
						}
			}

	   },
	successConstructor:function(rec,con,res){

		 
		this.recColumnas = rec;
		this.Atributos=[];
		this.fields=[];
		this.id_store='id_linea'
		
		this.sortInfo={
			field: 'id_linea',
			direction: 'ASC'
		};
		
		this.fields.push(this.id_store)
		this.fields.push('id_plan')
		this.fields.push('id_linea')
		this.fields.push('cod_linea_padre')
		this.fields.push({name:'nombre_linea', type: 'TextField'})
		this.fields.push('peso')
		this.fields.push('nivel')
		this.fields.push('id_linea_avance')
		this.fields.push('id_linea_avance_temporal')
		

		
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
										name: this.id_plan
								},
								type:'Field',
								form:true 
						};
			this.Atributos[2]={
			//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: this.id_linea
								},
								type:'Field',
								form:true 
						};
			this.Atributos[3]={
			//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: this.cod_linea_padre
								},
								type:'Field',
								form:true 
						};
			this.Atributos[4]={
			//configuracion del componente
								config:{
										
										name: 'nombre_linea',
										fieldLabel: 'nombre_linea',
										allowBlank: false,
										anchor: '80%',
						                renderer: function (value, p, record, rowIndex, colIndex){
						
						                   var espacion_blanco="";
						                   var duplicar="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						                   var nivel = record.data.nivel==null?0:record.data.nivel;
						                   var espacion_blanco = duplicar.repeat(nivel);
						
						                   if(record.data.nivel ==1 || record.data.nivel==0){
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
						                },
						                gwidth: 500,
								},
								type:'TextField',
								filters:{pfiltro:'nombre_linea',type:'date'},
								grid:true,
								form:true 
						};
			this.Atributos[5]={
			//configuracion del componente
								config:{
										
										name: 'peso',
										fieldLabel: 'peso',
										//format:'H:i:s',
										allowBlank: false,
										renderer: function (value, p, record, rowIndex, colIndex){
		

						                   	    return "<font color='red'>"+record.data.peso+"</font>";

						               },
						               gwidth: 50,
								},
								type:'NumberField',
								grid:true,
								form:true 
						};
			this.Atributos[6]={
			//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: this.nivel
								},
								type:'Field',
								form:true 
						};
			this.Atributos[7]={
			//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: this.id_linea_avance
								},
								type:'Field',
								form:true 
						};
			this.Atributos[8]={
			//configuracion del componente
								config:{
										labelSeparator:'',
										inputType:'hidden',
										name: this.id_linea_avance_temporal
								},
								type:'Field',
								form:true 
						};



		}		
		
        var contador=0;
		for (var i=0;i<arrayMeses.length;i++){
			
			//alert(rec[i].data.nivel);
			col_generado=col_generado+'@'+arrayMeses[i];
			
			this.fields.push(arrayMeses[i])

		   		       	this.Atributos.push({config:{
									 name: arrayMeses[i],
									 fieldLabel: arrayMeses[i],
									 allowBlank: true,
									 anchor: '80%',
									 gwidth: 100,
									 maxLength:100,
                                     gwidth: 60,
									},
									type:'NumberField',
									filters:{pfiltro:arrayMeses[i],type:'string'},
									id_grupo:1,
									egrid:true,
									grid:true,
									form:true
							});
							
				          this.Atributos.push({config:{
									 name: arrayMeses[i],
									 inputType:'hidden'
									},
									type:'Field',
									form:true
							});
			  	
			  			
			  if(arrayMeses[i]!='total'){
			  	  contador++;
			  	  
			      this.fields.push('id_lavance'+contador.toString())
			      
			      		this.Atributos.push({config:{
										labelSeparator:'',
										inputType:'hidden',
										maxLength:100,
										name: 'id_lavance'+contador.toString()
								},
								type:'Field',
								form:true 
						});
			  }
							
		   }
		   
		    this.fields.push('cod_hijos')
		    
	       	this.Atributos.push({config:{
						labelSeparator:'',
						inputType:'hidden',
						name: 'cod_hijos'

						},
                       type:'Field',
					   form:true 
				});
				
				
            this.fields.push('cod_linea')
            
	       	this.Atributos.push({config:{
						labelSeparator:'',
						inputType:'hidden',
						name: 'cod_linea'

						},
                       type:'Field',
					   form:true 
				});
				

           

			Phx.CP.loadingHide();
			Phx.vista.LineaAvance.superclass.constructor.call(this, this.config);
			this.argumentExtraSubmit={'id_plan': this.configMaestro.data.id_plan,'datos': col_generado};
		    
            //this.editorDetail.on('afteredit', this.onAfterEdit, this);
		
			this.init();
			
            this.grid.addListener('cellclick', this.oncellclick,this);
            
            this.grid.addListener('afteredit', this.onAfterEdit, this);


			
			this.store.baseParams={'id_plan': this.configMaestro.data.id_plan , 'datos': col_generado};			               
		
            this.load();
	},
	
    onAfterEdit:function(prueba){

       var columna=prueba.field;
       var cod_id_linea=prueba.record.data['cod_linea'];
       var cod_linea_padre=prueba.record.data['cod_linea_padre'];
       var peso=prueba.record.data['peso'];
       var array_hijos=(prueba.record.data['cod_hijos']).split(',');
       var sum_avance_padre=0;

       //console.log("me", prueba.record.data['feb17']);
       //console.log("probar stores  ", prueba);


       //this.calcular(array_hijos,cod_linea_padre,prueba);
       
       ///////////volver a cero cuando se introduce valor vacio///////////////////
       this.store.each(function(r){
		           if(r.data['cod_linea'].trim()== cod_id_linea){
		       	           if((prueba.value).toString().trim()==''){
					        	  r.set(columna,0);
					       }
		           }
		},this);
       /////////////////////////////////////////////////////////////////////////
    //   var iNum = 5.123456;
     //     iNum=.toPrecision();  


        this.store.each(function(r){
		           if(r.data['cod_linea'].trim()== cod_linea_padre.trim()){
		                   array_hijos=(r.data['cod_hijos']).split(',');
		                   var sumar=0.00000;
		                   for (var i=0;i<array_hijos.length-1;i++){
			                        this.store.each(function(rr){
					                   	if(rr.data['cod_linea'].trim()== array_hijos[i].trim()){
					                   	  sumar += ( (parseFloat(rr.data[columna])) * parseFloat(rr.data['peso']) )/parseFloat(100);
					                   	}
			                   	
			                        },this);
		                   }
		       	           r.set(columna,sumar.toFixed(9));

		       	           cod_linea_padre=r.data['cod_linea_padre'];
		       	           array_hijos=(r.data['cod_hijos']).split(',');

		           }
		       	
		},this);
		
		
        this.store.each(function(r){
		           if(r.data['cod_linea'].trim()== cod_linea_padre.trim()){
		                   array_hijos=(r.data['cod_hijos']).split(',');
		                   var sumar=0.0000;
		                   for (var i=0;i<array_hijos.length-1;i++){
			                        this.store.each(function(rr){
					                   	if(rr.data['cod_linea'].trim()== array_hijos[i].trim()){
					                   	  sumar += ( (parseFloat(rr.data[columna])) * parseFloat(rr.data['peso']) )/parseFloat(100);
					                   	}
			                   	
			                        },this);
		                   }
		       	           r.set(columna,sumar.toFixed(9));

		       	           cod_linea_padre=r.data['cod_linea_padre'];
		       	           array_hijos=(r.data['cod_hijos']).split(',');
		       	           
		           }
		       	
		},this);
		
		//totales se comento esto para evitar el exceso de modificaciones en el grid de linea avance
		// se mejoro para solucionar el problema mencionado 
		// en caso de 
		var contTotal='';
        this.store.each(function(r){
        	
        	console.log(r);
            var sumTotal=0;
           
			for (var i=0;i<arrayMeses.length-1;i++){
			
				sumTotal +=parseFloat(r.data[arrayMeses[i]]);
			}
		
			if(parseInt(sumTotal) > 100){
				 //	alert("Corrigiendo validadcion del sistema espere un momento por favor "+sumTotal +' ver  '+r.data['nombre_linea']);
			     contTotal="Alerta!! El número ingresado ocaciona un desborde mayor a 100 en el total de la linea "+r.data['nombre_linea']+"";
			}
			//
			//r.set('total',sumTotal);

		},this);   
		
		if(contTotal==''){
			this.InsertarAvancePrevisto();
		}
		else{
			alert(contTotal);
			this.store.rejectChanges();
		    Phx.CP.varLog=false;
       		this.reload();
		}
		
    },
    InsertarAvancePrevisto: function (){
    	
    	var bandera=false;
    	var linea='';
    	var filas=this.store.getModifiedRecords();
		if(filas.length>0){	
			
					//prepara una matriz para guardar los datos de la grilla
					var data={};
					for(var i=0;i<filas.length;i++){
						 //rac 29/10/11 buscar & para remplazar por su valor codificado
						 data[i]=filas[i].data;
						 //captura el numero de fila
						 data[i]._fila=this.store.indexOf(filas[i])+1
						 //RCM 12/12/2011: Llamada a función para agregar parámetros
						this.agregarArgsExtraSubmit(filas[i].data);
						Ext.apply(data[i],this.argumentExtraSubmit);
					    //FIN RCM
						 /*if(parseFloat(data[i].total)>100){
						 	bandera=true;
						 	linea=data[i].nombre_linea;
						 }*/
	                    //alert(data[i].data+" juan");
						//console.log("juan guardar ",data[i]);
						
					}
					Phx.CP.loadingShow();
			        Ext.Ajax.request({
			        	// form:this.form.getForm().getEl(),
			        	url:this.ActSave,
			        	params:{_tipo:'matriz','row':String(Ext.util.JSON.encode(data))},
					
						isUpload:this.fileUpload,
						success:this.successSaveFileUpload,
						//argument:this.argumentSave,
						failure: this.conexionFailure,
						timeout:this.timeout,
						scope:this
			        });
					
		}
		
		//if(bandera==false){

       //}
       /*else{
       	   // verificar si es necesario este else por que arriba ya hay un control para el ecceso de cambios  al editar muchos registros
       	    alert("Alerta!! El número ingresado ocaciona un desborde mayor a 100 en el total de la linea "+linea+"");
       		
       		this.store.rejectChanges();
		    Phx.CP.varLog=false;
       		this.reload();
       }*/
      	    
    },
		
	 oncellclick : function(grid, rowIndex, columnIndex, e) {
	 	var record = this.store.getAt(rowIndex),
        fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

        if(fieldName=='total'){
		 	//alert("No se puede editar el campo total");
		 	this.reload();
		}
		else{
			for (var i=0;i<arrayMeses.length-1;i++){
		 		if(arrayMeses[i]==fieldName && record.data['nivel']!=2){
		 			//alert("Solo se puede editar en el nivel 3");
		 			this.reload();
		 		}
		    }
		}



	 },
	 
	 onDestroy: function() {

    	    Phx.CP.getPagina(Phx.CP.getPagina(this.idContenedorPadre).maestro.scope.idContenedorPadre).root.reload();
            Phx.CP.getPagina(Phx.CP.getPagina(this.idContenedorPadre).maestro.scope.idContenedorPadre).treePanel.expandAll();	
      
	        this.fireEvent('closepanel',this);
	        
	        if (this.window) {
	            this.window.destroy();
	        }
	        if (this.form) {
	            this.form.destroy();
	        }
			
	        Phx.CP.destroyPage(this.idContenedor);
	        delete this;
	    
	},

	tam_pag:1500,	
	title:'Linea avance',
	ActSave:'../../sis_segintegralgestion/control/LineaAvance/insertarLineaAvance',
	ActDel:'../../sis_segintegralgestion/control/LineaAvance/eliminarLineaAvance',
	ActList:'../../sis_segintegralgestion/control/LineaAvance/listarLineaAvanceDinamico',
	bdel:false,
	bsave:false, 
	bedit:false, 
    bnew:false, 

    

	}
)
</script>
		
		