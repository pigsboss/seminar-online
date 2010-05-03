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
<?php
if(!array_key_exists('action',$_REQUEST)) {
  $_REQUEST['action']='seminar';
}
?>
    <script type="text/javascript">
      $(document).ready(function() {
        action="<?php echo $_REQUEST['action'];?>";
        $("div[id='seminar']").hide();
        $("div[id='course']").hide();
        $("div[id='report']").hide();
        switch(action) {
          case "seminar":
            $("div[id='seminar']").show();
            break;
          case "course":
            $("div[id='course']").show();
            break;
          case "report":
            $("div[id='report']").show();
            break;
          default:
        }
        $("button[id='print-seminar']").click(function() {
          $("div[id='menu-bar']").hide();
          $("div[id='seminar']").show();
          $("div[id='course']").hide();
          $("div[id='report']").hide();
          $(this).hide();
          window.print();
          window.location.href=window.location.href;
        });
        $("button[id='print-course']").click(function() {
          $("div[id='menu-bar']").hide();
          $("div[id='seminar']").hide();
          $("div[id='report']").hide();
          $("div[id='course']").show();
          $(this).hide();
          window.print();
          window.location.href=window.location.href;
        });
      });
    </script>
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
  if(is_null($teacher)) {
    $teacher=new Teacher($seminar->teacher[0]);
  }
  $num_teacher_rows=2;
  if(strlen($teacher->mail)>0) {
    $num_teacher_rows=$num_teacher_rows+1;
  }
  if(strlen($teacher->telephone)>0 || strlen($teacher->cellphone)>0) {
    $num_teacher_rows=$num_teacher_rows+1;
  }
  $newline=array("\r\n","\r","\n");
  $background=str_replace($newline,'<br>',htmlspecialchars($seminar->background));
  $content=str_replace($newline,'<br>',htmlspecialchars($seminar->content));
  $schedule=str_replace($newline,'<br>',htmlspecialchars($seminar->schedule));
  $result=str_replace($newline,'<br>',htmlspecialchars($seminar->result));
?>
    <div id="menu-bar">
    <table width="100%"><tbody><tr>
    <td align="left"><small>
    <a href="student.php">个人页面</a>|
    <a href="seminar.php">我的课题</a>
    <td align="right"><small>
    <a href="seminar-single.php?id=<?php echo $seminar->id;?>&action=seminar">研究训练计划</a>|
    <a href="seminar-single.php?id=<?php echo $seminar->id;?>&action=course">课程学习计划</a>|
    <a href="seminar-single.php?id=<?php echo $seminar->id;?>&action=report">研究报告</a>|
    <a href="index.php?action=logout">退出</a>
    </small>
    </tbody></table>
    <hr>
    </div>
    <div id="seminar" align="center">
      <h2>Seminar研究训练计划</h2>
      <p>（<?php echo htmlspecialchars($seminar->term);?>）</p>
      <table border="1" class="print"><tbody>
        <tr><td><table border="1" class="print"><tbody>
        <tr>
          <td >学生姓名<td><?php echo htmlspecialchars($student->name);?>
          <td >学号<td><?php echo htmlspecialchars($student->id);?>
          <td >班级<td><?php echo htmlspecialchars($student->class);?>
        <tr>
          <td  rowspan="<?php echo $num_teacher_rows;?>">导师姓名<td rowspan="<?php echo $num_teacher_rows;?>"><?php echo htmlspecialchars($teacher->name);?>
          <td >所在院系（单位）<td><?php echo htmlspecialchars($teacher->organization);?> 
          <td >职称<td><?php echo htmlspecialchars($teacher->title);?>
        <tr>
          <td >研究方向<td><?php echo htmlspecialchars($teacher->research);?>
          <td >职务<td><?php echo htmlspecialchars($teacher->duty);?>
  <?php
    if($num_teacher_rows>2) {
      if(strlen($teacher->telephone)>0) {
        if(strlen($teacher->cellphone)>0) {
          echo '<tr><td >办公电话<td>'.htmlspecialchars($teacher->telephone).'<td >移动电话<td>'.htmlspecialchars($teacher->cellphone);
        }else {
          echo '<tr><td >办公电话<td colspan="3">'.htmlspecialchars($teacher->telephone);
        }
      }else if(strlen($teacher->cellphone)>0) {
        echo '<tr><td >移动电话<td colspan="3">'.htmlspecialchars($teacher->cellphone);
      }
      if(strlen($teacher->mail)>0) {
        echo '<tr><td >电子邮箱地址<td colspan="3">'.htmlspecialchars($teacher->mail);
      }
    }
  ?>
        </tbody></table>
        <tr><td><table border="1" class="print"></tbody>
        <tr>
          <td id="seminar-title">研究课题名称<td colspan="5"><?php echo htmlspecialchars($seminar->title);?>
        <tr>
          <td>课题属性<td colspan="5"><?php echo htmlspecialchars($seminar->type);?>
        <tr>
          <td>课题来源<td colspan="5"><?php echo htmlspecialchars($seminar->source);?>
        <tr>
          <td>课题的目的与背景<td class="textarea" colspan="5"><?php echo $background;?>
        <tr>
          <td>研究的主要内容<td class="textarea" colspan="5"><?php echo $content;?>
        <tr>
          <td>研究进度安排<td class="textarea" colspan="5"><?php echo $schedule;?>
        <tr>
          <td>预期取得的成果<td class="textarea" colspan="5"><?php echo $result;?>
        </tbody></table>
        <tr><td><table class="print"><tbody>
          <tr>
            <td class="sign">
              <p>学生签名：__________________
              <p>__________年______月______日
            <td class="sign">
              <p>导师签名：__________________
              <p>__________年______月______日
        </tbody></table>
      </tbody></table>
      <p><button id="print-seminar">打印</button>
    </div>
    <div id="course" align="center">
      <h2>Seminar课程学习计划</h2>
      <p>（<?php echo htmlspecialchars($seminar->term);?>）</p>
      <table border="1" class="print"><tbody>
        <tr><td><table border="1" class="print"><tbody>
          <tr>
            <td>学生姓名<td><?php echo htmlspecialchars($student->name);?>
            <td>学号<td><?php echo htmlspecialchars($student->id);?>
            <td>班级<td><?php echo htmlspecialchars($student->class);?>
          <tr>
            <td rowspan="<?php echo $num_teacher_rows;?>">导师姓名<td rowspan="<?php echo $num_teacher_rows;?>"><?php echo htmlspecialchars($teacher->name);?>
            <td>所在院系（单位）<td><?php echo htmlspecialchars($teacher->organization);?> 
            <td>职称<td><?php echo htmlspecialchars($teacher->title);?>
          <tr>
            <td >研究方向<td><?php echo htmlspecialchars($teacher->research);?>
            <td >职务<td><?php echo htmlspecialchars($teacher->duty);?>
        </tbody></table>
        <tr><td><table border="1" class="print"><tbody>
          <tr>
            <th>序号<th>课程名称<th>课号<th>学分<th>课程属性<br>（必/限/选）<th>开课院系<th>导师要求<br>（考试/旁听）
<?php
  if(!empty($seminar->course)) {
    $num=1;
    foreach($seminar->course as $courseId) {
      $course=new Course($courseId);
      echo '<tr><td>'.$num.'<td>'.htmlspecialchars($course->name).'<td>'.htmlspecialchars($course->number).'<td>'.htmlspecialchars($course->score).'<td>'.htmlspecialchars($course->type).'<td>'.htmlspecialchars($course->school).'<td>'.htmlspecialchars($course->request);
      $num=$num+1;
    }
  }
?>
          </tbody></table>
          <table class="print"><tbody>
            <tr>
              <td class="sign">
                <p>学生签名：__________________
                <p>__________年______月______日
              <td class="sign">
                <p>导师签名：__________________
                <p>__________年______月______日
          </tbody></table>
        </tbody></table>  
      <p><button id="print-course">打印</button>
    </div>
    <div id="report">
<?php
if($student->id==$_SESSION['id'] && $seminar->student[0]==$student->id) {
  if(array_key_exists('report',$_FILES)) {
    $uploaddir=UPLOAD_DIR.$student->id;
    if(!is_dir($uploaddir)) {
      if(!mkdir($uploaddir)) {
        die('创建目录失败。');
      }
    }
    $uploaddir=$uploaddir.'/'.$seminar->id;
    if(!is_dir($uploaddir)) {
      if(!mkdir($uploaddir)) {
        die('创建目录失败。');
      }
    }
    $uploaddir=$uploaddir.'/';
    $uploadfile=$uploaddir.md5(time()).basename($_FILES['report']['name']);
    if(is_uploaded_file($_FILES['report']['tmp_name'])) {
      if(move_uploaded_file($_FILES['report']['tmp_name'],$uploadfile)) {
        $report=null;
        if(!empty($seminar->report)) {
          $report=new Report($seminar->report[0]);
          if(!unlink($report->path)) {
            die('删除旧文件失败。');
          }
          $report->path=$uploadfile;
          if(!in_array($report-id,$student->report)) {
            $student->report[]=$report->id;
          }
        }else {
          $report=new Report();
          $report->path=$uploadfile;
          $report->seminar[]=$seminar->id;
          $report->student[]=$student->id;
          $report->teacher[]=$seminar->teacher[0];
          $seminar->report[]=$report->id;
          $student->report[]=$report->id;
        }
      }else {
        die('上传文件失败。');
      }
    }else {
      die('上传文件失败。');
    }
    $seminar->Save();
    $report->Save();
    $student->Save();
    echo '<p>上传文件成功！';
    $dblink=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD,true);
    mysql_query('set names "utf8"',$dblink);
    mysql_select_db('seminar',$dblink);
    $result=mysql_query('select * from `system`',$dblink);
    $row=mysql_fetch_assoc($result);
    $smtp=new smtp_mail($row['smtp_server'],$row['smtp_port'],$row['smtp_user'],$row['smtp_password']);
    mysql_close($dblink);
    //Mail the owner of the report updated
    $smtp->send('wlxywb@mail.tsinghua.edu.cn',$student->mail,'您上传了新的报告','<p>'.$student->name.'，您好！<p>您上传了新的报告，点击下面的链接以下载：<br><a target="blank" href="http://seminar.3322.org/seminar/teacher-download-report.php?id='.$report->id.'&key='.md5($report->path).'">下载报告</a><p>如果您的邮箱不支持超文本电子邮件，请复制下面的链接到浏览器的地址栏之后打开。'."\n".'http://seminar.3322.org/seminar/teacher-download-report.php?id='.$report->id.'&key='.md5($report->path).'<p>祝好！<p>物理系业务办<br><a href="mailto:wlxywb@mail.tsinghua.edu.cn">wlxywb@mail.tsinghua.edu.cn</a>');
    //Mail the teacher of the owner
    $teacher=new Teacher($seminar->teacher[0]);
    $smtp=new smtp_mail($row['smtp_server'],$row['smtp_port'],$row['smtp_user'],$row['smtp_password']);
    $smtp->send('wlxywb@mail.tsinghua.edu.cn',$teacher->mail,'您的学生'.$student->name.'上传了新的报告','<p>'.$teacher->name.'老师，您好！<p>您的学生'.$student->name.'上传了新的报告，请您查看。<p>点击下面的链接以下载：<br><a target="blank" href="http://seminar.3322.org/seminar/teacher-download-report.php?id='.$report->id.'&key='.md5($report->path).'">下载报告</a><p>如果您的邮箱不支持超文本电子邮件，请复制下面的链接到浏览器的地址栏之后打开。'."\n".'http://seminar.3322.org/seminar/teacher-download-report.php?id='.$report->id.'&key='.md5($report->path).'<p>祝好！<p>物理系业务办<br><a href="mailto:wlxywb@mail.tsinghua.edu.cn">wlxywb@mail.tsinghua.edu.cn</a>');
  }
}else {
  die('您没有访问当前的课题的权限。');
}
if(!empty($seminar->report)) {
  echo '<p><a href="download-report.php?id='.$seminar->report[0].'">'.下载报告.'</a>';
}else {
  echo '<p>尚未上传任何报告。';
}
?>
    <form enctype="multipart/form-data" action="seminar-single.php?id=<?php echo $seminar->id;?>&action=report" method="post">
      <table><tbody>
        <tr><td><dt>研究报告上传
          <dd>上传新的报告将覆盖较旧的文件，需自行备份重要文件。
            <br>如包含多个文件，可先制作压缩包再上传。
          <dt>文件限制
          <dd>仅限上传与研究课题相关的报告，无关文件一经发现立即删除。</dd>
        <tr><td><b>浏览计算机以选择需要上传的文件：</b><br>
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo get_upload_max_filesize();?>">
          <input type="file" name="report" size="40">
        <tr><td>
          <input type="submit" value="上传文件">
      </tbody></table>
    </form>
    </div>
  </body>
</html>