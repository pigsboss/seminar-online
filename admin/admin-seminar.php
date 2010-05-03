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
      a {
        color:black;
      }
      a:hover {
        color:red;
      }
    </style>
    <title>Seminar在线——查看课题详情</title>
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
$seminar=mysql_fetch_assoc($result);
?>
<table border="1"><tbody>
<?php
  $newline=array("\r\n","\r","\n");
  $background=str_replace($newline,'<br>',htmlspecialchars($seminar['background']));
  $content=str_replace($newline,'<br>',htmlspecialchars($seminar['content']));
  $schedule=str_replace($newline,'<br>',htmlspecialchars($seminar['schedule']));
  $result=str_replace($newline,'<br>',htmlspecialchars($seminar['result']));
	echo '<tr><td rowspan="10">课题信息';
  echo '<td>名称<td>'.htmlspecialchars($seminar['title']);
  echo '<tr><td>学期<td>'.htmlspecialchars($seminar['term']);
  echo '<tr><td>类型<td>'.htmlspecialchars($seminar['type']);
  echo '<tr><td>课题来源<td>'.htmlspecialchars($seminar['source']);
  echo '<tr><td>目的与背景<td>'.$background;
  echo '<tr><td>主要内容<td>'.$content;
  echo '<tr><td>进度安排<td>'.$schedule;
  echo '<tr><td>预期成果<td>'.$result;
  if(strlen($seminar['report'])>0) {
		$reports=explode(';',$seminar['report']);
		if(array_sum($reports)>0) {
			foreach($reports as $report) {
				if(strlen($report)>0) {
					if($report>0) {
						echo '<tr><td>报告<td><a target="blank" href="admin-download-report.php?id='.$report.'">'.下载.'</a>';
						break;
					}
				}
			}
		}else {
			echo '<tr><td>报告<td>尚未提交';
		}
  }else {
    echo '<tr><td>报告<td>尚未提交';
  }
  echo '<tr><td>课程学习计划<td>';
  if(strlen($seminar['course'])>0) {
		$courses=explode(';',$seminar['course']);
		if(array_sum($courses)>0) {
			echo '<a target="_blank" href="admin-course-seminar.php?id='.$seminar['id'].'">'.查看.'</a>';
		}else {
			echo '<p>无';
			if(strlen($seminar['remark'])>0) {
				$remark=htmlspecialchars($seminar['remark']);
			}else {
				$remark='无';
			}
			echo '<p>理由：'.$remark;
		}
  }else {
    echo '<p>无';
    if(strlen($seminar['remark'])>0) {
      $remark=htmlspecialchars($seminar['remark']);
    }else {
      $remark='无';
    }
    echo '<p>理由：'.$remark;
  }
	if(strlen($seminar['teacher'])>0) {
		$teachers=explode(';',$seminar['teacher']);
		foreach($teachers as $teacherId) {
			if(strlen($teacherId)>0) {
				if($teacherId>0) {
					$result_teacher=mysql_query('select * from `teacher` where `id`='.$teacherId, $dblink);
					$teacher=mysql_fetch_assoc($result_teacher);
					echo '<tr><td rowspan="8">导师信息';
					echo '<td>姓名<td>'.$teacher['name'];
					echo '<tr><td>院系（单位）<td>'.$teacher['organization'];
					echo '<tr><td>职务<td>'.$teacher['duty'];
					echo '<tr><td>职称<td>'.$teacher['title'];
					echo '<tr><td>研究方向<td>'.$teacher['research'];
					echo '<tr><td>电话号码<td>'.$teacher['telephone'];
					echo '<tr><td>手机号码<td>'.$teacher['cellphone'];
					echo '<tr><td>电子邮箱<td>'.$teacher['mail'];
					break;
				}
			}
		}
	}
	if(strlen($seminar['student'])>0) {
		$studentId=$seminar['student'];
		$result_student=mysql_query('select * from `student` where `id`='.$studentId, $dblink);
		$student=mysql_fetch_assoc($result_student);
		echo '<tr><td rowspan="6">学生信息';
		echo '<td>姓名<td>'.$student['name'];
		echo '<tr><td>学号<td>'.$student['id'];
		echo '<tr><td>班级<td>'.$student['class'];
		echo '<tr><td>邮箱<td>'.$student['mail'];
		echo '<tr><td>电话号码<td>'.$student['telephone'];
		echo '<tr><td>手机号码<td>'.$student['cellphone'];
	}else {
		$result_student=mysql_query('select * from `student`', $dblink);
		$num_students=mysql_num_rows($result_student);
		for($i=0; $i<$num_students; $i++) {
			$student=mysql_fetch_assoc($result_student);
			if(strlen($student['seminar'])>0) {
				$seminars=explode(';',$student['seminar']);
				foreach($seminars as $seminarId) {
					if(strlen($seminarId)>0) {
						if($seminarId==$seminar['id']) {
							echo '<tr><td rowspan="6">学生信息';
							echo '<td>姓名<td>'.$student['name'];
							echo '<tr><td>学号<td>'.$student['id'];
							echo '<tr><td>班级<td>'.$student['class'];
							echo '<tr><td>邮箱<td>'.$student['mail'];
							echo '<tr><td>电话号码<td>'.$student['telephone'];
							echo '<tr><td>手机号码<td>'.$student['cellphone'];
						}
					}
				}
			}
		}
	}
	mysql_close($dblink);
?>
</tbody></table>
  </body>
</html>
