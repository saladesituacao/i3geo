<div class="corpoFundo" id="guia9obj" style="overflow: inherit; display: none; text-align: left; height: 100%">
    <div class="i3GEOfechaGuia" style="display: flex; height: 80px;">
        <button class="pull-left text-left" onclick="i3GEO.guias.abreFecha('fecha');">
            <span style="vertical-align: middle; font-size: 1.5rem;">Fluxo de Procedimentos Principais - SIH</span>
        </button>
        <button style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.ativa('temas',this)" data-verificaAbrangencia="" data-idconteudo="guia1obj" data-idListaFundo="listaFundo"
            data-idListaDeCamadas="listaTemas">
            <span style="vertical-align: middle" class="material-icons">visibility</span>
        </button>
        <button style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.ativa('adiciona',this)" data-idconteudo="guia2obj" data-idMigalha="catalogoMigalha"
            data-idNavegacao="catalogoNavegacao" data-idCatalogo="catalogoPrincipal" data-idMenus="catalogoMenus">
            <span style="vertical-align: middle" class="material-icons">layers</span>
        </button>
        <button style="width: unset; height: 3rem;" class="text-right" onclick="i3GEO.guias.abreFecha('fecha');">
            <span style="vertical-align: middle" class="material-icons">cancel</span>
        </button>
    </div>
    <div class="separadorCabecalhoGuias">&nbsp;</div>
    <div data-tutorialfluxo="guia" class="" style="width: calc(100% - 5px);">

        <!-- deixe o form aqui ou o acordion nao funciona -->
        <form onsubmit="fluxo.getData();return false;" id="form-fluxo" action="" class="noprint form-horizontal corpoFundo" role="form" method="post">
            <div class="panel-group" id="fluxoSESContainer" role="tablist" aria-multiselectable="false">
                <div style="overflow: hidden;" class="panel panel-default">
                    <div class="panel-heading corpoCabecalho" role="tab" id="headingOne">
                        <h4 class="panel-title" style="text-align: left;">
                            <a data-tutorialfluxo='abriranalise' role="button" data-toggle="collapse" data-parent="#fluxoSESContainer" href="#fluxoParametrosSES" aria-expanded="true"
                                aria-controls="fluxoParametrosSES"> An&aacute;lises </a>
                        </h4>
                    </div>
                    <div style="overflow: hidden;" id="fluxoParametrosSES" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body corpoFundo" style="height: auto; padding-top: 0px; padding-bottom: 10px; background-color: #477596; text-align: left; color: white;">
                            <div style="display: block; width: 100%;" class="form-group condensed">
                                <div data-tutorialfluxo='periodo' style="margin-right: 10px;">
                                    <label class="control-label fluxoSESlabel" style="color: white;" for="">In&iacute;cio do per&iacute;odo</label>
                                    <input style="font-size: 1.2rem;" required data-titulo="" class="form-control input-lg corpoFundo" name="inicio" value="" type="date">
                                </div>
                                <div style="margin-right: 10px;">
                                    <label class="control-label fluxoSESlabel" style="color: white;" for="">Fim do per&iacute;odo</label>
                                    <input style="font-size: 1.2rem;" required data-titulo="/ " class="form-control input-lg corpoFundo" name="fim" value="" type="date">
                                </div>

                                <div style="margin-right: 10px;" data-tutorialfluxo='origem'>
                                    <label class="control-label fluxoSESlabel" for="">Tipo de origem</label>
                                    <div style="width: 100%;">
                                        <select style="font-size: 1.2rem;" required data-size="3" data-style="fluxoSESlabel" class="form-control corpoFundo" data-titulo="Origem: " id="tipoorigem"
                                            name="tipoorigem">
                                            <option value="df">Distrito Federal</option>
                                            <option value="municipio">Outros munic&iacute;pios</option>
                                            <option selected value="uf">Outros Estados</option>
                                        </select> <b class='caret careti'></b>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group condensed'>
                                <div class='checkbox text-left'>
                                    <label class="fluxoSESlabel">
                                        <input data-tutorialfluxo='ride' data-titulo="Ride: " id="apenasride" name="apenasride" type='checkbox' />
                                        <span class="checkbox-material noprint"><span class="check"></span></span><span title=""> Apenas RIDE <span style="font-size: 10px;"><span></span> </span></span>
                                    </label>
                                </div>
                            </div>
                            <br>
                            <button data-tutorialfluxo='analise' disabled type="submit" class="btn btn-primary btn-sm btn-raised" role="button">
                                Criar an&aacute;lise <span class="glyphicon glyphicon-repeat normal-right-spinner"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div style="overflow: hidden;" class="panel panel-default">
                    <div class="panel-heading corpoCabecalho" role="tab" id="headingFiltro">
                        <h4 class="panel-title" style="text-align: left;">
                            <a data-tutorialfluxo='filtros' class="collapsed" role="button" data-toggle="collapse" data-parent="#fluxoSESContainer" href="#fluxoFiltrosSES" aria-expanded="false"
                                aria-controls="fluxoFiltrosSES"> Filtros opcionais&nbsp;</a>
                        </h4>
                    </div>
                    <div style="overflow: hidden;" id="fluxoFiltrosSES" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFiltro">
                        <div class="panel-body corpoFundo" style="padding-top: 0px; padding-bottom: 10px; background-color: #477596; text-align: left; color: white;">
                            <br>
                            <div id="fluxoFiltroPills" data-tutorialfluxo='itensfiltro' class="panel panel-default">
                                <div style="overflow: auto; max-height: 350px;" id="fluxoFiltroProcedimentosPills" class="panel-body corpoFundo"></div>
                                <div style="overflow: auto; max-height: 350px;" id="fluxoFiltroDestinosPills" class="panel-body corpoFundo"></div>
                                <div style="overflow: auto; max-height: 350px;" id="fluxoFiltroMunicipiosPills" class="panel-body corpoFundo"></div>
                            </div>
                            <br>
                            <div class="typeahead__container" style="z-index: 1000">
                                <div class="typeahead__field form-group label-fixed condensed" style="font-size: 2rem">
                                    <span class="typeahead__query"> <input data-tutorialfluxo='busca' id="fluxoTypehead" class="form-control input-lg" name="" placeholder="Procurar" autocomplete="off"
                                            type="search"> <b class='caret careti'></b>
                                    </span>
                                </div>
                            </div>
                            <div style="width: 100%; z-index: 10;" class="form-group label-fixed condensed ">
                                <div id="arvoreParametros">
                                    <span class="glyphicon glyphicon-repeat normal-right-spinner"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div data-tutorialfluxo='resultados' class="panel-group" id="fluxoSESContainerNovos" role="tablist" aria-multiselectable="true"></div>
    </div>
</div>
<script id="templateGrupoFluxo" type="x-tmpl-mustache">

<div class="panel panel-default painelLayers" id="painelLayers{{id}}">
    <div class="panel-heading corpoCabecalho" style="display: flex;" role="tab" id="{{id}}">
        <div class="dropdown noprint" style="margin: 2px 2px;">
            <a role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="material-icons">playlist_add_check</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-left">
                <li><a onclick="fluxo.hideLayersGroup('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">check_box_outline_blank</i> Desmarcar todos
                    </a></li>
                <li><a onclick="fluxo.showLayersGroup('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">check_box</i> Marcar todos
                    </a></li>
                <li><a onclick="fluxo.deleteGroup('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">delete</i> Remover an&aacute;lise
                    </a></li>
                <li><a onclick="fluxo.show.tableGrupo('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">view_list</i> Tabela consolidada
                    </a></li>
                <li><a onclick="this.nextElementSibling.click()" href="javascript:void(0)">
                        <i class="material-icons">color_lens</i> Usar uma &uacute;nica cor
                    </a>
                    <input onchange="fluxo.corUnicaGroup('{{id}}',this)" type="color" value="" style="display: none;" /></li>
                <li><a onclick="fluxo.restauraCoresGroup('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">undo</i> Restaura cores
                    </a></li>
                <li><a onclick="fluxo.legendonoff('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">visibility</i> Legenda simples/completa
                    </a></li>
                <li><a onclick="fluxo.exportageojson('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">get_app</i> Exportar para geoJson
                    </a></li>
            </ul>
        </div>
        <div class="checkbox" style="margin: 2px 2px;">
            <label class="fluxoSESlabel">
                <input id="grupoToggle{{id}}" onclick="fluxo.grupoToggle(this,'{{id}}')" class="noprint condensed" type="checkbox" checked />
                <span class="checkbox-material noprint"><span class="check"></span></span>
            </label>
        </div>
        <h4 class="panel-title" style="margin: 2px 2px; text-align: left; width: calc(100% - 60px);">
            <a class="" style="padding-right: 20px;" role="button" data-toggle="collapse" href="#fluxoAranhas{{id}}" aria-expanded="true" aria-controls="fluxoAranhas">{{{titulo}}}</a>
        </h4>
    </div>
    <div class="corpoFundo fluxoSESlabel" style="padding: 5px;">
        <small>{{{nomesProcedimentos}}}</small>
    </div>
    <div id="fluxoAranhas{{id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="{{id}}">
        <div class="panel-body corpoFundo" style="background-color: #477596; text-align: left; color: white;">
            <div class="loaderLayers">
                <span class="glyphicon glyphicon-repeat normal-right-spinner"></span>
            </div>
        </div>
        <ul class="aranha">
        </ul>
    </div>
</div>
</script>
<script id="templateCamadasFluxo" type="x-tmpl-mustache">
<li data-layer="{{idlayer}}" class="list-group condensed" style="cursor: move; margin-left: 20px; margin-top: 0px;">
    <button title="Tabela" onclick="fluxo.show.tableLayer('{{idlayer}}')" class="noprint btn btn-xs t-camadafluxo" style="margin: 2px; padding: 2px;">
        <i class="material-icons" style="width: 24px;">view_list</i>
    </button>
    <span class="dropup">
        <button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Gr&aacute;fico" class="g-camadafluxo noprint btn btn-xs"
            style="margin: 2px; padding: 2px;">
            <i class="material-icons" style="width: 24px;">insert_chart_outlined</i>
        </button>
        <ul class="noprint dropdown-menu dropdown-menu-left">
            <li><a onclick="fluxo.show.barChartStart('{{idgrupo}}','{{idlayer}}','1d')" href="javascript:void(0)"> Dia </a></li>
            <li><a onclick="fluxo.show.barChartStart('{{idgrupo}}','{{idlayer}}','1m')" href="javascript:void(0)"> M&ecirc;s </a></li>
            <li><a onclick="fluxo.show.barChartStart('{{idgrupo}}','{{idlayer}}','1y')" href="javascript:void(0)"> Ano </a></li>
        </ul>
    </span>
    <input id="{{idgrupo}}{{idlayer}}Color" class="c-camadafluxo" onchange="fluxo.setColor(this,'{{idlayer}}')" value="{{corhex}}"
        style="border: 1px solid {{corhex}}; width: 20px; height: 20px; margin: 2px; position: relative; top: 5px; background-color: unset; cursor: pointer;" type="color">

    <div class="checkbox text-left" style="display: inline;">
        <label style="display: inline;" class="fluxoSESlabel">
            <input id="{{idgrupo}}{{idlayer}}" class="noprint i-camadafluxo" type="checkbox" {{{checked}}} onclick="fluxo.layerToggle(this,'{{idgrupo}}','{{idlayer}}')" />
            <span class="checkbox-material noprint"><span class="check"></span></span><span title="{{{nomecompleto}}}">({{numocorrencias}}) {{{titulo}}} <span style="font-size:10px;">{{{nomecompleto}}}<span></span>
        </label>
    </div>
</li>
</script>