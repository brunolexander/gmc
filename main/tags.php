<?php

include_once ('config.php');
include_once ('include/functions.php');

do_header("Tags");

$sql = "SELECT nome FROM tag;";
$query = $conn->query($sql);

echo "<div class=\"row\">
<div class=\"col-11 mx-auto\">
<ul>";

while ($row = $query->fetch_array())
{
	$row[0] = ucfirst($row[0]);
	echo "<li><a href=\" ". BASE_URL . "?tag=$row[0]\">$row[0]</a></li>";
}

echo "</ul>
</div>
</div>";

do_footer();

?>