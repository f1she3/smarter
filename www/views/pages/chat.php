<div class="page-header">
	<h3 class="text-center">Chat général</h3>
</div>
<div class="messages" id="public_messages">
</div>	
<form method="POST" action="">
	<input type="hidden" id="name" value="<?= $_SESSION['name'] ?>">
	<div class="form-group col-md-6 col-md-offset-3">
		<label class="control-label">Votre message :</label>
		<textarea class="form-control" name="message" id="message" maxlength="350" autofocus></textarea>
	</div>
	<div class="col-md-6 col-md-offset-3">
		<button name="submit" id="submit" class="btn btn-primary center-block">
			envoyer
		</button>
	</div>
</form>