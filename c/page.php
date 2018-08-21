<div class='f'>
<a href='javascript:void(0);' onclick='u()'><h3>[usr]</h3></a>
<p class='tx'><input type='text' id='search' placeholder='搜索'></input></p>
<p><textarea rows="3" id='c' placeholder='想什么呢?'></textarea></p>
<p><input type='button' class='b' onclick='s()' value='发射~'></input></p>
</div>
<div class='l' id='l'></div>
<p><a href='javascript:void(0);' onclick='g(nowpage);' class='ma' id='ma'>加载更Door♂</a></p>
<script>setTimeout(function(){gc()},500);$("#search").bind("input porpertychange",function(){time=0;document.getElementById("search").focus();if(!flag){flag=true;timer=setInterval(function(){if(time<1){time+=1}else{var a=document.getElementById("search").value;if(a==null||String(a)=="undefined"||a.match(/^\s*$/)){gc();document.getElementById('ma').style.display='block';}else{$.ajax({type:"post",url:"x.php",data:{search:a,t:"search"},dataType:"text",success:function(msg){var datat="";if(msg!=""){datat=eval("("+msg+")")}data=datat;if(data.r==null||String(data.r)=="undefined"||data.r.match(/^\s*$/)){h("<h1>没有搜索到什么诶..</h1>","l")}else{h(data.r,'l');}document.getElementById('ma').style.display='none';},error:function(msg){alert("失去了与服务器的连接OAO\n查询失败")}})}clearInterval(timer);flag=false;time=0}},500)}});</script>