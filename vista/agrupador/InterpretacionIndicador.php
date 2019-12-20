<?php
/**
 * @package pXP
 * @file gen-Indicador.php
 * @author  (admin)
 * @date 21-11-2016 14:51:35
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    var v_maestro=null;
    
    Phx.vista.InterpretacionIndicador = Ext.extend(Phx.gridInterfaz, {
            constructor: function (config) {
                this.maestro = config.maestro;
                v_maestro = config;
                //llama al constructor de la clase padre
                Phx.vista.InterpretacionIndicador.superclass.constructor.call(this, config);
                this.init();

                //this.store.baseParams = {id_gestion: 0};
                this.load({params: {start: 0, limit: this.tam_pag}})
                //this.buildComponentesDetalle();
                this.iniciarEventos();
            },
            iniciarEventos: function () {
            	
                this.store.baseParams = {id_gestion: v_maestro.data.id_gestion};//agregado para filtro y enviar parametro
				this.load({params: {start: 0, limit: this.tam_pag}})
            },
            Atributos: [
            {
   				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_interpretacion_indicador'
				},
				type:'Field',
				form:true 
			},
			{
				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_gestion'
				},
				type:'Field',
				form:true 
			},	
			{
				config: {
                    name: 'interpretacion',
                    fieldLabel: 'Interpretacion',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 150,
                    disabled: true
                },
                type: 'TextField',
                filters: {pfiltro: 'padre.nombre', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
			},
			{
                config: {
                    name: 'porcentaje',
                    fieldLabel: 'Porcentaje',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 150
                },
                type: 'TextField',
                filters: {pfiltro: 'li.porcentaje', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true,
                egrid:true
            },
			{
                config: {
                    name: 'icono',
                    fieldLabel: 'Icono',
                    allowBlank: false,
                    anchor: '50%',
                    gwidth: 150,
                    maxLength: 150,
                    renderer: function (value, p, record) {

                    		return String.format('<p><img src="'+record.data['icono']+'"  width="15%" alt="Completado" /></p>');
                    },
                },
                type: 'TextField',
                filters: {pfiltro: 'li.icono', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            
            
            ],
            
            onButtonEdit: function () {

               Phx.vista.InterpretacionIndicador.superclass.onButtonEdit.call(this);
               this.cargarImagen(this.Cmp.semaforo.getValue(), this.Cmp.comparacion.getValue());
                           
            },
    
            tam_pag: 100,
            title: 'Interpretacion indicador',
            ActSave: '../../sis_segintegralgestion/control/Agrupador/modificarInterpretacionIndicador',
            ActList: '../../sis_segintegralgestion/control/Agrupador/listarInterpretacionIndicador',
            
            id_store: 'id_interpretacion_indicador',
            
            fields: [
		        {name:'id_interpretacion_indicador', type: 'numeric'},
			    {name:'id_gestion', type: 'numeric'},
			    {name:'interpretacion', type: 'string'},
			    {name:'porcentaje', type: 'numeric'},
			    {name:'icono', type: 'string'},

            ],
            sortInfo: {
                field: 'id_interpretacion_indicador',
                direction: 'ASC'
            },
            bdel: false,
            bsave: true,
            bnew: false,
            bedit: false,
        }
    )
</script>
		