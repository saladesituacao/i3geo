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
                        <i class="material-icons">delete</i> Remover análise
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
                <li><a onclick="fluxo.show.circlesonoff('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">bubble_chart</i> Liga/desliga c&iacute;rculos
                    </a></li>
                <li><a onclick="fluxo.legendonoff('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">visibility</i> Legenda simples/completa
                    </a></li>
                <li><a onclick="fluxo.exportashp('{{id}}')" href="javascript:void(0)">
                        <i class="material-icons">get_app</i> Exportar para shapefile
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