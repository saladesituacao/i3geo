if(typeof(i3GEO)==='undefined'){var i3GEO={}}i3GEO.social={curtirFacebook:function(url,tipo){if(tipo==="comtotal"){return"<iframe src='http://www.facebook.com/plugins/like.php?href="+url+"&layout=button_count&show_faces=false&width=160&action=like&colorscheme=light&height=21' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:160px; height:21px;' allowTransparency='true'></iframe>"}if(tipo==="semtotal"){return"<iframe src='http://www.facebook.com/plugins/like.php?href="+url+"&layout=button_count&show_faces=false&action=like&colorscheme=light&height=21' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:100px; height:21px;' allowTransparency='true'></iframe>"}},publicarTwitter:function(url,tipo){var re=new RegExp("=","g");url=url.replace(re,'%3d');if(tipo==="comtotal"){return'<iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?count=horizontal&via=i3geo&url='+url+'" style="width:100px; height:21px;"></iframe>'}if(tipo==="semtotal"){return'<iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?count=none&via=i3geo&url='+url+'" style="width:65px; height:21px;"></iframe>'}},compartilhar:function(id,urlcf,urlpt,tipo,locaplic){if(!locaplic){locaplic=i3GEO.configura.locaplic}if(!tipo){tipo="comtotal"}var onde=$i(id),tabela="";if(tipo==="comtotal"){tabela+="<table style='width:250px' ><tr>"}if(tipo==="semtotal"){tabela+="<table style='width:115px' ><tr>"}if(onde||id===""){if(urlpt!==""){tabela+="<td>"+i3GEO.social.publicarTwitter(urlpt,tipo)+"</td>"}if(urlcf!==""){tabela+="<td>"+i3GEO.social.curtirFacebook(urlcf,tipo)+"</td>"}tabela+="</tr></table>";if(id!==""){onde.innerHTML=tabela}return tabela}else{return false}},bookmark:function(link,locaplic){if(!locaplic){locaplic=i3GEO.configura.locaplic}var ins="<img style='cursor:pointer' src='"+locaplic+"/imagens/delicious.gif' onclick='javascript:window.open(\"http://del.icio.us/post?url="+link+"\")' title='Delicious'/> ";ins+="<img style='cursor:pointer' src='"+locaplic+"/imagens/digg.gif' onclick='javascript:window.open(\"http://digg.com/submit/post?url="+link+"\")' title='Digg'/> ";ins+="<img style='cursor:pointer' src='"+locaplic+"/imagens/facebook.gif' onclick='javascript:window.open(\"http://www.facebook.com/sharer.php?u="+link+"\")' title='Facebook'/> ";ins+="<img style='cursor:pointer' src='"+locaplic+"/imagens/stumbleupon.gif' onclick='javascript:window.open(\"http://www.stumbleupon.com/submit?url="+link+"\")' title='StumbleUpon'/>";return ins}};