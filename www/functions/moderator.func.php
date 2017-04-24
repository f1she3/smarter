<?php

	function display_moderator_dashboard($username){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT name FROM users WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $username);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $name);
		mysqli_stmt_fetch($query);
		echo "<h3 class=\"text-center page-header\"><a href=\"".$_SESSION['host'].constant('BASE_URL')."moderator\" style=\"color:#000\">Modération</a></h3>
			<h3 class=\"text-center col-md-12\"><a href=\"".$_SESSION['host'].constant('BASE_URL')."account&user=".$username."\" target=\"_blank\">".$username."</a></h3>
			<div class=\"col-md-10\">
				<h3 class=\"page-header\">Sanctions</h3>
			</div>
			<form method=\"POST\" action=\"\" name=\"dashboard\">
				<div class=\"form-group col-md-4\">
					<input type=\"number\" name=\"mute_min\" min=\"0\" max=\"59\" placeholder=\"min\" class=\"form-control\">
					<input type=\"number\" name=\"mute_hour\" min=\"0\" max=\"23\" placeholder=\"hours\" class=\"form-control\">
					<input type=\"number\" name=\"mute_day\" min=\"0\" max=\"365\" placeholder=\"days\" class=\"form-control\"><br>";
		if(is_mute($username)){
			echo "<button name=\"submit_demute\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-volume-up\" style=\"font-size:16px;\"></span></button>";
		}else{
			echo "<button name=\"submit_mute\" class=\"btn btn-warning\"><span class=\"glyphicon glyphicon-volume-off\" style=\"font-size:16px;\"></span></button>";
		}
		echo "</div>";
		$my_rank = get_rank($_SESSION['name']);
		$user_rank = get_rank($username);
		// At least chief moderator
		if($my_rank > 1){
			echo "<div class=\"col-md-10\">
					<h3 class=\"page-header\">Grade</h3>
				</div>
				<div class=\"form-group col-md-4\">
					<select name=\"rank\" class=\"form-control\">";
			$ranks = get_rank_list();
			if($my_rank == $ranks['max']){
				// There should only be one super root administrator
				$my_rank = $my_rank - 1;	
			}
			for($i = 0; $i <= $my_rank; $i++){
				if($i == $user_rank){
					echo "<option value=\"".$i."\" selected>".$ranks[$i]."</option>";
				
				}else{
					echo "<option value=\"".$i."\">".$ranks[$i]."</option>";
				}
			}
			echo "	</select><br>
						<button name=\"submit_rank\" type=\"submit\" class=\"btn btn-info col-md-4\">Changer</button>
					</div>
				</form>\n";
		}
	}
	function moderator_infos(){
		$mysqli = get_link();
		$query = mysqli_query($mysqli, 'SELECT name FROM users');
		$i = 0;
		while($var = mysqli_fetch_assoc($query)){
			$i++;	
		}
		$result['member_count'] = $i;
		$query = mysqli_query($mysqli, 'SELECT id FROM users WHERE rank = 1 OR rank = 2');
		$i = 0;
		while($var = mysqli_fetch_assoc($query)){
			$i++;	
		}
		$result['modo_count'] = $i;
		$query = mysqli_query($mysqli, 'SELECT id FROM users WHERE rank = 3 OR rank = 4');
		$i = 0;
		while($var = mysqli_fetch_assoc($query)){
			$i++;	
		}
		$result['admin_count'] = $i;
		$query = mysqli_query($mysqli, 'SELECT id FROM mute');
		$i = 0;
		while($var = mysqli_fetch_assoc($query)){
			$i++;	
		}
		$result['mute_count'] = $i;

		return $result;
	}
	function display_moderator_infos(){
		echo	"<div class=\"page-header\">
					<h3 class=\"text-center\">Modération</h3>
			</div>
			<form method=\"POST\" action=\"\" name=\"search_user\">
				<div class=\"form-group col-md-6 col-md-offset-3\">
					<input type=\"search\" list=\"search_users\" name=\"search\" class=\"form-control\" maxlength=\"25\" placeholder=\"chercher un membre\" autofocus>
				</div>
			</form>
			<input type=\"hidden\" value=\"".$_SESSION['host'].constant('BASE_URL')."\">
			<datalist id=\"search_users\">";
		$ret_datalist_options = datalist_options($_SESSION['name'], get_rank($_SESSION['name']));
		$i = 0;
		while(isset($ret_datalist_options[$i])){
			echo $ret_datalist_options[$i];
			$i++;
		}
		echo "</datalist>";
		$infos = moderator_infos();
		if($infos['member_count'] > 1){
			$member_txt = 'membres';
		
		}else{
			$member_txt = 'membre';
		}
		if($infos['modo_count'] > 1){
			$modo_txt = 'modérateurs';
		
		}else{
			$modo_txt = 'modérateur';
		}
		if($infos['admin_count'] > 1){
			$admin_txt = 'administrateurs';
		
		}else{
			$admin_txt = 'administrateur';
		}
		if($infos['mute_count'] > 1){
			$mute_txt_1 = 'Membres';
			$mute_txt_2 = 'muets';
		
		}else{
			$mute_txt_1 = 'Membre';
			$mute_txt_2= 'muet';
		}
		echo "<div class=\"col-md-12\">
				<pre style=\"border-radius:10px\">
					<ul>
						<li style=\"font-size:18px\">Nombre de ".$member_txt." : ".$infos['member_count']."</li>
						<li style=\"font-size:18px\">Nombre de ".$modo_txt." : ".$infos['modo_count']."</li>
						<li style=\"font-size:18px\">Nombre d'".$admin_txt." : ".$infos['admin_count']."</li>
						<li style=\"font-size:18px\">".$mute_txt_1." ".$mute_txt_2." : ".$infos['mute_count']."</li>
					</ul>
				</pre>
			</div>";
	}
