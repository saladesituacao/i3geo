botoesIni = [
{
	"img":"imagens/openlayers.png",
	"href": location.href.replace("init/index.php"+window.location.search,"") + customDir + "/ol.phtml",
	"titulo":$trad(4,g_traducao_init),
	"subtitulo": $trad("4a",g_traducao_init),
	"fa": "map-o",
	"target": "_blank"
},{
	"img":"imagens/osm.png",
	"href": location.href.replace("init/index.php"+window.location.search,"") + customDir + "/osm.phtml",
	"titulo":$trad(23,g_traducao_init),
	"subtitulo": $trad("23a",g_traducao_init),
	"fa": "map-o",
	"target": "_blank"
},{
	"img":"imagens/window-duplicate.png",
	"href": "../mapas/index.php",
	"titulo":$trad(34,g_traducao_init),
	"subtitulo": $trad("34a",g_traducao_init),
	"fa": "map-o",
	"target": "_self"
},{
	"img":"imagens/ogc_logo.png",
	"href": "../ogc/index.php",
	"titulo":$trad(11,g_traducao_init),
	"subtitulo": $trad("11a",g_traducao_init),
	"fa": "download",
	"target": "_self"
},{
	"img":"imagens/application-vnd-google-earth-kml.png",
	"href": "../kml.php",
	"titulo":$trad(12,g_traducao_init),
	"subtitulo": $trad("12a",g_traducao_init),
	"fa": "download",
	"target": "_self"
},{
	"img":"imagens/insert-link.png",
	"href": "../permlinks/index.php",
	"titulo":$trad(13,g_traducao_init),
	"subtitulo": $trad("13a",g_traducao_init),
	"fa": "map-o",
	"target": "_self"
},{
	"img":"imagens/applications-development-web.png",
	"href": "../admin",
	"titulo":$trad(3,g_traducao_init),
	"subtitulo": $trad("3a",g_traducao_init),
	"fa": "cogs",
	"target": "_self"
},{
	"img":"imagens/applications-development.png",
	"href": "../utilitarios/index.php",
	"titulo":$trad(33,g_traducao_init),
	"subtitulo": $trad("33a",g_traducao_init),
	"fa": "wrench",
	"target": "_self"
},{
	"img":"imagens/apple-touch-icon.png",
	"href":"https://softwarepublico.gov.br/gitlab/groups/i3geo",
	"titulo":$trad(30,g_traducao_init),
	"subtitulo": $trad("30a",g_traducao_init),
	"fa": "book",
	"target": "_self"
}
];
reordenaBotoesPorFavoritos();
//TODO um dia, remover as imagens da pasta init e deixar apenas as da pasta init/imagens
function mostraBotoesBT(men){
	var html = "";
	//menu
	html = Mustache.to_html(
			$("#menuTpl").html(),
			i3GEO.idioma.objetoIdioma(g_traducao_init)
	);
	$("#menuTpl").html(html);
	//
	$("#mensagemLogin").html(men);
	html = Mustache.to_html(
			$("#jumbotron").html(),
			{
				"jumbotron" : $trad(35,g_traducao_init),
				"host" : location.host,
				"href" : location.href
			}
	);
	$("#jumbotron").html(html);
	i3GEO.configura = {"locaplic" : ".."};
	i3GEO.idioma.IDSELETOR = "bandeiras";
	i3GEO.idioma.mostraSeletor();
	html = Mustache.to_html(
			"{{#d}}" + $("#botoesTpl").html() + "{{/d}}",
			{"d":botoesIni,"abrir" : $trad(36,g_traducao_init)}
	);
	$("#botoesTpl").html(html);
	aplicaFavoritos();
}
function findBootstrapDeviceSize() {
	var dsize = ['lg', 'md', 'sm', 'xs'];
	for (var i = dsize.length - 1; i >= 0; i--) {

		// Need to add &nbsp; for Chrome. Works fine in Firefox/Safari/Opera without it.
		// Chrome seem to have an issue with empty div's
		$el = $('<div id="sizeTest" class="hidden-'+dsize[i]+'">&nbsp;</div>');
		$el.appendTo($('body'));

		if ($el.is(':hidden')) {
			$el.remove();
			return dsize[i];
		}
	}
	return 'unknown';
}
//cookies sao armazenados em favoritosInit
function favorita(obj){
	$(obj).find("span").toggleClass("amarelo");
	//
	//modifica os cookies
	//
	var cookies = [];
	$(".amarelo").each(
			function(i,el){
				cookies.push($(el).attr("data-cookie"));
			}
	);
	i3GEO.util.insereCookie("favoritosInit",cookies.join("|"),200);
}
function aplicaFavoritos(){
	var favoritos = i3GEO.util.pegaCookie("favoritosInit");
	if(favoritos){
		favoritos = favoritos.split("|");
		$(favoritos).each(
				function(i,el){
					$('span[data-cookie="'+el+'"]').toggleClass("amarelo");
				}
		);
	}
}
function reordenaBotoesPorFavoritos(){
	var f = [],
	nf = [],
	favoritos = i3GEO.util.pegaCookie("favoritosInit");
	$(botoesIni).each(
		function(i,el){
			el.href = el.href.replace("#topo","");
		}
	);
	if(favoritos){
		favoritos = favoritos.split("|");
		$(botoesIni).each(
				function(i,el){
					if(jQuery.inArray(el.img,favoritos) >= 0){
						f.push(el);
					}
					else{
						nf.push(el);
					}
				}
		);
		botoesIni = jQuery.merge( f, nf );
	}
}


