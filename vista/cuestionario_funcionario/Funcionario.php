<?php
/**
 * @package pXP
 * @file AdendaDet.php
 * @author (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    
    
    Phx.vista.Funcionario = {
        require: '../../../sis_segintegralgestion/vista/cuestionario_funcionario/CuestionarioFuncionario.php',
        requireclase: 'Phx.vista.CuestionarioFuncionario',
        bedit: false,
        bnew: false,
        width:500,
        title: 'Funcionario',
        nombreVista: 'Funcionario',
        
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.Funcionario.superclass.constructor.call(this, config);
            this.init();		
            this.PanelEast.destroy();
            this.addHelp();
        },
        //
        onReloadPage: function (m) {     
            this.maestro = m;	   
            this.store.baseParams = {id_cuestionario: this.maestro.id_cuestionario};
            this.load({params: {start: 0, limit: 50}});	
        },
        //
        loadValoresIniciales: function () {    	
            Phx.vista.Funcionario.superclass.loadValoresIniciales.call(this);        
            this.Cmp.id_cuestionario.setValue(this.maestro.id_cuestionario);
        },        
        //
        liberaMenu: function() {
            var tb = Phx.vista.Funcionario.superclass.liberaMenu.call(this);
            if (tb) {
                this.getBoton('btnImprimir').hide();
            }
            return tb
        },
        //
        east:
        {
            url:'../../../sis_segintegralgestion/vista/evaluados/Evaluados.php',
            title:'Funacionarios',
            collapsed:true,
            width:400,
            cls:'Evaluados',
        },
        //
        Atributos:[
            {
                config: {
                    name: 'id_funcionarios',
                    fieldLabel: 'Funcionario',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
                        id: 'id_funcionario',
                        root: 'datos',
                        sortInfo:{
                            field: 'desc_person',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_funcionario','codigo','desc_person','ci','documento','telefono','celular','correo'],					
                        remoteSort: true,
                        baseParams: {par_filtro: 'FUNCIO.codigo#PERSON.nombre_completo2'}

                    }),
                    valueField: 'id_funcionario',
                    displayField: 'desc_person',
                    tpl:'<tpl for="."> <div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}</div> <p>{desc_person}</p> <p>CI:{ci}</p> </div></tpl>',
                    gdisplayField: 'desc_person',
                    hiddenName: 'id_funcionario',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '100%',
                    gwidth: 250,
                    minChars: 2,
                    enableMultiSelect: true,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_person']);
                    }
                },
                type: 'AwesomeCombo',
                id_grupo: 0,
                filters: {pfiltro: 'PERSON.desc_person',type: 'string'},
                grid: true,
                form: true
            },
            {
                config:{
                    name: 'sw_final',
                    fieldLabel: 'Evaluo?',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:10,
                    renderer: function (value, p, record){
                        if(record.data.sw_final == 'si'){
                            return String.format('<h1 style="color: #008042;">{0}</h1>', value);
                        } else{
                            return String.format('<h1 style="color: #ff0005;text-transform: uppercase;font-weight: bold;font-size:120%;">{0}</h1>', value);
                        }
                    }
                },
                    type:'TextField',
                    filters:{pfiltro:'cuefun.sw_final',type:'string'},
                    id_grupo:1,
                    grid:true,
                    form:false
            },
        ],
        id_store:'id_cuestionario_funcionario',
        fields: [
            {name:'id_cuestionario_funcionario', type: 'numeric'},
            {name:'sw_final', type: 'string'},            
            {name:'id_funcionarios', type: 'numeric'},
		    {name:'id_funcionario', type: 'numeric'},
            {name:'desc_person', type: 'string'},
        ],
        sortInfo:{
            field: 'sw_final',
            direction: 'ASC'
        },
        //
        addHelp: function () {
            this.addButton('lbl-color', {
                xtype: 'label',
                disabled: false,
                style: {
                    position: 'absolute',
                    top: '5px',
                    right: 0,
                    width: '90px',
                    'margin-right': '10px',
                    float: 'right'
                },
                html: '<div style="display: inline-flex">&nbsp;<div>Estado</div></div><br/>' +
                    '<div style="display: inline-flex"><div style="background-color:#008042;width:10px;height:10px;"></div>&nbsp;<div>Concluidos</div></div><br/>' +
                    '<div style="display: inline-flex"><div style="background-color:#ff0005;width:10px;height:10px;"></div>&nbsp;<div>En Proceso</div></div>'
            });
        },
    }
</script>