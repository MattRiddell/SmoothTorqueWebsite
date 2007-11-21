<form name="myForm">
	<table border="0">
		<tr>
			<td colspan="2"><img src="/images/logo2.png"></td>
		</tr>
		<tr>
			<td><label for="agents">Max Agents:</label></td>
			<td><input type="text" name="agents" id="agents"></td>
		</tr>
		<tr>
			<td><label for="did">Call Center Phone Number:</label></td>
			<td><input type="text" name="did" id="did"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="button" value="Close" onclick="closeMessage()">
			<input type="button" value="Send" onclick="closeMessage()"></td>
		</tr>
	</table>
	<script type="text/javascript">
	document.myForm.firstname.focus();
	</script>
</form>
