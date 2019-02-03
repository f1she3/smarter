<?php

require 'functions/init.php';
// Access right check & redirecting
if(isset($_GET['page']) && !empty($_GET['page']) && is_string($_GET['page'])){
	$page = $_GET['page'] = secure($_GET['page']);
	// Remove the / of an URL
	if(substr($page, 0, 1) == '/'){
		$page = substr($page, 1);
	} 
	$pages = scandir('pages/');
	// PHP or HTML file
	$file_type = false;	
	if(in_array($page.'.php', $pages)){
		$file_type = 'php';
		
	}else if(in_array($page.'.html',$pages)){
		$file_type = 'html';
	}	
	if($file_type){
		if(is_logged()){
			if($page == 'login' || $page == 'register'){
				$page = 'error404';
				$file_type = 'php';
			}else{
				if($page == 'admin' && get_rank($_SESSION['username']) < 3){
					$page = 'error404';
					$file_type = 'php';
				}else{
					if($page == 'moderator' && (get_rank($_SESSION['username'] != (1 | 2)))){
						$page = 'error404';
						$file_type = 'php';
					}
				}
			}
			
		}else{
			if($page != 'login' && $page != 'register' && $page != 'home' &&
				$page != 'error404' && $page != 'error403'){
				$page = 'error404';
				$file_type = 'php';
			}
		}

	}else{
		$page = 'error404';
		$file_type = 'php';
	}
	
}else if(is_logged()){
	redirect(1);
	
}else{
	redirect(0);
}
// Enf of access right check
// Inclusion of several files, the order is important
if(is_logged()){
	$title = 'Smarter @'.$_SESSION['username']; 
	require 'body/header-2.php';
}else{
	$title = 'Smarter #'.$page;
	require 'body/header-1.php';
}
get_flash();
$pages = scandir('functions/');
if(in_array($page.'.func.php',$pages)){
	require 'functions/'.$page.'.func.php';
}
require 'pages/'.$page.'.'.$file_type;
echo "<script type=\"text/javascript\" src=\"js/flash.js\"></script>\n";
#echo "<script type=\"text/javascript\" src=\"http://127.0.0.1:8080/socket.io/socket.io.js\"></script>";
$pages = scandir('js/');
if(in_array($page.'.js',$pages)){
	echo "<script type=\"text/javascript\" src=\"js/".$page.".js\"></script>\n"; 
}
if(is_logged()){
	echo "<script src=\"".$_SESSION['host']."/js/index.js\"></script>\n";
}
if($page == 'home'){
	require 'body/footer-1.html';
}else{
	require 'body/footer-2.html';
	
}

