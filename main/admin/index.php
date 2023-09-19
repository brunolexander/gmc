<?php

require_once ('include/restrict.php');

if (is_user_logged() == -1)
{
	header("Location: login.php", true, 303);
	exit();
}

require_once (dirname(dirname(__FILE__)) . '/config.php');

include ('include/functions.php');

do_header("Painel de controle", "painel");

$sql = "SELECT log, DATE_FORMAT(data, '%b %d, ') AS data, DATE_FORMAT(data, '%H:%i') AS hora FROM log ORDER BY data DESC, hora DESC;";
$query = $conn->query($sql);

?>

<link href="css/morris.css" rel="stylesheet">

<div class="panel mb-5">
	<h2><span class="oi oi-align-center" data-glyph="bar-chart"></span> Relatório de acesso</h2>
	<div id="morris-area-chart"></div>
</div>

<div class="panel">
	<h2>Registro de atividade</h2>
	
	<hr>
	<div class="scrollable">

<?php

while ($row = $query->fetch_array())
	echo "<p><span class=\"oi oi-align-center\" data-glyph=\"calendar\"></span> $row[1] <span class=\"oi oi-align-center ml-3\" data-glyph=\"clock\"></span> $row[2] <span class=\"float-right\">$row[0]</span></p>\n";

?>
	</div>
</div>

<?php

$js = '<script src="js/raphael.min.js"></script>
<script src="js/morris.min.js"></script>
<script src="js/morris-data.js"></script>';

do_footer($js);

?>