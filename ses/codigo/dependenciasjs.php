<?php
$arq = [
    "bootstrap-table"=>"bootstrap-table/dist/bootstrap-table.js",
    "bootstrap-table-pt-BR"=>"bootstrap-table/dist/locale/bootstrap-table-pt-BR.js",
    "bootstrap-table-export"=>"bootstrap-table/dist/extensions/export/bootstrap-table-export.js",
    "tableexport"=>"tableexport.js",
    "bootstrap-select.min"=>"bootstrap-select/js/bootstrap-select.min.js",
    "bootstrap-select-pt_BR.min"=>"bootstrap-select/js/defaults-pt_BR.min.js",
    "fluxo.index"=>"fluxo/index.js",
    "fluxo.tutorial"=>"fluxo/tutorial.js",
    "timelinemap.index"=>"timelinemap/index.js",
    "timelinemap.config"=>"timelinemap/config.js",
    "elasticsearch.15.3.1.min"=>"elasticsearch.15.3.1.min.js",
    "d3.v4.min"=>"d3.v4.min.js"
];
$includes = explode(",",$_GET["includes"]);
if(extension_loaded('zlib')){
    ob_start('ob_gzhandler');
}
header("Content-type: text/javascript");

foreach($includes as $inc){
    include($arq[$inc]);
    echo "\n";
}
if(extension_loaded('zlib')){
    ob_end_flush();
}