<?php
namespace ses\Util;

function verificaSession($sid){
    session_name("i3GeoPHP");
    session_id($sid);
    session_start(['read_and_close'  => true]);
    if(!isset($_SESSION["map_file"]) || empty($_SESSION["map_file"])){
        return false;
    } else {
        return true;
    }
}
function getSession($sid){
    session_name("i3GeoPHP");
    session_id($sid);
    session_start(['read_and_close'  => true]);
    if(!isset($_SESSION["map_file"]) || empty($_SESSION["map_file"])){
        return false;
    } else {
        return true;
    }
}
?>