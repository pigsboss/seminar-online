<?php
  require_once('config.php');
  $dblink=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
  $result=mysql_list_dbs($dblink);
  $num=mysql_num_rows($result);
  $rows=array();
  echo $num;
  for($i=0;$i<$num;$i++)
  {
      $row=mysql_fetch_row($result);
      array_push($rows,$row[0]);
  }
  if (in_array(DB_NAME,$rows))
  {
      echo DB_NAME . ' already exists.';
  }
  else
  {
      mysql_query('create database if not exists ' . DB_NAME,$dblink);
  }
  var_dump($rows);
  mysql_close($dblink);
?>
