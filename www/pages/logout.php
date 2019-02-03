<?php

rm_notifications($_SESSION['username']);
session_destroy();
redirect(0);
