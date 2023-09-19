<?php

function do_header($title)
{

global $conn;

?>

<!DOCTYPE html>
<html>
<head>
	<title>G.M.C. - <?php echo $title; ?></title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="open-iconic-master/css/open-iconic.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="icon" type="image/jpg" href="img/gmc_logo.jpg">
</head>
<body>

<div class="container mt-5 pt-4 pb-4 rounded" style="background-color: #fcfbf7;">
	<div class="row justify-content-center">
		<div class="col-11 col-md-6">
			<div id="gmc-logo">
				<img src="img/gmc_logo.jpg">
			</div>
		</div>

		<div class="col-11 col-md-5 mt-4">
			<div class="p-4 rounded text-center text-md-right">
				<p>Fake City | Industrial Distric, Address, N° 1234</p>
				<p>ZIP Code 12345-678 | Fake City / FS</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-11 mx-auto">
			<nav class="navbar navbar-inverse navbar-toggleable mt-4" style="background-color: #3a362b;">
				<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-content" aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbar-content">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo BASE_URL; ?>">Home</a>
						</li>

						<li class="nav-item mx-2 my-auto d-none d-sm-inline">
							<img src="img/box.png" class="nav-item-box">
						</li>

						<li class="nav-item">
							<a class="nav-link" href="portfolio.php">Portfólio</a>
						</li>

						<li class="nav-item mx-2 my-auto d-none d-sm-inline">
							<img src="img/box.png" class="nav-item-box">
						</li>

						<li class="nav-item">
							<a class="nav-link" href="contato.php">Contato</a>
						</li>
					</ul>

					<form action="#" method="POST" id="form-search" class="my-auto">
						<div id="nav-input-search">
							<input type="text" id="input-search"></input>

							<button type="submit">
								<span class="oi oi-align-center" data-glyph="zoom-in"></span>
							</button>
						</div>
					</form>
				</div>
			</nav>
		</div>
	</div>

	<div class="row mt-4 justify-content-center">
		<div class="col-11 col-md-8 mb-md-0">
			<div id="carousel" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
					<?php

					$limit = 6;

					$sql = "SELECT COUNT(*) FROM slide;";
					$query = $conn->query($sql);

					$num_rows = $query->fetch_array();
					$num_rows = min($limit, $num_rows[0]);

					for ($i = 0; $i < $num_rows; $i++)
					{
						switch ($i)
						{
							case 0:
								echo "<li data-target=\"#carousel\" data-slide-to=\"$i\" class=\"active\"></li>";
								break;

							default:
								echo "<li data-target=\"#carousel\" data-slide-to=\"$i\"></li>";
								break;
						}
					}

					?>
				</ol>

				<div class="carousel-inner" role="listbox">

					<?php

					$sql = "SELECT imagem, titulo, descricao FROM slide ORDER BY RAND() LIMIT 0, $limit;";
					$query = $conn->query($sql);

					$active = true;

					while ($row = $query->fetch_array())
					{
						switch ($active)
						{
							case true:
								echo "<div class=\"carousel-item active\">";
								$active = false;
								break;

							case false:
								echo "\n<div class=\"carousel-item\">";
								break;
						}

						echo "\n<img class=\"d-block img-fluid\" src=\"$row[0]\">
						<div class=\"carousel-caption\">
								<h3>$row[1]</h3>
								<p>$row[2]</p>
							</div>
						</div>";
					}

					?>
				</div>

				<a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Anterior</span>
				</a>

				<a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Próximo</span>
				</a>
			</div>
		</div>

		<div class="col-11 mt-4 col-md-3 mt-md-0">
			<div id="sidebar">
				<a href="<?php echo BASE_URL; ?>?tag=Mármore">Mármores<div class="float-right">-</div></a>
				<a href="<?php echo BASE_URL; ?>?tag=Granito">Granitos<div class="float-right">-</div></a>
				<a href="<?php echo BASE_URL; ?>">Mármores & Granitos<div class="float-right">-</div></a>
				<a href="<?php echo BASE_URL; ?>tags.php">Todas as tags<div class="float-right">-</div></a>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-11 mx-auto my-4">
			<h4 class="h4-title"><?php echo $title; ?></h4>
		</div>
	</div>

<?php

}

function do_footer()
{

?>

<div class="row">
	<div class="col-11 mx-auto">
		<footer class="p-3 mt-4">
			<ul>
				<li>
					<a href="<?php echo BASE_URL; ?>">Home</a>
				</li>

				<li>
					<a href="portfolio.php">Portfólio</a>
				</li>

				<li>
					<a href="contato.php">Contato</a>
				</li>
			</ul>
		</footer>
	</div>
</div>

<div class="text-center mt-3" style="font-size: 14px;">
	Copyright &copy; G.M.C. Granitos e Mármores Carlinhos <?php echo date("Y"); ?>
</div>

</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#form-search').submit(function(e) {
			e.preventDefault();

			var k = $('#input-search').val();
			var href = "<?php echo BASE_URL ?>?k=" + k;
			window.location.href = href;
		})
	})
</script>

<?php

global $search;
if (!empty($search))
	echo "<script>document.getElementById('input-search').value = '$search';</script>";

?>

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

?>