<?php
  require_once('class.php');
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
    <script src="check_mail.js"></script>
    <script src="check_phone.js"></script>
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
    <title>Seminar在线——新用户注册</title>
  </head>
  <body>
    <div id="header">
      <h1><em>Seminar在线</em></h1>
    </div>
    <hr>
  <form action="register.php" method="post">
    <table><tbody>
      <tr><th colspan="2" align="left">新用户注册
      <tr><td align="right">姓名：<td align="left"><input type="text" name="name">
      <tr><td align="right">学号：<td align="left"><input type="text" name="id">
      <tr><td align="right">班级：<td align="left"><select name="class">
          <optgroup label="基科物理">
            <option>基物51</option>
            <option>基物52</option>
            <option>基物61</option>
            <option>基物62</option>
            <option>基物71</option>
            <option>基物72</option>
          </optgroup>
          <optgroup label="基科应用">
            <option>基应51</option>
            <option>基应52</option>
            <option>基应61</option>
            <option>基应62</option>
            <option>基应71</option>
            <option>基应72</option>
          </optgroup>
          <optgroup label="其它班级">
            <option>其它班级</option>
          </optgroup>
        </select>
      <tr><td align="right">电子邮箱：<td align="left"><input type="text" name="mail">
      <tr><td align="right">电话号码：<td align="left"><input type="text" name="telephone">
      <tr><td align="right">手机号码：<td align="left"><input type="text" name="cellphone">
      <tr><td align="right">密码：<td align="left"><input type="password" name="password">
      <tr><td align="right">再输入一遍密码：<td align="left"><input type="password" name="password-confirm">
      <tr><td colspan="2" align="right"><input name="submit" type="button" value="提交"><input type="reset" value="重置">
    </tbody></table>
  </form>
  <script type="text/javascript">
    function check_form() {
      if ($("input[name='password']").val()!=$("input[name='password-confirm']").val()) {
        alert("两次输入的密码不一致。");
        return false;
      }
      var id=$("input[name='id']").val();
      if(id.length!=10) {
        alert("学号无效。");
        return false;
      }else {
				if(id.search(/\D/) != -1) {
					alert("学号无效。");
					return false;
				}
      }
      var name=$("input[name='name']").val();
      if(name.length<1) {
        alert("必须填写姓名。");
        return false;
      }
      var mail=$("input[name='mail']").val();
      if(!check_mail(mail)) {
        alert("电子邮箱地址不合法。");
        return false;
      }
      var cellphone=$("input[name='cellphone']").val();
			if(cellphone.length>0) {
				if(!check_cellphone(cellphone)) {
				alert("手机号码无效。");
				return false;
				}
			}
      var telephone=$("input[name='telephone']").val();
			if(telephone.length<1) {
				alert("必须填写电话号码。");
				return false;
			}else {
				if(!check_telephone(telephone)) {
				alert("电话号码无效。");
				return false;
				}
			}
      if((telephone.length)+(cellphone.length)<1) {
        alert("至少填写电话号码与手机号码其中之一。");
        return false;
      }
      return true;
    }
    $(document).ready(function() {
      $("input[name='submit']").click(function() {
        if(check_form()) {
          if(confirm("是否提交注册信息？")) {
            $("input[name='password']").val(hex_md5($("input[name='password']").val()));
            $("input[name='password-confirm']").val(hex_md5($("input[name='password-confirm']").val()));
            $("input[type='reset']").after("<input type='submit'>");
            $("input[type='submit']").click();
          }
        }
      });
    });
  </script>
  <?php
    if(array_key_exists('id',$_POST)) {
      $student=new Student();
      if($student->StudentExists($_POST['id'])) {
        echo <<<EOT
<script type="text/javascript">
  alert("学号为
EOT;
        echo $_POST['id'];
        echo <<<EOT
的用户已经存在。");
</script>
EOT;
      }else {
        $student->id=htmlspecialchars($_POST['id']);
        $student->name=htmlspecialchars($_POST['name']);
        $student->password=$_POST['password'];
        $student->mail=htmlspecialchars($_POST['mail']);
        $student->class=htmlspecialchars($_POST['class']);
				$student->telephone=htmlspecialchars($_POST['telephone']);
				$student->cellphone=htmlspecialchars($_POST['cellphone']);
        echo <<<EOT
<script type="text/javascript">
  if(confirm("注册成功，是否转入首页？")) {
    window.location.href="index.php";
  }
</script>
EOT;
      }
    }
  ?>
  </body>
</html>