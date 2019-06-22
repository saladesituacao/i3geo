<?php
error_reporting(0);
/*
if(!isset($_GET["teste"])){
    session_name("i3GeoPHP");
    session_id($_COOKIE["i3GeoPHP"]);
    session_start();
    if($_SESSION["fingerprint"] != md5('I3GEOSEC' . $_SERVER['HTTP_USER_AGENT'] . session_id())){
        echo "Ooops - acesso nao permitido";
        exit;
    }
}
*/
$handle = curl_init();
curl_setopt( $handle, CURLOPT_URL, "http://www.geomobi.dftrans.df.gov.br/geoserver/wms?".$_SERVER["QUERY_STRING"] );
//error_log("---------------- http://www.geomobi.dftrans.df.gov.br/geoserver/ows?".$_SERVER["QUERY_STRING"]);
curl_setopt( $handle, CURLOPT_HEADER, false );
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($handle, CURLOPT_TIMEOUT, 20);
$str = curl_exec( $handle );
curl_close( $handle );
header('Access-Control-Allow-Origin: *');
if(!isset($_GET["INFO_FORMAT"])){
    header("Content-type: image/png");
}
echo $str;