<?php
//http://localhost:8019/i3geo/ses/codigo/printmap.php?map=imprimirubs&cache=nao
include ("../../ms_configura.php");
include ($locaplic."/classesphp/funcoes_gerais.php");
error_reporting(0);
$posfixo = "a05";
//so tem essa opcao no momento
$whitelist = array(
    "imprimirubs" => "imprimirubs",
    "imprimirregiaosaude" => "imprimirregiaosaude"
);
if(!isset($_GET["outputformat"])){
    $_GET["outputformat"] = "svg";
}
if(empty($whitelist[$_GET["map"]])){
    echo "oops esse mapa não.";
    exit;
}
$_GET["map"] = $whitelist[$_GET["map"]];

$nomecache = $dir_tmp."/".$posfixo.$_GET["map"].".".$_GET["outputformat"];
$mapfile = $locaplic."/temas/".$_GET["map"].".map";
if(!file_exists($mapfile)){
    echo "Arquivo nao existente";
    exit;
}
if(!isset($_GET["cache"])){
    $_GET["cache"] = "sim";
}
if(file_exists($nomecache.".zip") && $_GET["cache"] == "sim"){
    readImage($nomecache.".zip");
    exit;
} else {
    @unlink($nomecache.".zip");
}
$map = ms_newMapObj($mapfile);
substituiConObj($map,$postgis_mapa);

//legenda
$mapleg = ms_newMapObj($mapfile);
$mapleg->selectOutputFormat("AGG_Q");
$numlayers = $mapleg->numlayers;
for ($i = 0; $i < $numlayers; ++ $i) {
    $la = $mapleg->getlayer($i);
    $nc = $la->numclasses;
    if($nc > 2){
        for ($c = 0; $c < $nc; $c ++) {
            $classe = $la->getclass($c);
            if ($classe->numstyles > 0) {
                $estilo = $classe->getstyle(0);
                $estilo->set("offsety", 8);
            }
        }
    }
}
$legenda = $mapleg->drawlegend();
$legenda->saveImage($dir_tmp."/".$_GET["map"]."legenda.png");
$nId = $map->getSymbolObjectById($map->getSymbolByName("legenda"));
$nId->setImagePath($dir_tmp."/".$_GET["map"]."legenda.png");

$map->selectOutputFormat($_GET["outputformat"]);
$img = $map->draw();
$img->saveImage($nomecache);
//readfile($nomecache);exit;
zip($nomecache);

function readImage($nome)
{
    if (ob_get_contents()) {
        ob_clean();
    }
    header('Content-type: application/zip');
    header('Content-Disposition: attachment; filename=' . basename($nome));
    readfile($nome);
}
function zip($nome){
    $nomezip = $nome.".zip";
    include ("../../pacotes/kmlmapserver/classes/zip.class.php");
    $ziper = new zipfile();
    $ziper->addFile(file_get_contents($nome),basename($nome));
    $fp = fopen($nomezip, "wb");
    fwrite($fp, $ziper->file());
    fclose($fp);
    if (file_exists($nomezip)) {
        ob_end_clean();
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename=' . basename($nomezip));
        readfile($nomezip);
    }
}
?>