if(typeof(i3GEOF)==='undefined'){var i3GEOF={}}i3GEO.login={divnomelogin:"i3GEONomeLogin",recarrega:false,funcaoLoginOk:null,funcaoLoginErro:null,anulaCookie:function(){i3GEO.util.insereCookie("i3geocodigologin","",0);i3GEO.util.insereCookie("i3geousuariologin","",0);i3GEO.util.insereCookie("i3geousuarionome","",0);i3GEO.util.insereCookie("i3GeoLogin","",0)},verificaCookieLogin:function(){var a=i3GEO.util.pegaCookie("i3geocodigologin"),b=i3GEO.util.pegaCookie("i3geousuarionome");if(a&&b&&a!=""&&b!=""){return true}else{return false}},verificaOperacao:function(operacao,locaplic,funcaoOk,tipo,funcaoErro){var p="",cp,temp,resultado=true;if(!i3GEO.login.verificaCookieLogin()){if(!funcaoErro){alert("Login...!")}else{funcaoErro.call()}return false}if(!locaplic){locaplic=i3GEO.configura.locaplic}temp=function(retorno){if(retorno.data=="naopermitido"){alert($trad("naoPermitido"));return false}if(retorno.data=="sim"){resultado=true}else{resultado=false}if(resultado===true){if(funcaoOk&&funcaoOk!=""){funcaoOk.call()}}else{if($i(i3GEO.login.divnomelogin)){$i(i3GEO.login.divnomelogin).innerHTML="";i3GEO.login.anulaCookie()}if(funcaoErro&&funcaoErro!=""&&resultado===false){funcaoErro.call()}}return resultado};if(!tipo||tipo==="sessao"){p=locaplic+"/admin/php/login.php?funcao=validaoperacaosessao"}if(tipo==="banco"){p=locaplic+"/admin/php/login.php?funcao=validaoperacaobanco"}cp=new cpaint();cp.set_response_type("JSON");cp.set_transfer_mode("POST");cp.call(p,"login",temp,"&operacao="+operacao)},dialogo:{abreLogin:function(locaplic){var js;if(!locaplic||locaplic==""){locaplic=i3GEO.configura.locaplic}if(typeof(i3GEOF.loginusuario)==='undefined'){js=locaplic+"/ferramentas/loginusuario/dependencias.php";i3GEO.util.scriptTag(js,"i3GEOF.loginusuario.start()","i3GEOF.loginusuario_script()")}else{i3GEOF.loginusuario.start()}},abreLogout:function(){var r=confirm($trad("x26"));if(r==true){i3GEO.login.anulaCookie();if(i3GEO.janela){i3GEO.janela.destroi("i3GEOF.loginusuario")}if($i(i3GEO.login.divnomelogin)){$i(i3GEO.login.divnomelogin).innerHTML=""}if(i3GEO.login.recarrega===true){document.location.reload()}if(i3GEO.parametros){i3GEO.parametros.editor="nao";if(i3GEO.catalogoMenus){i3GEO.catalogoMenus.listaMenus()}}}}}};