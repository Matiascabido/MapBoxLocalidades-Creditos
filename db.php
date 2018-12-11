<?php
// Opens a connection to a MySQL server.


// Sets the active MySQL database.
/*$db_selected = mysqli_select_db($connection,'accounts');
if (!$db_selected) {
    die ('Can\'t use db : ' . mysqli_error($connection));
}*/


$server = "";
$user = "";
$pwd="";
$dba="";
$concetinfo=array("Database" =>$dba , "UID" =>$user, "PWD"=>$pwd, "CharacterSet" => "UTF-8");
$conn= sqlsrv_connect($server,$concetinfo);
if (!$conn) {
    die('Not connected : ' . mysqli_connect_error());
    var_dump(mysqli_connect_error());
}
