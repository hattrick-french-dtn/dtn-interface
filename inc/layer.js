// Pop info on demand - 

window.popStatus = "";
var Pop_Status = new Array();
var Pop_ID = new Array();
var layer_name = 'layer';
var nb_btn = 0;

var btn_close = '<div align="right"><a class="linktipbtn" onMouseover="return Status()" href="javascript:void(0);" onClick="clickPop(\'LayerPoS\',event)">[X]</a></div><br>';
var btn_view = '<a class="linktip" href="javascript:void(0)" onMouseover="clickPop(\'LayerPoS\',event)" onMouseOut="//hidePop(\'LayerPoS\',event)">BtnView</a><br>';
var btn_view_val = 'View ';

document.writeln( '<STYLE TYPE="text/css"><!--',
    'DIV.pop {',
    ' position:absolute; visibility:hidden; display:block; width:auto; height:auto; left:0; top:0;',
    ' background-color:#ffffff; layer-background-color:white;',
    ' font-size: 11px; color: #000000; font-family:Arial,Verdana; padding:5px;',
    ' border-style:solid; border-width:1px; border-color:#000000; } ',
    'p,td,select,li{color:#000000;font-size:x-small;font-family:Arial,Helvetica}',
    'A.tip:link { text-decoration:none; cursor:help; color:red;} ',
    'A.tip:visited { text-decoration:none; cursor:help; color:red;} ',
    'A.tip:active { text-decoration:none; cursor:help; color:red;} ',
    'A.tip:hover { color:red; } ',
    'A.linktip:link { color:red; } ',
    'A.linktip:visited { } ',
    'A.linktip:active {  } ',
    'A.linktip:hover { color:red }',
    'A.linktipbtn:link { text-decoration:none;color:black;font-size: 9px;  } ',
    'A.linktipbtn:visited {text-decoration:none;color:black;font-size: 9px;  } ',
    'A.linktipbtn:active { text-decoration:none;color:black;font-size: 9px; } ',
    'A.linktipbtn:hover { text-decoration:none;color:black; font-size: 9px; }',
    '--></STYLE>' );

function SPop( id, text, status, type , href_click) {
	
    var Nb_Pop = Pop_ID.length;
    var ok=0;
    for(i=0;i<Nb_Pop;i++){
    	if(Pop_ID[i] == id){
    		ok=1;
    	}
    }
	  	if(ok == 0){
    		Pop_ID[Nb_Pop] = id;
    		Pop_Status[id]='hidden';
    }
    
	
    status = status || popStatus;
    var cls = 'smliensorange';
    if (status == 'type')  status = null;
    if (status == 'id')  status = id;
    var outstatus = (typeof(status) == 'string')? 'return Status()' : '';
    status = (typeof(status) == 'string')? 'self.status=\''+status+'\';return true' : '';
    
				//if(!type) type='mouse';
				
    if(type=='mouse'){
      href='0';
      Mouse = 'onMouseOver="showPop(\''+id+'\',event);'+status+'"'+' onMouseOut="hidePop(\''+id+'\',event);'+outstatus;
    }else if(type=='mouseclick'){
     	href='togglePop(\''+id+'\')';
     	Mouse = 'onMouseOver="showPop(\''+id+'\',event);'+status+'"'+' onMouseOut="hidePop(\''+id+'\',event);'+outstatus;
    }else if(type=='click'){
     	href='clickPop(\''+id+'\',event)';
     	Mouse = '';
    }
    if(!href_click){href_click="javascript:void(0);";}
    document.write('<A href="'+href_click+'" onClick="'+ href +'" CLASS="'+cls+'"'+
       Mouse +'">'+ text +'</A>');
}

function linkPop( id, text, status, href ) {
    status = status || popStatus;
    if (href) {
        var cls = 'linktip';
        if (status == 'href')  status = null;
    } else {
        var cls = 'tip';
        href = 'javascript:void(togglePop(\''+id+'\'))';
        if (status == 'href')  status = '';
    }
    if (status == 'id')  status = id;
    var outstatus = (typeof(status) == 'string')? 'return Status()' : '';
    status = (typeof(status) == 'string')? 'self.status=\''+status+'\';return true' : '';
    document.write('<A HREF="'+href+'" CLASS="'+cls+'"'+
       ' onMouseOver="showPop(\''+id+'\',event);'+status+'"'+
       ' onMouseOut="hidePop(\''+id+'\',event);'+outstatus+'">'+
       text +'</A>');
}


function btnPop(n)
{
	if (nb_btn<=layer.length-1||n)
	{
		var layerNB =(n)?(layer_name + n):(layer_name + nb_btn);
		var nb =(n)?(n):(nb_btn);

		var layer_op = new Array()
		layer_op = layer[nb].split("|")

		if(layer_op[2]=='0')
			var view_val = '';
		else if(layer_op[2]!='')
			var view_val = layer_op[2];
		else
			var view_val = btn_view_val;

		var cnt = btn_view.replace((eval('/BtnView/')),view_val); // Btn view new txt
		cnt = cnt.replace((eval('/LayerPoS/g')),layerNB); // Pos layer

		document.write(cnt)
		var layerNB =(n)?"":nb_btn++
	}
}

function showPop( id, event ) {
    var x = 10, y = 10;
    if (event) {
        if (document.all) {
            x = document.body.scrollLeft + document.documentElement.scrollLeft + event.clientX + 10;
            y = document.body.scrollTop + document.documentElement.scrollTop + event.clientY + 10;
        } else if (document.layers || document.getElementById) {
            x = event.pageX + 10;
            y = event.pageY + 10;
        }
    }
    removeAllPop();
    putPop(id, x, y);
    if (window.onPopCall)  window.onPopCall(id, event);
}

function hidePop( id, event ) {
    removePop(id);
    if (window.onPopHide)  window.onPopHide(id, event);
}

function putPop( id, x, y ) {
    var l;
    if (document.layers) {
        l = document.layers[id];
        l.left = Math.min(Math.max(x, window.pageXOffset), window.pageXOffset + window.innerWidth - l.clip.width);
        l.top = Math.min(Math.max(y, window.pageYOffset), window.pageYOffset + window.innerHeight - l.clip.height);
        l.visibility = "visible";
        l.zIndex = 100;
    } else if (document.all) {
        l = document.all[id];
        l.style.pixelLeft = Math.min(Math.max(x, document.body.scrollLeft + document.documentElement.scrollLeft), document.body.scrollLeft + document.documentElement.scrollLeft + document.body.clientWidth - l.offsetWidth);
        l.style.pixelTop = Math.min(Math.max(y, document.body.scrollTop + document.documentElement.scrollTop), document.body.scrollTop + document.documentElement.scrollTop + document.body.clientHeight - l.offsetHeight);
        l.style.visibility = "visible";
        l.style.zIndex = 100;
    } else if (document.getElementById) {
        l = document.getElementById(id);
        l.style.left = Math.min(Math.max(x, window.pageXOffset), window.pageXOffset + window.innerWidth - l.offsetWidth) +"px";
        l.style.top = Math.min(Math.max(y, window.pageYOffset), window.pageYOffset + window.innerHeight - l.offsetHeight) +"px";
        l.style.visibility = "visible";
        l.style.zIndex = 100;
    }
    Pop_Status[id] = 'visible';
}

function removePop( id ) {
    if (document.layers) {
        var l = document.layers[id];
    } else if (document.all) {
        var l = document.all[id].style;
    } else if (document.getElementById) {
        var l = document.getElementById(id).style;
    }
    if (!l) return;
    if (!l.fixed){
    	l.visibility = "hidden";
	    Pop_Status[id] = 'hidden';
	}
    Status();
}

function togglePop( id ) {
    if (document.layers) {
        var l = document.layers[id];
    } else if (document.all) {
        var l = document.all[id].style;
    } else if (document.getElementById) {
        var l = document.getElementById(id).style;
    }
    if (!l) return;
    if (l.fixed = !l.fixed)  l.zIndex = 1;
}

function removeAllPop() {
	var Nb_Pop = Pop_ID.length;
	for(i=0;i<Nb_Pop;i++){
		if(Pop_Status[Pop_ID[i]]=='visible'){
			removePop(Pop_ID[i]);
		}
	}
}

function Status()
{
	self.status='';
	return true;
}

function clickPop( id , event ){

	if(Pop_Status[id] == 'hidden'){
		showPop( id , event);
	}else{
		hidePop( id , event);
	}
}

function setupPops()
{
	var style_cnt = '';
	var div_cnt = '';

	var layer_op = new Array();
	for (i=0;i<=layer.length-1;i++){
		layer_op = layer[i].split("|");
		var layerNB = layer_name + i;
		if (layer_op[3]==1)
			var cnt = btn_close.replace((eval('/LayerPoS/')),layerNB) + layer_op[0];
		else
			var cnt = layer_op[0];

		style_cnt += ' #' + layerNB + ' {width:' + layer_op[1] + '}';
   		div_cnt += '<DIV CLASS="pop" ID="' + layerNB + '">' + cnt + '</DIV>\n';

		var next = Pop_ID.length;
		if(!next) next=0;
		Pop_ID[next] = layerNB;
		Pop_Status[layerNB] = 'hidden';

	}
	
    document.writeln('<STYLE TYPE="text/css"><!-- ' + style_cnt + ' --></STYLE>');
    document.writeln(div_cnt);

}
