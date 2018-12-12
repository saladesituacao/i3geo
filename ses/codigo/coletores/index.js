//http://dmapas.saude.df.gov.br/i3geo/ses/coletores.php
var coletores = {
	count: 0,
	_parameters: {
	    layer: false,
	    geojson: "../ogc.php?OUTPUTFORMAT=geojson&bbox=-180,-90,180,90&service=wfs&version=1.1.0&request=getfeature&layers=coletoreslayer&typeName=coletoreslayer",
	    ativoStyle: "",
	    inativoStyle: "",
	    defaultStyle: "",
	    timers: {
		checkStatus: 60500,
		refresh: 601000,
		updateChart: 61000
	    }
	},
	start: function(){
	    i3GEO.janela.snackBar({content: "Quando o status passar de ativo para inativo ser&aacute; lan&ccedil;ado um alerta vermelho aqui.", timeout: 30000});
	    this._parameters.ativoStyle = this.getPointStyle('rgba(0,255,0,0.8)',10);
	    this._parameters.inativoStyle = this.getPointStyle('rgba(255,0,0,0.8)',14);
	    this._parameters.defaultStyle = this.getPointStyle('rgb(120,120,120)');
	    this.createLayer();
	    this.mousemove();
	    this.startRefresh();
	},
	startRefresh: function(){
	    setInterval(function(){
		i3GEO.janela.tempoMsg("Atualizando a lista de coletores");
		coletores._parameters.layer.refresh();
	    },coletores._parameters.timers.refresh);
	},
	createLayer: function(){
	    var vectorSource = new ol.source.Vector({
		format: new ol.format.GeoJSON(),
		url: coletores._parameters.geojson,
		tipoServico : "geojson"
	    });
	    var vectorLayer = new ol.layer.Vector({
		source: vectorSource,
		style: coletores.getPointStyle('rgb(220,220,220)'),
		visible: true,
		name: "coletoreslayer",
		isBaseLayer : false
	    });
	    i3geoOL.addLayer(vectorLayer);
	    //vectorLayer.setMap(i3geoOL);
	    coletores._parameters.layer = vectorLayer;
	    vectorLayer.once("render",function(){
		i3GEO.janela.snackBar({content: "<span id='coletoresData'></span>", style: "navy", timeout: 0});
		coletores.ativaCheckStatus();
		coletores.chart.start();
	    });
	},
	mousemove: function(){
	    i3geoOL.on("pointermove", function(e) {
		var pixel = i3geoOL.getPixelFromCoordinate(e.coordinate);
		i3geoOL.forEachFeatureAtPixel(pixel, function(feature, layer) {
		    var ei = i3GEO.Interface.INFOOVERLAY.getElement();
		    //ei.innerHTML = "";
		    if(layer.get("name") == "coletoreslayer"){
			ei.style.visibility = "visible";
			if(feature.get("status") == "true"){
			    var c = "<i class='dotGreen'></i>";
			} else {
			    var c = "<i class='dotRed'></i>";
			}
			c += feature.get("cod_coletor")
			+ " - " + feature.get("cnes_desc") + " - " + feature.get("local");
			ei.innerHTML += "<span style='display:block;'>" + c + "<span>";
			i3GEO.Interface.INFOOVERLAY.setPosition(e.coordinate);
			i3GEO.eventos.mouseOverData();
		    } else {
			//ei.style.visibility = "hidden";
			//i3GEO.Interface.INFOOVERLAY.setPosition(undefined);
			//i3GEO.eventos.mouseOutData();
		    }
		});
	    });
	},
	getPointStyle: function(cor,s){
	    if(!s){
		s = 8;
	    }
	    var simbolo = new ol.style.RegularShape({
		radius: s,
		points: 6,
		fill: new ol.style.Fill({
		    color: cor
		}),
		stroke: new ol.style.Stroke({
		    color: 'rgb(255,255,255)',
		    width: 1
		})
	    });
	    return new ol.style.Style({
		image: simbolo
	    });
	},
	ativaCheckStatus: function(){
	    i3GEO.Interface.aplicaOpacidade(0.5,"osm");
	    this.checkStatus();
	    setInterval(this.checkStatus,coletores._parameters.timers.checkStatus);
	},
	checkStatus: function(){
	    var tempo = new Date();

	    $("#snackbar-container").find("#coletoresData").html("Atualizado em: " + tempo.getHours() + "h - " + tempo.getMinutes() + "m - " + tempo.getSeconds() + "s");
	    if(coletores.count > 0){
		return;
	    }
	    setTimeout(function(){
		i3GEO.janela.tempoMsg("Ciclo de atualiza&ccedil;&atilde;o iniciado");
	    },1000);
	    //var fs = coletores._parameters.layer.getSource().getFeaturesInExtent(i3geoOL.getExtent().toBBOX().split(","));
	    var fs = coletores._parameters.layer.getSource().getFeatures();
	    for(const f of fs){
		coletores.count++;
		$.get({
		    url: "codigo/coletores/verificacoletor.php?id="+f.get("cod_coletor"),
		    beforeSend: function(){
			//f.setStyle(coletores._parameters.defaultStyle);
		    }
		})
		.done(
			function(json, status){
			    coletores.count--;
			    //console.log(f.get("cod_coletor") + " = " + json.data);
			    if(json.data == true){
				f.setStyle(coletores._parameters.ativoStyle);
			    } else if (json.data == false){
				f.setStyle(coletores._parameters.inativoStyle);
			    }
			    //verifica se estava funcionando e parou
			    if(f.get("status") == "true" && json.data == false){
				var content = f.get("cod_coletor")
				+ " - " + f.get("cnes_desc") + " - " + f.get("local");
				i3GEO.janela.snackBar({content: content, style: "red", timeout: 10000});
			    }
			    if(json.data == false){
				f.set("status","false");
			    } else {
				f.set("status","true");
			    }

			}
		)
		.fail(
			function(data){
			    coletores.count--;
			    f.setStyle(coletores._parameters.defaultStyle);
			}
		);
	    };
	},
	getCount: function(){
	    var fs = coletores._parameters.layer.getSource().getFeatures();
	    var red = 0;
	    var green = 0;
	    for(const f of fs){
		if(f.get("status") == "false"){
		    red++;
		} else {
		    green++;
		}
	    }
	    return [green,red];
	},
	chart: {
	    cht : "",
	    start: function(){
		coletores.chart.makeChart();
		coletores.chart.addData([0,0]);
		setInterval(function(){
		    coletores.chart.addData();
		},coletores._parameters.timers.updateChart);
	    },
	    addData: function(conta){
		//i3GEO.janela.tempoMsg("Atualizando o gr&aacute;fico");
		//console.log("add data to chart");
		var d = new Date();
		if(!conta){
		    conta = coletores.getCount();
		}
		if(coletores.chart.cht.data.datasets[0].data.length > 15){
		    coletores.chart.cht.data.datasets[0].data.shift();
		    coletores.chart.cht.data.datasets[1].data.shift();
		}
		coletores.chart.cht.data.datasets[0].data.push({
		    y: conta[0],
		    x: d
		});
		coletores.chart.cht.data.datasets[1].data.push({
		    y: conta[1],
		    x: d
		});
		coletores.chart.cht.data.labels.push(d);
		coletores.chart.cht.update();
	    },
	    makeChart: function(){
		//var canvas = document.createElement("canvas");
		//$(".chartColetores").addClass("chartColetores");
		//$i("openlayers").appendChild(canvas);
		var ctx = document.getElementById("chartColetores").getContext('2d');
		coletores.chart.cht = new Chart(ctx, {
		    type: 'line',
		    data: {
			datasets: [{
			    borderColor: 'rgb(0,255,0)',
			    data: []
			},
			{
			    borderColor: 'rgb(255,0,0)',
			    data: []
			}]
		    },
		    options: {
			scales: {
			    yAxes: [{
				ticks: {
				    beginAtZero:true,
				    fontColor: "rgb(230,230,230)"
				}
			    }],
			    xAxes: [{
				ticks: {
				    beginAtZero:true,
				    maxRotation: 0,
				    fontColor: "rgb(230,230,230)",
				    maxTicksLimit: 2,
				    display: true
				},
				type: "time",
				time: {
				    min: new Date(),
				    //max: new Date(),
				    //unit: "hour",
				    distribution: 'linear'
				}
			    }]
			},
			title: {
			    display: false
			},
			legend: {
			    display: false
			},
			tooltips: {
			    callbacks: {
				title: function(){
				    return "";
				}
			    }
			}
		    }
		});
	    }
	},
	table: {
	    show: function(){
		if(jQuery.isEmptyObject(destaques._parameters.filtroAtual) == true){
		    i3GEO.janela.snackBar({content: "Escolha um TAG pelo menos",style: "red"});
		    return;
		}
		var t = destaques.table;
		var data = t.getData();
		var table = t.getHtml(data.total,"BootstrapTableDestaques");

		i3GEO.janela.formModal({
		    texto: table,
		    footer: false,
		    resizable: {
			disabled: false,
			ghost: true,
			handles: "se,n"
		    },
		    css: {'cursor': 'pointer', 'width':'', 'height': '70%','position': 'fixed','top': 0, 'left': 0, 'right': 0, bottom: 'unset', 'margin': 'auto'}
		});
		$('#BootstrapTableDestaques').bootstrapTable({
		    data: data.data
		});
	    },
	    getData: function(){
		var dados = [], total = 0;
		destaques._parameters.layer.getSource().getFeatures().forEach(function(f) {
		    if(f.get("passfilter") == true){
			dados.push({
			    "tipo": f.get("tipo_unidade"),
			    "nome": f.get("nomecompleto"),
			    "cnes": f.get("cod_cnes")
			});
			total = total + 1;
		    }
		});
		return {"data":dados,"total": total};
	    },
	    getHtml: function(total,id){
		var corpotable = [];
		corpotable.push("<h5 class='text-left'>Total: " + total + "</h5>");
		corpotable.push('<table style="height:80%;" data-show-export="true" data-locale="pt-BR" data-toggle="table" data-show-refresh="false" data-search="true" data-striped="false" data-show-toggle="true" class="bootstrapTable" id="' + id + '" ><thead><tr>');
		corpotable.push('<th data-align="left" data-sortable="true" data-field="tipo">Tipo</th>');
		corpotable.push('<th data-align="left" data-sortable="true" data-field="nome">Nome</th>');
		corpotable.push('<th data-align="center" data-sortable="true" data-field="cnes">Cnes</th>');
		corpotable.push('</thead>');
		corpotable.push('<table>');
		return corpotable.join("");
	    }
	}
};
