<?php

	$erro = isset($_GET['erro']) ? $_GET['erro'] : 0; //Se foi passado pelo parâmetro GET um erro de nome 'erro', o valor passado é armazenado, caso contrário, ele é setado automaticamente como 0 (que representa sem erro)

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
	
		<script>
			$(document).ready( function(){ //Após a página estar totalmente carregada ...

				//Verificar se os campos de usuário e senha foram devidamente preenchidos
				$('#btn_login').click(function(){ //Ao clicar no botão de Login de id="btn_login" ...

					var campo_vazio = false; //Variáel que irá armazenar true se houver um campo vazio

					if($('#campo_usuario').val() == ''){ //Se não foi digitado nada no campo de usuário de id=campo_usuario ...
						$('#campo_usuario').css({'border-color': '#A94442'}); //É colocada uma borda de cor avermelhada no campo de usuário que não foi preenchido
						campo_vazio = true; //Existe um campo vazio
					} else {
						$('#campo_usuario').css({'border-color': '#CCC'}); //Se algo foi digitado não é necessária a borda de cor avermelhada para indicar campo não preenchido no usuário
					}

					if($('#campo_senha').val() == ''){ //Se não foi digitado nada no campo de senha de id=campo_senha ...
						$('#campo_senha').css({'border-color': '#A94442'}); //É colocada uma borda de cor avermelhada no campo de senha que não foi preenchido
						campo_vazio = true; //Existe um campo vazio
					} else {
						$('#campo_senha').css({'border-color': '#CCC'}); //Se algo foi digitado não é necessária a borda de cor avermelhada para indicar campo não preenchido na senha
					}

					if(campo_vazio) return false; //Se houver um campo vazio retorna false para que o fluxo de execução seja interrompido aqui mesmo, até que tudo esteja preenchido como deve

				});
			});					
		</script>
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
	            	<a class="nav-link" href="inscrevase.php">Inscrever-se</a>  <!-- Classe do Bootstrap para criação de links da Barra de Navegação -->
	            </li>
	            <li class="nav-item <?= $erro == 1 ? 'show' : '' ?>"  > <!-- Aqui é usado a tag curta de impressão do PHP para inserir a classe 'show' caso a variável erro tenha valor 1 (ou seja, há um erro de usuário e/ou senha inválidos). Assim, como o login não foi uma operação bem sucedida, a caixa de login tem que ficar aberta. / A variáel $erro foi setada no topo da página, e tem valor 0 se não houver nenhum erro ou valor 1 se houver erro na autenticação do usuário e/ou senha -->
	            	<a class="nav-link" id="entrar" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Entrar</a>
					<div class="dropdown-menu dropdown-menu-right <?= $erro == 1 ? 'show' : '' ?>" aria-labelledby="entrar"> <!-- Aqui NOVAMENTE é usado a tag curta de impressão do PHP para inserir a classe 'show' caso a variável erro tenha valor 1 (ou seja, há um erro de usuário e/ou senha inválidos). Assim, como o login não foi uma operação bem sucedida, a caixa de login tem que ficar aberta / A variáel $erro foi setada no topo da página, e tem valor 0 se não houver nenhum erro ou valor 1 se houver erro na autenticação do usuário e/ou senha -->
						<div class="col-md-12">
				    		<h3>Você possui uma conta?</h3>
				    		<br/>
							<form method="post" action="validar_acesso.php" id="formLogin"> <!-- Enviando via método POST os campos de usuário e senha informados para a página 'validar_acesso.php' que irá validar ou não os campos informados -->
								<div class="form-group"> <!-- form-group é uma classe do Bootstrap -->
									<input type="text" class="form-control" id="campo_usuario" name="usuario" placeholder="Usuário"/> <!-- form-control é uma classe do Bootstrap -->
								</div>
								
								<div class="form-group"> <!-- form-group é uma classe do Bootstrap -->
									<input type="password" class="form-control" id="campo_senha" name="senha" placeholder="Senha"/> <!-- form-control é uma classe do Bootstrap -->
								</div>
								
								<button type="buttom" class="btn btn-primary" id="btn_login">Entrar</button>

								<br/><br/>
								
								<!-- Se a variável erro tiver valor igual a 1, é porque o usuário e/ou senha são inválidos, então uma mensagem tem que ser exibida na caixa de login informando esse erro. / A variáel $erro foi setada no topo da página, e tem valor 0 se não houver nenhum erro ou valor 1 se houver erro na autenticação do usuário e/ou senha -->
								<?php
									if($erro == 1){
										echo '<font color="#FF0000">Usuário e ou senha inválido(s)</font>';
									}
								?>

							</form>
						</div>
				  	</div>
	            </li>
	          </ul>
	        </div><!--/.nav-collapse -->
	    </nav><!--/.nav -->


	    <div class="container">

	      <!-- Main component for a primary marketing message or call to action -->
	      <div class="jumbotron"> <!-- Classe do Bootstrap -> A lightweight, flexible component that can optionally extend the entire viewport to showcase key marketing messages on your site -->
	        <h1>Bem vindo ao twitter clone</h1>
	        <p>Veja o que está acontecendo agora...</p>
	      </div>

	      <div class="clearfix"><!-- Classe do Bootstrap -> Quickly and easily clear floated content within a container by adding a clearfix utility. -->
	      </div>

		</div>
	
	
	</body>
</html>