<?php

include_once 'users.php';

parse_str($_SERVER[QUERY_STRING],$query);

if (isset($query['login'])){
	 $template=str_ireplace('{TITLE}','����� �� ����',$template);
	 
	 
   if (!isset($_POST['Name']))
   {$template=str_ireplace('{MAIN}','<div id="reg"><h1>���� �� ����:</h1>
              <form action="index.php?login" id="form-reg" method="post">
              <p>��� ������������:</p>
              <input type="text" name="Name" class="field" maxlength="20"/>
              <p>������:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/><br /><br  />
              <input type="submit" value="�����" />                      
               </form></div><!-- reg -->',$template);} 
     else { 
     	     $UserName=$_POST['Name'];
     	     $UserPass=$_POST['Pass'];   
     	   
     	    if(User_Register($UserName, $UserPass)) {
     	    	$template=str_ireplace('{MAIN}','<div id="reg"><h1>�� ������� �����!</h1>
              </div><!-- reg -->',$template);
     	    	$AuthSystem['IsRegister']=true;
     	    	$AuthSystem['UserName']=$UserName;
     	    }    else 
		     	    {
		     $template=str_ireplace('{MAIN}','<div id="reg"><h1>���� �� ����:</h1>
		     <h1 style="color:red;">�������� ��� ��� ������!</h1>
              <form action="index.php?login" id="form-reg" method="post">
              <p>��� ������������:</p>
              <input type="text" name="Name" class="field" maxlength="20"/>
              <p>������:</p>
              <input type="password" name="Pass" class="field" maxlength="20"/><br /><br  />
              <input type="submit" value="�����" />                      
               </form></div><!-- reg -->',$template);	
		     	    	
		     	    	
		     	    }
     }
	 
} else {
	$AuthSystem['IsRegister']=false;
	$_SESSION=array();
	unset($_COOKIE[session_name()]);
	session_destroy;
     $template=str_ireplace('{MAIN}','<div id="reg"><h1>�����:</h1>
		     <h1 style="color:red;">�� ������� �����!</h1>
             </div><!-- reg -->',$template);
}
    

?>