<?php
	
	session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

	if(!isset($_SESSION['usuario'])){ //Se a variável de sessão de nome 'usuario' não tiver sido iniciada, não pode acessar a página home.php (que é uma página só para pessoas logadas com sucesso), então a página é redirecionada para index.php com um erro sendo passado por método GET, que será tratado na página index.php
		header('Location: index.php?erro=1');
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
				$('#btn_tweet').click( function(){ //Sempre que clicar no botão de publicas Tweet de id='btn_tweet' ...
					
					if($('#texto_tweet').val().length > 0){ //Se algo foi digitado ...
						
						$.ajax({ //Via AJAX ...
							url: 'inclui_tweet.php', //Direciona para a página 'inclui_tweet.php'
							method: 'post', //Usando o método POST
							data: $('#form_tweet').serialize(), //O conteúdo do formulário de id='form_tweet'
							success: function(data) { //Em caso de sucesso ...
								$('#texto_tweet').val(''); //Apaga o que foi digitado no Tweet, para se a pessoa quiser digitar algo novo
								
								atualizaTweet(); //Chama a função 'atualizaTweet()' para atualizar os tweets que são mostrados dinamicamente
								atualizaQtdTweetsSeguidores(); //Chama a função 'atualizaQtdTweetsSeguidores()' para atualizar a quantidade de tweets e de seguidores
							}
						});
					}

				});

				function atualizaTweet(){ //Carregar os tweets.  /  Essa função será chamada assim mque a página for carregada e sempre que um novo tweet for inserido ou apagado

					$.ajax({ //Via AJAX ...
						url: 'get_tweet.php',  //Direciona para a página 'get_tweet.php'
						success: function(data) { //Em caso de sucesso ... 
							$('#tweets').html(data); //É inserido na div de id='tweets' o contéudo(data) recuperado da página 'get_tweet.php'

							$('.btn_apagar_tweet').click( function(){ //Sempre que clicar no botão de apagar um tweet  de class='btn_apagar_tweet' ...
									var id_tweet = $(this).data('id_tweet'); //Recuperando o atributo 'data' passado dentro da tag do próprio botão de nome 'id_tweet' (no caso, é o id do tweet que será apagado) (Obs.: Ver mais sobre 'data' em: get_tweet.php)

									//alert("Apagou!"); //Teste
				
									$.ajax({ //Via AJAX
										url: 'apaga_tweet.php', //Direciona para a página 'apaga_tweet.php'
										method: 'post', //Usando o método POST
										data: { apagar_id_tweet: id_tweet }, //Passa a data como sendo o id do tweet que será apagado
										success: function(data){ //Em caso de sucesso ...

											//alert('Registro efetuado com sucesso!'); //Teste

											atualizaTweet(); //Chama a própria função atualizaTweet() uma vez que um tweet foi apagado e tem que atualizar os tweets exibidos dinamicamente

											atualizaQtdTweetsSeguidores(); //Chama a função 'atualizaQtdTweetsSeguidores()' para atualizar a quantidade de tweets e de seguidores

										}
									});

								});

						}
					});
				}

				function atualizaQtdTweetsSeguidores(){ //Carregar a quantidade de tweets e seguidores.  /  Essa função será chamada assim mque a página for carregada e sempre que um novo tweet for inserido ou apagado

					$.ajax({ //Via AJAX ...
						url: 'get_qtd_tweets_seguidores.php',  //Direciona para a página 'get_qtd_tweets_seguidores.php'
						success: function(data) { //Em caso de sucesso ... 
							$('#qtd_tweets_seguidores').html(data); //É inserido na div de id='qtd_tweets_seguidores' o contéudo(data) recuperado da página 'get_qtd_tweets_seguidores.php'
						}
					});
				}

				atualizaTweet(); //A função 'atualizaTweet()' é chamada assim que a página é carregada, pois os tweets tem que ser exibidos assim que a página é carregada

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
		    	
		    	<div class="col-md-6"> <!-- Classe do Bootstrap -> Pegando 6 das 12 linhas (mais centraliazadas na página)  /  Nesse espaço ficarão os tweets e a opção e tweetar -->
		    		<div class="card"> <!-- Classe do Bootstrap -> A card is a flexible and extensible content container. It includes options for headers and footers, a wide variety of content, contextual background colors, and powerful display options. -->
		    			<div class="card-body"> <!-- Classe do Bootstrap -->

		    				<form id="form_tweet" class="input-group"> <!-- Formulário para criação de um Tweet -->
		    					<input type="text" id="texto_tweet" name="texto_tweet" class="form-control" placeholder="O que está acontecendo agora?" maxlength="140" /> <!-- Onde será digitado o Tweet, que tem que ter tamanho máximo de 140 caracteres. / A classe 'form-control' é uma classe do Bootstrap -->
		    					
		    					<button class="btn btn-primary" id="btn_tweet" type="button">Tweet</button>

		    				</form>

		    			</div>
		    		</div>

		    		<div id="tweets" class="list-group">
		    			<!-- É aqui que serão exibidos os Tweets feitos (tanto pelo usuário, quanto por seguidores)  /  Os tweets são recuperados via JS lá em cima na página -->
		    		</div>

				</div>

				<div class="col-md-3"> <!-- Classe do Bootstrap -> Pegando 3 das 12 linhas (mais a direita na página)  /  Nesse espaço ficará a opção de pesquisar por pessoas -->
					<div class="card"> <!-- Classe do Bootstrap -> A card is a flexible and extensible content container. It includes options for headers and footers, a wide variety of content, contextual background colors, and powerful display options. -->
						<div class="card-body"> <!-- Classe do Bootstrap -->
							<h4><a href="procurar_pessoas.php">Procurar por pessoas</h4> <!-- Se for procurar por alguma pessoa, será redirecionado para 'procurar_pessoas.php' -->
						</div>
					</div>
				</div>

			</div>
		</div>

	    </div>
	
	</body>
</html>