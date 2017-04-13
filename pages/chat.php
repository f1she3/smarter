<?php
	
	if(isset($_POST['submit'])){
		if(isset($_POST['message']) && !empty($_POST['message']) && strlen($_POST['message']) <= 350){
			if(!is_mute($_SESSION['name'])){
				$_POST['message'] = secure($_POST['message']);
				send_message($_POST['message']);
			}
		}
	}
?>
<div class="page-header">
	<h3 class="text-center">Chat général</h3>
</div>
<div class="messages">
	<?php display_chat($mysqli); ?>
</div>	
<form method="POST" action="">
	<div class="form-group col-md-6 col-md-offset-3">
		<label class="control-label">Votre message :</label>
		<textarea class="form-control" name="message" maxlength="350" autofocus></textarea>
	</div>
	<div class="col-md-6 col-md-offset-3">
		<button name="submit" class="btn btn-primary center-block">
			envoyer
		</button>
	</div>
</form>
