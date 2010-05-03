<?php
  require_once('../class.php');
  session_start();
?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="../jquery.js"></script>
    <script src="../md5.js"></script>
    <?php
      if(array_key_exists('name',$_POST)) {
        if($_POST['name']===ADMIN_NAME && $_POST['password']===ADMIN_PASSWORD) {
          $_SESSION=array();
          $_SESSION['role']='admin';
        }
      }
      if(array_key_exists('role',$_SESSION)) {
        if($_SESSION['role']==='admin') {
          echo <<<EOT
<script type="text/javascript">
$(document).ready(function() {
  window.location.href="admin.php";
});
</script>
EOT;
        }
      }
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $("form").submit(function() {
          $("input[name='name']").val(hex_md5($("input[name='display-name']").val()));
          $("input[name='password']").val(hex_md5($("input[name='display-password']").val()));
        });
      });
    </script>
    <style type="text/css">
      body {
        background-color:thistle;
      }
    </style>
    <title>Seminar在线——管理界面</title>
  </head>
  <body>
    <form action="index.php" method="post" id="submit">
      <input type="hidden" name="name">
      <input type="hidden" name="password">
      <table>
        <tbody>
          <tr><th colspan="2">输入用户名以及密码进入教务界面
          <tr><td>用户名：<td><input type="text" name="display-name">
          <tr><td>密码：<td><input type="password" name="display-password">
          <tr><td colspan="2"><input type="submit" value="提交"><input type="reset" value="重置">
        </tbody>
      </table>
    </form>
  </body>
</html>
