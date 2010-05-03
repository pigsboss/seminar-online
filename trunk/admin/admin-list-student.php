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
    <title>Seminar在线——学生列表</title>
  </head>
  <body>
<?php
  if($_SESSION['role']!='admin') {
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
  $dblink=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD,true);
  mysql_query('set names "utf8"',$dblink);
  mysql_select_db('seminar',$dblink);
  $filter='none';
  if(array_key_exists('filter',$_REQUEST)) {
    $filter=$_REQUEST['filter'];
  }
  $result=mysql_query('select * from `student`',$dblink);
  $num=mysql_num_rows($result);
  if(array_key_exists('filter',$_REQUEST)) {
    if($_REQUEST['filter']==='class') {
      if(!array_key_exists('class',$_REQUEST)) {
        $classes=array();
        for($i=0; $i<$num; $i++) {
          $row=mysql_fetch_assoc($result);
          if(!in_array($row['class'],$classes)) {
            $classes[]=$row['class'];
          }
        }
        foreach($classes as $class) {
          echo '<a href="admin-list-student.php?filter=class&class='.urlencode($class).'">'.$class.'</a><br>';
        }
        mysql_close($dblink);
        exit();        
      }
    }
  }
?>
<table border="1"><tbody><tr>
<?php
  switch($filter) {
    case 'detail':
		echo '<th rowspan="2">序号<th colspan="6">学生信息<th colspan="3">课题信息<th colspan="5">导师信息';
		echo '<tr><th>学号<th>姓名<th>班级<th>邮箱<th>手机<th>电话<th>名称<th>学期<th>课名<th>姓名<th>院系（单位）<th>邮箱<th>手机<th>电话';
    for($i=0; $i<$num; $i++) {
      $row=mysql_fetch_assoc($result);
			$seminars=explode(';',$row['seminar']);
			$num_seminar=count($seminars);
			if($num_seminar > 0) {
				if($num_seminar > 1) {
					echo '<tr><td rowspan="'. $num_seminar .'">'. ($i+1) .'<td rowspan="'. $num_seminar .'">'. $row['id'] .'<td rowspan="'. $num_seminar .'">'. $row['name'] .'<td rowspan="'. $num_seminar .'">'. $row['class'] .'<td rowspan="'. $num_seminar .'"><a href="mailto:'. $row['mail'] .'">'. $row['mail'] .'</a><td rowspan="'. $num_seminar .'">'. $row['cellphone'] .'<td rowspan="'. $num_seminar .'">'. $row['telephone'];
					for($j=0; $j<$num_seminar; $j++) {
						$result_seminar = mysql_query('select * from `seminar` where `id`=' . $seminars[$j], $dblink);
						if($result_seminar) {
							$seminar = mysql_fetch_assoc($result_seminar);
							if($j>0) {
                echo '<tr>';
              }
							$teachers=explode(';',$seminar['teacher']);
							foreach($teachers as $teacherId) {
								if(strlen($teacherId)>0) {
									if($teacherId>0) {
										$result_teacher = mysql_query('select * from `teacher` where `id`='.$teacherId, $dblink);
										$teacher = mysql_fetch_assoc($result_teacher);
										echo '<td><a target="_blank" href="admin-seminar.php?id='. $seminar['id'] .'">'. $seminar['title'] .'</a><td>'. $seminar['term'] .'<td>'. $seminar['phase'] .'<td>'. $teacher['name'] .'<td>'. $teacher['organization'] .'<td>'. $teacher['mail'] .'<td>'. $teacher['cellphone'] .'<td>'. $teacher['telephone'];
										break;
									}
								}
							}
						}else {
							echo '<td colspan="8">尚未提交任何研究计划';
						}	
					}
				}else {
					echo '<tr><td>'. ($i+1) .'<td>'. $row['id'] .'<td>'. $row['name'] .'<td>'. $row['class'] .'<td><a href="mailto:'. $row['mail'] .'">'. $row['mail'] .'</a><td>'. $row['cellphone'] .'<td>'. $row['telephone'];
					$result_seminar = mysql_query('select * from `seminar` where `id`=' . $seminars[0], $dblink);
					if($result_seminar) {
						$seminar = mysql_fetch_assoc($result_seminar);
						$teachers=explode(';',$seminar['teacher']);
						//var_dump($teachers);
						$result_teacher = mysql_query('select * from `teacher` where `id`=' . $teachers[0], $dblink);
						if($result_teacher) {
							$teacher = mysql_fetch_assoc($result_teacher);
						}
						echo '<td><a target="_blank" href="admin-seminar.php?id='. $seminar['id'] .'">'. $seminar['title'] .'</a><td>'. $seminar['term'] .'<td>'. $seminar['phase'] .'<td>'. $teacher['name'] .'<td>'. $teacher['organization'] .'<td>'. $teacher['mail'] .'<td>'. $teacher['cellphone'] .'<td>'. $teacher['telephone'];
					}else {
						echo '<td colspan="8">尚未提交任何研究计划';
					}
				}
			}else {
				echo '<tr><td>'. ($i+1) .'<td>'. $row['id'] .'<td>'. $row['name'] .'<td>'. $row['class'] .'<td><a href="mailto:'. $row['mail'] .'">'. $row['mail'] .'</a>';
				echo '<td colspan="2">尚未提交任何研究计划';
			}

			
			
			
			
			
			
			
			
			/*
      $student = new Student($row['id']);
      echo '<tr><td>'.($i+1).'<td>'.$row['name'].'<td>'.$row['id'].'<td>'.$row['class'].'<td><a href="mailto:'.$row['mail'].'">'.$row['mail'].'</a>';
      if(count($student->seminar)==0) {
        echo '<td colspan="10">本学期尚未提交研究计划';
      }else {
				foreach($student->seminar as $seminarId) {
					$seminar=new Seminar($seminarId);
					if($seminar->term===getterm()) {
						break;
					}
				}
				if($seminar->term!=getterm()) {
					echo '<td colspan="10">本学期尚未提交研究计划';
				}else {
					$teacher=new Teacher($seminar->teacher[0]);
					echo '<td>'.$seminar->title.'<td>'.$teacher->name.'<td>'.$teacher->organization.'<td><a href="mailto:'.$teacher->mail.'">'.$teacher->mail.'</a><td>'.$teacher->research.'<td>'.$teacher->title.'<td>'.$teacher->duty.'<td>'.$teacher->telephone.'<td>'.$seminar->type;
					if(count($seminar->report)>0) {
						$report=new Report($seminar->report[0]);
						echo '<td><a href="admin-download-report.php?id='.$report->id.'">'.已提交.'</a>';
					}else {
						echo '<td>未提交';
					}
				}
      }
      echo '<td><a href="admin-seminar-student.php?id='.$row['id'].'">'.查看.'</a>';
			
			*/
    }
    break;
    case 'class':
    echo '<th>序号<th>姓名<th>学号<th>班级<th>电子邮箱<th>是否提交本学期课题<th>详细信息';
    $k=1;
    for($i=0; $i<$num; $i++) {
      $seminar_added='否';
      $report_added=false;
      $row=mysql_fetch_assoc($result);
      $student=new Student($row['id']);
      foreach($student->seminar as $seminarId) {
        $seminar=new Seminar($seminarId);
        if($seminar->term===getterm()) {
          $seminar_added='是';
          if(count($seminar->report)>0) {
            $report_added=true;
          }
          break;
        }
      }
      if(urldecode($_REQUEST['class'])===$row['class']) {  
				echo '<tr><td>'.($k).'<td>'.$row['name'].'<td>'.$row['id'].'<td>'.$row['class'].'<td><a href="mailto:'.$row['mail'].'">'.$row['mail'].'</a><td>'.$seminar_added.'<td><a href="admin-seminar-student.php?id='.$row['id'].'">'.查看.'</a>';
				$k=$k+1;
      }
    }
    break;
    case 'report':
    echo '<th>序号<th>姓名<th>学号<th>班级<th>电子邮箱<th>是否提交本学期课题<th>详细信息';
    $k=1;
    for($i=0; $i<$num; $i++) {
      $seminar_added='否';
      $report_added=false;
      $row=mysql_fetch_assoc($result);
      $student=new Student($row['id']);
      foreach($student->seminar as $seminarId) {
        $seminar=new Seminar($seminarId);
        if($seminar->term===getterm()) {
          $seminar_added='是';
          if(count($seminar->report)>0) {
            $report_added=true;
          }
          break;
        }
      }
      if(!$report_added) {  
        echo '<tr><td>'.($k).'<td>'.$row['name'].'<td>'.$row['id'].'<td>'.$row['class'].'<td><a href="mailto:'.$row['mail'].'">'.$row['mail'].'</a><td>'.$seminar_added.'<td><a href="admin-seminar-student.php?id='.$row['id'].'">'.查看.'</a>';
        $k=$k+1;
      }
    }
    break;
    case 'course':
    echo '<th>序号<th>姓名<th>学号<th>班级<th>电子邮箱<th>是否提交本学期课题<th>备注<th>详细信息';
    $k=1;
    for($i=0; $i<$num; $i++) {
      $seminar_added='否';
      $course_added=false;
      $row=mysql_fetch_assoc($result);
      $student=new Student($row['id']);
      foreach($student->seminar as $seminarId) {
        $seminar=new Seminar($seminarId);
        if($seminar->term===getterm()) {
          $seminar_added='是';
          if(count($seminar->course)>0) {
            $course_added=true;
          }
          break;
        }
      }
      if(!$course_added) {
        if(strlen($seminar->remark)>0) {
          $remark=htmlspecialchars($seminar->remark);
        }else {
          $remark='无';
        }  
        echo '<tr><td>'.($k).'<td>'.$row['name'].'<td>'.$row['id'].'<td>'.$row['class'].'<td><a href="mailto:'.$row['mail'].'">'.$row['mail'].'</a><td>'.$seminar_added.'<td>'.$remark.'<td><a href="admin-seminar-student.php?id='.$row['id'].'">'.查看.'</a>';
        $k=$k+1;
      }
    }
    break;
    default:
    echo '<th>序号<th>姓名<th>学号<th>班级<th>电子邮箱<th>手机号码<th>电话号码<th>详细信息';
    for($i=0; $i<$num; $i++) {
      $row=mysql_fetch_assoc($result);
      $student=new Student($row['id']);
      echo '<tr><td>'.($i+1).'<td>'.$row['name'].'<td>'.$row['id'].'<td>'.$row['class'].'<td><a href="mailto:'.$row['mail'].'">'.$row['mail'].'</a><td>'.$row['cellphone'].'<td>'.$row['telephone'].'<td><a href="admin-seminar-student.php?id='.$row['id'].'">'.查看.'</a>';
    }
  }
  mysql_close($dblink);
?>
</tbody></table>
  </body>
</html>
