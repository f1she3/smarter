<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Open source real time web-based chat">
		<meta name="author" content="f1she3">
		<link rel="icon" href="<?= $_SESSION['host'].'/css/images/favicon.ico' ?>">
		<title><?= $title; ?></title>
		<script type="text/javascript" src="<?= $_SESSION['host'].'/js/jquery.min.js' ?>"></script>
		<script type="text/javascript" src="<?= $_SESSION['host'].'/js/bootstrap.min.js' ?>"></script>
		<link href="<?= $_SESSION['host'].'/css/bootstrap.min.css' ?>" rel="stylesheet">
		<link href="<?= $_SESSION['host'].'/css/style.css' ?>" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar-default navbar-inverse navbar-static-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
						<span class="sr-only"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
                    		</button>
					<a class="navbar-brand" href="<?= $_SESSION['host'].constant('BASE_URL').'home' ?>">
						Smarter
					</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right"> 
						<li class="<?php echo ($page == 'chat') ? 'active' : '' ?>">
							<a href="<?= $_SESSION['host'].constant('BASE_URL').'chat' ?>">
								<span class="glyphicon glyphicon-comment"></span>
								 CHAT
							</a>
						</li>	
						<li class="dropdown <?php echo ($page == 'account' || $page == 'configuration' || $page == 'contacts') ? 'active' : '' ?>">
							<a href="<?= $_SESSION['host'].constant('BASE_URL').'account' ?>" class="dropdown-togle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-user"></span>
								MON COMPTE <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Mon compte</li>
								<li><a href="<?= $_SESSION['host'].constant('BASE_URL').'account'?>">
									<span class="glyphicon glyphicon-info-sign"></span> Informations
								</a></li>
								<li><a href="<?= $_SESSION['host'].constant('BASE_URL').'contacts' ?>">
									<span class="glyphicon glyphicon-comment"></span> Amis
								</a></li>
								<li><a href="<?= $_SESSION['host'].constant('BASE_URL').'configuration' ?>">
									<span class="glyphicon glyphicon-lock"></span> Identifiants
								</a></li>
								<?php
									
									if(get_rank($_SESSION['name']) > 0){ 
										if(get_rank($_SESSION['name']) > 2){
											echo "<li>
													<a href=\"".$_SESSION['host'].constant('BASE_URL')."admin\">
														<span class=\"glyphicon glyphicon-wrench\"></span> Administration 
													</a>
												</li>";
											
										}else{
											echo "<li>
													<a href=\"".$_SESSION['host'].constant('BASE_URL')."moderator\">
														<span class=\"glyphicon glyphicon-volume-up\"></span> Modération
													</a>
												</li>";
										}
									}
								?>
								<li class="divider"></li>
								<li><a href="<?= $_SESSION['host'].constant('BASE_URL').'logout' ?>">
									<span class="glyphicon glyphicon-off"></span> Déconnexion
								</a></li>
							</ul>
						</li>                                              
					</ul>
				</div><!--/.nav-collapse -->
            	</div>
		</nav>
		<div class="container">
			<div id="event"></div>
