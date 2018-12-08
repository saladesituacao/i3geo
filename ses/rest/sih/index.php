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
$app->get('/listagrupos', function (Request $request, Response $response) {
    include ("../../../ms_configura.php");
    include ("../../../classesphp/funcoes_gerais.php");
    include ($conexaoadmin);
    try {
        $sql = "SELECT DISTINCT * FROM dbauxiliares.tb_grupo_proc ORDER BY grupo_proc_desc";
        $q = $dbhstage->query($sql, PDO::FETCH_ASSOC);
        $grupos = $q->fetchAll();
        $sql = "SELECT DISTINCT left(subgrupo_proc,2) AS grupo_proc,subgrupo_proc,subgrupo_proc_desc FROM dbauxiliares.tb_subgrupo_proc ORDER BY grupo_proc, subgrupo_proc";
        $q = $dbhstage->query($sql, PDO::FETCH_ASSOC);
        $subgrupos = $q->fetchAll();

        $sql = "SELECT DISTINCT left(co_forma,4) AS subgrupo_proc,co_forma,co_forma_desc FROM dbauxiliares.tb_forma_proc ORDER BY co_forma";
        $q = $dbhstage->query($sql, PDO::FETCH_ASSOC);
        $forma = $q->fetchAll();

        $sql = "SELECT DISTINCT left(proc_rea,6) AS co_forma, proc_rea,proc_rea_desc FROM dbauxiliares.tb_procedimentos ORDER BY proc_rea";
        $q = $dbhstage->query($sql, PDO::FETCH_ASSOC);
        $procedimentos = $q->fetchAll();
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar banco de dados");
        exit();
    }

    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()
        ->write(json_encode(array(
        "grupos" => $grupos,
        "subgrupos" => array_group_by($subgrupos, "grupo_proc"),
        "formas" => array_group_by($forma, "subgrupo_proc"),
        "procedimentos" => array_group_by($procedimentos, "co_forma")
    )));

    return $response;
});
// cnes que existem no sih
$app->get('/listacnes', function (Request $request, Response $response) {
    include ("../../../ms_configura.php");
    include ($conexaoadmin);
    try {
        $sql = "SELECT i_estab_cnes::text AS v,i_desc_sigla_estab_cnes::text AS n FROM st_stage.tab_rd GROUP BY i_estab_cnes,i_desc_sigla_estab_cnes ORDER BY i_desc_sigla_estab_cnes";
        $q = $dbhstage->query($sql, PDO::FETCH_ASSOC);
        $cnes = $q->fetchAll();
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar banco de dados");
        exit();
    }
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()
        ->write(json_encode($cnes));

    return $response;
});
// cnes que existem no sih
$app->get('/listamunicipios', function (Request $request, Response $response) {
    include ("../../../ms_configura.php");
    include ($conexaoadmin);
    try {
        $sql = "SELECT i_munic_res::text AS v,i_desc_munic_res::text AS n FROM st_stage.tab_rd GROUP BY i_munic_res,i_desc_munic_res ORDER BY i_desc_munic_res";
        $q = $dbhstage->query($sql, PDO::FETCH_ASSOC);
        $cnes = $q->fetchAll();
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar banco de dados");
        exit();
    }
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()
        ->write(json_encode($cnes));

    return $response;
});
$app->run();