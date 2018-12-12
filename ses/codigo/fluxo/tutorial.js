var tutorialFluxo = new Tour({
    storage: false,
    debug: false,
    backdrop: false,
    orphan: true,
    name: "tutorialFluxo",
    template: "<div class='popover tour' style='color: black;background-color: white;border:1px solid rgba(0,0,0,.25)'><div class='arrow' style='display:block'></div><h3 class='popover-title' style='background-color:#005272;color: white;'></h3><div class='popover-content' style='line-height: 2rem;font-size: 1.6rem;'></div><div class='popover-navigation' style='padding:2px 2px'><button class='btn btn-default btn-xs' data-role='prev'><i class='material-icons'>arrow_back</i></button><button class='btn btn-default btn-xs' style='float:none;' data-role='end'><i class='material-icons'>stop</i></button><button class='btn btn-default btn-xs' data-role='next'><i class='material-icons'>arrow_forward</i></button></div></div>",
    steps: [
	{
	    duration: false,
	    element: "",
	    placement: 'auto',
	    title: "An&aacute;lise de fluxos",
	    content: "Nesse mapa voc&ecirc; poder&aacute; criar an&aacute;lises para visualizar os fluxos de pacientes que buscam atendimento na rede p&uacute;blica do SUS no DF."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='guia']",
	    placement: 'auto',
	    title: "Funcionalidades",
	    content: "Utilize esse bloco para definir sua an&aacute;lise. Voc&ecirc; poder&aacute; criar mais de uma an&aacute;lise com par&acirc;metros diferentes."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='periodo']",
	    placement: 'auto',
	    title: "Per&iacute;odo",
	    content: "Comece escolhendo o per&iacute;odo de tempo. Observe que o volume de dados cresce em fun&ccedil;&atilde;o do n&uacute;mero de dias escolhidos, o que pode dificultar a gera&ccedil;&atilde;o da an&aacute;lise em determinadas condi&ccedil;&otilde;es, como tr&aacute;fego na rede ou n&uacute;mero de usu&aacute;rios conectados.",
	    onNext: function(){
		$("#fluxoParametrosSES input[name='inicio']").val("2017-01-01");
		$("#fluxoParametrosSES input[name='fim']").val("2017-03-20");
	    }
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='origem']",
	    placement: 'auto',
	    title: "Tipo de origem",
	    content: "Escolha aqui o tipo de origem dos pacientes. 'Outros estados' ir&aacute; considerar todos os estados excluindo o DF e os dados ser&atilde;o totalizados de acordo. As linhas de fluxo ter&atilde;o como origem um ponto no centro de cada estado. No caso da op&ccedil;&atilde;o 'Outros munic&iacute;pios' o agrupamento ser&aacute; por munic&iacute;pio de origem. J&aacute; a op&ccedil;&atilde;o 'Distrito Federal' ir&aacute; considerar apenas os dados cuja origem estiver dentro do DF."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='ride']",
	    placement: 'auto',
	    title: "RIDE",
	    content: "Marque essa op&ccedil;&atilde;o se voc&ecirc; quiser considerar como origem apenas as ocorr&ecirc;ncias dentro da Regi&atilde;o Integrada de Desenvolvimento do Distrito Federal e Entorno."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='analise']",
	    placement: 'auto',
	    title: "Gerar an&aacute;lise",
	    content: "Ap&oacute;s ter escolhido os par&acirc;metros, clique nesse bot&atilde;o para gerar a an&aacute;lise."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='filtros']",
	    placement: 'auto',
	    title: "Filtros",
	    content: "Antes de gerar a an&aacute;lise voc&ecirc; pode tamb&eacute;m escolher outros filtros, como os destinos (hospitais) e os tipos de procedimentos.",
	    onNext: function(){
		$("[data-tutorialfluxo='filtros']").click();
	    }
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='filtros']",
	    placement: 'auto',
	    title: "Filtros",
	    content: "Aguarde um pouco at&eacute; aparecerem as op&ccedil;&otilde;es do filtro para continuar o tutorial."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='busca']",
	    placement: 'auto',
	    title: "Busca",
	    content: "Aqui voc&ecirc; pode buscar um estabelecimento ou procedimento apenas digitando o nome."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='destino']",
	    placement: 'auto',
	    title: "&Aacute;rvore",
	    content: "Clique no &iacute;cone 'pastinha' ou no nome para expandir a &aacute;rvore.",
	    onNext: function(){
		$("[data-tutorialfluxo='destino']").click();
	    }
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='destino']",
	    placement: 'auto',
	    title: "Adicionar filtro",
	    content: "Clique no &iacute;cone 'mais' para incluir no filtro. Voc&ecirc; pode escolher mais de um item e de &aacute;rvores diferentes.",
	    onNext: function(){
		fluxo.filtro.adddestino("0010464","HRAN");
		fluxo.filtro.adddestino("6876617","HCB");
	    }
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='itensfiltro']",
	    placement: 'auto',
	    title: "Itens do filtro",
	    content: "Os itens escolhidos aparecer&atilde;o aqui. Voc&ecirc; pode excluir clicando sobre cada um."
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='filtros']",
	    placement: 'auto',
	    title: "Fechar",
	    content: "Clique aqui novamente para esconder as op&ccedil;&otilde;es de filtro.",
	    onNext: function(){
		$("[data-tutorialfluxo='filtros']").click();
	    }
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='abriranalise']",
	    placement: 'auto',
	    title: "Finalizando",
	    content: "Abra novamente o item que cont&eacute;m os par&acirc;metros principais clicando no t&iacute;tulo.",
	    onNext: function(){
		$("[data-tutorialfluxo='abriranalise']").click();
	    }
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='analise']",
	    placement: 'auto',
	    title: "Gerar an&aacute;lise",
	    content: "Agora que os par&acirc;metros j&aacute; foram escolhidos, utilize esse bot&atilde;o para iniciar o processo de busca e apresenta&ccedil;&atilde;o dos dados. Ap&oacute;s clicar, aguarde o processamento.",
	    onNext: function(){
		$("[data-tutorialfluxo='analise']").click();
	    }
	},
	{
	    duration: false,
	    element: "[data-tutorialfluxo='resultados']",
	    placement: 'auto',
	    title: "Aguarde",
	    content: "Ao final do processo, os resultados ser&atilde;o mostrados aqui. Espere aparecer para continuar o tutorial."
	}
	]});

tutorialFluxoContinuacao = [
    {
	duration: false,
	element: "[data-tutorialfluxo='resultados']",
	placement: 'auto',
	title: "Fluxos",
	content: "Agora voc&ecirc; j&aacute; pode ver os fluxos no mapa e realizar algumas opera&ccedil;&otilde;es."
    },
    {
	duration: false,
	element: ".painelLayers:first",
	placement: 'auto',
	title: "Camadas",
	content: "Cada par origem-destino corresponde &agrave; uma linha no mapa com cor diferente. Essas linhas iniciam vis&iacute;veis mas existem outras linhas especiais, que voc&ecirc; deve ligar manualmente quando quiser."
    },
    {
	duration: false,
	element: ".i-camadafluxo:first",
	placement: 'auto',
	title: "Ligar/desligar",
	content: "Clique aqui para ligar ou desligar a visualiza&ccedil;&atilde;o dessa linha.",
	onNext: function(){
		$(".i-camadafluxo:first").click();
	    }
    },
    {
	duration: false,
	element: ".c-camadafluxo:first",
	placement: 'auto',
	title: "Cor",
	content: "Para mudar a cor da linha use essa op&ccedil;&atilde;o."
    },
    {
	duration: false,
	element: ".g-camadafluxo:first",
	placement: 'auto',
	title: "Gr&aacute;fico",
	content: "Para ver a distribui&ccedil;&atilde;o dos valores em um gr&aacute;fico, clique nesse bot&atilde;o e escolha o tipo de agrega&ccedil;&atilde;o temporal que ser&aacute; considerada."
    },
    {
	duration: false,
	element: ".t-camadafluxo:first",
	placement: 'auto',
	title: "Tabela",
	content: "Voc&ecirc; tamb&eacute;m pode ver os dados na forma de uma tabela que permite tamb&eacute;m exportar os dados."
    }
    ];
