<html>
<head>
	
	<link rel="stylesheet" href="ayliss_style_uni.css" type="text/css">
	
	<script type="text/javascript" src="js/jquery-1.8.2.js"></script>

<script type="text/javascript">

$(document).ready(function(){
   setTimeout($('#alert-window').fadeIn(),1000);
   
	$('#close-alert').click(function(){
		$('#alert-window').fadeOut();
	});
});

</script>
</head>
<body>

This is some body content
	<div id="alert-window" class="modal-popup-container">
	<div class="modal-popup" style="color:black;">
		<span class="underline"><strong>Note to our customers:</strong></span><br><br>
		All orders placed after July 15th, 2013 will be shipped on or shortly after August 2nd, 2013. We appreciate your business!<br><br>
		<span id="close-alert" class="close-link">Close window</span>
	</div>
</div>
	
</body>
</html>