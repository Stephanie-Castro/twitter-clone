<?php

	$erro_usuario	= isset($_GET['erro_usuario'])	? $_GET['erro_usuario'] : 0; //Se foi passado pelo parâmetro GET um erro de nome 'erro_usuario', o valor passado é armazenado, caso contrário, ele é setado automaticamente como 0 (que representa sem erro)
	$erro_email		= isset($_GET['erro_email'])	? $_GET['erro_email']	: 0; //Se foi passado pelo parâmetro GET um erro de nome 'erro_email', o valor passado é armazenado, caso contrário, ele é setado automaticamente como 0 (que representa sem erro)

?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">

		<title>Twitter clone</title>
		
		<!-- JQuery - link cdn -->
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

		<!-- Bootstrap - link cdn -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	
	</head>

	<body>

		<!-- Navbar -->
	    <nav class="navbar navbar-expand-lg navbar-light bg-light"> <!-- Barra de Navegação de fundo claro -->
	    	<a class="navbar-brand" href="#"><img src="imagens/icone_twitter.png" /></a>  <!-- Ícone de marca do Twitter -->
	        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">  <!-- Botão que irá aparecer quando a tela do navegador for diminuída ou em aparelhos de telas menores. Esse botão irá agrupar as opções da Barra de Navegação -->
	        	<span class="navbar-toggler-icon"></span>  <!-- ícone do botão de agrupament da Barra de Navegação fornecido por uma classe do Bootstrap -->
	        </button>
	        
	        <div id="navbar" class="collapse navbar-collapse">  <!-- Opções da Barra de Navegação que podem ser colapsados em telas menores -->
	          <ul class="navbar-nav ml-auto"> <!-- Alinhamento a direita das opções da Barra de Navegação -->
	            <li class="nav-item"> <!-- Classe do Bootstrap para criação de itens da Barra de Navegação -->
	            	<a class="nav-link" href="index.php">Voltar para Home</a> <!-- Classe do Bootstrap para criação de links da Barra de Navegação -->
	            </li>
	          </ul>
	        </div><!--/.nav-collapse -->
	    </nav><!--/.nav -->



	    <div class="container">
	    	
	    	<br/><br/>
	    	<div class="row"> <!-- Classe do Bootstrap para criação de linha que podem ser "divididas" em até 12 partes(colunas) -->
	
		    	<div class="col-md-4"></div> <!-- Classe do Bootstrap -> Pegando 4 das 12 linhas (mais a esquerda na página) -->

		    	<div class="col-md-4"> <!-- Classe do Bootstrap -> Pegando 4 das 12 linhas (mais centraliazadas na página)  /  Nesse espaço ficará o formulário de cadastro -->
		    		<h3>Inscreva-se já.</h3>
		    		<br/>
					<form method="post" action="registra_usuario.php" id="formCadastrarse"> <!-- Formulário de Cadastro -->
						<div class="form-group"> <!-- form-group é uma classe do Bootstrap -->
							<input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuário" required="required"> <!-- form-control é uma classe do Bootstrap -->

							<!-- Se a variável 'erro_usuario' tiver valor igual a 1, é porque o usuário já existe no Banco de Dados, então uma mensagem tem que ser exibida na caixa de login informando esse erro. / A variáel $erro_usuario foi setada no topo da página, e tem valor 0 se não houver nenhum erro ou valor 1 se houver erro por já existir usuário de mesmo nome no Banco de Dados -->
							<?php
								if($erro_usuario){ // 1-true 0-false
									echo '<font style="color:#FF0000">Usuário já existe</font>';
								}
							?>
						</div>

						<div class="form-group"> <!-- form-group é uma classe do Bootstrap -->
							<input type="email" class="form-control" id="email" name="email" placeholder="Email" required="required"> <!-- form-control é uma classe do Bootstrap -->

							<!-- Se a variável 'erro_email' tiver valor igual a 1, é porque o email já existe no Banco de Dados, então uma mensagem tem que ser exibida na caixa de login informando esse erro. / A variáel $erro_email foi setada no topo da página, e tem valor 0 se não houver nenhum erro ou valor 1 se houver erro por já existir esse email no Banco de Dados -->
							<?php
								if($erro_email){
									echo '<font style="color:#FF0000">E-mail já existe</font>';
								}
							?>
						</div>
						
						<div class="form-group"> <!-- form-group é uma classe do Bootstrap -->
							<input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required="requiored"> <!-- form-control é uma classe do Bootstrap -->
						</div>
						
						<button type="submit" class="btn btn-primary form-control">Inscreva-se</button> <!-- form-control é uma classe do Bootstrap -->
					</form>
				</div>

				<div class="col-md-4"></div> <!-- Classe do Bootstrap -> Pegando 4 das 12 linhas (mais a esquerda na página) -->
			
			</div>

			<div class="clearfix"></div>
			<br/>

		</div>


	    </div>

	
	</body>
</html>