<?php
  require_once('../class.php');
  session_start();
?>
<?php
  if($_SESSION['role']!='admin') {
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
      body {
        background-color:thistle;
      }
      a {
        color:black;
      }
      a:hover {
        color:red;
      }
      dd {
        font-size: 0.75em;
      }
      dt {
        font-size: 0.9em;
        font-weight: bold;
      }
    </style>
    <title>Seminar在线——教务界面</title>
    <frameset cols="20%,80%">
      <frame name="control" src="admin-control.php">
      <frame name="content" src="admin-content.php" scrolling="auto">
    </frameset>
  </head>
  <body>
  <hr>
<?php
  var_dump($_SESSION);
  if($_SESSION['role']!='admin') {
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
?>
  </body>
</html>
