<?php
/*Register*/
if (isset($query['register'])) {
   $template=str_ireplace('{TITLE}','Регистрация нового пользователя',$template);
   if (strlen($query['register'])==0)
   {$template=str_ireplace('{MAIN}','<div id="reg"><h1>Регистрация нового пользователя:</h1>
              <form action="index.php?register=doAdd" id="form-reg" method="post">
              <p>Имя пользователя:</p>
              <input type="text" name="UserName" class="field" maxlength="20"/>Имя может содержать до 20 символов латинских букв и цифр.
              <p>Пароль:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/>Пароль может содержать до 20 символов.
              <p>Email:</p>
              <input type="text" name="Email" class="field" /><br /><br  />
              <input type="submit" value="Зарегестрироваться" />                      
               </form></div><!-- reg -->',$template);}
   
   
  if ($query['register'] == 'doAdd') {
       $UserName=$_POST['UserName'];
       $UserPass=$_POST['Pass'];
       $UserEmail=$_POST['Email'];
      if ((strlen($UserName)==0)||(strlen($UserPass)==0)){
      	$template=str_ireplace('{MAIN}','<div id="reg"><h1>Регистрация нового пользователя:</h1>
              <form action="index.php?register=doAdd" id="form-reg" method="post">
              <p style="color:red">Все поля обязательны к заполнению</p><br />              
              <p>Имя пользователя:</p>
              <input type="text" name="UserName" class="field" maxlength="20"/>Имя может содержать до 20 символов латинских букв и цифр.
              <p>Пароль:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/>Пароль может содержать до 20 символов.
                            <p>E-mail:</p>
              <input type="text" name="Email" class="field" /><br /><br  />
              <input type="submit" value="Зарегестрироваться" />                      
               </form></div><!-- reg -->',$template);
      }  			else 
                           if(!NameCorrect($UserName)||!EmailCorrect($UserEmail)){
                           	        	        
      	                   	 $template=str_ireplace('{MAIN}','<div id="reg"><h1>Регистрация нового пользователя:</h1>
      	                   	  <h1 style="color:red">Некоректно введенные данные</h1>
           </div><!-- reg -->',$template);
                           }       
      	                   	
      	                    else 
      	                           
      	                   if (User_Exists($UserName, $UserEmail)){
      	                   	  $template=str_ireplace('{MAIN}','<div id="reg"><h1>Регистрация нового пользователя:</h1>
      	                   	  <h1 style="color:red">Извините пользователь с таким именем/e-mail уже зарегистрирован</h1>
           </div><!-- reg -->',$template);      	                          
      	                      
      	
      	
      					} else {
      						AddUser($UserName, $UserPass, $UserEmail);
      						$template=str_ireplace('{MAIN}','<div id="reg"><h1>Вы успешно зарегестрированы!</h1>
      	                   	 
           </div><!-- reg -->',$template); 
      					}
  }
       
      
}

/*End register*/
?>