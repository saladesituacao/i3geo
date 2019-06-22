<?php
//http://localhost:8019/i3geo/ses/codigo/printmapubs.php?map=imprimirunidadesabrange&cache=nao&codubs=0011258
//http://mapabase.manatustecnologia.com.br/wms?SERVICE=WMS&VERSION=1.1.0&REQUEST=GetMap&FORMAT=image%2Fpng&TRANSPARENT=true&LAYERS=osm&SRS=EPSG%3A4326&STYLES=&WIDTH=4000&HEIGHT=2400&BBOX=-47.622127532958984%2C-16.1279296875%2C-47.04019546508789%2C-15.728302001953125
include ("../../ms_configura.php");
include ($locaplic."/classesphp/funcoes_gerais.php");
error_reporting(0);
$posfixo = "06";
$nomeTemp = $dir_tmp."/".$_GET["map"].$_GET["codubs"]*1;
$nomecache = $nomeTemp.$posfixo.".svg";
if(file_exists($nomecache.".zip") && $_GET["cache"] == "sim"){
    readImage($nomecache.".zip");
    exit;
} else {
    @unlink($nomecache.".zip");
}

$handle = curl_init();
$host = "localhost:8019";
if($_SERVER['SERVER_NAME'] == "mapas.saude.df.gov.br"){
    $host = "https://mapas.saude.df.gov.br";
}
if($_SERVER['SERVER_NAME'] == "dmapas.saude.df.gov.br"){
    $host = "http://dmapas.saude.df.gov.br";
}
//obtem os dados da unidade
curl_setopt($handle, CURLOPT_URL, $host . "/i3geo/ses/rest/unidadesdesaude/lista");
curl_setopt($handle, CURLOPT_HEADER, false);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($handle, CURLOPT_TIMEOUT, 15);
$unidades = curl_exec($handle);
if($unidades === false){
    exit;
}
curl_close($handle);
$unidades = json_decode($unidades,true);
$unidade = "";
foreach($unidades["data"]["categorias"] as $u){
    if($u["cnes"] == $_GET["codubs"]){
        $unidade = $u;
    }
}
if($unidade == ""){
    exit;
}
//obtem a extensao geografica
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $host . "/i3geo/ses/rest/unidadesdesaude/abrangenciaubs/{$_GET["codubs"]}/wkt");
curl_setopt($handle, CURLOPT_HEADER, false);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($handle, CURLOPT_TIMEOUT, 15);
$abrangencia = curl_exec($handle);
if($abrangencia === false){
    exit;
}
curl_close($handle);
$abrangencia = json_decode($abrangencia,true);

$boundary = explode(",",$abrangencia["boundary"]);
$centrox = ($boundary[0] + $boundary[2])/2;
$centroy = ($boundary[1] + $boundary[3])/2;

$abrangencia["boundary"] = "-47.64,-15.71,-47.34,-15.56";
//-47.862246,-15.658379,-47.746482,-15.500256
//-48.022319,-15.983993224583,-47.878621378747,-15.894228
//echo $abrangencia["boundary"];exit;
$boundary = explode(",",$abrangencia["boundary"]);
//$boundary = array(round($boundary[0],6),round($boundary[1],6),round($boundary[2],6),round($boundary[3],6));
$mapfile = $locaplic."/temas/".$_GET["map"].".map";
if(!file_exists($mapfile)){
    echo "Arquivo nao existente";
    exit;
}
if(!isset($_GET["cache"])){
    $_GET["cache"] = "sim";
}

$map = ms_newMapObj($mapfile);

$map->setextent($boundary[0],$boundary[1],$boundary[2],$boundary[3]);
$poPoint = ms_newpointobj();
$poPoint->setXY($centrox, $centroy);
$map->setCenter($poPoint);

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

$map->selectOutputFormat("svg");
$layer = $map->getlayerbyname("abrangenciaubsemfoco");
$sql = $layer->data;
$sql = str_replace("xxxx",$_GET["codubs"]*1,$sql);
$layer->set("data",$sql);

$lc = $map->getlayerbyname("titulo");
$lc->updatefromstring('LAYER FEATURE POINTS 0 50 END TEXT "Rede Pública de Saúde - GDF - ' . $unidade["nome"] . '" END END');
substituiConObj($map,$postgis_mapa);
$img = $map->draw();
$img->saveImage($nomecache);

//readfile($nomecache); exit;
zip($nomecache);
echo header("Content-type: " . $map->outputformat->mimetype  . "\n\n");
readImage($nomecache.".zip");
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
    if(!file_exists($nome)){
        exit;
    }
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