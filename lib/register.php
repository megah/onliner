<?php
/*Register*/
if (isset($query['register'])) {
   $template=str_ireplace('{TITLE}','����������� ������ ������������',$template);
   if (strlen($query['register'])==0)
   {$template=str_ireplace('{MAIN}','<div id="reg"><h1>����������� ������ ������������:</h1>
              <form action="index.php?register=doAdd" id="form-reg" method="post">
              <p>��� ������������:</p>
              <input type="text" name="UserName" class="field" maxlength="20"/>��� ����� ��������� �� 20 �������� ��������� ���� � ����.
              <p>������:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/>������ ����� ��������� �� 20 ��������.
              <p>Email:</p>
              <input type="text" name="Email" class="field" /><br /><br  />
              <input type="submit" value="������������������" />                      
               </form></div><!-- reg -->',$template);}
   
   
  if ($query['register'] == 'doAdd') {
       $UserName=$_POST['UserName'];
       $UserPass=$_POST['Pass'];
       $UserEmail=$_POST['Email'];
      if ((strlen($UserName)==0)||(strlen($UserPass)==0)){
      	$template=str_ireplace('{MAIN}','<div id="reg"><h1>����������� ������ ������������:</h1>
              <form action="index.php?register=doAdd" id="form-reg" method="post">
              <p style="color:red">��� ���� ����������� � ����������</p><br />              
              <p>��� ������������:</p>
              <input type="text" name="UserName" class="field" maxlength="20"/>��� ����� ��������� �� 20 �������� ��������� ���� � ����.
              <p>������:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/>������ ����� ��������� �� 20 ��������.
                            <p>E-mail:</p>
              <input type="text" name="Email" class="field" /><br /><br  />
              <input type="submit" value="������������������" />                      
               </form></div><!-- reg -->',$template);
      }  			else 
                           if(!NameCorrect($UserName)||!EmailCorrect($UserEmail)){
                           	        	        
      	                   	 $template=str_ireplace('{MAIN}','<div id="reg"><h1>����������� ������ ������������:</h1>
      	                   	  <h1 style="color:red">���������� ��������� ������</h1>
           </div><!-- reg -->',$template);
                           }       
      	                   	
      	                    else 
      	                           
      	                   if (User_Exists($UserName, $UserEmail)){
      	                   	  $template=str_ireplace('{MAIN}','<div id="reg"><h1>����������� ������ ������������:</h1>
      	                   	  <h1 style="color:red">�������� ������������ � ����� ������/e-mail ��� ���������������</h1>
           </div><!-- reg -->',$template);      	                          
      	                      
      	
      	
      					} else {
      						AddUser($UserName, $UserPass, $UserEmail);
      						$template=str_ireplace('{MAIN}','<div id="reg"><h1>�� ������� ����������������!</h1>
      	                   	 
           </div><!-- reg -->',$template); 
      					}
  }
       
      
}

/*End register*/
?>