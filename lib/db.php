<?php


 $id_db=mysql_connect('localhost','root','');
 mysql_query('CREATE DATABASE Onliner_Test');
 mysql_select_db('Onliner_Test');
 mysql_query("SET NAMES 'cp1251'");
 
 
 /*Создаем таблицу пользователей*/
 		 mysql_query('CREATE TABLE IF NOT EXISTS users(User_Name VARCHAR(20),
 		 INDEX i_User_Name(User_Name(10)),
 		 Pass TINYTEXT, 
 		 Email TINYTEXT)')or die(mysql_error());
  
 /*Создаем таблицу файлов*/		 
 		 mysql_query('CREATE TABLE IF NOT EXISTS files(id INT AUTO_INCREMENT PRIMARY KEY,
 		 User_Name VARCHAR(20),
 		 INDEX i_User_Name(User_Name(10)),
 		 FileName TINYTEXT,
 		 Date TINYTEXT,
 		 Comments TEXT,
 		 Com_pos BOOL,
 		 User_agent TINYTEXT,
 		 Ip TINYTEXT)') or die(mysql_error());







?>