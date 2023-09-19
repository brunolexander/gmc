<?php

require_once ("include/restrict.php");

$user = get_session('user');

if ($user == -1)
{
	header("Location: login.php", true, 303);
	exit();
}

require_once (dirname(dirname(__FILE__)) . "/config.php");

include ("include/functions.php");

$action = isset($_GET["a"]) ? $_GET["a"] : "";

if ($action != "perfil" && !check_access($user, 'Administrador'))
	exit();

$role = isset($_GET["acesso"]) ? $_GET["acesso"] : "";
$id = isset($_GET["id"]) ? $_GET["id"] : "";
$js = "";

switch ($action)
{

case 'form':
	header("Content-type: text/html; charset=utf-8");

	@$checkbox = $_POST['checkbox'];

	if (!isset($checkbox))
	{
		header("Location: usuarios.php");
		exit();
	}

	if (isset($_POST['aplicar']))
	{
		$acoes = $_POST['acoes'];

		switch ($acoes) {
			case 'Deletar':
				foreach ($checkbox as $id) {
					$array[] = "id = $id";
				}

				$checkbox = implode(" OR ", $array);

				$sql = "SELECT nome FROM usuarios WHERE ($checkbox) AND id != $user;";
				$query = $conn->query($sql);

				while ($row = $query->fetch_array())
					$users[] = "<b>" . $row[0] . "</b>";

				insert_log("deletou o(s) usuário(s): " . implode(", ", $users));

				$sql = "DELETE FROM usuarios WHERE ($checkbox) AND id != $user;";
				$query = $conn->query($sql);

				header("Location: usuarios.php?delete=$query");
				break;
			
			default:
				header("Location: usuarios.php");
				break;
		}
	}
	else if (isset($_POST['alterar']))
	{
		$acesso = $_POST['acesso'];
		
		if (empty($acesso))
		{
			header("Location: usuarios.php");
			exit();
		}

		foreach ($checkbox as $id) {
			$array[] = "id = $id";
		}

		$checkbox = implode(" OR ", $array);
		$access = check_access($user, "Administrador", false);

		if ($access && $acesso == "Funcionário")
			$sql = "SELECT nome FROM usuarios WHERE ($checkbox) AND id != $user;";
		else
			$sql = "SELECT nome FROM usuarios WHERE ($checkbox)";

		$query = $conn->query($sql);

		while ($row = $query->fetch_array())
			$users[] = "<b>" . $row[0] . "</b>";

		insert_log("atualizou o(s) usuário(s): " . implode(", ", $users));

		if ($access && $acesso == "Funcionário")
			$sql = "UPDATE usuarios SET acesso = '$acesso' WHERE ($checkbox) AND id != $user;";
		else
			$sql = "UPDATE usuarios SET acesso = '$acesso' WHERE ($checkbox);";

		$query = $conn->query($sql);
		
		header("Location: usuarios.php?update=$query");
	}

	break;

case 'delete':
	$sql = "SELECT nome FROM usuarios WHERE id = $id";
	$query = $conn->query($sql);

	if ($query->num_rows == 0)
	{
		header("Location: usuarios.php");
		exit();
	}

	$row = $query->fetch_array();

	if ($user != $id)
	{
		$sql = "DELETE FROM usuarios WHERE id = $id;";
		$query = $conn->query($sql);

		insert_log("removeu o usuário <b>$row[0]</b>");
		header("Location: usuarios.php?delete=$query");
	}
	else
		header("Location: usuarios.php?delete=0");

	break;

case 'insert':
	header("Content-type: text/html; charset=utf-8");

	$nome = strip_tags($_POST['nome']);
	$login = strip_tags($_POST['login']);
	$email = strip_tags($_POST['email']);
	$senha = md5(strip_tags($_POST['senha']));
	$acesso = $_POST['acesso'];

	$sql = "SELECT NULL FROM usuarios WHERE login = '$login';";
	$query = $conn->query($sql);

	if ($query->num_rows > 0)
	{
		header("Location: usuarios.php?a=adicionar&alert=login&nome=$nome&login=$login&email=$email&acesso=$acesso");
		exit();
	}

	if (!empty($email))
	{
		$sql = "SELECT NULL FROM usuarios WHERE email = '$email';";
		$query = $conn->query($sql);

		if ($query->num_rows > 0)
		{
			header("Location: usuarios.php?a=adicionar&alert=email&nome=$nome&login=$login&email=$email&acesso=$acesso");
			exit();
		}
	}

	$sql = "INSERT INTO usuarios VALUES (NULL, '$nome', '$login', '$email', '$senha', '$acesso');";
	$query = $conn->query($sql);

	insert_log("inseriu o usuário <b>$nome</b>");
	header("Location: usuarios.php?insert=$query");
	break;

case 'update':
	header("Content-type: text/html; charset=utf-8");

	$id = isset($_GET['id']) ? $_GET['id'] : "";
	$perfil = isset($_GET['perfil']) ? $_GET['perfil'] : "";
	$nome = strip_tags($_POST['nome']);
	$login = strip_tags($_POST['login']);
	$email = strip_tags($_POST['email']);
	$senha = md5(strip_tags($_POST['senha']));
	$acesso = $_POST['acesso'];
	$sql = "";

	if (!empty($perfil))
		$id = $perfil;

	$sql = "SELECT NULL FROM usuarios WHERE id = $id";
	$query = $conn->query($sql);

	if ($query->num_rows == 0)
	{
		header("Location: usuarios.php");
		exit();
	}

	if (!empty($email))
	{
		$sql = "SELECT NULL FROM usuarios WHERE email = '$email' AND id != $id;";
		$query = $conn->query($sql);

		if ($query->num_rows > 0)
		{
			header("Location: usuarios.php?a=editar&id=$id&alert=email");
			exit();
		}
	}

	$sql = "UPDATE usuarios SET nome = '$nome', login = '$login', email = '$email', senha = '$senha', acesso = '$acesso' WHERE id = $id;";
	$query = $conn->query($sql);

	if (!empty($perfil))
	{
		header("Location: usuarios.php?a=perfil&alert=update");
	}
	else
	{
		insert_log("atualizou o usuário <b>$nome</b>");
		header("Location: usuarios.php?a=editar&id=$id&alert=update");
	}

	break;

case 'adicionar':

do_header("Adicionar usuário", "usuarios", 2);

?>

<style type="text/css">
	.btn {
		cursor: pointer;
	}

	select {
		color: #888;
		font-size: 15px;
	}
</style>

<?php

$alert = isset($_GET["alert"]) ? $_GET["alert"] : NULL;
$nome = isset($_GET["nome"]) ? $_GET["nome"] : "";
$login = isset($_GET["login"]) ? $_GET["login"] : "";
$email = isset($_GET["email"]) ? $_GET["email"] : "";
$acesso = isset($_GET["acesso"]) ? $_GET["acesso"] : "";

if (!is_null($alert))
{

$class = "";
$message = "";

switch ($alert)
{
	case 'login':
		$class = "alert-danger";
		$message = "Este login já existe.";
		break;

	case 'email':
		$class = "alert-danger";
		$message = "Este email já está cadastrado.";
		break;
}

?>

<div class="alert <?php echo $class; ?>">
	<span class="oi oi-align-center mr-2" data-glyph="warning"></span>
	<?php echo $message; ?>
	<span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span>
</div>

<?php

}

?>

<form action="usuarios.php?a=insert" method="POST" class="col-8">
	<p>
		<label>Nome: </label>
		<input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>" required></input>
	</p>

	<p>
		<label>Login: </label>
		<input type="text" name="login" class="form-control" value="<?php echo $login; ?>" required></input>
	</p>

	<p>
		<label>Email (opcional): </label>
		<input type="email" name="email" class="form-control" value="<?php echo $email; ?>"></input>
	</p>

	<p>
		<label>Senha: </label>
		<input type="password" name="senha" class="form-control" required></input>
	</p>

	<p>
		<label>Acesso: </label>
		<select name="acesso">
			<option value="Funcionário" <?php if ($acesso == "Funcionário") echo "selected"; ?>>Funcionário</option>
			<option value="Administrador" <?php if ($acesso == "Administrador") echo "selected"; ?>>Administrador</option>
		</select>
	</p>

	<p><input type="submit" class="btn btn-primary btn-sm" value="Adicionar novo usuário"></p>
</form>

<?php

	break;

case 'editar':

do_header("Alterar usuário", "usuarios", 0);

?>

<style type="text/css">
	.btn {
		cursor: pointer;
	}

	select {
		color: #888;
		font-size: 15px;
	}

	.focus-disabled:focus {
		cursor: not-allowed;
		border: 1px solid rgba(0, 0, 0, 0.15);
	}
</style>

<?php

$alert = isset($_GET["alert"]) ? $_GET["alert"] : NULL;
$id = $_GET['id'];

$sql = "SELECT NULL FROM usuarios WHERE id = $id";
$query = $conn->query($sql);

if ($query->num_rows == 0)
{
	header("Location: usuarios.php");
	exit();
}

$sql = "SELECT * FROM usuarios WHERE id = $id;";
$query = $conn->query($sql);
$row = $query->fetch_array();

if (!is_null($alert))
{

$class = "";
$message = "";

switch ($alert)
{
	case 'update':
		$class = "alert-success";
		$message = "Usuário atualizado!";
		break;
}

?>

<div class="alert <?php echo $class; ?>">
	<span class="oi oi-align-center mr-2" data-glyph="warning"></span>
	<?php echo $message; ?>
	<span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span>
</div>

<?php

}

?>

<form action="usuarios.php?a=update<?php echo "&id=$id"; ?>" method="POST" class="col-8">
	<p>
		<label>Nome: </label>
		<input type="text" name="nome" class="form-control" value="<?php echo $row[1]; ?>" required></input>
	</p>

	<p>
		<label>Login: </label>
		<input style="cursor: not-allowed;" type="text" name="login" class="focus-disabled form-control mb-1" value="<?php echo $row[2]; ?>" readonly></input>
		<i style="font-size: 14px;">O login não pode ser alterado.</i>
	</p>

	<p>
		<label>Email (opcional): </label>
		<input type="email" name="email" class="form-control" value="<?php echo $row[3]; ?>"></input>
	</p>

	<p>
		<label>Senha: </label>
		<input type="password" name="senha" class="form-control" required></input>
	</p>

	<p>
		<label>Acesso: </label>
		<select name="acesso">
			<option value="Funcionário" <?php if ($row[5] == "Funcionário") echo "selected"; ?>>Funcionário</option>
			<option value="Administrador" <?php if ($row[5] == "Administrador") echo "selected"; ?>>Administrador</option>
		</select>
	</p>

	<p><input type="submit" class="btn btn-primary btn-sm" value="Atualizar perfil"></p>
</form>

<?php

	break;

case 'perfil':

do_header("Alterar meu perfil", "usuarios", 3);

?>

<style type="text/css">
	.btn {
		cursor: pointer;
	}

	select {
		color: #888;
		font-size: 15px;
	}

	.focus-disabled:focus {
		cursor: not-allowed;
		border: 1px solid rgba(0, 0, 0, 0.15);
	}
</style>

<?php

$alert = isset($_GET["alert"]) ? $_GET["alert"] : NULL;
$sql = "SELECT * FROM usuarios WHERE id = $user;";
$query = $conn->query($sql);
$row = $query->fetch_array();

if (!is_null($alert))
{

$class = "";
$message = "";

switch ($alert)
{
	case 'update':
		$class = "alert-success";
		$message = "Perfil atualizado!";
		break;
}

?>

<div class="alert <?php echo $class; ?>">
	<span class="oi oi-align-center mr-2" data-glyph="warning"></span>
	<?php echo $message; ?>
	<span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span>
</div>

<?php

}

?>

<form action="usuarios.php?a=update<?php echo "&perfil=$row[0]"; ?>" method="POST" class="col-8">
	<p>
		<label>Nome: </label>
		<input type="text" name="nome" class="form-control" value="<?php echo $row[1]; ?>" required></input>
	</p>

	<p>
		<label>Login: </label>
		<input type="text" name="login" class="form-control mb-1" value="<?php echo $row[2]; ?>" readonly></input>
		<i style="font-size: 14px;">O login não pode ser alterado.</i>
	</p>

	<p>
		<label>Email (opcional): </label>
		<input type="email" name="email" class="form-control" value="<?php echo $row[3]; ?>"></input>
	</p>

	<p>
		<label>Senha: </label>
		<input type="password" name="senha" class="form-control" required></input>
	</p>

	<input type="hidden" name="acesso" value="<?php echo $row[5]; ?>"></input>

	<p><input type="submit" class="btn btn-primary btn-sm" value="Atualizar perfil"></p>
</form>

<?php

	break;

default:

$delete = isset($_GET["delete"]) ? $_GET["delete"] : NULL;
$insert = isset($_GET["insert"]) ? $_GET['insert'] : NULL;
$update = isset($_GET["update"]) ? $_GET['update'] : NULL;

do_header("Controle de usuários", "usuarios", 1);

if (!is_null($insert))
{

$alert = $insert ? "alert-success" : "alert-danger";

?>

<div class="alert <?php echo $alert; ?>">
	<span class="oi oi-align-center mr-2" data-glyph="warning"></span>
	
	<?php

	switch ($insert)
	{
		case 0:
			echo "Não foi possível inserir o usuário.";
			break;

		default:
			echo "Usuário inserido!";
			break;
	}

	?>

	<span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span>
</div>

<?php

}
else if (!is_null($update))
{

$alert = $update ? "alert-success" : "alert-danger";

?>

<div class="alert <?php echo $alert; ?>">
	<span class="oi oi-align-center mr-2" data-glyph="warning"></span>
	
	<?php

	switch ($update)
	{
		case 0:
			echo "Não foi possível atualizar o(s) usuário(s).";
			break;

		default:
			echo "Usuário(s) atualizado(s)!";
			break;
	}

	?>

	<span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span>
</div>

<?php

}
else if (!is_null($delete))
{

$alert = $delete > 0 ? "alert-success" : "alert-danger";

?>

<div class="alert <?php echo $alert; ?>">
	<span class="oi oi-align-center mr-2" data-glyph="warning"></span>
	
	<?php

	switch ($delete)
	{
		case 0:
			echo "Não foi possível deletar o(s) usuário(s).";
			break;

		default:
			echo "Usuário(s) deletado(s)!";
			break;
	}

	?>

	<span class="oi oi-align-center float-right" data-glyph="circle-x" style="cursor: pointer;" data-dismiss="alert"></span>
</div>

<?php

}

?>

<style type="text/css">
	.row-actions {
		font-size: 13px;
		opacity: 0.0;
	}

	.row-actions a:hover {
		text-decoration: none;
		opacity: 0.6;
	}

	.btn {
		cursor: pointer;
	}

	select {
		color: #888;
		font-size: 14px;
	}

	#user_access a {
		text-decoration: none;
	}

	#user_access .count {
		color: #000;
	}
</style>

<?php

$sql = "SELECT acesso FROM usuarios;";
$query = $conn->query($sql);

$users_count = [0, 0];

while ($row = $query->fetch_array())
{
	switch ($row[0])
	{
		case 'Administrador':
			$users_count[0]++;
			break;

		case 'Funcionário':
			$users_count[1]++;
			break;
	}
}

?>

<p id="user_access"><a href="usuarios.php">Todos <span class="count">(<?php echo $query->num_rows; ?>)</span></a> | <a href="usuarios.php?acesso=Administrador">Administrador <span class="count">(<?php echo $users_count[0]; ?>)</span></a> | <a href="usuarios.php?acesso=Funcionário">Funcionário <span class="count">(<?php echo $users_count[1]; ?>)</span></a></p>

<form method="POST" action="usuarios.php?a=form">
	<p>
		<select name="acoes" class="mr-1" style="padding: 2px;">
			<option value="null" selected>Ações</option>
			<option value="Deletar">Deletar</option>
		</select>

		<input type="submit" name="aplicar" class="btn btn-secondary btn-sm" value="Aplicar">

		<select name="acesso" class="ml-3 mr-1" style="padding: 2px;">
			<option value="null" selected>Alterar acesso para</option>
			<option value="Administrador">Administrador</option>
			<option value="Funcionário">Funcionário</option>
		</select>

		<input type="submit" name="alterar" class="btn btn-secondary btn-sm" value="Alterar">

		<a href="usuarios.php?a=adicionar"><button type="button" class="btn btn-secondary btn-sm float-right">Adicionar usuário +</button></a>
	</p>

	<div class="table-responsive">
		<table class="table table-striped" style="border-width: 0.13rem 1px 0.187rem 1px; border-color: #e0e0e0; border-style: solid;">
			<thead>
				<th><input type="checkbox" class="check-all"></th>
				<th>Nome</th>
				<th>Login</th>
				<th>Email</th>
				<th>Acesso</th>
			</thead>

			<tbody>
				<?php

				$sql = "";

				switch (empty($role)) {
					case 0:
						$sql = "SELECT id, nome, login, email, acesso FROM usuarios WHERE acesso = '$role';";
						break;
					
					default:
						$sql = "SELECT id, nome, login, email, acesso FROM usuarios;";
						break;
				}
				
				$query = $conn->query($sql);

				if ($query->num_rows > 0)
				{
					while ($row = $query->fetch_array())
					{
						switch ($row[0]) {
							case $user:
								$row_actions = "<div class=\"row-actions\"><a href=\"usuarios.php?a=perfil\">Editar</a>";
								break;
							
							default:
								$row_actions = "<div class=\"row-actions\"><a href=\"usuarios.php?a=editar&id=$row[0]\">Editar</a>";

								$row_actions .= " | <a href=\"usuarios.php?a=delete&id=$row[0]\" class=\"text-danger\">Deletar</a>";
								break;
						}
					
						$row_actions .= "</div>";

						echo "<tr class=\"user\">
						\t<td><input type=\"checkbox\" name=\"checkbox[]\" value=\"$row[0]\"></td>
						\t<td>$row[1]<br>$row_actions</td>
						\t<td>$row[2]</td>
						\t<td>$row[3]</td>
						\t<td>$row[4]</td>
						</tr>\n";
					}
				}
				else
				{

				?>

				<tr><td colspan="5">Nenhum usuário encontrado.</td></tr>

				<?php

				}
				
				?>
			</tbody>

			<thead>
				<th><input type="checkbox" class="check-all"></th>
				<th>Nome</th>
				<th>Login</th>
				<th>Email</th>
				<th>Acesso</th>
			</thead>
		</table>
	</div>
</form>

<?php

$js = "<script type=\"text/javascript\">
	$(document).ready(function() {
		$('.check-all').click(function() {
			$('table input[type=checkbox]').prop('checked', this.checked);
		});

		$('table input[type=checkbox]:not(.check-all)').click(function() {
			var checked = $('table input[type=checkbox]:not(.check-all):checked').length;
			var count = $('table input[type=checkbox]:not(.check-all)').length;
			$('.check-all').prop('checked', checked < count ? false : true);
		});

		$('.user')
			.mouseover(function() {
				$(this).find('.row-actions').css('opacity', '1.0');
			})
			.mouseout(function() {
				$(this).find('.row-actions').css('opacity', '0.0');
			});
	});
</script>";

break;

}

do_footer($js);

?>