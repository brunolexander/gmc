<?php

require_once ("include/restrict.php");

$user = get_session("user");

if ($user == -1)
{
	header("Location: login.php");
	exit();
}

function uploadImage($file)
{
	$extension = "." . pathinfo($file, PATHINFO_EXTENSION);
	$image = strtolower(pathinfo($file, PATHINFO_FILENAME));
	$image = preg_replace("/ /", "-", preg_replace("/[^0-9a-zA-Z ]/m", "", $image));
	$image = "upload/" . $image;
	$path = dirname(dirname(__FILE__)) . "/" . $image . $extension;

	while (file_exists($path))
	{
		$image = $image . "_" . mt_rand(0, 1000);
		$path = dirname(dirname(__FILE__)) . "/" . $image . $extension;
	}

	$image = BASE_URL . $image . $extension;
	move_uploaded_file($_FILES["img_upload"]["tmp_name"], $path);
	return $image;
}

function unlinkImage($image)
{
	if (stripos($image, BASE_URL) == 0 && stripos($image, "img/sem_img.jpg") != true)
	{
		$image = dirname(dirname(__FILE__)) . "/" . str_replace(BASE_URL, "", $image);
	
		if (file_exists($image))
			unlink($image);
	}
}

$action = isset($_GET["a"]) ? $_GET["a"] : "";
$js = "";
@$id = $_GET["id"];
@$alert = $_GET["alerta"];

require_once (dirname(dirname(__FILE__)) . "/config.php");

include ("include/functions.php");

header("Content-type: text/html; charset=utf-8");

switch ($action)
{

case 'delete':
	if (!check_access($user, "Administrador"))
		exit();

	$sql = "SELECT imagem, nome FROM pedras WHERE id = $id;";
	$query = $conn->query($sql);

	if ($query->num_rows == 0)
	{
		header("Location: pedras.php");
		exit();
	}

	$pedra = $query->fetch_array();
	unlinkImage($pedra[0]);

	$sql = "DELETE FROM pedras WHERE id = $id;";
	$query = $conn->query($sql);

	$sql = "DELETE FROM tag_relac WHERE id_pedra = $id;";
	$conn->query($sql);

	$sql = "DELETE FROM tag WHERE id NOT IN (SELECT id_tag FROM tag_relac);";
	$conn->query($sql);

	insert_log("removeu a pedra <b>$pedra[1]</b>");

	header("Location: pedras.php?alerta=removido&pedra=$pedra[1]");
	break;

case 'update':
	$sql = "SELECT imagem, nome FROM pedras WHERE id = $id;";
	$query = $conn->query($sql);

	if ($query->num_rows == 0)
	{
		header("Location: pedras.php");
		exit();
	}

	$pedra = $query->fetch_array();

	$nome = strip_tags($_POST["nome"]);
	$img_url = strip_tags($_POST["img_url"]);
	$img_upload = $_FILES["img_upload"]["name"];
	$tag = strip_tags($_POST["tag"]);

	if (!empty($img_upload))
		$img_url = uploadImage($img_upload);
	else if (empty($img_url))
		$img_url = BASE_URL . "img/sem_img.jpg";

	if ($pedra[0] != $img_url)
		unlinkImage($pedra[0]);

	if (empty($tag))
		$tag = "Sem tag";

	$sql = "UPDATE pedras SET nome = '$nome', imagem = '$img_url' WHERE id = $id;";
	$conn->query($sql);

	$sql = "DELETE FROM tag_relac WHERE id_pedra = $id;";
	$conn->query($sql);

	$sql = "DELETE FROM tag WHERE id NOT IN (SELECT id_tag FROM tag_relac);";
	$conn->query($sql);
	
	$chars = [", ", " , "];
	$tag = explode(",", strtolower(str_replace($chars, ",", $tag)));

	foreach ($tag as $string)
	{
		# Insert 'tag' if not exists
		$sql = "INSERT INTO tag (nome) SELECT '$string' FROM DUAL WHERE NOT EXISTS (SELECT nome FROM tag WHERE nome = '$string');";
		$conn->query($sql);
		
		# Get 'tag' id
		$sql = "SELECT id FROM tag WHERE nome = '$string';";
		$query = $conn->query($sql);
		$row = $query->fetch_array();

		# Insert relationship between 'tag' and 'pedra' if not exists
		$sql = "INSERT INTO tag_relac VALUES ($row[0], $id);";
		$conn->query($sql);
	}

	insert_log("atualizou a pedra <b>$pedra[1]</b>");

	header("Location: pedras.php?alerta=atualizado&pedra=$pedra[1]");
	break;

case 'insert':
	
	$nome = strip_tags($_POST["nome"]);
	$img_url = strip_tags($_POST["img_url"]);
	$img_upload = $_FILES["img_upload"]["name"];
	$tag = strip_tags($_POST["tag"]);

	if (!empty($img_upload))
		$img_url = uploadImage($img_upload);
	else if (empty($img_url))
		$img_url = BASE_URL . "img/sem_img.jpg";

	if (empty($tag))
		$tag = "Sem tag";

	$sql = "INSERT INTO pedras (nome, imagem) VALUES ('$nome', '$img_url');";
	$conn->query($sql);
	
	$id = $conn->insert_id;
	$chars = [", ", " , "];
	$tag = explode(",", strtolower(str_replace($chars, ",", $tag)));

	foreach ($tag as $string)
	{
		# Insert 'tag' if not exists
		$sql = "INSERT INTO tag (nome) SELECT '$string' FROM DUAL WHERE NOT EXISTS (SELECT nome FROM tag WHERE nome = '$string');";
		$conn->query($sql);
		
		# Get 'tag' id
		$sql = "SELECT id FROM tag WHERE nome = '$string';";
		$query = $conn->query($sql);
		$row = $query->fetch_array();

		# Insert relationship between 'tag' and 'pedra' if not exists
		$sql = "INSERT INTO tag_relac VALUES ($row[0], $id);";
		$conn->query($sql);
	}

	insert_log("adicionou a pedra <b>$nome</b>");

	header("Location: pedras.php?alerta=adicionado&pedra=$nome");
	break;

case 'editar':
	$sql = "SELECT pedras.nome, pedras.imagem, GROUP_CONCAT(DISTINCT tag.nome ORDER BY tag.nome ASC SEPARATOR ', ') AS tag_nome FROM pedras LEFT JOIN tag_relac ON pedras.id = tag_relac.id_pedra LEFT JOIN tag ON tag_relac.id_tag = tag.id WHERE pedras.id = $id;";
	$query = $conn->query($sql);
	$row = $query->fetch_array();

	do_header("Editar pedra ($row[0])", "pedras", 0);

	$url = BASE_URL . "upload/";

	$js = '<script type="text/javascript">
		$(document).ready(function() {
			$("#img_upload").change(function() {
				var img = $(this).val().replace(/C:\\\fakepath\\\/i, "");
				img = '."\"$url\"".' + img;
				$("#img_url").val(img);
			});
		});
	</script>';
?>

<form action="pedras.php?a=update<?php echo "&id=$id"; ?>" method="POST" class="col-8" enctype="multipart/form-data">
	<p>
		<label>Nome:</label>
		<input type="text" name="nome" class="form-control" value="<?php echo $row[0]; ?>" required></input>
	</p>

	<p>
		<label>Imagem:</label>
		<input type="text" name="img_url" id="img_url" class="form-control mb-1" value="<?php echo $row[1]; ?>"></input>
		<input type="file" name="img_upload" id="img_upload"></input>
	</p>

	<p>
		<label>Tag(s):</label>
		<input type="text" name="tag" class="form-control" value="<?php echo $row[2]; ?>"></input>
		<i style="font-size: 14px;">Separe as tags com vírgula. Exemplo: mármore, granito, amarelo, vermelho</i>
	</p>

	<p><input type="submit" class="btn btn-primary btn-sm" value="Atualizar pedra"></p>
</form>

<?php

	break;

case 'adicionar':
	do_header("Adicionar pedra", "pedras", 2);

	$url = BASE_URL . "upload/";

	$js = '<script type="text/javascript">
		$(document).ready(function() {
			$("#img_upload").change(function() {
				var img = $(this).val().replace(/C:\\\fakepath\\\/i, "");
				img = '."\"$url\"".' + img;
				$("#img_url").val(img);
			});
		});
	</script>';
?>

<form action="pedras.php?a=insert" method="POST" class="col-8" enctype="multipart/form-data">
	<p>
		<label>Nome:</label>
		<input type="text" name="nome" class="form-control" required></input>
	</p>

	<p>
		<label>Imagem:</label>
		<input type="text" name="img_url" id="img_url" class="form-control mb-1"></input>
		<input type="file" name="img_upload" id="img_upload"></input>
	</p>

	<p>
		<label>Tag(s):</label>
		<input type="text" name="tag" class="form-control"></input>
		<i style="font-size: 14px;">Separe as tags com vírgula. Exemplo: mármore, granito, amarelo, vermelho...</i>
	</p>

	<p><input type="submit" class="btn btn-primary btn-sm" value="Adicionar nova pedra"></p>
</form>

<?php

	break;

default:
	$page = isset($_GET["p"]) ? $_GET["p"] : 1;

	if (!is_numeric($page))
		$page = 1;

	do_header("Controle de pedras", "pedras", 1);

?>

<style type="text/css">
	.placeholders {
		padding-bottom: 3rem !important;
	}

	.placeholder:hover {
		background-color: #ccc;
		border-radius: 5px;
		transition: all 0.15s ease;
	}

	.placeholder img {
		width: 180px;
		height: 225px;
		padding-top: 1.5rem;
 		padding-bottom: 1.5rem;
	}

	.placeholder .actions {
		padding: 4px 0px;
	}

	.placeholder .actions a {
		padding: 4px;
	}

	.placeholder .actions a:hover {
		opacity: 0.4;
	}

	.placeholder .actions a span {
		font-size: 22px;
	}

	.placeholder .actions a .oi[data-glyph="pencil"] {
		color: green;
	}

	.placeholder .actions a .oi[data-glyph="x"] {
		color: red;
	}
</style>

<?php

$access = check_access($user, "Administrador", false);

if (isset($alert))
{
	$pedra = $_GET['pedra'];

	switch ($alert)
	{
		case 'removido':
			do_alert("alert-success", "Pedra <b>$pedra</b> deletada!");
			break;

		case 'atualizado':
			do_alert("alert-success", "Pedra <b>$pedra</b> atualizada!");
			break;

		case 'adicionado':
			do_alert("alert-success", "Pedra <b>$pedra</b> adicionada!");
			break;
	}
}

?>

<section class="row text-center placeholders justify-content-center">
	<?php

	$sql = "SELECT COUNT(*) FROM pedras;";
	$query = $conn->query($sql);
	$row = $query->fetch_array();
	
	$total = $row[0];
	$max = 6;
	$pages = ceil($total / $max);
	$page = clamp($page, 1, $pages);
	$min = ($page * $max) - $max;

	$sql = "SELECT pedras.id, pedras.nome, pedras.imagem, GROUP_CONCAT(DISTINCT tag.nome ORDER BY tag.nome ASC SEPARATOR ', ') AS tag_nome FROM pedras LEFT JOIN tag_relac ON pedras.id = tag_relac.id_pedra LEFT JOIN tag ON tag_relac.id_tag = tag.id GROUP BY pedras.id ORDER BY pedras.nome ASC LIMIT $min, $max";
	$query = $conn->query($sql);

	while ($row = $query->fetch_array())
	{
		$row[3] = ucfirst(strtolower($row[3]));

		echo "<div class=\"col-7 col-sm-4 mb-3 mb-sm-5 placeholder\">
			<div class=\"actions text-center text-md-right\">
				<a href=\"pedras.php?a=editar&id=$row[0]\"><span class=\"oi oi-align-center\" data-glyph=\"pencil\"></span></a>";

				if ($access) echo "<a href=\"pedras.php?a=delete&id=$row[0]\"><span class=\"oi oi-align-center\" data-glyph=\"x\"></span></a>";

			echo "</div>

			<img src=\"$row[2]\" class=\"img-fluid rounded-circle\">
			<h4>$row[1]</h4>
			<p><span class=\"oi oi-align-center\" data-glyph=\"tags\"></span> <i>$row[3]</i></p>
		</div>";
	}

	?>
</section>

<?php

pagination($page, $pages, "admin/pedras.php?");

break;

}

do_footer($js);

?>