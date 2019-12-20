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

  var Id_IndicadorRecibido=null;
  var TipoUnidad=null;

  var Num_decimal=null;
  var validarInput=/[0-5/-]+/i;
  var frecuencia=null;
  var conf="";
  var semaforo=null;
  var comparacion=null;

  var banderaSeleccion=null;
    Phx.vista.IndicadorValorPrincipal = {
        bsave: false,
        require: '../../../sis_segintegralgestion/vista/indicador_valor/IndicadorValor.php',
        requireclase: 'Phx.vista.IndicadorValor',
        title: 'Indicador Valor Seguimiento',

        constructor: function (config) {
        	conf=config;
            this.maestro = config.maestro;
            this.initButtons = [this.contenidoImagen];
            Phx.vista.IndicadorValorPrincipal.superclass.constructor.call(this, config);
            this.init();
          
            this.grid.addListener('cellclick', this.oncellclick,this);
            this.grid.addListener('afteredit', this.onAfterEdit, this);
            
            Ext.getCmp('b-save-' + this.idContenedor).hide()   
            Ext.getCmp('b-new-' + this.idContenedor).hide()
        },
        onAfterEdit:function(prueba){
        	this.Insertar();
        },
        Insertar: function(){
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

							//VALIDAR FECHA
							if(TipoUnidad=='Fecha'){
								
								  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo1) && data[i].semaforo1!=''){
									//alert("Error formato de fecha"); 
									banderaFecha=true;
									NumeroFila=data[i]._fila+" semaforo 1";
								  }
								  else{
									  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo2) && data[i].semaforo2!=''){
										//alert("Error formato de fecha"); 
										banderaFecha=true;
										NumeroFila=data[i]._fila+" semaforo 2";
									  }
									  else{
										 if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo3) && data[i].semaforo3!=''){
											//alert("Error formato de fecha"); 
											banderaFecha=true;
											NumeroFila=data[i]._fila+" semaforo 3";
										  }
										  else{
											  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo4) && data[i].semaforo4!=''){
												//alert("Error formato de fecha"); 
												banderaFecha=true;
												NumeroFila=data[i]._fila+" semaforo 4";
											  }	
											  else{
												  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo5) && data[i].semaforo5!=''){
													//alert("Error formato de fecha"); 
													banderaFecha=true;
													NumeroFila=data[i]._fila+" semaforo 5";
												  }
												  else{
												  		var sem1=data[i].semaforo1; sem1=sem1.replace("-", "/"); sem1 = sem1.split("/"); sem1=sem1[2]+"/"+sem1[1]+"/"+sem1[0];
														var sem2=data[i].semaforo2; sem2=sem2.replace("-", "/"); sem2 = sem2.split("/"); sem2=sem2[2]+"/"+sem2[1]+"/"+sem2[0];
													    var sem3=data[i].semaforo3; sem3=sem3.replace("-", "/"); sem3 = sem3.split("/"); sem3=sem3[2]+"/"+sem3[1]+"/"+sem3[0];
														var sem4=data[i].semaforo4; sem4=sem4.replace("-", "/"); sem4 = sem4.split("/"); sem4=sem4[2]+"/"+sem4[1]+"/"+sem4[0];
														var sem5=data[i].semaforo5; sem5=sem5.replace("-", "/"); sem5 = sem5.split("/"); sem5=sem5[2]+"/"+sem5[1]+"/"+sem5[0];
													  	if(semaforo=="Simple" && comparacion=="Asc" ){
													  		if(Date.parse(sem1) >= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(Date.parse(sem2) >=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser menor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Simple" && comparacion=="Desc" ){
													  		if(Date.parse(sem1) <= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(Date.parse(sem2) <=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser mayor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Asc" ){
													  		if(Date.parse(sem1) >= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(Date.parse(sem2) >=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser menor al semaforo3');
													        }
										  		            if(Date.parse(sem3) >=  Date.parse(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo3 tiene que ser menor al semaforo4');
													        }
										  		            if(Date.parse(sem4) >=  Date.parse(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo4 tiene que ser menor al semaforo5');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Desc" ){
													  		if(Date.parse(sem1) <= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(Date.parse(sem2) <=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser mayor al semaforo3');
													        }
										  		            if(Date.parse(sem3) <=  Date.parse(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo3 tiene que ser mayor al semaforo4');
													        }
										  		            if(Date.parse(sem4) <=  Date.parse(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo4 tiene que ser mayor al semaforo5');
													        }
													  	}
												  }
											  }									  
										  }
									  }
								  }
							 }
							  //VALIDAR HORA
							if(TipoUnidad=='Hrs'){
								  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo1) && data[i].semaforo1!=''){
									//alert("Error formato de fecha"); 
									banderaHora=true;
									NumeroFila=data[i]._fila+" semaforo 1";
								  }
								  else{
									  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo2) && data[i].semaforo2!=''){
										//alert("Error formato de fecha"); 
										banderaHora=true;
										NumeroFila=data[i]._fila+" semaforo 2";
									  }
									  else{
										 if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo3) && data[i].semaforo3!=''){
											//alert("Error formato de fecha"); 
											banderaHora=true;
											NumeroFila=data[i]._fila+" semaforo 3";
										  }
										  else{
											  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo4) && data[i].semaforo4!=''){
												//alert("Error formato de fecha"); 
												banderaHora=true;
												NumeroFila=data[i]._fila+" semaforo 4";
											  }	
											  else{
												  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo5) && data[i].semaforo5!=''){
													//alert("Error formato de fecha"); 
													banderaHora=true;
													NumeroFila=data[i]._fila+" semaforo 5";
												  }
												  else{
												  		var sem1=data[i].semaforo1; sem1=sem1.replace(",", "."); 
														var sem2=data[i].semaforo2; sem2=sem2.replace(",", "."); 
													    var sem3=data[i].semaforo3; sem3=sem3.replace(",", "."); 
														var sem4=data[i].semaforo4; sem4=sem4.replace(",", "."); 
														var sem5=data[i].semaforo5; sem5=sem5.replace(",", "."); 
													  	if(semaforo=="Simple" && comparacion=="Asc" ){
													  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser menor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Simple" && comparacion=="Desc" ){
													  		if(parseFloat(sem1) <= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(parseFloat(sem2) <=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser mayor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Asc" ){
													  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser menor al semaforo3');
													        }
										  		            if(parseFloat(sem3) >=  parseFloat(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo3 tiene que ser menor al semaforo4');
													        }
										  		            if(parseFloat(sem4) >=  parseFloat(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo4 tiene que ser menor al semaforo5');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Desc" ){
													  		if(parseFloat(sem1) <= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(parseFloat(sem2) <=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser mayor al semaforo3');
													        }
										  		            if(parseFloat(sem3) <=  parseFloat(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo3 tiene que ser mayor al semaforo4');
													        }
										  		            if(parseFloat(sem4) <=  parseFloat(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo4 tiene que ser mayor al semaforo5');
													        }
													  	}
												  }
											  }									  
										  }
									  }
								  }
							 }
							 
							 if(TipoUnidad=='Numero'){
								  if(data[i].semaforo1!=''){
									      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo1)){
												banderaNumero=true;
												NumeroFila=data[i]._fila+" semaforo 1";
												FormatoNumero="(Formato válido número entero o con decimales)";
									  	  }
								  }
								  if(data[i].semaforo2!=''){
										      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo2)){
													banderaNumero=true;
													NumeroFila=data[i]._fila+" semaforo 2";
												    FormatoNumero="(Formato válido número entero o con decimales)";
										  	  }
									}
									if(data[i].semaforo3!=''){
											      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo3)){
														banderaNumero=true;
														NumeroFila=data[i]._fila+" semaforo 3";
												        FormatoNumero="(Formato válido número entero o con decimales)";
											  	  }
									 }
							         if(data[i].semaforo4!=''){
												      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo4)){
															banderaNumero=true;
															NumeroFila=data[i]._fila+" semaforo 4";
												            FormatoNumero="(Formato válido número entero o con decimales)";
												  	  }
									  }
									  var sem1=data[i].semaforo1; 
									  var sem2=data[i].semaforo2;
									  var sem3=data[i].semaforo3;
									  var sem4=data[i].semaforo4;
									  var sem5=data[i].semaforo5;
									  if(data[i].semaforo5!=''){
													      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo5)){
																banderaNumero=true;
																NumeroFila=data[i]._fila+" semaforo 5";
																FormatoNumero="(Formato válido número entero o con decimales)";
													  	  }
									  }
									  
							          if(banderaNumero == false){
													  	  	        // validando numero mayor en decimales
																  	if(semaforo=="Simple" && comparacion=="Asc" ){
																  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser menor al semaforo2');
																        }
													  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser menor al semaforo3');
																        }
																  	}
																  	if(semaforo=="Simple" && comparacion=="Desc" ){
																  		if(parseFloat(sem1) <= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser mayor al semaforo2');
																        }
													  		            if(parseFloat(sem2) <=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser mayor al semaforo3');
																        }
																  	}
																  	if(semaforo=="Compuesto" && comparacion=="Asc" ){
																  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser menor al semaforo2');
																        }
													  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser menor al semaforo3');
																        }
													  		            if(parseFloat(sem3) >=  parseFloat(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo3 tiene que ser menor al semaforo4');
																        }
													  		            if(parseFloat(sem4) >=  parseFloat(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo4 tiene que ser menor al semaforo5');
																        }
																  	}
																  	if(semaforo=="Compuesto" && comparacion=="Desc" ){
																  		if(parseInt(sem1) <= parseInt(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser mayor al semaforo2');
																        }
													  		            if(parseInt(sem2) <=  parseInt(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser mayor al semaforo3');
																        }
													  		            if(parseInt(sem3) <=  parseInt(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo3 tiene que ser mayor al semaforo4');
																        }
													  		            if(parseInt(sem4) <=  parseInt(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo4 tiene que ser mayor semaforo5');
																        }
																  	}
						               }		  
							  }
							 
						}
						if(banderaFecha==true){
						     alert("Error!! El formato de fecha ingresado en la fila "+NumeroFila+" no es valido  \n(Fomrato válido = dd/mm/yyyy)"); 
						}
						 else{
						     if(banderaHora==true){
						 	     alert("Error!! El formato de Hora ingresado en la fila "+NumeroFila+" \n(Formato válido numero entero o con decimales)"); 
						     }
						     else{
						     	if(banderaNumero==true){
						     		 alert("Error!! El formato ingresado en la fila "+NumeroFila+" no es valido  \n"+FormatoNumero); 
						     	}
						     }
						 }		
						 if(banderaFecha==false && banderaHora==false && banderaNumero==false && banderaGenerica==false){
						 	
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
       	oncellclick : function(grid, rowIndex, columnIndex, e) {
	        var record = this.store.getAt(rowIndex),
	            fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
	
	        // el estado gestion recupera el dato de la vista FormIndicador
	        if(banderaSeleccion=='true'){
	              alert("No se puede editar ninguna columna una vez aprobado la gestión");
	        }
       },
       contenidoImagen: new Ext.form.FormPanel({
         name: 'imagen',
         id:'imagen'
         //inputType: 'hidden',
       }),

       ValidarSemaforos: function(tipo){
           
       	    validarInput='NumberField';
       	    alert(tipo);
       	    
       	  
       	    //alert(validarInput);

       },
	   compararFechas : function(fechaInicial,fechaFinal)  
	   {  
            valuesStart=fechaInicial.split("/");
            valuesEnd=fechaFinal.split("/");
 
            var dateStart=new Date(valuesStart[2],(valuesStart[1]-1),valuesStart[0]);
            var dateEnd=new Date(valuesEnd[2],(valuesEnd[1]-1),valuesEnd[0]);
            if(dateStart>=dateEnd)
            {
                return 0;
            }
            return 1;
		},
		onButtonSave:function(o){
		    
		   
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

							//VALIDAR FECHA
							if(TipoUnidad=='Fecha'){
								
								  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo1) && data[i].semaforo1!=''){
									//alert("Error formato de fecha"); 
									banderaFecha=true;
									NumeroFila=data[i]._fila+" semaforo 1";
								  }
								  else{
									  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo2) && data[i].semaforo2!=''){
										//alert("Error formato de fecha"); 
										banderaFecha=true;
										NumeroFila=data[i]._fila+" semaforo 2";
									  }
									  else{
										 if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo3) && data[i].semaforo3!=''){
											//alert("Error formato de fecha"); 
											banderaFecha=true;
											NumeroFila=data[i]._fila+" semaforo 3";
										  }
										  else{
											  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo4) && data[i].semaforo4!=''){
												//alert("Error formato de fecha"); 
												banderaFecha=true;
												NumeroFila=data[i]._fila+" semaforo 4";
											  }	
											  else{
												  if(!/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(data[i].semaforo5) && data[i].semaforo5!=''){
													//alert("Error formato de fecha"); 
													banderaFecha=true;
													NumeroFila=data[i]._fila+" semaforo 5";
												  }
												  else{
												  		var sem1=data[i].semaforo1; sem1=sem1.replace("-", "/"); sem1 = sem1.split("/"); sem1=sem1[2]+"/"+sem1[1]+"/"+sem1[0];
														var sem2=data[i].semaforo2; sem2=sem2.replace("-", "/"); sem2 = sem2.split("/"); sem2=sem2[2]+"/"+sem2[1]+"/"+sem2[0];
													    var sem3=data[i].semaforo3; sem3=sem3.replace("-", "/"); sem3 = sem3.split("/"); sem3=sem3[2]+"/"+sem3[1]+"/"+sem3[0];
														var sem4=data[i].semaforo4; sem4=sem4.replace("-", "/"); sem4 = sem4.split("/"); sem4=sem4[2]+"/"+sem4[1]+"/"+sem4[0];
														var sem5=data[i].semaforo5; sem5=sem5.replace("-", "/"); sem5 = sem5.split("/"); sem5=sem5[2]+"/"+sem5[1]+"/"+sem5[0];
													  	if(semaforo=="Simple" && comparacion=="Asc" ){
													  		if(Date.parse(sem1) >= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(Date.parse(sem2) >=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser menor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Simple" && comparacion=="Desc" ){
													  		if(Date.parse(sem1) <= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(Date.parse(sem2) <=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser mayor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Asc" ){
													  		if(Date.parse(sem1) >= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(Date.parse(sem2) >=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser menor al semaforo3');
													        }
										  		            if(Date.parse(sem3) >=  Date.parse(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo3 tiene que ser menor al semaforo4');
													        }
										  		            if(Date.parse(sem4) >=  Date.parse(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo4 tiene que ser menor al semaforo5');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Desc" ){
													  		if(Date.parse(sem1) <= Date.parse(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(Date.parse(sem2) <=  Date.parse(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo2 tiene que ser mayor al semaforo3');
													        }
										  		            if(Date.parse(sem3) <=  Date.parse(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo3 tiene que ser mayor al semaforo4');
													        }
										  		            if(Date.parse(sem4) <=  Date.parse(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la fecha del semaforo4 tiene que ser mayor al semaforo5');
													        }
													  	}
												  }
											  }									  
										  }
									  }
								  }
							 }
							  //VALIDAR HORA
							if(TipoUnidad=='Hrs'){
								  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo1) && data[i].semaforo1!=''){
									//alert("Error formato de fecha"); 
									banderaHora=true;
									NumeroFila=data[i]._fila+" semaforo 1";
								  }
								  else{
									  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo2) && data[i].semaforo2!=''){
										//alert("Error formato de fecha"); 
										banderaHora=true;
										NumeroFila=data[i]._fila+" semaforo 2";
									  }
									  else{
										 if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo3) && data[i].semaforo3!=''){
											//alert("Error formato de fecha"); 
											banderaHora=true;
											NumeroFila=data[i]._fila+" semaforo 3";
										  }
										  else{
											  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo4) && data[i].semaforo4!=''){
												//alert("Error formato de fecha"); 
												banderaHora=true;
												NumeroFila=data[i]._fila+" semaforo 4";
											  }	
											  else{
												  if(!/^[1-9]\d*$|^[0-9]+([.]\d+)$|^-?\d+$|^-?\d+([.]\d+)$/.test(data[i].semaforo5) && data[i].semaforo5!=''){
													//alert("Error formato de fecha"); 
													banderaHora=true;
													NumeroFila=data[i]._fila+" semaforo 5";
												  }
												  else{
												  		var sem1=data[i].semaforo1; sem1=sem1.replace(",", "."); 
														var sem2=data[i].semaforo2; sem2=sem2.replace(",", "."); 
													    var sem3=data[i].semaforo3; sem3=sem3.replace(",", "."); 
														var sem4=data[i].semaforo4; sem4=sem4.replace(",", "."); 
														var sem5=data[i].semaforo5; sem5=sem5.replace(",", "."); 
													  	if(semaforo=="Simple" && comparacion=="Asc" ){
													  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser menor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Simple" && comparacion=="Desc" ){
													  		if(parseFloat(sem1) <= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(parseFloat(sem2) <=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser mayor al semaforo3');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Asc" ){
													  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser menor al semaforo2');
													        }
										  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser menor al semaforo3');
													        }
										  		            if(parseFloat(sem3) >=  parseFloat(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo3 tiene que ser menor al semaforo4');
													        }
										  		            if(parseFloat(sem4) >=  parseFloat(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo4 tiene que ser menor al semaforo5');
													        }
													  	}
													  	if(semaforo=="Compuesto" && comparacion=="Desc" ){
													  		if(parseFloat(sem1) <= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
													  		   banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo1 tiene que ser mayor al semaforo2');
													        }
										  		            if(parseFloat(sem2) <=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo2 tiene que ser mayor al semaforo3');
													        }
										  		            if(parseFloat(sem3) <=  parseFloat(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo3 tiene que ser mayor al semaforo4');
													        }
										  		            if(parseFloat(sem4) <=  parseFloat(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
										  		               banderaGenerica=true;
													           alert('Alerta!! Según las reglas establecidas la hora del semaforo4 tiene que ser mayor al semaforo5');
													        }
													  	}
												  }
											  }									  
										  }
									  }
								  }
							 }
							 
							 if(TipoUnidad=='Numero'){
								  if(data[i].semaforo1!=''){
									      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo1)){
												banderaNumero=true;
												NumeroFila=data[i]._fila+" semaforo 1";
												FormatoNumero="(Formato válido número entero o con decimales)";
									  	  }
								  }
								  if(data[i].semaforo2!=''){
										      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo2)){
													banderaNumero=true;
													NumeroFila=data[i]._fila+" semaforo 2";
												    FormatoNumero="(Formato válido número entero o con decimales)";
										  	  }
									}
									if(data[i].semaforo3!=''){
											      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo3)){
														banderaNumero=true;
														NumeroFila=data[i]._fila+" semaforo 3";
												        FormatoNumero="(Formato válido número entero o con decimales)";
											  	  }
									 }
							         if(data[i].semaforo4!=''){
												      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo4)){
															banderaNumero=true;
															NumeroFila=data[i]._fila+" semaforo 4";
												            FormatoNumero="(Formato válido número entero o con decimales)";
												  	  }
									  }
									  var sem1=data[i].semaforo1; 
									  var sem2=data[i].semaforo2;
									  var sem3=data[i].semaforo3;
									  var sem4=data[i].semaforo4;
									  var sem5=data[i].semaforo5;

									  if(data[i].semaforo5!=''){
													      if(!/^[1-9]\d*$|^[0-9]+([,\.]\d+)$|^-?\d+$|^-?\d+([,\.]\d+)$/.test(data[i].semaforo5)){
																banderaNumero=true;
																NumeroFila=data[i]._fila+" semaforo 5";
																FormatoNumero="(Formato válido número entero o con decimales)";
													  	  }
									  }
									  
							          if(banderaNumero == false){
													  	  	        // validando numero mayor en decimales
																  	if(semaforo=="Simple" && comparacion=="Asc" ){
																  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser menor al semaforo2');
																        }
													  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser menor al semaforo3');
																        }
																  	}
																  	if(semaforo=="Simple" && comparacion=="Desc" ){
																  		if(parseFloat(sem1) <= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser mayor al semaforo2');
																        }
													  		            if(parseFloat(sem2) <=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser mayor al semaforo3');
																        }
																  	}
																  	if(semaforo=="Compuesto" && comparacion=="Asc" ){
																  		if(parseFloat(sem1) >= parseFloat(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser menor al semaforo2');
																        }
													  		            if(parseFloat(sem2) >=  parseFloat(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser menor al semaforo3');
																        }
													  		            if(parseFloat(sem3) >=  parseFloat(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo3 tiene que ser menor al semaforo4');
																        }
													  		            if(parseFloat(sem4) >=  parseFloat(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo4 tiene que ser menor al semaforo5');
																        }
																  	}
																  	if(semaforo=="Compuesto" && comparacion=="Desc" ){
																  		if(parseInt(sem1) <= parseInt(sem2)  && data[i].semaforo1!="" && data[i].semaforo2!=""){
																  		   banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo1 tiene que ser mayor al semaforo2');
																        }
													  		            if(parseInt(sem2) <=  parseInt(sem3) && data[i].semaforo2!="" && data[i].semaforo3!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo2 tiene que ser mayor al semaforo3');
																        }
													  		            if(parseInt(sem3) <=  parseInt(sem4) && data[i].semaforo3!="" && data[i].semaforo4!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo3 tiene que ser mayor al semaforo4');
																        }
													  		            if(parseInt(sem4) <=  parseInt(sem5) && data[i].semaforo4!="" && data[i].semaforo5!=""){
													  		               banderaGenerica=true;
																           alert('Alerta!! Según las reglas establecidas el número del semaforo4 tiene que ser mayor semaforo5');
																        }
																  	}
						               }		  
							  }
							 
						}
						if(banderaFecha==true){
						     alert("Error!! El formato de fecha ingresado en la fila "+NumeroFila+" no es valido  \n(Fomrato válido = dd/mm/yyyy)"); 
						}
						 else{
						     if(banderaHora==true){
						 	     alert("Error!! El formato de Hora ingresado en la fila "+NumeroFila+" \n(Formato válido numero entero o con decimales)"); 
						     }
						     else{
						     	if(banderaNumero==true){
						     		 alert("Error!! El formato ingresado en la fila "+NumeroFila+" no es valido  \n"+FormatoNumero); 
						     	}
						     }
						 }		
						 if(banderaFecha==false && banderaHora==false && banderaNumero==false && banderaGenerica==false){
						 	
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
	   
	   successSave: function (resp) {
                Phx.vista.Indicador.superclass.successSave.call(this, resp);
                Phx.CP.getPagina(this.idContenedorPadre).reload();
       },
       cargarImagenIndicadorValor: function (semaforo, comparacion) {
            	
            	//document.getElementById("ext-gen186").innerHTML = '<div></div>';
            	
                if (semaforo == "Simple" && comparacion == "Asc") {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/SIMPLE ASC.png" width="120px">';
                }
                if (semaforo == "Simple" && comparacion == "Desc") {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/SIMPLE DESC.png" width="120px">';
                }
                if (semaforo == "Compuesto" && comparacion == "Asc") {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/COMPUESTO ASC.png" width="200px">';
                }
                if (semaforo == "Compuesto" && comparacion == "Desc") {
                    document.getElementById("imagen").innerHTML = '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/COMPUESTO DESC.png" width="200px">';
                }
       },   
       onReloadPage: function (m) {     
           
           if((m.semaforo=='Simple' && m.comparacion=='Asc') || (m.semaforo=='Simple' && m.comparacion=='Desc')){
	       	   this.setColumnHeader('semaforo1',String.format('<div style="background-color: {0};"> {1}</div>','#F9CAC4', 'semaforo1'));
	       	   this.setColumnHeader('semaforo2',String.format('<div style="background-color: {0};"> {1}</div>','#F9F7C4', 'semaforo2'));
	       	   this.setColumnHeader('semaforo3',String.format('<div style="background-color: {0};"> {1}</div>','#CEF9C4', 'semaforo3'));
           }
           else{
	           if((m.semaforo=='Compuesto' && m.comparacion=='Asc')){
		       	   this.setColumnHeader('semaforo1',String.format('<div style="background-color: {0};"> {1}</div>','#CEF9C4', 'semaforo1'));
		       	   this.setColumnHeader('semaforo2',String.format('<div style="background-color: {0};"> {1}</div>','#F9F7C4', 'semaforo2'));
		       	   this.setColumnHeader('semaforo3',String.format('<div style="background-color: {0};"> {1}</div>','#F9CAC4', 'semaforo3'));
		       	   this.setColumnHeader('semaforo4',String.format('<div style="background-color: {0};"> {1}</div>','#F9F7C4', 'semaforo4'));
		       	   this.setColumnHeader('semaforo5',String.format('<div style="background-color: {0};"> {1}</div>','#CEF9C4', 'semaforo5'));
	           }
	           else{
		           if((m.semaforo=='Compuesto' && m.comparacion=='Desc')){
			       	   this.setColumnHeader('semaforo1',String.format('<div style="background-color: {0};"> {1}</div>','#F9CAC4', 'semaforo1'));
			       	   this.setColumnHeader('semaforo2',String.format('<div style="background-color: {0};"> {1}</div>','#F9F7C4', 'semaforo2'));
			       	   this.setColumnHeader('semaforo3',String.format('<div style="background-color: {0};"> {1}</div>','#CEF9C4', 'semaforo3'));
			       	   this.setColumnHeader('semaforo4',String.format('<div style="background-color: {0};"> {1}</div>','#F9F7C4', 'semaforo4'));
			       	   this.setColumnHeader('semaforo5',String.format('<div style="background-color: {0};"> {1}</div>','#F9CAC4', 'semaforo5'));
		           }
	           }
           }

       	   
           this.maestro = m;
           var aa=this;
           Id_IndicadorRecibido=this.maestro.id_indicador;
           //this.ValidarSemaforos(this.maestro.tipo);
           this.cargarImagenIndicadorValor(this.maestro.semaforo,this.maestro.comparacion);

           
           this.store.baseParams = {id_indicador: this.maestro.id_indicador};
           this.load({params: {start: 0, limit: 50}})
            // ocultar del formulario this.ocultarComponente(this.Cmp.semaforo1);
           var colModel = this.grid.getColumnModel();


           console.log("atributos  ",this);
           
           console.log("grilla ",this.Cmp.semaforo1);
           console.log("grid  ",this.grid);
          //this.load();
          

          	
          	TipoUnidad=this.maestro.tipo;
          	semaforo=this.maestro.semaforo;
          	comparacion=this.maestro.comparacion;
          	if(this.maestro.tipo=='Numero'){
          		//this.Cmp.semaforo2.regex="^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$";
          		this.Cmp.semaforo2.maskRe=/[0-5/-]+/i;
          		this.Cmp.semaforo2.regex=/[0-5/-]+/i;
          		validarInput=/[0-5/-]+/i;
          	}
          	if(this.maestro.tipo=='Fecha'){
          		this.Cmp.semaforo2.maskRe=/[6-9/-]+/i;
          		this.Cmp.semaforo2.regex=/[6-9/-]+/i;
          		validarInput=/[6-9/-]+/i;
          		
          	}
          	//this.Cmp.semaforo2.store.setBaseParam('id_uo', Combo.getValue());
            this.Cmp.semaforo2.modificado = true;
          	
          	
          	
          	
          	

          	
          	console.log("Regex ",this.Cmp);
            //la variable frecuencia se usar al hacer click en la grilla de indicador valor
          	frecuencia=m.frecuencia;
            if(m.frecuencia=='Hito'){ 
                Ext.getCmp('b-new-' + this.idContenedor).show()
                Ext.getCmp('b-del-' + this.idContenedor).show()
                this.tbar.disabled=false;

                colModel.setHidden(2, false);
            }
            else{
               colModel.setHidden(2, true);
          	   Ext.getCmp('b-new-' + this.idContenedor).hide()
          	   Ext.getCmp('b-del-' + this.idContenedor).hide()
            }
            colModel.setHidden(8, true);
            colModel.setHidden(9, true); 
            colModel.setHidden(10, true); 
                                                               
            if(m.semaforo=='Simple'){
             colModel.setHidden(6, true);
             colModel.setHidden(7, true);
            }
            else{
              colModel.setHidden(6, false);
              colModel.setHidden(7, false); 
            } 
            
            //Ocultar del formularuio
            this.ocultarComponente(this.Cmp.valor);
            this.ocultarComponente(this.Cmp.justificacion); 
            this.ocultarComponente(this.Cmp.no_reporta); 
            

            Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/estadoGestion',
                        params: {
                            'id_gestion': this.maestro.id_gestion,
                        },
                        success: this.RespuestaEstadoGestion,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
            });

         },
         RespuestaEstadoGestion: function (s,m){
        	//alert(s);
        	this.maestro = m;
        	//this.store
           var estadoGestion = s.responseText.split('%');

           banderaSeleccion=estadoGestion[1];
            
            if(estadoGestion[1]=='true'){
            	Ext.getCmp('b-new-' + this.idContenedor).hide()
            	Ext.getCmp('b-del-' + this.idContenedor).hide()
            	Ext.getCmp('b-save-' + this.idContenedor).hide()
            	
            }
            else{
            	Ext.getCmp('b-save-' + this.idContenedor).show()
            	//Ext.getCmp('b-new-' + this.idContenedor).show()
                //Ext.getCmp('b-del-' + this.idContenedor).show()
                //Ext.getCmp('b-save-' + this.idContenedor).show()
            }
        },
        
        onButtonNew: function () {

           
            var me = this;
            Ext.Ajax.request({
                url: '../../sis_segintegralgestion/control/IndicadorValor/insertarIndicadorValorNuevo',
                params: {
                	id_indicador: Id_IndicadorRecibido,
                	semaforo3:'',
                	semaforo5:'',
                	no_reporta:'Reporta',
                	semaforo4:'',
                	semaforo2:'',
                	//valor:'',
                	//fecha:Date(),
                	hito:'',
                	semaforo1:'',
                	//justificacion:'',
                	},

                success: me.successSaveArb,
                failure: me.conexionFailure,
                timeout: me.timeout,
                scope: me
            });

            this.load();

          },
          
    
            
       bdel:true,
	   bsave:true,
	   bnew:true,
	   bedit:false,
        
        
    };
</script>
