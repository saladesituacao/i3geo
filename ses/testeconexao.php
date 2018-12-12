<?php
//$dbh usuario somente leitura
//$dbhw usuario com direito de escrita
//esse arquivo esta no .git/info/exclude
if (getenv('DBSALA') == false) {
    $_parametros = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/../configi3geoses.ini", true);
    $_parametros = $_parametros["i3geosesconfig"];
} else {
    $_parametros = array(
        "host"=>getenv('HOST'),
        "port"=>getenv('PORT'),
        "dbstage"=>getenv('DBSTAGE'),
        "dbsala"=>getenv('DBSALA'),
        "usersala"=>getenv('USERSALA'),
        "userapp"=>getenv('USERAPP'),
        "userstick"=>getenv('USERSTICK'),
        "passwordsala"=>getenv('PASSWORDSALA'),
        "passwordapp"=>getenv('PASSWORDAPP'),
        "passwordstick"=>getenv('PASSWORDSTICK')
    );
}
try
{
    $dbh = new PDO('pgsql:dbname='.$_parametros["dbsala"].';user='.$_parametros["usersala"].';password='.$_parametros["passwordsala"].';host='.$_parametros["host"]);
    $dbhw = new PDO('pgsql:dbname='.$_parametros["dbsala"].';user='.$_parametros["userstick"].';password='.$_parametros["passwordstick"].';host='.$_parametros["host"]);
    $dbhstage = new PDO('pgsql:dbname='.$_parametros["dbstage"].';user='.$_parametros["usersala"].';password='.$_parametros["passwordsala"].';host='.$_parametros["host"]);
    $_parametros = null;
}
catch (PDOException $e)
{
    print_r($e);
    die();
}
echo "Conexao OK";
$convUTF = true;
?>
