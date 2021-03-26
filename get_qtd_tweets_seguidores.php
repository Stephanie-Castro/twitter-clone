<?php

	session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

	if(!isset($_SESSION['usuario'])){ //Se a variável de sessão de nome 'usuario' não tiver sido iniciada, não pode acessar a página get_qtd_tweets_seguidores.php (que é uma página só para pessoas logadas com sucesso), então a página é redirecionada para index.php com um erro sendo passado por método GET, que será tratado na página index.php
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php'); //Chamando a classe de conexão com o Banco de Dados

	$id_usuario = $_SESSION['id_usuario']; //Pegando o valor de 'id_usuario' da variável de sessão. Pois 'id_usuario' será usado para fazer pesquisasno Banco de Dados

	//Criando a conexão com o Banco de Dados:
	$objDb = new db();
	$link = $objDb->conecta_mysql();


	

	//QUANTIDADE DE TWEETS:

	$sql = " SELECT COUNT(*) AS qtde_tweets FROM tweet WHERE id_usuario = $id_usuario "; //Criando comando sql que fará a contagem de Tweets feitos pelo usuário

	$resultado_qtd_tweets = mysqli_query($link, $sql); //Fazendo a pesquisa no Banco de Dados //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE

	$qtde_tweets = 0; //Essa variável irá armazenar a quantidade de Tweets feitas pelo usuário. Ela é iniciada com 0, até que a pesquisa no Banco de Dados seja feita



	//QUANTIDADE DE SEGUIDORES:

	$sql = " SELECT COUNT(*) AS qtde_seguidores FROM usuarios_seguidores WHERE id_usuario = $id_usuario "; //Criando comando sql que fará a contagem de seguidores
	$resultado_qtd_seguidores = mysqli_query($link, $sql);
	$qtde_seguidores = 0;


	if($resultado_qtd_tweets && $resultado_qtd_seguidores){ //Se as pesquisas no Banco de Dados foram realizadas com sucesso ...

		$registro_qtd_tweets = mysqli_fetch_array($resultado_qtd_tweets, MYSQLI_ASSOC); //Função que retorna SOMENTE o array associativo com os resultados da pesquisa no Banco de Dados
		$qtde_tweets = $registro_qtd_tweets['qtde_tweets']; //Atualizando o valor da quantidade de tweets feitos após a pesquisa do Banco de Dados

		$registro_qtd_seguidores = mysqli_fetch_array($resultado_qtd_seguidores, MYSQLI_ASSOC);
		$qtde_seguidores = $registro_qtd_seguidores['qtde_seguidores'];



		//CRIANDO A IMPRESSÃO DOA QUANTIDADE DE TWEETS E SEGUIDORES:
		echo '<div class="col-md-6">';
			echo "TWEETS";
			echo "<br/>";
			echo $qtde_tweets;
		echo "</div>";
		echo '<div class="col-md-6">';
			echo "SEGUIDORES";
			echo "<br/>";
			echo $qtde_seguidores;
		echo "</div>";


	} else { //Se a pesquisa no Banco de Dados NÃO foi realizada com sucesso ...
		echo 'Erro ao executar a query';
	}




?>