<?php
$whitelist = array(
    "dengue_new*",
    "aids_consolidado_new*",
    "sifilis_consolidado_new*",
    "hiv_consolidado_new*"
);
if(empty($_GET["indx"]) || !in_array($_GET["indx"],$whitelist)){
    echo "indice nao permitido";
    exit;
}
if (getenv('ELKAPI') == false) {
    $urlapi = "api.saude.df.gov.br:9200";
} else {
    $urlapi = getenv('ELKAPI');
}
if($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "dmapas.saude.df.gov.br"){
    $urlapi = "dapi.saude.df.gov.br:9200";
}
$data_string = file_get_contents('php://input');
//echo $data_string;exit;
$url = $urlapi."/".strip_tags($_GET["indx"])."/_search";
$handle = curl_init();
curl_setopt( $handle, CURLOPT_URL, $url );
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($handle, CURLOPT_NOBODY, false);
curl_setopt($handle, CURLOPT_HEADER, false );
curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($handle, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
    );
$str = curl_exec( $handle );
curl_close( $handle );
exit;