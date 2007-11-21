<BR><BR></TD></TR></TABLE>
<script type="text/javascript">
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


</script>
</BODY>
</HTML>
