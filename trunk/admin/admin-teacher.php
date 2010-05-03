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
    </style>
    <title>Seminar在线——查看导师详情</title>
  </head>
  <body>
<?php
  if($_SESSION['role']!='admin') {
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
?>
<?php
$dblink=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD,true);
mysql_query('set names "utf8"',$dblink);
mysql_select_db('seminar',$dblink);
$result=mysql_query('select * from `teacher` where `id`='.$_REQUEST['id'].'',$dblink);
$num=mysql_num_rows($result);
if($num<1) {
  die('要查看的导师不存在！');
}
mysql_close($dblink);
$teacher=new Teacher($_REQUEST['id']);
?>
<table border="1"><tbody><tr>
  <th>姓名<th>院系（单位）<th>职务<th>职称<th>研究方向<th>办公电话<th>手机号码<th>电子邮箱
<?php
  echo '<tr>';
  echo '<td>'.$teacher->name.'<td>'.$teacher->organization.'<td>'.$teacher->duty;
  echo '<td>'.$teacher->title.'<td>'.$teacher->research.'<td>'.$teacher->telephone.'<td>'.$teacher->cellphone;
  echo '<td><a href="mailto:'.$teacher->mail.'">'.$teacher->mail.'</a>';
?>
</tbody></table>
  </body>
</html>
