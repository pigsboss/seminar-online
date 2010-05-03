<?php
  require_once('class.php');
  session_start();
  require_once('smtp.php');
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
      table {
        border-collapse: collapse;
      }
      table.print {
        width: 600px;
      }
      td.sign {
        line-height: 3em;
        text-align: right;
        padding-top: 20px;
      }
      td.textarea {
        text-align: left;
      }
      #seminar-title {
        width: 120px;
      }
      dt {
        font-weight: bold;
      }
      dd {
        font-style: italic;
        color: red;
      }
      #report {
        font-size: 11pt;
      }
    </style>
    <script src="jquery.js"></script>
    <title>Seminar在线——我的课题</title>
  </head>
  <body>
<?php
  if(!array_key_exists('login',$_SESSION)) {
    $_SESSION['login']=false;
  }
  if(!$_SESSION['login']) {
    echo <<<EOT
<script type="text/javascript">
  alert("您没有登录，因此无法访问页面。");
  window.location.href="index.php";
</script>
EOT;
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
  if(array_key_exists('id',$_REQUEST)) {
    $seminar=new Seminar($_REQUEST['id']);
    if($seminar->id===null) {
      echo <<<EOT
<script type="text/javascript">
  window.location.href="seminar.php";
  alert("请求的研究课题不存在。");
</script>
EOT;
    }else {
      if(in_array($_SESSION['id'],$seminar->student)) {
        echo '<script type="text/javascript">document.title="Seminar在线——'.$seminar->title.'"</script>';
      }else {
        echo <<<EOT
<script type="text/javascript">
  window.location.href="seminar.php";
  alert("无权访问该研究项目。");
</script>
EOT;
      }
    }
  }else {
    echo <<<EOT
<script type="text/javascript">
  alert("未包含可识别的请求。");
  window.location.href="seminar.php";
</script>
EOT;
  }
  if(is_null($seminar)) {
    $seminar=new Seminar($_REQUEST['id']);
  }
  if(is_null($student)) {
    $student=new Student($_SESSION['id']);
  }
  if(array_key_exists('password', $_POST)) {
    if($_SESSION['tmppasswd']===$_POST['password']) {
      echo <<<EOT
<script type="text/javascript">
alert("口令正确！");
</script>
EOT;
      $seminarIds=$student->seminar;
      $student->seminar=array();
      foreach($seminarIds as $seminarId) {
        if($seminarId!=$_REQUEST['id']) {
          $student->seminar[]=$seminarId;
        }
      }
      echo <<<EOT
<script type="text/javascript">
alert("已完成删除操作。");
window.location.href="seminar.php";
</script>
EOT;
    }else {
    echo <<<EOT
<script type="text/javascript">
alert("口令错误！");
window.location.href="seminar.php";
</script>
EOT;
    }
  }else {
    $_SESSION['tmppasswd']=randstr(8);
  }
?>
    <script type="text/javascript">
      if(confirm("删除操作不可撤消，是否继续执行该操作？")) {
        alert("请记录口令（可复制，稍后粘贴）：<?php echo $_SESSION['tmppasswd'];?>");
      }else {
        alert("取消删除操作。");
        window.location.href="seminar.php";
      }
    </script>
    <form action="seminar-single-delete.php?id=<?php echo $_REQUEST['id'];?>" method="post">
      <table><tbody>
        <tr><td>口令：<td><input type="text" name="password">
      </tbody></table>
      <input type="submit"><input type="reset">
    </form>
  </body>
</html>