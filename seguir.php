<?php

	session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

	if(!isset($_SESSION['usuario'])){//Se a variável de sessão de nome 'usuario' não tiver sido iniciada, não pode acessar a página seguir.php (que é uma página só para pessoas logadas com sucesso), então a página é redirecionada para index.php com um erro sendo passado por método GET, que será tratado na página index.php
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php'); //Chamando a classe de conexão com o Banco de Dados

	$id_usuario = $_SESSION['id_usuario']; //Pegando o valor de 'id_usuario' da variável de sessão. Pois 'id_usuario' será usado para fazer pesquisas no Banco de Dados
	$seguir_id_usuario = $_POST['seguir_id_usuario']; //Pegando o valor de 'seguir_id_usuario' que foi passada via métdo POST. Esse é o id da pessoa que o usuário quer seguir

	if($id_usuario == '' || $seguir_id_usuario == ''){ //Se o id_usuario não tiver valor definido, nem o seguir_id_usuario tiver valor definido a aplicação/script tem que ser interrompido ...
		die();
	}

	//Criando a conexão com o Banco de Dados
	$objDb = new db();
	$link = $objDb->conecta_mysql();
	
	$sql = " INSERT INTO usuarios_seguidores(id_usuario, seguindo_id_usuario)values($id_usuario, $seguir_id_usuario) "; //Criando comando sql que irá inserir na tabela 'usuarios_seguidores' as informações correspondentes ao usuário e a quem ele deseja seguir que foram passadas

	mysqli_query($link, $sql); //Realizando a inserção de 'usuarios_seguidores' no Banco de Dados. //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE

?>