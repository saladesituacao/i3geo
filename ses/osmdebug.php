<?php
$corpoconfig = array(
    "legenda"=>false,
    "tipoInterface"=>"OSM",
    "debug"=>"naocompacto"
);
include ("corpo.php");
?>
<script>
<?php include ("corpo_script.php"); ?>
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
        /*
        new ol.control.FullScreen({
    		target: $i("i3GEOFullscreen"),
    		className: "ol-FullScreen",
    		tipLabel: $trad("fullscreen"),
    		source: "i3geo"
	    }).setMap(i3geoOL);
        */
        new ol.control.Zoom({
            target: $i("i3GEOzoomInOut")
        }).setMap(i3geoOL);

        if(i3GEO.util.detectaMobile() == false){
            i3GEO.legenda.inicia({
                "janela": false,
                //"templateLegenda": "templatesi3geo/legendamashup.html",
                "idLegenda": "legendaHtmlSES"
            });
        }

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
