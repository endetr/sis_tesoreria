<?php
/**
*@package pXP
*@file SolicitudRendicionDet.php
*@author  (gsarmiento)
*@date 16-12-2015 15:14:01
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.SolicitudRendicionDet=Ext.extend(Phx.gridInterfaz,{
	tipoDoc: 'compra',
	id_estado_workflow : 0,
	id_proceso_workflow : 0,
	constructor:function(config){

		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.SolicitudRendicionDet.superclass.constructor.call(this,config);

		this.init();
		this.addButton('fin_registro',
			{	text:'Mandar Revision',
				iconCls: 'badelante',
				disabled:true,
				handler:this.sigEstado,
				tooltip: '<b>Mandar Revision</b><p>Mandar a revision facturas</p>'
			}
		);

		this.load({params:{start:0, limit:this.tam_pag, id_solicitud_efectivo:this.id_solicitud_efectivo}, me : this, callback:function(r,o,s){

			if(r[0].data.id_estado_wf != '' && r[0].data.id_proceso_wf) {
				o.me.getBoton('fin_registro').enable();
				o.me.id_estado_workflow = r[0].data.id_estado_wf;
				o.me.id_proceso_workflow = r[0].data.id_proceso_wf;
			}
		} });

		if (parseFloat(this.dias_no_rendido) < 0){
			this.getBoton('edit').setVisible(false);
			this.getBoton('new').setVisible(false);
		}
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_solicitud_rendicion_det'
			},
			type:'Field',
			form:true 
		},
		/*{
			config: {
				name: 'id_proceso_caja',
				fieldLabel: 'id_proceso_caja',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_/control/Clase/Metodo',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_',
				displayField: 'nombre',
				gdisplayField: 'desc_',
				hiddenName: 'id_proceso_caja',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: true,
			form: true
		},*/
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_solicitud_efectivo'
			},
			type:'Field',
			form:true 
		},
		/*{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_plantilla'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_moneda'
			},
			type:'Field',
			form:true 
		},*/
		{
			config:{
				name: 'desc_plantilla',
				fieldLabel: 'Tipo Documento',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'pla.desc_plantilla',type:'string'},
				bottom_filter: true,
				id_grupo:0,
				grid:true,
				form:false
		},		
		{
			config:{
				name: 'fecha',
				fieldLabel: 'Fecha',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dc.fecha',type:'date'},
				id_grupo:0,
				grid:true,
				form:false
		},
		{
			config: {
				name: 'id_doc_compra_venta',
				fieldLabel: 'Razon Social',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_/control/Clase/Metodo',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_',
				displayField: 'nombre',
				gdisplayField: 'desc_',
				hiddenName: 'id_doc_compra_venta',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['razon_social']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'dc.razon_social',type: 'string'},
			grid: true,
			form: true
		},		
		{
			config:{
				name: 'nit',
				fieldLabel: 'Nit',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'dc.nit',type:'string'},
				bottom_filter: true,
				id_grupo:0,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'nro_documento',
				fieldLabel: 'Nro Factura',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'dc.nro_documento',type:'string'},
				bottom_filter: true,
				id_grupo:0,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'nro_autorizacion',
				fieldLabel: 'Nro Autorizacion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'dc.nro_autorizacion',type:'string'},
				bottom_filter: true,
				id_grupo:0,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'importe_doc',
				fieldLabel: 'Importe Total',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179650,				
				renderer:function (value,p,record){
					return  String.format('{0}', value);
				}
			},
			type:'NumberField',
			id_grupo:0,
			grid:true,
			form:true
		},		
		{
			config:{
				name: 'importe_descuento',
				fieldLabel: 'Descuento',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179650,				
				renderer:function (value,p,record){
					return  String.format('{0}', value);
				}
			},
			type:'NumberField',
			id_grupo:0,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'importe_descuento_ley',
				fieldLabel: 'Descuento Ley',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179650,				
				renderer:function (value,p,record){
					return  String.format('{0}', value);
				}
			},
			type:'NumberField',
			id_grupo:0,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'importe_excento',
				fieldLabel: 'Excento',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179650,				
				renderer:function (value,p,record){
					return  String.format('{0}', value);
				}
			},
			type:'NumberField',
			id_grupo:0,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'importe_pago_liquido',     //RAC 04/01/2018, ...esta import ede documento esto es un error no cosideraba el liquido pagable
				fieldLabel: 'Liquido Pagable',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179650,				
				renderer:function (value,p,record){
						if(record.data.tipo != 'summary'){
							return  String.format('{0}', value);
						}
						else{
							return  String.format('<b><font size=2 >{0}</font><b>', value==null?0:value);
						}
						
					}
			},
				type:'NumberField',
				filters:{pfiltro:'rend.monto',type:'numeric'},
				id_grupo:0,
				grid:true,
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
				filters:{pfiltro:'rend.estado_reg',type:'string'},
				id_grupo:0,
				grid:true,
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
				id_grupo:0,
				grid:true,
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
				filters:{pfiltro:'rend.fecha_reg',type:'date'},
				id_grupo:0,
				grid:true,
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
				filters:{pfiltro:'rend.usuario_ai',type:'string'},
				id_grupo:0,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'rend.id_usuario_ai',type:'numeric'},
				id_grupo:0,
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
				filters:{pfiltro:'rend.fecha_mod',type:'date'},
				id_grupo:0,
				grid:true,
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
				id_grupo:0,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Rendicion',
	ActSave:'../../sis_tesoreria/control/SolicitudRendicionDet/insertarSolicitudRendicionDet',
	ActDel:'../../sis_tesoreria/control/SolicitudRendicionDet/eliminarSolicitudRendicionDet',
	ActList:'../../sis_tesoreria/control/SolicitudRendicionDet/listarSolicitudRendicionDet',
	id_store:'id_solicitud_rendicion_det',
	fields: [
		{name:'id_solicitud_rendicion_det', type: 'numeric'},
		{name:'id_proceso_caja', type: 'numeric'},
		{name:'id_solicitud_efectivo', type: 'numeric'},
		{name:'id_doc_compra_venta', type: 'numeric'},
		{name:'desc_plantilla', type: 'string'},
		{name:'desc_moneda', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'id_plantilla', type: 'numeric'},
		{name:'id_moneda', type: 'numeric'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'nit', type: 'string'},
		{name:'razon_social', type: 'string'},
		{name:'nro_autorizacion', type: 'string'},
		{name:'nro_documento', type: 'string'},
		{name:'nro_dui', type: 'string'},
		{name:'obs', type: 'string'},
		{name:'importe_doc', type: 'string'},
		{name:'importe_pago_liquido', type: 'string'},
		{name:'importe_iva', type: 'string'},
		{name:'importe_descuento', type: 'string'},
		{name:'importe_descuento_ley', type: 'string'},
		{name:'importe_excento', type: 'string'},
		{name:'importe_ice', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'monto', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'id_estado_wf', type: 'numeric'},'codigo_control'
	],
	sortInfo:{
		field: 'id_solicitud_rendicion_det',
		direction: 'ASC'
	},
	
	
	
	abrirFormulario:function(tipo, record){
        //abrir formulario de solicitud
	   var me = this;	   
	   me.objSolForm = Phx.CP.loadWindows('../../../sis_tesoreria/vista/solicitud_rendicion_det/FormRendicion.php',
								'Formulario de rendicion',
								{
									modal:true,
									width:'90%',
									height:'90%'
								}, {data:{objPadre: me,
										  tipoDoc: me.tipoDoc,
										  tipo_form : tipo,
										  id_depto : me.id_depto,
										  id_solicitud_efectivo : me.id_solicitud_efectivo,
										  datosOriginales: record
										  },
									id_moneda_defecto : me.id_moneda
								}, 
								this.idContenedor,
								'FormRendicion');
    },

	
   
	onButtonNew:function(){
	    //abrir formulario de solicitud	       
		this.abrirFormulario('new') 
    },
	
    onButtonEdit:function(){
        this.abrirFormulario('edit', this.sm.getSelected())
    },
	
	sigEstado:function(){
		var id_estado_workflow = this.id_estado_workflow;
		var id_proceso_workflow = this.id_proceso_workflow;
		var countData = this.countData;
		
		if(id_estado_workflow ==0 || id_proceso_workflow==0){
			Ext.MessageBox.alert('Alerta','Debe tener al menos una factura registrada');
		}else{
			//
			Ext.Ajax.request({
				url: '../../sis_tesoreria/control/SolicitudRendicionDet/obtener_item_monto',
				params: 
				{
					id_proceso_workflow: id_proceso_workflow,
					monto: this.monto				
				},
				argument: {},
				success: function (resp) {
					var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));
					if(this.monto >reg.datos[0].importe_maximo_item){
						//
						Ext.Msg.show({
							title:'ALERTA',
							scope: this,
							msg: 'EL MONTO DE LA RENDICION '+ this.monto +', EXCEDE EL IMPORTE MAXIMO DE ITEM<br> ¿DESEA CONTINUAR CON LA RENDICION?<br>PARA CONTINUAR PRESIONE SI',
							buttons: Ext.Msg.YESNO,
							fn: function(id, value, opt) {			   		
								if (id == 'yes') {
										Ext.Msg.show({
										title:'Confirmación',
										scope: this,
										msg: 'Todas las facturas seran enviadas a rendicion? Para enviar presione el botón "Si"',
										buttons: Ext.Msg.YESNO,
										fn: function(id, value, opt) {			   		
											if (id == 'yes') {
												this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
												'Estado de Wf',
												{
													modal:true,
													width:700,
													height:450
												}, {data:{
													   id_estado_wf:id_estado_workflow,
													   id_proceso_wf:id_proceso_workflow									  
													}}, this.idContenedor,'FormEstadoWf',
												{
													config:[{
															  event:'beforesave',
															  delegate: this.onSaveWizard												  
															}],
													
													scope:this
												 });
											} else {
												opt.hide;
											}
									   },	
									   animEl: 'elId',
									   icon: Ext.MessageBox.WARNING
									}, this);
								} else {
									opt.hide;
								}
							},	
							animEl: 'elId',
							icon: Ext.MessageBox.WARNING
						}, this);
					}else{
						Ext.Msg.show({
							title:'Confirmación',
							scope: this,
							msg: 'Todas las facturas seran enviadas a rendicion? Para enviar presione el botón "Si"',
							buttons: Ext.Msg.YESNO,
							fn: function(id, value, opt) {			   		
								if (id == 'yes') {
									this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
									'Estado de Wf',
									{
										modal:true,
										width:700,
										height:450
									}, {data:{
										   id_estado_wf:id_estado_workflow,
										   id_proceso_wf:id_proceso_workflow									  
										}}, this.idContenedor,'FormEstadoWf',
									{
										config:[{
												  event:'beforesave',
												  delegate: this.onSaveWizard												  
												}],
										
										scope:this
									 });
								} else {
									opt.hide;
								}
						   },	
						   animEl: 'elId',
						   icon: Ext.MessageBox.WARNING
						}, this);
					}
					Ext.Msg.show({
							title:'Confirmación',
							scope: this,
							msg: 'Todas las facturas seran enviadas a rendicion? Para enviar presione el botón "Si"',
							buttons: Ext.Msg.YESNO,
							fn: function(id, value, opt) {			   		
								if (id == 'yes') {
									this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
									'Estado de Wf',
									{
										modal:true,
										width:700,
										height:450
									}, {data:{
										   id_estado_wf:id_estado_workflow,
										   id_proceso_wf:id_proceso_workflow									  
										}}, this.idContenedor,'FormEstadoWf',
									{
										config:[{
												  event:'beforesave',
												  delegate: this.onSaveWizard												  
												}],
										
										scope:this
									 });
								} else {
									opt.hide;
								}
						   },	
						   animEl: 'elId',
						   icon: Ext.MessageBox.WARNING
						}, this);													
				},
				failure: this.conexionFailure,
				timeout: this.timeout,
				scope: this
			});			
			//			
			
		}
	 },
	 
	 liberaMenu:function(n){		 
		  if (this.estado=='finalizado'){
			  this.getBoton('new').disable();
			  this.getBoton('edit').disable();
			  this.getBoton('del').disable();
		  }else{
			  this.getBoton('new').enable();
			  //this.getBoton('edit').enable();
			  //this.getBoton('del').enable();

		  }
		  this.getBoton('fin_registro').disable();
     },
	 
	 preparaMenu:function(n){
		  Phx.vista.SolicitudRendicionDet.superclass.preparaMenu.call(this);
		  if (this.estado=='finalizado'){
			  this.getBoton('edit').disable();
		  }else{
			  this.getBoton('edit').enable();
		  }
		 var data = this.getSelectedData();

		 if (data.id_estado_wf != '' && data.id_proceso_wf != ''){
			 this.getBoton('fin_registro').enable();
			 this.id_estado_workflow = data.id_estado_wf;
			 this.id_proceso_workflow = data.id_proceso_wf;
		 }
     },
	 
	 onSaveWizard:function(wizard,resp){
			Phx.CP.loadingShow();			
			Ext.Ajax.request({
				url:'../../sis_tesoreria/control/SolicitudEfectivo/siguienteEstadoSolicitudEfectivo',
				params:{
						
					id_proceso_wf_act:  resp.id_proceso_wf_act,
					id_estado_wf_act:   resp.id_estado_wf_act,
					id_tipo_estado:     resp.id_tipo_estado,
					id_funcionario_wf:  resp.id_funcionario_wf,
					id_depto_wf:        resp.id_depto_wf,
					obs:                resp.obs,
					//json_procesos:      Ext.util.JSON.encode(resp.procesos)		
					},
				success:this.successWizard,
				failure: this.conexionFailure,
				argument:{wizard:wizard},
				timeout:this.timeout,
				scope:this
			});
		},
		
		successWizard:function(resp){
			Phx.CP.loadingHide();
			resp.argument.wizard.panel.destroy()

			this.reload();
		 },

	
	bdel:true,
	bsave:false
	}
)
</script>
		
		