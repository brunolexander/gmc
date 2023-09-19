<?php

include_once ('config.php');
include_once ('include/functions.php');

do_header("Portfólio");

$page = isset($_GET['p']) ? $_GET['p'] : 1;
if (!is_numeric($page)) $page = 1;

$sql = "SELECT COUNT(*) FROM portfolio;";
$query = $conn->query($sql);

$num_rows = $query->fetch_array();
$num_rows = $num_rows[0];
$max_rows = 12;
$num_pages = ceil($num_rows / $max_rows);
$page = clamp($page, 1, $num_pages);
$min_rows = ($page * $max_rows) - $max_rows;

$sql = "SELECT imagem, descricao, titulo FROM portfolio ORDER BY titulo ASC LIMIT $min_rows, $max_rows;";
$query = $conn->query($sql);

if ($query->num_rows > 0)
{
	$count = 0;
	$num_rows = ceil($query->num_rows / 3);

	while ($row = $query->fetch_array())
	{
		if ($count == 0)
			echo "\n<div class=\"row justify-content-around mb-5\">";

		echo "\n\n<div class=\"col-10 col-md-3 my-3 my-md-0 col-item\">
	<img src=\"$row[0]\" alt=\"$row[2]\">
	<p><span class=\"oi oi-align-center\" data-glyph=\"double-quote-sans-right\"></span> <i>$row[1]</i></p>
	<h4 class=\"mt-3\">$row[2]</h4>
</div>";

		$count++;

		if ($count == 3)
		{
			echo "\n\n</div>";
			$count = 0;
			$num_rows--;
		}
	}

	if ($num_rows > 0)
		echo "\n\n</div>";

	pagination($page, $num_pages, "portfolio.php?");
}
else
	echo "<div class=\"text-center\">Nenhum registro foi encontrado no banco de dados.</div>";

do_footer();

?>