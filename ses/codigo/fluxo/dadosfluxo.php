<?php
if(!isset($_GET["municipios"])){
    $_GET["municipios"] = "";
}
$municipios = explode(",",$_GET["municipios"]);
$_GET["municipios"] = "";

if(!isset($_GET["destino"])){
    $_GET["destino"] = "";
}
$destino = explode(",",$_GET["destino"]);
$_GET["destino"] = "";

if(!isset($_GET["procedimentos"])){
    $_GET["procedimentos"] = "";
}
$procedimentos = explode(",",$_GET["procedimentos"]);
$_GET["procedimentos"] = "";

include ("../../../ferramentas/safe.php");
include ("../../../ms_configura.php");

include ($conexaoadmin);
//
$inicio = $_GET["inicio"];
$fim = $_GET["fim"];

$tipoorigem = $_GET["tipoorigem"];

$municipiosSafe = array();
foreach($municipios as $d){
    if($d != ""){
        $municipiosSafe[] = $d * 1;
    }
}
if(count($municipiosSafe) > 0){
    $municipios = "'" . implode("','",$municipiosSafe) . "'";
} else {
    $municipios = "";
}

$destinoSafe = array();
foreach($destino as $d){
    if($d != ""){
        $destinoSafe[] = $d * 1;
    }
}
if(count($destinoSafe) > 0){
    $destino = "'" . implode("','",$destinoSafe) . "'";
} else {
    $destino = "";
}

$procedimentosSafe = array();
foreach($procedimentos as $d){
    if(is_numeric($d)){
        $procedimentosSafe[] = $d;
    }
}
if(count($procedimentosSafe) > 0){
    $procedimentos = "'" . implode("','",$procedimentosSafe) . "'";
} else {
    $procedimentos = "";
}
$colunaData = "i_dt_internacao";

//filtros
if($destino != ""){
    $destino = " i_estab_cnes::integer IN ({$destino}) AND ";
}

if($municipios != ""){
    $destino .= " i_munic_res::integer IN ({$municipios}) AND ";
}

if($procedimentos != ""){
    $destino .= " (i_grupo IN ({$procedimentos}) OR i_subgrupo IN ({$procedimentos}) OR i_forma_org IN ({$procedimentos}) OR i_proc_realizado IN ({$procedimentos}) ) AND ";
}
//filtra ou nao a ride
if($_GET["apenasride"] == "on" && $tipoorigem != "df"){
    //pega os municipos da RIDE
    $sql = "SELECT munic_res FROM dbauxiliares.tb_municipio WHERE ride = 'true'";
    try {
        $q = $dbhstage->query($sql, PDO::FETCH_COLUMN,0);
        $ride = $q->fetchAll();
        $ride = "'" . implode("','",$ride) . "'";
        $destino .= " i_munic_res IN ({$ride}) AND ";
    } catch (PDOException $e) {
        header("HTTP/1.1 500 erro ao consultar banco de dados");
        exit;
    }
}
//define o agrupamento por data e os SQLs que retornam apenas os dados
$granotime = "";
if(isset($_GET["granotime"])){
    if($_GET["granotime"] == "d"){
        $granotime = "extract(YEAR FROM {$colunaData})||'-'||extract(MONTH FROM {$colunaData})||'-'||extract(DAY FROM {$colunaData}) AS date ";
        $granotimegroup = $colunaData;
        $sqlUf2Cnes = "SELECT i_estab_cnes AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_estab_cnes,{$granotimegroup} ORDER by {$colunaData}";
        $sqlUf2Df = "SELECT 'DF' AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY {$granotimegroup} ORDER by {$colunaData}";
        $sqlDf2Cnes = "SELECT i_estab_cnes AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_estab_cnes,{$granotimegroup} ORDER by {$colunaData}";
        $sqlDf2Df = "SELECT 'DF' AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY {$granotimegroup} ORDER by {$colunaData}";
    }
    if($_GET["granotime"] == "m"){
        $granotime = "extract(YEAR FROM {$colunaData})||'-'||extract(MONTH FROM {$colunaData}) AS date ";
        $granotimegroup = "extract(YEAR FROM {$colunaData})||'-'||extract(MONTH FROM {$colunaData})";
        $sqlUf2Cnes = "SELECT i_estab_cnes AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_estab_cnes,{$granotimegroup} ORDER by date";
        $sqlUf2Df = "SELECT 'DF' AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY {$granotimegroup} ORDER by date";
        $sqlDf2Cnes = "SELECT i_estab_cnes AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_estab_cnes,{$granotimegroup} ORDER by date";
        $sqlDf2Df = "SELECT 'DF' AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY {$granotimegroup} ORDER by date";
    }
    if($_GET["granotime"] == "a"){
        $granotime = "extract(YEAR FROM {$colunaData}) AS date ";
        $granotimegroup = "extract(YEAR FROM {$colunaData})";
        $sqlUf2Cnes = "SELECT i_estab_cnes AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_estab_cnes,{$granotimegroup} ORDER by date";
        $sqlUf2Df = "SELECT 'DF' AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY {$granotimegroup} ORDER by date";
        $sqlDf2Cnes = "SELECT i_estab_cnes AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_estab_cnes,{$granotimegroup} ORDER by date";
        $sqlDf2Df = "SELECT 'DF' AS codigo_destino, {$granotime},count(*) AS numocorrencias FROM st_stage.tab_rd AS rd JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY {$granotimegroup} ORDER by date";
    }
    $sqlMun2Cnes = $sqlUf2Cnes;
    $sqlMun2Df = $sqlUf2Df;
} else {
    $sqlUf2Cnes = "SELECT u.nome_emp AS nomecompleto,'dt_internacao'::text AS tipo_ocorrencia,'uf'::text AS tipo_origem, 'cnes'::text AS tipo_destino, trim(i_desc_sigla_estab_cnes) AS nome_destino,i_estab_cnes AS codigo_destino, i_cod_uf_res AS codigo_origem, trim(i_desc_uf_res) AS nome_origem, count(*) AS numocorrencias, st_x(u.geom) AS x2, st_y(u.geom) AS y2, st_x(g.centroide) AS x1, st_y(g.centroide) AS y1 FROM st_stage.tab_rd AS rd JOIN dbauxiliares.tb_unidade AS u ON rd.i_estab_cnes = u.cnes::text JOIN dbauxiliares.tb_uf AS g ON g.sigla_uf = i_cod_uf_res WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_cod_uf_res,codigo_destino, nome_origem,nome_destino, u.geom, g.centroide,nomecompleto ORDER by nome_origem,nome_destino";
    $sqlUf2Df = "SELECT 'DF'::text AS nomecompleto,'dt_internacao'::text AS tipo_ocorrencia,'uf'::text AS tipo_origem, 'DF'::text AS tipo_destino, 'DF' AS nome_destino,'DF' AS codigo_destino, i_cod_uf_res AS codigo_origem, i_desc_uf_res AS nome_origem, count(*) AS numocorrencias, '-47.86' AS x2, '-15.79' AS y2, st_x(g.centroide) AS x1, st_y(g.centroide) AS y1 FROM st_stage.tab_rd AS rd JOIN dbauxiliares.tb_unidade AS u ON rd.i_estab_cnes = u.cnes::text JOIN dbauxiliares.tb_uf AS g ON g.sigla_uf = i_cod_uf_res WHERE {$destino} i_cod_uf_res != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY i_cod_uf_res, nome_origem, g.centroide {$granotime} ORDER by nome_origem,nome_destino";

    $sqlMun2Cnes = "SELECT u.nome_emp AS nomecompleto,'dt_internacao'::text AS tipo_ocorrencia,'mun'::text AS tipo_origem, 'cnes'::text AS tipo_destino, trim(i_desc_sigla_estab_cnes) AS nome_destino,i_estab_cnes AS codigo_destino, munic_res AS codigo_origem, trim(munic_res_desc)||' - '||munic_res_uf AS nome_origem, count(*) AS numocorrencias, st_x(u.geom) AS x2, st_y(u.geom) AS y2, st_x(g.centroide) AS x1, st_y(g.centroide) AS y1 FROM st_stage.tab_rd AS rd JOIN dbauxiliares.tb_unidade AS u ON rd.i_estab_cnes = u.cnes::text JOIN dbauxiliares.tb_municipio AS g ON g.munic_res = rd.i_munic_res WHERE {$destino} sigla_uf != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY munic_res,codigo_destino, nome_origem,nome_destino, u.geom, g.centroide,nomecompleto ORDER by nome_origem,nome_destino";
    $sqlMun2Df = "SELECT 'DF'::text AS nomecompleto,'dt_internacao'::text AS tipo_ocorrencia,'mun'::text AS tipo_origem, 'DF'::text AS tipo_destino, 'DF' AS nome_destino,'DF' AS codigo_destino, munic_res AS codigo_origem, munic_res_desc||' - '||munic_res_uf AS nome_origem, count(*) AS numocorrencias, '-47.86' AS x2, '-15.79' AS y2, st_x(g.centroide) AS x1, st_y(g.centroide) AS y1 FROM st_stage.tab_rd AS rd JOIN dbauxiliares.tb_unidade AS u ON rd.i_estab_cnes = u.cnes::text JOIN dbauxiliares.tb_municipio AS g ON g.munic_res = rd.i_munic_res WHERE {$destino} sigla_uf != '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY munic_res,nome_origem,g.centroide {$granotime} ORDER by nome_origem,nome_destino";

    $sqlDf2Cnes = "SELECT u.nome_emp AS nomecompleto,'dt_internacao'::text AS tipo_ocorrencia,'cep'::text AS tipo_origem,'cnes'::text AS tipo_destino,trim(i_desc_sigla_estab_cnes) AS nome_destino,i_estab_cnes AS codigo_destino,rd.i_cep_res AS codigo_origem,rd.i_cep_res AS nome_origem,count(*) AS numocorrencias,st_x(u.geom) AS x2,st_y(u.geom) AS y2,st_x(st_centroid(g.geom)) AS x1, st_y(st_centroid(g.geom)) AS y1 FROM st_stage.tab_rd AS rd JOIN dbauxiliares.tb_unidade AS u ON rd.i_estab_cnes = u.cnes::text JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY codigo_origem,codigo_destino, nome_origem,nome_destino, u.geom, g.geom,nomecompleto ORDER by nome_origem,nome_destino";
    $sqlDf2Df = "SELECT 'DF'::text AS nomecompleto,'dt_internacao'::text AS tipo_ocorrencia,'cep'::text AS tipo_origem,'DF'::text AS tipo_destino,'DF' AS nome_destino,'DF' AS codigo_destino,rd.i_cep_res AS codigo_origem,rd.i_cep_res AS nome_origem,count(*) AS numocorrencias,'-47.86' AS x2,'-15.79' AS y2,st_x(st_centroid(g.geom)) AS x1, st_y(st_centroid(g.geom)) AS y1 FROM st_stage.tab_rd AS rd JOIN dbauxiliares.tb_unidade AS u ON rd.i_estab_cnes = u.cnes::text JOIN dbauxiliares_geo.abrangecep AS g ON g.cep = rd.i_cep_res WHERE {$destino} i_cod_uf_res = '53' AND date({$colunaData}) BETWEEN date('{$inicio}') AND date('{$fim}') GROUP BY codigo_origem,codigo_destino, nome_origem,nome_destino, g.geom {$granotime} ORDER by nome_origem,nome_destino";
}

$SqlsPorTipo = array(
    "uf" => $sqlUf2Cnes,
    "ufDf" => $sqlUf2Df,
    "municipio" => $sqlMun2Cnes,
    "municipioDf" => $sqlMun2Df,
    "df" => $sqlDf2Cnes,
    "dfDf" => $sqlDf2Df
);

if(isset($_GET["exportashp"])){
    //echo $_SESSION["map_file"];exit;
    //echo $SqlsPorTipo[$tipoorigem];exit;
    //cria uma camada temporaria para gerar o zip
    //essa funcao vem de safe.php
    $oMapa = ms_newMapObj($_SESSION["map_file"]);
    $l = criaLayer($oMapa, MS_LAYER_LINE,MS_DEFAULT, "ins", "SIM", false);
    $novoMapfile = str_replace(".map","fluxo.map",$_SESSION["map_file"]);
    $l->set("connection", "stage");
    $l->setconnectiontype(MS_POSTGIS);
    $l->set("data","geom from (select codigo_destino||'-'||codigo_origem as gid,ST_MakeLine(ST_MakePoint(g.x1,g.y1), ST_MakePoint(g.x2,g.y2)) AS geom,* from (".$SqlsPorTipo[$tipoorigem].") AS g) as foo using unique gid using srid=4326");
    $l->setmetadata("permitedownload","SIM");
    $l->set("template","foo.html");
    $ext = $oMapa->extent;
    $ext->setextent(-180,-90,180,90);
    $oMapa->save($novoMapfile);
    $retorno = downloadTema2($novoMapfile, $l->name, $_SESSION["locaplic"], $_SESSION["dir_tmp"], $_SESSION["postgis_mapa"]);
    $retorno["arquivos"] = "";
    $retorno["datas"] = "";
    $_SESSION["downloadZipTema"] = $retorno["shape-zip"];
    $retorno["shape-zip"] = basename($retorno["shape-zip"]);
    ob_clean();
    header("Content-type: application/json");
    echo json_encode($retorno);

} else {
    //$nomecache = $_SESSION["dir_tmp"]."/".sha1($SqlsPorTipo[$tipoorigem].(date("j, n, Y")));
    //if(!file_exists($nomecache)){
        $resultado = array();
        try {
            //echo $tipoorigem;exit;
            //echo $SqlsPorTipo[$tipoorigem];exit;
            $q = $dbhstage->query($SqlsPorTipo[$tipoorigem], PDO::FETCH_ASSOC);
            $resultado = $q->fetchAll();
            //total de ocorrencias
            if(!isset($_GET["granotime"])){
                $q = $dbhstage->query("SELECT sum(numocorrencias)::numeric AS total FROM (" . $SqlsPorTipo[$tipoorigem] . ") AS t ", PDO::FETCH_ASSOC);
                $total = $q->fetchAll();
                $q = $dbhstage->query($SqlsPorTipo[$tipoorigem."Df"], PDO::FETCH_ASSOC);
                $df = $q->fetchAll();
                $resultado = array_merge($df,$resultado);
            }
        } catch (PDOException $e) {
            header("HTTP/1.1 500 erro ao consultar banco de dados");
            exit;
        }
        $resultado = array_group_by($resultado, "codigo_destino");
        $chaves = array_keys($resultado);
        //file_put_contents($nomecache,json_encode(array("totalocorrencias"=>$total[0]["total"],"chaves"=>$chaves,"dados"=>$resultado)));
    //}
    ob_clean();
    header("Content-type: application/json");
    //echo file_get_contents($nomecache);
    echo json_encode(array("totalocorrencias"=>$total[0]["total"],"chaves"=>$chaves,"dados"=>$resultado));
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
st_y(munic.centroide) AS origem_latitude_municipio
FROM st_stage.tab_rd AS rd
JOIN dbauxiliares.tb_unidade AS unidades ON rd.i_estab_cnes = unidades.cnes::text
JOIN dbauxiliares.tb_uf AS ufres ON ufres.sigla_uf = i_cod_uf_res
JOIN dbauxiliares.tb_uf AS ufestab ON ufestab.sigla_uf = left(trim(i_munic_estab_cnes),2)
JOIN dbauxiliares.tb_municipio AS munic ON munic.munic_res = rd.i_munic_res
*/
