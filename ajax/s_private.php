<?php

	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		require_once '../functions/init.php';
		if(is_logged()){
			if(isset($_POST['contact']) && !empty($_POST['contact'])){
				$contact = $_POST['contact'] = secure($_POST['contact']);
				if(isset($_POST['message']) && !empty($_POST['message']) && strlen($_POST['message']) <= 500){
					$_POST['message'] = secure($_POST['message']);
					$query = mysqli_prepare($mysqli, 'INSERT INTO private (sender, contact, message, date) VALUES (?, ?, ?, NOW())');
					mysqli_stmt_bind_param($query, 'sss', $_SESSION['name'], $contact, $_POST['message']);
					mysqli_stmt_execute($query);
				
				}else{
					if(isset($_POST['is_typing'])){
						if(isset($_POST['empty'])){
							$req = mysqli_prepare($mysqli, 'DELETE FROM private WHERE sender = ? and contact = ? and is_typing = 1');
							mysqli_stmt_bind_param($req, 'ss', $_SESSION['name'], $contact);
							mysqli_stmt_execute($req);
							
						}else{
							$req = mysqli_prepare($mysqli, 'SELECT sender FROM private WHERE sender = ? and contact = ? and is_typing = 1');
							mysqli_stmt_bind_param($req, 'ss', $_SESSION['name'], $contact);
							mysqli_stmt_execute($req);
							$result = mysqli_stmt_fetch($req);
							if(empty($result)){
								$one = 1;
								$query = mysqli_prepare($mysqli, 'INSERT INTO private (sender, contact, date, is_typing) VALUES
									(?, ?, NOW(), ?)');
								mysqli_stmt_bind_param($query, 'ssi', $_SESSION['name'], $contact, $one);
								mysqli_stmt_execute($query);
							}
						}
					}
				}
			}

		}else{
			redirect(2);
		}
	
	}else{
		redirect(2);
	}
