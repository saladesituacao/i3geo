function abrangenciaubs(x,y,tema,cnes){
    var t1 = "rest/unidadesdesaude/abrangenciaubs/" + cnes + "/wkt";
    $.get(
	    t1
    )
    .done(
	    function(data, status){
		i3GEO.desenho[i3GEO.Interface.ATUAL].criaLayerGrafico();
		var c = i3GEO.desenho.layergrafico.getSource();
		var format = new ol.format.WKT();
		$.each(data.data,function( i, item ) {
		    var f = format.readFeatures(item);
		    f = f[0];
		    f.setProperties({
			origem : "pin"
		    });
		    var g = i3GEO.util.projGeo2OSM(f.getGeometry());
		    f.setGeometry(g);
		    c.addFeature(f);
		    $("[data-info='wkt']").click();
		});
	    }
    )
    .fail(
	    function(data){

	    }
    );
}
