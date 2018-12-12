<?php
/*
 * Define os parametros que serao utilizados na montagem do mapa default
 */
$formulario = file_get_contents("codigo/fluxo/sih_form.php");
// icone para abrir a guia com as opcoes de fluxo
$novoicone = '<div onclick="i3GEO.guias.ativa(\'fluxo\',this)" data-idconteudo="fluxoSESContainer"
    style="margin-top: 3px;">
    <button title="Fluxos" class="iconeGuiaMovel" style="color: white; box-shadow: none;">
    <i class="material-icons">device_hub</i>
    </button>
    </div>';

$botoes = '<button class="pull-left text-left" onclick="i3GEO.guias.abreFecha(\'fecha\');">
<span style="vertical-align: middle; font-size: 1.5rem;">Fluxo de Procedimentos Principais - SIH</span>
</button>
<button style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.ativa(\'temas\',this)" data-verificaAbrangencia=""
 data-idconteudo="guia1obj" data-idListaFundo="listaFundo" data-idListaDeCamadas="listaTemas">
<span style="vertical-align: middle" class="material-icons">visibility</span>
</button>
<button style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.ativa(\'adiciona\',this)" data-idconteudo="guia2obj"
 data-idMigalha="catalogoMigalha" data-idNavegacao="catalogoNavegacao" data-idCatalogo="catalogoPrincipal" data-idMenus="catalogoMenus">
<span style="vertical-align: middle" class="material-icons">layers</span>
</button>
<button style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.abreFecha(\'fecha\');">
<span style="vertical-align: middle" class="material-icons">cancel</span>
</button>';

$conteudoGuia = '<div class="corpoFundo" id="guia9obj" style="overflow: inherit;display: none; text-align: left; height: 100%">';
$conteudoGuia .= '<div class="i3GEOfechaGuia" style="display: flex; height:80px;">' . $botoes . '</div>';
$conteudoGuia .= '<div class="separadorCabecalhoGuias">&nbsp;</div>';
$conteudoGuia .= '<div data-tutorialfluxo="guia" class="" style="width: calc(100% - 5px);">';
$conteudoGuia .= $formulario;
$conteudoGuia .= '</div></div>';
$corpoconfig = array(
    "legenda" => true, // legenda do i3geo
    "tipoInterface" => "OSM",
    "debug" => "", // "naocompacto"
    "iconeGuiaMovel" => $novoicone,
    "conteudoGuia" => $conteudoGuia
);
include ("corpo.php");
?>
<!-- Scripts e CSS especificos da aplicacao SIH -->

<link rel="stylesheet" href="codigo/bootstrap-select/css/bootstrap-select.css" />
<link rel="stylesheet" href="codigo/bootstrap-table/dist/bootstrap-table.min.css" />

<script src="codigo/bootstrap-table/dist/bootstrap-table.js"></script>
<script src="codigo/bootstrap-table/dist/locale/bootstrap-table-pt-BR.js"></script>
<script src="codigo/bootstrap-table/dist/extensions/export/bootstrap-table-export.js"></script>
<script src="//rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js"></script>
<script src="codigo/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="codigo/bootstrap-select/js/defaults-pt_BR.min.js"></script>
<script src="codigo/fluxo/index.js"></script>
<script src="codigo/fluxo/tutorial.js"></script>
<style>
input[type="color"]::-moz-color-swatch {
    border: 0px solid grey;
}


.corpoFundo {
	background-color: #00695c !Important;
}

.corpoCabecalho {
	background-color: #003d33 !Important;
	color: white !Important;
}

#fluxoSESContainer.a, #fluxoSESContainer.a:focus, #fluxoSESContainer.a:hover {
	color: white !Important;
}
.adicionaFiltro {
    cursor: cell;
}
.adicionaFiltro:focus, .adicionaFiltro:hover {
    text-decoration: none;
}
.addfilter {
    top: 3px;
    cursor: cell;
    font-size: 1.8rem;
    margin-left: 3px;
    opacity: 0.8;
}
.fluxoSESlabel {
	color: white !Important;
	padding: 0px;
}
.addfilterTxt {
    position: relative;
    top: 1px;
    left: 3px;
}
div[role="listbox"] {
	overflow: auto !Important;
	height: unset !Important;
	max-height: 300px !Important;
}

.tree, .tree ul {
	margin: 0;
	padding: 0;
	list-style: none
}

.tree ul {
	margin-left: 1em;
	position: relative
}

.tree ul ul {
	margin-left: .5em
}

.tree ul:before {
	content: "";
	display: block;
	width: 0;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	border-left: 1px solid
}

.tree li {
	margin: 0;
	padding: 0 1em;
	line-height: 1.8em;
	color: unset;
	position: relative
}

.tree ul li:before {
	content: "";
	display: block;
	width: 10px;
	height: 0;
	border-top: 1px solid;
	margin-top: -1px;
	position: absolute;
	top: 1em;
	left: 0;
	background-color: unset !Important;
}

.tree ul li:last-child:before {
	background: #fff;
	height: auto;
	top: 1em;
	bottom: 0
}

.indicator {
	margin-right: 5px;
    font-size: 1.8rem;
    opacity: 0.8;
}

.tree li a {
	text-decoration: none;
	color: unset;
}

.tree li button, .tree li button:active, .tree li button:focus {
	text-decoration: none;
	color: unset;
	border: none;
	background: transparent;
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	outline: 0;
}

.closefilter {
	padding-top: 5px;
	padding-right: 35px;
	padding-bottom: 5px;
	padding-left: 5px;
	margin-bottom: 5px;
	display: inline-block;
	font-size: 1rem;
	margin-left: 5px;
}

.closefilter>button {
	font-size: 1.5rem;
}

.table-hover>tbody>tr:hover {
	background-color: rgba(255, 255, 255, 0.3);
}
.loaderLayers {
    margin: auto;
}

.bootstrapTable tr {
    height: 40px;
}
.fixed-table-body {
    height: calc(100% + 10px);
}
</style>
<!-- Templates Mustache utilizados -->
<?php
include("codigo/fluxo/templateGrupoFluxo.php");
include("codigo/fluxo/templateCamadasFluxo.php");
?>
<script>
/**
 * Configuracao default utilizada em todos os mapas
 */
<?php include ("corpo_script.php"); ?>
/**
 * Configuracoes especificas do aplicativo SIH
 */

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
        //para a guia com as opcoes do fluxo
        i3GEO.guias.CONFIGURA.fluxo = {
            icone : "",
            titulo : "",
            id : "guia9",
            idconteudo : "guia9obj",
            click : function(obj) {
            }
        };
        i3GEO.guias.ativa('fluxo',$("[data-idconteudo='guia9obj'"));
        i3GEO.guias.abreFecha("abre","fluxo");
        tutorialFluxo.init();
        tutorialFluxo.start();
        fluxo.startApp();
        setTimeout(function(){if(tutorialFluxo.getCurrentStep() == 0){
            tutorialFluxo.end();
        }}, 6000);

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
