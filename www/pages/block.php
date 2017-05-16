<?php

	if(isset($_GET['user']) && !empty($_GET['user']) && is_string($_GET['user'])){
		$user = $_GET['user'] = secure($_GET['user']);
		if(is_user($user)){
			$reasons[1] = 'Propos déplacés';
			$reasons[2] = 'Spam';
			$reasons[3] = 'Autre';
			echo "<h2 class=\"text-center page-header\">Êtes-vous sûr de vouloir bloquer ".$user."</h2>
				<div class=\"lead text-center\">".$user." ne pourra plus vous  envoyer de demandes</div>
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
					</div>
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
				block($_SESSION['name'], $user, $reason);
				set_flash('danger', "<span class=\"glyphicon glyphicon-user\"></span><span class=\"glyphicon glyphicon-volume-off\"></span>");
			
			}else if(isset($_POST['refuse'])){
				redirect('private&user='.$user);
			}
		
		}else{
			set_error('Erreur', 'zoom-out', 'Cet utilisateur n\'éxiste pas', 'contacts');
		}
	}
