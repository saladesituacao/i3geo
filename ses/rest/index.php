<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

error_reporting(0);

$config['displayErrorDetails'] = false;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App([
    "settings" => $config
]);
$container = $app->getContainer();

$container['tiporegiao'] = function () {
    $regioes = array(
        "AL"=>array("nome"=>"Amaz&ocirc;nia Legal","codigo"=>false),
        "brasil"=>array("nome"=>"Brasil","codigo"=>false),
        "CG"=>array("nome"=>"Regi&atilde;o de sa&uacute;de","codigo"=>true),
        "CG"=>array("nome"=>"Regi&atilde;o de sa&uacute;de","codigo"=>false),
        "PX"=>array("nome"=>"PDRS Xingu","codigo"=>false),
        "QF"=>array("nome"=>"Qualifar - recorte Brasil sem Mis&eacute;ria","codigo"=>false),
        "QS"=>array("nome"=>"Projeto Qualisus","codigo"=>true),
        "RD"=>array("nome"=>"RIDE","codigo"=>false),
        "RM"=>array("nome"=>"Regi&atilde;o Metropolitana","codigo"=>true),
        "SA"=>array("nome"=>"Semi&aacute;rido","codigo"=>false),
        "SF"=>array("nome"=>"SIS Fronteiras","codigo"=>false),
        "TC"=>array("nome"=>"Territ&oacute;rios da Cidadania","codigo"=>true),
        "uf"=>array("nome"=>"Estado","codigo"=>true),
        "regiao1"=>array("nome"=>"Norte","codigo"=>false),
        "regiao2"=>array("nome"=>"Nordeste","codigo"=>false),
        "regiao3"=>array("nome"=>"Sudeste","codigo"=>false),
        "regiao4"=>array("nome"=>"Sul","codigo"=>false),
        "regiao5"=>array("nome"=>"Centro-oeste","codigo"=>false)
    );
    return $regioes;
};

$app->get('/{tiporegiao}/{codigo}', function (Request $request, Response $response, $args) {
    if(!in_array($args["tiporegiao"],array_keys($this->tiporegiao))){
        $response->getBody()->write("regiao nao cadastrada");
        return $response;
    }
    if (extension_loaded('zlib')) {
        ob_start('ob_gzhandler');
    }
    header('Content-type: application/json');
    $arq = $args["tiporegiao"] . $args["codigo"] . ".geojson";
    $arq = filter_var($arq, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
    include ("arquivos/" . $arq);
    if (extension_loaded('zlib')) {
        ob_end_flush();
    }
});
$app->get('/{tiporegiao}', function (Request $request, Response $response, $args) {
    if(!in_array($args["tiporegiao"],array_keys($this->tiporegiao))){
        $response->getBody()->write("regiao nao cadastrada");
        return $response;
    }
    if (extension_loaded('zlib')) {
        ob_start('ob_gzhandler');
    }
    header('Content-type: application/json');
    $arq = $args["tiporegiao"] . ".geojson";
    $arq = filter_var($arq, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
    include ("arquivos/" . $arq);
    if (extension_loaded('zlib')) {
        ob_end_flush();
    }
});
$app->run();
?>