<?php
  require_once('class.php');
  session_start();
?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="jquery.js"></script>
    <script src="md5.js"></script>
    <style type="text/css">
      body {
        background-color:thistle;
        background-image:url('images/watermark.gif');
      }
      a {
        color:black;
      }
      a:hover {
        color:red;
      }
    </style>
    <title>Seminar在线——首页</title>
  </head>
  <body>
    <div id="header">
      <h1><em>Seminar在线</em></h1>
    </div>
    <hr>
  <?php
    if (array_key_exists('action',$_REQUEST)) {
      if ($_REQUEST['action']==='logout') {
        //退出登录
        $_SESSION=array();
        session_destroy();
          echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    alert("用户成功退出！");
    window.location.href="index.php";
  });
</script>          
EOT;
      }
    }
    if (array_key_exists('id',$_POST)) {
      $student=new Student();
      if ($student->StudentExists($_POST['id'])) {
        $student=new Student($_POST['id']);
        if ($student->password===$_POST['password']) {
          $_SESSION['login']=true;
          $_SESSION['role']='student';
          $_SESSION['id']=$_POST['id'];
        }else {
          echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    alert("学号与密码不匹配！");
  });
</script>
EOT;
        }
      }else {
        echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    if(confirm("用户
EOT;
        echo $_POST['id'];
        echo <<<EOT
不存在，是否需要注册新用户？")) {
      window.location.href="register.php?id=
EOT;
        echo $_POST['id'];
        echo <<<EOT
";
    }
  });
</script>
EOT;
      }
    }
    $login=false;
    if (array_key_exists('login',$_SESSION)) {
      $login=$_SESSION['login'];
    }
    if (!$login) {
      //显示登录界面
      echo <<<EOT
<form action="index.php" method="post">
  <table><tbody>
    <tr><th colspan="2" align=left>已注册学生请输入学号以及密码登录
    <tr><td align=right>学号：<td align=left><input type="text" name="id">
    <tr><td align=right>密码：<td align=left><input type="password" name="password">
    <tr><td colspan="2" align=center><input type="submit" value="登录">
    <tr><td colspan="2" align=left><a href="register.php">注册</a>成为新用户
    <tr><td colspan="2" align=left><a href="admin/index.php">进入教务界面</a>
  </tbody></table>
</form>
<script type="text/javascript">
  $("form").submit(function() {
    $("input[type='password']").val(hex_md5($("input[type='password']").val()));
  });
</script>
EOT;
    }else {
      echo <<<EOT
<script type="text/javascript">
  alert("成功登录，进入个人页面。");
  window.location.href="student.php";
</script>
EOT;
    }
  ?>
  <hr>
  <p><b>重要提示：</b>由于服务器放置在理科楼，无专业机房提供的稳定网络、良好散热等条件，有时候可能造成同学们访问困难，对此深表歉意。如果发现无法访问，请<a href="mailto:huozhuoxi03@mails.tsinghua.edu.cn">联系管理员</a>以便我们在第一时间排查问题。
  </p>
  </body>
</html>