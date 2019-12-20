<?php
/**
 * @package pXP
 * @file gen-SistemaDist.php
 * @author  (rarteaga)
 * @date 20-09-2011 10:22:05
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    var validarInput='TextField';

    var TipoUnidad=null;

    Phx.vista.IndicadorValorSeguimiento = {
        bsave: false,
        require: '../../../sis_segintegralgestion/vista/indicador_valor/IndicadorValor.php',
        requireclase: 'Phx.vista.IndicadorValor',
        title: 'Indicador Valor Seguimiento',

        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.IndicadorValorSeguimiento.superclass.constructor.call(this, config);
            this.init();
           // this.bloquearMenus();
        },
	    onAfterEdit:function(prueba){
           console.log("vertes",prueba)
	       if(prueba.field=='valor' && prueba.record.data['valor']!=''){
	       	 prueba.record.set('no_reporta','Reporta');
	       }
			
	    },
       	oncellclick : function(grid, rowIndex, columnIndex, e) {
		        var record = this.store.getAt(rowIndex),
		            fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
		   
		       // el estado gestion recupera el dato de la vista FormIndicador

		        if(estadoGestion[1]!='true'){
		              alert("No se puede editar ninguna columna si la gestión seleccionada no esta aprobada");
		              this.reload();
		        }
		        else{
		        	
		        	if(fieldName == 'fecha') {
		                alert("No se puede editar el campo fecha");
		            }
		            if(fieldName == 'hito') {
		                alert("No se puede editar el campo hito");
		            }
		            /*if(fieldName == 'no_reporta') {
		                var sw1 =record.data['no_reporta'];
		                sw1= record.data['no_reporta']=='f'?'t':'f';
		                record.set('no_reporta', sw1);
		            }*/
		        }
		        //this.Cmp.valor.reset();
		        console.log("Componenete  ",this.Cmp.valor);
        
       },
		onButtonSave:function(o){
		   
			var filas=this.store.getModifiedRecords();
			if(filas.length>0){	
			
					    var banderaFecha=false;
					    var banderaHora=false;
					    var banderaNumero=false;
					    var FormatoNumero="";
						//prepara una matriz para guardar los datos de la grilla
						var NumeroFila="";
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

							//VALIDAR FECHA
							if(TipoUnidad=='Fecha'){
								  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].valor) && data[i].valor!=''){
									//alert("Error formato de fecha"); 
									banderaFecha=true;
									NumeroFila=data[i]._fila+" valor";
								  }
							 }
							  //VALIDAR HORA
							if(TipoUnidad=='Hrs'){
								  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].valor) && data[i].valor!=''){
									//alert("Error formato de fecha"); 
									banderaHora=true;
									NumeroFila=data[i]._fila+" valor";
								  }
							 }
							 
							 if(TipoUnidad=='Numero'){

								  if(data[i].valor!=''){
									      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].valor)){
												banderaNumero=true;
												NumeroFila=data[i]._fila+" valor ";
												FormatoNumero="(Formato válido número entero o con decimales)";
									  	  }
								  }		  
							  }

						}
						if(banderaFecha==true){
						     alert("Error!! El formato de fecha ingresado en la fila "+NumeroFila+" no es valido  \n(Fomrato válido = dd/mm/yyyy)"); 
						}
						 else{
						     if(banderaHora==true){
						 	     alert("Error!! El formato de Hora ingresado en la fila "+NumeroFila+" \n(Formato válido número entero o con decimales)"); 
						     }
						     else{
						     	if(banderaNumero==true){
						     		alert("Error!! El formato ingresado en la fila "+NumeroFila+" no es valido  \n"+FormatoNumero); 
						     	}
						     }
						 }		
						 if(banderaFecha==false && banderaHora==false && banderaNumero==false){
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
				        
			}
	   },
        
        onReloadPage: function (m) {
           this.maestro = m;
           var aa=this;
           this.store.baseParams = {id_indicador: this.maestro.id_indicador};
           this.load({params: {start: 0, limit: 50}})
            // ocultar del formulario this.ocultarComponente(this.Cmp.semaforo1);
           var colModel = this.grid.getColumnModel();
           //console.log("botones ", Ext.getCmp('b-new-' + this.idContenedor));
           //tb.items.get('b-new-' + this.idContenedor).enable()
           // b-new-docs-SEIN-east-0
          	console.log("Componente hijo", this);
          	
          	TipoUnidad=this.maestro.tipo;
          	Ext.getCmp('b-new-' + this.idContenedor).hide()
            if(m.frecuencia=='Hito'){ 
                //Ext.getCmp('b-new-' + this.idContenedor).show()
                this.tbar.disabled=false;
                console.log('ver ids ',this.tbar); 
                colModel.setHidden(2, false);
            }
            else{
               colModel.setHidden(2, true);
          	   //Ext.getCmp('b-new-' + this.idContenedor).hide()
           	  
            }
            //Mostrar columna de grilla
            colModel.setHidden(8, false);
            colModel.setHidden(9, false); 
            colModel.setHidden(10, false);     
            // Ocultar Columnas de grilla
            colModel.setHidden(3, true);
            colModel.setHidden(4, true);
            colModel.setHidden(5, true);
            colModel.setHidden(6, true);
            colModel.setHidden(7, true);      
            //Ocultar del formularuio
            //this.ocultarComponente(this.Cmp.valor);
            //this.ocultarComponente(this.Cmp.justificacion);

            
         },
    
         bdel:true,
	     bsave:true,
	     bnew:true,
	     bedit:false,
        
        
    };
</script>
