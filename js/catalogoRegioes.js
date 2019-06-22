i3GEO.catalogoRegioes = {
        config: {
            'templateDir': 'templates/dir.html',
            'templateTema': 'templates/tema.html',
            'idCatalogoPrincipal': 'catalogoPrincipal',
            'idCatalogoNavegacao': 'catalogoNavegacao',
            'idOndeMigalha': 'catalogoMigalha'
        },
        carregaTemplates: function(){
            var t1 = i3GEO.catalogoRegioes.config.templateDir,
            t2 = i3GEO.catalogoRegioes.config.templateTema,
            t3 = $("#" + i3GEO.catalogoRegioes.config.idOndeMigalha).attr("data-template");
            $.when( $.get(t1),$.get(t2),$.get(t3) ).done(function(r1,r2,r3) {
                i3GEO.template.dir = r1[0];
                i3GEO.template.tema = r2[0];
                i3GEO.template.catalogoMigalha = r3[0];
                i3GEO.catalogoRegioes.inicia();
            }).fail(function() {
                i3GEO.janela.closeMsg($trad("erroTpl"));
                return;
            });
        },
        DADOS: "",
        aguarde: function(){
            $("#" + i3GEO.catalogoRegioes.config.idCatalogoNavegacao).html($trad("o1"));
        },
        atualizaMigalha: function(){
            var migalha = i3GEO.catalogoRegioes.MIGALHA;
            var n = migalha.length;

            var nome = migalha[n - 1].nome;
            var onclick = migalha[n - 2].onclick;

            var t = Mustache.to_html(
                    i3GEO.template.catalogoMigalha,
                    {"nome":nome,"onclick":"i3GEO.catalogoRegioes.MIGALHA.pop();i3GEO.catalogoRegioes.MIGALHA.pop();" + onclick}
            );
            $("#" + i3GEO.catalogoRegioes.config.idOndeMigalha).html(t);
            $("#i3GEOguiaMovelConteudo").scrollTop(0);

        },
        escondeCatalogoPrincipal: function(){
            $("#" + i3GEO.catalogoRegioes.config.idCatalogoPrincipal).hide();
        },
        mostraCatalogoPrincipal: function(){
            $("#" + i3GEO.catalogoRegioes.config.idCatalogoNavegacao).fadeOut( "fast", function(){
                $("#" + i3GEO.catalogoRegioes.config.idOndeMigalha).hide();
                $("#" + i3GEO.catalogoRegioes.config.idCatalogoPrincipal).show();
            });
            i3GEO.catalogoRegioes.DADOS = "";
        },
        inicia: function(config){
            if (typeof (console) !== 'undefined')
                console.info("i3GEO.catalogoRegioes.inicia");

            if(config){
                $.each( config, function( i,v ) {
                    i3GEO.catalogoRegioes.config[i] = v;
                });
            }
            i3GEO.catalogoRegioes.aguarde();

            i3GEO.catalogoRegioes.DADOS = "";
            if(!i3GEO.template.dir || !i3GEO.template.tema || !i3GEO.template.catalogoMigalha){
                i3GEO.catalogoRegioes.carregaTemplates();
                return;
            } else {
                i3GEO.catalogoRegioes.MIGALHA = [
                    {"nome":"","onclick":"i3GEO.catalogoRegioes.mostraCatalogoPrincipal()"},
                    {"nome":$trad("x87"),"onclick":"i3GEO.catalogoRegioes.inicia()"}
                    ];
                i3GEO.catalogoRegioes.atualizaMigalha();


                config = i3GEO.catalogoRegioes.config;

                i3GEO.catalogoRegioes.escondeCatalogoPrincipal();

                $("#" + i3GEO.catalogoRegioes.config.idCatalogoNavegacao).show();

                var lista = function(dados){
                    var clone = [],
                    t;

                    $.each( dados, function( i,v ) {
                        clone.push({
                            "nome": v.nome_tipo_regiao,
                            "descricao": v.descricao_tipo_regiao,
                            "onclick": "i3GEO.catalogoRegioes.adiciona(" + v.codigo_tipo_regiao + ")"
                        });
                    });
                    t = Mustache.to_html(
                            "{{#data}}" + i3GEO.template.tema + "{{/data}}",
                            {"data":clone}
                    );
                    $("#" + config.idCatalogoNavegacao).html(t);

                    $("#" + i3GEO.catalogoRegioes.config.idCatalogoPrincipal).fadeOut( "fast", function(){
                        $("#" + i3GEO.catalogoRegioes.config.idOndeMigalha).show();
                        $("#" + i3GEO.catalogoRegioes.config.idCatalogoNavegacao).show();
                    });
                };
                i3GEO.catalogoRegioes.getRegions(lista);
            }
        },
        getRegions: function(after){
            i3GEO.request.get({
                snackbar: false,
                snackbarmsg: false,
                btn: false,
                par: {
                    funcao: "getRegions"
                },
                prog: "/serverapi/catalog/",
                fn: function(data){
                    after.call(after, data);
                }
            });
        },
        getRegionsTree: function(after,region,regionpai,regionpaivalue){
            i3GEO.request.get({
                snackbar: false,
                snackbarmsg: false,
                btn: false,
                par: {
                    funcao: "getRegionsTree",
                    region: region,
                    regionpai: regionpai,
                    regionpaivalue: regionpaivalue
                },
                prog: "/serverapi/catalog/",
                fn: function(data){
                    after.call(after, data);
                }
            });
        },
        adiciona: function(region){
            i3GEO.request.get({
                snackbar: false,
                snackbarmsg: false,
                btn: false,
                par: {
                    funcao: "addLayerRegion",
                    region: region
                },
                prog: "/serverapi/map/",
                fn: function(){
                    i3GEO.mapa.refresh();
                }
            });
        }
};