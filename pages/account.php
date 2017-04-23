<?php
	
	if(isset($_GET['user'])){
		if(!empty($_GET['user'])  && is_string($_GET['user'])){
			$user = $_GET['user'] = secure($_GET['user']);
			if($user != $_SESSION['name']){
				if(is_user($user)){
					if(get_rank($_SESSION['name']) >= get_rank($user)){
						display_user_infos($user, false);
					
					}else{
						set_error('Erreur', 'ban-circle', 'Vous n\'avez pas les droits necessaires !', 'account');
					}
					
				}else{
					set_error('Erreur', 'search', 'Cet utilisateur n\'existe pas !', 'account');
				}
			
			}else{
				redirect('account');
			}
		
		}else{
			redirect('account');
		}

	}else{
		display_user_infos($_SESSION['name'], true);
	}
