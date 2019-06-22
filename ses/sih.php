<?php
/*
 * Define os parametros que serao utilizados na montagem do mapa default
 */
$conteudoGuia = file_get_contents("codigo/fluxo/sih_form.php");
// icone para abrir a guia com as opcoes de fluxo
$novoicone = '<div onclick="i3GEO.guias.ativa(\'fluxo\',this)" data-idconteudo="fluxoSESContainer"
    style="margin-top: 3px;">
    <button title="Fluxos" class="iconeGuiaMovel" style="color: white; box-shadow: none;">
    <i class="material-icons">device_hub</i>
    </button>
    </div>';

$corpoconfig = array(
    "legenda" => true, // legenda do i3geo
    "tipoInterface" => "OSM",
    "debug" => "", // "naocompacto"
    "iconeGuiaMovel" => $novoicone,
    "conteudoGuia" => ""
);
include ("corpo.php");
?>
<script>
/**
 * Configuracao default utilizada em todos os mapas
 */
<?php include ("corpo_script.php"); ?>
/**
 * Configuracoes especificas do aplicativo SIH
 */

//Funcao que sera executada apos a inicializacao do mapa
    config.afterStart = function() {
        $('.iconeGuiaMovel').tooltip({
            animation : false,
            trigger : "manual hover",
            placement : "left"
        });
        $('.iconeGuiaMovel').tooltip('show');
        setTimeout(function(){$('.iconeGuiaMovel').tooltip('hide');},5000);

        $('.ol-i3GEOcontrols button').tooltip({
            animation : false,
            trigger : "hover",
            placement : "auto",
            template : "<div class='tooltip ' ><div class='tooltip-inner'></div></div>"
        });
        caixadebusca.start();
        destaques.start();
        tutorial.init();
    new ol.control.Zoom({
        target: $i("i3GEOzoomInOut")
    }).setMap(i3geoOL);
        //para a guia com as opcoes do fluxo
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
        //veja em corpo_script.php
        open_fluxo();
    };
    //
    //inicia o mapa
    //Veja tambem config.php
    //
    //O primeiro parametro permite alterar o mapa, inserindo camadas e outras definicoes que afetam o corpo do mapa
    //O segundo parametro inclui configuracoes que afetam o funcionamento da interface que controla a visualizacao do mapa
    //
    i3GEO.init(parametrosMapa, config);
</script>
</body>
</html>
