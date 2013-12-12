<!DOCTYPE html>
<html>
<head>
	<script src="bloomer.js"></script>
	
	<!-- jQuery is only for example, it's not required -->
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	
	<style>
	#bloomer {
		background: #afa;
		padding: 10px;
	}
	
	#bloomer.hit {
		background: #faa;
	}
	</style>
</head>
<body>

<input type="hidden" id="bloomhex" value="<?php echo file_get_contents('bloom.hex')?>"/>

<div id="bloomer">
	<input type="text" id="password">
	<button id="button">Check</button>
</div>

<script>
$(function () {
	var bloomer = new Bloomer({
		hex: $('#bloomhex').val()
	});
	
	$('#button').click(function (e) {
		e.preventDefault();
		
		var result = bloomer.check($('#password').val());
		console.log("result = ", result);
		$('#bloomer').toggleClass('hit', result);
	});
});
</script>

</body>
</html>