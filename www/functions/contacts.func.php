<?php

function rm_friend($sender, $contact){
	$mysqli = get_link();
	$query = mysqli_prepare($mysqli, 'UPDATE friends SET validate = 3, deletedBy = ? WHERE (BINARY sender = ? OR BINARY sender = ?) AND
		(BINARY contact = ? OR BINARY contact = ?) AND validate = 1');
	mysqli_stmt_bind_param($query, 'sssss', $sender, $sender, $contact, $sender, $contact);
	mysqli_stmt_execute($query);
}
function display_contacts($username){
	$mysqli = get_link();
	// Validate != 2 : same : when a friend is removed, validate is set to 2
	$query = mysqli_prepare($mysqli, 'SELECT sender, contact, validate FROM friends WHERE (BINARY sender = ? OR BINARY contact = ?) 
		AND BINARY deletedBy != ? AND validate != 2 ORDER BY validate DESC');
	mysqli_stmt_bind_param($query, 'sss', $username, $username, $username);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $sender, $contact, $validate);
	$i = 0;
	echo "<div id=\"contacts\" class=\"col-md-6 col-md-offset-3 user-list\">";
	while(mysqli_stmt_fetch($query)){
		if($sender == $username){	
			$sender = $contact;
		}
		if(!is_blocked($contact, $sender)){
			if($validate == 1){
				echo "<div class=\"contact\">
					<form method=\"post\" action=\"\" class=\"center-block\">
						<a href=\"".$_SESSION['host'].constant('BASE_URL')."private&user=".$sender."\" class=\"btn btn-primary contact\">
							<div class=\"contact-name\">
								".$sender."
							</div>
							<span class=\"glyphicon glyphicon-user left-icon\"></span>
						</a>
						<button name=\"remove\" class=\"btn btn-danger remove-contact\">
							<span class=\"glyphicon glyphicon-remove\"></span>
						</button>
						<input type=\"hidden\" name=\"name\" value=\"".$sender."\">
					</form>
				</div>";
				
			}else{
				echo "<div class=\"contact\">
					<div class=\"center-block\">
						<a class=\"btn btn-warning\" href=\"".constant('BASE_URL')."private&user=".$sender."\">
							<div class=\"contact-name\">
								".$sender."
							</div>
							<span class=\"glyphicon glyphicon-user left-icon\"></span>
						</a>
					</div>
				</div>";
			}
			$i++;
		}
	}
	if($i == 0){
		echo "<h3 class=\"text-center\">Vous n'avez aucun ami</h3>
			<p class=\"text-center\">Vous pouvez ajouter des amis à tout moment en leur envoyant une demande</p>";
	}
	echo "</div>";
}
