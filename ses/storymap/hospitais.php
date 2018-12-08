<?php
// exemplo: http://localhost/i3geo/ferramentas/storymap/default.php?tema=_lreal&layers=_lbiomashp _llocali
if (@$_GET["layers"] != "") {
    $protocolo = explode("/", $_SERVER['SERVER_PROTOCOL']);
    $url = strtolower($protocolo[0]) . "://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'];
    $url .= str_replace("/ferramentas/storymap/default.php", "", $_SERVER["PHP_SELF"]);
    $map_type = $url . "/ogc.php?tema=" . strip_tags($_GET["layers"]) . "&DESLIGACACHE=&Z={z}&X={x}&Y={y}";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Hospitais SES/DF</title>
<meta charset="utf-8">
<meta name="description" content="TimelineJS Embed">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<!-- CSS-->
<link rel="stylesheet" href="https://cdn.knightlab.com/libs/storymapjs/latest/css/storymap.css">
<style>
html, body {
	height: 100%;
	width: 100%;
	padding: 0px;
	margin: 0px;
}

.vco-map-line {
	display: none
}

.vco-icon-iframe:before {
	content: "\e600" !important;
}

.vco-icon-iframe:after {
	content: "H";
	color: #950606 !important;
	font-weight: bold;
	width: 36px !important;
	top: 6px !important;
}

.vco-icon-fichaunidade:after {
    content: "H";
    color: #950606 !important;
    font-weight: bold;
    width: 36px !important;
    top: 6px !important;
}
.vco-media-fichaunidade{
    width:100%;
    height:100%;
}
iframe {
	border: 0px;
}
</style>

</head>
<body>
    <div id="storymap"></div>
    <!-- JavaScript-->
    <script src="StoryMapJS-0.6.9/compiled/js/storymap.js"></script>
    <script>
	//http://localhost/ogc.php?tema=_llocali&DESLIGACACHE=&tms=/_llocali/{z}/{x}/{y}.png
    //https://storymap.knightlab.com/advanced/
    VCO.Language = {
	    name: "Portugu&ecirc;s",
	    lang: "pt",
	    messages: {
	        loading: "carregando",
	        wikipedia: "da Wikipedia, a enciclop&eacute;dia livre",
			start: "Explore"
	    },
	    buttons: {
	        map_overview:      "vista geral do mapa",
			overview:          "vista geral",
	        backtostart:       "voltar ao come&ccedil;o",
	        collapse_toggle:   "ocultar o mapa",
	        uncollapse_toggle: "mostrar o mapa"
	    }
	};

	var storymap = new VCO.StoryMap('storymap', '../../json.php?v=teste&tema=hospitaisstorymap&format=storymap&', {
		"map_type": "osm:standard",
		"show_lines": false
	});
	window.onresize = function(event) {
		storymap.updateDisplay();
	}
</script>
</body>
</html>
