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
            <li><a onclick="fluxo.show.barChartStart('{{idgrupo}}','{{idlayer}}','d')" href="javascript:void(0)"> Dia </a></li>
            <li><a onclick="fluxo.show.barChartStart('{{idgrupo}}','{{idlayer}}','m')" href="javascript:void(0)"> M&ecirc;s </a></li>
            <li><a onclick="fluxo.show.barChartStart('{{idgrupo}}','{{idlayer}}','a')" href="javascript:void(0)"> Ano </a></li>
        </ul>
    </span>
    <input id="{{idgrupo}}{{idlayer}}Color" class="c-camadafluxo" onchange="fluxo.setColor(this,'{{idlayer}}')" value="{{corhex}}"
        style="border: 1px solid {{corhex}}; width: 20px; height: 20px; margin: 2px; position: relative; top: 5px; background-color: unset; cursor: pointer;" type="color">

    <div class="checkbox text-left" style="display: inline;">
        <label style="display: inline;" class="fluxoSESlabel">
            <input id="{{idgrupo}}{{idlayer}}" class="noprint i-camadafluxo" type="checkbox" {{{checked}}} onclick="fluxo.layerToggle(this,'{{idgrupo}}','{{idlayer}}')" />
            <span class="checkbox-material noprint"><span class="check"></span></span><span title="{{{nomecompleto}}}">({{numocorrencias}}) {{{titulo}}}</span>
        </label>
    </div>
</li>
</script>