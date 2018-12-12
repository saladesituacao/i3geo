<?php
include (dirname(__FILE__) . "/../safe2.php");
verificaBlFerramentas(basename(dirname(__FILE__)), $_SESSION["i3geoBlFerramentas"], false);
include (dirname(__FILE__) . "/../../classesphp/classe_temas.php");
switch (strtoupper($_GET["funcao"])) {
    case "INSEREFEATURE":
        $m = new Temas($_SESSION["map_file"]);
        $m->insereFeature($_GET["marca"], "ANNOTATION", $_GET["xy"], $_GET["texto"], $_GET["position"], $_GET["partials"], $_GET["offsetx"], $_GET["offsety"], $_GET["minfeaturesize"], $_GET["mindistance"], $_GET["force"], $_GET["shadowcolor"], $_GET["shadowsizex"], $_GET["shadowsizey"], $_GET["outlinecolor"], $_GET["cor"], $_GET["sombray"], $_GET["sombrax"], $_GET["sombra"], $_GET["fundo"], $_GET["angulo"], $_GET["tamanho"], $_GET["font"], $_GET["wrap"], false, $_GET["nomeTema"]);
        $m->salva();
        break;
    case "INSERECONECTOR":
        $m = new Temas($_SESSION["map_file"]);
        $m->insereFeature($_GET["marca"], "ANNOTATION", $_GET["xy2"], $_GET["texto"], $_GET["position"], $_GET["partials"], $_GET["offsetx"], $_GET["offsety"], $_GET["minfeaturesize"], $_GET["mindistance"], $_GET["force"], $_GET["shadowcolor"], $_GET["shadowsizex"], $_GET["shadowsizey"], $_GET["outlinecolor"], $_GET["cor"], $_GET["sombray"], $_GET["sombrax"], $_GET["sombra"], $_GET["fundo"], $_GET["angulo"], $_GET["tamanho"], $_GET["font"], $_GET["wrap"], false, $_GET["nomeTema"]);
        $m->insereFeature($_GET["marca"], "LINE", $_GET["xy1"]." ".$_GET["xy2"], "", $_GET["position"], $_GET["partials"], $_GET["offsetx"], $_GET["offsety"], $_GET["minfeaturesize"], $_GET["mindistance"], $_GET["force"], $_GET["shadowcolor"], $_GET["shadowsizex"], $_GET["shadowsizey"], $_GET["outlinecolor"], $_GET["cor"], $_GET["sombray"], $_GET["sombrax"], $_GET["sombra"], $_GET["fundo"], $_GET["angulo"], $_GET["tamanho"], $_GET["font"], $_GET["wrap"], false, $_GET["nomeTema"]." (lin)");
        $m->salva();
        break;
}
ob_clean();
header("Content-type: application/json");
echo json_encode($retorno);