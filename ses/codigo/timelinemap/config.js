timelinemap._datasets = {
	"sifilisnotifsem":{
	    "controles": {
		"intervalo": 400,
		"fillcolor": "#FEFF04",
		"maxsize": 400
	    },
	    "title": "Casos diagnosticados de s&iacute;filis por semana epidemiol&oacute;gica",
	    "tag": "S&iacute;filis, casos diagnosticados",
	    "index": "sifilis_consolidado_new",
	    "typedata": "semana",
	    "name": "sifilisnotifsem",
	    "keys": [],
	    "typeregion": "ra",
	    "maxsize": 0,
	    "style": new ol.style.Style({
		image: new ol.style.Circle({
		    radius: 0,
		    stroke: new ol.style.Stroke({
			color: "black"}),
		    fill: new ol.style.Fill({
			color: "#FEFF04"
		    })})}),
	    "query": {
		"aggs": {
		    "regiao": {
			"terms": {
			    "field": "i_desc_radf_res.keyword",
			    "size": 100,
			    "order": {"_key": "desc"}
			},
			"aggs": {
			    "ano": {
				"terms": {
				    "field": "i_ano_notif",
				    "size": 25,
				    "order": {"_key": "asc"}
				},
				"aggs": {
				    "semana": {
					"terms": {
				                "script": {
				                  "source": "doc['i_data_notif'].date.monthOfYear + '_' + doc['i_semana_notif'].value",
				                  "lang": "painless"
				                },
				                "size": 60,
				                "order": {
				                  "_key": "asc"
				                }
				              },
					"aggs": {"qtd": {"sum": {"field": "i_qtd_agravo"}}}
				    }
				}
			    }
			}
		    }
		},
		"docvalue_fields": [],
		"query": {
		    "bool": {
			"must": [
			    {"match_all": {}},
			    {"match_phrase": {"i_desc_uf_res": {"query": "DF"}}}
			    ]
		    }
		}
	    }
	},
	"hivdiagsem":{
	    "controles": {
		"intervalo": 400,
		"fillcolor": "#FEFF04",
		"maxsize": 400
	    },
	    "title": "Casos diagnosticados de hiv por semana epidemiol&oacute;gica",
	    "tag": "Hiv, casos diagnosticados",
	    "index": "hiv_consolidado_new",
	    "typedata": "semana",
	    "name": "hivdiagsem",
	    "keys": [],
	    "typeregion": "ra",
	    "maxsize": 0,
	    "style": new ol.style.Style({
		image: new ol.style.Circle({
		    radius: 0,
		    stroke: new ol.style.Stroke({
			color: "black"}),
		    fill: new ol.style.Fill({
			color: "#FEFF04"
		    })})}),
	    "query": {
		"aggs": {
		    "regiao": {
			"terms": {
			    "field": "i_desc_radf_res.keyword",
			    "size": 100,
			    "order": {"_key": "desc"}
			},
			"aggs": {
			    "ano": {
				"terms": {
				    "field": "i_ano_diag",
				    "size": 25,
				    "order": {"_key": "asc"}
				},
				"aggs": {
				    "semana": {
					"terms": {
				                "script": {
				                  "source": "doc['i_data_diag'].date.monthOfYear + '_' + doc['i_semana_diag'].value",
				                  "lang": "painless"
				                },
				                "size": 60,
				                "order": {
				                  "_key": "asc"
				                }
				              },
					"aggs": {"qtd": {"sum": {"field": "i_qtd_agravo"}}}
				    }
				}
			    }
			}
		    }
		},
		"docvalue_fields": [],
		"query": {
		    "bool": {
			"must": [
			    {"match_all": {}},
			    {"match_phrase": {"i_desc_uf_res": {"query": "DF"}}}
			    ]
		    }
		}
	    }
	},
	"aidsdiagsem":{
	    "controles": {
		"intervalo": 400,
		"fillcolor": "#FEFF04",
		"maxsize": 400
	    },
	    "title": "Casos diagnosticados de aids por semana epidemiol&oacute;gica",
	    "tag": "Aids, casos diagnosticados",
	    "index": "aids_consolidado_new",
	    "typedata": "semana",
	    "name": "aidsdiagsem",
	    "keys": [],
	    "typeregion": "ra",
	    "maxsize": 0,
	    "style": new ol.style.Style({
		image: new ol.style.Circle({
		    radius: 0,
		    stroke: new ol.style.Stroke({
			color: "black"}),
		    fill: new ol.style.Fill({
			color: "#FEFF04"
		    })})}),
	    "query": {
		"aggs": {
		    "regiao": {
			"terms": {
			    "field": "i_desc_radf_res.keyword",
			    "size": 100,
			    "order": {"_key": "desc"}
			},
			"aggs": {
			    "ano": {
				"terms": {
				    "field": "i_ano_diag",
				    "size": 25,
				    "order": {"_key": "asc"}
				},
				"aggs": {
				    "semana": {
					"terms": {
				                "script": {
				                  "source": "doc['i_data_diag'].date.monthOfYear + '_' + doc['i_semana_diag'].value",
				                  "lang": "painless"
				                },
				                "size": 60,
				                "order": {
				                  "_key": "asc"
				                }
				              },
					"aggs": {"qtd": {"sum": {"field": "i_qtd_agravo"}}}
				    }
				}
			    }
			}
		    }
		},
		"docvalue_fields": [],
		"query": {
		    "bool": {
			"must": [
			    {"match_all": {}},
			    {"match_phrase": {"i_desc_uf_res": {"query": "DF"}}}
			    ]
		    }
		}
	    }
	},
	"dengueprimsintsem":{
	    "controles": {
		"intervalo": 1000,
		"fillcolor": "#FEFF04",
		"maxsize": 2000
	    },
	    "title": "Casos de dengue por semana epidemiol&oacute;gica e ano dos primeiros sintomas",
	    "tag": "Dengue",
	    "index": "dengue_new",
	    "typedata": "semana",
	    "name": "dengueprimsintsem",
	    "keys": [],
	    "typeregion": "ra",
	    "maxsize": 0,
	    "style": new ol.style.Style({
		image: new ol.style.Circle({
		    radius: 0,
		    stroke: new ol.style.Stroke({
			color: "black"}),
		    fill: new ol.style.Fill({
			color: "#FEFF04"
		    })})}),
	    "query": {
		"aggs": {
		    "regiao": {
			"terms": {
			    "field": "i_desc_radf_res.keyword",
			    "size": 100,
			    "order": {"_key": "desc"}
			},
			"aggs": {
			    "ano": {
				"terms": {
				    "field": "i_ano_prim_sintomas",
				    "size": 25,
				    "order": {"_key": "asc"}
				},
				"aggs": {
				    "semana": {
					"terms": {
				                "script": {
				                  "source": "doc['i_data_prim_sintomas'].date.monthOfYear + '_' + doc['i_semana_prim_sintomas'].value",
				                  "lang": "painless"
				                },
				                "size": 60,
				                "order": {
				                  "_key": "asc"
				                }
				              },
					"aggs": {"qtd": {"sum": {"field": "i_qtd_casos"}}}
				    }
				}
			    }
			}
		    }
		},
		"docvalue_fields": [],
		"query": {
		    "bool": {
			"must": [
			    {"match_all": {}},
			    {"match_phrase": {"i_desc_uf_res": {"query": "DF"}}},
			    {"range": {"i_ano_prim_sintomas": {"gte": 2006,"lt": 2050}}}
			    ]
		    }
		}
	    }
	}
};