<?php

function do_header($title, $active = "", $sublink = 0)
{

global $conn;

$id = is_user_logged();
$sql = "SELECT nome FROM usuarios WHERE id = $id;";
$query = $conn->query($sql);
$user = $query->fetch_array();

?>

<!DOCTYPE html>
<html>
<head>
	<title>G.M.C. - <?php echo $title; ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>open-iconic-master/css/open-iconic.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-inverse fixed-top bg-inverse">
<a href="index.php" class="navbar-brand w-25">Painel de controle</a>

<ul class="navbar-nav ml-auto" style="width: 160px; margin-top: -37px;">
	<li class="dropdown">
		<a href="dropdown-toggle" class="dropdown-toggle nav-link" data-toggle="dropdown">
			<?php

			$user = get_session('user');

			$sql = "SELECT nome, acesso FROM usuarios WHERE id = $user;";
			$query = $conn->query($sql);

			$row = $query->fetch_array();
			$row[0] = "<span class=\"oi oi-align-center\" data-glyph=\"person\"></span> " . $row[0];
			echo $row[0];

			?>
			</a>

			<ul class="dropdown-menu mt-2">
				<li class="dropdown-item">
					<a href="usuarios.php?a=perfil"><span class="oi oi-align-center" data-glyph="cog"></span> Perfil</a>
				</li>

				<div class="dropdown-divider"></div>

				<li class="dropdown-item">
					<a href="login.php?do=logout"><span class="oi oi-align-center" data-glyph="power-standby"></span> Desconectar</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>

<div class="container-fluid">
	<div class="row justify-content-end">
		<nav class="col-sm-3 col-md-2 hidden-xs-down bg-inverse sidebar">
			<ul class="nav flex-column">
				<li class="nav-item">
					<a class="nav-link <?php if ($active == 'painel') echo 'active'; ?>" href="index.php"><span class="oi oi-align-center" data-glyph="dashboard"></span> Painel</a>
				</li>
				
				<li class="nav-item">
					<a href="javascript:;" class="nav-link <?php if ($active == 'pedras') echo 'active'; ?>" <?php if ($active != 'pedras') echo "data-toggle='collapse'"; ?> data-target="#dropdown-pedras">
						<span class="oi oi-align-center" data-glyph="grid-two-up"></span> Pedras <span class="oi oi-align-center" data-glyph="<?php if ($active == 'pedras') echo 'chevron-left'; else echo 'chevron-bottom'; ?>"></span></a>
					</a>

					<ul id="dropdown-pedras" class="<?php if ($active != 'pedras') echo 'collapse'; ?> list-unstyled">
						<li class="nav-item">
							<a href="pedras.php" class="nav-link <?php if ($active == 'pedras' && $sublink == 1) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="eye"></span> Ver todas</a>
						</li>

						<li class="nav-item">
							<a href="pedras.php?a=adicionar" class="nav-link <?php if ($active == 'pedras' && $sublink == 2) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="plus"></span> Adicionar</a>
						</li>
					</ul>
				</li>

				<li class="nav-item">
					<a href="javascript:;" class="nav-link <?php if ($active == 'portfolio') echo 'active'; ?>" <?php if ($active != 'portfolio') echo "data-toggle='collapse'"; ?> data-target="#dropdown-portfolio">
						<span class="oi oi-align-center" data-glyph="image"></span> Portfólio <span class="oi oi-align-center" data-glyph="<?php if ($active == 'portfolio') echo 'chevron-left'; else echo 'chevron-bottom'; ?>"></span></a>
					</a>

					<ul id="dropdown-portfolio" class="<?php if ($active != 'portfolio') echo 'collapse'; ?> list-unstyled">
						<li class="nav-item">
							<a href="portfolio.php" class="nav-link <?php if ($active == 'portfolio' && $sublink == 1) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="eye"></span> Ver tudo</a>
						</li>

						<li class="nav-item">
							<a href="portfolio.php?a=adicionar" class="nav-link <?php if ($active == 'portfolio' && $sublink == 2) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="plus"></span> Adicionar</a>
						</li>
					</ul>
				</li>

				<li class="nav-item">
					<a href="javascript:;" class="nav-link <?php if ($active == 'slides') echo 'active'; ?>" <?php if ($active != 'slides') echo "data-toggle='collapse'"; ?> data-target="#dropdown-slides">
						<span class="oi oi-align-center" data-glyph="play-circle"></span> Slides <span class="oi oi-align-center" data-glyph="<?php if ($active == 'slides') echo 'chevron-left'; else echo 'chevron-bottom'; ?>"></span></a>
					</a>

					<ul id="dropdown-slides" class="<?php if ($active != 'slides') echo 'collapse'; ?> list-unstyled">
						<li class="nav-item">
							<a href="slides.php" class="nav-link <?php if ($active == 'slides' && $sublink == 1) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="eye"></span> Ver todos</a>
						</li>

						<li class="nav-item">
							<a href="slides.php?a=adicionar" class="nav-link <?php if ($active == 'slides' && $sublink == 2) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="plus"></span> Adicionar</a>
						</li>
					</ul>
				</li>

				<?php if (check_access($user, 'Administrador', false)) { ?>

				<li class="nav-item">
					<a href="javascript:;" class="nav-link <?php if ($active == 'usuarios') echo 'active'; ?>" <?php if ($active != 'usuarios') echo "data-toggle='collapse'";?> data-target="#dropdown-usuarios"><span class="oi oi-align-center" data-glyph="people"></span> Usuários <span class="oi oi-align-center" data-glyph="<?php if ($active == 'usuarios') echo 'chevron-left'; else echo 'chevron-bottom'; ?>"></span></a>
					</a>

					<ul id="dropdown-usuarios" class="<?php if ($active != 'usuarios') echo 'collapse'; ?> list-unstyled">
						<li class="nav-item">
							<a href="usuarios.php" class="nav-link <?php if ($active == 'usuarios' && $sublink == 1) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="eye"></span> Ver todos</a>
						</li>

						<li class="nav-item">
							<a href="usuarios.php?a=adicionar" class="nav-link <?php if ($active == 'usuarios' && $sublink == 2) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="plus"></span> Adicionar</a>
						</li>

						<li class="nav-item">
							<a href="usuarios.php?a=perfil" class="nav-link <?php if ($active == 'usuarios' && $sublink == 3) echo 'text-white font-italic'; ?>"><span class="oi oi-align-center" data-glyph="person"></span> Meu perfil</a>
						</li>
					</ul>
				</li>

				<?php } ?>
			</ul>
		</nav>

		<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
			<h2 class="mb-4"><?php echo $title; ?></h2>
<?php

}

function do_footer($js = '')
{

?>

		</main>
	</div>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>tether/js/tether.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>

<?php echo $js; ?>

</body>
</html>

<?php

}

function pagination($page, $num_pages, $href)
{

$href = BASE_URL . $href;

echo "<div class=\"row\">
<div class=\"col-11 mx-auto\">
<ul class=\"pagination justify-content-center\">";

$back_page = $page - 1;

if ($back_page > 0)
{
	echo "<li class=\"page-item\">
		<a href=\"" . $href . "p=$back_page\" class=\"page-link\">&laquo;</a>
	</li>";
}
else
{
	echo "<li class=\"page-item disabled\">
		<a href=\"#\" class=\"page-link\">&laquo;</a>
	</li>";
}

if ($page > 5)
{
	echo "<li class=\"page-item\">
		<a href=\"" . $href . "p=1\" class=\"page-link\">1</a>
	</li>";
}

for ($i = max(1, $page - 4); $i <= min($num_pages, $page + 4); $i++)
{
	switch ($i)
	{
		case $page:
			echo "<li class=\"page-item active\">
				<a href=\"" . $href . "p=$i\" class=\"page-link\">$i</a>
			</li>";
			break;

		default:
			echo "<li class=\"page-item\">
				<a href=\"" . $href . "p=$i\" class=\"page-link\">$i</a>
			</li>";
			break;

	}
}

if ($page <= $num_pages - 5)
{
	echo "<li class=\"page-item\">
		<a href=\"" . $href . "p=$num_pages\" class=\"page-link\">$num_pages</a>
	</li>";
}

$next_page = $page + 1;

if ($next_page <= $num_pages)
{
	echo "<li class=\"page-item\">
		<a href=\"" . $href . "p=$next_page\" class=\"page-link\">&raquo;</a>
	</li>";
}
else
{
	echo "<li class=\"page-item disabled\">
		<a href=\"#\" class=\"page-link\">&raquo;</a>
	</li>";
}

echo "</ul>
</div>
</div>";

}

function clamp($value, $minvalue, $maxvalue)
{
	if ($value <= $minvalue)
		return $minvalue;

	if ($value >= $maxvalue)
		return $maxvalue;

	return $value;
}

function check_access($user, $flag, $page = true)
{
	global $conn;

	$sql = "SELECT acesso FROM usuarios WHERE id = $user;";
	$query = $conn->query($sql);

	$row = $query->fetch_array();

	if ($row[0] == $flag)
		return true;

	if ($page)
	{
?>

<!DOCTYPE html>
<html>
<head>
	<title>G.M.C. - Acesso restrito</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
</head>
<body style="background-color: #f1f1f1;">
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-9 p-5 rounded" style="background-color: #fff; margin-top: 60px;">
				<p></p>
				<h1>Oops!</h1>
				<hr>
				<p>Você não tem acesso à esta página.</p>
			</div>
		</div>
	</div>
</body>
</html>

<?php

	}

	return false;
}

function do_alert($class, $message)
{

?>

<div class="alert <?php echo $class; ?>">
	<span class="oi oi-align-center mr-2" data-glyph="warning"></span>
	<?php echo $message; ?>
	<span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span>
</div>

<?php

}

function insert_log($log)
{
	global $conn;
	global $user;

	# Get user name
	$sql = "SELECT acesso, nome FROM usuarios WHERE id = $user;";
	$query = $conn->query($sql);
	$row = $query->fetch_array();

	# Insert log message
	$log = "$row[0] <b>$row[1]</b> $log.";
	$sql = "INSERT INTO log VALUES ('$log', DEFAULT);";
	$conn->query($sql);
}

?>