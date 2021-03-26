<!-- Classe de validação de acesso para logar no twitter_clone  -->

<?php

	session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

	require_once('db.class.php'); //Chamando a classe de conexão com o Banco de Dados

	$usuario = $_POST['usuario']; //Pegando o valor recebido via método POST de 'usuario'
	$senha = md5($_POST['senha']); //Pegando o valor recebido via método POST de 'senha' e codificando em md5, pois o valor armazenado no Banco de Dados está convertido em md5 para segurança da senha

	$sql = " SELECT id, usuario, email FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha' "; //Criando comando sql que checará se existe algum usuário igual ao informado e de senha também igual a informada

	//Criando a conexão com o Banco de Dados:
	$objDb = new db();
	$link = $objDb->conecta_mysql();

	//Fazendo a pesquisa no Banco de Dados
	$resultado_id = mysqli_query($link, $sql); //É passado a conexão com o Banco de Dados e a query de pesquisa. Retorna um objeto mysqli_result para queries SELECT, SHOW, DESCRIBE, ou EXPLAIN realizadas com sucesso. Para outras queries realizadas com sucesso, retorna TRUE, e em caso de falha retorna FALSE

	if($resultado_id){ //Se a pesquisa no Banco de Dados foi realizada com sucesso ...

		$dados_usuario = mysqli_fetch_array($resultado_id); //Função que retorna um array numérico e um array associativo com os resultados da pesquisa no Banco de Dados

		//$dados_usuario = mysqli_fetch_array($resultado_id, MYSQLI_NUM); //Função que retorna SOMENTE o array numérico com os resultados da pesquisa no Banco de Dados
		//$dados_usuario = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC); //Função que retorna SOMENTE o array associativo com os resultados da pesquisa no Banco de Dados

		//var_dump($dados_usuario); //Testando os arrays retornados da pesquisa no Banco de Dados, que podem ser: Array Numérico, Aray Associativo ou os dois

		if(isset($dados_usuario['usuario'])){ //Se o resultado de retorno da pesquisa no Banco de Dados retornou um array que em seu campo associativo de nome 'usuario' tem um valor (ou seja, a pesquisa no Banco de Dados achou um usuário com a senha igual a informada) ...

			$_SESSION['id_usuario'] = $dados_usuario['id']; //Iniciando a variável de sessão do id_usuario
			$_SESSION['usuario'] = $dados_usuario['usuario']; //Iniciando a variável de sessão do usuario
			$_SESSION['email'] = $dados_usuario['email'];  //Iniciando a variável de sessão do email
			
			header('Location: home.php'); //Pode redirecionar para a página home.php pois o login ocorreu ocm sucesso

		} else {
			header('Location: index.php?erro=1'); //O login não ocorreu com sucesso, pois o usuário e/ou senha informados não estão corretos ou não foram cadastrados no Bancode Dados, então a página é direcionada para index.php passando um erro por método GET. Esse erro será tratado na página index.php .
		}
	} else { //Se a pesquisa no Banco de Dados NÃO foi realizada com sucesso ...
		echo 'Erro na execução da consulta, favor entrar em contato com o admin do site';
	}


	


?>