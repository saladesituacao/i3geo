//Instance the tour
var tutorial = new Tour({
	storage: false,
	debug: false,
	name: "tutorial",
	template: "<div class='popover tour' style='color: black;background-color: white;birder:1px solid rgba(0,0,0,.25)'><div class='arrow' style='display:block'></div><h3 class='popover-title'></h3><div class='popover-content' style='line-height: 16px;'></div><div class='popover-navigation' style='padding:2px 2px'><button class='btn btn-default btn-xs' data-role='prev'><i class='material-icons'>arrow_back</i></button><button class='btn btn-default btn-xs' style='float:none;' data-role='end'><i class='material-icons'>stop</i></button><button class='btn btn-default btn-xs' data-role='next'><i class='material-icons'>arrow_forward</i></button></div></div>",
	steps: [
		{
			element: ".ol-zoom",
			title: "Barra de navega&ccedil;&atilde;o",
			content: "Voc&ecirc; pode navegar pelo mapa utilizando os bot&otilde;es do mouse e a roda. A barra permite que as opera&ccedil;&otilde;es de aproximar/afastar sejam feitas com o clique do mouse, al&eacute;m de restaurar a extens&atilde;o geogr&aacute;fica inicial e voltar para o zoom anterior."
		},
		{
			element: ".typeahead__filter-button",
			title: "Title of my step",
			content: "Content of my step"
		}
		]});