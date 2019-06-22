<?php
//
//http://localhost:8019/i3geo/ses/timelinemap.php?layers=regiaoadmdf&type=dengueprimsintsem
//
$corpoconfig = array(
    "legenda"=>true,
    "tipoInterface"=>"OSM",
    "debug"=>""
);
include ("corpo.php");
if(isset($_GET["layers"])){
    $_GET["layers"] = str_replace(" ",",",trim(strip_tags($_GET["layers"])));
    define('LISTADETEMAS', "'".implode("','",explode(",",$_GET["layers"]))."'");
} else {
    define('LISTADETEMAS',"");
}
if(isset($_GET["type"])){
    $_GET["type"] = trim(strip_tags($_GET["type"]));
    define('TYPE', $_GET["type"]);
} else {
    define('TYPE',"");
}
?>
<script>
<?php include ("corpo_script.php"); ?>
    parametrosMapa.layers = {
        //array com a lista dos layers que serao adicionados e ligados (visiveis)
        add : [<?php echo LISTADETEMAS; ?>,"unidades","ridedf2016","regioesdesaude","regiaodesaudelimites","regioesdesaudenomes"],
        //array com a lista dos layers que serao adicionados mas nao ligados
        on : [<?php echo LISTADETEMAS; ?>],
        //array com os layers desligados
        off : ["unidades","ridedf2016","regioesdesaude","regiaodesaudelimites","regioesdesaudenomes"]
    };
    //Funcao que sera executada apos a inicializacao do mapa
    config.afterStart = function() {
        $('.iconeGuiaMovel').tooltip({
            animation : false,
            trigger : "manual hover",
            placement : "left"
        });
        $('.iconeGuiaMovel').tooltip('show');
        setTimeout(function(){$('.iconeGuiaMovel').tooltip('hide');},5000);

        $('.ol-i3GEOcontrols button').tooltip({
            animation : false,
            trigger : "hover",
            placement : "auto",
            template : "<div class='tooltip ' ><div class='tooltip-inner'></div></div>"
        });
        caixadebusca.start();
        destaques.start();
        tutorial.init();

        new ol.control.Zoom({
            target: $i("i3GEOzoomInOut")
        }).setMap(i3geoOL);
        if(i3GEO.util.detectaMobile() == false){
            i3GEO.legenda.inicia({
                "janela": false,
                "idLegenda": "legendaHtmlSES"
            });
        }
        //veja em corpo_script.php
        open_timelinemap("<?php echo TYPE;?>");
        //open_timelinemap();
    };
    //
    //inicia o mapa
    //Veja tambem config.php
    //
    //O primeiro parametro permite alterar o mapa, inserindo camadas e outras definicoes que afetam o corpo do mapa
    //O segundo parametro inclui configuracoes que afetam o funcionamento da interface que controla a visualizacao do mapa
    //
    i3GEO.init(parametrosMapa, config);
</script>
</body>
</html>