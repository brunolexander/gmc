<?php

function is_user_logged()
{
	@session_start();

	if (!isset($_SESSION['user']))
	{
		session_destroy();
		return -1;
	}

	return $_SESSION['user'];
}

function get_session($session)
{
	@session_start();

	if (!isset($_SESSION[$session]))
	{
		session_destroy();
		return -1;
	}

	return $_SESSION[$session];
}

?>