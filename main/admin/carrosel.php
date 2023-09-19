<?php

require_once ('include/restrict.php');

if (is_user_logged() == -1)
{
	header("Location: login.php", true, 303);
	exit();
}

require_once (dirname(dirname(__FILE__)) . '/config.php');

include ('include/functions.php');

do_header("Carrosel (slide)");

?>

<style type="text/css">
	td img {
		width: 170px;
		height: 100px;
	}

	.row-actions {
		color: #999;
		margin-top: 20px;
		font-size: 13px;
	}

	.row-actions a:hover {
		text-decoration: none;
		opacity: 0.4;
	}

	.row-actions .delete {
		color: red !important;
	}
</style>

<div class="mt-4 row justify-content-center">
	<div class="col-10">
		<table class="table table-stripped thead-inverse table-hover mx-auto">

		<thead>
			<th class="text-center"><input type="checkbox" class="check-all"></input></th>
			<th>Título</th>
			<th>Descrição</th>
			<th>Imagem</th>
		</thead>

		<tbody>
			<?php

			$sql = "SELECT titulo, descricao, imagem FROM carrosel;";
			$query = $conn->query($sql);

			if ($query->num_rows > 0)
			{
				while ($row = $query->fetch_array())
				{
					if (!stripos($row[2], "http://") && !stripos($row[2], "https://"))
						$row[2] = BASE_URL . $row[2];

					echo "\n<tr>
						\n<td class=\"text-center\"><input type=\"checkbox\"></input></td>
						\n<td>$row[0] <div class=\"row-actions\">
						<a href=\"#\" class=\"edit\"><span class=\"oi oi-align-center\" data-glyph=\"wrench\"></span> Editar</a> | <a href=\"#\" class=\"delete\"><span class=\"oi oi-align-center\" data-glyph=\"trash\"></span>Excluir</a></td>
						\n<td>$row[1]</td>
						\n<td class=\"text-center\"><img src=\"$row[2]\"></td>
					\n</tr>";
				}
			}
			else
				echo "<td colspan=\"3\">Nenhuma imagem está registrado no carrosel.</td>";

			?>
		</tbody>

		</table>
	</div>
</div>

<?php

$js = "<script type=\"text/javascript\">
	$(document).ready(function() {
		$('.check-all').click(function() {
			$('table td input[type=\"checkbox\"]').prop('checked', this.checked);
		});

		$('table td input[type=\"checkbox\"]').click(function() {
			if ($('.check-all').prop('checked') && !this.checked) {
				$('.check-all').prop('checked', false)
			}
		});
	});
</script>";

do_footer($js);

?>