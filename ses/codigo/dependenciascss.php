<?php
$arq = [
    "fluxo"=>"fluxo/fluxo.css"
];
$includes = explode(",",$_GET["includes"]);
if(extension_loaded('zlib')){
    ob_start('ob_gzhandler');
}
header("Content-type: text/css");
foreach($includes as $inc){
    include($arq[$inc]);
    echo "\n";
}
if(extension_loaded('zlib')){
    ob_end_flush();
}
exit;
?>