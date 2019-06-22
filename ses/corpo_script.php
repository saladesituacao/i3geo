/**
 * Esse programa e incluido em cada interface, por exemplo, osm.phtml
 */

//carrega o template customizado para o balao de informacoes
//i3GEOinfotooltip fica em corpo.php
i3GEO.template.infotooltip = $i("i3GEOinfotooltip").innerHTML;


//ativa o banner de inicializacao
//i3GEO.janela.tempoMsg(
//$i("i3GEOlogoMarcaTemplate").innerHTML, 4000);

//parametros aplicados na criacao do arquivo mapfile
var parametrosMapa = {
	//arquivo mapfile que servira de base para a criacao do mapa. Por default, sao utilizados os arquivos existentes em i3geo/aplicmap (geral1windows, geral1,...)
	//Essa variavel pode ser definida em ms_configura tambem. Se nao estiver definida em nenhum lugar, o i3Geo tentara descobrir o arquivo adequado a ser utilizado. Voce pode utilizar essa opcao para abrir um mapa com as camadas que voce quiser, mas para evitar redundâncias, prefira o uso de &temasa
	mapfilebase : "",
	//extensao geografica do mapa
	//deve ser definida em coordenadas no formato decimos de grau e na projecao geografica na sequencia xmin ymin xmax ymax
	//exemplo [-77,-26,-30,6]
	mapext : [-48.2851,-16,-47.30,-15.5],
	//perfil utilizado para restringir os menus de temas mostrando apenas os que correspondem a determinado perfil
	perfil : "",
	//layers que serao adicionados ao mapa.
	//Cada layer corresponde ao nome do mapfile existente na pasta i3geo/temas sem a extensao '.map'
	layers : {
	    //array com a lista dos layers que serao adicionados e ligados (visiveis)
	    add : ["unidades","ridedf2016","regioesdesaude","regiaodesaudelimites","regioesdesaudenomes"],
	    //array com a lista dos layers que serao adicionados mas nao ligados
	    on : [],
	    //array com os layers desligados
	    off : ["unidades","ridedf2016","regioesdesaude","regiaodesaudelimites","regioesdesaudenomes"]
	},
	//lista de coordenadas x e y que serao adicionadas como pontos no mapa
	points : {
	    //array com a lista de coordenadas
	    coord : [],
	    //titulo da nova camada
	    title : ""
	},
	//lista de coordenadas x e y que serao adicionadas como linhas no mapa
	lines : {
	    //array de arrays com a lista de coordenadas de cada linha
	    //exemplo [[-54,-12,-50,-12],[-50,-1,-50,-2,-50,-3]]
	    coord : [ [] ],
	    //titulo da nova camada
	    title : ""
	},
	//lista de coordenadas x e y que serao adicionadas como poligonos no mapa
	polygons : {
	    //array de arrays com a lista de coordenadas de cada poligono. A primeira coordenada deve ser igual a ultima.
	    //exemplo [[-50,-1,-48,-2,-50,-3,-50,-1]]
	    coord : [ [] ],
	    //titulo da nova camada
	    title : ""
	},
	//insere elementos no mapa com coordenadas definidas em wkt
	wkt : {
	    //string no formato wkt
	    coord : "",
	    //titulo da nova camada
	    title : ""
	},
	//simbolo que sera utilizado para desenhar os elementos inseridos
	symbol : {
	    //codigo do simbolo conforme definido em i3geo/symbols
	    name : "",
	    //em rgb, exemplo "0 255 0"
	    color : "",
	    //em pixels
	    size : ""
	},
	//arquivo KML que sera incluido no mapa. Valido apenas na interface google maps
	kml : {
	    url : ""
	},
	//endereco de um WMS (sera incluido como uma camada no mapa)
	wms : {
	    url : "",
	    layer : "",
	    style : "",
	    title : "",
	    srs : "",
	    imagetype : "",
	    version : ""
	},
	//filtros que serao aplicados aos layers. Utilize a expressaso conforme definido na documentacao
	//do mapserver, exemplo
	//{layer: "_lbiomashp",expression: "(('[CD_LEGENDA]'='CAATINGA'))"} ou {layer: "_lbiomashp",expression: "cd_legenda='CAATINGA'"}
	filters : [ {
	    layer : "",
	    expression : ""
	} ],
	//id de um mapa salvo e que sera recuperado
	restoreMapId : ""
};
var config = {
	//id do elemento HTML onde o corpo do mapa sera renderizado
	mapBody : "mapai3Geo",
	layerProgressBar: false,
	//tipo de mapa. Pode ser:
	//OL - utiliza o OpenLayers e coordenadas geograficas
	//OSM - utiliza o OpenLayers e o OpenStreetMap como fundo, em projecao semelhante ao GoogleMaps
	//GM - utiliza o GoogleMaps como motor de controle do mapa
	mapType : "<?php echo $corpoconfig['tipoInterface']; ?>",
	//armazena em um cookie a ultima extensao geografica do mapa e utiliza essa extensao quando o mapa for aberto
	saveExtension : false,
	//aplica um filtro de cores apos a renderizacao da imagem de cada camada que compoe o mapa cinza|sepiaclara|sepianormal
	posRenderType : "",
	//Endereco do servidor i3Geo. Utilizado para gerar as requisicoes AJAX
	//Por default e definido como: i3GEO.util.protocolo() + "://" + window.location.host + "/i3geo"
	//Para facilitar as coisas, i3GeoUrl e definida em interface/config.php
	i3GeoServer : i3GeoUrl,
	//opacidade default para camadas que nao sejam do tipo linha ou ponto
	//a opacidade sera aplicada ao objeto HTML e nao ao LAYER original
	//se for vazio, sera utilizado o valor definido no LAYER original
	//Nao se aplica na interface googlemaps
	layerOpacity : "",
	//parametros de configuracao de diferentes componentes do mapa, como o catalogo de temas, balao de info, etc
	components : {
	    //define se ao clicar no mapa sera feita a busca de atributos nas camadas existentes no mapa
	    info : true,
	    //lista com os codigos dos menus que serao considerados na montagem do catalogo de temas
	    idsMenus : [],
	    //webservice utilizado na opcao de encontrar lugares
	    searchService : "http://mapas.mma.gov.br/webservices/geonames.php",
	    //webservice wms que faz a apresentacao do lugar encontrado por searchService
	    searchWms : "http://mapas.mma.gov.br/webservices/geonameswms.php",
	    //posicao do mapa de referencia, em pixels [top,right]
	    referenceMapPosition : [ 50, 120 ],
	    referenceType : "api",
	    //propriedades do balao de informacoes mostrado quando o usuario clica no mapa
	    tooltip : {
		//o resultado sera mostrado em uma janela do tipo modal
		modal : false,
		//url que sera utilizada para obter os dados via $.get. Deve estar no mesmo dominio do i3Geo.
		//Ao final da url serao inseridos os parametros &xx=&yy= com valores em decimos de grau
		//use apenas se modal for true
		//exemplo: http://i3geo.saude.gov.br/i3geo/sage_tabelas/odm/odm6.php?
		url : "",
		//template que sera usado para compor o resultado da busca de dados
		//se for vazio, serao utilizadas as outras opcoes
		//se contiver a string {{{url}}} a mesma sera substituida por url
		//exemplo: "<iframe style='width:400px;height:190px;border:0px white solid' src='{{{url}}}'></iframe>"
		templateModal : "",
		//serao mostrados todos os dados ou apenas aqueles definidos na configuracao da camada
		simple : true,
		removeAoAdicionar : true,
		//parametros exclusivos da interface openlayers
		autoPan : true,
		autoPanAnimation : {
		    duration : 250
		},
		minWidth : '200px',
		//Altura e largura do tooltip (balao)
		toolTipSize : [ "100px", "200px" ],
		//mostra ou nao o balao caso seja vazio
		openTipNoData : true
	    },
	    //barra de rolagem - ver plugin jquery https://github.com/malihu/malihu-custom-scrollbar-plugin
	    scrollBar : {
		theme : "minimal-dark",
		axis : "yx",
		scrollbarPosition : "inside",
		scrollButtons : {
		    enable : true
		},
		advanced : {
		    autoExpandHorizontalScroll : true
		}
	    }
	},
	//parametros de configuracao das ferramentas que sao acionadas sob demanda
	//ferramentas localizadas em i3geo/ferramentas
	tools : {
	    //ferramenta de busca de camadas em um servico CSW
	    buscainde : {
		//endereco do servico no padrao CSW
		csw : "http://www.metadados.inde.gov.br/geonetwork/srv/br"
	    },
	    //ferramenta de identificacao
	    identifica : {
		//resolucao em pixels para busca de elementos
		resolution : 20
	    }
	},
	//configuracoes especificas para a interface que utiliza o OpenLayers
	openLayers : {
	    //utiliza ou nao tiles ao renderizar as camadas do mapa
	    //a utilizacao de tiles pode ser definida em cada camada, mas se essa propriedade for true, a definicao das camadas nao serao consideradas
	    singleTile : false,
	    //opcoes de inicializacao do mapa conforme definido na API do OpenLayers
	    MapOptions : {
		layers : [],
		controls : [
		    //new ol.control.Zoom(),
		    //new ol.control.ZoomSlider(),
		    new ol.control.ScaleLine(),
		    new ol.control.Attribution({
			collapsible : true
		    }) ],
		    loadTilesWhileAnimating : true,
		    loadTilesWhileInteracting : true,
		    //os objetos devem ser comentados na interface googleMaps
		    interactions : [
			new ol.interaction.DoubleClickZoom(),
			new ol.interaction.KeyboardPan(),
			new ol.interaction.KeyboardZoom(),
			new ol.interaction.MouseWheelZoom(),
			//new ol.interaction.PinchRotate(),
			new ol.interaction.PinchZoom(),
			//new ol.interaction.DragZoom(),
			i3GEO.navega.dragZoom(),
			new ol.interaction.DragPan() ]
	    },
	    //opcoes para o objeto view, que e uma instancia de MapOptions
	    //ver https://openlayers.org/en/latest/apidoc/ol.View.html
	    ViewOptions : {

	    },
	    //botoes que sao mostrados no editor vetorial
	    editorButtons : {
		'imprimir' : false,
		'grid' : false,
		'pan' : false,
		'zoombox' : false,
		'zoomtot' : false,
		'zoomin' : false,
		'zoomout' : false,
		'distancia' : false,
		'area' : false,
		'identifica' : false,
		'linha' : true,
		'ponto' : true,
		'poligono' : true,
		'texto' : true,
		'edita' : true,
		'listag' : true,
		'corta' : true,
		'apaga' : true,
		'procura' : false,
		'selecao' : true,
		'selecaotudo' : true,
		'salva' : true,
		'ajuda' : true,
		'propriedades' : true,
		'fecha' : true,
		'tools' : true,
		'undo' : false,
		'frente' : false,
		'legenda' : false,
		'rodadomouse' : true,
		'novaaba' : false
	    }
	},
	//configuracoes especificas para a interface GoogleMaps
	googleMaps : {
	    //opcoes de inicializacao do mapa conforme definido na API do GoogleMaps
	    MapOptions : {
		//estilo que sera utilizado no mapa
		//pode ser um desses: roadmap, satellite, hybrid, terrain, Red, Countries, Night, Blue, Greyscale, No roads, Mixed, Chilled
		//ver i3GEO.Interface.googleMaps.ESTILOS
		mapTypeId : "roadmap",
		scaleControl : true,
		mapTypeControl : true,
		mapTypeControlOptions : {
		    //position : google.maps.ControlPosition.LEFT_BOTTOM
		},
		zoomControl : true,
		zoomControlOptions : {
		    //style : google.maps.ZoomControlStyle.SMALL,
		    //position : google.maps.ControlPosition.LEFT_CENTER
		},
		streetViewControl : true,
		streetViewControlOptions : {
		    //position : google.maps.ControlPosition.LEFT_CENTER
		}
	    }
	}
};
//scripts para as ferramentas de analise em saude
function open_fluxo(){
    if (!$i("SESfluxo_css")) {
	var css = $("<link>", {
	    "rel" : "stylesheet",
	    "type" :  "text/css",
	    "id" : "SESfluxo_css",
	    "href" : "codigo/dependenciascss.php?includes=fluxo"
	})[0];
	css.onload = function(){
	    i3GEO.util.scriptTag("codigo/dependenciasjs.php?includes=fluxo.index,fluxo.tutorial", "fluxo.initTool()", "SESfluxo_js");
	};
	document.getElementsByTagName("head")[0].appendChild(css);
    } else {
	fluxo.initTool();
    }
};
function open_timelinemap(nomeagravo,listOptions){
    if(!nomeagravo || nomeagravo == undefined){
	nomeagravo = "";
	listOptions = true;
    }
    var temp = function(){
	timelinemap.initTool(nomeagravo,listOptions);
    };
    if (!$i("SEStimelinemap_js")) {
	i3GEO.util.scriptTag("codigo/dependenciasjs.php?includes=timelinemap.index,timelinemap.config,elasticsearch.15.3.1.min,d3.v4.min", temp, "SEStimelinemap_js");
    } else {
	temp();
    }
};
