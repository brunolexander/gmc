<?php

include_once ('config.php');
include_once ('include/functions.php');

do_header("Contato");

?>

<div class="row">
	<div class="col-11 mx-auto">
		<form action="#" method="post" class="mx-auto mx-md-0" id="contact-form">
			<input type="text" class="form-control" placeholder="Assunto (título da mensagem)" required></input>
			<input type="email" class="form-control mt-3" placeholder="Email" required></input>
			<input type="text" class="form-control mt-3" placeholder="Telefone (opcional)"></input>
			<textarea class="form-control my-3" style="height: 200px;" placeholder="Mensagem" required maxlength="1000"></textarea>

			<input type="submit" class="btn btn-primary btn-sm" value="Enviar"></input>
			<input type="reset" class="btn btn-primary btn-sm" value="Limpar campos"></input>
		</form>

		<p class="mt-5 text-justify">Se preferir, entre em contato conosco via email <u>email@example.com</u> ou telefone <u>+00 0000-0000</u>.</p>
	</div>
</div>

<?php do_footer(); ?>