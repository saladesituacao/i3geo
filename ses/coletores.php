<?php
if(i3GEOSES != "ok"){
    echo "Voce nao fez login na Sala";
    exit;
}
$corpoconfig = array(
    "legenda" => true,
    "tipoInterface" => "OSM",
    "debug" => ""
);
include ("corpo.php");
?>
<script src="codigo/coletores/index.js"></script>
<style>
.snackbar {
	width: 300px;
}

#snackbar-container {
	bottom: 100px;
}

.localizarxy, .ol-scale-line {
	display: none;
}

.snackbar.navy {
	background-color: navy;
}

.dotRed {
	height: 10px;
	width: 10px;
	background-color: red;
	border-radius: 50%;
	display: inline-block;
	margin-right: 4px;
}

.dotGreen {
	height: 10px;
	width: 10px;
	background-color: green;
	border-radius: 50%;
	display: inline-block;
	margin-right: 4px;
}

.chartColetores {
	display: block !Important;
	background-color: black;
    color: white;
	position: absolute;
	bottom: 0;
	left: 20px;
}
</style>
<div class="chartColetores snackbar snackbar-opened">
    <canvas id="chartColetores" width="400" height="100"></canvas>
</div>

<script>
<?php include ("corpo_script.php"); ?>
    //parametrosMapa.layers.add.push("coletoreslayer");
    //parametrosMapa.layers.on.push("coletoreslayer");
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
        coletores.start();
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
