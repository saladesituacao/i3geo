<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
    if (! file_exists("../../../temas/unidades.map")) {
        header("HTTP/1.1 500 mapfile nao encontrado");
        exit();
    }
    try {
        $mapObj = ms_newMapObj("../../../temas/unidades.map");
        $layer = $mapObj->getlayerbyname("unidades");
        $stringconexao = $postgis_mapa["stage"];
        $layer->set("connection", $stringconexao);
        $layer->open();
        $ret = ms_newRectObj();
        $ret->setextent(-180,-90,180,90);
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
                //$rect = $s->bounds;
                $nome = $s->values["nome_estab_cnes"];
                array_push($cat, array(
                    "nome" => $nome,
                    "nomecompleto" => $s->values["nomecompleto"],
                    "ext" => $ext,
                    "id" => $s->values["cod_cnes"],
                    "cnes" => $s->values["cod_cnes"],
                    "tipo" => "unidadedesaude",
                    "tipounidade" => $s->values["tipo_unidade"]
                ));
            }
            array_multisort(
                array_map(strtolower, array_column($cat, 'nome')),
                SORT_ASC,SORT_NATURAL,
                $cat);

            $retorno = (array(
                "status" => true,
                "error" => null,
                "data" => array(
                    "categorias" => $cat
                )
            ));
        } else {
            $retorno = array(
                "status" => false,
                "error" => null,
                "data" => array()
            );
        }
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao obter os dados");
        exit();
    }
    $response = $response->withHeader('Content-Type', 'application/json');

    $response->getBody()
        ->write(json_encode($retorno));

    return $response;
});
// lista as unidades no formato utilizado pelo plugin
// parametrossql do i3Geo
$app->get('/listai3geo/hospitais', function (Request $request, Response $response) {
    include ("../../../ms_configura.php");

    if (! file_exists("../../../temas/unidades.map")) {
        header("HTTP/1.1 500 mapfile nao encontrado");
        exit();
    }
    try {
        $mapObj = ms_newMapObj("../../../temas/unidades.map");
        $layer = $mapObj->getlayerbyname("unidades");
        $stringconexao = $postgis_mapa["stage"];
        $layer->set("connection", $stringconexao);
        $layer->open();
        $ret = ms_newRectObj();
        $ret->setextent(- 48.2851, - 16, - 47.30, - 15.5);
        // $colunas = $layer->getItems();
        $status = $layer->whichShapes($ret);
        // $categorias = array(array(),array());
        $cat = array();
        if ($status == 0) {
            while ($s = $layer->nextShape()) {
                $nome = $s->values["nome_estab_cnes"];
                if (in_array($s->values["cod_tipo_unidade"], [
                    62,
                    7,
                    5
                ])) {
                    array_push($cat, array(
                        "n" => $nome,
                        "v" => $s->values["cod_cnes"]
                    ));
                }
            }
        }
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao obter os dados");
        exit();
    }
    $response = $response->withHeader('Content-Type', 'application/json');
    $response = $response->withHeader('Access-Control-Allow-Origin: *');

    $response->getBody()
        ->write(json_encode($cat));
    return $response;
});
$app->get('/abrangenciaubs/{cnes}/wkt', function (Request $request, Response $response, $args) {
    include ("../../../ms_configura.php");
    if (! file_exists("../../../temas/abrangenciaubs.map")) {
        header("HTTP/1.1 500 mapfile nao encontrado");
        exit();
    }
    try {
        $mapObj = ms_newMapObj("../../../temas/abrangenciaubs.map");
        $layer = $mapObj->getlayerbyname("abrangenciaubs");
        if($layer->connectiontype == MS_POSTGIS){
            $stringconexao = $postgis_mapa["stage"];
            $layer->set("connection", $stringconexao);
        }
        $layer->open();
        $ret = ms_newRectObj();
        $ret->setextent(-48.2851, -16, -47.30, -15.5);
        $status = $layer->whichShapes($ret);
        $wkt = array();
        $x = array();
        $y = array();
        if ($status == 0) {
            while ($s = $layer->nextShape()) {
                $cnesshapes = explode(",",$s->values["cnes"]);
                foreach($cnesshapes as $cnesshape){
                    if ($args["cnes"]*1 == $cnesshape*1 ) {
                        $wkt[] = $s->toWkt();
                        $rect = $s->bounds;
                        $x[] = $rect->minx;
                        $x[] = $rect->maxx;
                        $y[] = $rect->miny;
                        $y[] = $rect->maxy;
                    }
                }
            }
            $resultado = (array(
                "status" => true,
                "error" => null,
                "data" => $wkt,
                "boundary" => min($x).",".min($y).",".max($x).",".max($y)
            ));
        } else {
            $resultado = array(
                "status" => false,
                "error" => null,
                "data" => array(),
                "boundary" => null
            );
        }
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar banco de dados");
        exit();
    }
    $response = $response->withHeader('Content-Type', 'application/json');
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