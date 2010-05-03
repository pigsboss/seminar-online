<?php
  require_once('class.php');
  session_start();
  if(array_key_exists('login',$_SESSION)) {
    if($_SESSION['login']) {
      if(array_key_exists('id',$_REQUEST)) {
        $student=new Student($_SESSION['id']);
        if(in_array($_REQUEST['id'],$student->report)) {
          $report=new Report($_REQUEST['id']);
          if(in_array($student->id,$report->student)) {
            $path_parts=pathinfo($report->path);
            $ext=$path_parts['extension'];
            $downloadname=$_SESSION['id'].'_'.$report->seminar[0].'.'.$ext;
            header('Content-type: '.mime_content_type($report->path));
            header('Content-Disposition: attachment; filename="'.$downloadname.'"');
            header('Content-Length: '.filesize($report->path));
            readfile($report->path);
          }
        }else {
          die('请求的对象不存在。');
        }
      }else {
        die('无效的请求。');
      }
    }else {
      die('没有登录。');
    }
  }else {
    die('初始化失败。');
  }
?>