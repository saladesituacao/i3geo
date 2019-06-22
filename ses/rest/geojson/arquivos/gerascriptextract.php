<?php
exit;
$arq = "script.txt";
unlink($arq);
$fp = fopen($arq,"w");
//$dbh = new PDO('pgsql:dbname=dbsalasituacao;user=dbsalasituacao;password=xdbsalasituacaox;host=10.233.66.41');
$t = 'ogr2ogr -f "GeoJSON" regioes_de_saude.geojson -simplify 0.01 -lco COORDINATE_PRECISION=4 PG:"host=10.233.66.41 user=dbsalasituacao dbname=dbsalasituacao password=xdbsalasituacaox" -sql "SELECT * FROM dbauxiliares.tb_class_regiao"';

fwrite($fp,$t."\r\n");

fclose($fp);
echo "Foi gerado o script na pasta geojson, deve ser executado manualmente";
?>
