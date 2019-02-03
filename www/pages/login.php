<?php

$error = '';
$style = 'a';
if(isset($_POST['submit'])){
	$password = $_POST['password'] = secure($_POST['password']);
	$username = $_POST['username'] = secure($_POST['username']);
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
	$ret_find_username = find_username($username);
	$ret_check_username = check_ids('username', false, $ret_find_username);
	$ret_check_pass = check_ids('password', $_POST['password'], $ret_find_username);
	$style = '';
	if(!empty($username)){
		if(!empty($_POST['password'])){	
			if(strlen($_POST['password']) >= 6){
				if(!is_banned($ret_find_username)){
					if(check_ids('username', false, $ret_find_username) && check_ids('password', $_POST['password'], $ret_find_username)){
						$_SESSION['username'] = $ret_find_username;
						set_flash('success', '<span class="glyphicon glyphicon-user"></span> <span class="glyphicon glyphicon-ok"></span>');
						redirect(1);
					}else{
						$error = 'nom d\'utilisateur ou mot de passe incorrect';
					}
				}else{
					$ret_is_banned = is_banned($ret_find_username);
					$error = 'banni pour la raison suivante : "'.bb_decode($ret_is_banned['message']).'"';
				}
			}else{
				$error = 'nom d\'utilisateur ou mot de passe incorrect';
			}
		}else{
			$error = 'veuillez saisir votre mot de passe';
		}
	}else{
		$error = 'veuillez saisir votre pseudo';
	}
}
?>
<h3 class="text-center">Connexion</h3>
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
<div class="col-sm-6 col-sm-offset-3">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><strong>Se connecter</strong></h3>
		</div>
		<div class="panel-body">
			<form method="POST" action="">
				<div class="form-group">
					<label for="name_log">Nom d'utilisateur :</label>
					<input class="form-control" placeholder="e-mail ou pseudo" name="username" type="text" id="username_log" maxlength="15" autofocus required>
				</div>
				<div class="form-group">
					<label for="password_log">Mot de passe :</label>
					<input class="form-control" placeholder="************" name="password" type="password" id="password_log" required>
				</div>
				<button name="submit" class="btn btn-primary center-block" id="submit_log">Connexion</button>
			</form>
		</div>
	</div>
</div>
