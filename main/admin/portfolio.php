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

switch ($action)
{

case 'delete':
	if (!check_access($user, "Administrador"))
		exit();

	$sql = "SELECT imagem, titulo FROM portfolio WHERE id = $id;";
	$query = $conn->query($sql);

	if ($query->num_rows == 0)
	{
		header("Location: portfolio.php");
		exit();
	}

	$row = $query->fetch_array();
	unlinkImage($row[0]);

	$sql = "DELETE FROM portfolio WHERE id = $id;";
	$query = $conn->query($sql);

	insert_log("removeu <b>$row[1]</b> do portfolio");
	header("Location: portfolio.php?alerta=removido&foto=$row[1]");
	break;

case 'update':
	$sql = "SELECT imagem, titulo FROM portfolio WHERE id = $id;";
	$query = $conn->query($sql);

	if ($query->num_rows == 0)
	{
		header("Location: portfolio.php");
		exit();
	}

	$row = $query->fetch_array();

	$titulo = strip_tags($_POST["titulo"]);
	$descricao = strip_tags($_POST["descricao"]);
	$img_url = strip_tags($_POST["img_url"]);
	$img_upload = $_FILES["img_upload"]["name"];

	if (!empty($img_upload))
		$img_url = uploadImage($img_upload);
	else if (empty($img_url))
		$img_url = BASE_URL . "img/sem_img.jpg";

	if ($row[0] != $img_url)
		unlinkImage($row[0]);


	$sql = "UPDATE portfolio SET titulo = '$titulo', descricao = '$descricao', imagem = '$img_url' WHERE id = $id;";
	$conn->query($sql);

	insert_log("atualizou <b>$row[1]</b> do portfolio");
	header("Location: portfolio.php?alerta=atualizado&foto=$row[1]");
	break;

case 'insert':
	$titulo = strip_tags($_POST["titulo"]);
	$descricao = strip_tags($_POST["descricao"]);
	$img_url = strip_tags($_POST["img_url"]);
	$img_upload = $_FILES["img_upload"]["name"];

	if (!empty($img_upload))
		$img_url = uploadImage($img_upload);
	else if (empty($img_url))
		$img_url = BASE_URL . "img/sem_img.jpg";

	$sql = "INSERT INTO portfolio VALUES(NULL, '$titulo', '$descricao', '$img_url');";
	$conn->query($sql);

	insert_log("adicionou <b>$titulo</b> no portfolio");
	header("Location: portfolio.php?alerta=adicionado&foto=$titulo");
	break;

case 'editar':
	$sql = "SELECT titulo, descricao, imagem FROM portfolio WHERE id = $id";
	$query = $conn->query($sql);
	$row = $query->fetch_array();

	do_header("Editar foto ($row[0])", "portfolio", 0);

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

<form action="portfolio.php?a=update<?php echo "&id=$id"; ?>" method="POST" class="col-8" enctype="multipart/form-data">
	<p>
		<label>Título:</label>
		<input type="text" name="titulo" class="form-control" value="<?php echo $row[0]; ?>" required></input>
	</p>

	<p>
		<label>Descrição:</label>
		<textarea name="descricao" class="form-control" style="height: 198px !important;" required><?php echo $row[1]; ?></textarea>
	</p>

	<p>
		<label>Imagem:</label>
		<input type="text" name="img_url" id="img_url" class="form-control mb-1" value="<?php echo $row[2]; ?>"></input>
		<input type="file" name="img_upload" id="img_upload"></input>
	</p>

	<p><input type="submit" class="btn btn-primary btn-sm" value="Atualizar foto"></p>
</form>

<?php

	break;

case 'adicionar':
	do_header("Adicionar foto", "portfolio", 2);

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

<form action="portfolio.php?a=insert" method="POST" class="col-8" enctype="multipart/form-data">
	<p>
		<label>Título:</label>
		<input type="text" name="titulo" class="form-control" required></input>
	</p>

	<p>
		<label>Descrição:</label>
		<textarea name="descricao" class="form-control" style="height: 198px !important;" required></textarea>
	</p>

	<p>
		<label>Imagem:</label>
		<input type="text" name="img_url" id="img_url" class="form-control mb-1"></input>
		<input type="file" name="img_upload" id="img_upload"></input>
	</p>

	<p><input type="submit" class="btn btn-primary btn-sm" value="Adicionar nova foto"></p>
</form>

<?php

	break;

default:
	$page = isset($_GET['p']) ? $_GET['p'] : 1;

	if (!is_numeric($page))
		$page = 1;

	do_header("Controle de portfolio", "portfolio", 1);

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
	$foto = $_GET['foto'];

	switch ($alert)
	{
		case 'removido':
			do_alert("alert-success", "Foto <b>$foto</b> deletada!");
			break;

		case 'atualizado':
			do_alert("alert-success", "Foto <b>$foto</b> atualizada!");
			break;

		case 'adicionado':
			do_alert("alert-success", "Foto <b>$foto</b> adicionada!");
			break;
	}
}

?>

<section class="row text-center placeholders justify-content-center">
	<?php

	$sql = "SELECT COUNT(*) FROM portfolio;";
	$query = $conn->query($sql);
	$row = $query->fetch_array();
	
	$total = $row[0];
	$max = 6;
	$pages = ceil($total / $max);
	$page = clamp($page, 1, $pages);
	$min = ($page * $max) - $max;

	$sql = "SELECT * FROM portfolio ORDER BY titulo ASC LIMIT $min, $max;";
	$query = $conn->query($sql);

	while ($row = $query->fetch_array())
	{
		echo "<div class=\"col-7 col-sm-4 mb-3 mb-sm-5 placeholder\">
			<div class=\"actions text-center text-md-right\">
				<a href=\"portfolio.php?a=editar&id=$row[0]\"><span class=\"oi oi-align-center\" data-glyph=\"pencil\"></span></a>";

				if ($access) echo "<a href=\"portfolio.php?a=delete&id=$row[0]\"><span class=\"oi oi-align-center\" data-glyph=\"x\"></span></a>";

			echo "</div>

			<img src=\"$row[3]\" class=\"img-fluid rounded-circle\">
			<h4>$row[1]</h4>
			<p><span class=\"oi oi-align-center\" data-glyph=\"double-quote-sans-right\"></span> <i>$row[2]</i></p>
		</div>";
	}

	?>
</section>

<?php

pagination($page, $pages, "admin/portfolio.php?");

break;

}

do_footer($js);

?>