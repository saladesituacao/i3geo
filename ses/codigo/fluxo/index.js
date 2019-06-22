$.fn.extend({
    treed: function (o) {
        var openedClass = 'glyphicon-minus-sign';
        var closedClass = 'glyphicon-plus-sign';
        if (typeof o != 'undefined'){
            if (typeof o.openedClass != 'undefined'){
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined'){
                closedClass = o.closedClass;
            }
        };
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                e.preventDefault();
            });
        });
    }
});
var fluxo = {
        MUSTACHE : "",
        _tema: "",
        _controle: false,
        _aguarde: "",
        _cor: [255,0,0,0.5],
        _strokeColor : "255,0,0",
        _opacidade : 1,
        _strokeWidth : 1.5,
        _maxStrokeWidth : 15,
        _fillColor: "250,180,15",
        _espessuraNovaLinha: 0,
        _variaCirculoDestino: true,
        _variaCirculoOrigem: true,
        _divid: "",
        _layersByName: {},
        _grupos : {},
        _coresCnes : {
            "0010456": "111,99,90",
            "0010464": "154,196,211",
            "0010472": "131,205,180",
            "0010480": "162,164,29",
            "0010499": "96,126,156",
            "0010502": "117,21,99",
            "0010510": "207,68,227",
            "0010529": "189,32,218",
            "0010537": "105,73,139",
            "0010545": "205,194,166",
            "0010561": "117,117,89",
            "0010596": "160,83,195",
            "0010618": "11,119,211",
            "2645157": "113,222,55",
            "2649497": "60,235,40",
            "2649527": "239,141,71",
            "2650355": "160,215,124",
            "2672197": "121,103,127",
            "2814897": "17,254,13",
            "2815966": "119,125,185",
            "3009939": "100,121,47",
            "3013162": "53,130,134",
            "3018520": "41,13,104",
            "3019608": "3,183,111",
            "3019616": "42,217,66",
            "3030121": "240,26,115",
            "3034704": "96,126,129",
            "3055450": "184,250,113",
            "3276678": "19,71,199",
            "3281418": "218,75,24",
            "3542440": "57,175,15",
            "5717515": "86,235,206",
            "6243495": "98,224,9",
            "6422497": "10,87,139",
            "6730914": "133,108,42",
            "6876617": "89,217,231",
            "red": "255,0,0",
            "green": "0,255,0"
        },
        _tituloOrigem : {
            "df": "CEP",
            "municipio": "Munic&iacute;pio",
            "uf": "UF",
            "cep": "CEP",
            "mun": "Munic&iacute;pio"
        },
        _destinos : {},
        _filterstarted : false,
        //inicia a aplicacao a partir das ferramnatas do i3geo
        //carrega o formulario
        initTool: function(){
            $.get({url:"codigo/fluxo/sih_form.php"}).done(function(r1) {
                $("#i3GEOguiaMovelConteudo").append(r1);
                i3GEO.guias.CONFIGURA.fluxo = {
                        icone : "",
                        titulo : "",
                        id : "guia9",
                        idconteudo : "guia9obj",
                        click : function(obj) {
                        }
                };
                i3GEO.guias.ativa('fluxo',$("[data-idconteudo='guia9obj'"));
                i3GEO.guias.abreFecha("abre","fluxo");
                tutorialFluxo.init();
                tutorialFluxo.start();
                fluxo.startApp();
                setTimeout(function(){if(tutorialFluxo.getCurrentStep() == 0){
                    tutorialFluxo.end();
                }}, 6000);
            }).fail(function() {
                return;
            });
        },
        //utilizado no html para iniciar a interface
        startApp: function(){
            fluxo.log("startApp",(new Error).lineNumber);
            fluxo.abreAguarde();
            fluxo._divid = "fluxoParametrosSES";
            $('#fluxoFiltrosSES').on('show.bs.collapse', fluxo.startFilterOptions);

            var t1 = i3GEO.configura.locaplic + "/ses/rest/sih/listacnes";
            $.get({url:t1,cache:true}).done(function(r1) {
                i3GEO.eventos.cliquePerm.desativa();
                i3geoOL.getViewport().addEventListener("click", fluxo.clickFeature);
                fluxo._destinos = r1;
                $.each(fluxo._destinos,function(index,value){
                    if(!fluxo._coresCnes[value.v]){
                        fluxo._coresCnes[value.v] = i3GEO.util.randomRGB();
                    }
                });
                fluxo._coresCnes["DF"] = "0,0,0";
                fluxo.fechaAguarde();
            }).fail(function() {
                fluxo.fechaAguarde();
                return;
            });
        },
        startFilterOptions: function(){
            fluxo.log("startFilterOptions",(new Error).lineNumber);
            if(fluxo._filterstarted == true){
                return;
            }
            var t1 = i3GEO.configura.locaplic + "/ses/rest/sih/listamunicipios";
            var t2 = i3GEO.configura.locaplic + "/ses/rest/sih/listagrupos";
            fluxo.abreAguarde();
            console.time("cronometroNome");
            $.when($.get({url:t1,cache:true}),$.get({url:t2,cache:true})).done(function(r1,r2) {
                console.timeEnd("cronometroNome");
                fluxo._filterstarted = true;
                var gruposproc = r2[0];
                var municipios = r1[0];
                $("#fluxoComboGruposproc")
                .html(fluxo.opcoesProcedimentos(gruposproc["grupos"]));
                $('#arvoreParametros')
                .html(fluxo.arvoreParametros({
                    "grupos": gruposproc["grupos"],
                    "subgrupos": gruposproc["subgrupos"],
                    "formas": gruposproc["formas"],
                    "procedimentos": gruposproc["procedimentos"],
                    "destinos": fluxo._destinos,
                    "municipios": municipios
                }))
                .treed({
                    openedClass : 'glyphicon-folder-open',
                    closedClass : 'glyphicon-folder-close'
                });
                fluxo.fechaAguarde();
                $("#headingFiltro").find(".normal-right-spinner").addClass("hidden");
            }).fail(function() {
                fluxo._filterstarted = false;
                fluxo.fechaAguarde();
                $("#headingFiltro").find(".normal-right-spinner").addClass("hidden");
                return;
            });
        },
        fechaAguarde: function(){
            i3GEO.janela.fechaAguarde();
            $("#fluxoParametrosSES").find(".normal-right-spinner").addClass("hidden");
            $("#fluxoParametrosSES").find("button").prop( "disabled", false );
        },
        abreAguarde: function(){
            i3GEO.janela.abreAguarde();
            $("#fluxoParametrosSES").find(".normal-right-spinner").removeClass("hidden");
            $("#fluxoParametrosSES").find("button").prop( "disabled", true );
        },
        log: function(t,linha){
            console.log(t + " line:" + linha);
        },
        //cria a lista de hospitais
        opcoesDestino: function(destino){
            fluxo.log("opcoesDestino",(new Error).lineNumber);
            var n = destino.length,
            i = 0,
            ins = "";
            for (i=0;i<n;i++){
                ins += "<option style='width:320px;white-space:unset;' value='" + destino[i].v + "' title='" + destino[i].n + "' >" + destino[i].n + "</option>";
            }
            return ins;
        },
        arvoreParametros: function({grupos,subgrupos,formas,procedimentos,destinos,municipios}){
            fluxo.log("arvoreParametros",(new Error).lineNumber);
            var typeHead = [];
            var tree = [];

            tree.push("<ul>");

            tree.push("<li><a role='button' >&nbsp;Munic&iacute;pios</a><ul>");
            $.each(municipios,function(i,v){
                var onclk = "fluxo.filtro.addmunicipio(\"" + municipios[i].v + "\",\"" + municipios[i].n + "\")";
                typeHead.push({"url": onclk, "nome": municipios[i].n});
                tree.push("<li><a class='adicionaFiltro' role='button' title='adiciona' onclick='" + onclk + "' >&nbsp;<i class='addfilter glyphicon glyphicon-plus-sign'></i>&nbsp;<i class='addfilterTxt'>" + municipios[i].n + "</i></a></li>");
            });
            tree.push("</ul></li>");

            tree.push("<li><a data-tutorialfluxo='destino' role='button' >&nbsp;Destinos</a><ul>");
            $.each(destinos,function(i,v){
                var onclk = "fluxo.filtro.adddestino(\"" + destinos[i].v + "\",\"" + destinos[i].n + "\")";
                typeHead.push({"url": onclk, "nome": destinos[i].n});
                tree.push("<li><a class='adicionaFiltro' role='button' title='adiciona' onclick='" + onclk + "' >&nbsp;<i class='addfilter glyphicon glyphicon-plus-sign'></i>&nbsp;<i class='addfilterTxt'>" + destinos[i].n + "</i></a></li>");
            });
            tree.push("</ul></li>");

            tree.push("<li><a role='button' >&nbsp;Procedimentos</a><ul>");
            $.each(grupos,function(indiceGrupo,valorGrupo){
                if(valorGrupo.grupo_proc != "00"){
                    var nome = valorGrupo.grupo_proc_desc;
                    var id = valorGrupo.grupo_proc;
                    var onclk = "fluxo.filtro.addprocedimento(\"grupo\",\"" + id + "\",\"" + nome + "\")";
                    tree.push("<li>&nbsp;<i title='adiciona' onclick='" + onclk + "' class='addfilter glyphicon glyphicon-plus-sign'></i>&nbsp;<a class='addfilterTxt' role='button'  >" + nome + "</a>");
                    typeHead.push({"url": onclk, "nome": nome});
                    var listaDesubGrupos = subgrupos[id];
                    tree.push("<ul>");
                    $.each(listaDesubGrupos, function(indiceSubGrupo,valorSubGrupo){
                        var nome = valorSubGrupo.subgrupo_proc_desc;
                        var id = valorSubGrupo.subgrupo_proc;
                        var onclk = "fluxo.filtro.addprocedimento(\"subgrupo\",\"" + id + "\",\"" + nome + "\")";
                        typeHead.push({"url": onclk, "nome": nome});
                        tree.push("<li>&nbsp;<i title='adiciona' onclick='" + onclk + "' class='addfilter glyphicon glyphicon-plus-sign'></i>&nbsp;<a class='addfilterTxt' role='button'  >" + nome + "</a>");
                        var listaDeFormas = formas[id];
                        tree.push("<ul>");
                        $.each(listaDeFormas, function(indiceForma,valorForma){
                            var nome = valorForma.co_forma_desc;
                            var id = valorForma.co_forma;
                            var onclk = "fluxo.filtro.addprocedimento(\"forma\",\"" + id + "\",\"" + nome + "\")";
                            typeHead.push({"url": onclk, "nome": nome});
                            tree.push("<li>&nbsp;<i title='adiciona' onclick='" + onclk + "' class='addfilter glyphicon glyphicon-plus-sign'></i>&nbsp;<a class='addfilterTxt' role='button'  >" + nome + "</a>");
                            var listaDeProcedimentos = procedimentos[id];
                            tree.push("<ul>");
                            $.each(listaDeProcedimentos, function(indiceProc,valorProc){
                                var nome = valorProc.proc_rea_desc;
                                var id = valorProc.proc_rea;
                                var onclk = "fluxo.filtro.addprocedimento(\"procedimento\",\"" + id + "\",\"" + nome + "\")";
                                typeHead.push({"url": onclk, "nome": nome});
                                tree.push("<li><a class='adicionaFiltro' role='button' title='adiciona' onclick='" + onclk + "' >&nbsp;<i class='addfilter glyphicon glyphicon-plus-sign'></i>&nbsp;<i class='addfilterTxt'>" + nome + "</i></a>");
                            });
                            tree.push("</ul>");
                        });
                        tree.push("</ul>");

                    });
                    tree.push("</ul>");
                    tree.push("</li>");
                }
            });
            tree.push("</ul></li></ul>");
            fluxo.filtro.searchInputStart(typeHead);
            return tree.join("");
        },
        filtro: {
            addmunicipio: function(id,nome){
                var onde = $("#fluxoFiltroMunicipiosPills");
                if(($(onde).find("[data-id='" + id + "']").length) == 0){
                    var pill = $('<div data-nome="' + nome + '" data-id="' + id + '" class="closefilter alert alert-dismissible alert-warning" style="background-color:#5f9240"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button>' + nome + '</div>');
                    if($(onde).find("[data-id^='" + id + "']").length > 0){
                        return;
                    }
                    pill.appendTo(onde);
                    i3GEO.janela.snackBar({content: "Filtro adicionado " + nome});
                }
            },
            getMunicipios: function(){
                var onde = $("#fluxoFiltroMunicipiosPills");
                var ids = [];
                var nomes = [];
                $.each($(onde).find("div"),function(i,v){
                    ids.push($(v).attr("data-id"));
                    nomes.push($(v).attr("data-nome"));
                });
                return {"ids": ids, "nomes": nomes};
            },
            adddestino: function(id,nome){
                var onde = $("#fluxoFiltroDestinosPills");
                if(($(onde).find("[data-id='" + id + "']").length) == 0){
                    var pill = $('<div data-nome="' + nome + '" data-id="' + id + '" class="closefilter alert alert-dismissible alert-warning" style="background-color:#5f9240"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button>' + nome + '</div>');
                    if($(onde).find("[data-id^='" + id + "']").length > 0){
                        return;
                    }
                    pill.appendTo(onde);
                    i3GEO.janela.snackBar({content: "Filtro adicionado " + nome});
                }
            },
            getDestinos: function(){
                var onde = $("#fluxoFiltroDestinosPills");
                var ids = [];
                var nomes = [];
                $.each($(onde).find("div"),function(i,v){
                    ids.push($(v).attr("data-id"));
                    nomes.push($(v).attr("data-nome"));
                });
                return {"ids": ids, "nomes": nomes};
            },
            addprocedimento: function(tipo,id,nome){
                var onde = $("#fluxoFiltroProcedimentosPills");
                if(($(onde).find("[data-id='" + id + "']").length) == 0){
                    var pill = $('<div data-nome="' + nome + '" data-id="' + id + '" class="closefilter alert alert-dismissible alert-danger" style="background-color:#b64949"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button>' + nome + '</div>');

                    if($(onde).find("[data-id^='" + id + "']").length > 0){
                        return;
                    }
                    pill.appendTo(onde);
                    //remove raiz de grupos com subgrupos
                    if(tipo == "subgrupo"){
                        var grupo = id.substring(0, 2);
                        $(onde).find("[data-id='" + grupo + "']").remove();
                    }
                    if(tipo == "forma"){
                        var grupo = id.substring(0, 2);
                        $(onde).find("[data-id='" + grupo + "']").remove();
                        var subgrupo = id.substring(0, 4);
                        $(onde).find("[data-id='" + subgrupo + "']").remove();
                    }
                    if(tipo == "procedimento"){
                        var grupo = id.substring(0, 2);
                        $(onde).find("[data-id='" + grupo + "']").remove();
                        var subgrupo = id.substring(0, 4);
                        $(onde).find("[data-id='" + subgrupo + "']").remove();
                        var forma = id.substring(0, 6);
                        $(onde).find("[data-id='" + forma + "']").remove();
                    }
                    i3GEO.janela.snackBar({content: "Filtro adicionado " + nome});
                }
            },
            getProcedimentos: function(){
                var onde = $("#fluxoFiltroProcedimentosPills");
                var ids = [];
                var nomes = [];
                $.each($(onde).find("div"),function(i,v){
                    ids.push($(v).attr("data-id"));
                    nomes.push($(v).attr("data-nome"));
                });
                return {"ids": ids, "nomes": nomes};
            },
            searchInputStart: function(data){
                $.typeahead({
                    input: '#fluxoTypehead',
                    minLength: 1,
                    accent: true,
                    cancelButton: false,
                    maxItem: 10,
                    order: "asc",
                    hint: true,
                    dynamic: false,
                    maxItemPerGroup: null,
                    cache: false,
                    ttl: 86400000, // 1day
                    compression: true,
                    template: '<a role="button" title="adiciona" onclick=\'{{url}}\'>{{nome}}</a>',
                    display: ["nome"],
                    source: {
                        data: data
                    },
                    debug: false,
                    callback: {
                        onClickAfter: function(node, a, item, event){
                            $(node).val("");
                        }
                    }
                });
            }
        },
        opcoesProcedimentos: function(grupos){
            fluxo.log("opcoesProcedimentos",(new Error).lineNumber);
            var n = grupos.length,
            i = 0,
            ins = "";
            for (i=0;i<n;i++){
                ins += "<option style='width:320px;white-space:unset;' value='" + grupos[i].grupo_proc + "' title='" + grupos[i].grupo_proc_desc + "' >" + grupos[i].grupo_proc_desc + "</option>";
            }
            return ins;
        },
        getData: function(){
            fluxo.log("getData",(new Error).lineNumber);
            if(fluxo._controle === true){
                return;
            }
            fluxo._controle = true;
            $("#fluxoParametrosSES").collapse('toggle');
            //verificr antes se é necessario mesmo obter os dados novamente
            var serialize = $("#form-fluxo").serialize();
            var query = $("#form-fluxo").serializeArray();

            var procedimentos = fluxo.filtro.getProcedimentos();
            var destinos = fluxo.filtro.getDestinos();
            var municipios = fluxo.filtro.getMunicipios();
            query.push({"name": "procedimentos","value": procedimentos.ids.join(",")});
            query.push({"name": "destinos","value": destinos.ids.join(",")});
            query.push({"name": "municipios","value": municipios.ids.join(",")});
            fluxo.abreAguarde();
            var nomegrupo = fluxo.getNameGroup();
            var idgrupo = i3GEO.util.uid()+"grupo";

            var tipoorigem = $i("tipoorigem").value;

            fluxo._grupos[idgrupo] = {"nome": nomegrupo, "query" : query, "idslayers" : [], "tipoorigem": tipoorigem};

            var node = fluxo.createOptionsGroup({"id":idgrupo,"nomesProcedimentos":procedimentos.nomes});

            //$.get(
            //        "codigo/fluxo/dadosfluxo.php?g_sid=" + i3GEO.configura.sid + "&",
            //        serialize + "&procedimentos=" + procedimentos.ids + "&destino=" + destinos.ids + "&municipios=" + municipios.ids
            //)
            $.get(
                    "rest/sih/" + i3GEO.configura.sid + "/docsbyfilter",
                    serialize + "&procedimentos=" + procedimentos.ids + "&destino=" + destinos.ids + "&municipios=" + municipios.ids
            )
            .done(
                    function(data, status){
                        fluxo.log("getData done",(new Error).lineNumber);
                        fluxo._controle = false;
                        fluxo.fechaAguarde();
                        if(data.chaves.length == 0){
                            i3GEO.janela.tempoMsg("N&atilde;o foi encontrada nenhuma ocorr&ecirc;ncia");
                        } else {
                            fluxo.afterGetData({"node":node,"tipoorigem":tipoorigem, "idgrupo": idgrupo,"nomegrupo": nomegrupo,"data":data,"query":query,"nomesProcedimentos": procedimentos.nomes});
                        }
                        $(node).find(".loaderLayers").remove();
                    }
            )
            .fail(
                    function(data){
                        fluxo.fechaAguarde();
                        $(node).find(".loaderLayers").remove();
                    }
            );
        },
        afterGetData: function({node,tipoorigem,idgrupo,nomegrupo,data,query,nomesProcedimentos}){
            fluxo.log("afterGetData",(new Error).lineNumber);
            var idlayer,layer;
            var idUnico = i3GEO.util.uid();
            //cria os layers para cada destino
            fluxo.addFluxLayers(idUnico,data,nomegrupo,tipoorigem,nomesProcedimentos,idgrupo);
            //cria as features para cada layer
            fluxo.addFluxLines(idUnico,data);
            //cria um layer com circulos representando o total em cada ponto de destino
            fluxo.addFluxCircleDestinos(idUnico,data);
            //cria um layer com circulos representando o total em cada ponto de origem
            fluxo.addFluxCircleOrigens(idUnico,data);
            //adiciona na interface as camadas
            fluxo.addOptionsGroup(idgrupo,nomesProcedimentos,node);

            if($i("tipoorigem").value == "df"){
                node.find(".panel-body").append("<h5 class='alert alert-info'>Quando &eacute; escolhida a op&ccedil;&atilde;o de origem <i>Distrito Federal</i> o resultado considera apenas os dados que permitem a localiza&ccedil;&atilde;o geogr&aacute;fica da pessoa, o que n&atilde;o corresponde ao total de registros dos procedimentos realizados.</h5>");
            };
            //para o tutorial
            if(tutorialFluxo.ended() == false){
                tutorialFluxo.addSteps(tutorialFluxoContinuacao);
                tutorialFluxo.next();
            }
        },
        addFluxLayers : function(idUnico,data,nomegrupo,tipoorigem,nomesProcedimentos,idgrupo){
            //cria um layer para os circulos no destino que mostram o total
            var idlayer = idUnico+"circledestino";
            layer = fluxo.createLayer(
                    "",
                    "Total por destino",
                    idlayer,
                    "0,255,0",
                    false
            );
            layer.set("nomegrupo",nomegrupo);
            layer.set("tipoorigem",tipoorigem);
            layer.set("cnes_destino","green");
            layer.set("numocorrencias",data.totalocorrencias);
            fluxo._grupos[idgrupo].idslayers.push(idlayer);

            //cria um layer para os circulos na origem que mostram o total
            var idlayer = idUnico+"circleorigem";
            layer = fluxo.createLayer(
                    "",
                    "Total por origem",
                    idlayer,
                    "0,255,0",
                    false
            );
            layer.set("nomegrupo",nomegrupo);
            layer.set("tipoorigem",tipoorigem);
            layer.set("cnes_destino","red");
            layer.set("numocorrencias",data.totalocorrencias);
            fluxo._grupos[idgrupo].idslayers.push(idlayer);

            //cria um layer para cada destino
            jQuery.each(data.destinos, function( index, destino ) {
                var idlayer = idUnico+"a"+destino.codigo;
                layer = fluxo.createLayer(
                        destino.nome,
                        destino.sigla + " - ",
                        idlayer,
                        fluxo._coresCnes[destino.codigo],
                        (destino.codigo != 'DF' ? true:false)
                );
                layer.set("cnes_destino",destino.codigo);
                layer.set("numocorrencias",destino.numocorrencias);
                layer.set("nomegrupo",nomegrupo);
                layer.set("tipoorigem",tipoorigem);
                layer.set("nomesProcedimentos",nomesProcedimentos);
                fluxo._grupos[idgrupo].idslayers.push(idlayer);
                //layer.getSource().changed();
            });

        },
        addFluxLines : function (idUnico,data){
            //fluxo.log("addFluxLines",(new Error).lineNumber);
            jQuery.each(data.fluxos, function( index, flux ) {
                var linha = fluxo.makeFluxLine(flux,data);
                var idlayer = idUnico+"a"+data.destinos[flux.destino].codigo;
                //nomecompleto,title,name,cor,visible
                var layer = fluxo._layersByName[idlayer];
                fluxo.layerAddFluxLine(layer,linha);
                layer.getSource().changed();
            });
        },
        layerAddFluxLine: function(layer,linha){
            //fluxo.log("layerAddFluxLine -> ",(new Error).lineNumber);
            var geometria = i3GEO.util.projGeo2OSM(
                    new ol.geom.LineString(linha.linestring)
            );
            var feature = new ol.Feature({
                geometry: geometria,
                nome_origem: linha.origem,
                tipo_origem: linha.tipo_origem,
                origem: "fluxo", //usado pelo i3geo tambem
                nome_destino: linha.destino,
                codigo_destino: linha.codigo_destino,
                numocorrencias: linha.numocorrencias,
                distanciakm: i3GEO.calculo.distHaversine(linha.linestring[0][0], linha.linestring[0][1], linha.linestring[1][0], linha.linestring[1][1])
            });
            feature.setId(linha.id);
            feature.setStyle(
                    new ol.style.Style({
                        stroke: new ol.style.Stroke({
                            color: 'rgba(' + fluxo._coresCnes[linha.codigo_destino] + ',' + fluxo._opacidade + ')',
                            width: linha.w
                        }),
                        fill: new ol.style.Fill({
                            color: 'rgba(' + fluxo._coresCnes[linha.codigo_destino] + ',' + fluxo._opacidade + ')'
                        })
                    })
            );
            layer.getSource().addFeature(feature);
        },
        addFluxCircleOrigens: function(idUnico,data){
            jQuery.each(data.origens, function( index, origem ) {
                var idlayer = idUnico+"circleorigem";
                var layer = fluxo._layersByName[idlayer];
                fluxo.addFluxCircleOrigem(layer,origem,data);
            });
        },
        addFluxCircleOrigem: function(layer,origem,data){
            var geometria = i3GEO.util.projGeo2OSM(
                    new ol.geom.Circle([origem.long,origem.lat],0.0001)
            );
            var feature = new ol.Feature({
                geometry: geometria,
                origem: "fluxo", //usado pelo i3geo tambem
                nome_destino: "",
                nome_origem: origem.nome,
                tipo_origem: origem.tipo,
                codigo_destino: origem.codigo,
                numocorrencias: origem.numocorrencias
            });
            feature.setStyle(
                    new ol.style.Style({
                        stroke: new ol.style.Stroke({
                            color: 'rgba(255,0,0,0.5)',
                            width: (origem.numocorrencias * 100 / data.totalocorrencias) + 12
                        }),
                        fill: new ol.style.Fill({
                            color: 'rgba(255,0,0,0.5)'
                        })
                    })
            );
            layer.getSource().addFeature(feature);
        },
        addFluxCircleDestinos: function(idUnico,data){
            jQuery.each(data.destinos, function( index, destino ) {
                var idlayer = idUnico+"circledestino";
                var layer = fluxo._layersByName[idlayer];
                fluxo.addFluxCircleDestino(layer,destino,data);
            });
        },
        addFluxCircleDestino: function(layer,destino,data){
            var geometria = i3GEO.util.projGeo2OSM(
                    new ol.geom.Circle([destino.long,destino.lat],0.0001)
            );
            var feature = new ol.Feature({
                geometry: geometria,
                origem: "fluxo", //usado pelo i3geo tambem
                nome_destino: destino.nome,
                nome_origem: "",
                codigo_destino: destino.codigo,
                numocorrencias: destino.numocorrencias
            });
            feature.setStyle(
                    new ol.style.Style({
                        stroke: new ol.style.Stroke({
                            color: 'rgba(0,255,0,0.5)',
                            width: (destino.numocorrencias * 100 / data.totalocorrencias) + 12
                        }),
                        fill: new ol.style.Fill({
                            color: 'rgba(0,255,0,0.5)'
                        })
                    })
            );
            layer.getSource().addFeature(feature);
        },
        makeFluxLine : function(flux,data){
            //fluxo.log("makeFluxLine",(new Error).lineNumber);
            var origem = data.origens[flux.origem];
            var destino = data.destinos[flux.destino];
            //se for necessario usar as UF da RIDE
            var centroideUfride = {
                    "MG": {"x":-46.583,"y":-15.816},
                    "GO": {"x":-48.946,"y":-15.739}
            };
            //modifica as coordenadas da origem quando for aplicado o filtro RIDE
            if($i("apenasride").checked && $i("tipoorigem").value == "uf"){
                var x1 = centroideUfride[origem.sigla].x;
                var y1 = centroideUfride[origem.sigla].y;
            } else {
                var x1 = origem.long;
                var y1 = origem.lat;
            }
            var numocorrencias = data.fluxos[origem.codigo+"-"+destino.codigo].numocorrencias;
            var linha = {
                    "linestring": [[destino.long*1,destino.lat*1],[x1*1,y1*1]],
                    "origem": origem.nome,
                    "tipo_origem": origem.tipo,
                    "destino": destino.nome,
                    "numocorrencias": numocorrencias,
                    "codigo_destino": destino.codigo,
                    "w": 0.5 + ((fluxo._maxStrokeWidth / data.maxocorrencias ) * numocorrencias )
            };
            return linha;
        },
        addOptionsGroup : function(id,nomesProcedimentos,node){
            fluxo.log("addOptionsGroup",(new Error).lineNumber);
            var templateLayer = $("#templateCamadasFluxo").html();
            jQuery.each(fluxo._grupos[id].idslayers, function(index,idlayer){
                var layer = fluxo._layersByName[idlayer];
                if(fluxo._coresCnes[layer.get("cnes_destino")]){
                    var hash = {
                            "checked": (layer.getVisible() ? "checked":""),
                            "titulo": layer.get("title"),
                            "nomecompleto": layer.get("nomecompleto"),
                            "idgrupo": id,
                            "idlayer": idlayer,
                            "numocorrencias": fluxo.show.formataNumero(layer.get("numocorrencias")),
                            "corhex": i3GEO.util.rgb2hex(fluxo._coresCnes[layer.get("cnes_destino")])
                    };
                    //node.find(".panel-body").append( Mustache.render(templateLayer, hash) );
                    node.find(".aranha").append( Mustache.render(templateLayer, hash) );
                }
            });
        },
        createOptionsGroup: function({id,nomesProcedimentos}){
            var template = $("#templateGrupoFluxo").html();
            var html = Mustache.render(template, {id: id,titulo: fluxo._grupos[id].nome, nomesProcedimentos: nomesProcedimentos.join(", ")});
            var node = $( html );
            node.appendTo( "#fluxoSESContainerNovos" );
            return node;
        },
        getNameGroup: function(){
            var nomegrupo = [];
            //titulo do item do acordeon
            nomegrupo.push(...fluxo.filtro.getDestinos().nomes);
            nomegrupo.push(...fluxo.filtro.getMunicipios().nomes);
            nomegrupo.push(...fluxo.filtro.getProcedimentos().nomes);
            jQuery.each( $( "#form-fluxo input" ), function( i, field ) {
                if(field.value != ""){
                    if(field.type == "date"){
                        var valor = field.value.split("-");
                        if(valor[2]){
                            nomegrupo.push(valor[2] + "-" + valor[1] + "-" + valor[0]);
                        } else {
                            nomegrupo.push(valor[1] + "-" + valor[0]);
                        }
                    }
                }
            });
            jQuery.each( $( "#form-fluxo select" ), function( i, field ) {
                if($(field).attr("data-titulo") != undefined){
                    var nomes = [];
                    $(field).find(':selected').each(function(i, sel){
                        var temp = $(sel).val();
                        temp != "" ? nomes.push(temp) : false;
                    });
                    nomes.length > 0 ? nomegrupo.push($(field).attr("data-titulo") + nomes.join(",")) : false;
                }
            });
            nomegrupo = nomegrupo.join(" ");
            //ajusta o nome em funcao dos parametros escolhidos
            if($i("tipoorigem").value == "df" || $i("apenasride").checked == false){
                nomegrupo = nomegrupo.replace("Ride: on"," ");
            }
            nomegrupo = nomegrupo.replace("undefined"," ");
            return nomegrupo;
        },
        deleteGroup: function(idgrupo){
            $.each(fluxo._grupos[idgrupo].idslayers,function(i,idlayer){
                var layer = fluxo._layersByName[idlayer];
                i3geoOL.removeLayer(layer);
                layer.setMap(null);
            });
            fluxo._grupos[idgrupo] = null;
            $("#painelLayers" + idgrupo).remove();
        },
        hideGroup : function(idgrupo){
            var layers = fluxo._grupos[idgrupo].idslayers;
            $.each(layers, function(i,v){
                var layer = fluxo._layersByName[v];
                layer.setVisible(false);
                layer.changed();
            });
        },
        showGroup : function(idgrupo){
            var layers = fluxo._grupos[idgrupo].idslayers;
            $.each(layers, function(i,v){
                var layer = fluxo._layersByName[v];
                if($i(idgrupo + v).checked == true){
                    layer.setVisible(true);
                    layer.changed();
                }
            });
        },
        grupoToggle: function(c,idgrupo){
            if(c.checked){
                fluxo.showGroup(idgrupo);
            } else {
                fluxo.hideGroup(idgrupo);
            }
        },
        hideLayersGroup : function(idgrupo){
            var layers = fluxo._grupos[idgrupo].idslayers;
            $.each(layers, function(i,v){
                var layer = fluxo._layersByName[v];
                layer.setVisible(false);
                layer.changed();
            });
            $("#painelLayers" + idgrupo + " .aranha").find("input").prop("checked",false);
        },
        showLayersGroup : function(idgrupo){
            var layers = fluxo._grupos[idgrupo].idslayers;
            $.each(layers, function(i,v){
                if(fluxo._layersByName[v].getProperties().cnes_destino != "DF"){
                    var layer = fluxo._layersByName[v];
                    layer.setVisible(true);
                    layer.changed();
                }
            });
            $("#painelLayers" + idgrupo).find("input").prop("checked",true);
        },
        corUnicaGroup : function(idgrupo,obj){
            var layers = fluxo._grupos[idgrupo].idslayers;
            var cor = obj.value
            $.each(layers, function(i,v){
                var layer = fluxo._layersByName[v];
                if(layer.getVisible() == true){
                    layer.getSource().getFeaturesCollection().forEach(function(feature){
                        var s = feature.getStyle();
                        s.getStroke().setColor(cor);
                        s.getFill().setColor(cor);
                    });
                    layer.changed();
                    $("#" + idgrupo + v + "Color").css("border-color",cor);
                }
            });
        },
        exportageojson: function(idgrupo){
            window.open("rest/sih/" + i3GEO.configura.sid + "/docsbyfilter?outputformat=geojson&" + jQuery.param(fluxo._grupos[idgrupo].query));
        },
        exportashp : function(idgrupo){
            fluxo.abreAguarde();
            $.get(
                    "codigo/fluxo/dadosfluxo.php?g_sid=" + i3GEO.configura.sid + "&exportashp=",
                    fluxo._grupos[idgrupo].query
            )
            .done(
                    function(data, status){
                        fluxo.fechaAguarde();
                        i3GEO.janela.snackBar({content: "Arquivo gerado"});
                        var url = i3GEO.configura.locaplic + "/ferramentas/download/forcedownload.php?g_sid=" + i3GEO.configura.sid;
                        var link = document.createElement("a");
                        $(link).click(function(e) {
                            e.preventDefault();
                            window.location.href = url;
                        });
                        $(link).click();
                    }
            )
            .fail(
                    function(data){
                        fluxo.fechaAguarde();
                    }
            );
        },
        legendonoff : function(idgrupo,obj){
            var linhas = $("#fluxoAranhas" + idgrupo);
            $.each(linhas.find("li").find("button,div>label>.checkbox-material"), function(i,v){
                if($(v).hasClass("hidden")){
                    $(v).removeClass("hidden");
                } else {
                    $(v).addClass("hidden");
                }
            });
            $.each(linhas.find("li"), function(i,v){
                var layer = $(v).data("layer");
                if(fluxo._layersByName[layer] && fluxo._layersByName[layer].getVisible() == false){
                    if($(v).hasClass("hidden")){
                        $(v).removeClass("hidden");
                    } else {
                        $(v).addClass("hidden");
                    }
                }
            });
        },
        restauraCoresGroup : function(idgrupo){
            var layers = fluxo._grupos[idgrupo].idslayers;
            $.each(layers, function(i,v){
                var layer = fluxo._layersByName[v];
                var cor = $("#" + idgrupo + v + "Color").val();
                layer.getSource().getFeaturesCollection().forEach(function(feature){
                    var s = feature.getStyle();
                    s.getStroke().setColor(cor);
                    s.getFill().setColor(cor);
                });
                layer.changed();
                $("#" + idgrupo + v + "Color").css({"border-color":cor}).val(cor);
            });
        },
        layerToggle: function(c,idgrupo,idlayer){
            var layer = fluxo._layersByName[idlayer];
            layer.setVisible(c.checked);
            if($i("grupoToggle"+idgrupo).checked == true){
                layer.changed();
            }
        },
        setColor: function(obj,idlayer){
            var layer = fluxo._layersByName[idlayer].getSource();
            var cor = 'rgba(' + i3GEO.util.hex2rgb(obj.value) + ',' + fluxo._opacidade + ')';
            obj.style.border = "1px solid " + cor;
            layer.getFeaturesCollection().forEach(function(feature){
                var s = feature.getStyle();
                s.getStroke().setColor(cor);
                s.getFill().setColor(cor);
            });
            layer.changed();
        },
        clickFeature: function(e){
            fluxo.log("clickFeature",(new Error).lineNumber);
            i3geoOL.forEachFeatureAtPixel(i3geoOL.getEventPixel(e), function (feature, layer) {
                if(feature.getProperties() && feature.getProperties().nome_origem){
                    i3GEO.janela.tempoMsg(fluxo._tituloOrigem[feature.getProperties().tipo_origem] + " - " + feature.getProperties().nome_origem + " -> " + feature.getProperties().nome_destino + "<br>" + fluxo.show.formataNumero(feature.getProperties().numocorrencias));
                } else if (feature.getProperties() && feature.getProperties().nome_destino){
                    i3GEO.janela.tempoMsg(feature.getProperties().nome_destino + "<br>" + fluxo.show.formataNumero(feature.getProperties().numocorrencias));
                }
            },{hitTolerance : 5});
        },
        createLayer: function(nomecompleto,title,name,cor,visible){
            fluxo.log("createLayer",(new Error).lineNumber);
            var vectorSource = new ol.source.Vector({
                features : new ol.Collection()
            });
            var vectorLayer = new ol.layer.Vector({
                source: vectorSource,
                style: new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'rgba(' + cor + ',' + fluxo._opacidade + ')',
                        width: fluxo._strokeWidth
                    }),
                    fill: new ol.style.Fill({
                        color: 'rgba(' + cor + ',' + fluxo._opacidade + ')'
                    })
                }),
                visible: visible
            });
            vectorLayer.setMap(i3geoOL);
            vectorLayer.setProperties({"title": title,"name": name,"nomecompleto":nomecompleto});
            fluxo._layersByName[name] = vectorLayer;
            return vectorLayer;
        },
        show: {
            formataNumero: function(value, row, index, field){
                return $.number(value,0,$trad("dec"),$trad("mil"));
            },
            formataNumero0: function(value, row, index, field){
                return $.number(value,2,$trad("dec"),$trad("mil"));
            },
            tableThead: function({id = ""} = {}){
                var table = [];
                table.push('<table data-show-export="true" data-locale="pt-BR" data-toggle="table" data-show-refresh="false" data-search="true" data-striped="false" data-show-toggle="true" class="bootstrapTable" id="bootstrapTable' + id + '" ><thead><tr>');
                table.push('<th data-align="left" data-sortable="true" data-field="nome_origem">Origem</th>');
                table.push('<th data-align="left" data-sortable="true" data-field="nome_destino">Destino</th>');
                table.push('<th data-formatter="fluxo.show.formataNumero" data-searchable="false" data-align="right" data-sortable="true" data-field="numocorrencias">Ocorr&ecirc;ncias</th>');
                table.push('<th data-formatter="fluxo.show.formataNumero0" data-searchable="false" data-align="right" data-sortable="false" data-field="perc">%</th>');
                table.push('</tr></thead><table></div>');
                return table.join("");
            },
            tableGroupByOrigem: function({id = "",layers = "", total = ""} = {}){
                var dadostemp = {}, dados = [];
                jQuery.each(layers, function(index,layer){
                    var fluxos = layer.get("fluxos");
                    $.each(fluxos,function(i,v){
                        if(dadostemp[v.nome_origem]){
                            var d = dadostemp[v.nome_origem];
                            dadostemp[v.nome_origem].numocorrencias = v.numocorrencias + d.numocorrencias;
                        } else {
                            dadostemp[v.nome_origem] = {
                                    perc: "",
                                    nome_origem: v.nome_origem,
                                    nome_destino: "-",
                                    numocorrencias: v.numocorrencias
                            };
                        }
                    });
                });
                $.each(dadostemp, function(i,v){
                    v.perc = (v.numocorrencias * 100) / total
                    dados.push(v);
                });
                $('#' + id).bootstrapTable({
                    data: dados
                });
            },
            tableGroupByDestino: function({id = "",layers = "", total= ""} = {}){
                var dadostemp = {}, dados = [];
                jQuery.each(layers, function(index,layer){
                    var fluxos = layer.get("fluxos");
                    $.each(fluxos,function(i,v){
                        if(dadostemp[v.nome_destino]){
                            var d = dadostemp[v.nome_destino];
                            dadostemp[v.nome_destino].numocorrencias = v.numocorrencias + d.numocorrencias;
                        } else {
                            dadostemp[v.nome_destino] = {
                                    perc: "",
                                    nome_origem: "-",
                                    nome_destino: v.nome_destino,
                                    numocorrencias: v.numocorrencias
                            };
                        }
                    });
                });
                $.each(dadostemp, function(i,v){
                    v.perc = (v.numocorrencias * 100) / total
                    dados.push(v);
                });
                $('#' + id).bootstrapTable({
                    data: dados
                });
            },
            tableGrupo: function(idgrupo){
                fluxo.log("tableGrupo",(new Error).lineNumber);
                var layers = [], layer, total = 0, dados = [], tituloJanela = [];
                //faz a soma dos totais
                jQuery.each(fluxo._grupos[idgrupo].idslayers, function(index,idlayer){
                    layer = fluxo._layersByName[idlayer];
                    if(layer.getVisible() == true){
                        layers.push(layer);
                        total = total + layer.get("numocorrencias")*1;
                        tituloJanela.push(layer.get("title"));
                    }
                });
                //cria as linhas da tabela
                jQuery.each(layers, function(index,layer){
                    var fluxos = layer.get("fluxos");
                    $.each(fluxos,function(i,v){
                        dados.push({
                            perc: (v.numocorrencias * 100) / total,
                            nome_origem: v.nome_origem,
                            nome_destino: v.nome_destino,
                            numocorrencias: v.numocorrencias
                        });
                    });
                });
                var corpotable = ["<div class='container-fluid'><h5>" + layers[0].get("nomegrupo") + "</h5>"];
                var janela = fluxo.show.openWindow(tituloJanela.join(", ")).obj;

                corpotable.push("<h5>" + layers[0].get("nomesProcedimentos") + "</h5>");
                corpotable.push("<h6>Fonte: arquivo RD/SIH/MS</h6>");
                corpotable.push("<h5>Total = " + $.number(total,0,$trad("dec"),$trad("mil")) + "</h5>");
                corpotable.push("<br><h4><b>Listagem completa</b></h4>");
                corpotable.push(fluxo.show.tableThead({
                    id: idgrupo
                }));
                corpotable.push("<br><h4><b>Consolidado por origem</b></h5>");
                corpotable.push(fluxo.show.tableThead({
                    id: "byOrigem" + idgrupo
                }));
                corpotable.push("<br><h4><b>Consolidado por destino</b></h5>");
                corpotable.push(fluxo.show.tableThead({
                    id: "byDestino" + idgrupo
                }));
                janela.setBody(corpotable.join(""));
                //ver o metodo bootstrapTable em http://bootstrap-table.wenzhixin.net.cn/documentation/
                $('#bootstrapTable' + idgrupo).bootstrapTable({
                    data: dados
                });
                fluxo.show.tableGroupByOrigem({total: total, id:"bootstrapTablebyOrigem" + idgrupo,layers:layers});
                fluxo.show.tableGroupByDestino({total: total, id:"bootstrapTablebyDestino" + idgrupo,layers:layers});
                $('.dropdown-toggle').dropdown();
            },
            tableLayer: function(idlayer){
                fluxo.log("tableLayer",(new Error).lineNumber);
                var layer = fluxo._layersByName[idlayer];
                var total = layer.get("numocorrencias")*1;
                //var dados = layer.get("fluxos");
                var dados = [];
                var janela = fluxo.show.openWindow(layer.get("title")).obj;
                var corpotable = ["<div class='container-fluid'><h5>" + layer.get("nomegrupo") + "</h5>"];
                layer.getSource().getFeaturesCollection().forEach(function(feature){
                    //origem destino numocorrencias %
                    dados.push({
                            "nome_origem": feature.get("nome_origem"),
                            "nome_destino": feature.get("nome_destino"),
                            "numocorrencias": feature.get("numocorrencias"),
                            "perc": (feature.get("numocorrencias") * 100) / total
                    });
                });
                corpotable.push("<h5>" + layer.get("nomesProcedimentos") + "</h5>");
                corpotable.push("<h6>Fonte: arquivo RD/SIH/MS</h6>");
                corpotable.push("<h5>Total = " + $.number(total,0,$trad("dec"),$trad("mil")) + "</h5>");
                corpotable.push(fluxo.show.tableThead({
                    id: idlayer
                }));
                janela.setBody(corpotable.join(""));
                //ver o metodo bootstrapTable em http://bootstrap-table.wenzhixin.net.cn/documentation/
                $('#bootstrapTable' + idlayer).bootstrapTable({
                    data: dados
                });
                $('.dropdown-toggle').dropdown();
            },
            barChartStart: function(idgrupo,idlayer,granotime){
                fluxo.log("barChartStart",(new Error).lineNumber);
                //console.log(idgrupo)
                var layer = fluxo._layersByName[idlayer];

                var queryoriginal = fluxo._grupos[idgrupo].query;
                var novaquery = [...queryoriginal];
                var cnes_destino = fluxo._layersByName[idlayer].getProperties().cnes_destino;

                if(cnes_destino != "DF"){
                    //muda os valores de destino pois o destino agora e um so
                    $.each(novaquery,function(i,v){
                        if(v.name == "destino[]"){
                            novaquery[i] = ({"name" : "destino[]", "value": cnes_destino});
                        }
                    });
                } else {
                    //se o destino for o DF, precisa agrupar os dados
                    $.each(novaquery,function(i,v){
                        if(v.name == "tipoorigem"){
                            novaquery[i] = ({"name" : "tipoorigem", "value": fluxo._grupos[idgrupo].tipoorigem+"Df"});
                        }
                    });
                }
                novaquery.push({"name": "granotime", "value": granotime});
                //console.log(novaquery);
                fluxo.abreAguarde();
                $.get(
                        //"codigo/fluxo/dadosfluxo.php?g_sid=" + i3GEO.configura.sid + "&",
                        "rest/sih/" + i3GEO.configura.sid + "/histogrambyfilter",
                        novaquery
                )
                .done(
                        function(data, status){
                            data = data.dados;
                            fluxo.log("barChartStart get data done",(new Error).lineNumber);
                            var janela = fluxo.show.openWindow(layer.get("title") + " Gr&aacute;fico");
                            janela.obj.setBody("");
                            fluxo.fechaAguarde();
                            if(data.length == 0){
                                i3GEO.janela.tempoMsg("N&atilde;o foi encontrada nenhuma ocorr&ecirc;ncia");
                            } else {
                                var div = document.createElement("div");
                                $(div).addClass('container-fluid');

                                var t = "<h5>" + layer.get("nomegrupo") + "</h5>";
                                div.innerHTML = t;
                                var canvas = document.createElement("canvas");
                                div.appendChild(canvas);
                                $i(janela.id + "_corpo").appendChild(div);

                                var ctx = canvas.getContext('2d');
                                //data = data.dados[cnes_destino];
                                var dados = [];
                                var labelsx = [];
                                var one_day=1000*60*60*24;

                                //verifica o tipo de data
                                if(data[0].date.split("-")[2]){
                                    $.each(data,function(i,v){
                                        if(i >= 0 && data[i+1] != undefined){
                                            var b = new Date(data[i+1].date);
                                            var a = new Date(data[i].date);
                                            var d = (b - a) / one_day;
                                            //console.log(d)
                                            if(d > 1){
                                                var k;
                                                dados.push({
                                                    y : v.numocorrencias,
                                                    x : v.date
                                                });
                                                labelsx.push(v.date);
                                                for(k = 1; k < d; k++){
                                                    var x = new Date(a.getTime() + (one_day * k));
                                                    x = x.toJSON().split("T")[0];
                                                    x = x.split("-");
                                                    x = x[0]*1 + "-" + x[1]*1 + "-" + x[2]*1;
                                                    dados.push({
                                                        y : 0,
                                                        x : x
                                                    });
                                                    labelsx.push(x);
                                                }
                                            } else {
                                                dados.push({
                                                    y : v.numocorrencias,
                                                    x : v.date
                                                });
                                                labelsx.push(v.date);
                                            }
                                        } else {
                                            dados.push({
                                                y : v.numocorrencias,
                                                x : v.date
                                            });
                                            labelsx.push(v.date);
                                        }
                                    });
                                    var time = {
                                            displayFormats: {
                                                day: 'D-M-Y'
                                            },
                                            unit : 'day'
                                    };
                                } else {
                                    $.each(data,function(i,v){
                                        dados.push({
                                            y : v.numocorrencias,
                                            x : v.date
                                        });
                                        labelsx.push(v.date);
                                    });
                                    if(data[0].date.split("-")[1]){
                                        var time = {
                                                displayFormats: {
                                                    day: 'M-Y'
                                                },
                                                unit : 'month'
                                        };
                                    } else {
                                        var time = {
                                                displayFormats: {
                                                    day: 'Y'
                                                },
                                                unit : 'year'
                                        };
                                    }

                                }
                                var dados = {
                                        "datasets" : [{
                                            "data" : dados,
                                            "backgroundColor": 'white'
                                        }]
                                };
                                fluxo.show.barChart({
                                    ctx: ctx,
                                    dados: dados,
                                    labelsx: labelsx
                                });
                            }
                        }
                )
                .fail(
                        function(data){
                            fluxo.fechaAguarde();
                        }
                );
            },
            barChart: function({ctx,dados,labelsx,time}){
                var grafico = new Chart(ctx, {
                    type: 'bar',
                    data: dados,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        //fill: false,
                        //lineTension: 2,
                        barThickness: 10,
                        categoryPercentage: 0.5,
                        barPercentage: 0.5,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true,
                                    fontColor: "rgba(255,255,255,0.8)"
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    beginAtZero:true,
                                    maxRotation: 45,
                                    fontColor: "rgba(255,255,255,0.8)",
                                    //maxTicksLimit: 2,
                                    display: true
                                },
                                type: "category",
                                labels: labelsx,
                                distribution: "series",
                                time: time
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
                                left: 10,
                                right: 10,
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
            },
            openWindow: function(titulo){
                titulo = titulo = "<span class='i3GeoTituloJanelaBsNolink' >" + titulo + "</span></div>";
                var id = i3GEO.util.uid()+"c";
                minimiza = function(){
                    i3GEO.janela.minimiza(id,200);
                };
                var janela = i3GEO.janela.cria(
                        "450px",
                        "300px",
                        "",
                        "",
                        "",
                        titulo,
                        id,
                        false,
                        "hd",
                        function(){},
                        minimiza,
                        "",
                        true,
                        "",
                        "",
                        "",
                        "",
                        ""
                );
                var d = YAHOO.i3GEO.janela.manager.overlays.length * 20 + 100;
                janela[0].moveTo(d,d)
                return {"obj": janela[0], "id": id};
            }
        }
};