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
      a {
        color:black;
      }
      a:hover {
        color:red;
      }
      body {
        background-color:thistle;
      }
      dd {
        font-size: 0.75em;
      }
      dt {
        font-size: 0.9em;
        font-weight: bold;
      }
    </style>
    <title>Seminar在线——课程学习计划</title>
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
$result=mysql_query('select * from `seminar` where `id`='.$_REQUEST['id'].'',$dblink);
$num=mysql_num_rows($result);
if($num<1) {
  die('要查看的课题不存在！');
}
mysql_close($dblink);
$seminar=new Seminar($_REQUEST['id']);
?>
<table border="1"><tbody><tr>
  <th>序号<th>课程名称<th>课程号<th>学分<th>开课院系<th>课程属性<th>导师要求
<?php
$i=1;
foreach($seminar->course as $courseId) {
  $course=new Course($courseId);
  echo '<tr><td>'.$i;
  echo '<td>'.$course->name.'<td>'.$course->number.'<td>'.$course->score.'<td>'.$course->school.'<td>'.$course->type.'<td>'.$course->request;
  $i=$i+1;
}
?>
</tbody></table>
  </body>
</html>
