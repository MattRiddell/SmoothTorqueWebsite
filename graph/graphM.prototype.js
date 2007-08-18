/**

 * Copyright (c) Dragan Bajcic http://dragan.yourtree.org

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

 * @author Dragan Bajcic
 * @link http://dragan.yourtree.org/code/ajax-mgraph/
 * 
 */

function draw(){
	this.htmlString='';
	this.htmlString='<div id="ag-head"></div>';
	this.htmlString+='<div id="ag-gLeft"><div id="ag-graph"><div id="ag-dump">No Data to display :]</div></div>';
	this.htmlString+='<div id="x-title-1" class="x-title"></div><div id="x-title-2" class="x-title"></div><div id="x-title-3" class="x-title"></div><div id="x-title-4" class="x-title"></div><div id="x-title-5" class="x-title"></div><div id="x-title-6" class="x-title"></div><div id="x-title-7" class="x-title"></div><div id="x-title-8" class="x-title"></div><div id="x-title-9" class="x-title"></div><div id="x-title-10" class="x-title"></div><div id="x-title-11" class="x-title"></div><div id="x-title-12" class="x-title"></div></div>';
	this.htmlString+='<div id="ag-gSidebar"><h3>Stats</h3><div id="ag-max"></div><div id="ag-min"></div><div id="ag-avg"></div><h4 id="note-title">Note</h4><div id="note-content">Little note about this graph goes here.. </div></div>';

	$(agGraphDivID).innerHTML=this.htmlString;
	$(agGraphDivID).setStyle({height:'300px'});
	$('ag-dump').innerHTML='Loading...';
	
	if(agGraphShowNote=='yes'){
		$('note-title').setStyle({display:'block'});
		$('note-content').setStyle({display:'block'});
		$('note-title').update(agGraphNoteTitle);
		$('note-content').update(agGraphNoteText);
	}		
	new Ajax.Request('number_generator.php', {
	

	// if we get any response:
	onSuccess: function(transport) { //
		
		var RESP = eval('(' + transport.responseText + ')');
	  $('ag-dump').innerHTML='';
	  var g = new Graph(RESP.num);
		this.i=1;
		RESP.xTitle.each(function(itm){
			$('x-title-'+i).update(itm);
			i=i+1;
		});

	 	$('ag-dump').innerHTML+=g.returnHTML;
		$('ag-head').update('Move mouse over graph bars to show values')
		$('ag-max').update('Max: '+g.max);	 
		$('ag-min').update('Min: '+g.min);
		$('ag-avg').update('Avg: '+g.avg);
	}// onSuccess end
			
	});// Ajax.Request end
		
}	

var gShow=function(elmnt,num,cnt){
	
	$('ag-head').update('value: '+num);
	$(elmnt).setStyle({background:'url(bar-active-bg.png) repeat-y'});
	$('x-title-'+cnt).setStyle({background:'#e1e1e1',color:'#414141'});
	
}

var gHide=function(elmnt,cnt){
	
	$('ag-head').update('');
	$(elmnt).setStyle({background:'url(bar-bg.png) repeat-y'});
	$('x-title-'+cnt).setStyle({background:'#ffffff',color:'#898989'});

}


var Graph = Class.create();

Graph.prototype = {
	initialize:function(data){
		this.data=data;
		this.max=Array.max(data);
		this.min=Array.min(data);
		this.avg=0;
		this.returnHTML=this.unfold(data);
		
	},
	
	unfold: function(da){
		this.len= da.length;
		this.tmp='';
		for (i=0;i<this.len;i++){
			
			this.scaled=Math.round(200/this.max * da[i]);
			this.mtop=200-this.scaled;
			this.counter=i+1;
			this.avg+=da[i];
			this.tmp+='<div style="height:'+this.scaled+'px;margin-top:'+this.mtop+'px;" class="m-bar" onmouseover="gShow(this,'+da[i]+','+this.counter+');" onmouseout="gHide(this,'+this.counter+');"></div>';
									
		}
		this.avg=Math.round(this.avg/this.counter);
		
		this.storeAvg(this.avg);
		return this.tmp;	
	
	},
	storeAvg:function(data){
		this.avg=data;
		
	}
}

Array.max = function( array ){
    return Math.max.apply( Math, array );
};

Array.min = function( array ){
    return Math.min.apply( Math, array );
};





