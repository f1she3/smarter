<?php
	
	if(isset($_POST['remove'])){
		$_POST['name'] = secure($_POST['name']);
		rm_friend($_SESSION['name'], $_POST['name'], $mysqli);
	}
?>
<div class="page-header">
	<h3 class="text-center">Mes amis</h3>
</div>
<form method="POST" action="">
	<div class="form-group col-md-6 col-md-offset-3">
		<input list="contacts_list" type="search" name="search" class="form-control" maxlength="25" placeholder="chercher un membre" autofocus>
	</div>
</form>
<input type="hidden" value="<?= $_SESSION['host'].constant('BASE_URL') ?>">
<datalist id="contacts_list">
<?php

	$ret_datalist_options = datalist_options($_SESSION['name'], false);
	$i = 0;
	while(isset($ret_datalist_options[$i])){
		echo $ret_datalist_options[$i];
		$i++;
	}
?>
</datalist>
<div id="contacts" class="col-md-6 col-md-offset-3">
	<?php	display_contacts($_SESSION['name']); ?>
</div>
