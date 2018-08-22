window.noticen=0;/*提示数量*/
var nowpage=0;
var scrollflag=true;
var time=0;
var flag=false;
var timer;
var submiting=false;
function e(){
	$.ajax({
        type: "post",
        url: "x.php",
        data: {t:'rqlog'},
        dataType: "text",//回调函数接收数据的数据格式
        success: function(msg){
          var datat='';
          if(msg!=''){
            datat = eval("("+msg+")");    //将返回的json数据进行解析，并赋给data
          }
		  data=datat;
		  if(data.result=='ok'){
			  h(data.r,'m');
		  }else{
			  notice('需要走个程序哦~');
			  setTimeout(function(){window.open('./l/','_self');},1500);
		  }
        },
        error:function(msg){
			notice('服务器通信失败QAQ');
        }
      });
}
function g(s){
	$.ajax({
        type: "post",
        url: "x.php",
        data: {t:'rqpage',st:s},
        dataType: "text",//回调函数接收数据的数据格式
        success: function(msg){
          var datat='';
          if(msg!=''){
            datat = eval("("+msg+")");    //将返回的json数据进行解析，并赋给data
          }
		  data=datat;
		  if(data.result=='ok'){
			  hd(data.r,'l');  
			  nowpage=data.n;
			  if(data.rs=='nomore'){
				  document.getElementById('ma').style.display='none';
			  }
		  }else{
			  notice('加载失败...');
		  }
        },
        error:function(msg){
			notice('服务器通信失败QAQ');
        }
      });
}
function d(s){
	if(confirm('真的要删除嘛...')){
	$.ajax({
        type: "post",
        url: "x.php",
        data: {t:'rqdel',pt:s},
        dataType: "text",//回调函数接收数据的数据格式
        success: function(msg){
          var datat='';
          if(msg!=''){
            datat = eval("("+msg+")");    //将返回的json数据进行解析，并赋给data
          }
		  data=datat;
		  if(data.result=='ok'){
			  gc();
			  document.getElementById('ma').style.display='block';
		  }else{
			  notice('删除失败...');
		  }
        },
        error:function(msg){
			notice('服务器通信失败QAQ');
        }
      });
	}
}
function s(){
	if(!submiting){
	document.getElementById("btn").disabled = true;
	submiting=true;
	$.ajax({
        type: "post",
        url: "x.php",
        data: {t:'submit',c:document.getElementById('c').value},
        dataType: "text",//回调函数接收数据的数据格式
        success: function(msg){
          var datat='';
          if(msg!=''){
            datat = eval("("+msg+")");    //将返回的json数据进行解析，并赋给data
          }
		  data=datat;
		  if(data.result=='ok'){
			  hs(data.r,'l');
			  document.getElementById('c').value='';
			  submiting=false;
			  document.getElementById("btn").disabled = false;
		  }else{
			  notice('提交失败：');
			  notice(data.m);
			  submiting=false;
			  document.getElementById("btn").disabled = false;
		  }
        },
        error:function(msg){
			notice('服务器通信失败QAQ');
        }
      });
	}
}
function gc(){
	nowpage=0;
	$.ajax({
        type: "post",
        url: "x.php",
        data: {t:'getc'},
        dataType: "text",//回调函数接收数据的数据格式
        success: function(msg){
          var datat='';
          if(msg!=''){
            datat = eval("("+msg+")");    //将返回的json数据进行解析，并赋给data
          }
		  data=datat;
		  if(data.result=='ok'){
			  h(data.r,'l'); 
			  nowpage=data.n;
		  }else{
			  if(data.m!==null){
			  notice(data.m);
			  }
		  }
        },
        error:function(msg){
			notice('服务器通信失败QAQ');
        }
      });
}
function h(c,e){
	$('#'+e).animate({opacity:'0'},500,function(){
		$('#'+e).html(c);
		setTimeout(function(){
			$('#'+e).animate({opacity:'1'},500);
		},500);
	});
}
function hd(c,e){
	$('#'+e).animate({opacity:'0'},100,function(){
		$('#'+e).html($('#'+e).html()+c);
		setTimeout(function(){
			$('#'+e).animate({opacity:'1'},100);
		},200);
	});
}
function hs(c,e){
	$('#'+e).animate({opacity:'0'},100,function(){
		$('#'+e).html(c+$('#'+e).html());
		setTimeout(function(){
			$('#'+e).animate({opacity:'1'},100);
		},200);
	});
}
function u(){
	var a=confirm('确定 --------> 修改密码\n取消 ---------> 登出');
	if(a){
		window.open('./l/?reset','_self');
	}else{
		window.open('./l/?logout','_self');
	}
}
function notice(s){
	window.noticen=parseInt(window.noticen)+1;
	var nownt=window.noticen;
	var div=document.getElementById('t');
	var h3=document.createElement("h3");
	h3.id='n'+nownt;
	h3.className='n';
	h3.style.opacity='0';
	h3.style.width=20+20*s.length+'px';
	div.appendChild(h3);
	document.getElementById('n'+nownt).innerHTML=s;
	document.getElementById('n'+nownt).style.display='block';
	document.getElementById('n'+nownt).style.top=40+5*(parseInt(window.noticen)-1)+'%';
	$('#n'+nownt).animate({opacity:'1'},500,function(){
		setTimeout(function(){
			$('#n'+nownt).animate({opacity:'0'},500,function(){
				div.removeChild(h3);
				window.noticen=parseInt(window.noticen)-1;
			});
		},1500);
	});
}