<?php

	/**
	* Info about column "validate" of table "friend"
	*
	* 0 = friend req sent, currently no answer
	* 1 = friend req accepted
	* 2 = friend req refused 
	* 3 = friend removed by the user
	*/
	if(isset($_GET['user']) && !empty($_GET['user']) && is_string($_GET['user'])){
		$user = $_GET['user'] = secure($_GET['user']);
		if($user != $_SESSION['name']){
			view_req($_SESSION['name'], $user);
			if(isset($_POST['accept'])){
				answer_friend_req($_SESSION['name'], $user, 1);
				redirect('private&user='.$user);
				
			}else if(isset($_POST['refuse'])){
				answer_friend_req($_SESSION['name'], $user, 2);
				redirect('contacts');
			}
			$ret_is_user = is_user($user);
			if($ret_is_user){
				$ret_is_friend = is_friend($_SESSION['name'], $user);
				$ret_is_asked = array();
				$ret_is_asked = is_asked($_SESSION['name'], $user);
				if(!empty($ret_is_asked['sender']) && !empty($ret_is_asked['message'])){
					$ret_is_asked['message'] = bb_decode($ret_is_asked['message']);
					echo "<div class=\"page-header\">
							<h3 class=\"text-center\">Accepter la demande de ".$ret_is_asked['sender']." ?</h3>
						</div>
						<div class=\"well col-xs-6 col-xs-offset-3 message-box\">
							<p class=\"text-center\"><b>".bb_decode($ret_is_asked['message'])."</b></p>
						</div>
						<form method=\"POST\" action=\"\" class=\"col-xs-4 col-xs-offset-4\">
							<div class=\"pull-left\">
								<button name=\"accept\" class=\"btn btn-success\">
									<img src=\"../css/images/check.png\">
								</button>
							</div>
							<div class=\"pull-right\">
								<button name=\"refuse\" class=\"btn btn-danger\">
									<img src=\"../css/images/close.png\">
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
					echo "<div class=\"page-header\">
							<h3 class=\"text-center\">Conversation privée avec ".$user."</h3>
						</div>
						<input type=\"hidden\" id=\"contact_name\" value=".$user.">
						<div class=\"messages\">";
					display_private_chat($_SESSION['name'], $user);
					echo "</div>
						<form method=\"POST\" action=\"\">
							<div class=\"form-group col-md-6 col-md-offset-3\">
								<label>Votre message :</label><br>
								<textarea class=\"form-control\" name=\"message\" maxlength=\"500\" autofocus></textarea>
							</div>		
							<div class=\"col-md-6 col-md-offset-3\">
								<button name=\"submit\" class=\"btn btn-primary center-block\">
									Envoyer
								</button>
							</div>
						</form>";
						
				}else{
					$ret_attempts_1 = attempts($_SESSION['name'], $user, 1);
					$ret_attempts_2 = attempts($_SESSION['name'], $user, 2);
					if(isset($_POST['send'])){
						if(strlen($_POST['message']) <= 500){
							$message = $_POST['send_req'] = secure($_POST['send_req']);
							if(!empty($message)){
								if(empty($ret_attempts_1)){
									new_friend_req($_SESSION['name'], $user, $message, 1);
									set_flash('success', "<span class=\"glyphicon glyphicon-plus\"></span> <span class=\"glyphicon glyphicon-user\"></span>");
									redirect('private&user='.$user);
					
								}else if(empty($ret_attempts_2)){
									new_friend_req($_SESSION['name'], $user, $message, 2);
									set_flash('success', "<span class=\"glyphicon glyphicon-plus\"></span> <span class=\"glyphicon glyphicon-user\"></span>");
									redirect('private&user='.$user);
								}
							}
						}
					}
					$ret_attempts_1 = attempts($_SESSION['name'], $user, 1);
					$ret_attempts_2 = attempts($_SESSION['name'], $user, 2);
					$ret_friend_answer_1 = friend_answer($_SESSION['name'], $user, 1, 2);
					$ret_friend_answer_2 = friend_answer($_SESSION['name'], $user, 2, 2);
					$ret_friend_answer_0 = friend_answer($_SESSION['name'], $user, 2, 0);
					if(empty($ret_attempts_1)){
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

					}else if(empty($ret_friend_answer_1)){
						set_error('Vous n\'êtes pas encore ami avec '.$user, 'time', 'Demande en attente ...', 'contacts');

					}else if(!empty($ret_attempts_2)){
						if(!empty($ret_friend_answer_2)){
							set_error('Vous n\'êtes pas ami avec '.$user.'', 'ban-circle', $user.' a refusé vos demandes, mais il peut vous en envoyer une à tout moment', 'contacts');
						
						}else if(!empty($ret_friend_answer_0)){
							set_error('Vous n\'êtes pas encore ami avec '.$user, 'time', 'Demande en attente ...', 'contacts');
						}						
					
					}else{
						echo "<h2 class=\"text-center page-header\">".$user." a refusé votre demande</h2>
							<h4 class=\"text-center\">Renvoyer une demande à ".$user."</h4>
							<form method=\"POST\" action=\"\">
								<div class=\"form-group col-md-6 col-md-offset-3\">
									<label>Message :</label>
									<textarea name=\"send_req\" class=\"form-control\" maxlength=\"500\" autofocus></textarea>
								</div>
								<div class=\"col-md-6 col-md-offset-3\">
									<button name=\"send\" class=\"btn btn-primary center-block\">renvoyer</button>
								</div>
							</form>";
					}
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
