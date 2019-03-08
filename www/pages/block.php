<?php

if(isset($_GET['user']) && !empty($_GET['user']) && is_string($_GET['user'])){
	$user = $_GET['user'] = secure($_GET['user']);
	if(is_user($user)){
		$reasons[1] = 'Propos déplacés';
		$reasons[2] = 'Spam';
		$reasons[3] = 'Autre';
		if(!is_blocked($_SESSION['username'], $user)){
			echo "<h2 class=\"text-center\">Bloquer ".$user." ?</h2>
				<p class=\"text-center\"><span class=\"glyphicon glyphicon-info-sign\"></span> ".$user." ne pourra plus vous  envoyer de demandes</p>
				<h4 class=\"text-center page-header col-xs-12\">Motif</h4>
				<form method=\"POST\" action=\"\" class=\"col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4\">
					<div class=\"radio\">
						<label>
							<input type=\"radio\" name=\"optradio\" value=\"1\" checked=\"checked\">".$reasons[1]."
						</label>
					</div>
					<div class=\"radio\">
						<label>
							<input type=\"radio\" name=\"optradio\" value=\"2\">".$reasons[2]."
						</label>
					</div>
					<div class=\"radio\">
						<label>
							<input type=\"radio\" name=\"optradio\" value=\"3\">".$reasons[3]."
						</label>
					</div><br><br>
					<div class=\"pull-left\">
						<button name=\"accept\" class=\"btn btn-success\">
							Oui <span class=\"glyphicon glyphicon-ok\"></span>
						</button>
					</div>
					<div class=\"pull-right\">
						<button name=\"refuse\" class=\"btn btn-danger\">
							Non <span class=\"glyphicon glyphicon-remove\"></span>
						</button>
					</div>
				</form>";
			if(isset($_POST['accept'])){
				if(isset($_POST['optradio']) && !empty($_POST['optradio'])){
					$optradio = $_POST['optradio'] = secure($_POST['optradio']);
					switch($optradio){
						case(1):
							$reason = $reasons[1];
							break;
						case(2):
							$reason = $reasons[2];
							break;
						case(3):
							$reason = $reasons[3];
							break;
						default:
							$reason = $reasons[1];
							break;
					}
				}
				block($_SESSION['username'], $user, $reason);
				set_flash('danger', "<span class=\"glyphicon glyphicon-user\"></span><span class=\"glyphicon glyphicon-volume-off\"></span>");
				redirect(3);
			
			}else if(isset($_POST['refuse'])){
				redirect('private&user='.$user);
			}
				
		}else{
			echo "<h2 class=\"text-center\">Vous avez bloqué ".$user."</h2>
				<p class=\"text-center\"><span class=\"glyphicon glyphicon-info-sign\"></span> ".$user." pourra à nouveau vous envoyer de demandes et parler avec vous</p>
				<h4 class=\"text-center page-header\">Débloquer ".$user." ?</h4>
				<form method=\"POST\" action=\"\" class=\"col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4\">
					<div class=\"pull-left\">
						<button name=\"unblock\" class=\"btn btn-success\">
							Oui <span class=\"glyphicon glyphicon-ok\"></span>
						</button>
					</div>
					<div class=\"pull-right\">
						<button name=\"no_unblock\" class=\"btn btn-danger\">
							Non <span class=\"glyphicon glyphicon-remove\"></span>
						</button>
					</div>
				</form>";
			if(isset($_POST['unblock'])){
				unblock($_SESSION['username'], $user);
				set_flash('success', "<span class=\"glyphicon glyphicon-user\"></span><span class=\"glyphicon glyphicon-volume-up\"></span>");
				redirect('private&user='.$user);


			}else if(isset($_POST['no_unblock'])){
				redirect('block');
			}
		}
	
	}else{
		set_error('Erreur', 'zoom-out', 'Cet utilisateur n\'éxiste pas', 'contacts');
	}

}else{
	display_blocked_users($_SESSION['username']);
}
