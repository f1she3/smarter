<?php
	
	if(isset($_POST['remove'])){
		$_POST['name'] = secure($_POST['name']);
		rm_friend($_SESSION['name'], $_POST['name'], $mysqli);
	}
?>
<h3 class="text-center">Mes amis</h3><hr>
<form method="POST" action="">
	<div class="form-group col-md-6 col-md-offset-3">
		<input list="search_contacts" type="search" name="search" class="form-control" maxlength="25" placeholder="chercher un membre" autofocus>
	</div>
</form>
<input type="hidden" value="<?= $_SESSION['host'] ?>">
<datalist id="search_contacts">
<?php

	$ret_datalist_options = datalist_options($_SESSION['name'], false);
	$i = 0;
	while(isset($ret_datalist_options[$i])){
		echo $ret_datalist_options[$i];
		$i++;
	}
?>
</datalist>
<div id="contact_list" class="col-md-6 col-md-offset-3">
	<?php	display_contacts($_SESSION['name']); ?>
</div>
