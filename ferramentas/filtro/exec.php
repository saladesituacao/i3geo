<?php
include (dirname(__FILE__) . "/../safe2.php");
verificaBlFerramentas(basename(dirname(__FILE__)), $_SESSION["i3geoBlFerramentas"], false);
include (dirname(__FILE__) . "/../../classesphp/classe_temas.php");
$m = new Temas($_SESSION["map_file"], $_GET["tema"]);
switch (strtoupper($_GET["funcao"]))
{
    case "PEGAFILTRO":
        $retorno = $m->pegaFiltro();
        $retorno = base64_encode($retorno);
        break;
    case "INSEREFILTRO":
        $retorno = $m->insereFiltro(base64_decode($_GET["filtro"]), "", "sim");
        $m->salva();
        break;
}
ob_clean();
header("Content-type: application/json");
echo json_encode($retorno);
