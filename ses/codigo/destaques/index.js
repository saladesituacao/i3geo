var destaques = {
	_parameters: {
	    layer: false,
	    geojson: "../ogc.php?OUTPUTFORMAT=geojson&bbox=-180,-90,180,90&service=wfs&version=1.1.0&request=getfeature&layers=destaquelayer&typeName=destaquelayer",
	    filtros : [{
		"index": 0,
		"title": "Porta aberta",
		"value": ["sim"],
		"name": "porta_aberta"
	    },{
		"index": 1,
		"title": "Porta fechada",
		"value": ["nao"],
		"name": "porta_aberta"
	    },{
		"index": 2,
		"title": "Possui atend. odontol.",
		"value": ["sim"],
		"name": "atend_odont"
	    },{
		"index": 3,
		"title": "N&atilde;o possui atend. odontol.",
		"value": ["nao"],
		"name": "atend_odont"
	    },{
		"index": 4,
		"title": "Tem farm&aacute;cia",
		"value": ["sim"],
		"name": "farmacia"
	    },{
		"index": 5,
		"title": "UBS",
		"value": ["2","3","6"],
		"name": "cod_tipo_unidade"
	    },{
		"index": 6,
		"title": "UPA",
		"value": ["73"],
		"name": "cod_tipo_unidade"
	    },{
		"index": 7,
		"title": "Hospital",
		"value": ["5","7","62"],
		"name": "cod_tipo_unidade"
	    },{
		"index": 8,
		"title": "Reg. Central",
		"value": ["2"],
		"name": "class_regiao"
	    },{
		"index": 9,
		"title": "Reg. Oeste",
		"value": ["5"],
		"name": "class_regiao"
	    },{
		"index": 10,
		"title": "Reg. Norte",
		"value": ["6"],
		"name": "class_regiao"
	    },{
		"index": 11,
		"title": "Reg. Sul",
		"value": ["3"],
		"name": "class_regiao"
	    },{
		"index": 12,
		"title": "Reg. Leste",
		"value": ["7"],
		"name": "class_regiao"
	    },{
		"index": 13,
		"title": "Reg. Sudoeste",
		"value": ["4"],
		"name": "class_regiao"
	    },{
		"index": 14,
		"title": "Reg. Centro-Sul",
		"value": ["1"],
		"name": "class_regiao"
	    }],
	    filtroAtual :{},
	    listDom : ".destaque-menu",
	    containerDom : ".destaque-container-pills",
	    listTemplate: "{{#data}}<li><a onclick='destaques.addFilter(false);destaques.addFilter({{{index}}})' href='javascript:void(0)'><span class='label label-danger badge destaques-pillColor'>{{{title}}}</span></a></li>{{/data}}"
	},
	start: function(){
	    this.makeList();
	    this.createLayer();
	    this.mousemove();
	},
	mousemove: function(){
	    i3geoOL.on("pointermove", function(e) {
		var pixel = i3geoOL.getPixelFromCoordinate(e.coordinate);
		i3geoOL.forEachFeatureAtPixel(pixel, function(feature, layer) {
		    var ei = i3GEO.Interface.INFOOVERLAY.getElement();
		    if(layer && layer.get("name") == "destaques"){
			ei.style.visibility = "visible";
			var c = feature.get("nomecompleto");
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
	makeList : function(){
	    var d = this._parameters;
	    $(d.listDom).html(Mustache.render(d.listTemplate, {data: d.filtros}));
	},
	createLayer: function(){
	    var vectorSource = new ol.source.Vector({
		format: new ol.format.GeoJSON(),
		url: destaques._parameters.geojson
	    });
	    var vectorLayer = new ol.layer.Vector({
		source: vectorSource,
		style: destaques.getPointStyle,
		visible: false,
		name: "destaques",
		isBaseLayer : false
	    });
	    i3geoOL.addLayer(vectorLayer);
	    destaques._parameters.layer = vectorLayer;
	},
	getNumPointsVis: function(){
	    var contador = 0;
	    destaques._parameters.layer.getSource().getFeatures().forEach(function(f) {
		if(f.get("passfilter") == true){
		    contador = contador + 1;
		}
	    });
	    return contador
	},
	getPointStyle: function(feature){
	    if(destaques.testFilter(feature) == true){
		var simbolo = new ol.style.RegularShape({
		    points: 5,
		    radius1: 8,
		    radius2: 4,
		    fill: new ol.style.Fill({
			color: 'rgba(250,153,0,0.8)'
		    }),
		    stroke: new ol.style.Stroke({
			color: 'rgb(0,0,0)',
			width: 1
		    })
		});
		return new ol.style.Style({
		    image: simbolo
		});
	    } else {
		return false;
	    }
	},
	atualizaFiltroAtual: function(){
	    var p = destaques._parameters;
	    var onde = $(p.containerDom);
	    var pills = onde.find("input");
	    var result = {};
	    for(const v of pills){
		if(v.value != "removeall"){
		    var f = p.filtros[v.value];
		    if(result[f.name]){
			result[f.name].push(...f.value);
		    } else {
			result[f.name] = f.value;
		    }
		}
	    }
	    p.filtroAtual = result;
	    destaques._parameters.layer.setVisible(!jQuery.isEmptyObject(result));
	    p.layer.changed();
	},
	addFilter: function(index){
	    var p = destaques._parameters,
	    f = p.filtros[index];
	    if(index === false){
		index = "removeall";
		f = {title: "Remover"};
	    }
	    var onde = $(p.containerDom);
	    if((onde.find("[data-index='" + index + "']").length) == 0){
		var pill = $('<div data-index="' + index + '" class="filterPill alert alert-dismissible alert-danger destaques-pillColor" ><a onclick="destaques.removeFilter(this)" href="javascript:void(0);" role="button" style="margin:auto;text-align:center;font-size:12px;"><input type="hidden" value="' + index + '">' + f.title + ' <i class="glyphicon glyphicon-remove"></i></a></div>');
		pill.appendTo(onde);
	    }
	    if(index != "removeall"){
		destaques.atualizaFiltroAtual();
	    }
	},
	removeFilter: function(obj){
	    if($(obj).parent().data("index") == "removeall"){
		destaques.removeAllFilter(obj);
	    } else {
		$(obj).parent().remove();
		destaques.atualizaFiltroAtual();
	    }
	},
	removeAllFilter: function(obj){
	    $(destaques._parameters.containerDom).html("");
	    destaques.atualizaFiltroAtual();
	},
	testFilter: function(feature){
	    var a = destaques._parameters.filtroAtual;
	    feature.set("passfilter", true);
	    for (var f in a){
		if(a[f].indexOf(feature.get(f)) < 0){
		    feature.set("passfilter", false);
		    return false;
		}
	    }
	    return true;
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
