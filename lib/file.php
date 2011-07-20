<?php

include_once 'session.php';
include_once 'db.php';

$AuthSystem=&$_SESSION['AuthSystem'];

function Data() {
	return date('d.m.y H:i');
}

function FileExists($filename){
	$filename=mysql_real_escape_string($filename);
	$sql='SELECT FileName FROM files WHERE filename="'.$filename.'"';
	$result=mysql_query($sql) or die(mysql_error());
	 if (mysql_num_rows($result)>0)
	 return true;
	 else return false;
}


function FileComments($filename){
	$sql='SELECT com_pos FROM files WHERE filename="'.$filename.'"';
	$result=mysql_query($sql) or die(mysql_error());
	$c=mysql_fetch_row($result);
	return $c[0];
}

function GetFileCommets($filename){
	$sql='SELECT comments FROM files WHERE filename="'.$filename.'"';
	$result=mysql_query($sql) or die (mysql_error());
	$c=mysql_fetch_row($result);
	return $c[0];
}

function SetFileCommets($filename,$comments){
	$comments=mysql_real_escape_string($comments);
	$filename=mysql_real_escape_string($filename);
	$sql='UPDATE files SET comments="'.$comments.'" WHERE filename="'.$filename.'"';
    mysql_query($sql) or die(mysql_error());
}

function AddFile($File){
    $tmp_name=$File['tmp_name'];
    $name=$File['name'];	
    global $AuthSystem;
      if  (move_uploaded_file($tmp_name,'./files/'.$name)) {    	
             
      	    $sql='INSERT INTO files(User_Name,FileName,Date,Comments,Com_pos,User_agent,Ip) 
      	     Values('
      	     .'"'.$AuthSystem['UserName'].'"'.','
      	     .'"'.$name.'"'.','
      	     .'"'.Data().'"'
      	     .',"","false",'
      	     .'"'.$_SERVER['HTTP_USER_AGENT'].'"'.','
      	     .'"'.$_SERVER['REMOTE_ADDR'].'"'.')';
      	     mysql_query($sql) or die(mysql_error());
      	     $id=mysql_insert_id();
      	     @rename("./files/{$name}", "./files/{$id}_{$name}");
      }     
}

/*Получение информации о файлах*/
/*Сортировки lifo,filename,date*/

function FileNum($user="ALL"){
	if ($user=="ALL"){
		$result=mysql_query('SELECT COUNT(*) FROM files WHERE id!=1');
		} else 
		$result=mysql_query('SELECT COUNT(*) FROM files WHERE user_name='.$user);
		
if(@mysql_num_rows($result)>0){
			$c=mysql_fetch_row($result);
			return $c[0];
}
}


function GetInformation(&$files_array,$first=0,$sort="lifo",$user="all"){
	if ($user=="all"){
		switch ($sort){
			case 'lifo':
			    $result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE id!=0 ORDER BY id LIMIT '.$first.',25');
				break;
			case 'filename':
				$result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE id!=0 ORDER BY filename  LIMIT '.$first.',25');
			    break;
			case 'date':
				$result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE id!=0 ORDER BY date LIMIT '.$first.',25');
			default:
				$result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE id!=0 ORDER BY id LIMIT '.$first.',25');
				break;
		}
	}   else 
		switch ($sort){
			case 'lifo':
			    $result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE user_name="'.$user.'" LIMIT '.$first.',25');
				break;
			case 'filename':
				$result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE user_name="'.$user.'" ORDER BY filename  LIMIT '.$first.',25');
			    break;
			case 'date':
				$result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE user_name="'.$user.'" ORDER BY date  LIMIT '.$first.',25');
                break;
            default:
				$result=mysql_query('SELECT id,user_name,filename,date,com_pos  FROM files WHERE user_name="'.$user.'" ORDER BY id LIMIT '.$first.',25');
				break;
		}
	
	  for ($i=0; $i<@mysql_num_rows($result);$i++){
	  	$files_array["$i"]=mysql_fetch_row($result);	  	
	  }
}


?>