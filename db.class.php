<!-- Classe de Conexão com o Banco de Dados -->

<?php

class db {

	//Host
	private $host = 'localhost';

	//Usuario
	private $usuario = 'root';

	//Senha
	private $senha = '';

	//Banco de dados
	private $database = 'twitter_clone';

	public function conecta_mysql(){

		//Cria a conexao
		$con = mysqli_connect($this->host, $this->usuario, $this->senha, $this->database);

		//Ajustar o charset de comunicação entre a aplicação e o banco de dados
		mysqli_set_charset($con, 'utf8');

		//Verficar se houve erro de conexão
		if(mysqli_connect_errno()){
			echo 'Erro ao tentar se conectar com o BD MySQL: '.mysqli_connect_error();	
		}

		return $con;
	}

}

?>