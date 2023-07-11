<?php
session_start();
#call pdo set up
require_once "pdo.php";
if ( isset($_POST['delete']) && isset($_POST['experiment_id'])){
  $sql = "DELETE FROM experiments WHERE experiment_id = :zip";
#  Echo "<pre>\n$sql\n</pre>\n";
  $stmt = $pdo-> prepare($sql);
  $stmt->execute(array(':zip'=> $_POST['experiment_id']));
  header("Location: Master.php");
  return;
}


if ( isset($_POST['details']) && isset($_POST['experiment_id'])){
  $sql =  "SELECT e.name as expname,
          b.benchlingexp_id as benchlingid,
          bd.fulldesc as descr,
          s.name as scientist,
          p.name as program,
          dateexperiment as dateexp,
          d.name as depname,
          r.data as resultsdata,
          r.drivelocation as resultspath,
          sp.name as sopused
        FROM Experiments e
        Left Join Benchlingexp b on b.benchling_id = e.benchling_id
        Left Join briefdesc bd on bd.desc_id = e.desc_id
        Left Join scientist s on s.scientist_id = e.scientist_id
        Left Join department d on d.department_id = s.department_id
        Left Join results r on r.result_id = e.result_id
        Left Join sop sp on sp.sop_id = e.sop_id
        Left Join program p on p.program_id = e.program_id
        WHERE e.experiment_id=:experiment_id  ";

    $stmtdet = $pdo-> prepare($sql);
    $stmtdet->execute(
      array(':experiment_id'=> $_POST['experiment_id']));
    While ( $row = $stmtdet->fetch(PDO::FETCH_ASSOC) ) {
      Echo"<head></head><body>";
        Echo "<p> Name of Experiment: ";
        Echo($row['expname']);
        Echo "</p>";
        Echo "<p> Benchling ID: ";
        Echo($row['benchlingid']);
        Echo "</p>";
        Echo "<p> Full Description: ";
        Echo($row['descr']);
        Echo "</p>";
        Echo "<p> Scientist: ";
        Echo($row['scientist']);
        Echo "</p>";
        Echo "<p> SOPs Used: ";
        Echo($row['sopused']);
        Echo "</p>";
        Echo "<p> Program: ";
        Echo($row['program']);
        Echo "</p>";
        Echo "<p> Date Started: ";
        Echo($row['dateexp']);
        Echo "</p>";
        Echo "<p> Department: ";
        Echo($row['depname']);
        Echo "</p>";
        Echo "<p> Results Data: ";
        Echo($row['resultsdata']);
        Echo "</p>";
        Echo "<p> Results Path: ";
        Echo($row['resultspath']);
        Echo "</p>";
    }

  }
//department, SOP, manager, results (done, files, location), full description, required supplies)
?>

<html><head></head>
<body>
  <a href="http://localhost/LabDB/Master.php">Home </a>
  &emsp;
  <a href="http://localhost/LabDB/UpdateExperiments.php">Update Experiments </a>
  &emsp;
  <a href="http://localhost/LabDB/UpdateScientists.php">Update Scientists </a>
<table border="1">
<tr>
<th>Experiment</th>
<th>Benchling ID</th>
<th>Description</th>
<th>Scientist</th>
<th>Program</th>
<th>ID</th>
<th>Date</th>
<th>Delete</th>
<th>Details</th>
</tr>
<?php
 $delete=isset($_SESSION['delete']) ? $_SESSION['delete'] : '';
 $experiment_id= isset($_SESSION['experiment_id']) ? $_SESSION['experiment_id'] : '';


$stmt = $pdo-> query(
  "SELECT e.name as expname,
    b.benchlingexp_id as benchlingid,
    bd.description as descr,
    s.name as scientist,
    p.name as program,
    experiment_id,
    dateexperiment
  FROM Experiments e
  Left Join Benchlingexp b on b.benchling_id = e.benchling_id
  Left Join briefdesc bd on bd.desc_id = e.desc_id
  Left Join scientist s on s.scientist_id = e.scientist_id
  Left Join program p on p.program_id = e.program_id");

While ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  Echo "<tr><td>";
  Echo ($row['expname']);
  Echo "</td><td>";
  Echo ($row['benchlingid']);
  echo("</td><td>");
  Echo ($row['descr']);
  echo("</td><td>");
  Echo ($row['scientist']);
  echo("</td><td>");
  Echo ($row['program']);
  echo("</td><td>");
  Echo ($row['experiment_id']);
  echo("</td><td>");
  Echo ($row['dateexperiment']);
  echo("</td><td>");

  // delete form
  Echo('<form method="post"><input type="hidden" ');
  Echo('name="experiment_id" value=" '.$row['experiment_id'].' ">'."\n");
  Echo('<input type="submit" value="X" name="delete">');
  echo("</td><td>");

  //details form
  Echo('<form method="post"><input type="hidden" ');
  Echo('name="experiment_id" value=" '.$row['experiment_id'].' ">'."\n");
  Echo('<input type="submit" value="..." name="details">');

    Echo("\n</form>\n");
  Echo("</td></tr>\n");
}
