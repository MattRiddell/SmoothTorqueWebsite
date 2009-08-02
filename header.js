function hideshow(name){
	var opened = "./images/open.png";
	var closed = "./images/closed.png";

	var element = document.getElementById(name);
	var img = document.getElementsByName("img_"+name);
	if(element.style.display == "none"){
		img[0].src = opened;
		element.style.display = "";
	}else{
		img[0].src = closed;
		element.style.display = "none";
	}
}

function whatPaySelected(myval){
    if (document.all2) {
        document.all['mode'].style.display = "visible";
    } else {
        document.getElementById('mode').style.display='';
    }

	if (myval == '0') {
    		if (document.all2) {
        		document.all['mode'].style.display = "none";
    		} else {
        		document.getElementById('mode').style.display='none';
    		}
    		if (document.all2) {
        		document.all['xx1'].style.display = "none";
        		document.all['xx2'].style.display = "none";
        		document.all['xx3'].style.display = "none";
        		document.all['xx4'].style.display = "none";
        		document.all['xx5'].style.display = "none";
        		document.all['xx6'].style.display = "visible";
    		} else {
        		document.getElementById('xx1').style.display='none';
        		document.getElementById('xx2').style.display='none';
        		document.getElementById('xx3').style.display='none';
        		document.getElementById('xx4').style.display='none';
        		document.getElementById('xx5').style.display='none';
        		document.getElementById('xx6').style.display='';
    		}
	} else if (myval == '10') {
    		if (document.all2) {
        		document.all['xx1'].style.display = "visible";
        		document.all['xx2'].style.display = "visible";
        		document.all['xx3'].style.display = "visible";
        		document.all['xx4'].style.display = "visible";
        		document.all['xx5'].style.display = "visible";
        		document.all['xx6'].style.display = "visible";
    		} else {
        		document.getElementById('xx1').style.display='';
        		document.getElementById('xx2').style.display='';
        		document.getElementById('xx3').style.display='';
        		document.getElementById('xx4').style.display='';
        		document.getElementById('xx5').style.display='';
        		document.getElementById('xx6').style.display='';
    		}
	} else if (myval == '11') {
    		if (document.all2) {
        		document.all['xx1'].style.display = "visible";
        		document.all['xx2'].style.display = "visible";
        		document.all['xx3'].style.display = "visible";
        		document.all['xx4'].style.display = "visible";
        		document.all['xx5'].style.display = "visible";
        		document.all['xx6'].style.display = "visible";
    		} else {
        		document.getElementById('xx1').style.display='';
        		document.getElementById('xx2').style.display='';
        		document.getElementById('xx3').style.display='';
        		document.getElementById('xx4').style.display='';
        		document.getElementById('xx5').style.display='';
        		document.getElementById('xx6').style.display='';
    		}
	} else if (myval == '12') {
    		if (document.all2) {
        		document.all['xx1'].style.display = "visible";
        		document.all['xx2'].style.display = "visible";
        		document.all['xx3'].style.display = "visible";
        		document.all['xx4'].style.display = "visible";
        		document.all['xx5'].style.display = "visible";
        		document.all['xx6'].style.display = "visible";
    		} else {
        		document.getElementById('xx1').style.display='';
        		document.getElementById('xx2').style.display='';
        		document.getElementById('xx3').style.display='';
        		document.getElementById('xx4').style.display='';
        		document.getElementById('xx5').style.display='';
        		document.getElementById('xx6').style.display='';
    		}
	} else if (myval == '13') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '14') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '15') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
}  else if (myval == '1') {
    if (document.all2) {
        document.all['xx1'].style.display = "none";/*the number for the call center*/
        document.all['xx2'].style.display = "none";/*press 1 message*/
        document.all['xx3'].style.display = "visible";/*answer machine message*/
        document.all['xx4'].style.display = "none";/*dnc message*/
        document.all['xx5'].style.display = "visible";/*caller id*/
        document.all['xx6'].style.display = "visible";/*imax connected calls*/
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '2') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '3') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '4') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '5') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '6') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '7') {
/*
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
   */
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '8') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['fax'].style.display = "visible";
    } else {
        document.getElementById('fax').style.display='';
    }


    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "none";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='none';
    }
} else if (myval == '9') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '054') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '-1') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "none";
        document.all['xx6'].style.display = "none";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='none';
        document.getElementById('xx6').style.display='none';
    }
}
}
    <!--
    function f_selectAll (s_select) {
var e_select = document.forms['customer'].elements[s_select];
for (var i = 0; i < e_select.options.length; i++)
e_select.options[i].selected = true;
}

    function MoveOption(objSourceElement, objTargetElement)
    {
        var aryTempSourceOptions = new Array();
        var x = 0;

        //looping through source element to find selected options
        for (var i = 0; i < objSourceElement.length; i++) {
            if (objSourceElement.options[i].selected) {
                //need to move this option to target element
                var intTargetLen = objTargetElement.length++;
                objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text;
                objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value;
            }
            else {
                //storing options that stay to recreate select element
                var objTempValues = new Object();
                objTempValues.text = objSourceElement.options[i].text;
                objTempValues.value = objSourceElement.options[i].value;
                aryTempSourceOptions[x] = objTempValues;
                x++;
            }
        }

        //resetting length of source
        objSourceElement.length = aryTempSourceOptions.length;
        //looping through temp array to recreate source select element
        for (var i = 0; i < aryTempSourceOptions.length; i++) {
            objSourceElement.options[i].text = aryTempSourceOptions[i].text;
            objSourceElement.options[i].value = aryTempSourceOptions[i].value;
            objSourceElement.options[i].selected = false;
        }
    }
    //-->


/************************************************************************************************************
*	DHTML modal dialog box
*
*	Created:						August, 26th, 2006
*	@class Purpose of class:		Display a modal dialog box on the screen.
*
*	Css files used by this script:	modal-message.css
*
*	Demos of this class:			demo-modal-message-1.html
*
* 	Update log:
*
************************************************************************************************************/


/**
* @constructor
*/

DHTML_modalMessage = function()
{
	var url;								// url of modal message
	var htmlOfModalMessage;					// html of modal message

	var divs_transparentDiv;				// Transparent div covering page content
	var divs_content;						// Modal message div.
	var iframe;								// Iframe used in ie
	var layoutCss;							// Name of css file;
	var width;								// Width of message box
	var height;								// Height of message box

	var existingBodyOverFlowStyle;			// Existing body overflow css
	var dynContentObj;						// Reference to dynamic content object
	var cssClassOfMessageBox;				// Alternative css class of message box - in case you want a different appearance on one of them
	var shadowDivVisible;					// Shadow div visible ?
	var shadowOffset; 						// X and Y offset of shadow(pixels from content box)
	var MSIE;

	this.url = '';							// Default url is blank
	this.htmlOfModalMessage = '';			// Default message is blank
	this.layoutCss = 'modal-message.css';	// Default CSS file
	this.height = 200;						// Default height of modal message
	this.width = 400;						// Default width of modal message
	this.cssClassOfMessageBox = false;		// Default alternative css class for the message box
	this.shadowDivVisible = true;			// Shadow div is visible by default
	this.shadowOffset = 5;					// Default shadow offset.
	this.MSIE = false;
	if(navigator.userAgent.indexOf('MSIE')>=0) this.MSIE = true;


}

DHTML_modalMessage.prototype = {
	// {{{ setSource(urlOfSource)
    /**
     *	Set source of the modal dialog box
     *
     *
     * @public
     */
	setSource : function(urlOfSource)
	{
		this.url = urlOfSource;

	}
	// }}}
	,
	// {{{ setHtmlContent(newHtmlContent)
    /**
     *	Setting static HTML content for the modal dialog box.
     *
     *	@param String newHtmlContent = Static HTML content of box
     *
     * @public
     */
	setHtmlContent : function(newHtmlContent)
	{
		this.htmlOfModalMessage = newHtmlContent;

	}
	// }}}
	,
	// {{{ setSize(width,height)
    /**
     *	Set the size of the modal dialog box
     *
     *	@param int width = width of box
     *	@param int height = height of box
     *
     * @public
     */
	setSize : function(width,height)
	{
		if(width)this.width = width;
		if(height)this.height = height;
	}
	// }}}
	,
	// {{{ setCssClassMessageBox(newCssClass)
    /**
     *	Assign the message box to a new css class.(in case you wants a different appearance on one of them)
     *
     *	@param String newCssClass = Name of new css class (Pass false if you want to change back to default)
     *
     * @public
     */
	setCssClassMessageBox : function(newCssClass)
	{
		this.cssClassOfMessageBox = newCssClass;
		if(this.divs_content){
			if(this.cssClassOfMessageBox)
				this.divs_content.className=this.cssClassOfMessageBox;
			else
				this.divs_content.className='modalDialog_contentDiv';
		}

	}
	// }}}
	,
	// {{{ setShadowOffset(newShadowOffset)
    /**
     *	Specify the size of shadow
     *
     *	@param Int newShadowOffset = Offset of shadow div(in pixels from message box - x and y)
     *
     * @public
     */
	setShadowOffset : function(newShadowOffset)
	{
		this.shadowOffset = newShadowOffset

	}
	// }}}
	,
	// {{{ display()
    /**
     *	Display the modal dialog box
     *
     *
     * @public
     */
	display : function()
	{
		if(!this.divs_transparentDiv){
			this.__createDivs();
		}

		// Redisplaying divs
		this.divs_transparentDiv.style.display='block';
		this.divs_content.style.display='block';
		this.divs_shadow.style.display='block';
		if(this.MSIE)this.iframe.style.display='block';
		this.__resizeDivs();

		/* Call the __resizeDivs method twice in case the css file has changed. The first execution of this method may not catch these changes */
		window.refToThisModalBoxObj = this;
		setTimeout('window.refToThisModalBoxObj.__resizeDivs()',150);

		this.__insertContent();	// Calling method which inserts content into the message div.
	}
	// }}}
	,
	// {{{ ()
    /**
     *	Display the modal dialog box
     *
     *
     * @public
     */
	setShadowDivVisible : function(visible)
	{
		this.shadowDivVisible = visible;
	}
	// }}}
	,
	// {{{ close()
    /**
     *	Close the modal dialog box
     *
     *
     * @public
     */
	close : function()
	{
		//document.documentElement.style.overflow = '';	// Setting the CSS overflow attribute of the <html> tag back to default.

		/* Hiding divs */
		this.divs_transparentDiv.style.display='none';
		this.divs_content.style.display='none';
		this.divs_shadow.style.display='none';
		if(this.MSIE)this.iframe.style.display='none';

	}
	// }}}
	,
	// {{{ __addEvent()
    /**
     *	Add event
     *
     *
     * @private
     */
	addEvent : function(whichObject,eventType,functionName,suffix)
	{
	  if(!suffix)suffix = '';
	  if(whichObject.attachEvent){
	    whichObject['e'+eventType+functionName+suffix] = functionName;
	    whichObject[eventType+functionName+suffix] = function(){whichObject['e'+eventType+functionName+suffix]( window.event );}
	    whichObject.attachEvent( 'on'+eventType, whichObject[eventType+functionName+suffix] );
	  } else
	    whichObject.addEventListener(eventType,functionName,false);
	}
	// }}}
	,
	// {{{ __createDivs()
    /**
     *	Create the divs for the modal dialog box
     *
     *
     * @private
     */
	__createDivs : function()
	{
		// Creating transparent div
		this.divs_transparentDiv = document.createElement('DIV');
		this.divs_transparentDiv.className='modalDialog_transparentDivs';
		this.divs_transparentDiv.style.left = '0px';
		this.divs_transparentDiv.style.top = '0px';

		document.body.appendChild(this.divs_transparentDiv);
		// Creating content div
		this.divs_content = document.createElement('DIV');
		this.divs_content.className = 'modalDialog_contentDiv';
		this.divs_content.id = 'DHTMLSuite_modalBox_contentDiv';
		this.divs_content.style.zIndex = 100000;

		if(this.MSIE){
			this.iframe = document.createElement('<IFRAME src="about:blank" frameborder=0>');
			this.iframe.style.zIndex = 90000;
			this.iframe.style.position = 'absolute';
			document.body.appendChild(this.iframe);
		}

		document.body.appendChild(this.divs_content);
		// Creating shadow div
		this.divs_shadow = document.createElement('DIV');
		this.divs_shadow.className = 'modalDialog_contentDiv_shadow';
		this.divs_shadow.style.zIndex = 95000;
		document.body.appendChild(this.divs_shadow);
		window.refToModMessage = this;
		this.addEvent(window,'scroll',function(e){ window.refToModMessage.__repositionTransparentDiv() });
		this.addEvent(window,'resize',function(e){ window.refToModMessage.__repositionTransparentDiv() });


	}
	// }}}
	,
	// {{{ __getBrowserSize()
    /**
     *	Get browser size
     *
     *
     * @private
     */
	__getBrowserSize : function()
	{
    	var bodyWidth = document.documentElement.clientWidth;
    	var bodyHeight = document.documentElement.clientHeight;

		var bodyWidth, bodyHeight;
		if (self.innerHeight){ // all except Explorer

		   bodyWidth = self.innerWidth;
		   bodyHeight = self.innerHeight;
		}  else if (document.documentElement && document.documentElement.clientHeight) {
		   // Explorer 6 Strict Mode
		   bodyWidth = document.documentElement.clientWidth;
		   bodyHeight = document.documentElement.clientHeight;
		} else if (document.body) {// other Explorers
		   bodyWidth = document.body.clientWidth;
		   bodyHeight = document.body.clientHeight;
		}
		return [bodyWidth,bodyHeight];

	}
	// }}}
	,
	// {{{ __resizeDivs()
    /**
     *	Resize the message divs
     *
     *
     * @private
     */
    __resizeDivs : function()
    {

    	var topOffset = Math.max(document.body.scrollTop,document.documentElement.scrollTop);

		if(this.cssClassOfMessageBox)
			this.divs_content.className=this.cssClassOfMessageBox;
		else
			this.divs_content.className='modalDialog_contentDiv';

    	if(!this.divs_transparentDiv)return;

    	// Preserve scroll position
    	var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
    	var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);

    	window.scrollTo(sl,st);
    	setTimeout('window.scrollTo(' + sl + ',' + st + ');',10);

    	this.__repositionTransparentDiv();


		var brSize = this.__getBrowserSize();
		var bodyWidth = brSize[0];
		var bodyHeight = brSize[1];

    	// Setting width and height of content div
      	this.divs_content.style.width = this.width + 'px';
    	this.divs_content.style.height= this.height + 'px';

    	// Creating temporary width variables since the actual width of the content div could be larger than this.width and this.height(i.e. padding and border)
    	var tmpWidth = this.divs_content.offsetWidth;
    	var tmpHeight = this.divs_content.offsetHeight;


    	// Setting width and height of left transparent div






    	this.divs_content.style.left = Math.ceil((bodyWidth - tmpWidth) / 2) + 'px';;
    	this.divs_content.style.top = (Math.ceil((bodyHeight - tmpHeight) / 2) +  topOffset) + 'px';

 		if(this.MSIE){
 			this.iframe.style.left = this.divs_content.style.left;
 			this.iframe.style.top = this.divs_content.style.top;
 			this.iframe.style.width = this.divs_content.style.width;
 			this.iframe.style.height = this.divs_content.style.height;
 		}

    	this.divs_shadow.style.left = (this.divs_content.style.left.replace('px','')/1 + this.shadowOffset) + 'px';
    	this.divs_shadow.style.top = (this.divs_content.style.top.replace('px','')/1 + this.shadowOffset) + 'px';
    	this.divs_shadow.style.height = tmpHeight + 'px';
    	this.divs_shadow.style.width = tmpWidth + 'px';



    	if(!this.shadowDivVisible)this.divs_shadow.style.display='none';	// Hiding shadow if it has been disabled


    }
    // }}}
    ,
	// {{{ __insertContent()
    /**
     *	Insert content into the content div
     *
     *
     * @private
     */
    __repositionTransparentDiv : function()
    {
    	this.divs_transparentDiv.style.top = Math.max(document.body.scrollTop,document.documentElement.scrollTop) + 'px';
    	this.divs_transparentDiv.style.left = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft) + 'px';
		var brSize = this.__getBrowserSize();
		var bodyWidth = brSize[0];
		var bodyHeight = brSize[1];
    	this.divs_transparentDiv.style.width = bodyWidth + 'px';
    	this.divs_transparentDiv.style.height = bodyHeight + 'px';

    }
	// }}}
	,
	// {{{ __insertContent()
    /**
     *	Insert content into the content div
     *
     *
     * @private
     */
    __insertContent : function()
    {
		if(this.url){	// url specified - load content dynamically
			ajax_loadContent('DHTMLSuite_modalBox_contentDiv',this.url);
		}else{	// no url set, put static content inside the message box
			this.divs_content.innerHTML = this.htmlOfModalMessage;
		}
    }
}

/************************************************************************************************************
Ajax dynamic content
Copyright (C) 2006  DTHMLGoodies.com, Alf Magne Kalleland

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

Dhtmlgoodies.com., hereby disclaims all copyright interest in this script
written by Alf Magne Kalleland.

Alf Magne Kalleland, 2006
Owner of DHTMLgoodies.com


************************************************************************************************************/

var enableCache = true;
var jsCache = new Array();

var dynamicContent_ajaxObjects = new Array();

function ajax_showContent(divId,ajaxIndex,url)
{
	var targetObj = document.getElementById(divId);
	targetObj.innerHTML = dynamicContent_ajaxObjects[ajaxIndex].response;
	if(enableCache){
		jsCache[url] = 	dynamicContent_ajaxObjects[ajaxIndex].response;
	}
	dynamicContent_ajaxObjects[ajaxIndex] = false;

	ajax_parseJs(targetObj)
}

function ajax_loadContent(divId,url)
{
	if(enableCache && jsCache[url]){
		document.getElementById(divId).innerHTML = jsCache[url];
		return;
	}

	var ajaxIndex = dynamicContent_ajaxObjects.length;
	document.getElementById(divId).innerHTML = 'Loading content - please wait';
	dynamicContent_ajaxObjects[ajaxIndex] = new sack();
	dynamicContent_ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
	dynamicContent_ajaxObjects[ajaxIndex].onCompletion = function(){ ajax_showContent(divId,ajaxIndex,url); };	// Specify function that will be executed after file has been found
	dynamicContent_ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function


}

function ajax_parseJs(obj)
{
	var scriptTags = obj.getElementsByTagName('SCRIPT');
	var string = '';
	var jsCode = '';
	for(var no=0;no<scriptTags.length;no++){
		if(scriptTags[no].src){
	        var head = document.getElementsByTagName("head")[0];
	        var scriptObj = document.createElement("script");

	        scriptObj.setAttribute("type", "text/javascript");
	        scriptObj.setAttribute("src", scriptTags[no].src);
		}else{
			if(navigator.userAgent.indexOf('Opera')>=0){
				jsCode = jsCode + scriptTags[no].text + '\n';
			}
			else
				jsCode = jsCode + scriptTags[no].innerHTML;
		}

	}

	if(jsCode)ajax_installScript(jsCode);
}


function ajax_installScript(script)
{
    if (!script)
        return;
    if (window.execScript){
    	window.execScript(script)
    }else if(window.jQuery && jQuery.browser.safari){ // safari detection in jQuery
        window.setTimeout(script,0);
    }else{
        window.setTimeout( script, 0 );
    }
}



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
                progressBar: pb
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
	messageObj.setSize(500,500);
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

/*****************************************/
/** Usable Forms 2.0, November 2005     **/
/** Written by ppk, www.quirksmode.org  **/
/** Instructions for use on my site     **/
/**                                     **/
/** You may use or change this script   **/
/** only when this copyright notice     **/
/** is intact.                          **/
/**                                     **/
/** If you extend the script, please    **/
/** add a short description and your    **/
/** name below.                         **/
/*****************************************/

var containerTag = 'TR';

var compatible = (
	document.getElementById && document.getElementsByTagName && document.createElement
	&&
	!(navigator.userAgent.indexOf('MSIE 5') != -1 && navigator.userAgent.indexOf('Mac') != -1)
	);

if (compatible)
{
	document.write('<style>.accessibility{display: none}</style>');
	var waitingRoom = document.createElement('div');
}

var hiddenFormFieldsPointers = new Object();

function prepareForm()
{
	if (!compatible) return;
	var marker = document.createElement(containerTag);
	marker.style.display = 'none';

	var x = document.getElementsByTagName('select');
	for (var i=0;i<x.length;i++)
		addEvent(x[i],'change',showHideFields)

	var x = document.getElementsByTagName(containerTag);
	var hiddenFields = new Array;
	for (var i=0;i<x.length;i++)
	{
		if (x[i].getAttribute('rel'))
		{
			var y = getAllFormFields(x[i]);
			x[i].nestedRels = new Array();
			for (var j=0;j<y.length;j++)
			{
				var rel = y[j].getAttribute('rel');
				if (!rel || rel == 'none') continue;
				x[i].nestedRels.push(rel);
			}
			if (!x[i].nestedRels.length) x[i].nestedRels = null;
			hiddenFields.push(x[i]);
		}
	}

	while (hiddenFields.length)
	{
		var rel = hiddenFields[0].getAttribute('rel');
		if (!hiddenFormFieldsPointers[rel])
			hiddenFormFieldsPointers[rel] = new Array();
		var relIndex = hiddenFormFieldsPointers[rel].length;
		hiddenFormFieldsPointers[rel][relIndex] = hiddenFields[0];
		var newMarker = marker.cloneNode(true);
		newMarker.id = rel + relIndex;
		hiddenFields[0].parentNode.replaceChild(newMarker,hiddenFields[0]);
		waitingRoom.appendChild(hiddenFields.shift());
	}

	setDefaults();
	addEvent(document,'click',showHideFields);
}

function setDefaults()
{
	var y = document.getElementsByTagName('input');
	for (var i=0;i<y.length;i++)
	{
		if (y[i].checked && y[i].getAttribute('rel'))
			intoMainForm(y[i].getAttribute('rel'))
	}

	var z = document.getElementsByTagName('select');
	for (var i=0;i<z.length;i++)
	{
		if (z[i].options[z[i].selectedIndex].getAttribute('rel'))
			intoMainForm(z[i].options[z[i].selectedIndex].getAttribute('rel'))
	}

}

function showHideFields(e)
{
	if (!e) var e = window.event;
	var tg = e.target || e.srcElement;

	if (tg.nodeName == 'LABEL')
	{
		var relatedFieldName = tg.getAttribute('for') || tg.getAttribute('htmlFor');
		tg = document.getElementById(relatedFieldName);
	}

	if (
		!(tg.nodeName == 'SELECT' && e.type == 'change')
		&&
		!(tg.nodeName == 'INPUT' && tg.getAttribute('rel'))
	   ) return;

	var fieldsToBeInserted = tg.getAttribute('rel');

	if (tg.type == 'checkbox')
	{
		if (tg.checked)
			intoMainForm(fieldsToBeInserted);
		else
			intoWaitingRoom(fieldsToBeInserted);
	}
	else if (tg.type == 'radio')
	{
		removeOthers(tg.form[tg.name],fieldsToBeInserted)
		intoMainForm(fieldsToBeInserted);
	}
	else if (tg.type == 'select-one')
	{
		fieldsToBeInserted = tg.options[tg.selectedIndex].getAttribute('rel');
		removeOthers(tg.options,fieldsToBeInserted);
		intoMainForm(fieldsToBeInserted);
	}
}

function removeOthers(others,fieldsToBeInserted)
{
	for (var i=0;i<others.length;i++)
	{
		var show = others[i].getAttribute('rel');
		if (show == fieldsToBeInserted) continue;
		intoWaitingRoom(show);
	}
}

function intoWaitingRoom(relation)
{
	if (relation == 'none') return;
	var Elements = hiddenFormFieldsPointers[relation];
	for (var i=0;i<Elements.length;i++)
	{
		waitingRoom.appendChild(Elements[i]);
		if (Elements[i].nestedRels)
			for (var j=0;j<Elements[i].nestedRels.length;j++)
				intoWaitingRoom(Elements[i].nestedRels[j]);
	}
}

function intoMainForm(relation)
{
	if (relation == 'none') return;
	var Elements = hiddenFormFieldsPointers[relation];
    if (Elements == null) return;
	for (var i=0;i<Elements.length;i++)
	{
		var insertPoint = document.getElementById(relation+i);
		insertPoint.parentNode.insertBefore(Elements[i],insertPoint);
		if (Elements[i].nestedRels)
		{
			var fields = getAllFormFields(Elements[i]);
			for (var j=0;j<fields.length;j++)
			{
				if (!fields[j].getAttribute('rel')) continue;
				if (fields[j].checked || fields[j].selected)
					intoMainForm(fields[j].getAttribute('rel'));
			}
		}
	}
}

function getAllFormFields(node)
{
	var allFormFields = new Array;
	var x = node.getElementsByTagName('input');
	for (var i=0;i<x.length;i++)
		allFormFields.push(x[i]);
	var y = node.getElementsByTagName('option');
	for (var i=0;i<y.length;i++)
		allFormFields.push(y[i]);
	return allFormFields;
}

/** ULTRA-SIMPLE EVENT ADDING **/

function addEvent(obj,type,fn)
{
	if (obj.addEventListener)
		obj.addEventListener(type,fn,false);
	else if (obj.attachEvent)
		obj.attachEvent("on"+type,fn);
}

addEvent(window,"load",prepareForm);


/** PUSH AND SHIFT FOR IE5 **/

function Array_push() {
	var A_p = 0
	for (A_p = 0; A_p < arguments.length; A_p++) {
		this[this.length] = arguments[A_p]
	}
	return this.length
}

if (typeof Array.prototype.push == "undefined") {
	Array.prototype.push = Array_push
}

function Array_shift() {
	var A_s = 0
	var response = this[0]
	for (A_s = 0; A_s < this.length-1; A_s++) {
		this[A_s] = this[A_s + 1]
	}
	this.length--
	return response
}

if (typeof Array.prototype.shift == "undefined") {
	Array.prototype.shift = Array_shift
}


/*********
* Javascript for file upload demo
* Copyright (C) Tomas Larsson 2006
* http://tomas.epineer.se/

* Licence:
* The contents of this file are subject to the Mozilla Public
* License Version 1.1 (the "License"); you may not use this file
* except in compliance with the License. You may obtain a copy of
* the License at http://www.mozilla.org/MPL/
*
* Software distributed under this License is distributed on an "AS
* IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
* implied. See the License for the specific language governing
* rights and limitations under the License.
*/
function PeriodicalAjax(url, parameters, frequency, decay, onSuccess, onFailure) {
	function createRequestObject() {
		var xhr;
		try {
			xhr = new XMLHttpRequest();
		}
		catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		return xhr;
	}

	function send() {
		if(!stopped) {
			xhr.open('post', url, true);
			xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			xhr.onreadystatechange = function() { self.onComplete(); };
			xhr.send(parameters);
		}
	}

	this.stop = function() {
		stopped = true;
		clearTimeout(this.timer);
	}

	this.start = function() {
		stopped = false;
		this.onTimerEvent();
	}

	this.onComplete = function() {
		if(this.stopped) return false;
		if ( xhr.readyState == 4) {
			if(xhr.status == 200||xhr.status == 0) {
				if(xhr.responseText == lastResponse) {
					decay = decay * originalDecay;
				} else {
					decay = 1;
				}
				lastResponse = xhr.responseText;
				//
				if(onSuccess instanceof Function) {
					onSuccess(xhr);
				}
				this.timer = setTimeout(function() { self.onTimerEvent(); }, decay * frequency * 1000);
			} else {
				alert(xhr.status);
				if(onFailure instanceof Function) {
					onFailure(xhr);
				}
			}
		}
	}

	this.getResponse = function() {
		if(xhr.responseText) {
			return xhr.responseText;
		}
	}

	this.onTimerEvent = function() {
		send();
	}

	var self = this;
	var stopped = false;
	var originalDecay = decay || 1.2;
	decay = originalDecay;
	var xhr = createRequestObject();
	var lastResponse = "";
	this.start();
}

function ProgressTracker(sid, options) {

	this.onSuccess = function(xhr) {
		if(parseInt(xhr.responseText) >= 100) {
			periodicalAjax.stop();
			if(options.onComplete instanceof Function) {
				options.onComplete();
			}
		} else if(xhr.responseText && xhr.responseText != lastResponse) {
			if(options.onProgressChange instanceof Function) {
				options.onProgressChange(xhr.responseText);
			}
			if(options.progressBar && options.progressBar.style) {
				//alert(xhr.responseText);
				options.progressBar.style.width = parseInt(xhr.responseText) + "%";
			}
		}
	}

	this.onFailure = function(xhr) {
		if(options.onFailure instanceof Function) {
			options.onFailure(xhr.responseText);
		} else {
			alert(xhr.responseText);
		}
		periodicalAjax.stop();
	}

	var self = this;
	var lastResponse = -1;
	options = options || {};
	var url = options.url || 'fileprogress.php';
	var frequency = options.frequency || 0.5;
	var decay = options.decay || 2;
	var periodicalAjax = new PeriodicalAjax(url, 'sid=' + sid, frequency, decay, function(request){self.onSuccess(request);},function(request){self.onFailure(request);});
}
/* Simple AJAX Code-Kit (SACK) v1.6.1 */
/* ©2005 Gregory Wild-Smith */
/* www.twilightuniverse.com */
/* Software licenced under a modified X11 licence,
   see documentation or authors website for more details */

function sack(file) {
	this.xmlhttp = null;

	this.resetData = function() {
		this.method = "POST";
  		this.queryStringSeparator = "?";
		this.argumentSeparator = "&";
		this.URLString = "";
		this.encodeURIString = true;
  		this.execute = false;
  		this.element = null;
		this.elementObj = null;
		this.requestFile = file;
		this.vars = new Object();
		this.responseStatus = new Array(2);
  	};

	this.resetFunctions = function() {
  		this.onLoading = function() { };
  		this.onLoaded = function() { };
  		this.onInteractive = function() { };
  		this.onCompletion = function() { };
  		this.onError = function() { };
		this.onFail = function() { };
	};

	this.reset = function() {
		this.resetFunctions();
		this.resetData();
	};

	this.createAJAX = function() {
		try {
			this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e1) {
			try {
				this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e2) {
				this.xmlhttp = null;
			}
		}

		if (! this.xmlhttp) {
			if (typeof XMLHttpRequest != "undefined") {
				this.xmlhttp = new XMLHttpRequest();
			} else {
				this.failed = true;
			}
		}
	};

	this.setVar = function(name, value){
		this.vars[name] = Array(value, false);
	};

	this.encVar = function(name, value, returnvars) {
		if (true == returnvars) {
			return Array(encodeURIComponent(name), encodeURIComponent(value));
		} else {
			this.vars[encodeURIComponent(name)] = Array(encodeURIComponent(value), true);
		}
	}

	this.processURLString = function(string, encode) {
		encoded = encodeURIComponent(this.argumentSeparator);
		regexp = new RegExp(this.argumentSeparator + "|" + encoded);
		varArray = string.split(regexp);
		for (i = 0; i < varArray.length; i++){
			urlVars = varArray[i].split("=");
			if (true == encode){
				this.encVar(urlVars[0], urlVars[1]);
			} else {
				this.setVar(urlVars[0], urlVars[1]);
			}
		}
	}

	this.createURLString = function(urlstring) {
		if (this.encodeURIString && this.URLString.length) {
			this.processURLString(this.URLString, true);
		}

		if (urlstring) {
			if (this.URLString.length) {
				this.URLString += this.argumentSeparator + urlstring;
			} else {
				this.URLString = urlstring;
			}
		}

		// prevents caching of URLString
		this.setVar("rndval", new Date().getTime());

		urlstringtemp = new Array();
		for (key in this.vars) {
			if (false == this.vars[key][1] && true == this.encodeURIString) {
				encoded = this.encVar(key, this.vars[key][0], true);
				delete this.vars[key];
				this.vars[encoded[0]] = Array(encoded[1], true);
				key = encoded[0];
			}

			urlstringtemp[urlstringtemp.length] = key + "=" + this.vars[key][0];
		}
		if (urlstring){
			this.URLString += this.argumentSeparator + urlstringtemp.join(this.argumentSeparator);
		} else {
			this.URLString += urlstringtemp.join(this.argumentSeparator);
		}
	}

	this.runResponse = function() {
		eval(this.response);
	}

	this.runAJAX = function(urlstring) {
		if (this.failed) {
			this.onFail();
		} else {
			this.createURLString(urlstring);
			if (this.element) {
				this.elementObj = document.getElementById(this.element);
			}
			if (this.xmlhttp) {
				var self = this;
				if (this.method == "GET") {
					totalurlstring = this.requestFile + this.queryStringSeparator + this.URLString;
					this.xmlhttp.open(this.method, totalurlstring, true);
				} else {
					this.xmlhttp.open(this.method, this.requestFile, true);
					try {
						this.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
					} catch (e) { }
				}

				this.xmlhttp.onreadystatechange = function() {
					switch (self.xmlhttp.readyState) {
						case 1:
							self.onLoading();
							break;
						case 2:
							self.onLoaded();
							break;
						case 3:
							self.onInteractive();
							break;
						case 4:
							self.response = self.xmlhttp.responseText;
							self.responseXML = self.xmlhttp.responseXML;
							self.responseStatus[0] = self.xmlhttp.status;
							self.responseStatus[1] = self.xmlhttp.statusText;

							if (self.execute) {
								self.runResponse();
							}

							if (self.elementObj) {
								elemNodeName = self.elementObj.nodeName;
								elemNodeName.toLowerCase();
								if (elemNodeName == "input"
								|| elemNodeName == "select"
								|| elemNodeName == "option"
								|| elemNodeName == "textarea") {
									self.elementObj.value = self.response;
								} else {
									self.elementObj.innerHTML = self.response;
								}
							}
							if (self.responseStatus[0] == "200") {
								self.onCompletion();
							} else {
								self.onError();
							}

							self.URLString = "";
							break;
					}
				};

				this.xmlhttp.send(this.URLString);
			}
		}
	};

	this.reset();
	this.createAJAX();
}
