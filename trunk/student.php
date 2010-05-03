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
    <title>Seminar在线——个人页面</title>
  </head>
  <body>
    <div id="header">
      <h1><em>Seminar在线</em></h1>
    </div>
    <hr>
<?php
  if (!array_key_exists('login',$_SESSION)) {
    $_SESSION['login']=false;
  }
  if (!$_SESSION['login']) {
    echo <<<EOT
<script type="text/javascript">
  alert("您没有登录，因此无法访问页面。");
  window.location.href="index.php";
</script>
EOT;
  }
  if ($_SESSION['role']!=='student') {
    echo <<<EOT
<script type="text/javascript">
  alert("您没有登录，因此无法访问页面。");
  window.location.href="index.php";
</script>
EOT;
  }
?>
<table width="100%"><tbody><tr>
<td align="left"><small>
<a href="student.php">个人页面</a>|
<a href="seminar.php">我的课题</a>
<td align="right"><small>
<a href="student.php?action=edit-info-prompt">修改个人信息</a>|
<a href="student.php?action=edit-password-prompt">修改密码</a>|
<a href="student.php?action=reset-password-prompt">重置密码</a>|
<a href="index.php?action=logout">退出</a>
</small>
</tbody></table>
<hr>
<div id="edit-info" align="left">
<form id="form-info" action="student.php?action=edit-info" method="post">
  <table><tbody>
    <tr><th colspan="2">修改个人信息
    <tr><td align="right">姓名：<td align="left"><input type="text" name="name" value="<?php $student=new Student($_SESSION['id']);echo $student->name;?>">
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
    <tr><td align="right">电子邮箱：<td align="left"><input type="text" name="mail" value="<?php echo $student->mail;?>">
    <tr><td align="right">手机号码：<td align="left"><input type="text" name="cellphone" value="<?php echo $student->cellphone;?>">
    <tr><td align="right">电话号码：<td align="left"><input type="text" name="telephone" value="<?php echo $student->telephone;?>">
    <tr><td colspan="2" align="right"><input id="submit-info" type="button" value="提交"><input type="reset" value="重置">
  </tbody></table>
</form>
<script type="text/javascript">
  $(document).ready(function() {
    $("div[id='edit-info']").hide();
  });
  $("input[id='submit-info']").click(function() {
    var name_valid=false;
    mail_valid=check_mail($("input[name='mail']").val());
    if ($("input[name='name']").val().length<1) {
      name_valid=false;
    }else {
      name_valid=true;
    }
		var telephone=$("input[name='telephone']").val();
		var cellphone=$("input[name='cellphone']").val();
		var telephone_valid=false;
		var cellphone_valid=false;
		if (cellphone.length==0 || check_cellphone(cellphone)) {
			cellphone_valid=true;
		}
		if (telephone.length>0 && check_telephone(telephone)) {
			telephone_valid=true;
		}
    if (name_valid && mail_valid && cellphone_valid && telephone_valid) {
      if(confirm("是否修改个人信息？")) {
        $("form[id='form-info']").submit();
      }else {
        window.location.href="student.php";
      }
    }else if(!name_valid) {
      alert("姓名尚未填写。");
    }else if(!mail_valid) {
      alert("电子邮箱地址不合法。");
    }else if(!cellphone_valid) {
			alert("手机号码无效。");
		}else if(!telephone_valid) {
			alert("电话号码无效。");
		}
  });
</script>
</div>                    
<div id="info" align="left">
<?php
echo '<p>'.$student->name.'，'.$student->class;
echo '<p>'.$student->mail;
if(strlen($student->cellphone)>0) {
	echo '<p>'.$student->cellphone;
}
if(strlen($student->telephone)>0) {
	echo '<p>'.$student->telephone;
}else {
	echo '<p>请修改个人信息，添加电话号码，以便及时与教务老师取得联系。';
}
?>
</div>
<div id="edit-password" align="left">
<form id="form-password" action="student.php?action=edit-password" method="post">
  <table><tbody>
    <tr><th colspan="2">修改密码
    <tr><td align="right">旧密码：<td align="left"><input type="password" name="old-password"><input type="hidden" name="password" value="<?php
    $student=new Student($_SESSION['id']);
    echo $student->password;
    ?>">
    <tr><td align="right">新密码：<td align="left"><input type="password" name="new-password">
    <tr><td align="right">再输入一遍新密码：<td align="left"><input type="password" name="new-password-confirm">
    <tr><td colspan="2" align="right"><input id="submit-password" type="button" value="提交"><input type="reset" value="重置">
  </tbody></table>
</form>
<script type="text/javascript">
  $(document).ready(function() {
    $("div[id='edit-password']").hide();
  });
  $("input[id='submit-password']").click(function() {
    var password=$("input[name='password']").val();
    var old_pass=hex_md5($("input[name='old-password']").val());
    $("input[name='old-password']").val(old_pass);
    var new_pass=hex_md5($("input[name='new-password']").val());
    $("input[name='new-password']").val(new_pass);
    var new_pass_confirm=hex_md5($("input[name='new-password-confirm']").val());
    $("input[name='new-password-confirm']").val(new_pass_confirm);
    if(password!=old_pass) {
      alert("输入了错误的旧密码。");
    }else if(new_pass!=new_pass_confirm) {
      alert("两次输入的新密码不相符。");
    }else {
      if(confirm("确定要修改密码？")) {
        $("form[id='form-password']").submit();
      }else {
        window.location.href="student.php";
      }
    }
  });
</script>                                                        
</div>
<?php
if(array_key_exists('action',$_REQUEST)) {
  echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    $("div[id='info']").hide();
  });
</script>
EOT;
  if($_REQUEST['action']==='edit-info') {
    $student->name=htmlspecialchars($_POST['name']);
    $student->mail=htmlspecialchars($_POST['mail']);
    $student->cellphone=htmlspecialchars($_POST['cellphone']);
    $student->telephone=htmlspecialchars($_POST['telephone']);
    $student->class=htmlspecialchars($_POST['class']);
    echo <<<EOT
<script type="text/javascript">
window.location.href="student.php";
alert("成功修改个人信息！");
</script>
EOT;
  }
  if($_REQUEST['action']==='edit-info-prompt') {
    echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    $("div[id='edit-info']").show();
    $("div[id='edit-password']").hide();
  });
</script>
EOT;
  }
  if($_REQUEST['action']==='edit-password') {
    $student=new Student($_SESSION['id']);
    if($student->password!==$_POST['old-password']) {
      echo <<<EOT
<script type="text/javascript">
  alert("输入了错误的旧密码。");
  window.location.href="student.php?action=edit-password-prompt";
</script>
EOT;
    }else if($_POST['new-password-confirm']!==$_POST['new-password']) {
      echo <<<EOT
<script type="text/javascript">
  alert("两次输入的新密码不一致。");
  window.location.href="student.php?action=edit-password-prompt";
</script>
EOT;
    }else {
      $student->password=$_POST['new-password'];
      echo <<<EOT
<script type="text/javascript">
  window.location.href="student.php";
  alert("密码修改成功，新密码生效。");
</script>
EOT;
    }
    echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    $("div[id='edit-password']").show();
  });
</script>
EOT;
  }
  if($_REQUEST['action']==='edit-password-prompt') {
    echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    $("div[id='edit-password']").show();
  });
</script>
EOT;
  }
  if($_REQUEST['action']==='reset-password-prompt') {
    echo <<<EOT
<script type="text/javascript">
  $("div[id='edit-info']").hide();
  $("div[id='info']").hide();
  $("div[id='edit-password']").hide();
  if(confirm("是否重置密码？")) {
    window.location.href="student.php?action=reset-password";
  }else {
    $("div[id='info']").show();
    $("div[id='edit-password']").hide();
    window.location.href="student.php";
  }
</script>
EOT;
  }
  if($_REQUEST['action']==='reset-password') {
    $password=randstr(8);
    $student=new Student($_SESSION['id']);
    $student->password=md5($password);
    echo '新密码： <label id="new-password">'.$password.'</label>';
    echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    alert("请尽快修改密码。");
  });
</script>
EOT;
  }
}else {
  echo <<<EOT
<script type="text/javascript">
  $(document).ready(function() {
    $("div[id='info']").show();
    $("div[id='edit-password']").hide();
  });
</script>
EOT;
}
?>
</body>
</html>