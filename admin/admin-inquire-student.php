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
      dd {
        font-size: 0.75em;
      }
      dt {
        font-size: 0.9em;
        font-weight: bold;
      }
    </style>
    <title>Seminar在线——学生查询</title>
  </head>
  <body>
    <div id="inquire-form">
      <form action="admin-inquire-student.php" method="post">
        输入欲查询学生的学号：<input type="text" name="student_id"><input type="submit" value="查询">
      </form>
    </div>
    <hr>
    <div id="result-table">
<?php
  if($_SESSION['role']!='admin') {
    die('<a target="_top" href="index.php">返回首页登录</a>');
  }
  if(count($_POST)>0) {
    $student_id=htmlspecialchars($_POST['student_id']);
    $dblink=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD,true);
    mysql_query('set names "utf8"',$dblink);
    mysql_select_db('seminar',$dblink);
    $result=mysql_query('select * from `student` where `id`="'.$student_id.'"',$dblink);
    $num=mysql_num_rows($result);
    if($num>0) {
      if($num==1) {
        $student=mysql_fetch_assoc($result);
        $seminars=explode(';',$student['seminar']);
        $num_seminar=count($seminars);
        if($num_seminar>0) {
          if($num_seminar>1) {
            echo '<table border="1"><tbody><tr><td>学号<td>'.$student['id'].'<tr><td>班级<td>'.$student['class'].'<tr><td>姓名<td>'.$student['name'].'<tr><td>邮箱<td>'.$student['mail'].'<tr><td>手机<td>'.$student['cellphone'].'<tr><td>电话<td>'.$student['telephone'].'<tr><td>课题<td>';
            for($j=0; $j<$num_seminar; $j++) {
              $result_seminar = mysql_query('select * from `seminar` where `id`=' . $seminars[$j], $dblink);
              if($result_seminar) {
                $seminar = mysql_fetch_assoc($result_seminar);
                echo $seminar['term'].'，'. $seminar['phase'].'：<a target="_blank" href="admin-seminar.php?id='.$seminar['id'].'">'. $seminar['title'] .'</a><br>';
              }else {
                echo '尚未提交任何研究计划';
              }	
            }
            echo '</tbody></table>';
          }else {
            echo '<table border="1"><tbody><tr><td>学号<td>'.$student['id'].'<tr><td>班级<td>'.$student['class'].'<tr><td>姓名<td>'.$student['name'].'<tr><td>邮箱<td>'.$student['mail'].'<tr><td>手机<td>'.$student['cellphone'].'<tr><td>电话<td>'.$student['telephone'].'<tr><td>课题<td>';
            $result_seminar = mysql_query('select * from `seminar` where `id`=' . $seminars[0], $dblink);
            if($result_seminar) {
              $seminar = mysql_fetch_assoc($result_seminar);
                echo $seminar['term'].'，'. $seminar['phase'].'：<a target="_blank" href="admin-seminar.php?id='.$seminar['id'].'">'. $seminar['title'] .'</a>';
            }else {
              echo '尚未提交任何研究计划';
            }	
            echo '</tbody></table>';
          }
        }else {
          echo '<table border="1"><tbody><tr><td>学号<td>'.$student['id'].'<tr><td>班级<td>'.$student['class'].'<tr><td>姓名<td>'.$student['name'].'<tr><td>邮箱<td>'.$student['mail'].'<tr><td>手机<td>'.$student['cellphone'].'<tr><td>电话<td>'.$student['telephone'].'<tr><td>课题<td>尚未提交。</tbody></table>';
        }
      }else {
        mysql_close($dblink);
        die('查询结果不唯一。');
      }
    }else {
      echo '没有查询到学号为'.$student_id.'的学生。';
    }
    mysql_close($dblink);
  }
/*   echo '<table border="1"><tbody><tr>'
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
  }
 */
?>
    </div>
  </body>
</html>
