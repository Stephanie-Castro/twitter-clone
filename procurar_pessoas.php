<?php
	
	session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

	if(!isset($_SESSION['usuario'])){ //Se a variável de sessão de nome 'usuario' não tiver sido iniciada, não pode acessar a página procurar_pessoas.php (que é uma página só para pessoas logadas com sucesso), então a página é redirecionada para index.php com um erro sendo passado por método GET, que será tratado na página index.php
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php'); //Chamando a classe de conexão com o Banco de Dados

	//Criando a conexão com o Banco de Dados:
	$objDb = new db();
	$link = $objDb->conecta_mysql();

	$id_usuario = $_SESSION['id_usuario']; //Pegando o valor de 'id_usuario' da variável de sessão. Pois 'id_usuario' será usado para fazer pesquisasno Banco de Dados

	//Quantidade de Tweets:
	$sql = " SELECT COUNT(*) AS qtde_tweets FROM tweet WHERE id_usuario = $id_usuario "; //Criando comando sql que fará a contagem de Tweets feitos pelo usuário

	$resultado_id = mysqli_query($link, $sql); //Fazendo a pesquisa no Banco de Dados //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE

	$qtde_tweets = 0; //Essa variável irá armazenar a quantidade de Tweets feitas pelo usuário. Ela é iniciada com 0, até que a pesquisa no Banco de Dados seja feita

	if($resultado_id){ //Se a pesquisa no Banco de Dados foi realizada com sucesso ...

		$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC); //Função que retorna SOMENTE o array associativo com os resultados da pesquisa no Banco de Dados

		$qtde_tweets = $registro['qtde_tweets']; //Atualizando o valor da quantidade de tweets feitos após a pesquisa do Banco de Dados

	} else { //Se a pesquisa no Banco de Dados NÃO foi realizada com sucesso ...
		echo 'Erro ao executar a query';
	}




	//Quantidade de Seguidores:
	$sql = " SELECT COUNT(*) AS qtde_seguires FROM usuarios_seguidores WHERE seguindo_id_usuario = $id_usuario "; //Criando comando sql que fará a contagem de seguidores
	$resultado_id = mysqli_query($link, $sql);
	$qtde_seguidores = 0;
	if($resultado_id){
		$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
		$qtde_seguidores = $registro['qtde_seguires'];
	} else {
		echo 'Erro ao executar a query';
	}

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
	
		<script type="text/javascript">

			$(document).ready( function(){ //Após a página ter sido totalmente carregada ...

				//Associar o evento de click ao botão
				$('#btn_procurar_pessoa').click( function(){ //Sempre que clicar no botão de pesquisar pessoas de id='btn_procurar_pessoa' ...
					
					if($('#nome_pessoa').val().length > 0){ //Se algo foi digitado ...
						
						$.ajax({ //Via AJAX ...
							url: 'get_pessoas.php', //Direciona para a página 'get_pessoas.php'
							method: 'post', //Usando o método POST
							data: $('#form_procurar_pessoas').serialize(), //O conteúdo do formulário de id='form_procurar_pessoas'
							success: function(data) { //Em caso de sucesso ...
								$('#pessoas').html(data); //É inserido na div de id='pessoas' o contéudo(data) recuperado da página 'form_procurar_pessoas.php'

								$('.btn_seguir').click( function(){ //Sempre que clicar no botão seguir de uma pessoa de class='btn_seguir' ...
									var id_usuario = $(this).data('id_usuario'); //Recuperando o atributo 'data' passado dentro da tag do próprio botão de nome 'id_usuario' (no caso, o usuário que passará a ser seguido) (Obs.: Ver mais sobre 'data' em: get_pessoas.php)

									$('#btn_seguir_'+id_usuario).hide(); //Esconde o botão de seguir
									$('#btn_deixar_seguir_'+id_usuario).show(); //Exibe o botão de deixar de seguir

									$.ajax({ //Via AJAX
										url: 'seguir.php', //Direciona para a página 'seguir.php'
										method: 'post', //Usando o método POST
										data: { seguir_id_usuario: id_usuario }, //Passa a data como sendo o id do usuario que será seguido
										success: function(data){ //Em caso de sucesso ...

											//alert('Registro efetuado com sucesso!');

											atualizaQtdTweetsSeguidores();

										}
									});

								});

								$('.btn_deixar_seguir').click( function(){ //Sempre que clicar no botão de deixar seguir de uma pessoa de class='btn_deixar_seguirr' ...
									var id_usuario = $(this).data('id_usuario'); //Recuperando o atributo 'data' passado dentro da tag do próprio botão de nome 'id_usuario' (no caso, o usuário que deixará de ser seguido) (Obs.: Ver mais sobre 'data' em: get_pessoas.php)

									$('#btn_seguir_'+id_usuario).show(); //Exibe o botão de seguir
									$('#btn_deixar_seguir_'+id_usuario).hide(); //Esconde o botão de deixar de seguir

									$.ajax({ //Via AJAX
										url: 'deixar_seguir.php', //Direciona para a página 'deixar_seguir.php'
										method: 'post', //Usando o método POST
										data: { deixar_seguir_id_usuario: id_usuario },
										success: function(data){ //Passa a data como sendo o id do usuario que deixará de ser seguido

											//alert('Registro removido com sucesso!');

											atualizaQtdTweetsSeguidores(); //Chama a função 'atualizaQtdTweetsSeguidores()' para atualizar a quantidade de tweets e de seguidores
										}
									});

								});
							}
						});
					}

				});

				function atualizaQtdTweetsSeguidores(){ //Carregar a quantidade de tweets e seguidores.  /  Essa função será chamada assim mque a página for carregada e sempre que um novo tweet for inserido ou apagado

					$.ajax({ //Via AJAX ...
						url: 'get_qtd_tweets_seguidores.php',  //Direciona para a página 'get_qtd_tweets_seguidores.php'
						success: function(data) { //Em caso de sucesso ... 
							$('#qtd_tweets_seguidores').html(data); //É inserido na div de id='qtd_tweets_seguidores' o contéudo(data) recuperado da página 'get_qtd_tweets_seguidores.php'
						}
					});
				}

				atualizaQtdTweetsSeguidores(); //A função 'atualizaQtdTweetsSeguidores()' é chamada assim que a página é carregada, para exibir a quantidade de tweets e de seguidores

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
	            	<a class="nav-link" href="home.php">Home</a> <!-- Classe do Bootstrap para criação de links da Barra de Navegação -->
	            </li>
	            <li class="nav-item"> <!-- Classe do Bootstrap para criação de itens da Barra de Navegação -->
	            	<a class="nav-link" href="sair.php">Sair</a> <!-- Classe do Bootstrap para criação de links da Barra de Navegação -->
	            </li>
	          </ul>
	        </div><!--/.nav-collapse -->
	    </nav><!--/.nav -->


	    <div class="container">
	    	<div class="row"> <!-- Classe do Bootstrap para criação de linha que podem ser "divididas" em até 12 partes(colunas) -->
	
		    	<div class="col-md-3"> <!-- Classe do Bootstrap -> Pegando 3 das 12 linhas (mais a esquerda na página)  /  Nesse espaço ficarão as informações de tweets e seguidores -->
		    		<div class="card"> <!-- Classe do Bootstrap -> A card is a flexible and extensible content container. It includes options for headers and footers, a wide variety of content, contextual background colors, and powerful display options. -->
		    			<div class="card-body"> <!-- Classe do Bootstrap -->
		    				
		    				<h4><?= $_SESSION['usuario'] ?></h4> <!-- Imprimindo com a tag curta de impressão do PHP o nome de usuário armazenado na variável de sessão-->
		    				<hr />
		    				<div class="row" id="qtd_tweets_seguidores">
		    					<!-- É AQUI QUE APARECERÁ DINâMICAMENTE A QUANTIDADE DE TWEETS E A QUANTIDADE DE SEGUIDORES.   / É feito em um comando JS acima -->
		    				</div>
		    				
		    			</div>
		    		</div>
		    	</div>
	    	
	    	<div class="col-md-6"> <!-- Classe do Bootstrap -> Pegando 6 das 12 linhas (mais centraliazadas na página)  /  Nesse espaço ficará o campo de pesquisa de pessoas -->
	    		<div class="card"> <!-- Classe do Bootstrap -> A card is a flexible and extensible content container. It includes options for headers and footers, a wide variety of content, contextual background colors, and powerful display options. -->
	    			<div class="card-body"> <!-- Classe do Bootstrap -->
	    				<form id="form_procurar_pessoas" class="input-group"> <!-- Formulário para pesquisa de pessoas -->
	    					<input type="text" id="nome_pessoa" name="nome_pessoa" class="form-control" placeholder="Quem você está procurando?" /> <!-- Onde pesquisa por pessoas. / A classe 'form-control' é uma classe do Bootstrap -->

	    					<button class="btn btn-primary" id="btn_procurar_pessoa" type="button">Procurar</button>

	    				</form>
	    			</div>
	    		</div>

	    		<div id="pessoas" class="list-group">
	    			<!-- É aqui que serão exibidos as pessoas que forem pesquisadas  /  As pessoas são recuperados via JS lá em cima na página -->
	    		</div>

			</div>

			<div class="col-md-3"> <!-- Classe do Bootstrap -> Pegando 3 das 12 linhas (mais a direita na página) -->

			</div>

		</div>
	</div>


	    </div>
	
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	</body>
</html>