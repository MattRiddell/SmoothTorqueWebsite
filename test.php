<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
        <title> your awesome page </title>
        <meta name="Generator" content="EditPlus" />
        <meta name="Author" content="Kieran Huggins - http://kieran.ca" />
        <script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').load('disTime.php');  // jquery ajax load into div
                },5000);
        });
        </script>
</head>
<body>
        <div id="ajaxDiv"></div>
</body>
</html> 
