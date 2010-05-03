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
  if(array_key_exists('termyear', $_POST)) {
    $seminar->term=$_POST['termyear'].$_POST['termseason'];
    $seminar->phase=$_POST['phase'];
    $seminar->Save();
    echo <<<EOT
<script type="text/javascript">
alert("修改成功！");
window.location.href="seminar.php";
</script>
EOT;
  }
?>
    <p>目前系统仅提供修改下列项目的功能。需要修改其他项目，请<a href="mailto:huozhuoxi03@mails.tsinghua.edu.cn">联系管理员</a>。</p>
    <form action="seminar-single-modify.php?id=<?php echo $_REQUEST['id'];?>" method="post">
      <table><tbody>
        <tr><td>学期：
        <td><select name="termyear">
          <option>2007-2008学年度</option>
          <option>2008-2009学年度</option>
          <option>2009-2010学年度</option>
          <option>2010-2011学年度</option>
        </select>
        <select name="termseason">
          <option>春季学期</option>
          <option>夏季学期</option>
          <option>秋季学期</option>
        </select>
        <tr><td>阶段：<td><select name="phase">
        <option>专题研究课（1）</option>
        <option>专题研究课（2）</option>
        <option>专题研究课（3）</option>
        </select>
      </tbody></table>
      <input type="submit" value="保存修改"><input type="reset">
    </form>
  </body>
</html>