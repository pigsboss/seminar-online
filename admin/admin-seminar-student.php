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
        font-size: 0.75em;
        width: auto;
      }
      table {border-collapse: collapse;}
      a {
        color:black;
      }
      a:hover {
        color:red;
      }
    </style>
    <title>Seminar在线——查看研究课题</title>
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
$result=mysql_query('select * from `student` where `id`='.$_REQUEST['id'].'',$dblink);
$num=mysql_num_rows($result);
if($num<1) {
  die('要查看的学生不存在！');
}
mysql_close($dblink);
$student=new Student($_REQUEST['id']);
?>
<table border="1"><tbody><tr>
  <th>序号<th>名称<th>学期<th>类型<th>课题来源<th>导师姓名<th>详细信息
<?php
$i=1;
foreach($student->seminar as $seminarId) {
  $seminar=new Seminar($seminarId);
  echo '<tr>';
  echo '<td>'.$i.'<td>'.$seminar->title.'<td>'.$seminar->term.'<td>'.$seminar->type.'<td>'.$seminar->source;
  $teacher=new Teacher($seminar->teacher[0]);
  echo '<td><a target="_blank" href="admin-teacher.php?id='.$teacher->id.'">'.$teacher->name.'</a>';
  echo '<td><a target="_blank" href="admin-seminar.php?id='.$seminar->id.'">'.查看.'</a>';
  $i=$i+1;
}
?>
</tbody></table>
  </body>
</html>
