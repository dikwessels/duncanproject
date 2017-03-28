	<!DOCTYPE html>
	<html>
		<head>
			<script type="text/javascript" rel = "nofollow" src="/js/jquery-1.11.1.min.js"></script>
			
			<script type="text/javascript">
				$(document).ready(
				
				function(){
				
				
				$('#testForm').append('<input class="dynamic" id="invoice-tax" type="hidden" name="zTAXAMT" value="0.00">');
				$('#testForm').append('<input id="invoice-shipping" type="hidden" name="zFREIGHTAMT" value="10.00">');
				$('#testForm').append('<input id="invoice-total" type="hidden" name="PAYMENTREQUEST_0_AMT" value="5.00">');
				$('#testForm').append('<input id="pp-invoice-items" type="hidden" name="itemlist" value="itemy items!">');
				
				$(".dynamic").remove();
				
				}
				);
			</script>
		</head>
		<body>	
			<form id  = "testForm">	</form>
		</body>
	</html>