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
        white-space: nowrap;
      }
      a {
        color:black;
      }
      a:hover {
        color:red;
      }
      table {border-collapse: collapse;}
    </style>
    <title>Seminar在线——课题列表</title>
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
$result_student=mysql_query('select * from `student`',$dblink);
$num=mysql_num_rows($result_student);
?>
<table border="1"><tbody><tr>
  <th rowspan="2">序号<th colspan="4">课题信息<th colspan="6">学生信息<th colspan="5">导师信息
	<tr><th>名称<th>已交报告<th>学期<th>课名<th>姓名<th>学号<th>班级<th>邮箱<th>手机<th>电话<th>姓名<th>院系（单位）<th>邮箱<th>手机<th>电话
<?php
$k=1;
for($i=0; $i<$num; $i++) {
  $student=mysql_fetch_assoc($result_student);
	if(strlen($student['seminar'])>0) {
		$seminars=explode(';',$student['seminar']);
		if(count($seminars)>0) {
			foreach($seminars as $seminarId) {
				$result_seminar=mysql_query('select * from `seminar` where `id`='.$seminarId, $dblink);
				$seminar=mysql_fetch_assoc($result_seminar);
				$has_report='否';
				if(strlen($seminar['report'])>0) {
					$reports=explode(';',$seminar['report']);
					foreach($reports as $report) {
						if(strlen($report)>0) {
							if($report>0) {
								$has_report='是';
							}
						}
					}
				}
				$teachers=explode(';',$seminar['teacher']);
				$teacherId=$teachers[0];
				$result_teacher=mysql_query('select * from `teacher` where `id`='.$teacherId, $dblink);
				$teacher=mysql_fetch_assoc($result_teacher);
				echo '<tr><td>'.$k.'<td><a href="admin-seminar.php?id='.$seminar['id'].'">'.$seminar['title'].'</a><td>'.$has_report.'<td>'.$seminar['term'].'<td>'.$seminar['phase'].'<td>'.$student['name'].'<td>'.$student['id'].'<td>'.$student['class'].'<td><a href="mailto:'.$student['mail'].'">'.$student['mail'].'</a><td>'.$student['cellphone'].'<td>'.$student['telephone'].'<td>'.$teacher['name'].'<td>'.$teacher['organization'].'<td><a href="mailto:'.$teacher['mail'].'">'.$teacher['mail'].'</a><td>'.$teacher['cellphone'].'<td>'.$teacher['telephone'];
				$k=$k+1;
			}
		}
	}
}
mysql_close($dblink);
?>
</tbody></table>
  </body>
</html>
