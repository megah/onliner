<?php
include_once 'file.php';

$AuthSystem=&$_SESSION['AuthSystem'];
parse_str($_SERVER[QUERY_STRING],$query);

if ($AuthSystem['IsRegister']){
$template=str_ireplace('{MAIN}', "<a href=\"index.php?show_files=user&user_name={$AuthSystem['UserName']}\" style=\"margin:0 0 0 40px;\">Перейти к моим файлам</a> <br /><br /> {MAIN}", $template);
}

if (!isset($query['sort']))
$sort='lifo'; else
$sort=$query['sort'];


if (isset($query['page']))
$page=@intval($query['page'])*25; else 
$page=0;



reset($query);
$k=key($query);

if   ( !($query['show_files']=='comments')){
links_page();}

switch($query[$k]){
	case 'doAdd':
		doAdd();
		break;
	case 'user':
		ShowUser();
		break;
	case 'update':
		doUpdate();
		break;
	case 'comments':
		ShowComments();
		break;
	default: 
		DefaultView();
		break;
}


function ShowComments(){
	global $query,$template,$AuthSystem;
	 
	
	
	
	
       $filename=$query['comments'];
       if (isset($query['comments']) && FileExists($filename)){
       	     if (!FileComments($filename)){
       	     	$template=str_ireplace('{main}', '<h1 style="text-align:center;color:red;">Комментарии для этого файла запрещены!</h1>', $template);
       	     }   else {
       	     	  
       	     	if ($query['update']=='doAdd') {
                      $s=GetFileCommets($filename);
                      $com=unserialize($s);
                      if (@NameCorrect($_POST['ComUser']) && !($AuthSystem['IsRegister'])){
                        if (strlen($_POST['Comments'])>0){
                       $st=htmlspecialchars($_POST['Comments']);      
                        if (is_array($com))                   	
                       array_unshift($com, array(1,$_POST['ComUser'],Data(),$st,false))   ; 
                        else 
                        $com[0]=array(1,$_POST['ComUser'],Data(),$st,false);
                       $com=serialize($com);
                       SetFileCommets($filename, $com) ;
                        }

                                            
                      } else {
                      	if (strlen($_POST['Comments'])>0){
                        $st=htmlspecialchars($_POST['Comments']);
                          if (is_array($com))   
                        array_unshift($com, array(1,$AuthSystem['UserName'],Data(),$st,true)); 
                         else 
                        $com[0]=array(1,$AuthSystem['UserName'],Data(),$st,true);
                        $com=serialize($com); 
                        SetFileCommets($filename, $com) ;
                        
                      } 
                      
                     
                      
                   }
                   
                   header("Location: http://{$_SERVER['SERVER_NAME']}/index.php?show_files=comments&comments=$filename&__LINE__");
                      exit();
       	     	}
       	     	
       	     	
       	     	if ($query['update']=='insert' && NameCorrect($_POST['ComUser']) && (isset($_POST['ComUser']) || ($AuthSystem['IsRegister'])) && !isset($query['z']) ) {
       	     		     	     		
       	     		$com=GetFileCommets($filename);
       	     		$com=unserialize($com);
       	     		$result=count($com);
       	     		$found=false;

       	     		    for ($i=0;$i<$result;$i++){
       	     		    	list($level,$username,$date,$comment,$user_register)=$com[$i];
       	     		    	$new_com[]=$com[$i];
       	     		    	
       	     		    	 if ($date==$query['date'] && $username==$query['username']){
       	     		    	 	$found=true;
						       	          if (!($AuthSystem['IsRegister'])){
						                        if (strlen($_POST['Comments'])>0){
						                       $st=htmlspecialchars($_POST['Comments']);    	     		    	 	
                                                   $new_com[]=array(intval($level)+1,$_POST['ComUser'],Data(),$st,false);}
     
						                      }
						       	     		 	
			       	     		     else {
                           
			       	   if (strlen($_POST['Comments'])>0){
                       $st=htmlspecialchars($_POST['Comments']);   
                        $new_com[]= array(intval($level)+1,$AuthSystem['UserName'],Data(),$st,true); 
   
			       	   
			       	   }
			       	     	  } 

       	     		    	 }
       	     		    }
       	           if ($found){
       	           	$new_com=serialize($new_com);
       	           	SetFileCommets($filename, $new_com) ;
       	           }   
       	          
 	     		    header("Location: http://{$_SERVER['SERVER_NAME']}/index.php?show_files=comments&comments=$filename&__LINE__");
                      exit();
       	     		
       	     	}
       	     	
       	     	
       	     
       	     	$s=GetFileCommets($filename);
                    if (strlen($s)>0){
                    	$com=unserialize($s);
                    	$result=count($com);
                    	$str='';
                  
                    	    for ($i=0;$i<$result;$i++)  {
                    	    	
                    	    	list($level,$username,$date,$comment,$user_register)=$com[$i];
                          
                    	    	
                    	    	
                        if (!isset($query['username'])&& !isset($query['update']) && !isset($query['date'])){
                    	    $str.='<p style="border-bottom: 1px solid black; width: 500px; position: relative; margin-left: '.(intval($level)*10).'px;">
							      <span style="color: gray;position:relative; margin-right:10px;">'.$date.'</span>';
                    	    
                    	    
                    	    $str.=$user_register ? '<a href="index.php?show_files=user&user_name='.$username.'" style="display: inline; padding-right: 20px;">'.$username.'</h1>' : $username ;
                    	    
                    	    $str.='<a href="index.php?show_files=comments&comments='.$filename.'&update=insert&username='.$username.'&date='.$date.'&z=1" style="display: inline;">[комментировать]</a>
							      <h3 style="padding: 10px 0 10px 40px;">'.$comment.'</h3>
							      </p>';	
                    	    
                    	    	
                    	     }else 
                    	    
                    	    {
                    	    	if (($query['username']==$username) && ($query['date'])==$date){
                    	   $str.='<p style="border-bottom: 1px solid black; width: 500px; position: relative; margin-left: '.(10).'px;">
							      <span style="color: gray;position:relative; margin-right:10px;">'.$date.'</span>';
                    	    
                    	    
                    	    $str.=$user_register ? '<a href="index.php?show_files=user&user_name='.$username.'" style="display: inline; padding-right: 20px;">'.$username.'</h1>' : $username ;
                    	    
                    	    $str.='<a href="index.php?show_files=comments&comments='.$filename.'&update=insert&username='.$username.'&date='.$date.'&z=1" style="display: inline;">[комментировать]</a>
							      <h3 style="padding: 10px 0 10px 40px;">'.$comment.'</h3>
							      </p>';
                    	    	}
                    	    }
                    	    
                    	    }
                    	    
                    	 $template=str_ireplace('{main}',$str.'{main}', $template);
                    	    
                    } else 
                    $template=str_ireplace('{main}', '<h1 style="text-align:center;color:red;">Станьте первым кто оставит комментарий!</h1>{main}', $template);
                  if (isset($query['username']) && isset($query['date']))
                  $str='<form action="index.php?show_files=comments&comments='.$filename.'&update=insert&username='.$query['username'].'&date='.$query['date'].'" method="post" style="margin-left: 20px;">';
                  else
       	          $str='<form action="index.php?show_files=comments&comments='.$filename.'&update=doAdd" method="post" style="margin-left: 20px;">';

       	          $str.=$AuthSystem['IsRegister']?'':'Имя:<br  />
					      <input type="text" name="ComUser" />Имя может содержать до 20 символов латинских букв и цифр.
					      <br  />';
       	          
				  $str.='Комментарий:<br  />
					      <textarea name="Comments" cols="80" rows="6" ></textarea>
					      <br  />
					      <input type="submit" value="Отправить" />
					      </form>';
       	          $template=str_ireplace('{main}', $str, $template);
       	     
       	     
       	     }       
              
       	     	
     	
       	     
       	     
       	
       } else
       $template=str_ireplace('{main}', '<h1 style="text-align:center;color:red;">Такого файла не существует!</h1>', $template);
}

function Links_Page(){
global $AuthSystem,$query,$template;
  

$num=FileNum();
  if ($num!=0){
	if (($query['show_files']=='user')&&(User_Exists($query['user_name'],''))){
			$num=FileNum($query['user_name']);

			$col_page=$num/25;
			$col_page=ceil($col_page);
			  	$st='<div class="links"><a href="index.php?show_files=user&user_name='.$query['user_name'].'&page=0">0-25</a>';
			  	for ($i=1;$i<$col_page;$i++){
			  		$st.='<a href="index.php?show_files=user&user_name='.$query['user_name'].'&page='.$i.'">'.($i*25).'-'.($i*25+25).'</a>';
			  	}
			  	$st.='</div>{MAIN}';
			 $template=str_ireplace('{MAIN}', $st, $template);
	} else 
	{
			
  
			$col_page=$num/25;
			$col_page=ceil($col_page);
			  	$st='<div class="links"><a href="index.php?show_files=all&page=0">0-25</a>';
			  	for ($i=1;$i<$col_page;$i++){
			  		$st.='<a href="index.php?show_files=all&page='.$i.'">'.($i*25).'-'.($i*25+25).'</a>';
			  	}
			  	$st.='</div>{MAIN}';
			 $template=str_ireplace('{MAIN}', $st, $template);}
}
}


function ShowUser(){
	global $query,$template,$AuthSystem,$sort,$page;
	$user_name=$query['user_name'];

	$exists=User_Exists($user_name, '');
	
	if (!$exists)
	$template=str_ireplace('{MAIN}', '<p style="text-align:center;color:red;">Пользователь не зарегистрирован в системе</p>{MAIN}', $template);
	
	if ($exists && ($user_name==$AuthSystem['UserName'])){
		GetInformation($files_array,$page,$sort,$user_name);
		 $st='<form action="index.php?show_files=update&page='.$page.'&sort='.$sort.'" method="post"><table width="80%">
			    <tr>
			    <th width="10%">ID:</th>
			    <th width="20%">Имя файла:</th>
			    <th width="10%">Дата:</th>
			    <th width="20%">Ссылка:</th>
			    <th width="10%">Удалить:</th>
			    <th width="10%">Возможность комментирования:</th>
			    </tr>';
		 
		for ($i=0; $i<count($files_array);$i++){
			  	list($id,,$filename,$date,$com_pos)=$files_array["$i"];
			  	
			  	if ($com_pos==1)
			  	$s1='<input type="hidden" value="0" name="com_'.$id.'"/><input type="checkbox" checked="checked" name="com_'.$id.'"/>';		  	
			     else 
			  	$s1='<input type="hidden" value="0" name="com_'.$id.'"/><input type="checkbox"  name="com_'.$id.'"/>';
			  	
			  	   $st.='<tr>
			    <td>'.$id.'</td>
			    <td>'.$filename.'<br /><a style="color:Highlight" href="index.php?show_files=comments&comments='.$filename.'">Коментарии</a></td>
			    <td>'.$date.'</td>
			    <td><a href="http://'.$_SERVER['SERVER_NAME'].'/files/'.$id.'_'.$filename.'" > http://'.$_SERVER['SERVER_NAME'].'/files/'.$id.'_'.$filename.'</a> </td>
			    <td><input type="checkbox" name="del_'.$id.'"/></td>
			    <td>'.$s1.'</td>
			    
			    </tr>';
			  	 
			  }
		     $st.='</table>
    <div style="position: relative; margin: 20px auto 0 auto; width: 100px; ">
    <input type="submit" value="Применить" /></div>
    </form>';
	if ($AuthSystem['IsRegister']) $st.='{main}';
       $template=str_ireplace('{MAIN}', $st, $template);
	}
if ($exists && ($user_name!=$AuthSystem['UserName'])){
	
    GetInformation($files_array,$page,$sort,$user_name);

  $st='<table width="80%"><tr>
    <th width="16%">ID:</th>
    <th width="16%">Имя пользователя:</th>
    <th width="16%">Имя файла:</th>
    <th width="16%">Дата:</th>
    <th width="16%">Ссылка:</th>
    </tr>';
    
  for ($i=0; $i<count($files_array);$i++){
  	list($id,$user_name,$filename,$date)=$files_array["$i"];
  	   $st.='<tr>
    <td>'.$id.'</td>
    <td>'.$user_name.'</td>
    <td>'.$filename.'</td>
    <td>'.$date.'</td>
    <td><a href="http://'.$_SERVER['SERVER_NAME'].'/files/'.$id.'_'.$filename.'" > http://'.$_SERVER['SERVER_NAME'].'/files/'.$id.'_'.$filename.'</a> </td>
    </tr>';
  }
 $st.='</table><br /><br />';
 if ($AuthSystem['IsRegister']) $st.='{main}';
$template=str_ireplace('{MAIN}', $st, $template);
	
}
	
	
	
}

function doAdd(){
	global $AuthSystem;
       $File=&$_FILES['File'];
            switch($File['error']){
            	case UPLOAD_ERR_OK:
            		$Message='Файл успешно закачан';
            		/*Добавляем данные в базу данных*/
            		
            		AddFile($File);
            		break;
            	case  UPLOAD_ERR_INI_SIZE:
            		$Message='Файл слишком велик';
            		break;    
            	case  UPLOAD_ERR_PARTIAL:
            		$Message='Файл закачался частично, попробуйте закачать еще раз';
            		break; 
            	case  UPLOAD_ERR_NO_FILE:
            		$Message='Не выбран файл для закачки';
            		break;   
            	default: $Message='Ошибка закачки';          		            		
            }
        $template=str_ireplace('{MAIN}','<p style="text-align:center">'. $Message. '</p>{MAIN}',$template);
        header("Location: http://{$_SERVER['SERVER_NAME']}/index.php?show_files=user&user_name={$AuthSystem['UserName']}");
        exit();
}       
	

function DefaultView(){
global $AuthSystem,$page,$sort;

    GetInformation($files_array,$page,$sort);
    
  $st='<table width="80%"><tr>
    <th width="16%">ID:</th>
    <th width="16%">Имя пользователя:</th>
    <th width="16%">Имя файла:</th>
    <th width="16%">Дата:</th>
    <th width="16%">Ссылка:</th>
    </tr>';
    
  for ($i=0; $i<count($files_array);$i++){
  	list($id,$user_name,$filename,$date)=$files_array["$i"];
  	   $st.='<tr>
    <td>'.$id.'</td>
    <td><a href="index.php?show_files=user&user_name='.$user_name.'">'.$user_name.'</a></td>
    <td>'.$filename.'<br /><a style="color:Highlight" href="index.php?show_files=comments&comments='.$filename.'">Коментарии</a></td>
    <td>'.$date.'</td>
    <td><a href="http://'.$_SERVER['SERVER_NAME'].'/files/'.$id.'_'.$filename.'" > http://'.$_SERVER['SERVER_NAME'].'/files/'.$id.'_'.$filename.'</a> </td>
    </tr>';
  }
 $st.='</table><br /><br />';
global $template;

if ($AuthSystem['IsRegister']) $st.='{main}';
$template=str_ireplace('{MAIN}', $st, $template);
    
}

function doUpdate(){
	global $query,$template,$AuthSystem,$sort,$page;
	
	 foreach ($_POST as $k=>$v){

	 	$id=substr($k,4);
	 	$func=substr($k,0,3);
	 	   switch($func){
	 	   	case 'del':
	 	   		$sql = 'SELECT filename FROM files WHERE user_name="'.$AuthSystem["UserName"].'" and id='.$id;
	 	   			 	   		
	 	   		$result=mysql_query($sql);
	 	   		if (@mysql_num_rows($result) ){
	 	   			mysql_query('DELETE FROM files WHERE user_name="'.$AuthSystem["UserName"].'"and id='.$id);
	 	   			$filename=mysql_fetch_row($result);
	 	   			@unlink('./files/'.$id.'_'.$filename['0']);
	 	   		}	 	   		   		
	 	   	break;
	 	   	case 'com':
                if ($v=='on') $v=1; else $v=0;
	 	   		$sw= 'UPDATE files SET com_pos='.$v.' WHERE user_name="'.$AuthSystem["UserName"].'"and id='.$id;
	 	   		mysql_query($sw) or die(mysql_error());
	 	   	break;
	 	   }
	 }
header('Location: http://'.$_SERVER['SERVER_NAME'].'/index.php?show_files=user&user_name='.$AuthSystem["UserName"].'&sort='.$sort.'&page='.$page);
    exit;
}


	if ($AuthSystem['IsRegister'] && !($query['show_files']=='comments')){
$template=str_ireplace('{MAIN}', '<form action="index.php?show_files=doAdd" method="post" enctype="multipart/form-data" style="margin: 0 0 0 40px;">
     <p >Выберите файл для отправки:</p>
     <input type="file" name="File" />
     <input type="submit" name="doLoad" value="Закачать" style="margin: 0 0 40px 0;"/>
     </form>', $template);
}	


?>