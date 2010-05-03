<?php
  require_once('../class.php');
  session_start();
?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="../jquery.js"></script>
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
    <title>Seminar在线——课题查询</title>
  </head>
  <body>
	<form action="admin-inquire-seminar.php" method="post">
		<table><tbody>
			<tr><td>课程名：<td>
				<select name="phase">
					<option value="">任意课程名</option>
					<option>专题研究课（1）</option>
					<option>专题研究课（2）</option>
					<option>专题研究课（3）</option>
				</select>
			<tr><td>学期：<td>
				<select name="term-year">
					<option value="">任意学年度</option>
					<option>2007-2008学年度</option>
					<option>2008-2009学年度</option>
					<option>2009-2010学年度</option>
					<option>2010-2011学年度</option>
				</select>
				<select name="term-season">
					<option value="">任意学期</option>
					<option>秋季学期</option>
					<option>春季学期</option>
					<option>夏季学期</option>
				</select>
			<tr><td>班级：<td>
				<select name="class">
					<optgroup label="任意班级">
					<option value="">任意班级</option>
					<optgroup label="基科物理">
						<option>基物51</option>
						<option>基物52</option>
						<option>基物61</option>
						<option>基物62</option>
						<option>基物71</option>
						<option>基物72</option>
					</optgroup>
					<optgroup label="基科应用">
						<option>基应51</option>
						<option>基应52</option>
						<option>基应61</option>
						<option>基应62</option>
						<option>基应71</option>
						<option>基应72</option>
					</optgroup>
					<optgroup label="其它班级">
						<option>其它班级</option>
					</optgroup>
				</select>
			<tr><td><input type="submit" value="查询"><td><input type="reset" value="重置条件">
		</tbody></table>
	</form>
	<div id="no-match">
		<p>对不起，没有找到与您提供的条件相匹配的记录。
	</div>
	<script type="text/javascript">
		$("div[id='no-match']").hide();
	</script>
<?php
if($_SESSION['role']!='admin') {
	die('<a target="_top" href="index.php">返回首页登录</a>');
}
if(array_key_exists('term-year',$_POST)) {
	$dblink=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD,true);
	mysql_query('set names "utf8"',$dblink);
	mysql_select_db('seminar',$dblink);
	if($_POST['class']==='') {
		$result_student=mysql_query('select * from `student`', $dblink);
	}else {
		$result_student=mysql_query('select * from `student` where `class`="'.$_POST['class'].'"', $dblink);
	}
	$num_students=mysql_num_rows($result_student);
	echo '<div id="inquire-result"><table border="1"><th rowspan="2">序号<th colspan="4">课题信息<th colspan="6">导师信息<th colspan="6">学生信息';
	echo '<tr><th>名称<th>报告<th>学期<th>课名<th>姓名<th>院系（单位）<th>研究方向<th>邮箱<th>手机号码<th>电话号码<th>姓名<th>班级<th>学号<th>邮箱<th>手机号码<th>电话号码';
	$k=1;
	for($i=0; $i<$num_students; $i++) {
		$student=mysql_fetch_assoc($result_student);
		if(strlen($student['seminar'])>0) {
			$seminars=explode(';',$student['seminar']);
			foreach($seminars as $seminarId) {
				if(strlen($seminarId)>0) {
					if($seminarId>0) {
						$result_seminar=mysql_query('select * from `seminar` where `id`='.$seminarId, $dblink);
						$seminar=mysql_fetch_assoc($result_seminar);
						if($_POST['term-year']==='' || strstr($seminar['term'], $_POST['term-year'])) {
							if($_POST['term-season']==='' || strstr($seminar['term'], $_POST['term-season'])) {
								if($_POST['phase']==='' || $seminar['phase']===$_POST['phase']) {
									$report='未交';
									if(strlen($seminar['report'])>0) {
										$reports=explode(';',$seminar['report']);
										foreach($reports as $reportId) {
											if(strlen($reportId)>0) {
												if($reportId>0) {
													$report='<a href="admin-download-report.php?id='.$reportId.'">已交</a>';
													break;
												}
											}
										}
									}
									$phase='未填写';
									if(!is_null($seminar['phase'])) {
										if(strlen($seminar['phase'])>0) {
											if(strstr($seminar['phase'],'专题研究课')) {
												$phase=$seminar['phase'];
											}
										}
									}
									$has_teacher=false;
									if(strlen($seminar['teacher'])>0) {
										$teachers=explode(';',$seminar['teacher']);
										foreach($teachers as $teacherId) {
											if(strlen($teacherId)>0) {
												if($teacherId>=0) {
													$result_teacher=mysql_query('select * from `teacher` where `id`='.$teacherId, $dblink);
													if($result_teacher) {
														$teacher=mysql_fetch_assoc($result_teacher);
														if(strlen($teacher['name'])>0) {
															$has_teacher=true;
														}
													}
												}
											}
										}
									}
									if($has_teacher) {
										$teacher_name=$teacher['name'];
										$teacher_organization='未知';
										$teacher_research='未知';
										$teacher_mail='未知';
										$teacher_cellphone='未知';
										$teacher_telephone='未知';
										if(strlen($teacher['organization'])>0) {
											$teacher_organization=htmlspecialchars($teacher['organization']);
										}
										if(strlen($teacher['research'])>0) {
											$teacher_research=htmlspecialchars($teacher['research']);
										}
										if(strlen($teacher['mail'])>0) {
											$teacher_mail='<a href="mailto:'.htmlspecialchars($teacher['mail']).'">'.htmlspecialchars($teacher['mail']).'</a>';
										}
										if(strlen($teacher['cellphone'])>0) {
											if(check_cellphone($teacher['cellphone'])) {
												$teacher_cellphone=htmlspecialchars($teacher['cellphone']);
											}else {
												$teacher_cellphone='无效';
											}
										}
										if(strlen($teacher['telephone'])>0) {
											if(check_telephone($teacher['telephone'])) {
												$teacher_telephone=htmlspecialchars($teacher['telephone']);
											}else {
												$teacher_telephone='无效';
											}
										}
										$teacher_info='<td>'.$teacher_name.'<td>'.$teacher_organization.'<td>'.$teacher_research.'<td>'.$teacher_mail.'<td>'.$teacher_cellphone.'<td>'.$teacher_telephone;
									}else {
										$teacher_info='<td colspan="6">导师信息不存在';
									}
									$student_mail='未知';
									$student_cellphone='未知';
									$student_telephone='未知';
									if(strlen($student['mail'])>0) {
										$student_mail='<a href="mailto:'.htmlspecialchars($student['mail']).'">'.htmlspecialchars($student['mail']).'</a>';
									}
									if(!is_null($student['cellphone'])) {
										if(strlen($student['cellphone'])>0) {
											if(check_cellphone($student['cellphone'])) {
												$student_cellphone=htmlspecialchars($student['cellphone']);
											}else {
												$student_cellphone='无效';
											}
										}
									}
									if(!is_null($student['telephone'])) {
										if(strlen($student['telephone'])>0) {
											if(check_telephone($student['telephone'])) {
												$student_telephone=htmlspecialchars($student['telephone']);
											}else {
												$student_telephone='无效';
											}
										}
									}
									$student_info='<td>'.$student['name'].'<td>'.$student['class'].'<td>'.$student['id'].'<td>'.$student_mail.'<td>'.$student_cellphone.'<td>'.$student_telephone;
									echo '<tr><td>'.$k.'<td><a href="admin-seminar.php?id='.$seminar['id'].'">'.$seminar['title'].'</a><td>'.$report.'<td>'.$seminar['term'].'<td>'.$phase.$teacher_info.$student_info;
									$k=$k+1;
								}
							}
						}
					}
				}
			}
		}
	}
	echo '</table></div>';
	if($k<=1) {
		echo <<<EOT
<script type="text/javascript">
	$("div[id='inquire-result']").hide();
	$("div[id='no-match']").fadeIn();
</script>
EOT;
	}
}
?>
  </body>
</html>