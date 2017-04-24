<?php

	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		require_once '../functions/init.php';
		if(is_logged()){
			// Attempts != 0 : when a friend is removed, the attempts field is set to 0, so we don't want to show the removed friends
			// Validate != 2 : same : when a friend is removed, validate is set to 2
			$query = mysqli_prepare($mysqli, 'SELECT sender, contact, validate FROM friends WHERE (BINARY sender = ? OR BINARY contact = ?) 
				AND validate != 2 AND validate != 3 ORDER BY validate DESC LIMIT 5');
			mysqli_stmt_bind_param($query, 'ss', $_SESSION['name'], $_SESSION['name']);
			mysqli_stmt_execute($query);
			mysqli_stmt_bind_result($query, $sender, $contact, $validate);
			$i = 0;
			while(mysqli_stmt_fetch($query)){
				if($sender == $_SESSION['name']){	
					$sender = $contact;
				}	
				if($validate == 1){
					echo "<div class='contact'>
						<form method='post' action='' class='center-block'>
							<a href='/private&user=".$sender."' class='btn btn-primary contact'>
								<div class='contact-name'>
									".$sender."
								</div>
								<span class='glyphicon glyphicon-user left-icon'></span>
							</a>
							<button name='remove' class='btn btn-danger remove-contact'>
								<span class='glyphicon glyphicon-remove'></span>
							</button>
							<input type='hidden' name='name' value='".$sender."'>
						</form>
					</div>";
					
				}else{
					echo "<div class='contact'>
						<div class='center-block'>
							<a class='btn btn-warning' href='/private&user=".$sender."'>
								<div class='contact-name'>
									".$sender."
								</div>
								<span class='glyphicon glyphicon-user left-icon'></span>
							</a>
						</div>
					</div>";
				}
				$i++;
			}
			if($i == 0){
				echo "<h3 class='text-center'>Vous n'avez aucun ami pour le moment ...</h3>
					<p class='text-center'>Vous pouvez ajouter des amis à tout moment en leur envoyant une demande</p>";
			}
		
		}else{
			redirect(2);
		}
			
	}else{
		redirect(2);
	}
