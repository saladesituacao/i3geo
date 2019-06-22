function fichaunidade(x,y,layer,cnes){
    var t1 = "codigo/fichaunidade.php";
    $.get(
	    t1,
	    {"codigo":cnes}
    )
    .done(
	    function(j, status){
		var data = j.list[0];
		var html = [];
		var meta = j.meta;

		html.push('<div class="container-fluid">');
		$.each(meta,function(i,v){
		    //console.log(data[i])
		    if(data[i] && data[i] != "null"){
			html.push('<div class="row">');
			html.push("<div class='col-md-5 text-left'><strong>" + v.name + "</strong></div><div class='col-md-7 text-left' style='margin-left:10px'> " + data[i] + "</div>");
			html.push('</div>');

		    }

		});
		if(data.foto){
			html.push("<img style='width:100%' src='fotosunidades/" + data.foto + "'>");
		    }
		html.push('</div>');
		i3GEO.janela.closeMsg(html.join(""));
	    }
    )
    .fail(
	    function(data){

	    }
    );
}
