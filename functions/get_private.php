<?php

	function rm_notification($sender, $contact){
		$mysqli = get_link();
		$one = 1;
		$query = mysqli_prepare($mysqli, 'DELETE FROM private WHERE sender = ? and contact = ? AND is_typing = ?');
		mysqli_stmt_bind_param($query, 'ssi', $sender, $contact, $one);
		mysqli_stmt_execute($query);
	}
