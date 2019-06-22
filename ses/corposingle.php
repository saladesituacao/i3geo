<?php
if (! isset($corpoconfig)) {
    $corpoconfig = array(
        "legenda" => true,
        "tipoInterface" => "OSM",
        "debug" => "",
        "iconeGuiaMovel" => "",
        "conteudoGuia" => ""
    );
}
$configInc = array(
    "debug" => $corpoconfig["debug"], // posfixos inserido na carga do script do i3geo
    "tipo" => $corpoconfig["tipoInterface"], // OL ou OSM
    "inc" => "../interface/inc", // caminho para os includes PHP com os componentes da interface
    "pathjs" => "..", // caminho para o include dos arquivos JS
    "pathcss" => "..", // caminho para o include dos arquivos css
    "pathconfig" => ".", // caminho para o include do arquivo JS config.php
    "pathtutorial" => "../interface", // caminho para o include do arquivo JS tutorial.js
    "pathtemplates" => "../interface/templates", // caminho para a pasta template com os arquivos MUSTACHE
    "nocache" => time()
);
include ($configInc["inc"] . "/meta.php");
?>
<title>i3GEO</title>
<?php
include ($configInc["inc"] . "/js.php");
include ($configInc["inc"] . "/css.php");
?>
<!-- ver http://www.chartjs.org/ -->
<script src="codigo/Chart.bundle.min.js"></script>
<!-- lista com os links que serao mostrados na guia ferramentas -->
<script src="listaDeFerramentas.js"></script>
<!-- configuracoes default tipo pode ser OL (openLayers) ou GM (googlemaps) -->
<script src="config.php?tipo=<?php echo $corpoconfig["tipoInterface"];?>"></script>
<link rel="stylesheet" href="../pacotes/jquery/jquery-typeahead-2.10.4/dist/jquery.typeahead.min.css">
<script src="../pacotes/jquery/jquery-typeahead-2.10.4/dist/jquery.typeahead.min.js"></script>
<script src="codigo/destaques/index.js"></script>
<script src="codigo/caixadebusca/index.js"></script>
<link rel="stylesheet" href="codigo/bootstrap-select/css/bootstrap-select.css" />
<link rel="stylesheet" href="codigo/bootstrap-table/dist/bootstrap-table.min.css" />
<script src="codigo/bootstrap-table/dist/bootstrap-table.js"></script>
<script src="codigo/bootstrap-table/dist/locale/bootstrap-table-pt-BR.js"></script>
<script src="codigo/bootstrap-table/dist/extensions/export/bootstrap-table-export.js"></script>
<script src="//rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js"></script>
<style>
.ol-scale-line::after {
	display: none;
}

.ol-attribution.ol-uncollapsible {
	height: 2.1em;
	right: 24px;
	background: none;
	margin-bottom: 15px;
}

.foraDoMapa+span>span {
	background-color: yellow;
}

.typeahead__filter {
	/*display: none;*/

}

.typeahead__list {
	overflow: auto;
	max-height: 200px;
}

.localizarxy {
	left: 10px;
}

.iconesGuiaMovel {
	top: 50px;
}

#legendaHtmlSES {
	max-height: 500px;
	overflow: auto;
	background: white;
	width: calc(100% + 10px);
}

#legendaHtmlSESContainer {
	position: absolute;
	width: 300px;
	bottom: 60px;
	left: 10px;
	overflow: auto;
	background: white;
}

#legendaHtmlSESContainer {
	opacity: 0.8;
	filter: alpha(opacity = 60);
}

#legendaHtmlSESContainer:hover {
	opacity: 1;
	filter: alpha(opacity = 100);
}

.ol-overviewmap {
	left: .5em;
	bottom: 12.5em;
}

#legendaHtmlSESContainer [data-toggle*=collapse]:after {
	font-family: "Glyphicons Halflings";
	content: "\e114" !Important;
}
/* Icon when the collapsible content is hidden */
#legendaHtmlSESContainer [data-toggle*=collapse].collapsed:after {
	content: "\e113" !Important;
}

.formTop {
	z-index: 0;
	top: 5px;
	margin: auto;
	position: absolute;
	left: 0px;
	right: 0px;
	display: inline-flex;
	width: 480px;
}

.ol-i3GEOcontrols {
	top: 4.5rem;
	left: 1rem;
	width: auto;
	font-size: 1.8rem;
}

.aranha {
	background-color: #00695c;
}

.aranha>li {
	display: block;
}

@media print {
	.container-minimiza, .container-close, .fixed-table-toolbar {
		display: none;
	}
	.aranha>li {
		display: inline;
		margin-left: 20px;
	}
}

.destaque-menu {
	flex-wrap: wrap;
	width: 30vw;
}

.open>.destaque-menu.dropdown-menu {
	display: flex;
}

.destaque-container {
	position: relative;
	font: 1rem Lato, Helvetica Neue, Arial, Helvetica, sans-serif;
}

.destaque-container-pills {
	display: flex;
	width: 40vw;
	flex-wrap: wrap;
}

.filterPill {
	display: flex;
	padding: 2px;
	margin: 2px;
}

.destaques-pillColor {
	color: black !Important;
	background-color: orange !Important;
	font-weight: unset !Important;
}

#i3GEOToolFormModal .bootstrap-table {
	height: 80%;
}

@media ( min-width : 0px) and (max-width:767px) {
	.hiddenincell, .typeahead__filter {
		display: none !Important;
	}
	.buscaUnidade {
		width: 240px !Important;
	}
}
</style>
</head>
<!-- As palavras entre {{{}}} sao utilizadas para a traducao. Veja i3geo/js/dicionario.js
        Marque com data-traduzir="true" os elementos que deverao passar pelo tradutor
    -->
<body id="i3geo" style='background: white;'>
    <!-- inclui o nome do usuario logado
    <div id="i3GEONomeLogin"
        style="position: absolute; left: 10px; top: 2px; font-size: 11px; z-index: 50000"></div>
    -->
    <!-- Aqui vai o mapa. O div a ser inserido e padronizado e depende da interface usar openlayers ou googlemaps
    Se os estilos width e height nao estiverem definidos, o tamanho do mapa abrangera a tela toda
    -->
    <div id="mapai3Geo" style="width: 100vw; height: 100vh"></div>
    <!-- barra de icones de navegacao -->
    <div class="ol-i3GEOcontrols ol-control" data-traduzir="true">
        <div class="clearfix"></div>
        <span class="hidden-xs" id="i3GEOzoomInOut" style="cursor: pointer;"></span>
    </div>
    <?php include($configInc["inc"]."/barraaguarde.php");?>
    <!-- mensagem de copyright -->
    <div id="i3GEOcopyright">i3Geo - SES</div>
    <div id="i3GEOguiaMovel">
        <!-- configuracao para todos os botoes
            data-idconteudo - id do DIV que contem o conteudo da guia e que sera mostrado ao ser clicado
        -->
        <div class="iconesGuiaMovel ol-control" data-traduzir="true">
            <?php
            include ($configInc["inc"] . "/iconecamadas.php");
            if (isset($corpoconfig["iconeGuiaMovel"])) {
                echo $corpoconfig["iconeGuiaMovel"];
            }
            if ($corpoconfig["legenda"] == true) {
                include ($configInc["inc"] . "/iconelegenda.php");
            }
            ?>
        </div>
        <!-- veja i3GEO.guias.CONFIGURA -->
        <!-- Os IDs sao definidos no botao que ativa a guia veja: "i3GEOguiaMovel" -->
        <!-- se height nao estiver definido sera utilizada a altura do mapa -->
        <div id="i3GEOguiaMovelMolde">
            <div id="i3GEOguiaMovelConteudo">
                <?php
                include ($configInc["inc"] . "/guiaferramentas.php");
                include ($configInc["inc"] . "/guiacamadas.php");
                if (isset($corpoconfig["conteudoGuia"])) {
                    echo $corpoconfig["conteudoGuia"];
                }
                // include ($configInc["inc"] . "/guiacatalogo.php");
                ?>
                <div id='guia2obj' data-traduzir="true" style='display: none; text-align: left; height: 100%;'>
                    <div class="i3GEOfechaGuia" style="display: flex;">
                        <button class="pull-left text-left" onclick="i3GEO.guias.abreFecha('fecha');i3GEO.catalogoMenus.mostraCatalogoPrincipal();">
                            <span style="vertical-align: middle">{{{iconeCatalogo}}}</span>
                        </button>
                        <div class="dropdown">
                            <button title="{{{opcoes}}}" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span style="vertical-align: middle" class="material-icons">playlist_add_check</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="javascript:void(0)" onclick="i3GEO.arvoreDeTemas.dialogo.downloadbase()">
                                        <span class="glyphicon glyphicon-cloud-download"></span> {{{a3}}}
                                    </a></li>
                                <li><a href="http://localhost/i3geo/kml.php?tipoxml=kml" target="_blank">
                                        <span class="glyphicon glyphicon-import"></span> {{{a13}}}
                                    </a></li>
                            </ul>
                        </div>
                        <button title="{{{iconeMapa}}}" style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.ativa('temas',this)" data-verificaAbrangencia=""
                            data-idconteudo="guia1obj" data-idListaFundo="listaFundo" data-idListaDeCamadas="listaTemas" data-idListaLayersGr="listaLayersGr" data-idBtnLayersGr="listaLayersGrBtn">
                            <span style="vertical-align: middle" class="material-icons">visibility</span>
                        </button>
                        <button title="{{{iconeLegenda}}}" style="width: unset; height: 3rem;" class="<?php if($corpoconfig["legenda"] == false){ echo "hidden ";}?> text-right"
                            onclick="i3GEO.guias.ativa('legenda',this)" data-idconteudo="guia4obj" data-idLegenda="legendaHtml">
                            <span style="vertical-align: middle" class="material-icons">view_list</span>
                        </button>
                        <button style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.abreFecha('fecha');i3GEO.catalogoMenus.mostraCatalogoPrincipal();">
                            <span style="vertical-align: middle" class="material-icons">cancel</span>
                        </button>
                    </div>
                    <div class="separadorCabecalhoGuias">&nbsp;</div>
                    <div class="guiaOverflow" style="height: calc(100% - 45px)">
                        <div id="catalogoMigalha" style="display: block;" data-template="templates/catalogoMigalha.html"></div>
                        <!-- aqui entra a lista de elementos quando uma das opcoes e clicada -->
                        <div id="catalogoNavegacao"></div>
                        <!-- Opcoes -->
                        <div id="catalogoPrincipal">
                            <hr>
                            <div id="catalogoMenus" data-templateDir="templates/dir.html" data-templateTema="templates/tema.html"></div>
                            <div id="arvoreAdicionaTema"></div>
                            <!-- servicos da INDE brasileira -->
                            <div class="list-group condensed">
                                <div class="row-content text-left" style="opacity: 0.8;">
                                    <label style="width: 300px; vertical-align: middle;">
                                        <a onclick="i3GEO.catalogoInde.inicia()" role="button" href="javascript:void(0)">
                                            <h4>
                                                <i class="material-icons" style="vertical-align: text-bottom;">cloud_queue</i> INDE-Br
                                            </h4>
                                        </a>
                                        <h6>Infraestrutura Nacional de Dados Espaciais do Brasil</h6>
                                    </label>
                                    <a onclick="i3GEO.catalogoInde.inicia()" role="button" class="" href="javascript:void(0)">
                                        <i style="margin-bottom: 10px; margin-top: 10px;" class="pull-right material-icons">navigate_next</i>
                                    </a>
                                </div>
                            </div>
                            <hr>
                            <!-- lista de wms cadastrados no sistema de administracao -->
                            <div class="list-group condensed">
                                <div class="row-content text-left" style="opacity: 0.8;">
                                    <label style="width: 300px; vertical-align: middle;">
                                        <a onclick="i3GEO.catalogoOgc.inicia()" role="button" href="javascript:void(0)">
                                            <h4>
                                                <i class="material-icons" style="vertical-align: text-bottom;">cloud_queue</i> Infraestrutura de Dados Espaciais - IDE/DF
                                            </h4>
                                        </a>
                                        <h6>Geoportal do DF e outros serviços externos de acesso a dados geogr&aacute;ficos</h6>
                                    </label>
                                    <a onclick="i3GEO.catalogoOgc.inicia();return false;" role="button" class="" href="javascript:void(0)">
                                        <i style="margin-bottom: 10px; margin-top: 10px;" class="pull-right material-icons">navigate_next</i>
                                    </a>
                                </div>
                            </div>
                            <hr>
                            <!-- lista de wms cadastrados no sistema de administracao -->
                            <div class="list-group condensed">
                                <div class="row-content text-left" style="opacity: 0.8;">
                                    <label style="width: 300px; vertical-align: middle;">
                                        <a onclick="i3GEO.catalogoOgc.inicia()" role="button" href="javascript:void(0)">
                                            <h4>
                                                <i class="material-icons" style="vertical-align: text-bottom;">cloud_queue</i> Webservices
                                            </h4>
                                        </a>
                                        <h6>{{{descws}}}</h6>
                                    </label>
                                    <a onclick="i3GEO.catalogoOgc.inicia();return false;" role="button" class="" href="javascript:void(0)">
                                        <i style="margin-bottom: 10px; margin-top: 10px;" class="pull-right material-icons">navigate_next</i>
                                    </a>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <?php if($corpoconfig["legenda"] == true){ include ($configInc["inc"] . "/guialegenda.php");}?>
            </div>
        </div>
    </div>
    <div class="<?php if($corpoconfig["legenda"] == true){ echo "hidden ";}?>panel panel-default hidden-xs" id="legendaHtmlSESContainer">
        <div class="panel-heading">
            <h3 data-target="#legendaHtmlSES" data-toggle="collapse" class="panel-title collapsed">Legenda</h3>
        </div>
        <div class="panel-body">
            <div id="legendaHtmlSES" class="collapse"></div>
        </div>
    </div>
    <!--  template do balao de identificacao. Alterado para incluir o link que abre a busca por endereco -->
    <script id="i3GEOinfotooltip" type="x-tmpl-mustache">
<div style='min-width: {{minWidth}};' class='ol-popup'>
    <div class='i3GEOCabecalhoInfoWindow hiddenInfo'>
        <div class='dropdown' style='display: inline; vertical-align: top' >
            <button class='btn btn-default btn-xs dropdown-toggle' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'
                style='padding: 2px; margin: 0px 3px 0px 0px;'>
                <span class='material-icons'>playlist_add_check</span>
            </button>
            <ul class='dropdown-menu'>
                <li style='margin-top: 0px;' class='container-fluid form-group'>
                    <label class='control-label' style='color:gray;'> {{{tolerancia}}}</label>
                        <form onchange='i3GEO.configura.ferramentas.identifica.resolution = this[0].value*1;i3GEO.mapa.dialogo.verificaTipDefault({{x}},{{y}});'>
                            <input class='form-control' style='background: none;color: black;' oninput='this.form[1].value=this.value;' type='text' value='{{resolution}}' >
                            <input style='background: none;' oninput=';this.form[0].value=this.value;'  type='range' min='1' max='50' value='{{resolution}}' class='slider'>
                        </form>
                </li>
            </ul>
        </div>
        <button data-info='lockopen' style='padding: 2px; margin: 0px 3px 0px 0px;' class='{{lock_open}} btn btn-default btn-xs'>
            <span style='vertical-align: middle; padding: 0px;' class='material-icons'>lock_open</span>
        </button>
        <button data-info='lock' style='padding: 2px; margin: 0px 3px 0px 0px;' class='{{lock}} btn btn-default btn-xs'>
            <span style='vertical-align: middle; padding: 0px;' class='material-icons'>lock</span>
        </button>
        <button data-info='info' style='padding: 2px; margin: 0px 3px 0px 0px;' class='btn btn-default btn-xs'>
            <span style='vertical-align: middle; padding: 0px;' class='material-icons'>info</span>
        </button>
        <button data-info='wkt' style='padding: 2px; margin: 0px 3px 0px 0px;' class='{{wkt}} btn btn-default btn-xs' title='Fecha sem remover o desenho'>
            <span style='color: red; vertical-align: middle; padding: 0px;' class='material-icons'>highlight_off</span>
        </button>
        <button data-info='copy' style='padding: 2px; margin: 0px 3px 0px 0px;' class='btn btn-default btn-xs'>
            <span style='font-size:2rem;' class='glyphicon glyphicon-copy' aria-hidden='true'></span>
        </button>
    </div>
    <div class='tooltip-conteudo'>
        {{{texto}}}
    </div>
<!--
    <a href='javascript:void(0);' onclick='SESbuscaEndereco("{{{x}}},{{{y}}}",this);return false;' ><small style='color:gray;cursor:pointer;'>Busca endere&ccedil;o</small></a>
    <br> -->
    <small style='color:gray;'><span class='copyToMemory' onclick='i3GEO.util.copyToClipboard("{{{x}}},{{{y}}}");return false;'>{{{xtxt}}},{{{ytxt}}}</span></small>
        <button data-info='close' style='padding: 2px; margin: 0px 3px 0px 0px; right: 0px; position: absolute;' class='btn btn-default btn-xs'>
            <span style='color:gray;vertical-align: middle; padding: 0px;' class='material-icons'>highlight_off</span>
        </button>
</div>
</script>