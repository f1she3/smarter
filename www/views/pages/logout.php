<?php

	rm_notifications($_SESSION['name']);
	unset($_SESSION);
	session_destroy();
	redirect(0);
