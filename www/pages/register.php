<?php

$error = '';
$style = 'a';
if(isset($_POST['reg_submit'])){
	if(isset($_POST['username'])){
		$username = $_POST['username'] = secure($_POST['username']);
	}
	$email = $_POST['email'] = secure($_POST['email']);
	$_POST['password'] = secure($_POST['password']);
	$_POST['r_password'] = secure($_POST['r_password']);
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
	$email_hash = sha1($email);
	$ret_check_pattern_username = check_pattern('#^[a-zA-Z0-9_@[\]éè-]+$#', $username);
	$ret_check_pattern_email = check_pattern('#^[a-z0-9._-]+@[a-z0-9._-]{2,20}\.[a-z]{2,4}$#', $email);
	$ret_is_used_username = is_used('username', $username);
	$style = '';
	if(!empty($username)){
	    if(!empty($email)){
		if(!empty($_POST['password'])){
		    if(!empty($_POST['r_password'])){
			if(strlen($username) >= 4 && strlen($username) <= 15){
			    if($ret_check_pattern_username){
				if($ret_check_pattern_email){
				    if(strlen($_POST['password']) >= 6){
					if($_POST['password'] == $_POST['r_password']){
						if(!$ret_is_used_username){
						    register($username, $email_hash, $password); 
						    set_flash('success', '<img src="css/images/check.png"><img src="css/images/account.png">');
						$_SESSION['username'] = $username;
						redirect('chat');
					    }else{
						$error = 'ce nom d\'utilisateur est déjà utilisé';
					    }
					}else{
					    $error = 'les mots de passe ne correspondent pas';
					}
				    }else{
					$error = 'le mot de passe doit contenir au minimum 6 caractères';
				    }
				}else{
				    $error = 'l\'adresse email entrée est invalide';
				}
			    }else{
				$error = 'le pseudo choisi est invalide';
			    }
			}else{
			    $error = 'le nom d\'utilisateur doit contenir au minimum 4 caractères';
			}
		    }else{
			$error = 'veuillez répéter le mot de passe';
		    }
		}else{
		    $error = 'veuillez saisir un mot de passe';
		}
	    }else{
		$error = 'veuillez saisir votre adresse email';
	    }
	}else{
	    $error = 'veuillez saisir un pseudo';
	}
}
?>
<h2 class="text-center">Inscription</h2>
<div class="errors-block col-sm-6 col-sm-offset-3">
<?php 
	
	if(!empty($error)){
		echo "<div class='alert alert-danger errors medium-box center-block text-center'>
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
			<h3 class="panel-title"><strong>S'inscrire</strong></h3>
		</div>
		<div class="panel-body">
			<form method="POST" action="">
				<div class="form-group">
					<label for="name_reg">Votre nom d'utilisateur :</label>
					<input class="form-control" placeholder="4 caractères minimum" name="username" type="text" id="name_reg" maxlength="15" autofocus required>
				</div>
				<div class="form-group">
					<label for="email_reg">Votre adresse email :</label>
					<input class="form-control" placeholder="me@example.com" name="email" type="email" id="email_reg" maxlength="40" required>
				</div>
				<div class="form-group">
					<label for="password_reg">Votre mot de passe :</label>
					<input class="form-control" placeholder="6 caractères minimum" name="password" type="password" id="password_reg" maxlength="60" required>
				</div>
				<div class="form-group">
					<label for="r_password_reg">Répétez votre mot de passe :</label>
					<input class="form-control" placeholder="************" name="r_password" type="password" id="r_password_reg" maxlength="60" required>
				</div>
				<button name="reg_submit" class="btn btn-success center-block">Inscription</button>
			</form>
		</div>
	</div>
</div>
