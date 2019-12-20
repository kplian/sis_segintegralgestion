 <?php
/**
*@package pXP
*@file gen-Agrupador.php
*@author  (admin)
*@date 05-06-2017 04:46:40
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?> 

<script>
var arbol = null;
var estadoGestionAgrupador=null;
Phx.vista.Agrupador=Ext.extend(Phx.arbGridInterfaz,
	{
		nombreVista: 'Agrupador',
		//
		constructor:function(config)
		{
			this.maestro=config.maestro;
			this.initButtons = [this.cmbGestion,this.cmbPeriodo];				        
	    	//llama al constructor de la clase padre
			Phx.vista.Agrupador.superclass.constructor.call(this,config);							
			this.loaderTree.baseParams = {id_gestion: undefined};        
			this.init();	
				   	      
			//this.cmbGestion.on('select', this.capturaFiltros, this);  
			
			this.cmbGestion.on('select', function(combo, record, index){
                this.sm.clearSelections()  //#6 endetr Juan 03/06/2019 Correccion de cache 
				this.tmpGestion = record.data.gestion;
				this.cmbPeriodo.modificado = true;
			    this.cmbPeriodo.enable();
			    this.cmbPeriodo.reset();
			    //this.store.removeAll();
			    //alert(this.cmbGestion.getValue());
			    //console.log(this.cmbPeriodo);
			    this.cmbPeriodo.store.baseParams = Ext.apply(this.cmbPeriodo.store.baseParams, {id_gestion: this.cmbGestion.getValue()});
			    //this.store.baseParams.id_periodo = this.cmbPeriodo.getValue();
			    
			    
			    //this.capturaFiltros();
	        },this);
			
			this.cmbPeriodo.on('select', function( combo, record, index){
				if (this.cmbPeriodo.getValue()) {
					 this.tmpPeriodo = record.data.periodo;
			         this.capturaFiltros();
				}
				else{
					alert("Seleccione un periodo para la evaluación");
				}
			   
            },this);
			
			//this.tbar.items.get('b-new-' + this.idContenedor).disable()
			this.tbar.items.get('b-edit-' + this.idContenedor).disable()
			this.tbar.items.get('b-del-' + this.idContenedor).disable()			
			this.addButton('btnAprobado', {
                text: 'Aprobar',
                iconCls: 'block',
                disabled: true,
                handler: function () {
                    this.aprobarPlanes(1)
                },
                tooltip: '<b>Aprobar</b>'
            });

            this.addButton('btnDesAprobado', {
                text: 'Desaprobar',
                iconCls: 'bunlock',
                disabled: true,
                handler: function () {
                    this.aprobarPlanes(0)
                },	
                tooltip: '<b>Desaprobar</b>'
            });
            this.addButton('btnParametrizacion', {
                text: 'Parametrización',
                iconCls: 'x-btn-text bengine',
                disabled: false,
                handler: function () {
                    this.parametrizacion()
                },	
                tooltip: '<b>Desaprobar</b>'
            });
            this.addButton('btnCuadroMando', {
                text: 'Cuadro de mando',
                iconCls: ' x-btn-text bword',
                disabled: false,
                handler: function () {
                    this.CuadroMando()
                },  
                tooltip: '<b>Imprime el cuadro de mando integral</b>'
            });

            this.bloquearMenuPlan(); 
            this.desplegarArbol();     
            
            this.getBoton('btnAprobado').hide();
			this.getBoton('btnDesAprobado').hide();
			this.getBoton('btnParametrizacion').hide();
            this.getBoton('btnCuadroMando').hide();
			  
		},
        CuadroMando: function () {

            Phx.CP.loadingShow();
            Ext.Ajax.request({
            url: '../../sis_segintegralgestion/control/Agrupador/reporteCuadroDeMando',
            params: {
                'id_gestion': this.cmbGestion.getValue(),
                'id_periodo': this.cmbPeriodo.getValue(),
                'gestion': this.cmbGestion.lastSelectionText,
                'periodo': this.cmbPeriodo.lastSelectionText,

            },
            success: this.successExport,
            failure: this.conexionFailure,
            timeout: this.timeout,
            scope: this
            });
        },
        successExport : function(resp) {
            Phx.CP.loadingHide();
            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            var nomRep = objRes.ROOT.detalle.archivo_generado;
            if (Phx.CP.config_ini.x == 1) {
                nomRep = Phx.CP.CRIPT.Encriptar(nomRep);
            }
            window.open('../../../lib/lib_control/Intermediario.php?r=' + nomRep+'&t='+new Date().toLocaleTimeString())
        },
        /*parametrizacion: function (record) {
            this.openInterpretacion('new', this);
        },*/
        parametrizacion: function () {

           // this.maestro=v_maestro;
            var me = this;

            me.objSolForm = Phx.CP.loadWindows('../../../sis_segintegralgestion/vista/agrupador/InterpretacionIndicador.php',
                'Parametrizacion indicador',
                {
                    modal: true,
                    //width: '100%',
                    width: '30%',
                    height: '30%'
                }, 
                {
                    data: {
                        id_gestion: this.cmbGestion.getValue()

                    }
                },
                this.idContenedor,
                'InterpretacionIndicador',
                );

        },
		//
		desplegarArbol:function(){
			this.loaderTree.baseParams = {id_gestion: this.cmbGestion.getValue(), id_periodo: this.cmbPeriodo.getValue()};
            this.tbar.items.get('b-new-' + this.idContenedor).enable()
			this.tbar.items.get('b-edit-' + this.idContenedor).enable()
			this.tbar.items.get('b-del-' + this.idContenedor).enable()			
						
			this.bloquearMenuPlan();
            this.root.reload();
            this.treePanel.expandAll();	
		},		
		//filtra de acuerdo al parametro gestion
        capturaFiltros: function (combo, record, index) {
        	this.desplegarArbol();	
        	this.tbar.items.get('b-new-' + this.idContenedor).enable() 
        	
        	this.getBoton('btnAprobado').show();
			this.getBoton('btnDesAprobado').show();
			this.getBoton('btnParametrizacion').show();
            this.getBoton('btnCuadroMando').show();
            
			
			
	        //this.desbloquearOrdenamientoGrid();
	        /*this.store.baseParams.nombreVista = this.nombreVista;
	        if(this.validarFiltros()){
	        	this.store.baseParams.id_gestion = this.cmbGestion.getValue();
		        this.store.baseParams.id_periodo = this.cmbPeriodo.getValue();
		        this.store.baseParams.id_depto = this.cmbDepto.getValue();
		        
		        this.load(); 
	        }*/ 
			
        },        
		//cargar el combo de gestion
        loadValoresIniciales: function () {
            Phx.vista.Agrupador.superclass.loadValoresIniciales.call(this);            
            this.getComponente('id_gestion').setValue(this.cmbGestion.getValue());                               
        },   
        //creacion de nodo, hoja o hijo
        onButtonNew: function () {        			  
        	if (this.cmbGestion.getValue()) {
        		var nodo = this.sm.getSelectedNode();        		        		
                if (nodo) {   
                	 if (nodo.attributes.nivel < 2) {                	 	                	 
                        Phx.vista.Agrupador.superclass.onButtonNew.call(this);                        
                        this.getComponente('nivel').setValue((nodo.attributes.nivel > 0) ? (parseInt(nodo.attributes.nivel) + 1) : 1);
                        this.getComponente('id_agrupador_padre').setValue(nodo.attributes.id_agrupador);
                        this.getComponente('aprobado').setValue(0);    
                        if(nodo.attributes.nivel==null){                                             	 
                	 		this.getComponente('nombre_agrupador_padre').setValue('Raiz - '+  nodo.attributes.nombre);	
                	 	}else{
                	 		if (nodo.attributes.nivel == 1) {                 	 		        	 			             	 	
                	 			//this.getComponente('nombre_agrupador_padre').setValue(nodo.attributes.nombre_agrupador_padre);
                	 			this.getComponente('nombre_agrupador_padre').setValue(nodo.attributes.nombre);
                	 		}else{                	                	 				
                	 			this.getComponente('nombre_agrupador_padre').setValue(nodo.attributes.nombre);	
                	 		} 		                	
                	 	}                         
                    } else {
                        Ext.MessageBox.alert('ERROR', 'NO PUEDE CREAR MAS NODOS');
                    }                
                } else {
                    Phx.vista.Agrupador.superclass.onButtonNew.call(this);                    
                    this.getComponente('nombre_agrupador_padre').setValue('Raiz');                    
                    this.getComponente('aprobado').setValue(0);
                    this.getComponente('peso').setValue(100);         
                }
            }
            else {
                Ext.MessageBox.alert('SELECCIONE UNA GESTION');
            }
            this.sm.clearSelections();            
            nodo=null;            
        },     	         
        //
        aprobarPlanes: function (valorAprobado) {
            var me = this;
            if (this.cmbGestion.getValue()) {
            	if(this.cmbPeriodo.getValue()||valorAprobado==0){
	            	Phx.CP.loadingShow();
	                Ext.Ajax.request({
	                    url: '../../sis_segintegralgestion/control/Agrupador/aprobarPlanes',
	                    params: {
	                        'id_gestion': this.cmbGestion.getValue(),
	                        'aprobado'  : valorAprobado,
	                        'id_periodo'  :this.cmbPeriodo.getValue(),
	                    },
	                    success: me.successSaveAprobar,
	                    failure: me.conexionFailureAprobar,
	                    timeout: me.timeout,
	                    scope: me
	                });
            	}
            	else{
            		Ext.MessageBox.alert('ERROR!!!', 'Seleccione un periodo para la evaluación');
            	}

            }
            else {
                Ext.MessageBox.alert('ERROR!!!', 'Seleccione primero una gestion.');
            }
        },
        //
        successSaveAprobar: function () {
            Phx.CP.loadingHide();
            this.bloquearMenuPlan();
            this.root.reload();
            Ext.MessageBox.alert('EXITO!!!', 'Se realizo con exito la operación.');
        },       
        //
        conexionFailureAprobar: function () {
            Phx.CP.loadingHide(); 		
            this.bloquearMenuPlan();
            alert('No estan validados los datos, le falta llenar un nivel, o le falta completar un peso')
            this.root.reload();
        },     
        //
        conexionFailureAprobar: function () {
            Phx.CP.loadingHide();
            this.bloquearMenuPlan();
            alert('No estan validados los datos, le falta llenar un nivel, o le falta completar un peso')
            this.root.reload();

        },
        //muestra/oculta botones
        preparaMenu: function (n) {
            // llamada funcion clase padre
            if (n.attributes.aprobado == 0) {
                this.tbar.items.get('b-edit-' + this.idContenedor).enable()
                this.tbar.items.get('b-del-' + this.idContenedor).enable()
                if (n.attributes.id_gestion > 0 || n.attributes.tipo_nodo == 'hijo' || n.attributes.tipo_nodo == 'raiz' || n.attributes.id == 'id') {
                    this.tbar.items.get('b-new-' + this.idContenedor).enable()
                }
                else {
                    this.tbar.items.get('b-new-' + this.idContenedor).disable()
                }
            }
            if (n.attributes.aprobado == 1) {
                this.tbar.items.get('b-new-' + this.idContenedor).disable()
                this.tbar.items.get('b-edit-' + this.idContenedor).disable()
                this.tbar.items.get('b-del-' + this.idContenedor).disable()
            }
        }, 
		//
        successSave: function (resp) {        	
        	this.desplegarArbol();
            Phx.vista.Agrupador.superclass.successSave.call(this, resp);
            //this.root.reload();            
        },  
        //
        bloquearMenuPlan: function () {
            Ext.Ajax.request({
                url: '../../sis_segintegralgestion/control/Agrupador/estadoGestion',
                params: {
                    'id_gestion': this.cmbGestion.getValue(),
                },
                success: this.successBloquearMenu,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        //
        successBloquearMenu: function (s, m) {
            //this.tbar.items.get('b-new-' + this.idContenedor).disable();            
            estadoGestionAgrupador = s.responseText.split('%');            
            if (estadoGestionAgrupador[1] == '1') {
                this.getBoton('btnDesAprobado').enable();
                this.getBoton('btnAprobado').disable();
            }
            else if (estadoGestionAgrupador[1] == '0') {
                this.getBoton('btnDesAprobado').disable();
                this.getBoton('btnAprobado').enable();
            } else {
                if (m.params.id_gestion != "") {
                    this.tbar.items.get('b-new-' + this.idContenedor).enable();
                }
                this.getBoton('btnDesAprobado').disable();
                this.getBoton('btnAprobado').disable();
            }
            //this.tbar.items.get('b-new-' + this.idContenedor).enable();
        },        
	    //
        onButtonAct: function () {
            this.root.reload();
            //this.desplegarArbol();
        },
        onButtonEdit : function() {
        	Phx.vista.Agrupador.superclass.onButtonEdit.call(this);    
        	this.root.reload();            
        },
        //
        successDel: function (resp) {
            Phx.CP.loadingHide();
            //resp.argument.nodo.reload()
            //this.onButtonAct()
            this.desplegarArbol();
        },
        //
        liberaMenu: function () {
            Phx.vista.Agrupador.superclass.liberaMenu.call(this);
            this.tbar.items.get('b-new-' + this.idContenedor).disable()
            this.bloquearMenuPlan();
        },       
        //combo gestion en el tbar
	    cmbGestion: new Ext.form.ComboBox({
            fieldLabel: 'Gestion',
            allowBlank: true,            
            emptyText: 'Gestion...',            
            store: new Ext.data.JsonStore(
            {
                url: '../../sis_parametros/control/Gestion/listarGestion',
                id: 'id_gestion',
                root: 'datos',
                sortInfo: {
                    field: 'gestion',
                    direction: 'DESC'
                },
                
                totalProperty: 'total',
                fields: ['id_gestion', 'gestion'],
                remoteSort: true,
                baseParams: {par_filtro: 'gestion'}
            }),
            valueField: 'id_gestion',            
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode: 'remote',
            pageSize: 50,
            queryDelay: 500,
            listWidth: '280',
            width: 80
        }),
        
        cmbPeriodo: new Ext.form.ComboBox({
				fieldLabel: 'Periodo',
				allowBlank: false,
				blankText : 'Mes',
				emptyText:'Periodo...',
				store:new Ext.data.JsonStore(
				{
					url: '../../sis_parametros/control/Periodo/listarPeriodo',
					id: 'id_periodo',
					root: 'datos',
					sortInfo:{
						field: 'periodo',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_periodo','periodo','id_gestion','literal'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'gestion'}
				}),
				valueField: 'id_periodo',
				triggerAction: 'all',
				displayField: 'literal',
			    hiddenName: 'id_periodo',
    			mode:'remote',
				pageSize:50,
				disabled: true,
				queryDelay:500,
				listWidth:'280',
				width:80
		}),
	
        
        //formulario				
		Atributos:[
			{
				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_agrupador'
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
				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_agrupador_padre'
				},
				type:'Field',
				form:true 
			},
			{
				config: {
                    name: 'nombre_agrupador_padre',
                    fieldLabel: 'Nivel Superior',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
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
                config: {
                    name: 'nombre',
                    fieldLabel: 'Nombre',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 150
                },
                type: 'TextField',
                filters: {pfiltro: 'ssig_ag.nombre', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
			{
				//combo simple 
				config: {
                    name: 'id_funcionario',
                    fieldLabel: 'Responsable',
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
                    valueField: 'id_funcionario',//valor que se le dara de acuerdo al sotre del combo
                    displayField: 'desc_person',                   
                    gdisplayField: 'desc_person',//poner el parametro que viene de la BD del grid
                    hiddenName: 'id_funcionario',//es el mismo nombre del name
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
                    renderer: function (value, p, record) {
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
				config:{
					name: 'descripcion',
					fieldLabel: 'Descripcion',
					allowBlank: true,
					anchor: '80%',
					gwidth: 100,
					maxLength:1000
				},
				type:'TextField',
				filters:{pfiltro:'ssig_ag.descripcion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
			},
			{
				config:{
					name: 'nivel',
					fieldLabel: 'nivel',
					allowBlank: true,
					anchor: '80%',
					inputType:'hidden',	
					gwidth: 100,
					maxLength:4
				},
				type:'NumberField',
				filters:{pfiltro:'ssig_ag.nivel',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:true
			},
			{
				config:{
					name: 'peso',
					fieldLabel: 'Peso',
					allowBlank: false,
					anchor: '80%',
					gwidth: 100,
					maxLength:4,
					maxValue:100,
					minValue:0
				},
				type:'NumberField',
				filters:{pfiltro:'ssig_ag.peso',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
			},
            {
                config: {
                    name: 'porcentaje_acumulado',
                    fieldLabel: 'Peso acumulado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4,
                },
                type: 'TextField',
                filters: {pfiltro: 'ssig_ag.peso', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'porcentaje_restante',
                    fieldLabel: 'Peso restante',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'NumberField',
                filters: {pfiltro: 'ssig_ag.peso', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },						
 			{
				config: {
		            name: 'aprobado',
					fieldLabel: 'aprobado',
					inputType: 'hidden',
					gwidth: 50,
				},
				type: 'Field',
			    grid: true,
			    form: true
			},
            {
                config: {
                    name: 'resultado',
                    fieldLabel: 'Resultado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4,
                    gtpl: function (p){
                      if(this.resultado  == null){
                        return "No reporta";
                      }
                      else{
                        return this.resultado;
                      }
                        
                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'ssig_ag.resultado', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config:{
                    name: 'orden_logico',
                    fieldLabel: 'Orden Lógico',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4,
                    maxValue:100,
                    minValue:0
                },
                type:'NumberField',
                filters:{pfiltro:'ssig_ag.orden_logico',type:'numeric'},
                id_grupo:1,
                grid:false,
                form:true
            },
            {
                config: {
                    name: 'orden_logico_temporal',
                    fieldLabel: 'Orden Lógico',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 50,
                    gtpl: function (p){
                      
                        return this.orden_logico;
                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'orden_logico_temporal', type: 'string'},
                id_grupo: 0,
                grid: true,
                //egrid: true,
                form: false
            },
			{
				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_periodo'
				},
				type:'Field',
				form:true 
			},	
		],
		//propiedades del arbol
	    NodoCheck: false,//si los nodos tienen los valores para el check
	    id_nodo: 'id_agrupador',
	    id_nodo_p: 'id_agrupador_padre',
	    enableDD: false,
	    rootVisible: false,
	    //propiedades del tbar
	    title:'Agrupador',
		ActSave:'../../sis_segintegralgestion/control/Agrupador/insertarAgrupador',
		ActDel:'../../sis_segintegralgestion/control/Agrupador/eliminarAgrupador',
		ActList:'../../sis_segintegralgestion/control/Agrupador/listarAgrupadorArb',
	    id_store:'id_agrupador',	    
	    baseParams: {clasificacion: true},	    
	    fwidth: 420,
	    fheight: 250,	
	    //grid
		fields: [
			{name:'id_agrupador', type: 'numeric'},
			{name:'id_agrupador_padre', type: 'numeric'},
			{name:'id_funcionario', type: 'numeric'},
			{name:'nombre', type: 'string'},			
			{name:'descripcion', type: 'string'},			
			{name:'nivel', type: 'numeric'},
			{name:'peso', type: 'numeric'},
			{name:'id_gestion', type: 'numeric'},
			{name: 'aprobado', type: 'string'},		
			{name: 'nombre_agrupador_padre', type: 'string'},
            {name: 'porcentaje_acumulado', type: 'string'},
            {name: 'porcentaje_restante', type: 'string'},
            {name: 'resultado', type: 'numeric'},
            {name: 'id_periodo', type: 'numeric'},
            {name: 'orden_logico', type: 'numeric'},
            {name: 'orden_logico_temporal', type: 'string'},
            
		],
		/*sortInfo:{
			field: 'orden_logico',
			direction: 'asc'
		},*/
		bnew: true,
        bsave: false,
        bedit: true,
        bdel: true,
		//grilla ubicada a la derecha de la pantalla(este)		
		tabeast: 
		[{
            url: '../../../sis_segintegralgestion/vista/agrupador_indicador/AgrupadorIndicador.php',
            title: 'Agrupador/Indicador',
            width: 700,
            cls: 'AgrupadorIndicador'
        }],		
	}
)
</script>
		
		