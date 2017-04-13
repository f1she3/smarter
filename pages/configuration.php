<?php

	$style = 'a';
	$error = '';
	if(isset($_POST['submit'])){
		$_POST['o_password'] = secure($_POST['o_password']);
		$_POST['n_password'] = secure($_POST['n_password']);
		$_POST['r_password'] = secure($_POST['r_password']);
		$o_password = password_hash($_POST['o_password'], PASSWORD_BCRYPT);
		$n_password = password_hash($_POST['n_password'], PASSWORD_BCRYPT);
		$r_password = password_hash($_POST['r_password'], PASSWORD_BCRYPT);
		$ret_check_pass = check_pass($_POST['o_password'], $_SESSION['name']);
		$style = '';
		if(!empty($_POST['o_password'])){
			if(!empty($_POST['n_password'])){
				if(!empty($_POST['r_password'])){
					if($_POST['n_password'] == $_POST['r_password']){
						if(strlen($_POST['n_password']) >= 6){
							if($_POST['n_password'] != $_POST['o_password']){
								if($ret_check_pass){
									update_pass($_SESSION['name'], $n_password);
									set_flash('success', "<span class='glyphicon glyphicon-ok'></span> <span class='glyphicon glyphicon-lock'></span>");
									redirect(1);

								}else{
									$error = "l'ancien mot de passe est incorrect";
								}
							
							}else{
								$error = "veuillez choisir un mot de passe différent de l'ancien";
							}
						
						}else{
							$error = "le mot de passe doit contenir au minimum 6 caractères";
						}
					
					}else{
						$error = "les nouveaux mots de passe ne correspondent pas";
					}
				
				}else{
					$error = "veuillez répéter votre nouveau mot de passe";
				}
			
			}else{
				$error = "veuillez saisir votre nouveau mot de passe";
			}
		
		}else{
			$error = "veuillez saisir votre ancien mot de passe";
		}
	}
?>

<h3 class="text-center">Changer mes informations</h3><hr>
<div class="errors-block col-sm-6 col-sm-offset-3">
<?php

	if(!empty($error)){
		echo "<div class='alert alert-danger text-center'>
				<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> ".$error."
			</div>";
	}
	if(!empty($style)){
		echo "<div class='alert alert-danger invisible'>".$style."</div>";
	}
?>
</div>
<div class="icon">
	<a href="/delete">
		<span class="glyphicon glyphicon-trash"></span>
	</a>
</div>
<div class="col-sm-6 col-sm-offset-3">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><strong>Changer le mot de passe</strong></h3>
		</div>
		<div class="panel-body">
			<form method="POST" action="">
				<div class="form-group">
					<label for="o_password">Ancien mot de passe :</label>
					<input type="password" name="o_password" class="form-control" id="o_password" placeholder="*********" autofocus required>
				</div>
				<div class="form-group">
					<label for="n_password">Nouveau mot de passe :</label>
					<input type="password" name="n_password" class="form-control" id="n_password" placeholder="*********" autofocus="" required>
				</div>
				<div class="form-group">
					<label for="r_password">Répetez le mot de passe :</label>
					<input type="password" name="r_password" class="form-control" id="r_password" placeholder="*********" autofocus="" required>
				</div>
				<button name="submit" class="btn btn-primary center-block">Changer</button>
			</form>
		</div>
	</div>
</div>
