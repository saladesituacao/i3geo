<?php
    include("../../ms_configura.php");
	$mapObj = ms_newMapObj("../../temas/unidades.map");
	$layer = $mapObj->getlayerbyname("unidades");
	$stringconexao = $postgis_mapa["ses"];
	$layer->set("connection",$stringconexao);
	$layer->open();
	$ret = ms_newRectObj();
	$ret->setextent(-48.2851,-16,-47.30,-15.5);
	//$colunas = $layer->getItems();
	$status = $layer->whichShapes($ret);
	//$categorias = array(array(),array());
	$cat = array();
	if ($status == 0) {
	    while ($s = $layer->nextShape()) {
	        $ponto = $s->getCentroid();
	        $ext = array($ponto->x,$ponto->y);
	        $nome = $s->values["nome_fantasia"];
	        $tipo = $s->values["tipo_unidade"];
	        //array_push($categorias[0],$tipo);
	        //array_push($categorias[1],array("nome"=>$nome,"ext"=>$ext,"id"=>$s->values["id"]));
	        array_push($cat,array("nome"=>$nome,"ext"=>$ext,"id"=>$s->values["id"]));
	    }
	} else {
	    echo json_encode(array(
	        "status" => false,
	        "error" => null,
	        "data" => array()
	    ));
	}
	//print_r( $colunas );
	/*
	Array
	(
	[0] => localizacao
	[1] => regiaoadministrativa
	[2] => nome
	[3] => regiaodesaude
	[4] => cnes
	[5] => nomefantasia
	[6] => endereco
	[7] => cep
	[8] => cidade
	[9] => tipodeubs
	[10] => tipoubs77
	[11] => latitude
	[12] => longitude
	[13] => tamanho
	[14] => aproxlocal
	[15] => id
	)
	*/
	//$cat = array_combine_($categorias[0],$categorias[1]);
	header('Content-Type: application/json');
	echo json_encode(array(
		"status" => true,
		"error" => null,
		"data" => array(
			"categorias" => $cat
		)
	));
	function array_combine_($keys, $values){
		$result = array();
		foreach ($keys as $i => $k) {
			$result[$k][] = $values[$i];
		}
		array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));
		return    $result;
	}
?>
