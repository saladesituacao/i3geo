var caixadebusca = {
	start: function(){
	    $.typeahead({
		input: '#buscaUnidade',
		minLength: 3,
		maxItem: 0,
		delay: 800,
		order: "asc",
		hint: true,
		dynamic: false,
		group: {
		    template: "{{group}}"
		},
		maxItemPerGroup: null,
		backdrop: {
		    "background-color": "#fff"
		},
		accent: {
		    from: 'áãàâçéêíóôõú',
		    to: 'aaaaceeiooou'
		},
		cache: false,
		ttl: 86400000, // 1day
		compression: true,
		dropdownFilter: "Tudo",
		emptyTemplate: 'Nenhum resultado para "{{query}}"',
		//template: '{{nome}} <span onclick="downloadplanilha({{id}})" ><i class=material-icons >file_download</i></span>',
		source: {
		    "Unidades": {
			display: "nome",
			ajax: {
			    url: "rest/unidadesdesaude/lista",
			    path: "data.categorias"
			},
			dynamic: false
		    },
		    "Unidades (completo)": {
			display: "nomecompleto",
			ajax: {
			    url: "rest/unidadesdesaude/lista",
			    path: "data.categorias"
			},
			dynamic: false
		    },
		    "Regi&otilde;es": {
			display: "nome",
			ajax: {
			    url: "rest/regioesdesaude/lista",
			    path: "data.categorias"
			},
			dynamic: false
		    },
		    "Endere&ccedil;o": {
			display: "nome",
			ajax: {
			    url: "rest/endereco/" + i3GEO.configura.sid + "/busca/{{query}}",
			    path: "data.categorias"
			},
			dynamic: true
		    }
		},
		callback: {
		    onSubmit: function(){
			$('#buscaUnidade').trigger('input.typeahead');
		    },
		    onCancel: function(){
			i3GEO.desenho.removePins();
		    },
		    onClickAfter: function (node, a, item, event) {
			event.preventDefault;
			i3GEO.desenho.removePins();
			if(item.tipo != "regiaodesaude"){
			    i3GEO.desenho.addPin(
				    item.ext[0],
				    item.ext[1],
				    40,
				    40,
				    i3GEO.configura.locaplic + "/ses/imagens/pin.png"
			    );
			    i3GEO.navega.pan2ponto(
				    item.ext[0],
				    item.ext[1]
			    );
			}
			/*
			if(item.tipo != "endereco"){
			    i3GEO.desenho.addPin(
				    item.ext[0],
				    item.ext[1],
				    40,
				    40,
				    i3GEO.configura.locaplic + "/ses/imagens/pin.png"
			    );
			    i3GEO.navega.pan2ponto(
				    item.ext[0],
				    item.ext[1]
			    );
			}
			*/
			if(item.tipo == "regiaodesaude"){
			    var url = "rest/regioesdesaude/" + item.codigo_reg + "/wkt";
			    $.get(
				    url
			    )
			    .done(
				    function(json, status){
					//var json = jQuery.parseJSON(data);
					//console.info(json.data.wkt)
					i3GEO.desenho[i3GEO.Interface.ATUAL].criaLayerGrafico();
					var g, format, f, idunico,c = i3GEO.desenho.layergrafico.getSource();
					format = new ol.format.WKT();
					f = format.readFeatures(json.data.wkt);
					f = f[0];
					f.setProperties({
					    origem : "pin"
					});
					g = f.getGeometry();
					g = i3GEO.util.projGeo2OSM(g);
					f.setGeometry(g);

					f.setStyle([
					    new ol.style.Style({
						stroke: new ol.style.Stroke({
						    color: 'rgba(' + i3GEO.editor._simbologia.strokeColor + ',' + i3GEO.editor._simbologia.opacidade + ')',
						    width: 7
						})
					    }),
					    new ol.style.Style({
						stroke: new ol.style.Stroke({
						    color: 'rgb(0,0,0)',
						    width: 1
						})
					    })
					]);
					c.addFeature(f);
					//i3GEO.editor.setStyleDefault(f);
				    }
			    )
			    .fail(
				    function(data){

				    }
			    );
			}
		    }
		},
		debug: false
	    });
	}
};
