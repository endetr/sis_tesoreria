--------------- SQL ---------------

CREATE OR REPLACE FUNCTION tes.f_cuenta_bancaria_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Tesoreria
 FUNCION: 		tes.f_cuenta_bancaria_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'tes.tcuenta_bancaria'
 AUTOR: 		Gonzalo Sarmiento Sejas
 FECHA:	        24-04-2013 15:19:30
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_cuenta_bancaria	integer;
			    
BEGIN

    v_nombre_funcion = 'tes.f_cuenta_bancaria_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'TES_CTABAN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Gonzalo Sarmiento Sejas	
 	#FECHA:		24-04-2013 15:19:30
	***********************************/

	if(p_transaccion='TES_CTABAN_INS')then
					
        begin
        
            -- validar que el numero de cuenta sea unico para registros activos
            
            IF   exists (select 1 FROM tes.tcuenta_bancaria cb 
                                   WHERE cb.nro_cuenta ilike v_parametros.nro_cuenta
                                      and  cb.id_institucion = v_parametros.id_institucion ) THEN
                  
               raise exception 'La nro de cuenta ya existe para este banco';                    
                                      
            END IF;
            
            --valida fechas
            
            IF v_parametros.fecha_alta is  null then
                
                raise exception 'La fecha de alta no puede quedar en blanco';
               
            END IF;
            
            IF v_parametros.fecha_baja is not null then
                 IF  v_parametros.fecha_baja <  v_parametros.fecha_alta  THEN
                  
                  raise exception 'La fecha de baja no puede ser anterior a la fecha de alta';
                
                END IF;
            END IF;
            
          
            
        
        	--Sentencia de la insercion
        	insert into tes.tcuenta_bancaria(
                estado_reg,
                fecha_baja,
                nro_cuenta,
                fecha_alta,
                id_institucion,
                fecha_reg,
                id_usuario_reg,
                fecha_mod,
                id_usuario_mod,
                id_moneda,
                denominacion,
                centro,
                id_finalidad
          	) values(
                'activo',
                v_parametros.fecha_baja,
                v_parametros.nro_cuenta,
                v_parametros.fecha_alta,
                v_parametros.id_institucion,
                now(),
                p_id_usuario,
                null,
                null,
                v_parametros.id_moneda,
                v_parametros.denominacion,
                v_parametros.centro,
                string_to_array(v_parametros.id_finalidads,',')::integer[]
							
			)RETURNING id_cuenta_bancaria into v_id_cuenta_bancaria;

            insert into tes.tdepto_cuenta_bancaria
            (id_depto,
            id_cuenta_bancaria,
            id_usuario_reg
            )VALUES(
            v_parametros.id_depto_lb,
            v_id_cuenta_bancaria,
            p_id_usuario
            );
            
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria almacenado(a) con exito (id_cuenta_bancaria'||v_id_cuenta_bancaria||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuenta_bancaria',v_id_cuenta_bancaria::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'TES_CTABAN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		Gonzalo Sarmiento Sejas	
 	#FECHA:		24-04-2013 15:19:30
	***********************************/

	elsif(p_transaccion='TES_CTABAN_MOD')then

		begin
        
             -- validar que el numero de cuenta sea unico para registros activos
            
            IF   exists (select 1 FROM tes.tcuenta_bancaria cb 
                                   WHERE cb.nro_cuenta ilike v_parametros.nro_cuenta
                                      and  cb.id_institucion = v_parametros.id_institucion
                                      and  cb.id_cuenta_bancaria!= v_parametros.id_cuenta_bancaria) THEN
                  
               raise exception 'La nro de cuenta ya existe para este banco';                    
                                      
            END IF;
            
            --valida fechas
            
            IF v_parametros.fecha_alta is  null then
                
                raise exception 'La fecha de alta no puede quedar en blanco';
               
            END IF;
            
            IF v_parametros.fecha_baja is not null then
                 IF  v_parametros.fecha_baja <  v_parametros.fecha_alta  THEN
                  
                  raise exception 'La fecha de baja no puede ser anterior a la fecha de alta';
                
                END IF;
            END IF;
        
			--Sentencia de la modificacion
			update tes.tcuenta_bancaria set
                fecha_baja = v_parametros.fecha_baja,
                nro_cuenta = v_parametros.nro_cuenta,
                fecha_alta = v_parametros.fecha_alta,
                centro = v_parametros.centro,
                id_institucion = v_parametros.id_institucion,
                fecha_mod = now(),
                id_usuario_mod = p_id_usuario,
                id_moneda = v_parametros.id_moneda,
                denominacion = v_parametros.denominacion,
                id_finalidad = string_to_array(v_parametros.id_finalidads,',')::integer[]
			where id_cuenta_bancaria=v_parametros.id_cuenta_bancaria;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuenta_bancaria',v_parametros.id_cuenta_bancaria::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'TES_CTABAN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		Gonzalo Sarmiento Sejas	
 	#FECHA:		24-04-2013 15:19:30
	***********************************/

	elsif(p_transaccion='TES_CTABAN_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from tes.tcuenta_bancaria
            where id_cuenta_bancaria=v_parametros.id_cuenta_bancaria;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuenta_bancaria',v_parametros.id_cuenta_bancaria::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
        
    /*********************************    
 	#TRANSACCION:  'TES_CTABOPE_IME'
 	#DESCRIPCION:	Permite configurar las operaciones para las cuentas bancarias (retenciones, devoluciones)
 	#AUTOR:		Gonzalo Sarmiento
 	#FECHA:		24-05-2016
	***********************************/

	elsif(p_transaccion='TES_CTABOPE_IME')then

		begin        
        
             update tes.tcuenta_bancaria set
			  sw_operacion = string_to_array(v_parametros.sw_operacion,',')::varchar[]
             where id_cuenta_bancaria=v_parametros.id_cuenta_bancaria;
                          
             --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se modificaron las operaciones de la cuenta bancaria'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuenta_bancaria',v_parametros.id_cuenta_bancaria::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end; 
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;