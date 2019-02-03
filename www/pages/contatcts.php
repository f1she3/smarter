<?php
	
if(isset($_POST['remove'])){
	if(isset($_POST['username']) && !empty($_POST['username']) && is_string($_POST['username']) && strlen($_POST['username']) <= 15){
		$_POST['username'] = secure($_POST['username']);
		rm_friend($_SESSION['username'], $_POST['username']);
	}
}
if(isset($_POST['search']) && !empty($_POST['search'] && is_string($_POST['search']))){
	$search = $_POST['search'] = secure($_POST['search']);
	redirect('private&user='.$search);
}
?>
<div class="page-header">
	<h3 class="text-center">Mes amis</h3>
</div>
<div class="icon">
	<a href="<?= constant('BASE_URL'); ?>block">
		<span class="glyphicon glyphicon-user"></span>
		<span class="glyphicon glyphicon-ban-circle"></span>
	</a>
</div>
<form method="POST" action="">
	<div class="form-group col-md-6 col-md-offset-3">
		<input list="contacts_list" type="search" name="search" class="form-control" maxlength="25" placeholder="chercher un utilisateur" autofocus required>
	</div>
</form>
<input type="hidden" value="<?= $_SESSION['host'].constant('BASE_URL') ?>">
<datalist id="contacts_list">
<?php

	$ret_datalist_options = datalist_options($_SESSION['username'], false);
	$i = 0;
	while(isset($ret_datalist_options[$i])){
		echo $ret_datalist_options[$i];
		$i++;
	}
?>
</datalist>
<?php	display_contacts($_SESSION['username']); ?>

