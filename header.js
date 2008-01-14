function beginUpload(sid) {
      document.postform.submit();
        var pb = document.getElementById("progress");
        var pb2 = document.getElementById("matt");
        var pb3 = document.getElementById("matt2");
        pb.parentNode.parentNode.style.display='block';
        pb2.style.display='none';
        pb3.style.display='none';
        pb.parentNode.parentNode.style.display='block';
        new ProgressTracker(sid,{
                progressBar: pb,
                onFailure: function(msg) {
                        Element.hide(pb.parentNode);
                        alert(msg);
                }
        });
    }

var ie=document.all;
var nn6=document.getElementById&&!document.all;

var isdrag=false;
var x,y;
var dobj;

function movemouse(e)
{
  if (isdrag)
  {
    if ((nn6 ? ty + e.clientY - y : ty + event.clientY - y) > -57){
        dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x;
        dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y;
    } else {

        dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x;
        dobj.style.top  = -56;

    }
    return false;
  }
}

function selectmouse(e)
{
  var fobj       = nn6 ? e.target : event.srcElement;
  var topelement = nn6 ? "HTML" : "BODY";

  while (fobj.tagName != topelement && fobj.className != "dragme")
  {
    fobj = nn6 ? fobj.parentNode : fobj.parentElement;
  }

  if (fobj.className=="dragme")
  {
    isdrag = true;
    dobj = fobj;
    tx = parseInt(dobj.style.left+0);
    ty = parseInt(dobj.style.top+0);
    x = nn6 ? e.clientX : event.clientX;
    y = nn6 ? e.clientY : event.clientY;
    document.onmousemove=movemouse;
    return false;
  }
}

document.onmousedown=selectmouse;
document.onmouseup=new Function("isdrag=false");


function hideItem(obj) {
    var el = document.getElementById(obj);
    if (el) {
    el.style.display = 'none';
    }
}
messageObj = new DHTML_modalMessage();	// We only create one object of this class
messageObj.setShadowOffset(5);	// Large shadow


function displayMessage(url)
{

	messageObj.setSource(url);
	messageObj.setCssClassMessageBox('modalDialog_contentDiv');
	messageObj.setSize(480,480);
	messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
	messageObj.display();
}
function displaySmallMessage(url)
{

	messageObj.setSource(url);
	messageObj.setCssClassMessageBox('modalDialog_contentDiv');
	messageObj.setSize(330,200);
	messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
	messageObj.display();
}
function displayLargeMessage(url)
{

	messageObj.setSource(url);
	messageObj.setCssClassMessageBox('modalDialog_contentDiv');
	messageObj.setSize(500,340);
	messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
	messageObj.display();
}
function displayStaticMessage(messageContent,cssClass)
{
	messageObj.setHtmlContent(messageContent);
	messageObj.setSize(300,150);
	messageObj.setCssClassMessageBox(cssClass);
	messageObj.setSource(false);	// no html source since we want to use a static message here.
	messageObj.setShadowDivVisible(false);	// Disable shadow for these boxes
	messageObj.display();


}

function closeMessage()
{
	messageObj.close();
}

