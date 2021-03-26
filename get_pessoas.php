<?php

	session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

	if(!isset($_SESSION['usuario'])){ //Se a variável de sessão de nome 'usuario' não tiver sido iniciada, não pode acessar a página get_pessoas.php (que é uma página só para pessoas logadas com sucesso), então a página é redirecionada para index.php com um erro sendo passado por método GET, que será tratado na página index.php
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php'); //Chamando a classe de conexão com o Banco de Dados

	$nome_pessoa = $_POST['nome_pessoa']; //Pegando o nome da pessoa que está buscando, no campo de pesquisa por pessoas, via método POST de 'nome_pessoa'
	$id_usuario = $_SESSION['id_usuario']; //Pegando o valor de 'id_usuario' da variável de sessão. Pois 'id_usuario' será usado para fazer pesquisasno Banco de Dados

	//Criando a conexão com o Banco de Dados:
	$objDb = new db();
	$link = $objDb->conecta_mysql();
	
	//Criando comando sql que recupera as pessoas pelo nome buscado pelo usuário.   /   É usado o LEFT JOIN pois é para exibir qualquer pessoa cadastrada no Banco de Dados na tabela 'usuarios' que tenha o nome similar ao digitado na pesquisa
	$sql = " SELECT u.*, us.* "; //Seleciona tudo da tabela 'usuarios' e da tabela 'usuarios_seguidores' via LEFT JOIN
	$sql.= " FROM usuarios AS u ";
	$sql.= " LEFT JOIN usuarios_seguidores AS us ";
	$sql.= " ON (us.id_usuario = $id_usuario AND u.id = us.seguindo_id_usuario) "; //É para aparecer TODOS os usuários armazenados da tabela 'usuarios'. Da tabela 'usuarios_seguidores' é para aparecer as informações que tenham o id do usuario atual
	$sql.= " WHERE u.usuario like '%$nome_pessoa%' AND u.id <> $id_usuario "; //Só é para aparecer resultados que contenham o nome digitado pelo usuário (por isso usa %...%) e não pode aparecer o próprio usuário para ele mesmo

	$resultado_id = mysqli_query($link, $sql); //Fazendo a pesquisa no Banco de Dados //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE

	if($resultado_id){ //Se a pesquisa no Banco de Dados foi realizada com sucesso ...

		while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){ //Para cada resultado encontrado (ou seja, para cada pessoa encontrada com nome parecido ao digitado pelo usuário ...)  /  Obs.: É feita uma atribuição à variável '$registro' dentro da própria cláusula do 'while'

			//CRIANDO A IMPRESSÃO DAS PESSOAS:
			echo '<a href="#" class="list-group-item">'; //class="list-group-item" é uma classe do Bootstrap
				echo '<strong>'.$registro['usuario'].'</strong> <spam class= "text-muted" style="font-size: 12px; float:right"> - '.$registro['email'].'</spam>'; //class= "text-muted" é uma classe do Bootstrap
				
				echo '<div class="container">';
					$esta_seguindo_usuario_sn = isset($registro['id_usuario_seguidor']) && !empty($registro['id_usuario_seguidor']) ? 'S' : 'N'; //Cria uma variável que armazena 'S' se estiver seguindo o usuário que está sendo exibido, ou 'N' se não estiver seguindo o usuário que está sendo exibido.   /   Para saber se está seguindo ou não o atributo 'id_usuario_seguidor' está iniciado e não está vazio. Pois é no atributo 'id_usuario_seguidor' que é armazenado o id do usuário que está sendo seguido, e como foi feito um left join da tabela 'usuarios' com a tabela 'usuarios_seguidores', levando em conta o id do usuario atual, o atributo 'id_usuario_seguido' só estará preenchido se o mesmo for seguido pelo usuário atual

					//Os botões de seguir e deixar de seguir serão exibidos ou não dependendo se o usuário segue ou não a pessoa:
					$btn_seguir_display = 'block';
					$btn_deixar_seguir_display = 'block';

					if($esta_seguindo_usuario_sn == 'N'){ //Se o usuário atual NÃO segue o usuário que está sendo exibido ...
						$btn_deixar_seguir_display = 'none'; //É para ocultar o botão que deixa de seguir, pois já não segue ele mesmo
					} else { //Se o usuário atual SEGUE o usuário que está sendo exibido ...
						$btn_seguir_display = 'none'; //É para exibir o botão que deixa de seguir, pois ele tem a opção de deixar de seguir
					}

					echo '<button type="button" id="btn_seguir_'.$registro['id'].'" style="display: '.$btn_seguir_display.'" class="btn btn-primary btn_seguir float-right" data-id_usuario="'.$registro['id'].'">Seguir</button>'; //O id do botão seguir tem acrescido em seu nome o id do usuário exibido para tornar o botão específico para esse usuário que está sendo exibido   /   'data' é usado para passar uma informação adiante via HTML. Em data-id_usuario, está sendo passado via 'data', pelo nome 'id_usuario' o registro do id do usuário da pesquisa, na tag desse botão.
					echo '<button type="button" id="btn_deixar_seguir_'.$registro['id'].'" style="display: '.$btn_deixar_seguir_display.'" class="btn btn-secondary btn_deixar_seguir float-right" data-id_usuario="'.$registro['id'].'">Deixar de Seguir</button>'; //O id do botão deixar de seguir tem acrescido em seu nome o id do usuário exibido para tornar o botão específico para esse usuário que está sendo exibido   /   'data' é usado para passar uma informação adiante via HTML. Em data-id_usuario, está sendo passado via 'data', pelo nome 'id_usuario' o registro do id do usuário da pesquisa, na tag desse botão.
				echo '</div>';
				echo '<div class="clearfix"></div>'; //clearfix é uma classe do Bootstrap
			echo '</a>';
		}

	} else {
		echo 'Erro na consulta de usuários no banco de dados!';
	}

?>