<?php


// Gets data from URL parameters.
if(isset($_GET['add_location'])) {
    add_location();
}
if(isset($_GET['id'])) {
   echo flauten2($_GET['id']);
}

if(isset($_GET['id_loc'])) {
    echo atms($_GET['id_loc']);
 }


function getlocationsinfo(){
  
    try {

    $server = "";
    $user = "";
    $pwd="";
    $dba="";
    $concetinfo=array("Database" =>$dba , "UID" =>$user, "PWD"=>$pwd, "CharacterSet" => "UTF-8");
    $conn= sqlsrv_connect($server,$concetinfo);
        
    $sql = "select s.sol_id_localidad as ID, l.loc_descripcion, count(*) as cantidad,g.lat as Lat, g.long as Long 
    from FBC_CREDITO C
    inner join FBC_SOLICITUD s on s.sol_id = c.id_solicitud
    inner join FBC_LOCALIDAD L on L.loc_id = s.sol_id_localidad
    inner join localidad_gps g on g.id = L.loc_id

	inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id and ss.sso_estado not in( 'FIRMA_CONTRATO','EFECTIVIZACION')
	and ss.sso_id in (select max(sso_id) from FBC_SEGUIMIENTO_SOLICITUD where id_solicitud = s.sol_id)
	group by s.sol_id_localidad , l.loc_descripcion, g.lat,g.long
    order by ID";
    $rs = sqlsrv_query($conn,$sql);
    $rows = array();
    $descris = array();

    while ($row = sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC)) {
        array_push($rows,array($row['Long'],$row['Lat']));
        array_push($descris ,$row['ID'],$row['loc_descripcion'],$row['cantidad']);
    }
    $indexed = array_map('array_values', $rows);
    } catch (\Throwable $th) {
    
    }
    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}

function flauten(){
    try {
       
   
    $server = "";
    $user = "";
    $pwd="";
    $dba="";
    $concetinfo=array("Database" =>$dba , "UID" =>$user, "PWD"=>$pwd, "CharacterSet" => "UTF-8");
    $conn= sqlsrv_connect($server,$concetinfo);
        
    $sql = "select s.sol_id_localidad as ID, l.loc_descripcion, count(*) as cantidad,g.lat as Lat, g.long as Long 
    from FBC_CREDITO C
    inner join FBC_SOLICITUD s on s.sol_id = c.id_solicitud
    inner join FBC_LOCALIDAD L on L.loc_id = s.sol_id_localidad
    inner join localidad_gps g on g.id = L.loc_id

	inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id and ss.sso_estado not in( 'FIRMA_CONTRATO','EFECTIVIZACION')
	and ss.sso_id in (select max(sso_id) from FBC_SEGUIMIENTO_SOLICITUD where id_solicitud = s.sol_id)
	group by s.sol_id_localidad , l.loc_descripcion, g.lat,g.long
    order by ID";
    $rs = sqlsrv_query($conn,$sql);
    $rows = array();

    while ($row = sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC)) {
        array_push($rows,array($row['Lat'], $row['Long'],$row['ID'],$row['loc_descripcion'],$row['cantidad']));
    }
    $indexed = array_map('array_values', $rows);
    } catch (\Throwable $th) {
        //throw $th;
    }
    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }

}



function flauten2($id)
{
  
    try{
        $server = "";
        $user = "";
        $pwd="";
        $dba="";
        $concetinfo=array("Database" =>$dba , "UID" =>$user, "PWD"=>$pwd, "CharacterSet" => "UTF-8");
        $conn= sqlsrv_connect($server,$concetinfo);
            
        $sql = "select count(*) as Cantidad, 'Plan de Pago Vigente' as Estado
        from FBC_CREDITO C
        inner join FBC_SOLICITUD s on s.sol_id = c.id_solicitud
        inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id
        where s.sol_id_localidad = $id
        and ss.sso_estado in ('PLAN_PAGO_VIGENTE') and ss.sso_id in (select max(sso_id) from fbc_seguimiento_solicitud where id_solicitud = s.sol_id)
        group by sol_id_localidad
        union all
        select count(*) as Cantidad, 'Cuota Vencida' as Estado
        from FBC_CREDITO C
        inner join FBC_SOLICITUD s on s.sol_id = c.id_solicitud
        inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id
        where s.sol_id_localidad = $id
        and ss.sso_estado in ('PLAN_PAGO_CUOTA_VENCIDA') and ss.sso_id in (select max(sso_id) from fbc_seguimiento_solicitud where id_solicitud = s.sol_id)
        group by sol_id_localidad
        union all
        select  count(*) as Cantidad, 'Plan Pago Caido' as Estado
        from FBC_CREDITO C
        inner join FBC_SOLICITUD s on s.sol_id = c.id_solicitud
        inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id
        where s.sol_id_localidad = $id
        and ss.sso_estado in ('PLAN_PAGO_CAIDO') and ss.sso_id in (select max(sso_id) from fbc_seguimiento_solicitud where id_solicitud = s.sol_id)
        group by sol_id_localidad
        union all
        select  count(*) as Cantidad, 'Plan Pago Atrasado' as Estado
        from FBC_CREDITO C
        inner join FBC_SOLICITUD s on s.sol_id = c.id_solicitud
        inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id
        where s.sol_id_localidad = $id
        and ss.sso_estado in ('PLAN_PAGO_ATRASADO') and ss.sso_id in (select max(sso_id) from fbc_seguimiento_solicitud where id_solicitud = s.sol_id)
        group by sol_id_localidad
        union all
        select  count(*) as Cantidad, 'Analisis Procuracion' as Estado
        from FBC_CREDITO C
        inner join FBC_SOLICITUD s on s.sol_id = c.id_solicitud
        inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id
        where s.sol_id_localidad = $id
        and ss.sso_estado in ('ANALISIS_PROCU') and ss.sso_id in (select max(sso_id) from fbc_seguimiento_solicitud where id_solicitud = s.sol_id)
        group by sol_id_localidad
        union all
        select  count(*) as Cantidad, 'Solicitudes' as Estado
        from FBC_SOLICITUD s 
        inner join fbc_seguimiento_solicitud ss on ss.id_solicitud = s.sol_id
        where s.sol_id_localidad = $id
        and ss.sso_estado in ('EVALUACION_OBSERVADA', 'APROBADO', 'RECHAZADO', 'EVALUACION_TECNICA','EFECTIVIZACION','ANALISIS_CREDITICIO','SOLICITUD_INICIAL','ARMADO_LEGAJO','APROBACION_GERENCIA') and ss.sso_id in (select max(sso_id) from fbc_seguimiento_solicitud where id_solicitud = s.sol_id)
        group by sol_id_localidad
        ";
        $rs = sqlsrv_query($conn,$sql);
        $rows = array();

            while ($row = sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC)) {
                
                array_push($rows,array($row['Cantidad'], $row['Estado']));
            }
            $indexed = array_map('array_values', $rows);
        
        }   catch(exception $e){
            
            }
            header('Content-type: application/json; charset=utf-8');
            return json_encode($indexed);
        if (!$rows) {
            return null;
        }
    
}

function atms($id){
    try{
        $server = "";
        $user = "";
        $pwd="";
        $dba="";
        $concetinfo=array("Database" =>$dba , "UID" =>$user, "PWD"=>$pwd, "CharacterSet" => "UTF-8");
        $conn= sqlsrv_connect($server,$concetinfo);   
        
        $sql = "select a.atm_nombre as Nombre, a.atm_apellido as Apellido
        from  fbc_atm_localidad al 
        inner join fbc_atm a on a.atm_id  = al.id_atm
        where al.id_localidad = $id";
        $rs = sqlsrv_query($conn,$sql);
        $rows = array();
    
        while ($row = sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC)) {
            array_push($rows,array($row['Nombre'], $row['Apellido']));
        }
        $indexed = array_map('array_values', $rows);
        } catch (\Throwable $th) {
            //throw $th;
        }
        echo json_encode($indexed);
        if (!$rows) {
            return null;
        }
    




    

}
?>

