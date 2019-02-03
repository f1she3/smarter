<?php

/**
* Info about column "validate" of table "friend"
*
* 0 = friend req sent, currently no answer
* 1 = friend req accepted
* 2 = friend req refused 
* 3 = friend removed by the user
*
*/
if(isset($_GET['user']) && !empty($_GET['user']) && is_string($_GET['user'])){
	$user = $_GET['user'] = secure($_GET['user']);
	if($user != $_SESSION['name']){
		if(is_user($user)){
			if(!is_blocked($_SESSION['name'], $user)){
				view_req($_SESSION['name'], $user);
				if(isset($_POST['accept'])){
					answer_friend_req($_SESSION['name'], $user, 1);
					redirect('private&user='.$user);
					
				}else if(isset($_POST['refuse'])){
					answer_friend_req($_SESSION['name'], $user, 2);
					redirect('contacts');
				}
				echo "<form method=\"POST\" action=\"\">
						<div class=\"icon\">
							<a href=\"".constant('BASE_URL')."block&user=".$user."\" class=\"text-danger\">
								<span class=\"glyphicon glyphicon-ban-circle text-danger\"></span>
							</a>
						</div>
					</form>";
				$ret_is_friend = is_friend($_SESSION['name'], $user);
				$ret_is_asked = is_asked($user, $_SESSION['name'], 0);
				if($ret_is_asked){
					echo "<div class=\"page-header\">
							<h3 class=\"text-center\">Accepter la demande de ".$ret_is_asked['sender']." ?</h3>
						</div>
						<blockquote class=\"blockquote col-xs-8 col-xs-offset-2\">
						<p style=\"word-wrap:break-word\">".bb_decode($ret_is_asked['message'])."</p>
						<footer class=\"blockquote-footer\"><p>".$user."</p></footer>
						</blockquote>
						<form method=\"POST\" action=\"\" class=\"col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4\">
							<div class=\"pull-left\">
								<button name=\"accept\" class=\"btn btn-success\">
									<span class=\"glyphicon glyphicon-ok\"></span>
								</button>
							</div>
							<div class=\"pull-right\">
								<button name=\"refuse\" class=\"btn btn-danger\">
									<span class=\"glyphicon glyphicon-remove\"></span>
								</button>
							</div>
						</form>";
									
				}else if($ret_is_friend){
					if(isset($_POST['submit'])){
						if(isset($_POST['message']) && !empty($_POST['message']) && strlen($_POST['message']) <= 500){
							$_POST['message'] = secure($_POST['message']);
							send_private_message($_SESSION['name'], $user, $_POST['message']);
						}
					}
					display_private_chat($_SESSION['name'], $user);

				}else{
					if(isset($_POST['send'])){
						if(isset($_POST['send_req']) && !empty($_POST['send_req']) && is_string($_POST['send_req'])){
							$message = $_POST['send_req'] = secure($_POST['send_req']);
							if(strlen($message) <= 500){
								if(!empty($message)){
									new_friend_req($_SESSION['name'], $user, $message);
									set_flash('success', "<span class=\"glyphicon glyphicon-plus\"></span> <span class=\"glyphicon glyphicon-user\"></span>");
									redirect('private&user='.$user);
						
								}
							}
						}
					}
				}
				$ret_is_asked_0 = is_asked($_SESSION['name'], $user, 0);
				$ret_is_asked = is_asked($_SESSION['name'], $user, NULL);
				$ret_reverse_is_asked = is_asked($user, $_SESSION['name'], NULL);
				$ret_is_pending = is_asked($_SESSION['name'], $user, 0);
				$ret_reverse_is_pending = is_asked($user, $_SESSION['name'], 0);
				if(!$ret_is_pending && !$ret_reverse_is_pending){
					if(!$ret_is_friend){
						echo "<h2 class=\"text-center page-header\">Vous n'êtes pas encore ami avec ".$user."</h2>
							<h4 class=\"text-center\">Envoyer une demande à ".$user."</h4>
							<form method=\"POST\" action=\"\">
								<div class=\"form-group col-md-6 col-md-offset-3\">
									<label>Message :</label>
									<textarea name=\"send_req\" class=\"form-control\" maxlength=\"500\" autofocus></textarea>
								</div>
								<div class=\"col-md-6 col-md-offset-3\">
									<button name=\"send\" class=\"btn btn-primary center-block\">envoyer</button>
								</div>
							</form>";
						}

				}else if($ret_is_pending){
					set_error('Vous n\'êtes pas encore ami avec '.$user, 'time', 'Demande en attente ...', 'contacts');

				}
			
			}else{
				redirect('block&user='.$user);
			}
		
		}else{
			set_error('Erreur', 'search', 'Cet utilisateur n\'existe pas !', 'contacts');
		}
		
	}else{
		redirect(3);
	}

}else{
	redirect(3);
}
