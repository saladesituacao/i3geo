<?php
//https://www.mediawiki.org/wiki/API:Main_page
include(dirname(__FILE__)."/../../ms_configura.php");
include(dirname(__FILE__)."/../blacklist.php");
verificaBlFerramentas(basename(dirname(__FILE__)),$i3geoBlFerramentas,false);
$usuarioGeonames = "i3geo";
//set_time_limit(600);
require_once(dirname(__FILE__)."/../../pacotes/cpaint/cpaint2.inc.php");
include_once (dirname(__FILE__)."/../../classesphp/sani_request.php");
$_GET = array_merge($_GET,$_POST);
$ret = explode(" ",$_GET["ret"]);
$x = $ret[0]*1 + (($ret[2]*1 - $ret[0]*1) / 2);
$y = $ret[1]*1 + (($ret[3]*1 - $ret[1]*1) / 2);
require_once(dirname(__FILE__)."/../../classesphp/carrega_ext.php");
error_reporting(0);
$cp = new cpaint();
$cp->register('listaartigos');
$cp->start();
$cp->return_data();
function listaartigos()
{
	global $x,$y, $cp, $usuarioGeonames;
	$e = explode(" ",$ret);
	$url = "https://pt.wikipedia.org/w/api.php?action=query&list=geosearch&gscoord=".$y."|".$x."&gsradius=10000&gslimit=10&format=json";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
	if (isset($i3geo_proxy_server) && $i3geo_proxy_server != "") {
	    curl_setopt($ch, CURLOPT_PROXY, $i3geo_proxy_server);
	}
	$resultado = curl_exec($ch);
	$resultado = json_decode($resultado,false);
	$resultado = $resultado->query->geosearch;
	$html = [];
	foreach($resultado as $r){
	    $html[] = "<div onmouseover='i3GEOF.wiki.mostraxy(".$r->lon.",".$r->lat.")' onmouseout='i3GEOF.wiki.escondexy()'><h4>".$r->title."</h4> ";
	    $html[] = "<a href='http://pt.wikipedia.org/wiki?curid=".$r->pageid."' target=blank >abrir Wikpedia</a><br>";
	    $html[] = "<hr></div>";
	}
	if(count($html) == 0){
	    $cp->set_data("<span style=color:red >Nada encontrado</span><br><hr>");
	} else {
	   $cp->set_data(implode(" ",$html));
	}
/*
array(10) {
  [0]=>
  object(stdClass)#6 (7) {
    ["pageid"]=>
    int(49169990)
    ["ns"]=>
    int(0)
    ["title"]=>
    string(58) "ServiÃ§o Brasileiro de Apoio Ã s Micro e Pequenas Empresas"
    ["lat"]=>
    float(-15.816)
    ["lon"]=>
    float(-47.8882)
    ["dist"]=>
    float(1309.3)
    ["primary"]=>
    string(0) ""
  }
 */
}
?>