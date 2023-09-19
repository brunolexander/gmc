<?php

include_once ('config.php');
include_once ('include/functions.php');

$page = isset($_GET["p"]) ? $_GET["p"] : 1;
$tag = isset($_GET["tag"]) ? $_GET["tag"] : "";
$search = isset($_GET["k"]) ? $_GET["k"] : "";
$title = "Mámores & Granitos";

if (!is_numeric($page))
	$page = 1;

$sql = "";
$tag_results = 0;

if (!empty($search))
{
	$title = "Resultados da busca: \"$search\"";

	$sql = "SELECT (SELECT COUNT(*) FROM portfolio WHERE portfolio.titulo LIKE '%$search%' OR portfolio.descricao LIKE '%$search%') + (SELECT COUNT(*) FROM pedras LEFT JOIN tag_relac ON pedras.id = tag_relac.id_pedra LEFT JOIN tag ON tag_relac.id_tag = tag.id WHERE tag.nome LIKE '%$search%' OR pedras.nome LIKE '%$search%') AS total FROM DUAL;";
}
else if (!empty($tag))
{
	$title = "Tag: \"$tag\"";

	$tag = strtolower($tag);
	$sql = "SELECT * FROM tag WHERE nome = '$tag';";
	$query = $conn->query($sql);
	$tag = $query->fetch_array();

	$sql = "SELECT COUNT(id_pedra) FROM tag_relac WHERE id_tag = $tag[0];";
}
else
	$sql = "SELECT COUNT(*) FROM pedras;";

do_header($title);

$query = $conn->query($sql);

$num_rows = $query->fetch_array();
$num_rows = $num_rows[0];
$max_rows = 15;
$num_pages = ceil($num_rows / $max_rows);
$page = clamp($page, 1, $num_pages);
$min_rows = ($page * $max_rows) - $max_rows;

if ($num_rows > 0)
{
	if (!empty($search))
	{
		$sql = "SELECT pedras.nome, pedras.imagem, GROUP_CONCAT(DISTINCT tag.nome ORDER BY tag.nome ASC SEPARATOR ', ') AS tag_nome FROM pedras LEFT JOIN tag_relac ON pedras.id = tag_relac.id_pedra LEFT JOIN tag ON tag_relac.id_tag = tag.id GROUP BY pedras.id HAVING pedras.nome LIKE '%$search%' OR tag_nome LIKE '%$search%' ORDER BY pedras.nome ASC LIMIT $min_rows, $max_rows;";
		$query = $conn->query($sql);

		if ($query->num_rows > 0)
		{
			echo "<p class=\"text-center\">Resultados relacionados à pedra:</p><hr>";

			$count = 0;
			$num_rows = ceil($query->num_rows / 3);

			while ($row = $query->fetch_array())
			{
				$row[2] = ucfirst(strtolower($row[2]));
					
				if ($count == 0)
					echo "\n<div class=\"row justify-content-around mb-5\">";

				echo "\n\n<div class=\"col-10 col-md-3 my-3 my-md-0 col-item\">
					<img src=\"$row[1]\" alt=\"$row[0]\">
					<h4 class=\"mt-3\"> $row[0]</h4>
					<p style=\"font-size: 13px;\"><span class=\"oi oi-align-center\" data-glyph=\"tags\"></span> <i>$row[2]</i></p>
				</div>";

				$count++;

				if ($count == 3)
				{
					echo "\n\n</div>";
					$count = 0;
					$num_rows--;
				}

				$max_rows--;
			}

			if ($num_rows > 0)
				echo "\n\n</div>";
		}

		$sql = "SELECT DISTINCT titulo, descricao, imagem FROM portfolio WHERE titulo LIKE '%$search%' OR descricao LIKE '%$search%' ORDER BY titulo ASC LIMIT $min_rows, $max_rows;";
		$query = $conn->query($sql);

		echo $min_rows . "<br>";
		echo $max_rows;

		if ($query->num_rows > 0)
		{
			echo "<p class=\"text-center\">Resultados relacionados ao portfolio:</p><hr>";

			echo "<div class=\"row col-11 justify-content-around mb-5 mx-auto\">";

			while ($row = $query->fetch_array())
			{
				echo "\n\n<div class=\"col-10 col-md-3 my-3 my-md-0 col-item\">
					<img src=\"$row[2]\" alt=\"$row[0]\">
					<p><span class=\"oi oi-align-center\" data-glyph=\"double-quote-sans-right\"></span> <i>$row[1]</i></p>
					<h4 class=\"mt-3\">$row[0]</h4>
				</div>";
			}

			echo "</div>";
		}
	}
	else
	{
		if (!empty($tag))
		{
			$sql = "SELECT pedras.nome, pedras.imagem, GROUP_CONCAT(DISTINCT tag.nome ORDER BY tag.nome ASC SEPARATOR ', ') AS tag_nome FROM pedras LEFT JOIN tag_relac ON pedras.id = tag_relac.id_pedra LEFT JOIN tag ON tag_relac.id_tag = tag.id WHERE $tag[0] IN (SELECT id_tag FROM tag_relac WHERE id_pedra = pedras.id) GROUP BY pedras.id ORDER BY pedras.nome ASC LIMIT 0, 15";
		}
		else
		{
			$sql = "SELECT pedras.nome, pedras.imagem, GROUP_CONCAT(DISTINCT tag.nome ORDER BY tag.nome ASC SEPARATOR ', ') AS tag_nome FROM pedras LEFT JOIN tag_relac ON pedras.id = tag_relac.id_pedra LEFT JOIN tag ON tag_relac.id_tag = tag.id GROUP BY pedras.id ORDER BY pedras.nome ASC LIMIT $min_rows, $max_rows";
		}
		
		$query = $conn->query($sql);

		$count = 0;
		$num_rows = ceil($query->num_rows / 3);

		while ($row = $query->fetch_array())
		{
			$row[2] = ucfirst(strtolower($row[2]));
				
			if ($count == 0)
				echo "\n<div class=\"row justify-content-around mb-5\">";

			echo "\n\n<div class=\"col-10 col-md-3 my-3 my-md-0 col-item\">
				<img src=\"$row[1]\" alt=\"$row[0]\">
				<h4 class=\"mt-3\"> $row[0]</h4>
				<p style=\"font-size: 13px;\"><span class=\"oi oi-align-center\" data-glyph=\"tags\"></span> <i>$row[2]</i></p>
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
	}

	$link = "";

	if (!empty($search))
		$link = "?k=$search&";
	else if (!empty($tag))
		$link = "?tag=$tag[1]&";
	else
		$link = "?";

	pagination($page, $num_pages, $link);
}
else
	echo "<div class=\"text-center\">Nenhum registro foi encontrado no banco de dados.</div>";

do_footer();

?>