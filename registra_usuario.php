<?php

	require_once('db.class.php'); //Chamando a classe de conexão com o Banco de Dados

	$usuario = $_POST['usuario']; //Pegando o nome de usuário recebido via método POST de 'usuario'
	$email = $_POST['email']; //Pegando o email recebido via método POST de 'email'
	$senha = md5($_POST['senha']); //Pegando a senha recebida via método POST de 'senha' e convertendo em md5 que é para encriptar a senha, tornando mais seguro

	//Criando a conexão com o Banco de Dados:
	$objDb = new db();
	$link = $objDb->conecta_mysql();

	//Criando variáveis que irão armazenar se usuario e email já existem no Banco de Dados, elas começam com valor de false (como se não existissem no Banco de Dados, pois a consulta ainda não foi feita)
	$usuario_existe = false;
	$email_existe = false;



	//VERIFICAR SE O NOME DE USUÁRIO JÁ EXISTE NO BANCO DE DADOS:

	$sql = " SELECT * FROM usuarios WHERE usuario = '$usuario' "; //Criando comando sql que irá selecionar usuários que tenham o mesmo nome do digitado

	if($resultado_id = mysqli_query($link, $sql)) { //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE    /   Se a pesquisa no Banco de Dados foi realizada com sucesso ...

		$dados_usuario = mysqli_fetch_array($resultado_id); //Função que retorna um array numérico e um array associativo com os resultados da pesquisa no Banco de Dados


		if(isset($dados_usuario['usuario'])){ //Se o resultado de retorno da pesquisa no Banco de Dados retornou um array que em seu campo associativo de nome 'usuario' tem um valor (ou seja, a pesquisa no Banco de Dados achou um usuário com o mesmo nome informado) ...
			$usuario_existe = true; //Então já existe um usuário usando esse nome de usuario
		}
	} else { //Se a pesquisa no Banco de Dados NÃO foi realizada com sucesso ...
		echo 'Erro ao tentar localizar o registro de usuário';
	}



	//VERIFICAR SE O EMAIL JÁ EXISTE NO BANCO DE DADOS:

	$sql = " select * from usuarios where email = '$email' "; //Criando comando sql que irá selecionar pessoas que tenham o mesmo email do digitado

	if($resultado_id = mysqli_query($link, $sql)) {

		$dados_usuario = mysqli_fetch_array($resultado_id);

		if(isset($dados_usuario['email'])){
			$email_existe = true;
		} 
	} else {
		echo 'Erro ao tentar localizar o registro de email';
	}





	if($usuario_existe || $email_existe){ //Se o nome de usuario ou o email já existem no Banco de Dados ...

		$retorno_get = ''; //Variável que irá retornar os erros via método GET

		if($usuario_existe){ //Se o nome do usuário já existe no Banco de Dados ...
			$retorno_get.= "erro_usuario=1&"; //É criado o erro correspondente
		}

		if($email_existe){ //Se o email já existe no Banco de Dados ...
			$retorno_get.= "erro_email=1&"; //É criado o erro correspondente
		}

		header('Location: inscrevase.php?'.$retorno_get); //É direcionado para a página inscrevasse.php adicionando via GET os erros correspondentes de nome de usuário e/ou email que já existam no Banco de Dados

		die(); //Encerra a execução do script. Nada abaixo é executado.
	}

	$sql = " insert into usuarios(usuario, email, senha) values ('$usuario', '$email', '$senha') "; //Criando comando sql que irá inserir o novo registro de usuário no Banco de Dados


	//Executar a Query:
	if(mysqli_query($link, $sql)){ //Realizando a inserção do tweet no Banco de Dados. //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE   /   Se a inserção no Banco de Dados foi realizada com sucesso ... 

		echo 'Usuário registrado com sucesso!';

	} else { //Se a pesquisa no Banco de Dados NÃO foi realizada com sucesso ...
		
		echo 'Erro ao registrar o usuário!';
	}


?>