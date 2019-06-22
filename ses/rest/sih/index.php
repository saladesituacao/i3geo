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
        $query = '{"size":0,"aggs":{"data":{"composite":{"size":50,"sources":[{"grupo_proc":{"terms":{"field":"i_grupo.keyword"}}},{"grupo_proc_desc":{"terms":{"field":"i_desc_grupo.keyword"}}}]}}}}';
        $data = (json_decode(queryEs($query), true)["aggregations"]);
        $grupos = array();
        foreach ($data["data"]["buckets"] as $d) {
            $grupos[] = array(
                "grupo_proc" => $d["key"]["grupo_proc"],
                "grupo_proc_desc" => $d["key"]["grupo_proc_desc"]
            );
        }
        setlocale(LC_COLLATE, 'pt_PT.utf8');
        array_multisort(array_column($grupos, 'n'), SORT_ASC, SORT_LOCALE_STRING, $grupos);

        $query = '{"size":0,"aggs":{"data":{"composite":{"size":50,"sources":[{"grupo_proc":{"terms":{"field":"i_grupo.keyword"}}},{"grupo_proc_desc":{"terms":{"field":"i_desc_grupo.keyword"}}}]}}}}';
        $data = (json_decode(queryEs($query), true)["aggregations"]);
        $grupos = array();
        foreach ($data["data"]["buckets"] as $d) {
            $grupos[] = array(
                "grupo_proc" => $d["key"]["grupo_proc"],
                "grupo_proc_desc" => $d["key"]["grupo_proc_desc"]
            );
        }
        setlocale(LC_COLLATE, 'pt_PT.utf8');
        array_multisort(array_column($grupos, 'grupo_proc_desc'), SORT_ASC, SORT_LOCALE_STRING, $grupos);

        $query = '{"size":0,"aggs":{"data":{"composite":{"size":50,"sources":[{"grupo_proc":{"terms":{"field":"i_grupo.keyword"}}},{"subgrupo_proc":{"terms":{"field":"i_subgrupo.keyword"}}},{"subgrupo_proc_desc":{"terms":{"field":"i_desc_subgrupo.keyword"}}}]}}}}';
        $data = (json_decode(queryEs($query), true)["aggregations"]);
        $subgrupos = array();
        foreach ($data["data"]["buckets"] as $d) {
            $subgrupos[] = array(
                "grupo_proc" => $d["key"]["grupo_proc"],
                "subgrupo_proc" => $d["key"]["subgrupo_proc"],
                "subgrupo_proc_desc" => $d["key"]["subgrupo_proc_desc"]
            );
        }
        setlocale(LC_COLLATE, 'pt_PT.utf8');
        array_multisort(array_column($subgrupos, 'subgrupo_proc_desc'), SORT_ASC, SORT_LOCALE_STRING, $subgrupos);

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
    $query = '{"size":0,"aggs":{"data":{"composite":{"size":300,"sources":[{"n":{"terms":{"field":"i_desc_sigla_estab_cnes.keyword"}}},{"v":{"terms":{"field":"i_estab_cnes.keyword"}}}]}}}}';
    $data = (json_decode(queryEs($query), true)["aggregations"]);
    $extract = array();
    foreach ($data["data"]["buckets"] as $d) {
        $extract[] = array(
            "n" => $d["key"]["n"],
            "v" => $d["key"]["v"]
        );
    }
    setlocale(LC_COLLATE, 'pt_PT.utf8');
    array_multisort(array_column($extract, 'n'), SORT_ASC, SORT_LOCALE_STRING, $extract);
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()
        ->write(json_encode($extract));
    return $response;
});
// cnes que existem no sih
$app->get('/listamunicipios', function (Request $request, Response $response) {
    // $query='{"aggs":{"v":{"terms":{"field":"i_desc_munic_res.keyword","size":5000,"order":{"_term":"asc"}},"aggs":{"n":{"terms":{"field":"i_munic_res.keyword","size":1,"order":{"_term":"asc"}}}}}}}';
    $query = '{"size":0,"aggs":{"data":{"composite":{"size":5000,"sources":[{"n":{"terms":{"field":"i_desc_munic_res.keyword"}}},{"v":{"terms":{"field":"i_munic_res.keyword"}}}]}}}}';
    $data = (json_decode(queryEs($query), true)["aggregations"]);
    $extract = array();
    foreach ($data["data"]["buckets"] as $d) {
        $extract[] = array(
            "n" => $d["key"]["n"],
            "v" => $d["key"]["v"]
        );
    }
    setlocale(LC_COLLATE, 'pt_PT.utf8');
    array_multisort(array_column($extract, 'n'), SORT_ASC, SORT_LOCALE_STRING, $extract);
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()
        ->write(json_encode($extract));
    return $response;
});
$app->get('/{sid}/docsbyfilter', function (Request $request, Response $response, $args) {
    $parametros = $request->getQueryParams();
    $_GET["g_sid"] = $args["sid"];
    include("../../../classesphp/funcoes_gerais.php");
    if($parametros["outputformat"] != "geojson"){
        include ("../../../ferramentas/safe.php");
    }
    error_reporting(0);
    $filter = (object) [
        "bool" => (object) [
            "must" => array(
                (object) ["match_all" => (object) []],
                (object) ["range" => (object) [
                    "i_dt_internacao" => (object) [
                        "gte" => $parametros["inicio"],
                        "lt" => $parametros["fim"]
                    ]
                ]]
            ),
            "filter" => array(),
            "should" => array(),
            "must_not" => array()
        ]
    ];
    //origem UF destino CNES fora do df
    if($parametros["tipoorigem"] == "uf"){
        $sources = array (
            (object) ["origem_sigla" => (object) ["terms" => (object) ["field" => "origem_siglauf.keyword"]]],
            (object) ["origem_codigo" => (object) ["terms" => (object) ["field" => "origem_codigouf.keyword"]]],
            (object) ["origem_nome" => (object) ["terms" => (object) ["field" => "origem_siglauf.keyword"]]],
            (object) ["destino_codigo" => (object) ["terms" => (object) ["field" => "destino_codigocnes.keyword"]]],
            (object) ["destino_nome" => (object) ["terms" => (object) ["field" => "destino_nomecnes.keyword"]]],
            (object) ["destino_sigla" => (object) ["terms" => (object) ["field" => "destino_siglacnes.keyword"]]],
            (object) ["origem_latitude" => (object) ["terms" => (object) ["field" => "origem_latitude_uf"]]],
            (object) ["origem_longitude" => (object) ["terms" => (object) ["field" => "origem_longitude_uf"]]],
            (object) ["destino_latitude" => (object) ["terms" => (object) ["field" => "destino_latitude_cnes"]]],
            (object) ["destino_longitude" => (object) ["terms" => (object) ["field" => "destino_longitude_cnes"]]]
        );
        array_push($filter->bool->must_not,
            (object) [
                "match_phrase" => (object) [
                    "origem_codigouf.keyword"=> (object) [
                        "query" => "53"
                ]]]
            );
    }
    if($parametros["tipoorigem"] == "municipio"){
        $sources = array (
            (object) ["origem_sigla" => (object) ["terms" => (object) ["field" => "origem_siglauf.keyword"]]],
            (object) ["origem_codigo" => (object) ["terms" => (object) ["field" => "origem_codigoibge.keyword"]]],
            (object) ["origem_nome" => (object) ["terms" => (object) ["field" => "origem_codigoibge.keyword"]]],
            (object) ["destino_codigo" => (object) ["terms" => (object) ["field" => "destino_codigocnes.keyword"]]],
            (object) ["destino_nome" => (object) ["terms" => (object) ["field" => "destino_nomecnes.keyword"]]],
            (object) ["destino_sigla" => (object) ["terms" => (object) ["field" => "destino_siglacnes.keyword"]]],
            (object) ["origem_latitude" => (object) ["terms" => (object) ["field" => "origem_latitude_municipio"]]],
            (object) ["origem_longitude" => (object) ["terms" => (object) ["field" => "origem_longitude_municipio"]]],
            (object) ["destino_latitude" => (object) ["terms" => (object) ["field" => "destino_latitude_cnes"]]],
            (object) ["destino_longitude" => (object) ["terms" => (object) ["field" => "destino_longitude_cnes"]]]
        );
        array_push($filter->bool->must_not,
            (object) [
                "match_phrase" => (object) [
                    "origem_codigouf.keyword"=> (object) [
                        "query" => "53"
                    ]]]
            );
    }
    if($parametros["tipoorigem"] == "df"){
        $sources = array (
            (object) ["origem_sigla" => (object) ["terms" => (object) ["field" => "origem_siglauf.keyword"]]],
            (object) ["origem_codigo" => (object) ["terms" => (object) ["field" => "origem_cep.keyword"]]],
            (object) ["origem_nome" => (object) ["terms" => (object) ["field" => "origem_cep.keyword"]]],
            (object) ["destino_codigo" => (object) ["terms" => (object) ["field" => "destino_codigocnes.keyword"]]],
            (object) ["destino_nome" => (object) ["terms" => (object) ["field" => "destino_nomecnes.keyword"]]],
            (object) ["destino_sigla" => (object) ["terms" => (object) ["field" => "destino_siglacnes.keyword"]]],
            (object) ["origem_latitude" => (object) ["terms" => (object) ["field" => "origem_latitude_cep"]]],
            (object) ["origem_longitude" => (object) ["terms" => (object) ["field" => "origem_longitude_cep"]]],
            (object) ["destino_latitude" => (object) ["terms" => (object) ["field" => "destino_latitude_cnes"]]],
            (object) ["destino_longitude" => (object) ["terms" => (object) ["field" => "destino_longitude_cnes"]]]
        );
    }
    if($parametros["apenasride"] == "on"){
        array_push($filter->bool->must,
            (object) ["match_phrase" => (object) [
                "origem_isride" => (object) [
                    "query" => "true"
                ]]]
            );
    }
    if($parametros["destino"] != ""){
        $destino = explode(",",$parametros["destino"]);
        $shouldnode = array();
        foreach ($destino as $d){
            array_push(
                $shouldnode,
                (object) ["match_phrase"  => ["destino_codigocnes.keyword" => $d]]
                );
        }
        array_push(
            $filter->bool->must,
            (object) ["bool" => (object) [
                "minimum_should_match"  => "1",
                "should" => $shouldnode
            ]]
            );
    }
    if($parametros["procedimentos"] != ""){
        array_push(
            $filter->bool->must,
            (object) ["multi_match" => (object)  [
                "query"  => $parametros["procedimentos"],
                "fields" => ["i_grupo.keyword","i_subgrupo.keyword","i_forma_org.keyword","i_proc_realizado.keyword"]
            ]]
            );
    }
    if($parametros["municipios"] != ""){
        $destino = explode(",",$parametros["municipios"]);
        $shouldnode = array();
        foreach ($destino as $d){
            array_push(
                $shouldnode,
                (object) ["match_phrase"  => ["origem_codigoibge.keyword" => $d]]
                );
        }
        array_push(
            $filter->bool->must,
            (object) ["bool" => (object) [
                "minimum_should_match"  => "1",
                "should" => $shouldnode
            ]]
            );
    }
    $aggregations = (object) [
        "ocorrencias" => (object) ["sum" => (object) ["field" => "i_qtd_aih"]]
    ];
    $obj = (object) [
        "size" => 0,
        "aggs" => (object) [ "data" => (object) [
            "composite" => (object) [
                "size" => 5000,
                "sources" => $sources
            ],
            "aggregations" => $aggregations
        ]],
        "stored_fields" => array("*"),
        "script_fields" => (object) [],
        "docvalue_fields" => array("i_dt_internacao"),
        "query" => $filter
    ];
    $query = json_encode($obj);

    //header('Content-type: application/json');
    //echo($query);exit;

    $data = (json_decode(queryEsBody($query,"fluxo_rd_new"), true));
    $totalocorrencias = 0;
    $chaves = [];
    $resultado = array();
    $resultadoDf = array();
    $origens = array();
    $destinos = array();
    $fluxos = array();
    foreach ($data["aggregations"]["data"]["buckets"] as $d) {
        $totalocorrencias = $totalocorrencias + $d["ocorrencias"]["value"];
        $key = $d["key"];
        $chaves[] = $key["destino_codigo"];
        $resultado[] = array(
            "nomecompleto" => $key["destino_nome"],
            "tipo_ocorrencia" => "dt_internacao",
            "tipo_origem" => $parametros["tipoorigem"],
            "tipo_destino" => "cnes",
            "nome_destino" => $key["destino_sigla"],
            "codigo_destino" => $key["destino_codigo"],
            "codigo_origem" => $key["origem_codigo"],
            "nome_origem" => $key["origem_sigla"]." - ".$key["origem_nome"],
            "numocorrencias" => $d["ocorrencias"]["value"],
            "x2" => $key["destino_longitude"],
            "y2" => $key["destino_latitude"],
            "x1" => $key["origem_longitude"],
            "y1" => $key["origem_latitude"]
        );
        $n = $origens[$key["origem_codigo"]]["numocorrencias"];
        $origens[$key["origem_codigo"]] = array(
            "tipo" => $parametros["tipoorigem"],
            "codigo" => $key["origem_codigo"],
            "sigla" => $key["origem_sigla"],
            "nome" => $key["origem_nome"],
            "numocorrencias" => $d["ocorrencias"]["value"] + $n,
            "long" => $key["origem_longitude"],
            "lat" => $key["origem_latitude"]
        );
        $n = $destinos[$key["destino_codigo"]]["numocorrencias"];
        $destinos[$key["destino_codigo"]] = array(
            "tipo" => "cnes",
            "codigo" => $key["destino_codigo"],
            "sigla" => $key["destino_sigla"],
            "nome" => $key["destino_nome"],
            "numocorrencias" => $d["ocorrencias"]["value"] + $n,
            "long" => $key["destino_longitude"],
            "lat" => $key["destino_latitude"]
        );
        $n = $fluxos[$key["origem_codigo"]."-".$key["destino_codigo"]]["numocorrencias"];
        $fluxos[$key["origem_codigo"]."-".$key["destino_codigo"]] = array(
            "origem" => $key["origem_codigo"],
            "destino" => $key["destino_codigo"],
            "numocorrencias" => $d["ocorrencias"]["value"] + $n
        );
        $resultadoDf[] = array(
            "nomecompleto" => $key["destino_nome"],
            "tipo_ocorrencia" => "dt_internacao",
            "tipo_origem" => $parametros["tipoorigem"],
            "tipo_destino" => "DF",
            "nome_destino" => "DF",
            "codigo_destino" => "DF",
            "codigo_origem" => $key["origem_codigo"],
            "nome_origem" => $key["origem_sigla"]." - ".$key["origem_nome"],
            "numocorrencias" => $d["ocorrencias"]["value"],
            "x2" => -47.86,
            "y2" => -15.79,
            "x1" => $key["origem_longitude"],
            "y1" => $key["origem_latitude"]
        );
    }
    $resultado = array_group_by($resultado, "codigo_destino");
    $resultado["DF"] = $resultadoDf;
    $chaves = array_unique($chaves);
    array_unshift ( $chaves , "DF" );
    //obtem o menor e maior valor de ocorrencias
    $valorersocorrencias = array();
    foreach($fluxos as $f){
        $valorersocorrencias[] = $f["numocorrencias"];
    }
    if($parametros["outputformat"] == "geojson"){
        $features = array();
        foreach($fluxos as $fluxo){
            $feature = (object) [
                "type" => "Feature",
                "properties" => (object) [
                    "numocorrencias" => $fluxo["numocorrencias"],
                    "nome_destino" => $destinos[$fluxo["destino"]]["nome"],
                    "codigo_destino" => $destinos[$fluxo["destino"]]["codigo"],
                    "nome_origem" => $origens[$fluxo["origem"]]["nome"],
                    "codigo_origem" => $origens[$fluxo["origem"]]["codigo"],
                    "x2" => $destinos[$fluxo["destino"]]["long"],
                    "y2" => $destinos[$fluxo["destino"]]["lat"],
                    "x1" => $origens[$fluxo["origem"]]["long"],
                    "y1" => $origens[$fluxo["origem"]]["lat"]
                ],
                "geometry" => (object) [
                    "type" => "LineString",
                    "coordinates" => array(
                        [$origens[$fluxo["origem"]]["long"],$origens[$fluxo["origem"]]["lat"]],
                        [$destinos[$fluxo["destino"]]["long"],$destinos[$fluxo["destino"]]["lat"]]
                    )
                ]
            ];
            $features[] = $feature;
        }
        $geojson = (object) [
            "type" => "FeatureCollection",
            "crs" => (object) [
                "type" => "name", "properties" => (object) [ "name" => "urn:ogc:def:crs:EPSG::4674" ]
            ],
            "features" => $features
        ];
        header('Content-type: application/json');
        echo json_encode($geojson);
        exit;
    }
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()
    ->write(json_encode(
        array(
            "totalocorrencias" => $totalocorrencias,
            "maxocorrencias" => max($valorersocorrencias),
            "minocorrencias" => min($valorersocorrencias),
            "totalfluxos" => count($fluxos),
            "origens" => $origens,
            "destinos" => $destinos,
            "fluxos" => $fluxos,
            "chaves" => $chaves,
            "dados" => $resultado
        )));
    return $response;
});

$app->get('/{sid}/histogrambyfilter', function (Request $request, Response $response, $args) {
    $parametros = $request->getQueryParams();
    $_GET["g_sid"] = $args["sid"];
    include("../../../classesphp/funcoes_gerais.php");
    include ("../../../ferramentas/safe.php");
    error_reporting(0);
    $filter = (object) [
        "bool" => (object) [
            "must" => array(
                (object) ["match_all" => (object) []],
                (object) ["range" => (object) [
                    "i_dt_internacao" => (object) [
                        "gte" => $parametros["inicio"],
                        "lt" => $parametros["fim"]
                    ]
                ]]
            ),
            "filter" => array(),
            "should" => array(),
            "must_not" => array()
        ]
    ];
    //origem UF destino CNES fora do df
    if($parametros["tipoorigem"] == "uf"){
        array_push($filter->bool->must_not,
            (object) [
                "match_phrase" => (object) [
                    "origem_codigouf.keyword"=> (object) [
                        "query" => "53"
                    ]]]
            );
    }
    if($parametros["tipoorigem"] == "municipio"){
        array_push($filter->bool->must_not,
            (object) [
                "match_phrase" => (object) [
                    "origem_codigouf.keyword"=> (object) [
                        "query" => "53"
                    ]]]
            );
    }
    if($parametros["apenasride"] == "on"){
        array_push($filter->bool->must,
            (object) ["match_phrase" => (object) [
                "origem_isride" => (object) [
                    "query" => "true"
                ]]]
            );
    }
    if($parametros["destino"] != ""){
        $destino = explode(",",$parametros["destino"]);
        $shouldnode = array();
        foreach ($destino as $d){
            array_push(
                $shouldnode,
                (object) ["match_phrase"  => ["destino_codigocnes.keyword" => $d]]
                );
        }
        array_push(
            $filter->bool->must,
            (object) ["bool" => (object) [
                "minimum_should_match"  => "1",
                "should" => $shouldnode
            ]]
            );
    }
    if($parametros["procedimentos"] != ""){
        array_push(
            $filter->bool->must,
            (object) ["multi_match" => (object)  [
                "query"  => $parametros["procedimentos"],
                "fields" => ["i_grupo.keyword","i_subgrupo.keyword","i_forma_org.keyword","i_proc_realizado.keyword"]
            ]]
            );
    }
    if($parametros["municipios"] != ""){
        $destino = explode(",",$parametros["municipios"]);
        $shouldnode = array();
        foreach ($destino as $d){
            array_push(
                $shouldnode,
                (object) ["match_phrase"  => ["origem_codigoibge.keyword" => $d]]
                );
        }
        array_push(
            $filter->bool->must,
            (object) ["bool" => (object) [
                "minimum_should_match"  => "1",
                "should" => $shouldnode
            ]]
            );
    }
    $aggregations = (object) [
        "ocorrencias" => (object) ["sum" => (object) ["field" => "i_qtd_aih"]]
    ];
    $obj = (object) [
        "size" => 0,
        "aggs" => (object) [ "data" => (object) [
            "date_histogram" => (object) [
                "field" => "i_dt_internacao",
                "interval" => $parametros["granotime"],
                "time_zone" => "America/Sao_Paulo",
                "min_doc_count" => 1
            ],
            "aggs" => $aggregations
        ]],
        "stored_fields" => array("*"),
        "script_fields" => (object) [],
        "docvalue_fields" => array("i_dt_internacao"),
        "query" => $filter
    ];
    $query = json_encode($obj);

    //header('Content-type: application/json');
    //echo($query);exit;

    $data = (json_decode(queryEsBody($query,"fluxo_rd_new"), true));
    //var_dump($data);exit;
    $totalocorrencias = 0;
    $resultado = array();
    foreach ($data["aggregations"]["data"]["buckets"] as $d) {
        $totalocorrencias = $totalocorrencias + $d["ocorrencias"]["value"];
        $resultado[] = array(
            "date" => explode("T",$d["key_as_string"])[0],
            "numocorrencias" => $d["ocorrencias"]["value"]
        );
    }
    $response = $response->withHeader('Content-Type', 'application/json');
    $response->getBody()
    ->write(json_encode(
        array(
            "totalocorrencias" => $totalocorrencias,
            "dados" => $resultado
        )));
    return $response;
});
$app->run();

function queryEs($q,$indice = "rd_new")
{
    $url = "http://elkup:9200/". $indice ."/_search/?source_content_type=application/json&source=";
    if ($_SERVER['SERVER_NAME'] == "localhost") {
        $url = "http://desenv.saude.df.gov.br:9200/". $indice ."/_search/?source_content_type=application/json&source=";
    }
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url . $q);
    curl_setopt($handle, CURLOPT_HEADER, false);
    curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $str = curl_exec($handle);
    curl_close($handle);
    return $str;
}
function queryEsBody($q,$indice = "rd_new")
{
    $url = "http://elkup:9200/". $indice ."/_search";
    if ($_SERVER['SERVER_NAME'] == "localhost") {
        $url = "http://desenv.saude.df.gov.br:9200/". $indice ."/_search";
    }
    //var_dump($q);exit;
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($handle, CURLOPT_NOBODY, false);
    curl_setopt($handle, CURLOPT_HEADER, false );
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $q);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('content-type: application/json; charset=UTF-8'));
    $str = curl_exec( $handle );
    curl_close( $handle );
    return $str;
}
/*
SELECT
unidades.nome_emp AS destino_nomecnes,
trim(i_desc_sigla_estab_cnes) AS destino_siglacnes,
i_estab_cnes AS destino_codigocnes,
left(trim(i_munic_estab_cnes),2) AS destino_codigouf,
ufestab.sigla_uf_desc AS destino_siglauf,
i_cod_uf_res AS origem_codigouf,
trim(i_desc_uf_res) AS origem_siglauf,
munic.munic_res AS origem_codigoibge,
munic.munic_res_desc AS origem_nomemunicipio,
i_cep_res AS origem_cep,
i_ride AS origem_isride,
i_dt_internacao,
i_grupo,
i_subgrupo,
i_forma_org,
i_proc_realizado,
i_desc_tipo_estab_cnes,
i_desc_regiao_saude AS i_desc_regiao_saude_cnes,
i_desc_sexo,
i_faixa_etaria,
i_desc_tipo_uti,
i_qtd_diaria_pac,
i_val_total_aih,
i_desc_nat_jur,
i_desc_car_int_atend,
i_qtd_aih,
i_proc_alta_complex,
i_partos,
i_cirurgias,
st_x(unidades.geom) AS destino_longitude_cnes,
st_y(unidades.geom) AS destino_latitude_cnes,
st_x(ufres.centroide) AS origem_longitude_uf,
st_y(ufres.centroide) AS origem_latitude_uf,
st_x(munic.centroide) AS origem_longitude_municipio,
st_y(munic.centroide) AS origem_latitude_municipio,
st_x(st_centroid(cep.geom)) AS origem_longitude_cep,
st_y(st_centroid(cep.geom)) AS origem_latitude_cep
FROM st_stage.tab_rd AS rd
LEFT JOIN dbauxiliares.tb_unidade AS unidades ON rd.i_estab_cnes = unidades.cnes::text
LEFT JOIN dbauxiliares.tb_uf AS ufres ON ufres.sigla_uf = i_cod_uf_res
LEFT JOIN dbauxiliares.tb_uf AS ufestab ON ufestab.sigla_uf = left(trim(i_munic_estab_cnes),2)
LEFT JOIN dbauxiliares.tb_municipio AS munic ON munic.munic_res = rd.i_munic_res
LEFT JOIN dbauxiliares_geo.abrangecep AS cep ON cep.cep = rd.i_cep_res
*/

