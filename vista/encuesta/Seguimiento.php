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
    
    
    Phx.vista.Seguimiento = {
        require: '../../../sis_segintegralgestion/vista/cuestionario/Cuestionario.php',
        requireclase: 'Phx.vista.Cuestionario',
        bedit: false,
        bnew: false,
        title: 'Seguimiento de Evaluaciones',
        nombreVista: 'Seguimiento',
        gruposBarraTareas:[{name:'enviado',title:'<H1 align="center"><i class="fa fa-eye"></i> Enviados</h1>',grupo:1,height:0}],	
        actualizarSegunTab: function(name, indice){		
            if(this.finCons){			
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.Seguimiento.superclass.constructor.call(this, config);
            this.init();	
            this.PanelSouth.destroy();
            this.load({params:{start:0, limit:this.tam_pag, pes_estado: 'enviado'}})		
            this.finCons = true;
            this.addHelp();
        },
        addHelp: function () {
            this.addButton('lbl-color', {
                xtype: 'label',
                disabled: false,
                style: {
                    position: 'absolute',
                    top: '5px',
                    right: 0,
                    width: '250px',
                    'margin-right': '10px',
                    float: 'right'
                },
                html: '<div style="display: inline-flex">' +
                '<img src="../../../sis_segintegralgestion/vista/ImagenesIndicador/loguito360-01.png" width="250px"></div>'
            });
        },
        //
        onReloadPage: function (m) {
            this.maestro = m;        
        },
        //
        liberaMenu: function() {
            var tb = Phx.vista.Seguimiento.superclass.liberaMenu.call(this);
            if (tb) {
                this.getBoton('btnenviarCorreo').hide();
            }
            return tb
        },
        //
        east:
        {
            url: '../../../sis_segintegralgestion/vista/cuestionario_funcionario/Funcionario.php',
            title: 'Funcionarios',
            width: 500,
            cls: 'Funcionario'            
        },
        south:
		{
			url: '../../../sis_segintegralgestion/vista/cuestionario_funcionario/CuestionarioFuncionario.php',
			title: '',
			height: '50%',
            cls: 'CuestionarioFuncionario',            
            collapsed:true
        },
    }
</script>