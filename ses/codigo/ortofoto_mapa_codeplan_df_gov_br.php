<?php
//http://localhost:8019/i3geo/ses/codigo/ortofoto_mapa_codeplan_df_gov_br.php?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image%2Fpng&TRANSPARENT=true&layers=mosaico_df_2015&srs=EPSG%3A4674&format=image%2Fpng&WIDTH=256&HEIGHT=256&CRS=EPSG%3A4326&STYLES=&BBOX=-15.8038330078125%2C-47.867431640625%2C-15.79833984375%2C-47.8619384765625

//echo "http://ortofoto.mapa.codeplan.df.gov.br/wms?".$_SERVER["QUERY_STRING"];exit;
error_reporting(0);
session_name("i3GeoPHP");
session_id($_COOKIE["i3GeoPHP"]);
session_start();
if($_SESSION["fingerprint"] != md5('I3GEOSEC' . $_SERVER['HTTP_USER_AGENT'] . session_id())){
    echo "Ooops - acesso nao permitido";
    exit;
}
$handle = curl_init();
curl_setopt( $handle, CURLOPT_URL, "http://ortofoto.mapa.codeplan.df.gov.br/wms?".$_SERVER["QUERY_STRING"] );
curl_setopt( $handle, CURLOPT_HEADER, false );
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec( $handle );
curl_close( $handle );
header("Content-type: image/png");
echo $str;