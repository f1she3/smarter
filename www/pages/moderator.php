<?php
	
if(isset($_GET['user']) && !empty($_GET['user']) && is_string($_GET['user'])){
	$user = $_GET['user'] = secure($_GET['user']);
	if($user != $_SESSION['name']){
		if(is_user($user)){
			if(get_rank($_SESSION['name']) > get_rank($user)){
				if(isset($_POST) && !empty($_POST)){
					if(isset($_POST['submit_mute'])){
						if(isset($_POST['mute_min']) && !empty($_POST['mute_min'])){
							if(isset($_POST['mute_hour']) && !empty($_POST['mute_hour'])){
								if(isset($_POST['mute_day']) && !empty($_POST['mute_day'])){
									$min = $_POST['mute_min'] = secure($_POST['mute_min']);
									$hour = $_POST['mute_hour'] = secure($_POST['mute_hour']);
									$day = $_POST['mute_day'] = secure($_POST['mute_day']);
									if(is_number($min) && is_number($hour) && is_number($day)){
										if($min <= 59 && $hour <= 24 && $day <= 365){
											if(isset($_GET['user']) && !empty($_GET['user'])
												&& is_string($_GET['user'])){
												set_mute($user, $min, $hour, $day);
												set_flash('success', "<span class=\"glyphicon glyphicon-volume-off\"></span> <span class=\"glyphicon glyphicon-ok\"></span>");
												redirect('moderator&user='.$user);
											}
										}
									}

								}else{
									$min = $_POST['mute_min'] = secure($_POST['mute_min']);
									$hour = $_POST['mute_hour'] = secure($_POST['mute_hour']);
									set_mute($user, $min, $hour, 0);
									set_flash('success', "<span class='glyphicon glyphicon-volume-off'></span> <span class='glyphicon glyphicon-ok'></span>");
									redirect('moderator&user='.$user);
								}
							
							}else if(isset($_POST['mute_day']) && !empty($_POST['mute_day'])){
									$min = $_POST['mute_min'] = secure($_POST['mute_min']);
									$day = $_POST['mute_day'] = secure($_POST['mute_day']);
									set_mute($user, $min, 0, $day);
									set_flash('success', "<span class='glyphicon glyphicon-volume-off'></span> <span class='glyphicon glyphicon-ok'></span>");
									redirect('moderator&user='.$user);

							}else{
								$min = $_POST['mute_min'] = secure($_POST['mute_min']);
								set_mute($user, $min, 0, 0);
								set_flash('success', "<span class='glyphicon glyphicon-volume-off'></span> <span class='glyphicon glyphicon-ok'></span>");
								redirect('moderator&user='.$user);
							}
					
						}else if(isset($_POST['mute_hour']) && !empty($_POST['mute_hour'])){
							if(isset($_POST['mute_day']) && !empty($_POST['mute_day'])){
								$hour = $_POST['mute_hour'] = secure($_POST['mute_hour']);
								$day = $_POST['mute_day'] = secure($_POST['mute_day']);
								set_mute($user, 0, $hour, $day);
								set_flash('success', "<span class='glyphicon glyphicon-volume-off'></span> <span class='glyphicon glyphicon-ok'></span>");
								redirect('moderator&user='.$user);
							
							}else{
								$hour = $_POST['mute_hour'] = secure($_POST['mute_hour']);
								set_mute($user, 0, $hour, 0);
								set_flash('success', "<span class='glyphicon glyphicon-volume-off'></span> <span class='glyphicon glyphicon-ok'></span>");
								redirect('moderator&user='.$user);
							}
					
						}else{
							if(isset($_POST['mute_day']) && !empty($_POST['mute_day'])){
								$day = $_POST['mute_day'] = secure($_POST['mute_day']);
								set_mute($user, 0, 0, $day);
								set_flash('success', "<span class='glyphicon glyphicon-volume-off'></span> <span class='glyphicon glyphicon-ok'></span>");
								redirect('moderator&user='.$user);
							}
						}

					}else if(isset($_POST['submit_demute'])){
						demute($user);
						set_flash('success', "<span class='glyphicon glyphicon-volume-up'></span> <span class='glyphicon glyphicon-ok'></span>");
						redirect('moderator&user='.$user);

					}else if(isset($_POST['submit_rank'])){
						$rank = $_POST['rank'] = secure($_POST['rank']);
						set_rank($user, $rank);
						set_flash('success', "<span class='glyphicon glyphicon-pencil'></span> <span class='glyphicon glyphicon-ok'></span>");
						redirect('moderator');
					}
				}
				display_moderator_dashboard($user);
			
			}else{
				set_error('Erreur', false, 'Vous n\'avez pas les droits nécessaires !', 'moderator');
			}
		
		}else{
			set_error('Erreur', false, 'Cet utilisateur n\'éxiste pas !', 'moderator');
		}
	
	}else{
		display_moderator_infos();
	}

}else{
	display_moderator_infos();
}
