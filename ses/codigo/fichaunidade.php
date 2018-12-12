<?php
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, "http://api.saude.df.gov.br/ses/api/cnes?codigo=" . $_GET["codigo"]);
curl_setopt($handle, CURLOPT_HEADER, false);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec($handle);
curl_close($handle);
$foto = "../fotosunidades/".$_GET["codigo"].".PNG";
if(file_exists($foto)){
    $json = json_decode($str);
    $json->list[0]->foto = $_GET["codigo"].".PNG";
    $str = json_encode($json);
}
$foto = "../fotosunidades/".$_GET["codigo"].".png";
if(file_exists($foto)){
    $json = json_decode($str);
    $json->list[0]->foto = $_GET["codigo"].".png";
    $str = json_encode($json);
}
if (! @isset($_GET["outputtype"])) {
    header('Access-Control-Allow-Origin: *');
    header("Content-type: application/json");
    echo $str;
    exit;
}
if ($_GET["outputtype"] == "html") {
    error_reporting(0);
    $o = json_decode($str,true);
    $data = $o["list"][0];
    $html = array();
    $meta = $o["meta"];
    $html[] = '<div class="container-fluid">';
    foreach (array_keys($meta) as $m) {
        if($data[$m] != ""){
            $html[] = '<div class="row">';
            $html[] = "<div class='col-md-5 text-left'><strong>" . $meta[$m]["name"] . "</strong></div><div class='clo-md-7 text-left' style='margin-left:10px'>" . $data[$m] . "</div>";
            $html[] = '</div>';
        }
    }
    $html[] = '</div>';
    echo implode("",$html);
}
