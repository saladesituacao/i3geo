<?php
$i3geomaster = array();
$i3geoPermiteLogin = false;
if ($_SERVER['SERVER_NAME'] == "dmapas.saude.df.gov.br" || $_SERVER['SERVER_NAME'] == "localhost") {
    $i3geoPermiteLogin = true;
    $i3geomaster = array(array("usuario"=>"adminses", "senha"=>"adminses"));
}

$i3geoKeys = array(
    "salvaMapfile" => ""
);

$i3geoPermiteLoginIp = array();
$logTransacoes = false;
$i3geoEsquemasWL = array();
$i3geoUploadDataWL = array();
$logExec = array(
    "mapa_" => false, // mapa_openlayers e mapa_googlemaps
    "init" => false, // ms_criamapa mapa_inicia
    "ogc" => false, // servico ogc
    "upload" => false, // ferramentas de upload
    "ferramentas" => false, // todas as ferramentas que usam safe.php
    "controle" => false // tudo que passa por mapa_controle.php
);
$i3GeoProjDefault = array(
    'proj4' => '+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs ',
    'epsg' => '4326',
    'prj' => 'GEOGCS["GCS_WGS_1984",DATUM["D_WGS_1984",SPHEROID["WGS_1984",6378137,298.257223563]],PRIMEM["Greenwich",0],UNIT["Degree",0.017453292519943295]]'
);
$statusFerramentas = array(
    "saiku" => false,
    "melhorcaminho" => false
);
$i3GeoRegistraAcesso = false;
$i3geoBlFerramentas = array(
    "aplicarsld",
    "carregamapa",
    "importarwmc",
    "metaestat",
    "salvamapa",
    "upload",
    "uploadarquivos",
    "uploaddbf",
    "uploadgpx",
    "uploadkml"
);
$ogrOutput = true;
$saikuUrl = "";
$saikuConfigDataSource = array();
$i3georendermode = 0;
$linkedinoauth = "";
$facebookoauth = array(
    "consumerkey" => "",
    "consumersecret" => ""
);
$twitteroauth = array();
include (dirname(__FILE__) . "/versao.php");
$tituloInstituicao = "i3Geo";
$emailInstituicao = "";
$googleApiKey = "";
$metaestatTemplates = "/ferramentas/metaestat/templates";
$navegadoresLocais = "";
$locaplic = dirname(__FILE__);
$locmapserv = "";
$locmapas = "";
$R_libpath = "";
$R_path = "R";
// variaveis de configuracao
if (getenv('DBSALA') == false) {
    $_parametros = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/../configi3geoses.ini", true);
    $_parametros = $_parametros["i3geosesconfig"];
} else {
    $_parametros = array(
        "host"=>getenv('HOST'),
        "port"=>getenv('PORT'),
        "dbstage"=>getenv('DBSTAGE'),
        "dbsala"=>getenv('DBSALA'),
        "usersala"=>getenv('USERSALA'),
        "userapp"=>getenv('USERAPP'),
        "userstick"=>getenv('USERSTICK'),
        "passwordsala"=>getenv('PASSWORDSALA'),
        "passwordapp"=>getenv('PASSWORDAPP'),
        "passwordstick"=>getenv('PASSWORDSTICK')
    );
}
// mantenha sempre uma chave chamada "ses" pois eh utilizada em SQL outros que nao o i3Geo

$postgis_mapa = array(
    "ses" => "user=".$_parametros["dbsala"]." password=".$_parametros["passwordsala"]." dbname=".$_parametros["dbsala"]." host=".$_parametros["host"]." port=".$_parametros["port"],
    "stage" => "user=".$_parametros["dbsala"]." password=".$_parametros["passwordsala"]." dbname=".$_parametros["dbstage"]." host=".$_parametros["host"]." port=".$_parametros["port"]
);
$conexaoadmin = $locaplic . "/ses/conexao.php";

if ($_SERVER['SERVER_NAME'] == "dmapas.saude.df.gov.br" || $_SERVER['SERVER_NAME'] == "localhost") {
    $postgis_mapa["abrangencia_app"] = "user=".$_parametros["userapp"]." password=".$_parametros["passwordapp"]." dbname=".$_parametros["dbstage"]." host=".$_parametros["host"]." port=".$_parametros["port"];
}
$_parametros = null;
$utilizacgi = "nao";
$expoeMapfile = "sim";

$esquemaadmin = "geo";
$interfacePadrao = "ol.phtml";
$customDir = "ses";
if (file_exists("/var/www/i3geo")) {
    $base = $locaplic . "/ses/base.map";
} else {
    $base = $locaplic . "/ses/basehtml.map";
}
$ogcwsmap = $locaplic . "/ses/ogcws.map";
// para uso em localhost
if (file_exists("/var/www_ses_19/i3geo")) {
    $base = $locaplic . "/ses/baselocalhost.map";
    $ogcwsmap = $locaplic . "/ses/localhostogcws.map";
}
$cachedir = "/tmp/ms_tmp/cache";

$i3geo_proxy_server = ""; // "http://10.85.2.1:8080/";
$dir_tmp = "/tmp/ms_tmp";
$locmapserv = "/cgi-bin/mapserv";

/**
 * Define o idioma de inicializacao (cookies nao devem ter sido definidos anteriormente)
 *
 * Idiomas disponiveis: pt, en, es
 *
 * Para trocar, altere a linha abaixo
 */
if (empty($_COOKIE["i3geolingua"]) && array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $l = "pt";
    if ($lang == "en" || $lang == "es") {
        $l = $lang;
    }
    setcookie('i3geolingua', $l, time() + 60 * 60 * 24 * 365, '/');
}
error_reporting(0);
?>
