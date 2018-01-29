<?php
/**
*@package pXP
*@file gen-MODPlanPagoDocCompra.php
*@author  (admin)
*@date 25-01-2018 15:16:48
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODPlanPagoDocCompra extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarPlanPagoDocCompra(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='tes.ft_plan_pago_doc_compra_sel';
		$this->transaccion='TES_OPDCOMP_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//captura parametros adicionales para el count
		$this->capturaCount('total_importe_ice','numeric');
		$this->capturaCount('total_importe_excento','numeric');
		$this->capturaCount('total_importe_it','numeric');
		$this->capturaCount('total_importe_iva','numeric');
		$this->capturaCount('total_importe_descuento','numeric');
		$this->capturaCount('total_importe_doc','numeric');
		$this->capturaCount('total_importe_retgar','numeric');
		$this->capturaCount('total_importe_anticipo','numeric');
		$this->capturaCount('tota_importe_pendiente','numeric');
		$this->capturaCount('total_importe_neto','numeric');
		$this->capturaCount('total_importe_descuento_ley','numeric');
		$this->capturaCount('total_importe_pago_liquido','numeric');
		$this->capturaCount('total_importe_aux_neto','numeric');
				
		//Definicion de la lista del resultado del query
		$this->captura('id_plan_pago_doc_compra','int4');
		$this->captura('id_doc_compra_venta','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_obligacion_pago','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_reg_op','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');

		$this->captura('revisado','varchar');
		$this->captura('movil','varchar');
		$this->captura('tipo','varchar');
		$this->captura('importe_excento','numeric');
		$this->captura('id_plantilla','int4');
		$this->captura('fecha','date');
		$this->captura('nro_documento','varchar');
		$this->captura('nit','varchar');
		$this->captura('importe_ice','numeric');
		$this->captura('nro_autorizacion','varchar');
		$this->captura('importe_iva','numeric');
		$this->captura('importe_descuento','numeric');
		$this->captura('importe_doc','numeric');
		$this->captura('sw_contabilizar','varchar');
		$this->captura('tabla_origen','varchar');
		$this->captura('estado','varchar');
		$this->captura('id_depto_conta','int4');
		$this->captura('id_origen','int4');
		$this->captura('obs','varchar');
		$this->captura('codigo_control','varchar');
		$this->captura('importe_it','numeric');
		$this->captura('razon_social','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usr_reg_dcv','varchar');
		
		$this->captura('desc_depto','varchar');
		$this->captura('desc_plantilla','varchar');
		$this->captura('importe_descuento_ley','numeric');
		$this->captura('importe_pago_liquido','numeric');
		$this->captura('nro_dui','varchar');
		$this->captura('id_moneda','int4');
		$this->captura('desc_moneda','varchar');
		$this->captura('id_int_comprobante','int4');
		$this->captura('nro_tramite','varchar');
		$this->captura('desc_comprobante','varchar');
		
		
		$this->captura('importe_pendiente','numeric');
		$this->captura('importe_anticipo','numeric');
		$this->captura('importe_retgar','numeric');
		$this->captura('importe_neto','numeric');		
		$this->captura('id_auxiliar','integer');
		$this->captura('codigo_auxiliar','varchar');
		$this->captura('nombre_auxiliar','varchar');		
		$this->captura('id_tipo_doc_compra_venta','integer');
		$this->captura('desc_tipo_doc_compra_venta','varchar');		
		$this->captura('importe_aux_neto','numeric');
		
		$this->captura('id_funcionario','integer');		
		$this->captura('desc_funcionario2','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarDocCompleto(){
		//Abre conexion con PDO
		$cone = new conexion();
		$link = $cone->conectarpdo();
		$copiado = false;			
		try {
			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		  	$link->beginTransaction();
			
			/////////////////////////
			//  inserta cabecera de la solicitud de compra
			///////////////////////
			
			//Definicion de variables para ejecucion del procedimiento
			$this->procedimiento='tes.ft_plan_pago_doc_compra_ime';
			$this->transaccion='TES_DCV_INS';
			$this->tipo_procedimiento='IME';
					
			//Define los parametros para la funcion
			$this->setParametro('id_plan_pago','id_plan_pago','integer');
			$this->setParametro('revisado','revisado','varchar');
			$this->setParametro('movil','movil','varchar');
			$this->setParametro('tipo','tipo','varchar');
			$this->setParametro('importe_excento','importe_excento','numeric');
			$this->setParametro('id_plantilla','id_plantilla','int4');
			$this->setParametro('fecha','fecha','date');
			$this->setParametro('nro_documento','nro_documento','varchar');
			$this->setParametro('nit','nit','varchar');
			$this->setParametro('importe_ice','importe_ice','numeric');
			$this->setParametro('nro_autorizacion','nro_autorizacion','varchar');
			$this->setParametro('importe_iva','importe_iva','numeric');
			$this->setParametro('importe_descuento','importe_descuento','numeric');
			$this->setParametro('importe_doc','importe_doc','numeric');
			$this->setParametro('sw_contabilizar','sw_contabilizar','varchar');
			$this->setParametro('tabla_origen','tabla_origen','varchar');
			$this->setParametro('estado','estado','varchar');
			$this->setParametro('id_depto_conta','id_depto_conta','int4');
			$this->setParametro('id_origen','id_origen','int4');
			$this->setParametro('obs','obs','varchar');
			$this->setParametro('estado_reg','estado_reg','varchar');
			$this->setParametro('codigo_control','codigo_control','varchar');
			$this->setParametro('importe_it','importe_it','numeric');
			$this->setParametro('razon_social','razon_social','varchar');
			$this->setParametro('importe_descuento_ley','importe_descuento_ley','numeric');
			$this->setParametro('importe_pago_liquido','importe_pago_liquido','numeric');
			$this->setParametro('nro_dui','nro_dui','varchar');
			$this->setParametro('id_moneda','id_moneda','int4');
			
			$this->setParametro('importe_pendiente','importe_pendiente','numeric');
			$this->setParametro('importe_anticipo','importe_anticipo','numeric');
			$this->setParametro('importe_retgar','importe_retgar','numeric');
			$this->setParametro('importe_neto','importe_neto','numeric');			
		    $this->setParametro('id_auxiliar','id_auxiliar','integer');
			$this->setParametro('id_int_comprobante','id_int_comprobante','integer');

			$this->setParametro('estacion','estacion','varchar');
			$this->setParametro('id_punto_venta','id_punto_venta','integer');
			$this->setParametro('id_agencia','id_agencia','integer');

			//Ejecuta la instruccion
            $this->armarConsulta();
			$stmt = $link->prepare($this->consulta);		  
		  	$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);				
			
			//recupera parametros devuelto depues de insertar ... (id_solicitud)
			$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
			if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
				throw new Exception("Error al ejecutar en la bd", 3);
			}
			
			$respuesta = $resp_procedimiento['datos'];
			
			$id_doc_compra_venta = $respuesta['id_doc_compra_venta'];
			
			//////////////////////////////////////////////
			//inserta detalle de la compra o venta
			/////////////////////////////////////////////
			
			
			if($this->aParam->getParametro('regitrarDetalle') == 'si'){
			//decodifica JSON  de detalles 
				$json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('json_new_records'));			
				
				//var_dump($json_detalle)	;
				foreach($json_detalle as $f){
					
					$this->resetParametros();
					//Definicion de variables para ejecucion del procedimiento
				    $this->procedimiento='conta.ft_doc_concepto_ime';
					$this->transaccion='CONTA_DOCC_INS';
					$this->tipo_procedimiento='IME';
					
					//modifica los valores de las variables que mandaremos
					$this->arreglo['id_centro_costo'] = $f['id_centro_costo'];
					
					
					$this->arreglo['descripcion'] = $f['descripcion'];
					$this->arreglo['precio_unitario'] = $f['precio_unitario'];
					$this->arreglo['id_doc_compra_venta'] = $id_doc_compra_venta;
					$this->arreglo['id_orden_trabajo'] = $f['id_orden_trabajo'];
					$this->arreglo['id_concepto_ingas'] = $f['id_concepto_ingas'];
					$this->arreglo['precio_total'] = $f['precio_total'];
					$this->arreglo['precio_total_final'] = $f['precio_total_final'];
					$this->arreglo['cantidad_sol'] = $f['cantidad_sol'];
					
					//throw new Exception("cantidad ...modelo...".$f['cantidad'], 1);
							
					//Define los parametros para la funcion
					$this->setParametro('estado_reg','estado_reg','varchar');
					$this->setParametro('id_doc_compra_venta','id_doc_compra_venta','int4');
					$this->setParametro('id_orden_trabajo','id_orden_trabajo','int4');
					$this->setParametro('id_centro_costo','id_centro_costo','int4');
					$this->setParametro('id_concepto_ingas','id_concepto_ingas','int4');
					$this->setParametro('descripcion','descripcion','text');
					$this->setParametro('cantidad_sol','cantidad_sol','numeric');
					$this->setParametro('precio_unitario','precio_unitario','numeric');
					$this->setParametro('precio_total','precio_total','numeric');
					$this->setParametro('precio_total_final','precio_total_final','numeric');
					
					//Ejecuta la instruccion
		            $this->armarConsulta();
					$stmt = $link->prepare($this->consulta);		  
				  	$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);				
					
					//recupera parametros devuelto depues de insertar ... (id_solicitud)
					$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
					if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
						throw new Exception("Error al insertar detalle  en la bd", 3);
					}
	                    
	                        
	            }

             //verifica si los totales cuadran
			$this->resetParametros();
			$this->procedimiento='conta.ft_doc_compra_venta_ime';
			$this->transaccion='CONTA_CHKDOCSUM_IME';
			$this->tipo_procedimiento='IME';
			
			$this->arreglo['id_doc_compra_venta'] = $id_doc_compra_venta;
			$this->setParametro('id_doc_compra_venta','id_doc_compra_venta','int4');
			
			$this->armarConsulta();
			$stmt = $link->prepare($this->consulta);		  
		  	$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			//recupera parametros devuelto depues de insertar ... (id_solicitud)
			$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
			if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
				throw new Exception("Error al verificar cuadre ", 3);
			}	
			
			}
			
			
			
			
			//si todo va bien confirmamos y regresamos el resultado
			$link->commit();
			$this->respuesta=new Mensaje();
			$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
			$this->respuesta->setDatos($respuesta);
		} 
	    catch (Exception $e) {			
		    	$link->rollBack();
				$this->respuesta=new Mensaje();
				if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
					$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				} else if ($e->getCode() == 2) {//es un error en bd de una consulta
					$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
				} else {//es un error lanzado con throw exception
					throw new Exception($e->getMessage(), 2);
				}
				
		}    
	    
	    return $this->respuesta;
	}
			
	function modificarDocCompleto(){
		
		//Abre conexion con PDO
		$cone = new conexion();
		$link = $cone->conectarpdo();
		$copiado = false;			
		try {
			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		  	$link->beginTransaction();
			
			/////////////////////////
			//  inserta cabecera de la solicitud de compra
			///////////////////////
			
			//Definicion de variables para ejecucion del procedimiento
			$this->procedimiento='conta.ft_doc_compra_venta_ime';
			$this->transaccion='CONTA_DCV_MOD';
			$this->tipo_procedimiento='IME';
					
			//Define los parametros para la funcion
			$this->setParametro('id_plan_pago','id_plan_pago','integer');
			$this->setParametro('id_doc_compra_venta','id_doc_compra_venta','int4');
			$this->setParametro('revisado','revisado','varchar');
			$this->setParametro('movil','movil','varchar');
			$this->setParametro('tipo','tipo','varchar');
			$this->setParametro('importe_excento','importe_excento','numeric');
			$this->setParametro('id_plantilla','id_plantilla','int4');
			$this->setParametro('fecha','fecha','date');
			$this->setParametro('nro_documento','nro_documento','varchar');
			$this->setParametro('nit','nit','varchar');
			$this->setParametro('importe_ice','importe_ice','numeric');
			$this->setParametro('nro_autorizacion','nro_autorizacion','varchar');
			$this->setParametro('importe_iva','importe_iva','numeric');
			$this->setParametro('importe_descuento','importe_descuento','numeric');
			$this->setParametro('importe_doc','importe_doc','numeric');
			$this->setParametro('sw_contabilizar','sw_contabilizar','varchar');
			$this->setParametro('tabla_origen','tabla_origen','varchar');
			$this->setParametro('estado','estado','varchar');
			$this->setParametro('id_depto_conta','id_depto_conta','int4');
			$this->setParametro('id_origen','id_origen','int4');
			$this->setParametro('obs','obs','varchar');
			$this->setParametro('estado_reg','estado_reg','varchar');
			$this->setParametro('codigo_control','codigo_control','varchar');
			$this->setParametro('importe_it','importe_it','numeric');
			$this->setParametro('razon_social','razon_social','varchar');
			$this->setParametro('importe_descuento_ley','importe_descuento_ley','numeric');
			$this->setParametro('importe_pago_liquido','importe_pago_liquido','numeric');
			$this->setParametro('nro_dui','nro_dui','varchar');
			$this->setParametro('id_moneda','id_moneda','int4');
			$this->setParametro('importe_pendiente','importe_pendiente','numeric');
			$this->setParametro('importe_anticipo','importe_anticipo','numeric');
			$this->setParametro('importe_retgar','importe_retgar','numeric');
			$this->setParametro('importe_neto','importe_neto','numeric');
			$this->setParametro('id_auxiliar','id_auxiliar','integer');
			$this->setParametro('id_int_comprobante','id_int_comprobante','integer');

			$this->setParametro('estacion','estacion','varchar');
			$this->setParametro('id_punto_venta','id_punto_venta','integer');
			$this->setParametro('id_agencia','id_agencia','integer');

			//Ejecuta la instruccion
            $this->armarConsulta();
			$stmt = $link->prepare($this->consulta);		  
		  	$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);				
			
			//recupera parametros devuelto depues de insertar ... (id_solicitud)
			$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
			if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
				throw new Exception("Error al ejecutar en la bd", 3);
			}
			
			$respuesta = $resp_procedimiento['datos'];
			
			$id_doc_compra_venta = $respuesta['id_doc_compra_venta'];
			
			//////////////////////////////////////////////
			//inserta detalle de la compra o venta
			/////////////////////////////////////////////
			
			
			
			//decodifica JSON  de detalles 
			$json_detalle = $this->aParam->_json_decode($this->aParam->getParametro('json_new_records'));			
			
			//var_dump($json_detalle)	;
			
		  if($this->aParam->getParametro('regitrarDetalle') == 'si'){
					foreach($json_detalle as $f){
						
						$this->resetParametros();
						//Definicion de variables para ejecucion del procedimiento
					    
					    
						//modifica los valores de las variables que mandaremos
						$this->arreglo['id_centro_costo'] = $f['id_centro_costo'];
						$this->arreglo['id_doc_concepto'] = $f['id_doc_concepto'];
						
						$this->arreglo['descripcion'] = $f['descripcion'];
						$this->arreglo['precio_unitario'] = $f['precio_unitario'];
						$this->arreglo['id_doc_compra_venta'] = $id_doc_compra_venta;
						$this->arreglo['id_orden_trabajo'] = (isset($f['id_orden_trabajo'])?$f['id_orden_trabajo']:'null');
						$this->arreglo['id_concepto_ingas'] = $f['id_concepto_ingas'];
						$this->arreglo['precio_total'] = $f['precio_total'];
						$this->arreglo['precio_total_final'] = $f['precio_total_final'];
						$this->arreglo['cantidad_sol'] = $f['cantidad_sol'];
						
						
						$this->procedimiento='conta.ft_doc_concepto_ime';
						$this->tipo_procedimiento='IME';
						//si tiene ID modificamos
						if ( isset($this->arreglo['id_doc_concepto']) && $this->arreglo['id_doc_concepto'] != ''){
							$this->transaccion='CONTA_DOCC_MOD';
						}
						else{
							//si no tiene ID insertamos
							$this->transaccion='CONTA_DOCC_INS';
						}
						
						
						
						
						//throw new Exception("cantidad ...modelo...".$f['cantidad'], 1);
								
						//Define los parametros para la funcion
						$this->setParametro('estado_reg','estado_reg','varchar');
						$this->setParametro('id_doc_compra_venta','id_doc_compra_venta','int4');
						$this->setParametro('id_orden_trabajo','id_orden_trabajo','int4');
						$this->setParametro('id_centro_costo','id_centro_costo','int4');
						$this->setParametro('id_concepto_ingas','id_concepto_ingas','int4');
						$this->setParametro('descripcion','descripcion','text');
						$this->setParametro('cantidad_sol','cantidad_sol','numeric');
						$this->setParametro('precio_unitario','precio_unitario','numeric');
						$this->setParametro('precio_total','precio_total','numeric');
						$this->setParametro('precio_total_final','precio_total_final','numeric');
						$this->setParametro('id_doc_concepto','id_doc_concepto','numeric');
						
						
						//Ejecuta la instruccion
			            $this->armarConsulta();
						$stmt = $link->prepare($this->consulta);		  
					  	$stmt->execute();
						$result = $stmt->fetch(PDO::FETCH_ASSOC);				
						
						//recupera parametros devuelto depues de insertar ... (id_solicitud)
						$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
						if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
							throw new Exception("Error al insertar detalle  en la bd", 3);
						}
		                    
		                        
		            }
					
					/////////////////////////////
					//elimia conceptos marcado
					///////////////////////////
					
					$this->procedimiento='conta.ft_doc_concepto_ime';
					$this->transaccion='CONTA_DOCC_ELI';
					$this->tipo_procedimiento='IME';
					
					$id_doc_conceto_elis = explode(",", $this->aParam->getParametro('id_doc_conceto_elis'));			
					//var_dump($json_detalle)	;
					for( $i=0; $i<count($id_doc_conceto_elis); $i++){
						
						$this->resetParametros();
						$this->arreglo['id_doc_concepto'] = $id_doc_conceto_elis[$i];
						//Define los parametros para la funcion
						$this->setParametro('id_doc_concepto','id_doc_concepto','int4');
						//Ejecuta la instruccion
			            $this->armarConsulta();
						$stmt = $link->prepare($this->consulta);		  
					  	$stmt->execute();
						$result = $stmt->fetch(PDO::FETCH_ASSOC);				
						
						//recupera parametros devuelto depues de insertar ... (id_solicitud)
						$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
						if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
							throw new Exception("Error al eliminar concepto  en la bd", 3);
						}
					
					}
					//verifica si los totales cuadran
					$this->resetParametros();
					$this->procedimiento='conta.ft_doc_compra_venta_ime';
					$this->transaccion='CONTA_CHKDOCSUM_IME';
					$this->tipo_procedimiento='IME';
					
					$this->arreglo['id_doc_compra_venta'] = $id_doc_compra_venta;
					$this->setParametro('id_doc_compra_venta','id_doc_compra_venta','int4');
					
					$this->armarConsulta();
					$stmt = $link->prepare($this->consulta);		  
				  	$stmt->execute();
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					//recupera parametros devuelto depues de insertar ... (id_solicitud)
					$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
					if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
						throw new Exception("Error al verificar cuadre ", 3);
					}	
					
			}//fin del if tiene detalle
			
			//si todo va bien confirmamos y regresamos el resultado
			$link->commit();
			$this->respuesta=new Mensaje();
			$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
			$this->respuesta->setDatos($respuesta);
		} 
	    catch (Exception $e) {			
		    	$link->rollBack();
				$this->respuesta=new Mensaje();
				if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
					$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
				} else if ($e->getCode() == 2) {//es un error en bd de una consulta
					$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
				} else {//es un error lanzado con throw exception
					throw new Exception($e->getMessage(), 2);
				}
				
		}    
	    
	    return $this->respuesta;
	}
			
	function eliminarDocCompraVenta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='tes.ft_plan_pago_doc_compra_ime';
		$this->transaccion='DCV_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_plan_pago_doc_compra','id_plan_pago_doc_compra','int4');
		$this->setParametro('id_doc_compra_venta','id_doc_compra_venta','int8');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>