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
    var estadoBotonAprobado;

    var estadoGestion=null;

    Phx.vista.FormIndicador = {
        //bsave: false,
        require: '../../../sis_segintegralgestion/vista/indicador/Indicador.php',
        requireclase: 'Phx.vista.Indicador',
        title: 'Indicadores',
        
        constructor: function (config) {
            this.maestro = config.maestro;
           
         
            Phx.vista.FormIndicador.superclass.constructor.call(this, config);
            this.init();
            

            
            this.addButton('btnCerrarGestion', {
				text : 'Desaprobar gestión',
				iconCls : 'block',
				disabled : true,
				handler : this.EstadoGestionCerrar,
				tooltip : '<b>Desaprobar gestión</b> Nadie puede insertar ni modificar datos en la gestión dada'
			});
			this.addButton('btnAbrirGestion', {
				text : 'Aprobar gestión',
				iconCls : 'bunlock',
				disabled : true,
				handler : this.EstadoGestionAbrir,
				tooltip : '<b>Aprobar gestión</b> Permitir registrar datos en la gestión dada'
			});	
			
			this.getBoton('btnAbrirGestion').hide();
			this.getBoton('btnCerrarGestion').hide();
			
            estadoBotonAprobado=this;
             
			this.BloqueMenuIndicadores();
			 //this.IndicadorRespuestaEstadoGestion();

        },
        onReloadPage:function(m){
		   this.maestro=m;
		
	   },
       EstadoGestionAbrir: function (estado) {
                //verifica si se seleccion al guna gestión
          console.log('aprobar gestion ', this.cmbGestion.getValue())
          //para actualizar la variable de banera gestion
          
          
          this.verEstadoIndicador();  
   
       },
       verEstadoIndicador: function(){

       	            Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/verEstadoIndicador',
                        params: {
                            'id_gestion': this.cmbGestion.getValue(),
                        },
                        success: this.RespuestaverEstadoIndicador,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
            });
       	
       },
       

       RespuestaverEstadoIndicador: function (s,m){

          this.maestro = m;

          estadoGestion = s.responseText.split('%');
           
          if(estadoGestion[1]=='true'){
              alert("Alerta!! Complete los semaforos en los indicadores de color naranja");
          }
          else{
               
                var estado=true;

                if (this.cmbGestion.getValue()) {
                    //var rec = this.sm.getSelected();
                    //var data = rec.data;
                  //  Phx.CP.loadingShow();
                    Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/aprobarGestion',
                        params: {
                            'id_gestion': this.cmbGestion.getValue(),
                            'estado': estado
                        },
                        success: this.MensajeEstadoGestion,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
                    });

                }
                else {
                    alert('No se selecciono ninguna gestión para aprobar ó desaprobar')
                }
                
                 this.BloqueMenuIndicadores(); 
                 
           } 

           this.reload();
           
           
       },
       
       onButtonNew: function() {
       	Phx.vista.Indicador.superclass.onButtonNew.call(this);
       	this.cargarImagen("","");
       	if(estadoBotonAprobado.cmbGestion.getValue()==false){
       	   	alert("Seleccione una gestion");
       	}
        this.window.buttons[0].show();
        this.form.getForm().reset();
        this.loadValoresIniciales();
        this.window.show();
        if(this.getValidComponente(0)){
        	this.getValidComponente(0).focus(false,100);
        }
        
		   //this.store.baseParams={id_depto: this.maestro.id_depto};

          // this.store.baseParams.id_gestion=this.cmbGestion.getValue();
          // this.load({params:{start:0, limit:50}})
        
		
	   },

       BloqueMenuIndicadores: function(){

       	            Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/estadoGestion',
                        params: {
                            'id_gestion': this.cmbGestion.getValue(),
                        },
                        success: this.IndicadorRespuestaEstadoGestion,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
            });
       	
       },
       IndicadorRespuestaEstadoGestion: function (s,m){

           this.maestro = m;

           estadoGestion = s.responseText.split('%');


           Ext.getCmp('b-save-' + this.idContenedor).hide()
            if(estadoGestion[1]=='true'){
            	
            	Ext.getCmp('b-new-' + this.idContenedor).hide()
            	Ext.getCmp('b-edit-' + this.idContenedor).hide()
            	Ext.getCmp('b-del-' + this.idContenedor).hide()
            	

                estadoBotonAprobado.getBoton('btnCerrarGestion').enable();
                estadoBotonAprobado.getBoton('btnAbrirGestion').disable();
            }
            else{
            	Ext.getCmp('b-new-' + this.idContenedor).show()
            	Ext.getCmp('b-edit-' + this.idContenedor).show()
            	Ext.getCmp('b-del-' + this.idContenedor).show()
                estadoBotonAprobado.getBoton('btnCerrarGestion').disable();
                estadoBotonAprobado.getBoton('btnAbrirGestion').enable();

            }
            
            if(estadoBotonAprobado.cmbGestion.getValue()==false){
       	   	   Ext.getCmp('b-new-' + this.idContenedor).hide()
       	    }
            //this.reload();
       },
 
    
       EstadoGestionCerrar: function (estado) {
                //verifica si se seleccion al guna gestión
                console.log('aprobar gestion ', this.cmbGestion.getValue())

                var estado=false;

                if (this.cmbGestion.getValue()) {
                    //var rec = this.sm.getSelected();
                    //var data = rec.data;

                  //  Phx.CP.loadingShow();
                    Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/Indicador/aprobarGestion',
                        params: {
                            'id_gestion': this.cmbGestion.getValue(),
                            'estado': estado
                        },
                        success: this.MensajeEstadoGestionDesaprobado,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
                    });

                }
                else {
                    alert('No se selecciono ninguna gestión para aprobar ó desaprobar')
                }
                 this.load();
                 this.BloqueMenuIndicadores();
       },

       MensajeEstadoGestion:function(res){
            this.BloqueMenuIndicadores();
	     	alert("Gestión aprobada");

	    },
        MensajeEstadoGestionDesaprobado:function(res){
            this.BloqueMenuIndicadores();
	     	alert("Gestión desaprobada");

	    },
	    

        tabeast: [
            {
                // url: '../../../sis_segproyecto/vista/actividad/ActividadNieto.php',
                url: '../../../sis_segintegralgestion/vista/indicador_valor/IndicadorValorPrincipal.php',
                title: 'Indicador valor seguimiento principal',
                width: 500,
                cls: 'IndicadorValorPrincipal'
            }
        ],
        
       bdel:true,
	   bsave:true,
	   bnew:true,
	   bedit:true,
	   
	/*   preparaMenu: function(n) {
	  	var tb = Phx.vista.FormIndicador.superclass.preparaMenu.call(this);
	   	this.getBoton('btnCerrarGestion').setDisabled(false);
	  	this.getBoton('btnAbrirGestion').setDisabled(false);
	   	
	   	
  		return tb;
	   },
	   liberaMenu: function() {
		var tb = Phx.vista.FormIndicador.superclass.liberaMenu.call(this);
	   	this.getBoton('btnCerrarGestion').setDisabled(true);
	 	this.getBoton('btnAbrirGestion').setDisabled(true)
	   },*/

        
    };
</script>
