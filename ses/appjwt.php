<?php
//http://localhost:8019/i3geo/ses/appjwt.php?jwt=eyJhbGciOiJzaGExIiwidHlwIjoiSldUIn0.eyJzdWIiOiJlZG1hci5tb3JldHRpIiwiaXNzIjoiV1AiLCJhdWQiOiJlZGl0b3JhYnJhbmdlbmNpYXVicyIsImlhdCI6MTU0MTUxMzA3MiwiZXhwIjoxNTQxNTEzMTMyfQ.2db2a161b2d85be57024b52fa6900df03ce3f5d4
include("secretappjwt.php");
$secretappjwt = \ses\secretappjwt\getSecret();
$decode64 = function ($data) {
    return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
};
$jwt = explode(".", $_POST["jwt"]);
if (sha1($jwt[0] . $jwt[1] . $secretappjwt) != $jwt[2]) {
    echo "Acesso n&atilde;o permitido";
    exit();
}
$header = json_decode($decode64($jwt[0]));
//var_dump($header);exit;
$payload = json_decode($decode64($jwt[1]));
//var_dump($payload);exit;
if (time() > $payload->exp) {
    echo "Acesso n&atilde;o permitido - seu tempo expirou";
    exit;
}
if ($payload->sub == "") {
    echo "Acesso n&atilde;o permitido - usuário não definido";
    exit();
}

define("i3GEOSES","ok");
// coletores de ponto
if ($payload->aud == "coletores") {
    include("coletores.php");
}
// edicao dos limites das areas de abrangencia das UBS
if ($payload->aud == "editorabrangenciaubs") {
    $usuario = "editorabrangenciaubs";
    $senha = "EditorAbrangEnciaUBS0912";
    $login = loginI3geo($usuario, $senha);
    if($login == true){
        ob_clean();
        include("editorabrangenciaubs.phtml");
    } else {
        echo "Erro no login do i3Geo";
    }
}
function loginI3geo($usuario, $senha)
{
    $teste = autenticaUsuario($usuario, $senha);
    if ($teste != false) {
        $_SESSION["usuario"] = $usuario;
        $_SESSION["id_usuario"] = $teste["usuario"]["id_usuario"];
        $_SESSION["senha"] = $senha;
        $_SESSION["papeis"] = $teste["papeis"];
        $_SESSION["operacoes"] = $teste["operacoes"];
        $_SESSION["gruposusr"] = $teste["gruposusr"];
        $fingerprint = 'I3GEOLOGIN' . $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['fingerprint'] = md5($fingerprint . session_id());
        return true;
    }
}
//funcao copiada do original do i3Geo e adaptada para usar aqui
function autenticaUsuario($usuario, $senha)
{
    error_reporting(0);
    include ("../ms_configura.php");
    error_reporting(0);
    include ($conexaoadmin);
    error_reporting(0);
    include ("../admin/php/funcoesAdmin.php");
    error_reporting(0);

    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    $_SESSION = array();
    ini_set("session.use_cookies", 0);
    session_name("i3GeoLogin");
    session_start();
    session_regenerate_id(true);
    $_SESSION["locaplic"] = $locaplic;
    $_SESSION["conexaoadmin"] = $conexaoadmin;
    $esquemaadmin = str_replace(".", "", $esquemaadmin) . ".";
    $_SESSION["esquemaadmin"] = $esquemaadmin;

    $senhamd5 = md5($senha);
    if (function_exists("password_hash")) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    }
    // verifica se a senha e uma string ou pode ser um md5
    $ok = false;
    $dados = array();
    // por causa das versoes antigas do PHP
    if (strlen($senha) == 32 || ! function_exists("password_hash")) {
        $dados = \admin\php\funcoesAdmin\pegaDados("select senha,login,id_usuario,nome_usuario from " . $esquemaadmin . "i3geousr_usuarios where login = '$usuario' and senha = '$senhamd5' and ativo = 1", $dbh, false);
        if (count($dados) == 1 && $dados[0]["senha"] == $senhamd5 && $dados[0]["login"] == $usuario) {
            $ok = true;
        }
    } else {
        $usuarios = \admin\php\funcoesAdmin\pegaDados("select senha,id_usuario,nome_usuario from " . $esquemaadmin . "i3geousr_usuarios where login = '$usuario' and ativo = 1", $dbh, false);
        if (count($usuarios) == 1 && password_verify($senha, $usuarios[0]["senha"])) {
            $ok = true;
            $dados[] = array(
                "id_usuario" => $usuarios[0]["id_usuario"],
                "nome_usuario" => $usuarios[0]["nome_usuario"]
            );
        }
        $usuarios = null;
    }
    if ($ok == true) {
        $pa = \admin\php\funcoesAdmin\pegaDados("select * from " . $esquemaadmin . "i3geousr_papelusuario where id_usuario = " . $dados[0]["id_usuario"], $dbh, false);
        $op = \admin\php\funcoesAdmin\pegadados("SELECT O.codigo, PU.id_usuario FROM " . $esquemaadmin . "i3geousr_operacoes AS O JOIN " . $esquemaadmin . "i3geousr_operacoespapeis AS OP ON O.id_operacao = OP.id_operacao JOIN " . $esquemaadmin . "i3geousr_papelusuario AS PU ON OP.id_papel = PU.id_papel	WHERE id_usuario = " . $dados[0]["id_usuario"], $dbh, false);
        $gr = \admin\php\funcoesAdmin\pegadados("SELECT * from " . $esquemaadmin . "i3geousr_grupousuario where id_usuario = " . $dados[0]["id_usuario"], $dbh, false);
        $operacoes = array();
        foreach ($op as $o) {
            $operacoes[$o["codigo"]] = true;
        }
        $papeis = array();
        foreach ($pa as $p) {
            $papeis[] = $p["id_papel"];
        }
        $gruposusr = array();
        foreach ($gr as $p) {
            $gruposusr[] = $p["id_grupo"];
        }
        $r = array(
            "usuario" => $dados[0],
            "papeis" => $papeis,
            "operacoes" => $operacoes,
            "gruposusr" => $gruposusr
        );
        $dbh = null;
        $dbhw = null;
        return $r;
    } else {
        $dbh = null;
        $dbhw = null;
        return false;
    }
}

function logoutUsuario()
{
    $_COOKIE = array();
    $_SESSION = array();
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}
// funcao utilizada no WP
// esta aqui apenas para referencia
/*
 * function jwti3geo_func( $attr ) {
 * if(!is_user_logged_in()){
 * return "Usuário não autenticado";
 * }
 * error_reporting(0);
 * $userinfo = get_currentuserinfo();
 * $secret = "xxxxxxx";
 * $header = array(
 * "alg"=>"sha1",
 * "typ"=>"JWT"
 * );
 * $payload = array(
 * "sub"=>$userinfo->user_login,
 * "iss"=>"WP",
 * "aud"=>$attr["appdestination"],
 * "iat"=>time(),
 * "exp"=>time() + 60
 * );
 * $decode64 = function($data){
 * return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
 * };
 * $encode64 = function($data){
 * return rtrim( strtr( base64_encode( $data ), '+/', '-_'), '=');
 * };
 * $header = $encode64(json_encode($header));
 * $payload = $encode64(json_encode($payload));
 *
 * $sha = sha1($header.$payload.$secret);
 * if($_SERVER['SERVER_NAME'] == "desenv.saude.df.gov.br"){
 * $url = "http://dmapas.saude.df.gov.br/i3geo/ses/appjwt.php?jwt=";
 * } else {
 * $url = "http://mapas.saude.df.gov.br/i3geo/ses/appjwt.php?jwt=";
 * }
 * return $url.$header.".".$payload.".".$sha;
 * }
 */
?>