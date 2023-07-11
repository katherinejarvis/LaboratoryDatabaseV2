<?php

#call pdo set up
require_once "pdo.php";
session_start();
 If ( isset($_POST['namesci']) && isset($_POST['mansci'])
    && isset($_POST['dep'])
    && isset($_POST['sopsci'])) {
   ### insert into users (headers) values (placeholders)
   $sql ='INSERT INTO scientist (name, department_id)
        VALUES ( :namesci,
          (SELECT department_id
            FROM department
            WHERE name = :dep));

            UPDATE scientist  SET manager_id= (SELECT scientist_id
              FROM scientist
              WHERE name = :mansci);

          INSERT INTO scientist_sop (scientist_id, sop_id)
          Values (
            (Select scientist_id
              FROM scientist
              where name=:namesci),
              (Select sop_id
                FROM sop
                where name=:sopsci)
          )';
   #checks syntax, might blow up
   $stmt = $pdo->prepare($sql);
   $stmt->execute(array(
     ':namesci'=> $_POST['namesci'] ,
     ':mansci'=> $_POST['mansci'],
      ':dep'=> $_POST['dep'],
      ':sopsci'=> $_POST['sopsci']
   ));
  $stmt->closeCursor();
}

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
</tr>
<?php

$delete=isset($_SESSION['delete']) ? $_SESSION['delete'] : '';
$experiment_id= isset($_SESSION['namesci']) ? $_SESSION['namesci'] : '';
$benchling_id= isset($_SESSION['manid']) ? $_SESSION['manid'] : '';
$nameexp= isset($_SESSION['dep']) ? $_SESSION['dep'] : '';
$briefdesc= isset($_SESSION['sopsci']) ? $_SESSION['sopsci'] : '';

$stmt = $pdo-> query(
  "SELECT e.name as expname,
    b.benchlingexp_id as benchlingid,
    bd.description as descr,
    s.name as scientist,
    p.name as program,
    e.experiment_id,
    e.dateexperiment
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
      Echo('<form method="post"><input type="hidden" ');
      Echo('name="experiment_id" value=" '.$row['experiment_id'].' ">'."\n");
      Echo('<input type="submit" value="X" name="delete">');
      Echo("\n</form>\n");
    Echo("</td></tr>\n");
  }

  ?>
  </table>
  <p>Add A New Scientist<p>
  <form method="post">
  <p>Name:<input type="text" name='namesci' size="40"></p>
  <tr>
    <p>Manager:
    <?php
    $query = $pdo->query("SELECT name FROM `scientist` WHERE 1"); // Run your query
    echo '<select name="mansci">'; // Open your drop down box
    // Loop through the query results, outputing the options one by one
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.htmlspecialchars($row['name']).'">'.htmlspecialchars($row['name']).'</option>';
    }
    echo '</select>';// Close your drop down box
    ?>
    </p>
  </tr>

  <tr>
    <p>Department:
    <?php
    $query = $pdo->query("SELECT name FROM `Department` WHERE 1"); // Run your query
    echo '<select name="dep">'; // Open your drop down box
    // Loop through the query results, outputing the options one by one
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.htmlspecialchars($row['name']).'">'.htmlspecialchars($row['name']).'</option>';
    }
    echo '</select>';// Close your drop down box
    ?>
    </p>
  </tr>

  <tr>
    <p>SOPs Trained on:
    <?php
    $query = $pdo->query("SELECT name FROM `SOP` WHERE 1"); // Run your query
    echo '<select name="sopsci" multiple>'; // Open your drop down box
    // Loop through the query results, outputing the options one by one
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.htmlspecialchars($row['name']).'">'.htmlspecialchars($row['name']).'</option>';
    }
    echo '</select>';// Close your drop down box
    ?>
    </p>
  </tr>

  <p><input type="submit" value="Add New"/></p>
  </form>
  </body>
