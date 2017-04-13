<?php

		require_once 'app.php';
		require_once 'actions.php';
		require_once 'display.php';
		session_start();
		date_default_timezone_set('Europe/Paris');
		$_SESSION['host'] = '//'.$_SERVER['HTTP_HOST'];
		$_SESSION['host_name'] = 'Smarter';
		define('HOST', 'x');
		define('USER', 'x');
		define('PASSWORD', 'x');
		define('DB_NAME', 'x');
		$mysqli = mysqli_connect(constant('HOST'), constant('USER'), constant('PASSWORD'), constant('DB_NAME'));
		if(!mysqli_connect_errno()){
			if(!mysqli_set_charset($mysqli, 'utf8')){
				$title = $_SESSION['host_name'].' #erreur';
				if(is_logged()){
					require 'body/header-2.php';

				}else{
					require 'body/header.php';
				}
				set_error('Erreur', false, '/!\ Erreur lors du chargement de UTF-8 /!\\', '');
				require 'body/footer.html';
				exit();
			}

		}else{
			$title = $_SESSION['host_name'].' #erreur';
			if(is_logged()){
				require 'body/header-2.php';

			}else{
				require 'body/header-1.php';
			}
			set_error('Erreur', false, '/!\ Erreur lors de la connection à la base de données /!\\', '');
			require 'body/footer.html';
			exit();
			
		}
