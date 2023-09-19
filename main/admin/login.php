<?php

ob_start();

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

require_once (dirname(dirname(__FILE__)) . '/config.php');

function do_login_form($alert = '', $login = '')
{

?>

<form action="login.php?do=login" method="post" class="p-5">
	<div class="text-center mt-2 mb-5 w-100">
		<img src="img/user.png" style="width: 100px;">

		<h4 class="mt-4">Painel de administração</h4>
		<p>Granitos e Mármores Carlinhos</p>
	</div>

	<?php if (!empty($alert)) { ?><div class="alert alert-info"><span class="oi oi-align-center mr-2" data-glyph="warning"></span><?php echo $alert; ?><span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span></div><?php } ?>

	<input type="text" class="form-control my-3" placeholder="Usuário ou email" name="login" value=<?php echo '"' . $login . '"'; ?> required></input>
	<input type="password" class="form-control my-3" placeholder="Senha" name="password" required></input>
	<button type="submit" class="btn" style="background-color: #1c1c1c; color: #ccc;">Login</button>
</form>

<?php

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>G.M.C. - Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>open-iconic-master/css/open-iconic.min.css">

	<style type="text/css">
		body {
			background-color: #f4f4f4;
		}

		form {
			background-color: #fff;
			box-shadow: 1px 1px 4px #ccc;
			min-width: 250px;
		}

		@media (min-height: 568px) {
			form {
				margin-top: 3.5em;
			}
		}
	</style>
</head>
<body>

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-7 col-md-5">

		<?php

		@$do = $_GET['do'];

		switch ($do)
		{
			case 'login':
				$login = isset($_POST['login']) ? $_POST['login'] : '';
				$password = isset($_POST['password']) ? md5($_POST['password']) : '';

				$alert = "";

				if (!empty($login) && !empty($password))
				{
					$sql = "";

					switch (strpos($login, "@"))
					{
						case false:
							$sql = "SELECT id FROM usuarios WHERE login LIKE '$login' AND senha = '$password';";
							break;

						case true:
							$sql = "SELECT id FROM usuarios WHERE email LIKE '$login' AND senha = '$password';";
							break;
					}
					
					$query = $conn->query($sql);

					if ($query->num_rows > 0)
					{
						$row = $query->fetch_array();
						$_SESSION['user'] = $row[0];
						
						ob_end_clean();
						header('Location: index.php', true, 303);
						exit;
					}
					
					$alert = "Login e/ou senha incorreto(s).";
				}

				do_login_form($alert, $login);
				break;

			case 'logout':
				unset($_SESSION['user']);
				session_destroy();
				do_login_form("Você se desconectou.");
				break;

			default:
				do_login_form();
				break;
		}


		?>

		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>

</body>
</html>

<?php ob_end_flush(); ?>