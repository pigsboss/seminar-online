<?php
  require_once("config.php");
  class Student {
    public $id;
    private $dblink;
    public $name='';
    public $class='';
    public $mail='';
    public $password='';
    public $seminar=array();
    public $report=array();
    public $teacher=array();
    public $partner=array();
    public function StudentExists() {
      $id;
      if(func_num_args()<1) {
        $id=$this->id;
      }else {
        $id=func_get_arg(0);
      }
      $result=mysql_query('select * from `student` where `id`="'.$id.'"',$this->dblink);
      if(!$result) {
        die('Student::StudentExists: 无效的请求：'.mysql_error($this->dblink));
      }
      $n=mysql_num_rows($result);
      return ($n>0);
    }
    public function __construct() {
      $this->dblink=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
      mysql_query('set names "utf8"',$this->dblink);
      if (!$this->dblink) {
        die('Could not connect: ' . mysql_error());
      }
      if (!mysql_select_db(DB_NAME, $this->dblink)) {
        die('Could not use ' . DB_NAME .': ' . mysql_error());
      }
      if (func_num_args()>0) {
        $id=func_get_arg(0);
        if ($this->StudentExists($id)) {
          $this->__load($id);
        }
      }
    }
    public function __destruct() {
      if (!is_null($this->id)) {
        $this->__save();
      }
      mysql_close($this->dblink);
    }
    private function __load($id) {          
      $result=mysql_query('select * from `student` where `id`='.$id, $this->dblink);
      if (!$result) {
        var_dump($id);
        die('Student::__load: Invalid query: ' . mysql_error());
      }
      $num_rows=mysql_num_rows($result);
      if ($num_rows>0) {
        $row=mysql_fetch_assoc($result);
        $this->id=$row['id'];
        $this->name=$row['name'];
        $this->class=$row['class'];
        $this->mail=$row['mail'];
        $this->cellphone=$row['cellphone'];
        $this->telephone=$row['telephone'];
        $this->password=$row['password'];
        $seminar=explode(';', $row['seminar']);
        foreach($seminar as $seminarId) {
          if (strlen($seminarId)>0 && $seminarId>=0) {
            array_push($this->seminar,$seminarId+0);
          }
        }
        $this->seminar=array_unique($this->seminar);
        $report=explode(';', $row['report']);
        foreach($report as $reportId) {
          if (strlen($reportId)>0 && $reportId>=0) {
            array_push($this->report,$reportId+0);
          }
        }
        $this->report=array_unique($this->report);
        $teacher=explode(';', $row['teacher']);
        foreach($teacher as $teacherId) {
          if (strlen($teacherId)>0 && $teacherId>=0) {
            array_push($this->teacher,$teacherId+0);
          }
        }
        $this->teacher=array_unique($this->teacher);        
        $partner=explode(';', $row['partner']);
        foreach($partner as $partnerId) {
          if (strlen($partnerId)>0 && $partnerId>=0) {
            array_push($this->partner,$partnerId+0);
          }
        }
        $this->partner=array_unique($this->partner);        
        return true;
      }else {
        echo 'ID doesn\'t exist. ';
        return false;
      }
    }
    public function Save() {
      if ($this->id>0 && strlen($this->id)==10) {
        $this->__save();
        return true;
      }else {
        return false;
      }
    }
    private function __save() {
      $seminar=implode(';', $this->seminar);
      $report=implode(';', $this->report);
      $teacher=implode(';', $this->teacher);
      $partner=implode(';', $this->partner);
      $result=mysql_query(
      'replace into `student` (`id`,`name`,`class`,`mail`,`cellphone`,`telephone`,`password`,`seminar`,`report`,`teacher`,`partner`) values('
      .$this->id.',"'.$this->name.'","'. $this->class.'","'.$this->mail.'","'.$this->cellphone.'","'.$this->telephone.'","'.$this->password.'","'.$seminar.'","'.$report.'","'.$teacher.'","'.$partner.'")', $this->dblink);
      if (!$result) {
        var_dump($this);
        die('Student::__save: Invalid query: ' . mysql_error());
      }
    }
    public function AddSeminar($seminarId) {
      array_push($this->seminar,$seminarId);
      $this->__sync();
      $this->__save();
    }
    private function __sync() {
      $this->partner=array();
      $this->teacher=array();
      $this->report=array();
      $this->seminar=array_unique($this->seminar);
      foreach($this->seminar as $seminarId) {
        $seminar=new Seminar($seminarId);
        foreach($seminar->student as $partnerId) {
          $student=new Student($partnerId);
          if ($this->id!=$student->id && !in_array($this->id,$student->partner)) {
            array_push($student->partner,$this->id);
          }
          if ($this->id!=$student->id) {
            array_push($this->partner,$partnerId);
          }
        }
        foreach($seminar->teacher as $teacherId) {
          $teacher=new Teacher($teacherId);
          if (!in_array($this->id,$teacher->student)) {
            array_push($teacher->student,$this->id);
          }
          array_push($this->teacher,$teacher->id);
        }  
        foreach($seminar->report as $reportId) {
          array_push($this->report,$reportId);
        }
        if (!in_array($this->id,$seminar->student)) {
          array_push($seminar->student,$this->id);
        }
      }
      $this->partner=array_unique($this->partner);
      $this->teacher=array_unique($this->teacher);
      $this->report=array_unique($this->report);
    }
  }
  class Seminar {
    public $id;
    private $dblink;
    public $date;
    public $title;
    public $type=1;
    public $subject;
    public $source=1;
    public $background;
    public $content;
    public $schedule;
    public $result;
    public $term;
    public $remark;
    public $course=array();
    public $student=array();
    public $teacher=array();
    public $report=array();
    public function __construct() {
      $this->dblink=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
      mysql_query('set names "utf8"',$this->dblink);
      if (!$this->dblink) {
        die('Could not connect: ' . mysql_error());
      }
      if (!mysql_select_db(DB_NAME, $this->dblink)) {
        die('Could not use ' . DB_NAME .': ' . mysql_error());
      }
      if (func_num_args()>0) {
        $id=func_get_arg(0);
        $this->__load($id);
      }else {
        $this->__new();
      }
    }
    public function __destruct() {
      $this->Save();
      mysql_close($this->dblink);
    }
    public function __delete() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          if(!empty($this->teacher)) {
            foreach($this->teacher as $teacherId) {
              $teacher=new Teacher($teacherId);
              $teacher->__delete();
            }
          }
          if(!empty($this->course)) {
            foreach($this->course as $courseId) {
              $course=new Course($courseId);
              $course->__delete();
            }
          }
          if(!empty($this->report)) {
            foreach($this->report as $reportId) {
              $report=new Report($reportId);
              $report->__delete();
            }
          }
          $result=mysql_query('delete from `seminar` where `id`='.$this->id, $this->dblink);
          if(!$result) {
            die('Seminar::__delete: 无效的请求'.mysql_error());
          }
          $this->id=null;
        }
      }
    }
    public function _unset() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          if(!empty($this->teacher)) {
            foreach($this->teacher as $teacherId) {
              $teacher=new Teacher($teacherId);
              $teacher->__delete();
            }
          }
          if(!empty($this->course)) {
            foreach($this->course as $courseId) {
              $course=new Course($courseId);
              $course->__delete();
            }
          }
          $this->teacher=array();
          $this->course=array();
          $this->student=array();
          $this->report=array();
        }
      }
    }
    public function __load($id) {
      $result=mysql_query('select * from `seminar` where `id`='.$id, $this->dblink);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      $num_rows=mysql_num_rows($result);
      if ($num_rows>0) {
        $row=mysql_fetch_assoc($result);
        $this->id=$row['id'];
        $this->term=$row['term'];
        $this->schedule=$row['schedule'];
        $this->background=$row['background'];
        $this->source=$row['source'];
        $this->type=$row['type'];
        $this->content=$row['content'];
        $this->result=$row['result'];
        $this->subject=$row['subject'];
        $this->remark=$row['remark'];
        $this->title=$row['title'];
        $this->phase=$row['phase'];
        $timestamp=$row['date'];
        $this->date=getdate($timestamp);
        $course=explode(';', $row['course']);
        foreach($course as $courseId) {
          if (strlen($courseId)>0 && $courseId>=0) {
            array_push($this->course,$courseId+0);
          }
        }
        $this->course=array_unique($this->course);
        $report=explode(';', $row['report']);
        foreach($report as $reportId) {
          if (strlen($reportId)>0 && $reportId>=0) {
            array_push($this->report,$reportId+0);
          }
        }
        $this->report=array_unique($this->report);
        $teacher=explode(';', $row['teacher']);
        foreach($teacher as $teacherId) {
          if (strlen($teacherId)>0 && $teacherId>=0) {
            array_push($this->teacher,$teacherId+0);
          }
        }
        $this->teacher=array_unique($this->teacher);
        $student=explode(';', $row['student']);
        foreach($student as $studentId) {
          if (strlen($studentId)>0 && $studentId>=0) {
            array_push($this->student,$studentId+0);
          }
        }
        $this->student=array_unique($this->student);
        return true;
      }else {
        echo 'ID doesn\'t exist. ';
        $this->id=null;
        return false;
      }
    }
    public function __new() {
      $this->date=getdate();
      $result=mysql_query(
      'insert into `seminar` (`remark`,`background`,`result`,`schedule`,`source`,`subject`,`report`,`type`,`title`,`content`,`date`,`term`,`teacher`,`student`,`course`) values("","","","","","","","","","",'.$this->date[0].',"","","","")', $this->dblink);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      $this->id=mysql_insert_id($this->dblink);
    }
    public function Save() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          $this->__save();
        }
      }
    }
    private function __save() {
      $course=implode(';',$this->course);
      $student=implode(';', $this->student);
      $report=implode(';', $this->report);
      $teacher=implode(';', $this->teacher);
      $timestamp=$this->date[0];
      $result=mysql_query(
      'replace into `seminar` (`phase`,`remark`,`id`,`type`,`source`,`title`,`content`,`background`,`result`,`schedule`,`subject`,`term`,`date`,`report`,`teacher`,`course`,`student`) values("'.$this->phase.'","'.$this->remark.'",'.$this->id.',"'.$this->type.'","'.$this->source.'","'.$this->title.'","'. $this->content.'","'.$this->background.'","'.$this->result.'","'.$this->schedule.'","'.$this->subject.'","'.$this->term.'",'.$timestamp.',"'.$report.'","'.$teacher.'","'.$course.'","'.$student.'")', $this->dblink);
      if (!$result) {
        //die('Seminar::__save: 无效的请求：' . mysql_error());
      }
    }
  }
  function getterm() {
    $date=getdate();
    if ($date['yday']>31 && $date['yday']<212) {
      return ($date['year']-1).'-'.$date['year'].'学年度春季学期';
    }else {
      return $date['year'].'-'.($date['year']+1).'学年度秋季学期';
    }
  }
  class Teacher {
    public $id;
    private $dblink;
    public $name='';
    public $telephone='';
    public $cellphone='';
    public $mail='';
    public $organization='';
    public $title='';
    public $duty='';
    public $research='';
    public $seminar=array();
    public $student=array();
    public $report=array();
    public function __construct() {
      $this->dblink=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
      mysql_query('set names "utf8"',$this->dblink);
      if (!$this->dblink) {
        die('Could not connect: ' . mysql_error());
      }
      if (!mysql_select_db(DB_NAME, $this->dblink)) {
        die('Could not use ' . DB_NAME .': ' . mysql_error());
      }
      if (func_num_args()>0) {
        $id=func_get_arg(0);
        $this->__load($id);
      }else {
        $this->__new();
      }
    }
    public function __destruct() {
      $this->Save();
      mysql_close($this->dblink);
    }
    public function __load($id) {
      $result=mysql_query('select * from `teacher` where `id`='.$id, $this->dblink);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      $num_rows=mysql_num_rows($result);
      if ($num_rows>0) {
        $row=mysql_fetch_assoc($result);
        $this->id=$row['id'];
        $this->cellphone=$row['cellphone'];
        $this->research=$row['research'];
        $this->duty=$row['duty'];
        $this->title=$row['title'];
        $this->telephone=$row['telephone'];
        $this->mail=$row['mail'];
        $this->organization=$row['organization'];
        $this->name=$row['name'];
        $report=explode(';', $row['report']);
        foreach($report as $reportId) {
          if (strlen($reportId)>0 && $reportId>=0) {
            array_push($this->report,$reportId+0);
          }
        }
        $this->report=array_unique($this->report);
        $seminar=explode(';', $row['seminar']);
        foreach($seminar as $seminarId) {
          if (strlen($seminarId)>0 && $seminarId>=0) {
            array_push($this->seminar,$seminarId+0);
          }
        }
        $this->seminar=array_unique($this->seminar);
        $student=explode(';', $row['student']);
        foreach($student as $studentId) {
          if (strlen($studentId)>0 && $studentId>=0) {
            array_push($this->student,$studentId+0);
          }
        }
        $this->student=array_unique($this->student);
        return true;
      }else {
        echo 'ID doesn\'t exist. ';
        return false;
      }
    }
    public function __new() {
      $result=mysql_query(
      'insert into `teacher` (`research`,`duty`,`title`,`cellphone`,`telephone`,`mail`,`organization`,`name`,`report`,`seminar`,`student`) values('
      .'"","","","","","","","","","","")', $this->dblink);
      if (!$result) {
        die('Teacher::__new: 无效的请求：' . mysql_error());
      }
      $this->id=mysql_insert_id($this->dblink);
    }
    private function __save() {
      $student=implode(';', $this->student);
      $report=implode(';', $this->report);
      $seminar=implode(';', $this->seminar);
      $result=mysql_query(
      'replace into `teacher` (`research`,`duty`,`title`,`id`,`cellphone`,`telephone`,`mail`,`organization`,`name`,`report`,`seminar`,`student`) values('
      .'"'.$this->research.'","'.$this->duty.'","'.$this->title.'",'.$this->id.',"'.$this->cellphone.'","'.$this->telephone.'","'.$this->mail.'","'.$this->organization.'","'.$this->name.'","'.$report.'","'.$seminar.'","'.$student.'")', $this->dblink);
      if (!$result) {
        die('Teacher::__new: 无效的请求' . mysql_error());
      }
    }
    public function Save() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          $this->__save();
        }
      }
    }
    public function __delete() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          $result=mysql_query('delete from `teacher` where `id`='.$this->id.'',$this->dblink);
          if(!$result) {
            die('Teacher::__delete: 无效的请求' . mysql_error());
          }
          $this->id=null;
        }
      }
    }
  }
  class Report {
    public $id;
    private $dblink;
    public $path='';
    public $student=array();
    public $seminar=array();
    public $teacher=array();
    public function __construct() {
      $this->dblink=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
      mysql_query('set names "utf8"',$this->dblink);
      if (!$this->dblink) {
        die('Could not connect: ' . mysql_error());
      }
      if (!mysql_select_db(DB_NAME, $this->dblink)) {
        die('Could not use ' . DB_NAME .': ' . mysql_error());
      }
      if (func_num_args()>0) {
        $id=func_get_arg(0);
        $this->__load($id);
      }else {
        $this->__new();
      }
    }
    public function __destruct() {
      $this->__save();
      mysql_close($this->dblink);
    }
    public function __load($id) {
      $result=mysql_query('select * from `report` where `id`='.$id, $this->dblink);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      $num_rows=mysql_num_rows($result);
      if ($num_rows>0) {
        $row=mysql_fetch_assoc($result);
        $this->id=$row['id'];
        $this->path=$row['path'];
        $this->teacher=explode(';', $row['teacher']);
        $this->seminar=explode(';', $row['seminar']);
        $this->student=explode(';', $row['student']);
        return true;
      }else {
        echo 'ID doesn\'t exist. ';
        return false;
      }
    }
    public function __new() {
      $result=mysql_query(
      'insert into `report` (`path`,`teacher`,`seminar`,`student`) values('
      .'"","","","")', $this->dblink);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
      $this->id=mysql_insert_id($this->dblink);
    }
    private function __save() {
      $student=implode(';', $this->student);
      $teacher=implode(';', $this->teacher);
      $seminar=implode(';', $this->seminar);
      $result=mysql_query(
      'replace into `report` (`id`,`path`,`teacher`,`seminar`,`student`) values('
      .$this->id.',"'.$this->path.'","'.$teacher.'","'.$seminar.'","'.$student.'")', $this->dblink);
      if (!$result) {
        die('Invalid query: ' . mysql_error());
      }
    }
    public function Save() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          $this->__save();
        }
      }
    }
    public function __delete() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          $result=mysql_query('delete from `delete` where `id`='.$this->id.'',$this->dblink);
          if(!$result) {
            die('Course::__delete: 无效的请求' . mysql_error());
          }
          $this->id=null;
          if(!unlink($this->path)) {
            die('删除文件失败。');
          }
        }
      }
    }
  }
  class Admin {
    private $dblink;
    public function __construct() {
      $this->dblink=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
      mysql_query('set names "utf8"',$this->dblink);
      if (!$this->dblink) {
        die('Could not connect: ' . mysql_error());
      }
      if (!mysql_select_db(DB_NAME, $this->dblink)) {
        die('Could not use ' . DB_NAME .': ' . mysql_error());
      }
    }
    public function __destruct() {
      mysql_close($this->dblink);
    }
    public function GetAllStudent() {
      return $this->__getAllElement('student');
    }
    public function GetAllSeminar() {
      return $this->__getAllElement('seminar');
    }
    public function GetAllTeacher() {
      return $this->__getAllElement('teacher');
    }
    public function GetAllReport() {
      return $this->__getAllElement('report');
    }
    private function __getAllElement($element) {
      $result=mysql_query('select * from `'.$element.'`',$this->dblink);
      if (!$result) {
        die('无效请求：'.mysql_error($this->dblink));
      }
      $n=mysql_num_rows($result);
      if ($n<1) {
        echo $element.'为空。';
        return NULL;
      }
      $rows=array();
      for($i=0;$i<$n;$i++) {
        $row=mysql_fetch_assoc($result);
        array_push($rows,$row);
      }
      return $rows;
    }
  }
  class Course {
    private $dblink;
    public $id;
    public $name;
    public $number;
    public $type=1;
    public $score;
    public $request=1;
    public $school;
    public function __construct() {
      $this->dblink=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
      mysql_query('set names "utf8"',$this->dblink);
      if (!$this->dblink) {
        die('Course::__construct: 无法连接：' . mysql_error());
      }
      if (!mysql_select_db(DB_NAME, $this->dblink)) {
        die('Course::__construct: 无法使用' . DB_NAME .': ' . mysql_error());
      }
      if (func_num_args()>0) {
        $id=func_get_arg(0);
        $this->__load($id);
      }else {
        $this->__new();
      }
    }
    public function __destruct() {
      $this->Save();
      mysql_close($this->dblink);
    }
    public function __load($id) {
      $result=mysql_query('select * from `course` where `id`='.$id, $this->dblink);
      if (!$result) {
        die('Course::__load: 无效的请求：' . mysql_error());
      }
      $num_rows=mysql_num_rows($result);
      if ($num_rows>0) {
        $row=mysql_fetch_assoc($result);
        $this->id=$row['id'];
        $this->name=$row['name'];
        $this->number=$row['number'];
        $this->score=$row['score'];
        $this->school=$row['school'];
        $this->type=$row['type'];
        $this->request=$row['request'];
        return true;
      }else {
        echo 'ID doesn\'t exist. ';
        return false;
      }
    }
    public function __new() {
      $result=mysql_query(
      'insert into `course` (`name`,`number`,`score`,`school`) values('
      .'"","","","")', $this->dblink);
      if (!$result) {
        die('Course::__new: 无效的请求：' . mysql_error());
      }
      $this->id=mysql_insert_id($this->dblink);
    }
    private function __save() {
      $result=mysql_query(
      'replace into `course` (`id`,`name`,`number`,`score`,`school`,`type`,`request`) values('
      .$this->id.',"'.$this->name.'","'.$this->number.'","'.$this->score.'","'.$this->school.'","'.$this->type.'","'.$this->request.'")', $this->dblink);
      if (!$result) {
        die('Course::__save: 无效的请求：' . mysql_error());
      }
    }
    public function Save() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          $this->__save();
        }
      }
    }
    public function __delete() {
      if(!is_null($this->id)) {
        if(strlen($this->id)>0) {
          $result=mysql_query('delete from `course` where `id`='.$this->id.'',$this->dblink);
          if(!$result) {
            die('Course::__delete: 无效的请求' . mysql_error());
          }
          $this->id=null;
        }
      }
    }
  }
  class Page {
    public $title='';
    public $script='';
    private $body='';
    private $head='';
    private $foot='';
    public function out() {
      $html=<<<EOT
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="jquery.js"></script>
EOT;
      if (strlen($this->script)>0) {
        $html=$html.'<script src="'.$this->script.'"></script>';
      }
      $html=$html.'<title>'.$this->title.'</title>';
      $html=$html.'</head><body>'.$this->body.'</body></html>';
      echo $html;
    }
  }
  function randstr($length) {
    $str='';
    for($i=0;$i<$length;$i++) {
      $char=rand(0,61);
      if($char<10) {
        $str=$str.chr($char+ord('0'));
      }elseif($char<36) {
        $str=$str.chr($char-10+ord('a'));
      }else {
        $str=$str.chr($char-36+ord('A'));
      }
    }
    return $str;
  }
  function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
      // The 'G' modifier is available since PHP 5.1.0
      case 'g':
        $val *= 1024;
      case 'm':
        $val *= 1024;
      case 'k':
        $val *= 1024;
    }

    return $val;
  }
  function get_upload_max_filesize() {
    $memory_limit=return_bytes(ini_get('memory_limit'));
    $post_max_size=return_bytes(ini_get('post_max_size'));
    $upload_max_filesize=return_bytes(ini_get('upload_max_filesize'));
    return min($memory_limit,$post_max_size,$upload_max_filesize);
  }
	function check_cellphone($cellphone) {
		if(strlen($cellphone)<11) {
			return false;
		}
		if(preg_match("/\D/", $cellphone) && strlen($cellphone)==11) {
			return false;
		}
		return true;
	}
	function check_telephone($telephone) {
		if(preg_match("/\d{8}/",$telephone)) {
			return true;
		}else {
			return false;
		}
	}
?>