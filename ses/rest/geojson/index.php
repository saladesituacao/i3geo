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
        "regioes_de_saude"=>array(),
        "regioes_de_saude2018"=>array(),
        "ra_ses"=>array()
    );
    return $regioes;
};

$app->get('/{tiporegiao}', function (Request $request, Response $response, $args) {
    if(!in_array($args["tiporegiao"],array_keys($this->tiporegiao))){
        $response->getBody()->write("regiao nao cadastrada");
        return $response;
    }
    header('Access-Control-Allow-Origin: *');
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
