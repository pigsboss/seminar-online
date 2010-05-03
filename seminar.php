<?php
  require_once('class.php');
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
        background-image:url('images/watermark.gif');
      }
      a {
        color:black;
      }
      a:hover {
        color:red;
      }
      table {border-collapse: collapse;}
      h3 {text-align: center;font-size: 16pt;font-family: "黑体";}
      th {font-size: 11pt;}
      td.name {text-align: center;font-size: 11pt;}
      td.optional {text-align: center;font-size: 11pt;font-style: italic;}
      td.value {text-align: left;font-size: 10pt;}
      td.button {text-align: right;font-size: 10pt;}
      input,textarea,select {font-size: 10pt;}
      ol {text-align: left;font-size: 11pt;list-style-type: decimal;line-height: 1.5em;width: 600px;}
      dt {font-weight: bold;}
      dd {font-style: italic;color: red;}
      p {font-size: 11pt;}
    </style>
    <script src="jquery.js"></script>
    <script src="check_mail.js"></script>
    <script src="check_phone.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        //根据request显示不同的div
        action=window.location.href.substring(window.location.href.indexOf('=')+1);
        $("div[id='seminar-list']").show();
        $("div[id='add-seminar-prompt']").hide();
        $("div[id='add-seminar-form']").hide();
        if(action=='add-seminar') {
          $("div[id='seminar-list']").hide();
          $("div[id='add-seminar-prompt']").show();
          $("div[id='add-seminar-form']").hide();
        }
        //numCourse是课程学习计划表中已经添加的课程数量
        numCourse=$("input[name='number-of-course']").val();
        numCourse=parseInt(numCourse);
        //idCourse是课程学习计划表中当前编辑的课程id
        idCourse=0;
        //根据课程数量决定是否显示已经添加的课程列表
        if(numCourse>0) {
          $("table[id='course-list']").show();
        }else {
          $("table[id='course-list']").hide();
        }
        //点击”添加“课程触发的事件
        $("input[id='course-add']").click(function() {
          name=$("input[name='course-name-single']").val().replace(/;/g,"");
          number=$("input[name='course-number-single']").val().replace(/;/g,"");
          score=$("input[name='course-score-single']").val().replace(/;/g,"");
          school=$("input[name='course-school-single']").val().replace(/;/g,"");
          type=$("select[name='course-type-single']").val().replace(/;/g,"");
          request=$("select[name='course-request-single']").val().replace(/;/g,"");
          //先判断是否为空字符串
          if(name.length>0 && number.length>0 && score.length>0 && school.length>0) {
            //再判断学分是否为非负整数
            if(parseInt(score)>-1) {
              idCourse=idCourse+1;
              numCourse=numCourse+1;
              if(numCourse>0) {
                $("table[id='course-list']").show();
              }else {
                $("table[id='course-list']").hide();
              }
              $("input[name='number-of-course']").val(String(numCourse));
              if(idCourse>1) {
                $("input[name='course-name']").val($("input[name='course-name']").val()+';');
                $("input[name='course-number']").val($("input[name='course-number']").val()+';');
                $("input[name='course-score']").val($("input[name='course-score']").val()+';');
                $("input[name='course-type']").val($("input[name='course-type']").val()+';');
                $("input[name='course-request']").val($("input[name='course-request']").val()+';');
                $("input[name='course-school']").val($("input[name='course-school']").val()+';');
              }
              $("input[name='course-name']").val($("input[name='course-name']").val()+name);
              $("input[name='course-number']").val($("input[name='course-number']").val()+number);
              $("input[name='course-score']").val($("input[name='course-score']").val()+score);
              $("input[name='course-type']").val($("input[name='course-type']").val()+type);
              $("input[name='course-request']").val($("input[name='course-request']").val()+request);
              $("input[name='course-school']").val($("input[name='course-school']").val()+school);
              $("tbody[id='course-list-tbody']").append('<tr id="'+idCourse+'"><td>'+name+"<td>"+number+"<td>"+type+"<td>"+score+"<td>"+school+"<td>"+request+"<td>"+'<input name="delete" type="button" value="删除" id="'+idCourse+'">');
              //点击删除课程按钮触发的事件
              $("input[id='"+idCourse+"'][type='button'][name='delete']").click(function() {
                $("tr[id='"+$(this).attr("id")+"']").remove();
                numCourse=numCourse-1;
                if(numCourse>0) {
                  $("table[id='course-list']").show();
                }else {
                  $("table[id='course-list']").hide();
                }
                $("input[name='number-of-course']").val(String(numCourse));
                if($("input[name='course-remove']").val().length>0) {
                  $("input[name='course-remove']").val($("input[name='course-remove']").val()+';');
                }
                $("input[name='course-remove']").val($("input[name='course-remove']").val()+$(this).attr('id'));
              });
              $("input[name='course-name-single']").val("");
              $("input[name='course-number-single']").val("");
              $("input[name='course-score-single']").val("");
              $("input[name='course-school-single']").val("");
            }else {
              alert('填写的学分信息不合法。');
            }
          }else {
            alert('未填写完全。');
          }
        });
        $("input[id='seminar-next']").click(function() {
          $("table[id='seminar']").hide();
          $("table[id='teacher']").show();
          $("table[id='course']").hide();
        });
        $("input[id='teacher-next']").click(function() {
          $("table[id='seminar']").hide();
          $("table[id='teacher']").hide();
          $("table[id='course']").show();
        });
        $("input[id='teacher-prev']").click(function() {
          $("table[id='seminar']").show();
          $("table[id='teacher']").hide();
          $("table[id='course']").hide();
        });
        $("input[id='course-prev']").click(function() {
          $("table[id='seminar']").hide();
          $("table[id='teacher']").show();
          $("table[id='course']").hide();
        });
        //处理有无课程学习计划
        courseRadios=document.getElementsByName('with-course');
        courseRadioTrue=courseRadios[0];
        courseRadioFalse=courseRadios[1];
        withCourse=false;
        if(courseRadioTrue.value=='false') {
          courseRadioTrue=courseRadios[1];
          courseRadioFalse=courseRadios[0];
        }
        withCourse=courseRadioTrue.checked;
        if(withCourse) {
          $("td[id='course-remark']").hide();
          $("td[id='course-plan']").show();
        }else {
          $("td[id='course-plan']").hide();
          $("td[id='course-remark']").show();
        }
        courseRadioTrue.onclick=function() {
          $("td[id='course-remark']").hide();
          $("td[id='course-plan']").show();
          withCourse=true;
        };
        courseRadioFalse.onclick=function() {
          $("td[id='course-plan']").hide();
          $("td[id='course-remark']").show();
          withCourse=false;
        };
        //点击完成按钮后触发的事件
        $("input[id='finish']").click(function() {
          seminar_title=$("input[name='seminar-title']").val();
          seminar_type=$("select[name='seminar-type']").val();
          seminar_source=$("select[name='seminar-source']").val();
          seminar_background=$("textarea[name='seminar-background']").val();
          seminar_content=$("textarea[name='seminar-content']").val();
          seminar_schedule=$("textarea[name='seminar-schedule']").val();
          seminar_result=$("textarea[name='seminar-result']").val();
          teacher_name=$("input[name='teacher-name']").val();
          teacher_organization=$("input[name='teacher-organization']").val();
          teacher_title=$("input[name='teacher-title']").val();
          teacher_duty=$("input[name='teacher-duty']").val();
          teacher_telephone=$("input[name='teacher-telephone']").val();
          teacher_research=$("input[name='teacher-research']").val();
          teacher_cellphone=$("input[name='teacher-cellphone']").val();
          teacher_mail=$("input[name='teacher-mail']").val();
          course_name=$("input[name='course-name']").val();
          course_number=$("input[name='course-number']").val();
          course_type=$("input[name='course-type']").val();
          course_score=$("input[name='course-score']").val();
          course_school=$("input[name='course-school']").val();
          course_request=$("input[name='course-request']").val();
          course_remove=$("input[name='course-remove']").val();
          //检查是否有空缺的信息
          if(seminar_title.length>0 && seminar_background.length>=20 && seminar_result.length>=20 && 
          seminar_content.length>=20 && seminar_schedule.length>=20 && teacher_name.length>0 && 
          teacher_organization.length>0 && teacher_title.length>0 && teacher_duty.length>0 && 
          teacher_research.length>0 && teacher_telephone.length>0 && teacher_mail.length>0) {
            if(teacher_cellphone.length==0) {
              teacher_cellphone=prompt("建议提供导师的移动电话号码。");
              if(teacher_cellphone!=null) {
                $("input[name='teacher-cellphone']").val(teacher_cellphone);
              }
            }
						while(teacher_mail.length<1 || !check_mail(teacher_mail)) {
							teacher_mail=prompt("电子邮箱地址不合法，请核实后重新输入：");
						}
						$("input[name='teacher-mail']").val(teacher_mail);
						while(teacher_telephone.length<1 || !check_telephone(teacher_telephone)) {
							teacher_telephone=prompt("办公电话号码无效，请核实后重新输入：");
						}
						$("input[name='teacher-telephone']").val(teacher_telephone);
						while(teacher_cellphone.length>0 && !check_cellphone(teacher_cellphone)) {
							teacher_cellphone=prompt("移动电话号码无效，请核实后重新输入：");
						}
						$("input[name='teacher-cellphone']").val(teacher_cellphone);
            if(withCourse) {
              if(course_name.length==0 || course_type.length==0 || course_score.length==0 ||
              course_school.length==0 || course_number.length==0 || course_request.length==0) {
                alert("如果包含课题学习计划，需要先编辑课程信息，然后添加课程。");
                return ;
              }
            }else {
              $("input[name='course-name']").val("");
              $("input[name='course-number']").val("");
              $("input[name='course-type']").val("");
              $("input[name='course-score']").val("");
              $("input[name='course-school']").val("");
              $("input[name='course-request']").val("");
              $("input[name='course-remove']").val("");
            }
            $("form").submit();
          }else {
            alert("必填项未填写完全或者不符合要求，请核查后再点击完成。");
          }
        });
      });
    </script>
    <title>Seminar在线——我的课题</title>
  </head>
  <body>
    <div id="header">
      <h1><em>Seminar在线</em></h1>
    </div>
    <hr>
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
if(array_key_exists('seminar-title',$_POST)) {
  $dblink=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD,true);
  mysql_query('set names "utf8"',$dblink);
  mysql_select_db('seminar',$dblink);
  $student_id=htmlspecialchars($_SESSION['id']);
  $seminar_term=htmlspecialchars($_POST['seminar-termyear'].$_POST['seminar-termseason']);
  $seminar_phase=htmlspecialchars($_POST['seminar-phase']);
  $seminar_title=htmlspecialchars($_POST['seminar-title']);
  $seminar_type=htmlspecialchars($_POST['seminar-type']);
  $seminar_source=htmlspecialchars($_POST['seminar-source']);
  $seminar_background=htmlspecialchars($_POST['seminar-background']);
  $seminar_content=htmlspecialchars($_POST['seminar-content']);
  $seminar_schedule=htmlspecialchars($_POST['seminar-schedule']);
  $seminar_result=htmlspecialchars($_POST['seminar-result']);
  $seminar_student=array();
  $seminar_teacher=array();
  $seminar_course=array();
  $seminar_student[]=$student_id;
  $teacher_name=htmlspecialchars($_POST['teacher-name']);
  $teacher_organization=htmlspecialchars($_POST['teacher-organization']);
  $teacher_duty=htmlspecialchars($_POST['teacher-duty']);
  $teacher_title=htmlspecialchars($_POST['teacher-title']);
  $teacher_telephone=htmlspecialchars($_POST['teacher-telephone']);
  $teacher_cellphone=htmlspecialchars($_POST['teacher-cellphone']);
  $teacher_mail=htmlspecialchars($_POST['teacher-mail']);
  $teacher_research=htmlspecialchars($_POST['teacher-research']);
  $teacher_seminar='';
  $teacher_student='';
  $result=mysql_query('insert into `teacher` (`name`, `organization`, `duty`, `title`, `telephone`, `cellphone`, `mail`, `research`) values ("'.$teacher_name.'", "'.$teacher_organization.'", "'.$teacher_duty.'", "'.$teacher_title.'", "'.$teacher_telephone.'", "'.$teacher_cellphone.'", "'.$teacher_mail.'", "'.$teacher_research.'")', $dblink);
  if (!$result) {
    die('无效的请求0：' . mysql_error());
  }
  $teacher_id=mysql_insert_id($dblink);
  $seminar_teacher[]=$teacher_id;
  if($_POST['with-course']==="true") {
    $course_name=explode(';',htmlspecialchars($_POST['course-name']));
    $course_number=explode(';',htmlspecialchars($_POST['course-number']));
    $course_score=explode(';',htmlspecialchars($_POST['course-score']));
    $course_type=explode(';',htmlspecialchars($_POST['course-type']));
    $course_school=explode(';',htmlspecialchars($_POST['course-school']));
    $course_request=explode(';',htmlspecialchars($_POST['course-request']));
    $course_remove=explode(';',htmlspecialchars($_POST['course-remove']));
    for($i=0;$i<count($course_name);$i++) {
      if(!in_array(($i+1)."",$course_remove)) {
        $result=mysql_query('insert into `course` (`name`,`number`,`score`,`type`,`school`,`request`) values("'.$course_name.'","'.$course_number.'","'.$course_score.'","'.$course_type.'","'.$course_school.'","'.$course_request.'")', $dblink);
        if (!$result) {
          die('无效的请求：' . mysql_error());
        }
        $course_id=mysql_insert_id($dblink);
        $seminar->course[]=$course_id;
      }
    }
    $seminar_remark='';
  }else {
    $seminar_remark=htmlspecialchars($_POST['course-remark']);
  }
  $seminar_course=implode(';',$seminar_course);
  $seminar_student=implode(';',$seminar_student);
  $seminar_teacher=implode(';',$seminar_teacher);
  $timestamp=getdate();
  $result=mysql_query('insert into `seminar` (`phase`,`remark`,`type`,`source`,`title`,`content`,`background`,`result`,`schedule`,`term`,`date`,`teacher`,`course`,`student`) values ("'.$seminar_phase.'","'.$seminar_remark.'","'.$seminar_type.'","'.$seminar_source.'","'.$seminar_title.'","'. $seminar_content.'","'.$seminar_background.'","'.$seminar_result.'","'.$seminar_schedule.'","'.$seminar_term.'",'.$timestamp[0].',"'.$seminar_teacher.'","'.$seminar_course.'","'.$seminar_student.'")',$dblink);
  if (!$result) {
    die('无效的请求1：' . mysql_error());
  }
  $seminar_id=mysql_insert_id($dblink);
  $result=mysql_query('select * from `student` where `id`="'.$student_id.'"', $dblink);
  if (!$result) {
    die('无效的请求2：' . mysql_error());
  }
  $num=mysql_num_rows($result);
  if($num!=1) {
    die('学号为'.$student_id.'的学生不唯一。');
  }
  $student=mysql_fetch_assoc($result);
  $student_seminar=explode(';', $student['seminar']);
  $num_seminar=count($student_seminar);
  $new_student_seminar=array();
  $new_student_seminar[]=$seminar_id;
  for($i=0; $i<$num_seminar; $i++) {
    $result=mysql_query('select * from `seminar` where `id`="'.$student_seminar[$i].'"', $dblink);
    if (!$result) {
      die('无效的请求3：' . mysql_error());
    }
    $old_seminar=mysql_fetch_assoc($result);
    if($old_seminar['term']==$seminar_term && $old_seminar['phase']==$seminar_phase) {
      echo '旧的记录被覆盖。';
      $old_seminar['id']=$seminar_id;
    }
    if(!in_array($old_seminar['id'], $new_student_seminar)) {
      $new_student_seminar[]=$old_seminar['id'];
    }
  }
  var_dump($new_student_seminar);
  $new_student_seminar=implode(';',$new_student_seminar);
  var_dump($new_student_seminar);
  $result=mysql_query('replace into `student` (`id`, `name`, `class`, `mail`, `cellphone`, `telephone`, `password`, `seminar`, `report`, `teacher`, `partner`) values ("'.$student_id.'", "'.$student['name'].'", "'.$student['class'].'", "'.$student['mail'].'", "'.$student['cellphone'].'", "'.$student['telephone'].'", "'.$student['password'].'", "'.$new_student_seminar.'", "'.$student['report'].'", "'.$student['teacher'].'", "'.$student['partner'].'")', $dblink);
  if (!$result) {
    die('无效的请求4：' . mysql_error());
  }  
  mysql_close($dblink);
/*
  $student=new Student($_SESSION['id']);
  $seminar=new Seminar();
  $student->seminar[]=$seminar->id;
  $teacher=new Teacher();
  $seminar->term=$_POST['seminar-termyear'].$_POST['seminar-termseason'];
  $seminar->phase=$_POST['seminar-phase'];
  $seminar->title=$_POST['seminar-title'];
  $seminar->type=$_POST['seminar-type'];
  $seminar->source=$_POST['seminar-source'];
  $seminar->background=$_POST['seminar-background'];
  $seminar->content=$_POST['seminar-content'];
  $seminar->schedule=$_POST['seminar-schedule'];
  $seminar->result=$_POST['seminar-result'];
  $seminar->student[]=$student->id;
  $seminar->teacher[]=$teacher->id;
  $teacher->name=$_POST['teacher-name'];
  $teacher->organization=$_POST['teacher-organization'];
  $teacher->duty=$_POST['teacher-duty'];
  $teacher->title=$_POST['teacher-title'];
  $teacher->telephone=$_POST['teacher-telephone'];
  $teacher->cellphone=$_POST['teacher-cellphone'];
  $teacher->mail=$_POST['teacher-mail'];
  $teacher->research=$_POST['teacher-research'];
  $teacher->seminar[]=$seminar->id;
  $teacher->student[]=$student->id;
  if($_POST['with-course']==="true") {
    $course_name=explode(';',$_POST['course-name']);
    $course_number=explode(';',$_POST['course-number']);
    $course_score=explode(';',$_POST['course-score']);
    $course_type=explode(';',$_POST['course-type']);
    $course_school=explode(';',$_POST['course-school']);
    $course_request=explode(';',$_POST['course-request']);
    $course_remove=explode(';',$_POST['course-remove']);
    for($i=0;$i<count($course_name);$i++) {
      if(!in_array(($i+1)."",$course_remove)) {
        $course=new Course();
        $course->name=$course_name[$i];
        $course->number=$course_number[$i];
        $course->score=$course_score[$i];
        $course->type=$course_type[$i];
        $course->school=$course_school[$i];
        $course->request=$course_request[$i];
        $seminar->course[]=$course->id;
        $course->Save();
      }
    }
  }else {
    $seminar->remark=$_POST['course-remark'];
  }
  $seminar->Save();
  $student->Save();
  $teacher->Save();
*/
}
?>
    <table width="100%"><tbody><tr>
    <td align="left"><small>
    <a href="student.php">个人页面</a>|
    <a href="seminar.php">我的课题</a>
    <td align="right"><small>
    <a href="seminar.php?action=add-seminar">添加课题</a>|
    <a href="index.php?action=logout">退出</a>
    </small>
    </tbody></table>
    <hr>
    <div id="seminar-list" align="left">
<?php
  if(is_null($student)) {
    $student=new Student($_SESSION['id']);
  }
  if(count($student->seminar)>0) {
    echo '<table width="80%"><tbody><tr><th>名称<th>学期<th>类型<th>操作';
    foreach($student->seminar as $seminarId) {
      $seminar=new Seminar($seminarId);
			if(strlen($seminar->phase)<1) {
				echo '<tr><td><a href="seminar-single.php?id='.$seminar->id.'">'.htmlspecialchars($seminar->title).'</a>（缺少必要信息，请<a href="seminar-single-modify.php?id='.$seminar->id.'">补充</a>完全）<td>'.$seminar->term.'<td>'.$seminar->type.'<td><a href="seminar-single.php?id='.$seminar->id.'">查看</a>|<a href="seminar-single-delete.php?id='.$seminar->id.'">删除</a>|<a  href="seminar-single-modify.php?id='.$seminar->id.'">修改</a>';
			}else {
				echo '<tr><td><a href="seminar-single.php?id='.$seminar->id.'">'.htmlspecialchars($seminar->title).'</a><td>'.$seminar->term.'<td>'.$seminar->type.'<td><a href="seminar-single.php?id='.$seminar->id.'">查看</a>|<a href="seminar-single-delete.php?id='.$seminar->id.'">删除</a>|<a  href="seminar-single-modify.php?id='.$seminar->id.'">修改</a>';
			}
    }
    echo '</tbody></table>';
  }else {
    echo <<<EOT
<p>目前还没有添加任何研究课题。<br>
<button onclick="window.open('seminar.php?action=add-seminar','_self')">添加课题</button>
EOT;
  }
?>
    </div>
    <div id="add-seminar-prompt" align="left">
      <p>添加研究课题之前，先认真阅读<a target="blank" href="notice.php">《基科班Seminar课程的要求和注意事项》</a>。
      点击<button id="notice-read">已经阅读</button>按钮后填写添加研究课题所需的表格。表格中可选项目以斜体字标出，
      其他一律为必填项。填写完整后方可完成。有的同学还需要提交课程学习计划，也在表格中的选择。</p>
      <p>每个学生每学期只能添加一个研究课题，重复添加将会覆盖较旧的记录。</p>
      <button onclick="window.open('notice.php')">现在就阅读</button>
      <button id="notice-read">已经阅读</button>
      <script type="text/javascript">
        $(document).ready(function() {
          $("button[id='notice-read']").click(function() {
            $("div[id='add-seminar-prompt']").hide();
            $("div[id='add-seminar-form']").show();
            $("table[id='seminar']").show();
            $("table[id='teacher']").hide();
            $("table[id='course']").hide();
          });
        });
      </script>
    </div>
    <div id="add-seminar-form" align="center">
      <form action="seminar.php" method="post">
        <table id="seminar"><tbody>
          <tr><th colspan="2"><h3>Seminar研究训练计划</h3>
          <tr><td class="name">本计划相关工作进行于<td class="value">
            <select name="seminar-termyear">
              <option>2007-2008学年度</option>
              <option>2008-2009学年度</option>
              <option>2009-2010学年度</option>
              <option>2010-2011学年度</option>
            </select>
            <select name="seminar-termseason">
              <option>春季学期</option>
              <option>夏季学期</option>
              <option>秋季学期</option>
            </select>
          <tr><td class="name">本计划处于阶段<sup>*<sup><td class="value"><select name="seminar-phase">
            <option>专题研究课（1）</option>
            <option>专题研究课（2）</option>
            <option>专题研究课（3）</option>
            </select>
          <tr><td class="name">研究课题名称<sup>*<sup><td class="value"><input type="text" name="seminar-title" size="60">
          <tr><td class="name">课题属性<td class="value">
            <select name="seminar-type">
              <option selected="on">理论型</option>
              <option>实验型</option>
            </select>
          <tr><td class="name">课题来源<td class="value">
            <select name="seminar-source">
              <option selected="on">导师布置，是导师课题的一部分</option>
              <option>导师布置，与导师课题无关</option>
              <option>自选</option>
            </select>
          <tr><td class="name">课题的目的与背景<br>（至少20字。）<td class="value">
            <textarea name="seminar-background" cols="60" rows="4"></textarea>
          <tr><td class="name">研究的主要内容<sup>*</sup><br>（至少20字。）<td class="value">
            <textarea name="seminar-content" cols="60" rows="4"></textarea>
          <tr><td class="name">研究进度安排<sup>*</sup><br>（至少20字。）<td class="value">
            <textarea name="seminar-schedule" cols="60" rows="4"></textarea>
          <tr><td class="name">预期取得的成果<sup>*</sup><br>（至少20字。）<td class="value">
            <textarea name="seminar-result" cols="60" rows="4"></textarea>
          <tr><td colspan="2">
            <h3>注意</h3>
            <ol>
              <li>进入seminar的每位同学都必须填写此表。
              <li>对表中打“*”的条目的说明：
                <dt>“本计划处于阶段”
                <dd>同一个研究课题可能持续多个学期，这种情况下每学期仍需提交研究计划。不同学期提交的计划处于同一个课题的不同阶段，这里填写的信息必须与选定课程名称一致，例如：专题研究课（1）、专题研究课（2）、专题研究课（3）。
                <dt>“研究课题名称”
                <dd>填导师布置给你的研究题目，而不是填导师或其他人的研究题目。
                <dt>“研究的主要内容”
                <dd>填你独立或与他人合作研究的主要内容，而不是介绍其他人的研究内容。
                <dt>“研究进度安排”
                <dd>填你在进入研究实质训练的学期内将做哪些工作，而不是介绍其他人的研究进展。
                <dt>“预期取得的成果”
                <dd>填在你的研究题目下，你将取得的研究成果，以及展示成果的形式（如发表正式论文、会议上宣读、专利、搭建实验仪器、编写计算软件等）。而不是预计其他人将会取得的什么研究成果。
              <li>“研究计划”是在确定研究题目后填表提交，不要晚于第二个Seminar学期（第6个学期）的头一个月。交给班委负责同学，收齐后交给负责老师。
              <li>若换导师和研究方向，需重新填写此表，并交给负责老师。
            </ol></td>
          <tr><td colspan="2" class="button"><input type="button" value="下一步" id="seminar-next">
        </tbody></table>
        <table id="teacher"><tbody>
          <tr><th colspan="2"><h3>导师信息</h3>
          <tr><td class="name">导师姓名<td class="value"><input type="text" name="teacher-name">
          <tr><td class="name">所在院系（单位）<td class="value"><input type="text" name="teacher-organization">
          <tr><td class="name">研究方向<td class="value"><input type="text" name="teacher-research">
          <tr><td class="name">职称<td class="value"><input type="text" name="teacher-title">
          <tr><td class="name">职务<td class="value"><input type="text" name="teacher-duty">
          <tr><td class="name">办公电话号码<td class="value"><input type="text" name="teacher-telephone">
          <tr><td class="name optional">移动电话号码<td class="value"><input type="text" name="teacher-cellphone">
          <tr><td class="name">电子信箱地址<td class="value"><input type="text" name="teacher-mail">
          <tr><td colspan="2" class="button"><input type="button" value="上一步" id="teacher-prev">
            <input type="button" value="下一步" id="teacher-next">
        </tbody></table>
        <input type="hidden" name="number-of-course" value="0">
        <input type="hidden" name="course-name" value="">
        <input type="hidden" name="course-number" value="">
        <input type="hidden" name="course-type" value="">
        <input type="hidden" name="course-score" value="">
        <input type="hidden" name="course-school" value="">
        <input type="hidden" name="course-request" value="">
        <input type="hidden" name="course-remove" value="">
        <table id="course"><tbody>
          <tr><th><h3>Seminar课程学习计划</h3>
          <tr><td>是否包含课程学习计划？<input type="radio" checked="on" name="with-course" value="true">是
            <input type="radio" name="with-course" value="false">否
          <tr>
            <td id="course-plan">
              <table border="1" id="course-list">
                <tbody id="course-list-tbody">
                  <tr><th colspan="7"><h3>已经添加的课程列表</h3>
                  <tr id="course-list-head">
                    <th>课程名称<th>课号<th>课程属性<th>学分<th>开课院系<th>导师要求<th>操作
                  </tr>
                </tbody>
              </table>
              <table id="course-edit">
                <tbody>
                  <tr><th colspan="2"><h3>编辑待添加的课程</h3>
                  <tr><td class="name">课程名称<td class="value"><input type="text" name="course-name-single">
                  <tr><td class="name">课号<td class="value"><input type="text" name="course-number-single">
                  <tr><td class="name">课程属性<td class="value">
                    <select name="course-type-single">
                      <option selected="on" value="必">必修</option>
                      <option value="限">限选</option>
                      <option value="选">选修</option>
                    </select>
                  <tr><td class="name">学分<td class="value"><input type="text" name="course-score-single">
                  <tr><td class="name">开课院系<td class="value"><input type="text" name="course-school-single">
                  <tr><td class="name">导师要求<td class="value">
                    <select name="course-request-single">
                      <option selected="on" value="考试">考试</option>
                      <option value="旁听">旁听</option>
                    </select>
                  <tr><td colspan="2" class="button"><input type="button" value="添加" id="course-add">
                </tbody>
              </table>
            </td>
          <tr>
            <td id="course-remark">
              <table id="remark">
                <tbody>
                  <tr><td><p>无特殊原因，须包含课题学习计划。否则，需要说明原因：
                  <tr><td><textarea name="course-remark" cols="60" rows="6"></textarea>
                </tbody>
              </table>
          <tr><td class="button"><input type="button" value="上一步" id="course-prev">
            <input type="button" value="完成" id="finish">
        </tbody></table>
      </form>
    </div>
  </body>
</html>