if (typeof (i3GEO) === 'undefined') {
    var i3GEO = {};
}
i3GEO.listaDeFerramentas = {
	menu : [
	    {
		nome : "Tecnologia i3Geo",
		descricao: "Informa&ccedil;&otilde;es sobre o software utilizado para criar o mapa interativo",
		id : "ajudaMenu"
	    },
	    {
		nome : $trad("s2"),
		descricao: $trad("descMenuAnalise"),
		id : "analise"
	    },
	    {
		nome : $trad("operacoesMapaTema"),
		descricao:  $trad("descOperacoesMapaTema"),
		id : "ferramentas"
	    }
	    ],
	    submenus : {
		"ajudaMenu" : [
		    {
			id : "omenudataAjudamenu9",
			text : $trad("x68"),
			url : "javascript:i3GEO.janela.tempoMsg(i3GEO.parametros.mensageminicia)"
		    },
		    {
			id : "omenudataAjudamenu4",
			text : "Youtube",
			url : "https://www.youtube.com/results?search_query=i3geo",
			target : "_blank"
		    },
		    {
			id : "omenudataAjudamenu5",
			text : $trad("u5a"),
			url : "http://www.softwarepublico.gov.br",
			target : "_blank"
		    },
		    {
			id : "omenudataAjudamenu1",
			text : $trad("x67"),
			url : "https://softwarepublico.gov.br/social/i3geo",
			target : "_blank"
		    },
		    {
			id : "omenudataAjudamenu7",
			text : "Git",
			url : "https://softwarepublico.gov.br/gitlab/groups/i3geo",
			target : "_blank"
		    }
		    ],
		    "analise" : [
			{
			    id : "omenudataAnalise5",
			    text : $trad("u23"),
			    submenu : {
				id : "subAnalise2",
				itemdata : [
				    [
					{
					    id : "omenudataAnalise6",
					    text : $trad("u11a"),
					    url : "javascript:i3GEO.analise.dialogo.distanciaptpt()"
					},
					{
					    id : "omenudataAnalise7",
					    text : $trad("u12"),
					    url : "javascript:i3GEO.analise.dialogo.nptpol()"
					},
					{
					    id : "omenudataAnalise8",
					    text : $trad("u13"),
					    url : "javascript:i3GEO.analise.dialogo.pontoempoligono()"
					},
					{
					    id : "omenudataAnalise9",
					    text : $trad("u14"),
					    url : "javascript:i3GEO.analise.dialogo.pontosdistri()"
					},
					{
					    id : "omenudataAnalise9a",
					    text : $trad("u28"),
					    url : "javascript:i3GEO.analise.dialogo.centromassa()"
					}
					]
				    ]
			    }
			},
			{
			    id : "omenudataAnalise10",
			    text : $trad("u24"),
			    submenu : {
				id : "subAnalise3",
				itemdata : [
				    [
					{
					    id : "omenudataAnalise11",
					    text : $trad("u25"),
					    url : "javascript:i3GEO.analise.dialogo.dissolve()"
					}
					]
				    ]
			    }
			},
			{
			    id : "omenudataAnalise12",
			    text : $trad("u27"),
			    submenu : {
				id : "subAnalise5",
				itemdata : [
				    [
					{
					    id : "omenudataAnalise14",
					    text : $trad("u10"),
					    url : "javascript:i3GEO.analise.dialogo.buffer()"
					},
					{
					    id : "omenudataAnalise15",
					    text : $trad("u26"),
					    url : "javascript:i3GEO.analise.dialogo.agrupaelementos()"
					},
					{
					    id : "omenudataAnalise16",
					    text : $trad("u11"),
					    url : "javascript:i3GEO.analise.dialogo.centroide()"
					},
					{
					    id : "omenudataAnalise17",
					    text : $trad("t37b"),
					    url : "javascript:i3GEO.analise.dialogo.graficointerativo()"
					},
					{
					    id : "omenudataAnalise21",
					    text : $trad("x102"),
					    url : "javascript:i3GEO.analise.dialogo.heatmap()"
					},
					{
					    id : "omenudataAnalise22",
					    text : $trad("x104"),
					    url : "javascript:i3GEO.analise.dialogo.markercluster()"
					}
					]
				    ]
			    }
			}
			],
			"ferramentas" : [
			    // mapas
			    // apos modificar, veja se a ferramenta i3GEO.mapa.dialogo.ferramentas() vai funcionar
			    // essa ferramenta faz um parser das strings existentes em text e url
			    // text deve sempre ter a tag </span> e url deve sempre ter javascript:
			    //
			    {
				id : "omenudataFerramentas0a",
				text : $trad("g4a"),
				submenu : {
				    id : "mapa",
				    itemdata : [
					[
					    {
						id : "omenudataFerramentas11",
						text : $trad("d22t"),
						url : "javascript:i3GEO.mapa.dialogo.cliquePonto()"
					    },
					    {
						id : "omenudataFerramentas12",
						text : $trad("d25t"),
						url : "javascript:i3GEO.mapa.dialogo.cliqueTexto()"
					    },
					    {
						id : "wkt2layer",
						text : $trad("wkt2layer"),
						url : "javascript:i3GEO.mapa.dialogo.wkt2layer()"
					    }
					    ]
					]
				}
			    },
			    // temas
			    {
				id : "omenudataFerramentas0b",
				text : $trad("a7"),
				submenu : {
				    id : "camada",
				    itemdata : [
					[
					    {
						id : "omenudataFerramentas1b",
						text : $trad("t31"),
						url : "javascript:i3GEO.tema.dialogo.tabela()"
					    },
					    {
						id : "omenudataFerramentas3b",
						text : $trad("t25"),
						url : "javascript:i3GEO.tema.dialogo.toponimia()"
					    },
					    {
						id : "omenudataFerramentas4b",
						text : $trad("t27"),
						url : "javascript:i3GEO.tema.dialogo.etiquetas()"
					    },
					    {
						id : "omenudataFerramentas5b",
						text : $trad("t29"),
						url : "javascript:i3GEO.tema.dialogo.filtro()"
					    },
					    {
						id : "omenudataFerramentas6b",
						text : $trad("t33"),
						url : "javascript:i3GEO.tema.dialogo.editaLegenda()"
					    },
					    {
						id : "omenudataFerramentas8b",
						text : $trad("t37a"),
						url : "javascript:i3GEO.tema.dialogo.graficotema()"
					    },
					    {
						id : "omenudataFerramentas9b",
						text : $trad("t37b"),
						url : "javascript:i3GEO.analise.dialogo.graficointerativo()"
					    },
					    {
						id : "omenudataFerramentas3e",
						text : $trad("t49"),
						url : "javascript:i3GEO.tema.dialogo.tme()"
					    }
					    ]
					]
				}
			    },
			    //navegacao
			    {
				id : "omenudataNavegacao1",
				text : $trad("x105"),
				submenu : {
				    id : "subAnalise4",
				    itemdata : [
					[
					    {
						id : "omenudataAnalise18",
						text : $trad("d30"),
						url : "javascript:i3GEO.analise.dialogo.linhaDoTempo()"
					    },
					    {
						id : "omenudataNavegacao4",
						text : $trad("d8t"),
						url : "javascript:i3GEO.mapa.dialogo.mostraExten()"
					    }
					    ]
					]
				}
			    }
			    ]
	    }
};