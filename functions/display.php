<?php

	function set_error($title, $icon, $content, $location){
			if(!$location){
				$full_location = $_SESSION['host'];
			
			}else{
				$full_location = $_SESSION['host'].constant('BASE_URL').$location;
			}
			if($icon){
				if($icon == 'error'){
					echo "<h2 class=\"text-center\">".$title."</h2>
						<img src=\"../css/images/emojis/e_s.svg\" height=\"40\" width=\"40\" class=\"center-block\">
						<h4 class=\"text-center\"><span class=\"glyphicon glyphicon-".$icon."\"></span></h4>
						<h4 class=\"text-center\">".$content."</h4><hr>
						<a href=\"".$full_location."\">
							<img src=\"".$_SESSION['host']."/css/images/home.svg\" height=\"75\" width=\"75\" class=\"center-block\">
						</a>";
				}else{
					echo "<h2 class=\"text-center\">".$title."</h2>
						<h4 class=\"text-center\"><span class=\"glyphicon glyphicon-".$icon."\"></span></h4>
						<h4 class=\"text-center\">".$content."</h4><hr>
						<a href=\"".$full_location."\">
							<img src=\"".$_SESSION['host']."/css/images/home.svg\" height=\"75\" width=\"75\" class=\"center-block\">
						</a>";
				}
			
			}else{
				echo "<h2 class=\"text-center\">".$title."</h2>
					<h4 class=\"text-center\">".$content."</h4><hr>
					<a href=\"".$full_location."\">
						<img src=\"".$_SESSION['host']."/css/images/home.svg\" height=\"75\" width=\"75\" class=\"center-block\">
					</a>";
			}
	}
	function bb_decode($text){
		$pattern_1 = '#https?://[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,4}(/\S*)?#';
		$pattern_2 = '#https?://[0-9]{1,3}+(\.[0-9]{1,3}){3}#';
		$text = htmlspecialchars_decode($text);
		if(preg_match($pattern_1, $text)){
			$text = strtolower($text);
			$text = preg_replace($pattern_1, '<a href="$0" target="_blank">$0</a>', $text);
			$i = 0;
		}
		if(preg_match($pattern_2, $text)){
			$text = strtolower($text);
			$text = preg_replace($pattern_2, '<a href="$0" target="_blank">$0</a>', $text);
			$i = 1;
		}
		if(!isset($i)){
			$text = str_ireplace( ':/', '<img src="../css/images/emojis/e_p.svg" height="16" width="16">', $text);
		}
		$text = str_ireplace( ';)', '<img src="../css/images/emojis/e_o.svg" height="16" width="16">', $text);
		$text = str_ireplace( ':)', '<img src="../css/images/emojis/e_c.svg" height="16" width="16">', $text);
		$text = str_ireplace( ':(', '<img src="../css/images/emojis/e_s.svg" height="16" width="16">', $text);
		$text = str_ireplace( ':p', '<img src="../css/images/emojis/e_l.svg" height="16" width="16">', $text);
		$text = str_ireplace( ':\\', '<img src="../css/images/emojis/e_p.svg" height="16" width="16">', $text);
		$text = str_ireplace( ':D', '<img src="../css/images/emojis/e_b.svg" height="16" width="16">', $text);
		$text = str_ireplace( ':-D', '<img src="../css/images/emojis/e_x.svg" height="16" width="16">', $text);
		$text = str_ireplace( ':-)', '<img src="../css/images/emojis/e_x.svg" height="16" width="16">', $text);
		
		return $text;
	}
	function set_flash($type, $content){
		$_SESSION['flash'] = [
			'type' 	=> $type,
			'content'  	=> $content 
		];	
	}
	function get_flash(){
		if(!empty($_SESSION['flash']['type']) && !empty($_SESSION['flash']['content'])){ 
			$type = $_SESSION['flash']['type'];
			$content = $_SESSION['flash']['content'];
			$_SESSION['flash']['type'] = $_SESSION['flash']['content'] = '';
			echo "<span class=\"flash\">
					<div class=\"alert alert-".$type."\">
						".$content."
					</div>
				</span>";
		}
	}
	function datalist_options($username, $rank_restriction){
		$mysqli = get_link();
		if($rank_restriction){
			$query = mysqli_prepare($mysqli, 'SELECT name FROM users WHERE BINARY name != ? AND rank < ?');
			mysqli_stmt_bind_param($query, 'ss', $username, $rank_restriction);

		}else{
			$query = mysqli_prepare($mysqli, 'SELECT name FROM users WHERE BINARY name != ?');
			mysqli_stmt_bind_param($query, 's', $username);
		}
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $r);
		$i = 0;
		$result = array();
		while(mysqli_stmt_fetch($query)){
			$result[$i] = "<option value=\"".$r."\">\n"; $i++;
		}
		
		return $result;
	}
