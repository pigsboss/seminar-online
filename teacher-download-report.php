<?php
  require_once('class.php');
  session_start();
  $report=new Report($_REQUEST['id']);
  if(md5($report->path)===$_REQUEST['key']) {
    $path_parts=pathinfo($report->path);
    $ext=$path_parts['extension'];
    $downloadname=$report->student[0].'_'.$report->seminar[0].'.'.$ext;
    header('Content-type: '.mime_content_type($report->path));
    header('Content-Disposition: attachment; filename="'.$downloadname.'"');
    header('Content-Length: '.filesize($report->path));
    readfile($report->path);
  }else {
    echo <<<EOT
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Seminar在线——错误</title>
  </head>
  <body>
    密钥验证失败。
  </body>
</html>
EOT;
  }
?>