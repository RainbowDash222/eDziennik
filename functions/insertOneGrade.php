<?php
session_start();
if (!isset($_SESSION["user"]) || (isset($_SESSION["user"]) && $_SESSION['user']['rank'] !== "teacher")) {
  header("Location: http://{$_SERVER['HTTP_HOST']}/");
  exit();
}

$isEmpty = false;
$isStudentOk = true;
$isCategoryOk = true;
$isGradeOk = true;

foreach ($_POST as $input => $value) {
  if ($value === null && $input !== "description") {
    $isEmpty = true;
  }
}

if (!in_array($_POST['student'], array_column($_SESSION['students'], "id"))) {
  $isStudentOk = false;
}
if (!in_array($_POST['category'], array_column($_SESSION['categories'], "id"))) {
  $isCategoryOk = false;
}
if ($_POST['grade'] < 1 || $_POST['grade'] > 6) {
  $isGradeOk = false;
}


if (!$isEmpty && $isCategoryOk && $isGradeOk && $isStudentOk) {
  require "../db.php";
  $conn = connectToDB();

  $sql = <<<SQL
  INSERT INTO grades
  (`student_id`,
  `teacher_id`,
  `subject_id`,
  `category_id`,
  `grade`,
  `description`,
  `date`) 
  VALUES 
  ({$_POST['student']},
  {$_SESSION['user']['id']},
  {$_SESSION['subject']['id']},
  {$_POST['category']},
  {$_POST['grade']},
  "{$_POST['description']}",
  CURRENT_TIMESTAMP())
SQL;

  mysqli_query($conn, $sql);
}
header("Location: http://{$_SERVER['HTTP_HOST']}/teacher/grades.php");
