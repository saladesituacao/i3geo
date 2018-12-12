<!-- deixe o form aqui ou o acordion nao funciona -->
<form onsubmit="fluxo.getData();return false;" id="form-fluxo" action="" class="noprint form-horizontal corpoFundo" role="form" method="post">
    <div class="panel-group" id="fluxoSESContainer" role="tablist" aria-multiselectable="true">
        <div style="overflow: hidden;" class="panel panel-default">
            <hr>
            <div class="panel-heading corpoCabecalho" role="tab" id="headingFiltro">
                <h4 class="panel-title" style="text-align: left;">
                    <a data-tutorialfluxo='filtros' class="collapsed" role="button" data-toggle="collapse" data-parent="#fluxoSESContainer" href="#fluxoFiltrosSES" aria-expanded="false"
                        aria-controls="fluxoFiltrosSES"> Filtros &nbsp;</a>
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
        <hr>
        <div style="overflow: hidden;" class="panel panel-default">
            <div class="panel-heading corpoCabecalho" role="tab" id="headingOne">
                <h4 class="panel-title" style="text-align: left;">
                    <a data-tutorialfluxo='abriranalise' role="button" data-toggle="collapse" data-parent="#fluxoSESContainer" href="#fluxoParametrosSES" aria-expanded="true"
                        aria-controls="fluxoParametrosSES"> Análises </a>
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
                                <select required data-size="3" data-style="fluxoSESlabel" class="form-control corpoFundo" data-titulo="Origem: " id="tipoorigem" name="tipoorigem">
                                    <option value="df">Distrito Federal</option>
                                    <option value="municipio">Outros munic&iacute;pios</option>
                                    <option selected value="uf">Outros Estados</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='form-group condensed'>
                        <div class='checkbox text-left'>
                            <label class="fluxoSESlabel">
                                <input data-tutorialfluxo='ride' data-titulo="Ride: " id="apenasride" name="apenasride" type='checkbox' />
                                Apenas RIDE
                            </label>
                        </div>
                    </div>
                    <br>
                    <button data-tutorialfluxo='analise' disabled type="submit" class="btn btn-primary btn-sm btn-raised" role="button">
                        Criar análise <span class="glyphicon glyphicon-repeat normal-right-spinner"></span>
                    </button>
                </div>
            </div>
        </div>
        <hr>
    </div>
</form>
<div data-tutorialfluxo='resultados' class="panel-group" id="fluxoSESContainerNovos" role="tablist" aria-multiselectable="true"></div>
