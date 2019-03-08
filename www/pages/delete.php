<?php
	
$style = 'a';
$error = '';
if(isset($_POST['submit'])){
	$password = $_POST['password'] = secure($_POST['password']);
	$ret_check_pass = check_pass($password, $_SESSION['username']);
	$style = '';
	if(!empty($_POST['password'])){
		if($ret_check_pass){
			rm_account($_SESSION['username']);
			redirect('logout');

		}else{
			$error = 'le mot de passe est incorrect';
		}

	}else{
		$error = 'veuillez saisir votre mot de passe';
	}
}
?>
<div class="page-header">
<h3 class="text-center">Se désinscrire</h3>
</div>
<h4 class="text-center">Veuillez saisir votre mot de passe</h4>
<div class="errors-block col-sm-6 col-sm-offset-3">
<?php
	
	if(!empty($error)){
		echo "<div class=\"alert alert-danger text-center\">
				<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span> ".$error."
			</div>";
	}
	if(!empty($style)){
		echo "<div class=\"alert alert-danger invisible\">".$style."</div>";
	}
?>
</div>
<div class="col-sm-6 col-sm-offset-3">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><strong>Supprimer mon compte</strong></h3>
		</div>
		<div class="panel-body">
			<form method="POST" action="">
				<div class="form-group">
					<label>Votre mot de passe :</label>
					<input type="password" class="form-control" name="password" placeholder="************" autofocus required>
				</div>
				<button class="btn btn-danger center-block" name="submit">Confirmer</button>
			</form>
		</div>
	</div>
</div>

