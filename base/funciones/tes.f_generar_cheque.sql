--------------- SQL ---------------

CREATE OR REPLACE FUNCTION tes.f_generar_cheque (
  p_id_usuario integer,
  p_id_int_comprobante integer,
  p_id_int_transaccion integer,
  p_id_finalidad integer,
  p_id_cbte_endesis integer = NULL::integer,
  p_c31 varchar = ''::character varying,
  p_origen varchar = 'endesis'::character varying
)
RETURNS varchar AS
$body$
/*
	Autor: GSS
    Fecha: 24-06-2015
    Descripción: Función que se encarga de generar solo cheque, se debe conocer el deposito
    del cual se emitira el cheque.
      HISTORIAL DE MODIFICACIONES:
   	
 ISSUE            FECHA:		      AUTOR                 DESCRIPCION
   
 #0        		24-06-2015        GSS BOA           creacion
 #3  ETR        26/12/2018       RAC KPLIAN         al generar cheche se manda la fecha del cbte
  
    
    
*/
DECLARE

    v_resp							varchar;
    v_nombre_funcion   				varchar;
    v_datos_cheque					record;
	v_posicion_inicial				integer;
    v_posicion_final				integer;
    v_id_deposito					varchar;
    v_respuesta						varchar;
    v_sistema_origen				varchar;
    v_reg_banco						record;
    v_importe_deposito              numeric; --#3
    v_importe_cheque                numeric; --#3
    v_tipo                          varchar; --#3
BEGIN   



     v_nombre_funcion:='tes.f_generar_cheque';

     --si el origen es endesis
    if p_origen  = 'nacional' then
    	v_sistema_origen = 'KERP';
    ELSE
    	v_sistema_origen = 'KERP_INT';
    end if;
		/*
        select cbte.id_depto, cbte.beneficiario, cbte.momento_pagado,cbte.manual, cbte.nro_tramite,
        cbte.id_depto_libro, op.numero || ' - INGRESO PARA PAGO A ' || pp.nombre_pago || ',' || COALESCE(op.obs,'')  as glosa,
        tra.importe_haber,tra.id_cuenta_bancaria,
        tra.nro_cheque, tra.nombre_cheque_trans, tra.forma_pago, substr(depto.codigo,4) as origen,
        cbte.id_cuenta_bancaria_mov as id_libro_bancos_deposito
        into v_datos_cheque
        from conta.tint_comprobante cbte
        inner join conta.tint_transaccion tra on tra.id_int_comprobante=cbte.id_int_comprobante
        left join param.tdepto depto on depto.id_depto=cbte.id_depto_libro
        inner join tes.tplan_pago pp on pp.id_int_comprobante=cbte.id_int_comprobante
		inner join tes.tobligacion_pago op on op.id_obligacion_pago=pp.id_obligacion_pago
        where cbte.id_int_comprobante=p_id_int_comprobante and tra.forma_pago='cheque';*/

       select 
       
       COALESCE(tra.nombre_cheque_trans,cbte.beneficiario) as beneficiario, 
       dpcb.id_depto as id_depto_libro,
       cbte.glosa1 as glosa, 
       tra.importe_haber, 
       tra.importe_haber_mb,
       tra.importe_haber_mt,
       tra.importe_debe, 
       tra.importe_debe_mb,
       tra.importe_debe_mt,
       tra.id_moneda,
       tra.id_cuenta_bancaria,
       substr(depto.codigo, 4) as origen, 
       cbte.nro_tramite, 
       tra.id_cuenta_bancaria_mov as id_libro_bancos_deposito,
       cbte.fecha as fecha_cbte   --#3 add fecha_cbte
       
       into v_datos_cheque
       from conta.tint_comprobante cbte
       inner join conta.tint_transaccion tra on tra.id_int_comprobante = cbte.id_int_comprobante
       left join tes.tdepto_cuenta_bancaria dpcb on dpcb.id_cuenta_bancaria = tra.id_cuenta_bancaria
       left join param.tdepto depto on depto.id_depto=dpcb.id_depto
        where cbte.id_int_comprobante = p_id_int_comprobante and tra.forma_pago = 'cheque' and tra.id_int_transaccion  = p_id_int_transaccion;
        
        ----------------------------------------------------------------------------------------------
        --RAC 28/12/2017,    Revisa la coherencia entre el monto del cbte y la moneda de la cuenta
        ---------------------------------------------------------------------------------------------
        
        select 
           cb.id_moneda
        into
          v_reg_banco
        from tes.tcuenta_bancaria cb
        where cb.id_cuenta_bancaria = v_datos_cheque.id_cuenta_bancaria;
        
       
       
        IF v_reg_banco.id_moneda = param.f_get_moneda_base()  THEN 
          -- 1)  la cuenta es moenda base, ....directamente usamos la monto_haber_mb        
          v_importe_cheque   = v_datos_cheque.importe_haber_mb ;
          v_importe_deposito = v_datos_cheque.importe_debe_mb ;
               
        ELSEIF v_reg_banco.id_moneda =  param.f_get_moneda_triangulacion()  THEN
           -- 2)   la cuenta es moenda de triangulación usa el monto_haber_mt
           v_importe_cheque =v_datos_cheque.importe_haber_mt;
           v_importe_deposito       =v_datos_cheque.importe_debe_mt;
        ELSEIF   v_reg_banco.id_moneda = v_datos_cheque.id_moneda   THEN
           -- 4) es de la misma moneda que la transaccion
           v_importe_cheque = v_datos_cheque.importe_haber;
           v_importe_deposito = v_datos_cheque.importe_debe;
        ELSE
           --4)   la cuenta  es otra moenda diferente hay que convertir de doalres a la moenda correpondiente           
          raise exception 'Caso de conversion  no contemplado'; 
        END IF;
        
        
        IF  v_importe_cheque > 0  THEN
           v_tipo = 'cheque';
        ELSE
           v_tipo = 'deposito';
        END IF;
       
       
       

		if(v_datos_cheque.id_cuenta_bancaria is null)then
        	raise exception 'El comprobante % no cuenta con el id_cuenta_bancaria', p_id_int_comprobante;
        end if;
		        
        if(v_datos_cheque.id_depto_libro is null)then
        	raise exception 'El comprobante % no cuenta con el id_depto_libro', p_id_int_comprobante;
        end if;
        
        
        
        IF(v_datos_cheque.id_libro_bancos_deposito is null)THEN
        
        
        v_resp = pxp.f_intermediario_ime(p_id_usuario::int4,NULL,NULL::varchar,'v58gc566o75102428i2usu08i4',13313,'172.17.45.202','99:99:99:99:99:99','tes.ft_ts_libro_bancos_ime','TES_LBAN_INS',NULL,'no',NULL,
        			array['filtro' , 'ordenacion','dir_ordenacion','puntero','cantidad','_id_usuario_ai','_nombre_usuario_ai','id_cuenta_bancaria',                       'id_depto',                            'a_favor',                           'nro_cheque','importe_deposito',               'nro_liquidacion',   'detalle',                              'origen',                       'observaciones',                   'importe_cheque',        'id_libro_bancos_fk','nro_comprobante','comprobante_sigma','tipo',   'id_finalidad',           'sistema_origen',         'id_int_comprobante',                'id_int_transaccion',           'fecha'],
                    array[' 0 = 0 ', '',          '',              '',       '',        'NULL',          'NULL',               v_datos_cheque.id_cuenta_bancaria::varchar, v_datos_cheque.id_depto_libro::varchar,v_datos_cheque.beneficiario::varchar,'NULL',      v_importe_deposito::varchar,     '',                  'PAGO A '||v_datos_cheque.glosa::varchar,v_datos_cheque.origen::varchar,v_datos_cheque.nro_tramite::varchar,v_importe_cheque::varchar,'NULL',              '',              'C31-'||p_c31,     v_tipo,    p_id_finalidad::varchar,  v_sistema_origen::varchar,''||p_id_int_comprobante::varchar||'',p_id_int_transaccion::varchar,  v_datos_cheque.fecha_cbte::varchar    ],   --#3 add fecha_cbte
                    array['varchar','varchar','varchar','integer','integer','int4','varchar','int4','int4','varchar','int4','numeric','varchar','text','varchar','text','numeric','int4','varchar','varchar','varchar','int4','varchar','varchar','integer', 'date']
                    ,'',NULL,NULL);
        ELSE
        
        v_resp = pxp.f_intermediario_ime(p_id_usuario::int4,NULL,NULL::varchar,'v58gc566o75102428i2usu08i4',13313,'172.17.45.202','99:99:99:99:99:99','tes.ft_ts_libro_bancos_ime','TES_LBAN_INS',NULL,'no',NULL,
        			array['filtro','ordenacion','dir_ordenacion','puntero','cantidad','_id_usuario_ai','_nombre_usuario_ai','id_cuenta_bancaria',                     'id_depto',                             'a_favor',                            'nro_cheque', 'importe_deposito',            'nro_liquidacion', 'detalle',                               'origen',                       'observaciones',                    'importe_cheque',         'id_libro_bancos_fk',                            'nro_comprobante','comprobante_sigma',   'tipo',   'id_finalidad',           'sistema_origen',          'id_int_comprobante',         'id_int_transaccion'],
                    array[' 0 = 0 ','',         '',               '',      '',        'NULL',           'NULL',              v_datos_cheque.id_cuenta_bancaria::varchar,v_datos_cheque.id_depto_libro::varchar,v_datos_cheque.beneficiario::varchar,'NULL',        v_importe_deposito::varchar,  '',                'PAGO A '||v_datos_cheque.glosa::varchar,v_datos_cheque.origen::varchar,v_datos_cheque.nro_tramite::varchar,v_importe_cheque::varchar, v_datos_cheque.id_libro_bancos_deposito::varchar,'',                'C31-'||p_c31,         v_tipo,   p_id_finalidad::varchar, v_sistema_origen::varchar, p_id_int_comprobante::varchar, p_id_int_transaccion::varchar],
                    array['varchar','varchar','varchar','integer','integer','int4','varchar','int4','int4','varchar','int4','numeric','varchar','text','varchar','text','numeric','int4','varchar','varchar','varchar','int4','varchar','varchar','integer']
                    ,'',NULL,NULL);
        END IF;
        
        v_respuesta = substring(v_resp from '%#"tipo_respuesta":"_____"#"%' for '#');

        IF v_respuesta = 'tipo_respuesta":"ERROR"' THEN
            v_posicion_inicial = position('"mensaje":"' in v_resp) + 11;	
            v_posicion_final = position('"codigo_error":' in v_resp) - 2;	
            RAISE EXCEPTION 'No se pudo ingresar el cheque en libro de bancos ERP-BOA: mensaje: %',substring(v_resp from v_posicion_inicial for (v_posicion_final-v_posicion_inicial));
        ELSE 
            v_posicion_inicial = position('"id_libro_bancos":"' in v_resp) + 19;
            v_posicion_final = position('"}' in v_resp);
            v_id_deposito=substring(v_resp from v_posicion_inicial for (v_posicion_final-v_posicion_inicial));
                                     
        END IF;--fin error respuesta   
    

	
    v_respuesta = pxp.f_agrega_clave(v_respuesta,'mensaje','Cheque generado'); 
    v_respuesta = pxp.f_agrega_clave(v_respuesta,'operacion','cambio_exitoso');
    v_respuesta = pxp.f_agrega_clave(v_respuesta,'id_libro_bancos',v_id_deposito);            
    return v_respuesta;

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