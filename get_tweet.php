<?php

	session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

	if(!isset($_SESSION['usuario'])){ //Se a variável de sessão de nome 'usuario' não tiver sido iniciada, não pode acessar a página get_tweet.php (que é uma página só para pessoas logadas com sucesso), então a página é redirecionada para index.php com um erro sendo passado por método GET, que será tratado na página index.php
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php'); //Chamando a classe de conexão com o Banco de Dados

	$id_usuario = $_SESSION['id_usuario']; //Pegando o valor de 'id_usuario' da variável de sessão. Pois 'id_usuario' será usado para fazer pesquisasno Banco de Dados

	//Criando a conexão com o Banco de Dados:
	$objDb = new db();
	$link = $objDb->conecta_mysql();
	
	//Criando comando sql que recupera Tweets feitos pelo usuário ou por pessoas que o usuário segue. Recupera: tweet, usuário que fez o tweet e data em que o tweet foi feito. Ordenando por data em que o tweet foi feito em ordem decrescente:
	$sql = " SELECT DATE_FORMAT(t.data_inclusao, '%d %b %Y %T') AS data_inclusao_formatada, t.tweet, t.id_usuario, t.id_tweet, u.usuario ";
	$sql.= " FROM tweet AS t JOIN usuarios AS u ON (t.id_usuario = u.id) ";
	$sql.= " WHERE id_usuario = $id_usuario ";
	$sql.= " OR id_usuario IN (select seguindo_id_usuario from usuarios_seguidores where id_usuario = $id_usuario) ";
	$sql.= " ORDER BY data_inclusao DESC ";

	$resultado_id = mysqli_query($link, $sql); //Fazendo a pesquisa no Banco de Dados //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE

	if($resultado_id){ //Se a pesquisa no Banco de Dados foi realizada com sucesso ...

		while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){ //Para cada resultado encontrado (ou seja, para cada tweet feito pelo usuário ou pessoa que o usuário segue ...)  /  Obs.: É feita uma atribuição à variável '$registro' dentro da própria cláusula do 'while'

		

			//CRIANDO A IMPRESSÃO DOS TWEETS:
			echo '<a href="#" class="list-group-item">'; //class="list-group-item" é uma classe do Bootstrap
				echo '<h4 class="">'.$registro['usuario'].' <spam class= "text-muted" style="font-size: 12px; float:right"> - '.$registro['data_inclusao_formatada'].'</spam> </h4>'; //class= "text-muted" é uma classe do Bootstrap
				echo '<p class="">'.$registro['tweet'].'</p>';


				if($registro['id_usuario'] == $id_usuario){ //Se o tweet encontrado for do próprio usuário que está logado ele pode ser deletado (só podem ser apagados tweets ´feitos pelo próprio usuário) ...

					echo '<button type="button" id="btn_apagar_tweet_'.$registro['id_tweet'].'" style="display: block" class="btn btn-primary btn_apagar_tweet float-right" data-id_tweet="'.$registro['id_tweet'].'">Apagar Tweet</button>'; //O id do botão para apagar tweet tem acrescido em seu nome o id do do próprio tweet exibido para tornar o botão específico para esse tweet que está sendo exibido, assim pode apagar esse tweet e somente esse tweet ao clicar no botão   /   'data' é usado para passar uma informação adiante via HTML. Em data-id_usuario, está sendo passado via 'data', pelo nome 'id_usuario' o registro do id do usuário da pesquisa, na tag desse botão.
				}
				
			echo '</a>';
		}

	} else { //Se a pesquisa no Banco de Dados NÃO foi realizada com sucesso ...
		echo 'Erro na consulta de tweets no banco de dados!';
	}

?>