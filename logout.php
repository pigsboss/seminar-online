<?php
  session_start();
  $_SESSION=array();
  session_destroy();
?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="jquery.js"></script>
    <script src="md5.js"></script>
    <title>Seminar在线——退出</title>
  </head>
  <body>
    <a href="index.php">返回首页</a>
    <script type="text/javascript">
      $(document).ready(function() {
        window.location.href="index.php";
      });
    </script>
  </body>
</html>