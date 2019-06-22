var timelinemap = {
	_urlsearch: i3GEO.configura.locaplic+"/ses/codigo/queryapikibana.php?indx=",
	_datasets: {},//ver em config.js
	_typesregion: {
	    "ra": {
		"url": "rest/regioesadm/lista",
		"keycode": "nome"
	    }
	},
	_templateSlide: "",
	_idcontainer: "SEStimelinemapContainer",
	_idtags: "SEStimelinemapTags",
	_dataset: "",
	_snackbar: "",
	_stop: false,
	_last: 0,
	_slider: false,
	_layer: false,
	initTool: function(typeDataset){
	    console.log("initTool " + typeDataset);
	    timelinemap.close();
	    $("body").append(
		    "<div style='display:block;margin-bottom: 0px; width: 100vw; position: absolute; bottom: 0px; background-color: #2a2a2a; opacity: 1; padding: 10px; z-index: 100;' id='" + timelinemap._idcontainer + "'><div style='display:none;' id='" + timelinemap._idtags + "'><h5 style='color:white;'>Escolha um conjunto de dados </h5></div></div>"
	    );
	    if(typeDataset){
		timelinemap._dataset = timelinemap._datasets[typeDataset];
		timelinemap.aguardeStart();
		var t1 = timelinemap._typesregion[timelinemap._dataset.typeregion].url;
		var t2 = "codigo/timelinemap/template_mst.html";
		$.when( $.get(t1),$.get(t2)).done(function(r1,r2) {
		    timelinemap._templateSlide = r2[0];
		    timelinemap.setLayerTyperegion(
			    r1[0].data.categorias
		    );
		    timelinemap.getData();
		}).fail(function() {
		    timelinemap.aguardeFecha();
		    return;
		});
	    } else {
		timelinemap.afterStart();
		timelinemap.showlistDataset();
	    }
	},
	afterStart: function(){
	    timelinemap.listDataset();
	    timelinemap.hiddenlistDataset();
	},
	aguardeStart: function(){
	    if(timelinemap._dataset != ""){
		timelinemap._snackbar = i3GEO.janela.snackBar({content: "<span id='timelinemapaguarde'>Carregando os dados...aguarde</span>", style: "navy", timeout: 0});
	    }
	},
	aguardeAtualiza: function(texto){
	    $("#timelinemapaguarde").html(texto);
	},
	aguardeFecha: function(){
	    $(timelinemap._snackbar).snackbar("hide");
	},
	//timelinemap.animStop("denguenotifsem")
	animStop: function(){
	    timelinemap._stop = true;
	},
	//timelinemap.animStart("denguenotifsem")
	animStart: function(){
	    timelinemap._stop = false;
	    timelinemap.animeAll();
	},
	close: function(){
	    i3geoOL.removeLayer(timelinemap._layer, false);
	    timelinemap._dataset = "";
	    $( "#" + timelinemap._idcontainer ).remove();
	},
	setFillColor: function(cor){
	    timelinemap._dataset.controles.fillcolor = cor;
	    var source = timelinemap._layer.getSource();
	    var features = source.getFeatures();
	    for(const feature of features){
		var style = feature.getStyle();
		if(style){
		    var nstyle = style.clone();
		    nstyle.getImage().getFill().setColor(cor);
		    feature.setStyle(nstyle);
		}
	    }
	    source.changed();
	},
	setIntervalo: function(intervalo){
	    timelinemap._dataset.controles.intervalo = intervalo;
	},
	setMaxsize: function(maxsize){
	    timelinemap._dataset.controles.maxsize = maxsize;
	},
	controlers: function(){
	    console.log("controlers");
	    var idslide = "SEStimelinemapslide";
	    var idcontainer = "SEStimelinemapcontroles";
	    var mustache = {
		    "idslide": "SEStimelinemapslide",
		    "idagravo": "SEStimelinemapidagravo",
		    "nomeagravo": timelinemap._dataset.name,
		    "intervalo": timelinemap._dataset.controles.intervalo,
		    "maxsize": timelinemap._dataset.controles.maxsize,
		    "cor": timelinemap._dataset.controles.fillcolor,
		    "idchart": "chart_" + timelinemap._dataset.name,
		    "idtitle": "SEStimelinemapstitle"
	    };
	    $("#"+timelinemap._idcontainer).append(
		    Mustache.render(timelinemap._templateSlide, mustache)
	    );
	    $("#SEStimelinemapstitle").html("<h4 style='color:white'>" + timelinemap._dataset.title + "</h4>");
	    timelinemap._slider = $i(idslide);
	    noUiSlider.create(timelinemap._slider, {
		connect: "lower",
		start: [ 0 ],
		step: 1,
		tooltips: [true],
		format: {
		    to: function (value) {
			var v = timelinemap._dataset.keys[parseInt(value,10)];
			if(v == undefined){
			    return "";
			}
			if(timelinemap._dataset.typedata == "semana"){
			    v = v.split("_");
			    v = v[0] + "<span style=display:none ><i>" + value + "<i></span><br>M&ecirc;s <strong>" + v[1] + "</strong><br>Semana <strong>" + v[2] + "</strong>";
			}
			return  v;
		    },
		    from: function (value) {
			return value;
		    }
		},
		range: {
		    'min': [  0 ],
		    'max': [ timelinemap._dataset.keys.length - 1 ]
		}
	    });
	    timelinemap._slider.noUiSlider.on('start', function( values, handle ) {
		timelinemap.animStop();
	    });
	    timelinemap._slider.noUiSlider.on('slide', function( values, handle ) {
		var key = values[0].split("<i>")[1]*1;
		var time = "data" + timelinemap._dataset.keys[parseInt(key,10)];
		timelinemap.changeSize(time);
	    });
	    timelinemap._slider.noUiSlider.on('end', function( values, handle ) {
		timelinemap.animStop();
		var key = values[0].split("<i>")[1]*1;
		timelinemap._last = parseInt(key,10);
	    });
	    timelinemap.afterStart();
	},
	hiddenlistDataset: function(){
	    $("#"+timelinemap._idtags).css("display","none");
	},
	showlistDataset:function(){
	    $("#"+timelinemap._idtags).css("display","block");
	},
	togglelistDataset: function(){
	    var el = $i(timelinemap._idtags);
	    if(el.style.display == "block"){
		el.style.display = "none";
	    } else {
		el.style.display = "block";
	    }
	},
	listDataset: function(){
	    console.log("listDataset");
	    var onde = $("#"+timelinemap._idtags);
	    for (const key of Object.keys(timelinemap._datasets)) {
		$('<div class="alert alert-warning" style="background-color:#5f9240;display: inline-flex;padding: 2px;margin: 2px;cursor: pointer;border-radius: 2px;box-shadow: 1px 1px 1px 0px rgba(225, 203, 203, 0.33);">' + timelinemap._datasets[key].tag + '</div>')
		.appendTo(onde).on("click",function(){
		    timelinemap.initTool(key);
		});
	    }
	},
	setLayerTyperegion: function(data){
	    timelinemap.aguardeAtualiza("Criando camada...");
	    var features = [];
	    var typeregion = timelinemap._typesregion[timelinemap._dataset.typeregion];
	    for(const d of data){
		var feature = new ol.Feature({
		    geometry: i3GEO.util.extGeo2OSM(new ol.geom.Point(d.ext))
		});
		feature.setProperties({
		    "fat": {"Regi&atilde;o": {valor: d.desc}},
		    "region": d[typeregion.keycode]
		});
		feature.setId(d.nome);
		features.push(feature);
	    }
	    var layer = new ol.layer.Vector({
		source: new ol.source.Vector({features: features}),
		opacity: 0.5,
		title: timelinemap._dataset.title,
		name : timelinemap._dataset.title,
		isBaseLayer : false,
		visible : true,
		style: timelinemap._dataset.style
	    });
	    i3geoOL.addLayer(layer);
	    timelinemap._layer = layer;
	},
	getData: function(){
	    timelinemap.aguardeAtualiza("Obtendo as ocorr&ecirc;ncias...");
	    console.log('getData');
	    var short = timelinemap._dataset;
	    var client = new elasticsearch.Client({
		host: timelinemap._urlsearch + short.index+"*",
		apiVersion: '6.2'
		    //log: 'trace'
	    });
	    client.search({
		index: short.index+"*",
		body: short.query
	    }).then(function (response) {
		var d = timelinemap.parsedata[short.typedata](response.aggregations,short);
		console.log('parsedataok');
		timelinemap.aguardeFecha();
		timelinemap.controlers();
		timelinemap.animStart();
	    }, function (error) {});
	},
	parsedata: {
	    semana: function(data){
		//console.log('parsedata');
		//obtem as regioes do layer existente no agravo
		var layer = timelinemap._layer;
		var source = layer.getSource();
		var keys = [];
		var regioes = data.regiao.buckets;
		var maxsize = 0;
		for(const regiao of regioes){
		    //console.log(regiao)
		    var feature = source.getFeatureById(regiao.key);
		    if(feature){
			var valores = {};
			var anos = regiao.ano.buckets;
			for(const ano of anos){
			    var semanas = ano.semana.buckets;
			    for(const semana of semanas){
				valores["data" + ano.key+"_"+semana.key] = semana.qtd.value;
				if(maxsize < semana.qtd.value){
				    maxsize = semana.qtd.value;
				}
				if(keys.indexOf(ano.key+"_"+semana.key) < 0){
				    keys.push(ano.key+"_"+semana.key);
				}
			    }
			}
			feature.set(timelinemap._dataset.name,valores);
			feature.setStyle(timelinemap._dataset.style);
		    } else {
			//console.log("Feature nao encontrada " + regiao.key)
		    }
		}
		timelinemap._dataset.maxsize = maxsize;
		//ordena as chaves
		timelinemap._dataset.keys = keys.sort((function(a, b){
		    var da = a.split("_");
		    var db = b.split("_");
		    if(da[0] != db[0]){
			return da[0]*1 - db[0]*1;
		    } else {
			return da[2]*1 - db[2]*1;
		    }
		}));
	    }
	},
	animeAll: function(){
	    console.log("animeAll");
	    var onde = $i("agravo" + timelinemap._dataset.name);
	    var datas = timelinemap._dataset.keys;
	    var cont = timelinemap._last;
	    var l = datas.length;
	    var exec = function(){
		if(timelinemap._stop == false){
		    setTimeout(function(){
			if(cont > l){
			    cont = 0;
			    timelinemap._slider.noUiSlider.set(0);
			}
			//console.log(cont + " de " + l);
			//onde.innerHTML = (cont + 1) + " de " + l + " - semana " + datas[cont];
			var time = "data"+datas[cont];
			timelinemap.changeSize(time);
			cont++;
			timelinemap._last = cont;
			timelinemap._slider.noUiSlider.set(cont);
			exec();
		    }, timelinemap._dataset.controles.intervalo);
		} else {
		    timelinemap._stop = false;
		}
	    };
	    exec();
	},
	changeSize: function(time){
	    console.log("changeSize");
	    var layer = timelinemap._layer;
	    var source = layer.getSource();
	    var features = source.getFeatures();
	    var delta = function(size){
		if(timelinemap._dataset.controles.maxsize == 0){
		    return size;
		}
		return (timelinemap._dataset.controles.maxsize / timelinemap._dataset.maxsize) * size;
	    };
	    //console.log(time);
	    for(const feature of features){
		var style = feature.getStyle();
		if(style){
		    var nstyle = style.clone();
		    var size = feature.get(timelinemap._dataset.name)[time];
		    if(size != undefined){
			nstyle.getImage().setRadius(delta(size));
		    } else {
			nstyle.getImage().setRadius(0);
		    }
		    nstyle.getImage().getFill().setColor(timelinemap._dataset.controles.fillcolor);
		    feature.setStyle(nstyle);
		}
	    }
	    layer.changed();
	},
	barChart: function(){
	    console.log("barChart");
	    var onde = $i("chart_" + timelinemap._dataset.name);

	    if(onde.style.display == "block"){
		onde.style.display = "none";
		return;
	    }
	    onde.style.display = "block";
	    if (onde.getElementsByTagName("canvas").length > 0){
		return;
	    }
	    onde.innerHTML = '<canvas height="100" ></canvas>';
	    var ctx = onde.getElementsByTagName("canvas")[0].getContext('2d');
	    var dados = [];
	    var features = timelinemap._layer.getSource().getFeatures();
	    for(const x of timelinemap._dataset.keys){
		var y = 0;
		for(const feature of features){
		    if(feature && feature.get(timelinemap._dataset.name) != undefined && feature.get(timelinemap._dataset.name)["data"+x]){
			y += feature.get(timelinemap._dataset.name)["data"+x]*1;
		    }
		}
		dados.push({
		    y : y,
		    x : x
		});
	    }
	    //console.log(dados);
	    var grafico = new Chart(ctx, {
		type: 'bar',
		data: {
		    "datasets" : [{
			"data" : dados,
			"backgroundColor": 'white'
		    }]
		},
		options: {
		    responsive: true,
		    maintainAspectRatio: false,
		    barThickness: 10,
		    categoryPercentage: 0.5,
		    barPercentage: 0.5,
		    scales: {
			yAxes: [{
			    ticks: {
				beginAtZero:true,
				fontColor: "rgba(255,255,255,1)",
				display: false,
				tickMarkLength: 0
			    }
			}],
			xAxes: [{
			    ticks: {
				beginAtZero:true,
				fontColor: "rgba(255,255,255,0.8)",
				display: false
			    },
			    type: "category",
			    labels: timelinemap._dataset.keys
			}]
		    },
		    title: {
			display: false
		    },
		    legend: {
			display: false
		    },
		    layout: {
			padding: {
			    left: 0,
			    right: 0,
			    top: 10,
			    bottom: 10
			}
		    },
		    tooltips: {
			enabled: true,
			callbacks: {
			    title: function(tooltipItem, data) {
				var d = tooltipItem[0].xLabel.split("-");
				if(d.length == 3){
				    var title = d[2] + "-" + d[1] + "-" + d[0];
				} else if (d.length == 2){
				    var title = d[1] + "-" + d[0];
				}else if (d.length == 1){
				    var title = d[0];
				}
				return title;
			    }
			}
		    }
		}
	    });
	}
};