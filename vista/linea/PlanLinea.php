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
var bandera_boton_nuevo=0;
var arrayMeses=null;

var v_maestro=null;
var v_bandera_avance_previsto="";

Phx.vista.PlanLinea=Ext.extend(Phx.gridInterfaz,{

    constructor:function(config){
        this.maestro=config.maestro;
        //llama al constructor de la clase padre
        Phx.vista.PlanLinea.superclass.constructor.call(this,config);
        this.init();
        this.grid.addListener('cellclick', this.oncellclick,this);
        //this.load({params:{start:0, limit:11000}})
        this.store.baseParams = {id_plan: 0};
        //console.log("pagiancion ",this.tam_pag);
        this.OcultarBotones();


        this.addButton('btnLineaAvancee', {
            text: 'Avance previsto',
            iconCls: 'blist',
            disabled: true,
            handler: this.AsignarLineaAvance,
            tooltip: '<b>Avance previsto</b><br/>'
        });
        this.addButton('btnAvanceReal', {
            text: 'Avance real',
            iconCls: 'blist',
            disabled: true,
            handler: this.AsignarLineaAvanceReal,
            tooltip: '<b>Avance real</b><br/>'
        })
        // Inicio de columanas generadas                                
        //fin columnas generadas                                   
                                               

    },  
    
    AsignarLineaAvanceReal: function (record) {
        this.openFormLineaAvanceReal('new', this);
    },
    openFormLineaAvanceReal: function () {

        this.maestro=v_maestro;
        var me = this;

        me.objSolForm = Phx.CP.loadWindows('../../../sis_segintegralgestion/vista/linea_avance/FormAvanceReal.php',
            'Formulario de avance real',
            {
                modal: true,
                //width: '100%',
                 width: '80%',
                height: '60%'
            }, 
            {
                data: {
                    id_plan: v_id_plan

                }
            },
            this.idContenedor,
            'FormAvanceReal',
            );

    },
    AsignarLineaAvance: function (record) {
        
        if(v_bandera_avance_previsto!=""){
            alert(v_bandera_avance_previsto);
        }
        else{
            this.openFormLineaAvance('new', this);
        }
    },

    openFormLineaAvance: function () {
        this.GenerarColumnas();
        var me = this;
        
        console.log("Ver errores de formulario ",this);
        
        console.log("prueba de datos ", me);
        me.objSolForm = Phx.CP.loadWindows('../../../sis_segintegralgestion/vista/linea_avance/LineaAvance.php',
            'Formulario de avance previsto',
            {
                modal: true,
                //width: '100%',
                width: '90%',
                height: '60%'
            }, 
            {
                data: {
                    id_plan: v_id_plan,
                     meses: arrayMeses,
                     this:this
                    //tipo_form: tipo,
                    //datos_originales: record
                }
            },
            this.idContenedor,
            'LineaAvance',
            );

    },

    onReloadPage: function (m) {   

           this.maestro = m;

           //Ext.MessageBox.alert('Alerta!!', 'NO PUEDE crear mas SUB-NIVLELES.');
           this.store.baseParams = {id_plan: m.id_plan};
           //this.tam_pag PARA EL LIMIT NO FUNCIONA
           this.load({params: {start: 0, limit: 11000}})
           v_id_plan=m.id_plan;
           this.Cmp.id_plan.setValue(m.id_plan);
        
           //this.OcultarBotones();
           /*bandera_boton_nuevo=m.nivel;
           if(m.nivel>1){
               this.MostrarBotones();
               //this.GenerarColumnas();
           }*/

            //validar aumentando el peso acumulado del tercer nivel el peso acumulado del tercer nivel de la linea 
            if(m.nivel==2 && m.porcentaje_acumulado_aux>0 ){
                
                
                if(parseInt(m.aprobado)!=1){
                    this.getBoton('btnAvanceReal').disable();
                    this.getBoton('btnLineaAvancee').enable();

                    //this.OcultarBotones();

                }
                else{

                    this.getBoton('btnAvanceReal').enable();
                    this.getBoton('btnLineaAvancee').disable();
                    
                    

                }
                //console.log("seleccion ",this);
                //alert(this.root.childNodes);
            }
            else{
                this.getBoton('btnLineaAvancee').disable();
                this.getBoton('btnAvanceReal').disable();
                //this.OcultarBotones();
            }

            if(parseInt(m.aprobado)==1){
               this.OcultarBotones();
            }
            else{
                this.MostrarBotones();
            }

    },
    GenerarColumnas: function (){
                            Ext.Ajax.request({
                        url: '../../sis_segintegralgestion/control/LineaAvance/GenerarColumnaMeses',
                        params: {
                            'id_plan': v_id_plan,
                        },
                        success: this.RespuestaColumnas,
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
            });
    },
    RespuestaColumnas: function (s,m){
        this.maestro = m;
        var meses = s.responseText.split('%');
        arrayMeses = meses[1].split(",");
        


        
        var recText = this.id_store;
        /*for(i = 0; i < arrayMeses.length; i++){
            alert(arrayMeses[i]);               

        }*/
            

        
        console.log("Nuevo stores ",recText);
                      
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
    onButtonAct:function(){
        this.store.rejectChanges();
        Phx.CP.varLog=false;
        this.reload();
        this.grid.getSelectionModel().clearSelections();
        if(bandera_boton_nuevo>1){
            this.MostrarBotones();
        }
    },
    oncellclick : function(grid, rowIndex, columnIndex, e) {
        var record = this.store.getAt(rowIndex),
            fieldName = grid.getColumnModel().getDataIndex(columnIndex);
        
        if(record.data['nivel']=='2') {
            Ext.getCmp('b-new-' + this.idContenedor).hide()
            //esta bandera servira para mostrar boton al actualizar y desceleccionar en caso de ser nivel 2
        }
        else{
             Ext.getCmp('b-new-' + this.idContenedor).show()
        }
    },
                    
    Atributos:[
        /*{
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_linea_avance'
            },
            type:'Field',
            form:true 
        },*/
        {
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_linea_temporal'
            },
            type:'Field',
            form:true 
        },
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
                    name: 'id_plan'
            },
            type:'Field',
            form:true 
        },
        {
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_linea_padre'
            },
            type:'Field',
            form:true 
        },
        {
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'nivel'
            },
            type:'Field',
            form:true 
        },
        {
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
        },
        {
            config:{
                name: 'nombre_linea',
                fieldLabel: 'Nombre',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:655370,
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
                //filters:{pfiltro:'liav.avance_real',type:'numeric'},
                id_grupo:1,
                grid:true,
                form:true,
                //egrid:true
        },
        {
            config:{
                name: 'peso',
                fieldLabel: 'Peso',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:655370
            },
                type:'NumberField',
                //filters:{pfiltro:'liav.avance_real',type:'numeric'},
                id_grupo:1,
                grid:true,
                form:true,
                //egrid:true
        },
        {
            config:{
                name: 'peso_acumulado',
                fieldLabel: 'Peso acumulado',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:655370,
                renderer: function (value, p, record, rowIndex, colIndex){

                   if(record.data.nivel !=2 ){
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
        },
        {
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
                   if(record.data.nivel !=2 ){
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
        },
        {
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
                            var res = record.data['funcionarios'].replace(",","<br>");
                            return String.format('{0}', res);
                        }
                    },
                    //cambiar el tipo de combo
                    type: 'AwesomeCombo',
                    id_grupo: 0,
                    filters: {pfiltro: 'PERSON.desc_funcionario1', type: 'string'},
                    grid: true,
                    form: true,
                    //egrid:true
        },

        {
            config:{
                name: 'orden_logico',
                fieldLabel: 'orden_logico',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:50
            },
                type:'NumberField',
                filters:{pfiltro:'arb.orden_logico',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
        {
            config:{
                name: 'mes',
                fieldLabel: 'mes',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:50
            },
                type:'TextField',
                filters:{pfiltro:'liav.mes',type:'string'},
                id_grupo:1,
                grid:false,
                form:false
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
                filters:{pfiltro:'liav.estado_reg',type:'string'},
                id_grupo:1,
                grid:false,
                form:false
        },
        {
            config:{
                name: 'id_usuario_ai',
                fieldLabel: '',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:4
            },
                type:'Field',
                filters:{pfiltro:'liav.id_usuario_ai',type:'numeric'},
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
                filters:{pfiltro:'liav.usuario_ai',type:'string'},
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
                filters:{pfiltro:'liav.fecha_reg',type:'date'},
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
                filters:{pfiltro:'liav.fecha_mod',type:'date'},
                id_grupo:1,
                grid:false,
                form:false
        }
    ], 
    tam_pag:11000, 
    title:'Linea avance',
    /*ActSave:'../../sis_segintegralgestion/control/LineaAvance/insertarLineaAvance',
    ActDel:'../../sis_segintegralgestion/control/LineaAvance/eliminarLineaAvance',*/
    ActSave: '../../sis_segintegralgestion/control/Linea/insertarLinea',
    ActDel: '../../sis_segintegralgestion/control/Linea/eliminarLinea',     
    ActList:'../../sis_segintegralgestion/control/LineaAvance/listarLineaAvance_ordenado',
    id_store:'id_linea',
    fields: [
        {name:'id_linea_temporal', type: 'numeric'},
        {name:'id_linea_avance', type: 'numeric'},
        {name:'id_linea', type: 'numeric'},
        {name:'nombre_linea', type: 'string'},
        {name:'peso', type: 'numeric'},
        {name:'peso_acumulado', type: 'string'},
        {name:'peso_restante', type: 'string'},
        {name:'id_funcionarios', type: 'string'},
        {name:'funcionarios', type: 'string'},
        //{name:'mes', type: 'string'},
        
        //{name:'avance_previsto', type: 'string'},
        //{name:'avance_real', type: 'string'},
        //{name:'comentario', type: 'string'},
        //{name:'aprobado_real', type: 'string'},
        
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
        {name:'id_linea_padre', type: 'numeric'},
        {name:'id_plan', type: 'numeric'},
        {name:'orden_logico', type: 'numeric'},
        
    ],
    sortInfo:{
        field: 'id_linea',
        direction: 'ASC'
    },
    bdel:true,
    bsave:true, 
    onButtonNew: function () {
                Phx.vista.PlanLinea.superclass.onButtonNew.call(this);
                
                if(this.sm.selections.items==''){
                    this.Cmp.nivel.setValue(null);
                    this.Cmp.id_plan.setValue(v_id_plan);  
                    this.Cmp.id_linea_padre.setValue(null);  
                    this.Cmp.id_linea.setValue(null);  
                }
                else{
                    this.Cmp.linea_padre.setValue(this.sm.selections.items[0].data.linea_padre);
                    this.Cmp.id_plan.setValue(this.sm.selections.items[0].data.id_plan);
                    this.Cmp.nivel.setValue(parseInt((this.sm.selections.items[0].data.nivel==null?0:this.sm.selections.items[0].data.nivel))+1);
                    this.Cmp.id_linea.setValue(null);
                    this.Cmp.id_linea_padre.setValue(this.sm.selections.items[0].data.id_linea);   
                }
       },
    }
)
</script>
        
