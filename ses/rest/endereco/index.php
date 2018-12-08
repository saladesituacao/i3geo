<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
include ("../util.php");

error_reporting(0);

$config['displayErrorDetails'] = false;
$config['addContentLengthHeader'] = false;
$app = new \Slim\App([
    "settings" => $config
]);
$container = $app->getContainer();
$app->get('/{sid}/busca/{search}', function (Request $request, Response $response, $args) {
    $abre = \ses\Util\getSession($args["sid"]);
    if ($abre == false) {
        exit;
    }
    try{
        $url = "http://geocodeapi.codeplan.df.gov.br/?localidade=" . $args["search"];
        $handle = curl_init();
        curl_setopt( $handle, CURLOPT_URL, $url);
        curl_setopt( $handle, CURLOPT_HEADER, false );
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $str = curl_exec( $handle );
        curl_close( $handle );

        $resultados = json_decode($str)->features;
        $cat = array();
        foreach($resultados AS $r){
            $nome = $r->properties->nome;
            array_push($cat, array(
                "nome" => $nome,
                "ext" => $r->geometry->coordinates,
                "tipo" => "localidade"
            ));
        }

    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar codeplan");
        exit;
    }
    $response = $response->withHeader(
        'Content-Type', 'application/json');
    $response->getBody()
    ->write(json_encode($resultado = (array(
        "status" => true,
        "error" => null,
        "data" => array(
            "categorias" => $cat
        )
    ))));
    return $response;
});
$app->run();