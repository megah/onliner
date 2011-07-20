<?php
include_once 'users.php';

parse_str($_SERVER[QUERY_STRING],$query);
$template=file('./index.tpl');
$AuthSystem=&$_SESSION['AuthSystem'];



if ($query==array()) include_once 'main.php';

reset($query);
$k=key($query);


	switch($k){
		case 'register':
			include_once 'register.php';
			break;
		case 'login':
			include_once 'login.php';
			break;
		case 'logout':
			include_once 'login.php';
			break;
		case 'show_files':
			include_once 'showfiles.php';
			break;
		default:
			include_once 'main.php';
			break;
			
	}





if ($AuthSystem['IsRegister']==true){
	$template=str_ireplace('{NAME}', $AuthSystem['UserName'].'!'.'&nbsp;<a href="index.php?logout">Выйти</a>', $template);
} else {$template=str_ireplace('{Name}', 'Гость!'.'&nbsp;<a href="index.php?login">Войти</a>', $template);}


   foreach($template as $line=>$text){
   	 echo "$text";
   }

?>