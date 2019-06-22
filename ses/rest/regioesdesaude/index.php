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

$app->get('/lista', function (Request $request, Response $response) {
    include ("../../../ms_configura.php");
    if(!file_exists("../../../temas/regioesdesaude.map")){
        header("HTTP/1.1 500 mapfile nao encontrado");
        exit;
    }
    try{
        $mapObj = ms_newMapObj("../../../temas/regioesdesaude.map");
        $layer = $mapObj->getlayerbyname("regioesdesaude");
        $stringconexao = $postgis_mapa["stage"];
        $layer->set("connection", $stringconexao);
        $layer->open();
        $ret = ms_newRectObj();
        $ret->setextent(-48.2851, -16, -47.30, -15.5);
        // $colunas = $layer->getItems();
        $status = $layer->whichShapes($ret);
        // $categorias = array(array(),array());
        $cat = array();
        if ($status == 0) {
            while ($s = $layer->nextShape()) {
                $ponto = $s->getCentroid();
                $ext = array(
                    $ponto->x,
                    $ponto->y
                );
                $nome = $s->values["class_regiao_desc"];
                array_push($cat, array(
                    "nome" => $nome,
                    "ext" => $ext,
                    "codigo_reg" => $s->values["class_regiao"],
                    "tipo" => "regiaodesaude"
                ));
            }
            $resultado = (array(
                "status" => true,
                "error" => null,
                "data" => array(
                    "categorias" => $cat
                )
            ));
        } else {
            $resultado = array(
                "status" => false,
                "error" => null,
                "data" => array()
            );
        }
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar banco de dados");
        exit;
    }
    $response = $response->withHeader(
        'Content-Type', 'application/json');
    $response->getBody()
    ->write(json_encode($resultado));
    return $response;
});
$app->get('/{codigo_reg}/wkt', function (Request $request, Response $response, $args) {
    include ("../../../ms_configura.php");
    if(!file_exists("../../../temas/regioesdesaude.map")){
        header("HTTP/1.1 500 mapfile nao encontrado");
        exit;
    }
    try{
        $mapObj = ms_newMapObj("../../../temas/regioesdesaude.map");
        $layer = $mapObj->getlayerbyname("regioesdesaude");
        $stringconexao = $postgis_mapa["stage"];
        $layer->set("connection", $stringconexao);
        $layer->open();
        $ret = ms_newRectObj();
        $ret->setextent(-48.2851, -16, -47.30, -15.5);
        // $colunas = $layer->getItems();
        $status = $layer->whichShapes($ret);
        $resultado = array();
        // $categorias = array(array(),array());
        if ($status == 0) {
            while ($s = $layer->nextShape()) {
                if($args["codigo_reg"] == $s->values["class_regiao"]){
                    $nome = $s->values["class_regiao_desc"];
                    $cat = array(
                        "nome" => $nome,
                        "wkt" => $s->toWkt(),
                        "codigo_reg" => $s->values["class_regiao"],
                        "tipo" => "regiaodesaude"
                    );
                }
            }
            $resultado = (array(
                "status" => true,
                "error" => null,
                "data" => $cat
            ));
        } else {
            $resultado = array(
                "status" => false,
                "error" => null,
                "data" => array()
            );
        }
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar banco de dados");
        exit;
    }
    $response = $response->withHeader(
        'Content-Type', 'application/json');
    $response->getBody()
    ->write(json_encode($resultado));
    return $response;
});
$app->run();

function array_combine_($keys, $values)
{
    $result = array();
    foreach ($keys as $i => $k) {
        $result[$k][] = $values[$i];
    }
    array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));
    return $result;
}