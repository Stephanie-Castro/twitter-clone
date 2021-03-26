<?php

session_start(); //Iniciando uma sessão. As variáveis armazenadas em 'session' podem ser acessadas em qualquer página desse programa. / Isso é feito para que reconheça o usuário, sem a sessão torna-se impossível identificar quem fez o login quando o usuário ir para uma outra página. Quando se utiliza o session_start() é criado (ou utilizado) o cookie de PHPSESSID com um valor que deve ser único, isso permite que o usuário navegue em várias páginas e seja sempre reconhecido pelo mesmo cookie e portanto pela mesma sessão.

 //Unsetting as variáveis de sessão:
//unset($_SESSION['usuario']);
//unset($_SESSION['email']);

unset($_SESSION);

session_destroy();

echo 'Esperamos você de volta em breve!!!'

?>