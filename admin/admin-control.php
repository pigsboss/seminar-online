<?php
  require_once('../class.php');
  session_start();
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
    <title>Seminar在线——管理界面</title>
  </head>
  <body>
<?php
  if($_SESSION['role']!='admin') {
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
?>
    <h2><em>Seminar在线</em></h2>
    <dt>学生列表<br>
    <dd><a target="content" href="admin-list-student.php?filter=detail">查看所有学生详细信息</a><br>
    <dd><a target="content" href="admin-list-student.php">查看所有学生简明信息</a><br>
    <dd><a target="content" href="admin-inquire-student.php">学生查询</a><br>
    <dt>课题列表<br>
    <dd><a target="content" href="admin-list-seminar.php">查看所有课题</a><br>
    <dd><a target="content" href="admin-inquire-seminar.php">课题查询</a><br>
    <dt>其他功能<br>
    <dd><a href="mailto: huozhuoxi03@mails.tsinghua.edu.cn">联系管理员</a>
    <dd><a target="_top" href="../logout.php">退出系统</a><br>
  </body>
</html>
