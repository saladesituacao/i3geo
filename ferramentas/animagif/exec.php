<?php
$_GET = $_POST;

include_once(dirname(__FILE__)."/../safe2.php");
verificaBlFerramentas(basename(dirname(__FILE__)),$_SESSION["i3geoBlFerramentas"],false);
$retorno = false;
switch (strtoupper($_POST["funcao"])){
	case "REMOVE":
		$mapa = ms_newMapObj($_SESSION["map_file"]);
		$l = $mapa->getlayerbyname($_POST["tema"]);
		if($l != ""){
			$l->setmetadata("animagif","");
			$mapa->save($_SESSION["map_file"]);
			$retorno = true;
		}
	break;
	case "INCLUI":
		$retorno = "erro";
		$mapa = ms_newMapObj($_SESSION["map_file"]);
		$l = $mapa->getlayerbyname($_POST["tema"]);
		if($l != ""){
			$l->setmetadata("animagif",str_replace("\\","'",$_POST["animagif"]));
			$mapa->save($_SESSION["map_file"]);
			$retorno = true;
		}
	break;
}
ob_clean();
if ($retorno == false) {
    header("HTTP/1.1 500 erro ");
} else {
    header("Content-type: application/json");
    echo json_encode($retorno);
}