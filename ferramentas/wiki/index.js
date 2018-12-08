if(typeof(i3GEOF) === 'undefined'){
    var i3GEOF = {};
}
/*
Classe: i3GEOF.wiki
 */
i3GEOF.wiki = {
	MARCA: false,
	/*
	Variavel: aguarde

	Estilo do objeto DOM com a imagem de aguarde existente no cabe&ccedil;alho da janela.
	 */
	aguarde: "",
	/**
	 * Template no formato mustache. E preenchido na carga do javascript com o programa dependencias.php
	 */
	MUSTACHE : "",
	/**
	 * Susbtitutos para o template
	 */
	mustacheHash : function() {
	    var dicionario = i3GEO.idioma.objetoIdioma(i3GEOF.wiki.dicionario);
	    return dicionario;
	},
	/*
	Function: inicia

	Inicia a ferramenta. &Eacute; chamado por criaJanelaFlutuante

	Parametro:

	iddiv {String} - id do div que receber&aacute; o conteudo HTML da ferramenta
	 */
	inicia: function(iddiv){
	    if(i3GEOF.wiki.MUSTACHE == ""){
		$.get(i3GEO.configura.locaplic + "/ferramentas/wiki/template_mst.html", function(template) {
		    i3GEOF.wiki.MUSTACHE = template;
		    i3GEOF.wiki.inicia(iddiv);
		});
		return;
	    }
	    try{
		$i(iddiv).innerHTML = i3GEOF.wiki.html();
		i3GEOF.wiki.ativaFoco();
		i3GEO.eventos.NAVEGAMAPA.push("i3GEOF.wiki.lista()");
		i3GEOF.wiki.lista();
	    }
	    catch(erro){if(typeof(console) !== 'undefined'){console.error(erro);}}
	},
	/*
	Function: html

	Gera o c&oacute;digo html para apresenta&ccedil;&atilde;o das op&ccedil;&otilde;es da ferramenta

	Retorno:

	String com o c&oacute;digo html
	 */
	html:function() {
	    var ins = Mustache.render(i3GEOF.wiki.MUSTACHE, i3GEOF.wiki.mustacheHash());
	    return ins;
	},
	/*
	Function: iniciaJanelaFlutuante

	Cria a janela flutuante para controle da ferramenta.
	 */
	iniciaJanelaFlutuante: function(){
	    var minimiza,cabecalho,janela,divid,temp,titulo;
	    //funcao que sera executada ao ser clicado no cabe&ccedil;alho da janela
	    if ($i("i3GEOF.wiki")) {
		return;
	    }
	    cabecalho = function(){
		i3GEOF.wiki.ativaFoco();
	    };
	    minimiza = function(){
		i3GEO.janela.minimiza("i3GEOF.wiki",200);
	    };
	    //cria a janela flutuante
	    titulo = "<span class='i3GeoTituloJanelaBsNolink' >Wikip&eacute;dia</span></div>";
	    janela = i3GEO.janela.cria(
		    "450px",
		    "220px",
		    "",
		    "",
		    "",
		    titulo,
		    "i3GEOF.wiki",
		    false,
		    "hd",
		    cabecalho,
		    minimiza,
		    "",
		    true,
		    "",
		    "",
		    "",
		    "",
		    "73"
	    );
	    divid = janela[2].id;
	    i3GEOF.wiki.aguarde = $i("i3GEOF.wiki_imagemCabecalho").style;
	    i3GEOF.wiki.inicia(divid);
	    temp = function(){
		if(i3GEO.Interface.ATUAL !== "googlemaps" && i3GEO.Interface.ATUAL !== "googleearth"){
		    i3GEO.eventos.removeEventos("NAVEGAMAPA",["i3GEOF.wiki.lista()"]);
		}
		if(i3GEO.Interface.ATUAL === "googlemaps"){
		    google.maps.event.removeListener(wikiDragend);
		    google.maps.event.removeListener(wikiZoomend);
		}
		if(i3GEO.Interface.ATUAL === "googleearth"){
		    google.earth.removeEventListener(wikiDragend);
		}
	    };
	    YAHOO.util.Event.addListener(janela[0].close, "click", temp);
	},
	/*
	Function: ativaFoco

	Refaz a interface da ferramenta quando a janela flutuante tem seu foco ativado
	 */
	ativaFoco: function(){
	},
	/*
	Function: lista

	Lista os artigos

	Veja:

	<LISTAARTIGOS>
	 */
	lista: function(){
	    if(i3GEOF.wiki.aguarde.visibility === "visible")
	    {return;}
	    i3GEOF.wiki.aguarde.visibility = "visible";
	    var mostrar,p,cp;
	    mostrar = function(retorno){
		i3GEOF.wiki.aguarde.visibility = "hidden";
		if (retorno.data === 'undefined' ){
		    $i("i3GEOwikiLista").innerHTML = "Erro.";
		    return;
		}
		$i("i3GEOwikiLista").innerHTML = retorno.data+$trad('atualizaNavegacao',i3GEOF.wiki.dicionario);
	    };
	    cp = new cpaint();
	    cp.set_response_type("JSON");
	    if(i3GEO.parametros.mapexten){
		ext = i3GEO.util.extOSM2Geo(i3GEO.parametros.mapexten);
	    }
	    else{
		ext = "-49.1774741355 -16.379556709 -47.2737662565 -14.9806872512";
	    } //apenas para exemplo
	    p = i3GEO.configura.locaplic+"/ferramentas/wiki/funcoes.php?funcao=listaartigos&ret="+ext;
	    cp.call(p,"listaartigos",mostrar);
	},
	escondexy: function(){
	    i3GEO.desenho.removePins("wiki");
	    i3GEOF.wiki.MARCA = false;
	},
	mostraxy: function(x,y){
	    if(i3GEOF.wiki.MARCA === false){
		i3GEOF.wiki.MARCA = i3GEO.desenho.addPin(x*1,y*1,"","",i3GEO.configura.locaplic+'/imagens/google/confluence.png',"wiki");
	    }
	    else{
		i3GEO.desenho.movePin(i3GEOF.wiki.MARCA,x,y);
	    }
	}
};