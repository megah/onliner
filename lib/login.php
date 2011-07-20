<?php

include_once 'users.php';

parse_str($_SERVER[QUERY_STRING],$query);

if (isset($query['login'])){
	 $template=str_ireplace('{TITLE}','Войти на сайт',$template);
	 
	 
   if (!isset($_POST['Name']))
   {$template=str_ireplace('{MAIN}','<div id="reg"><h1>Вход на сайт:</h1>
              <form action="index.php?login" id="form-reg" method="post">
              <p>Имя пользователя:</p>
              <input type="text" name="Name" class="field" maxlength="20"/>
              <p>Пароль:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/><br /><br  />
              <input type="submit" value="Войти" />                      
               </form></div><!-- reg -->',$template);} 
     else { 
     	     $UserName=$_POST['Name'];
     	     $UserPass=$_POST['Pass'];   
     	   
     	    if(User_Register($UserName, $UserPass)) {
     	    	$template=str_ireplace('{MAIN}','<div id="reg"><h1>Вы успешно вошли!</h1>
              </div><!-- reg -->',$template);
     	    	$AuthSystem['IsRegister']=true;
     	    	$AuthSystem['UserName']=$UserName;
     	    }    else 
		     	    {
		     $template=str_ireplace('{MAIN}','<div id="reg"><h1>Вход на сайт:</h1>
		     <h1 style="color:red;">Неверное имя или пароль!</h1>
              <form action="index.php?login" id="form-reg" method="post">
              <p>Имя пользователя:</p>
              <input type="text" name="Name" class="field" maxlength="20"/>
              <p>Пароль:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/><br /><br  />
              <input type="submit" value="Войти" />                      
               </form></div><!-- reg -->',$template);	
		     	    	
		     	    	
		     	    }
     }
	 
} else {
	$AuthSystem['IsRegister']=false;
	$_SESSION=array();
	unset($_COOKIE[session_name()]);
	session_destroy;
     $template=str_ireplace('{MAIN}','<div id="reg"><h1>Выход:</h1>
		     <h1 style="color:red;">Вы успешно вышли!</h1>
             </div><!-- reg -->',$template);
}
    

?>