<?php

include_once 'db.php';

/*Функция добавления нового пользователя*/
function AddUser($UserName,$UserPass,$UserEmail){
	$UserEmail=mysql_real_escape_string($UserEmail);
	$UserPass=mysql_real_escape_string($UserPass);
	mysql_query("INSERT INTO users(User_Name,Pass,Email) Values (\"$UserName\",\"$UserPass\",\"$UserEmail\")") 
	or die(mysql_error());
}


/*Функция проверки существования пользователя и e-mail*/
function User_Exists($UserName,$UserEmail){
	$result=mysql_query("SELECT user_name,email FROM users WHERE user_name=\"$UserName\" or email=\"$UserEmail\"") or die(
	mysql_error());
	if (mysql_num_rows($result)==0) {
		return false;
	} else {
		return true;
	}
}


function User_Register($UserName,$UserPass){
	$UserName=mysql_real_escape_string($UserName);
	$UserPass=mysql_real_escape_string($UserPass);
	$result=mysql_query("SELECT user_name,pass FROM users WHERE user_name=\"$UserName\" and pass=\"$UserPass\"") or die(
	mysql_error());
	if (mysql_num_rows($result)==0) {
		return false;
	} else {
		return true;
	}
	
}

/*Функция проверки корректности e-mail*/

function EmailCorrect($email){
	$pattern='/^(([a-zA-Z0-9]|[!#$%\*\/\?\|^\{\}`~&\'+=-_])+.)*([a-zA-Z0-9]|[!#$%*\/\?|^{}`~&\'+=-_])+@([a-zA-Z0-9-]+.)+[a-zA-Z0-9-]+$/';
	if (preg_match($pattern, $email)){
		return true;
	} else 
	{return false;}
}


/*Функция проверки корректности имени*/

function NameCorrect($name){
	$pattern='/(\W+)/';
	    if (!preg_match($pattern, $name)) {
	    	return true;
	    } else {return FALSE;}
}

?>