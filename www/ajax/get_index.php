<?php

require '../functions/init.php';
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	if(is_logged()){
		$query = mysqli_prepare($mysqli, 'SELECT sender FROM private WHERE BINARY contact = ? AND viewed = 0 and isTyping != 1');
		mysqli_stmt_bind_param($query, 's', $_SESSION['username']);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $sender);
		$i = 0;
		while(mysqli_stmt_fetch($query)){
			$i++;
		}	
		if($i > 0){
			if($i > 9){
				$i = 9;
			}
			$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND validate = 1');
			mysqli_stmt_bind_param($query, 'ssss', $_SESSION['username'], $sender, $_SESSION['username'], $s);
			mysqli_stmt_execute($query);
			$x = 0;
			while(mysqli_stmt_fetch($query)){
				$x++;
			}
			if($x > 0){
				echo $i;
			
			}else{
				//echo "<input type=\"hidden\" name=\"hidden_input\">";
			}
			
		}else{
			$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE BINARY contact = ? AND viewed = 0 AND validate = 0');
			mysqli_stmt_bind_param($query, 's', $_SESSION['username']);
			mysqli_stmt_execute($query);
			mysqli_stmt_bind_result($query, $sender);
			while(mysqli_stmt_fetch($query)){
				$i++;
			}
			if($i > 0){
				if($i > 9){
					$i = 9;
				}
				echo $i;
		
			}else{
				$query = mysqli_prepare($mysqli, 'SELECT contact FROM friends WHERE BINARY sender = ? AND viewed != 2 AND validate = 1');
				mysqli_stmt_bind_param($query, 's', $_SESSION['username']);
				mysqli_stmt_execute($query);
				mysqli_stmt_bind_result($query, $sender);
				$g = 0;
				while(mysqli_stmt_fetch($query)){
					$g++;
				}
				if($g > 0){
					if($g > 9){
						$g = 9;
					}
					echo $i;
				}else{
					//echo "<input type=\"hidden\" name=\"hidden_input\">";
				}
			}
		}
	}else{
		redirect(2);
	}
}else{
	redirect(2);
}
