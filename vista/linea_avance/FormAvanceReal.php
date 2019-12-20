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
var v_id_plan;   //cuando es id no es necesario inicar la variable solo en el constructor
var v_mes='';
var v_cod_linea_avance;
var v_aprobado=null;
Phx.vista.FormAvanceReal=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){

		
		this.configMaestro=config;
		this.config=config;
		this.maestro=config.maestro;
		
		
		v_id_plan= this.configMaestro.data.id_plan;  
		console.log("recibir config111 ", this.configMaestro.data.id_plan);
		console.log("recibir config ", this.configMaestro.id_plan);
		
		this.initButtons = [this.cmbMeses];
    	//llama al constructor de la clase padre
    	
       
            
		Phx.vista.FormAvanceReal.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();
		
		this.grid.addListener('cellclick', this.oncellclick,this);
        this.grid.addListener('afteredit', this.onAfterEdit, this);
 
        this.addButton('btnAprobado', {
            text: 'Aprobrar',
            iconCls: 'block',
            disabled: true,
            handler: function () {
                this.aprobar_linea_avance('true')
            },
            tooltip: '<b>Realiza la aprobación del mes seleccionado</b>'
        });
        
        this.addButton('btnDesAprobado', {
            text: 'Desaprobrar',
            iconCls: 'bunlock',
            disabled: true,
            handler: function () {
                this.aprobar_linea_avance('false')
            },
            tooltip: '<b>Realiza la desaprobación del mes seleccionado</b>'
        });
        
        this.cmbMeses.on('select', this.capturaFiltros, this);
        //console.log("testear parametros recibidos ",this.data.id_plan);
		
		//para que no cargue cuando es detalle
		//this.load({params:{start:0, limit:this.tam_pag} ,id_plan: this.data.id_plan})
		
		
        this.store.baseParams={'id_plan': this.data.id_plan, 'mes': ''};		
        this.finCons = true;
        this.load();
        //this.grid.addListener('cellclick', this.oncellclick,this.data.id_plan);
        
	},
	iniciarEventos : function () {
	    
	    this.cmbMeses.store.load({params:{start:0,limit:this.tam_pag}, 
           callback : function (r) {
           	console.log("ver combo ",r);
               
                    this.cmbMeses.setValue(r[r.length-1].data.id_linea_avance);  
                    this.cmbMeses.fireEvent('select', r[r.length-1]);                  
 
                                
            }, scope : this
        });
        this.cmbMeses.on('select', function(combo, record, index) {   
        	
        // el try se activa la primera vez y el catch para que funcione con el record	         
           try{
            v_mes=combo.data.mes;
            v_cod_linea_avance=combo.data.cod_linea_avance;
            this.BloquearBotones();
            this.store.baseParams={'id_plan': v_id_plan , 'mes': combo.data.mes};	
           }
           catch(error){
            v_mes=record.data.mes;
            v_cod_linea_avance=record.data.cod_linea_avance;
            this.BloquearBotones();
            this.store.baseParams={'id_plan': v_id_plan , 'mes': record.data.mes};	
           }

            this.load();
            
        } , this);
	},
	oncellclick : function(grid, rowIndex, columnIndex, e) {
	 	var record = this.store.getAt(rowIndex),
        fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

        if((record.data['aprobado_real']=='t' && fieldName=='avance_real')||(record.data['aprobado_real']=='t' && fieldName=='comentario')){
            alert("No se puede editar una vez aprobado el mes");
        }
        else{
        	if((fieldName=='avance_real'  && record.data['nivel']!=2) || (fieldName=='comentario'  && record.data['nivel']!=2) || (fieldName=='dato'  && record.data['nivel']!=2) ){
	 			//alert("Solo se puede editar en el nivel 3");
	 			this.reload();
	 		}
        }
 		

	},
    aprobar_linea_avance: function (valorAprobado) {

       var banderaDesaprobar=false;
       var banderaAcumuladorReal=false;
     
       for(var i=0; i< this.cmbMeses.store.data.items.length;i++){
	       	 if( (parseInt(this.cmbMeses.store.data.items[i].data['cod_linea_avance']) > parseInt(v_cod_linea_avance)) && valorAprobado!='true'){
	       	 		if(this.cmbMeses.store.data.items[i].data['aprobado_real']=='t'){
	       	 		    banderaDesaprobar=true;	
	       	 		}
	       	 }
       }
       
       for(var i=0; i< this.store.data.items.length;i++){
	       	 if(parseFloat(this.store.data.items[i].data['acumulado_real'])>parseFloat(100.00) && valorAprobado=='true' ){
	       	 	banderaAcumuladorReal=true;
	       	 }
       }  
       
       
       if(banderaAcumuladorReal==true){
       	  alert("ALERTA!! el acumulado real no puede ser mayor a 100");
       }
       else{
		       if(banderaDesaprobar==true){
		           alert("Para desaprobar el mes de "+v_mes+"  debe desaprobar los meses superiores  a dicho mes");
		       }
		       else{
			        var me = this;
			        v_aprobado=valorAprobado;
			        if (this.cmbMeses.getValue()) {
			            //var rec = this.sm.getSelected();
			            //var data = rec.data;
			            
			            //Phx.CP.loadingShow();
			            Ext.Ajax.request({
			                url: '../../sis_segintegralgestion/control/LineaAvance/aprobarAvanceReal',
			                params: {
			                    'id_plan':v_id_plan,
			                    'mes':v_mes,
			                    'estado':valorAprobado
			                },
			                success: me.successSaveAprobar,
			                failure: me.conexionFailureAprobar,
			                timeout: me.timeout,
			                scope: me
			            });
			
			        }
			        else {
			            Ext.MessageBox.alert('ALERTA!!!', 'Seleccione un mes.');
			
			        }
		       }       
		 }

    },
    successSaveAprobar: function () {
    	
    	//this.cmbMeses.store.load();
    	this.cmbMeses.modificado=true;
    	this.reload();
    	console.log("testear store de combo ",this.cmbMeses);
    	 
        Phx.CP.loadingHide();
        this.BloquearBotones();
        //this.root.reload();
        if(v_aprobado=='true'){
        	 Ext.MessageBox.alert('EXITO!!', 'Aprobado');
        }
        else{
             Ext.MessageBox.alert('EXITO!!', 'Desaprobado');
        }
        
       
    },
    capturaFiltros: function (combo, record, index) {

/*
            //bloquearMenuPlan: function () {
            v_mes=record.data.mes;
            v_cod_linea_avance=record.data.cod_linea_avance;
            this.BloquearBotones();
            //},

            console.log("Testear mes ", v_mes);
            //alert("captura filtros ",this.cmbMeses.getValue());

            this.store.baseParams={'id_plan': v_id_plan , 'mes': record.data.mes};	
            this.load();*/
            
    },
    BloquearBotones: function (){
	      Ext.Ajax.request({
                url: '../../sis_segintegralgestion/control/LineaAvance/EstadoAvanceReal',
                params: {
                    //'id_linea_avance': this.cmbMeses.getValue(),
                    'id_plan':v_id_plan,
                    'mes':v_mes
                },
                success: this.successBloquearBotones,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
            
    },
    successBloquearBotones: function (s, m) {
        //this.tbar.items.get('b-new-' + this.idContenedor).disable()

        var estadoAvanceReal = s.responseText.split('%');

        if (estadoAvanceReal[1] == 'true') {
            this.getBoton('btnDesAprobado').enable();
            this.getBoton('btnAprobado').disable();
        }
        else {
            this.getBoton('btnDesAprobado').disable();
            this.getBoton('btnAprobado').enable();
        } 

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
    onAfterEdit:function(prueba){

       var columna=prueba.field;
       var cod_id_linea=prueba.record.data['cod_linea'];
       var cod_linea_padre=prueba.record.data['cod_linea_padre'];
       var peso=prueba.record.data['peso'];
       
        console.log("probar codigos de hijos ", prueba.record.data['cod_hijos']);
        
       var array_hijos=(prueba.record.data['cod_hijos']).split(',');
       var sum_avance_padre=0;

        //console.log("me", prueba.record.data['feb17']);
        console.log("probar stores  ", prueba);


       //this.calcular(array_hijos,cod_linea_padre,prueba);
        this.store.each(function(r){
		           if(r.data['cod_linea'].trim()== cod_id_linea && columna=='avance_real'){
		       	           if((prueba.value).toString().trim()==''){
					        	  r.set(columna,0);
					       }
		           }
		       	
		},this);
       
       
        this.store.each(function(r){
		           if(r.data['cod_linea'].trim()== cod_linea_padre && columna=='avance_real'){
		                   array_hijos=(r.data['cod_hijos']).split(',');

		                   var sumar=0.00;
		                   for (var i=0;i<array_hijos.length;i++){
			                        this.store.each(function(rr){
					                   	if(rr.data['cod_linea'].trim()== array_hijos[i].trim()){
					                   	  sumar += ( (parseFloat(rr.data[columna])) * parseFloat(rr.data['peso']) )/100;
					                   	 
					                   	}
			                   	
			                        },this);
			                        
			                        //alert("valor peso  "+array_hijos[i]);
			                         
		                   }
		       	           r.set(columna,sumar);
                           //alert(r.data['cod_hijos']);
		       	           cod_linea_padre=r.data['cod_linea_padre'];
		       	           array_hijos=(r.data['cod_hijos']).split(',');

		           }
		       	
		},this);
		
		
        this.store.each(function(r){
		           if(r.data['cod_linea'].trim()== cod_linea_padre && columna=='avance_real'){
		                   array_hijos=(r.data['cod_hijos']).split(',');
		                   var sumar=0.00;
		                   for (var i=0;i<array_hijos.length;i++){
			                        this.store.each(function(rr){
					                   	if(rr.data['cod_linea'].trim()== array_hijos[i].trim()){
					                   	  sumar += ( (parseFloat(rr.data[columna])) * parseFloat(rr.data['peso']) )/100;
					                   	}
			                   	
			                        },this);
		                   }
		       	           r.set(columna,sumar);

		       	           cod_linea_padre=r.data['cod_linea_padre'];
		       	           array_hijos=(r.data['cod_hijos']).split(',');
		       	           
		           }
		       	
		},this);
		
		this.InsertarAvanceReal();
		//totales
		/*var contTotal=0;
        this.store.each(function(r){
        	
        	console.log(r);
            var sumTotal=0.00;
           
			for (var i=0;i<arrayMeses.length-1;i++){
				sumTotal +=parseFloat(r.data[arrayMeses[i]]);
			}
			r.set('total',sumTotal);

		},this);*/
		

		
    },
    
    InsertarAvanceReal: function (){
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
						 
					}
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
    },
    
    cmbMeses: new Ext.form.ComboBox({
        fieldLabel: 'Meses',
        allowBlank: true,
        emptyText: 'Meses...',
        store: new Ext.data.JsonStore(
            {
                url: '../../sis_segintegralgestion/control/LineaAvance/listarMeses',
                id: 'id_linea_avance',
                root: 'datos',
                sortInfo: {
                    field: 'mes',
                    direction: 'DESC'
                },
                totalProperty: 'total',
                fields: ['id_linea_avance', 'mes','aprobado_real','cod_linea_avance'],
                // turn on remote sorting
                remoteSort: true,
                baseParams: {par_filtro: 'mes',id_plann: v_id_plan},

            }),
        valueField: 'id_linea_avance',
        triggerAction: 'all',
        displayField: 'mes',
        hiddenName: 'id_linea_avance',
        mode: 'remote',
        pageSize: 50,
        queryDelay: 500,
        listWidth: '280',
        width: 80
    }),
         	
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_linea'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'aprobado_real'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'cod_hijos'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'cod_linea'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'cod_linea_padre'
			},
			type:'Field',
			form:true 
		},
	    {
			config:{
				name: 'nombre_linea',
				fieldLabel: 'Nombre linea',
				allowBlank: true,
				anchor: '100%',
				gwidth: 500,
				maxLength:100,
                renderer: function (value, p, record, rowIndex, colIndex){

                   var espacion_blanco="";
                   var duplicar="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                   var nivel = record.data.nivel==null?0:record.data.nivel;
                   var espacion_blanco = duplicar.repeat(nivel);


                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                            
                        


                   if(record.data.nivel ==1 || record.data.nivel==null){
                   	    return  String.format(espacion_blanco+' <img src="../../../lib/imagenes/a_form_edit.png" > '+ record.data.nombre_linea);

                   }
                   else{
                    	if(record.data.nivel == 2 ){
                   	        return  String.format(espacion_blanco+' <img src="../../../lib/imagenes/a_form.png"> '+ record.data.nombre_linea);
                    	}
                    	else{
                    		return  record.data.nombre_linea;
                    	}
                   }
                }
			},
				type:'TextField',
				filters:{pfiltro:'arb.nombre_linea',type:'string'},
				id_grupo:1,
				grid:true,
				//egrid:true,
				form:true
		},
		{
			config:{
				name: 'peso',
				fieldLabel: 'Peso',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50,
				renderer: function (value, p, record, rowIndex, colIndex){

                        if(record.data['nivel']!=1 && record.data['nivel']!=2){
                            p.style=p.style+"background-color:#EAE9E9; ";
                        }
                        if(record.data['nivel']==1 ){
                            p.style=p.style+"background-color:#F3F3F3; ";
                        }
                            
                   	    return "<font color='red'>"+record.data.peso+"</font>";

	            }
				//maskRe: validarInput,
				//regex: validarInput
			},
				type:'NumberField',
				filters:{pfiltro:'arb.peso',type:'string'},
				id_grupo:1,
				grid:true,
				//egrid:true,
				form:true
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'nivel'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'linea_padre'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_linea_padre'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_plan'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_linea_avance'
			},
			type:'Field',
			form:true 
		},
	    {
			config:{
				name: 'avance_previsto',
				fieldLabel: 'Av. previsto',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100,
				renderer: function (value, p, record) {
                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                    return record.data['avance_previsto'];
                },
			},
				type:'NumberField',
				filters:{pfiltro:'avance_previsto',type:'string'},
				id_grupo:1,
				grid:true,
				//egrid:true,
				form:true
		},
	    {
			config:{
				name: 'avance_real',
				fieldLabel: 'Av. real',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100,
				renderer: function (value, p, record) {
                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                    return record.data['avance_real'];
                },
			},
				type:'NumberField',
				filters:{pfiltro:'avance_real',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
	    {
			config:{
				name: 'acumulado_previsto',
				fieldLabel: 'Ac. previsto',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100,
				renderer: function (value, p, record) {
                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                    return record.data['acumulado_previsto'];
                },
			},
				type:'NumberField',
				filters:{pfiltro:'acumulado_previsto',type:'string'},
				id_grupo:1,
				grid:true,
				//egrid:true,
				form:true
		},
	    {
			config:{
				name: 'acumulado_real',
				fieldLabel: 'Ac. real',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100,
				renderer: function (value, p, record) {
                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                    return record.data['acumulado_real'];
                },
			},
				type:'NumberField',
				filters:{pfiltro:'acumulado_real',type:'string'},
				id_grupo:1,
				grid:true,
				//egrid:true,
				form:true
		},
	    {
			config:{
				name: 'desviacion',
				fieldLabel: 'Desviacion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100,
				renderer: function (value, p, record) {
                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                    return record.data['desviacion'];
                },
			},
				type:'NumberField',
				filters:{pfiltro:'desviacion',type:'string'},
				id_grupo:1,
				grid:true,
				//egrid:true,
				form:true
		},
		{
			config:{
				name: 'comentario',
				fieldLabel: 'Comentario',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50,
                renderer: function (value, p, record, rowIndex, colIndex){

                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                    
                
                    if(record.data.comentario=='null'){
                   	    return  '';
                    }
                    else{
                    	return  record.data.comentario;
                    }
                }
			},
				type:'TextField',
				filters:{pfiltro:'la.comentario',type:'string'},
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
				filters:{pfiltro:'arb.estado_reg',type:'string'},
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
				filters:{pfiltro:'arb.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'arb.usuario_ai',type:'string'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'arb.fecha_reg',type:'date'},
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
				filters:{pfiltro:'arb.cuenta',type:'string'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'id_usuario_reg',
				fieldLabel: 'id_usuario_reg',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'arb.id_usuario_reg',type:'string'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'mes'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'dato',
				fieldLabel: 'Datos',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50,
				renderer: function (value, p, record) {
                    if(record.data['nivel']!=1 && record.data['nivel']!=2){
                        p.style=p.style+"background-color:#EAE9E9; ";
                    }
                    if(record.data['nivel']==1 ){
                        p.style=p.style+"background-color:#F3F3F3; ";
                    }
                    return record.data['dato'];
                },
			},
				type:'TextField',
				filters:{pfiltro:'dato',type:'string'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},

	],
	tam_pag:5000,	
	title:'Avance real',
	ActSave:'../../sis_segintegralgestion/control/LineaAvance/insertarAvanceReal',
	ActDel:'../../sis_segintegralgestion/control/IndicadorValor/eliminarIndicadorValor',
	ActList:'../../sis_segintegralgestion/control/LineaAvance/listarAvanceReal',
	id_store:'id_linea',
	fields: [
		{name:'id_linea', type: 'numeric'},
		{name:'nombre_linea', type: 'string'},
		{name:'peso', type: 'numeric'},
		{name:'nivel', type: 'numeric'},
		{name:'linea_padre', type: 'string'},
		{name:'id_linea_padre', type: 'numeric'},
		{name:'id_plan', type: 'numeric'},
		{name:'id_linea_avance', type: 'numeric'},
		{name:'avance_previsto', type: 'numeric'},
		{name:'avance_real', type: 'numeric'},
		{name:'acumulado_previsto', type: 'numeric'},
		{name:'acumulado_real', type: 'numeric'},
		{name:'desviacion', type: 'numeric'},
		{name:'comentario', type: 'string'},
		{name:'aprobado_real', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'mes', type: 'string'},
		{name:'cod_hijos', type: 'string'},
		{name:'cod_linea', type: 'numeric'},
		{name:'cod_linea_padre', type: 'numeric'},
		{name:'dato', type: 'string'},
	],
	sortInfo:{
		field: 'id_linea',
		direction: 'ASC'
	},
	
	    bedit:false,
	    bdel:false,
	    bsave:false,
	    bnew:false,
	    
	
}

        
	
)
</script>
		
		