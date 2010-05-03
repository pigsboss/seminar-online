<?php
  require_once('../class.php');
  session_start();
?>
<?php
  if($_SESSION['role']!='admin') {
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
?>
<?php
  $report=new Report($_REQUEST['id']);
  $path_parts=pathinfo($report->path);
  $ext=$path_parts['extension'];
  $downloadname=$report->student[0].'_'.$report->seminar[0].'.'.$ext;
  header('Content-type: '.mime_content_type($report->path));
  header('Content-Disposition: attachment; filename="'.$downloadname.'"');
  header('Content-Length: '.filesize($report->path));
  readfile($report->path);
?>