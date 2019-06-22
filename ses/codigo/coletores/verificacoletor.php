<?php
/*
header("Content-type: application/json");
echo json_encode(array("data"=>rand(0,1) == 1));
exit;
*/
//http://localhost:8019/i3geo/ses/codigo/coletores/verificacoletor.php?id=10&porta=9 13 37 8080
include ("../../../ms_configura.php");
include ($conexaoadmin);
//$sql = "SELECT cnes,cnes_desc,local,cod_coletor,desc_tipo_unidade_ses,logradouro,bairro,complement from  st_stage.tab_coletores";
set_time_limit(4);
$sql = "SELECT ip from  st_stage.tab_coletores WHERE cod_coletor = '".($_GET["id"]*1)."'";
$dbhstage->setAttribute(PDO::ATTR_TIMEOUT, 2);
$q = $dbhstage->query($sql, PDO::FETCH_ASSOC);
$lista = $q->fetchAll();
$ip = $lista[0]["ip"];
$r = false;
$teste = false;
if(empty($ip)){
    $r = "ip nao encontrado";
} else {
    $ip = explode(".",$ip);
    $ip = ($ip[0]*1).".".($ip[1]*1).".".($ip[2]*1).".".($ip[3]*1);

    exec("ping -c 1 " . $ip,$saida,$retorno);
    //var_dump($saida);exit;
    if ($saida[4] != "1 packets transmitted, 0 received, +1 errors, 100% packet loss, time 0ms") {
        $r = true;
    }else{
        $r = false;
    }
    /*
    exec ("curl -f -m 5 ".$ip.":8080",$teste);
    if (empty($teste)) {
        $r = false;
    } else {
        $r = true;
    }
    */
}
ob_clean();
header("Content-type: application/json");
echo json_encode(array("data"=>$r));