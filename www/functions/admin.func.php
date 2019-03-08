<?php

function display_admin_dashboard($username){
	$mysqli = get_link();
	$query = mysqli_prepare($mysqli, 'SELECT username FROM users WHERE BINARY username = ?');
	mysqli_stmt_bind_param($query, 's', $username);
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $name);
	mysqli_stmt_fetch($query);
	echo "<h3 class=\"text-center\"><a href=\"".$_SESSION['host'].constant('BASE_URL')."admin\" style=\"color:#000\">Administration</a></h3>
		<h3 class=\"text-center col-md-12\"><a href=\"".$_SESSION['host'].constant('BASE_URL')."account&user=".$username."\" target=\"_blank\">".$username."</a></h3>
		<div class=\"col-md-10\">
			<h3>Sanctions</h3><hr>
		</div>
		<form method=\"POST\" action=\"\">
			<div class=\"form-group col-md-5\">
				<input type=\"number\" name=\"mute_min\" min=\"0\" max=\"59\" placeholder=\"min\" class=\"form-control\">
				<input type=\"number\" name=\"mute_hour\" min=\"0\" max=\"23\" placeholder=\"hours\" class=\"form-control\">
				<input type=\"number\" name=\"mute_day\" min=\"0\" max=\"365\" placeholder=\"days\" class=\"form-control\"><br>";
	if(is_mute($username)){
		echo "<button name=\"submit_demute\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-volume-up\" style=\"font-size:16px;\"></span></button>";
	}else{
		echo "<button name=\"submit_mute\" class=\"btn btn-warning\"><span class=\"glyphicon glyphicon-volume-off\" style=\"font-size:16px;\"></span></button>";
	}
	echo "	</div>
		</form>
		<form method=\"POST\" action=\"\">
			<div class=\"form-group col-md-4 col-md-offset-1\">";
	$ret_is_banned = is_banned($username);
	if($ret_is_banned){
		echo "<input type=\"text\" name=\"ban_message\" class=\"form-control\" placeholder=\"Message\" maxlength=\"50\" value=\"".$ret_is_banned['message']."\"><br>";
		echo "<button name=\"submit_deban\" class=\"btn btn-success col-md-4\">Débannir</button>";
	
	}else{
		echo "<input type=\"text\" name=\"ban_message\" class=\"form-control\" placeholder=\"Message\" maxlength=\"50\" required><br>";
		echo "<button name=\"submit_ban\" class=\"btn btn-danger col-md-4\">Bannir</button>";
	}
	echo "</div>
		</form>";
		$my_rank = get_rank($_SESSION['username']);
		$user_rank = get_rank($username);
		echo "<form method=\"POST\" action=\"\">
				<div class=\"col-md-10\">
					<h3>Grade</h3><hr>
				</div>
				<div class=\"form-group col-md-4\">
					<select name=\"rank\" class=\"form-control\">";
		$ranks = get_rank_list();
		if($my_rank == $ranks['max']){
			// There should only be one "super root"
			$my_rank = $my_rank - 1;	
		}
		for($i = 0; $i <= $my_rank; $i++){
			if($i == $user_rank){
				echo "<option value=\"".$i."\" selected>".$ranks[$i]."</option>";
				
			}else{
				echo "<option value=\"".$i."\">".$ranks[$i]."</option>";
			}
		}
		echo "		</select><br>
					<button name=\"submit_rank\" type=\"submit\" class=\"btn btn-info col-md-4\">Changer</button>
				</div>
			</form>\n";
}
function server_infos(){
	$mysqli = get_link();
	$query = mysqli_query($mysqli, 'SELECT username FROM users');
	$i = 0;
	while($result = mysqli_fetch_assoc($query)){
		$i++;	
	}
	$result['mysql_version'] = mysqli_get_server_info($mysqli);
	$result['php_version'] = phpversion();
	$result['os_version'] = php_uname('s').' '.php_uname('r');
	$result['os_version_long'] = php_uname();
	$result['member_count'] = $i;

	return $result;
}
function display_server_infos(){
	echo	"<h3 class=\"text-center\">Administration</h3><hr>
		<form method=\"POST\" action=\"\" name=\"search_user\">
			<div class=\"form-group col-md-6 col-md-offset-3\">
				<input type=\"search\" list=\"search_users\" name=\"search\" class=\"form-control\" maxlength=\"25\" placeholder=\"chercher un membre\" autofocus>
			</div>
		</form>
		<input type=\"hidden\" value=\"".$_SESSION['host'].constant('BASE_URL')."\">
		<datalist id=\"search_users\">";
	$ret_datalist_options = datalist_options($_SESSION['username'], false);
	$i = 0;
	while(isset($ret_datalist_options[$i])){
		echo $ret_datalist_options[$i];
		$i++;
	}
	echo "</datalist>";
	$infos = server_infos();
	if($infos['member_count'] > 1){
		$text = 'Membres';
	
	}else{
		$text = 'Membre';
	}
	echo "<div class=\"col-md-12\">
			<pre style=\"border-radius:10px\">
				<ul>
					<li style=\"font-size:18px\">".$text." : ".$infos['member_count']."</li>
					<li style=\"font-size:18px\">PHP : ".$infos['php_version']."</li>
					<li style=\"font-size:18px\">MySQL : ".$infos['mysql_version']."</li>
					<li style=\"font-size:18px\">OS : <abbr title=\"".$infos['os_version_long']."\">".$infos['os_version']."</abbr></li>
				</ul>
			</pre>
		</div>";
}
function is_number($input){
	if(preg_match("#^[0-9]{1,}$#", $input)){
		return true;
	
	}else{
		return false;
	}
}
