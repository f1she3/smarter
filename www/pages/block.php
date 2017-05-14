<?php
	
	if(isset($_GET['user']) && !empty($_GET['user']) && is_string($_GET['user'])){
		$user = $_GET['user'] = secure($_GET['user']);
	}
?>	
<h2 class="text-center page-header">Etes-vous sûr de vouloir bloquer <?= $user ?> ?</h2>
<div class="lead text-center"><?= $user ?> ne pourra plus vous  envoyer de demandes</div>
<form method="POST" action="" class="col-xs-4 col-xs-offset-4">
	<div class="pull-left">
		<button name="accept" class="btn btn-success">
			Oui <span class="glyphicon glyphicon-ok"></span>
		</button>
	</div>
	<div class="pull-right">
		<button name="refuse" class="btn btn-danger">
			Non <span class="glyphicon glyphicon-remove"></span>
		</button>
	</div>
</form>
