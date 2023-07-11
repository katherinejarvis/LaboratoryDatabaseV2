<?php

#call pdo set up
require_once "pdo.php";
session_start();
 If ( isset($_POST['nameexp']) && isset($_POST['benchling_id'])
    && isset($_POST['briefdesc'])
    && isset($_POST['sci'])
    && isset($_POST['prog'])
    && isset($_POST['fulldesc'])
    && isset($_POST['sopexp'])
    && isset($_POST['resultsfiles'])
    && isset($_POST['driveloco'])
  ) {
   ### insert into users (headers) values (placeholders)
   $sql ='INSERT INTO benchlingexp ( benchlingexp_id)
        VALUES ( :benchling_id );
    INSERT INTO briefdesc ( description,fulldesc)
        VALUES ( :briefdesc, :fulldesc);
    INSERT INTO results( data, drivelocation)
        VALUES ( :resultsfiles, :driveloco);
    INSERT INTO experiments (name, benchling_id, desc_id,
            scientist_id, program_ID, dateexperiment, result_id, sop_id)
        VALUES ( :nameexp,
          (SELECT benchling_id
            FROM benchlingexp
            WHERE benchlingexp_id = :benchling_id),
          (SELECT desc_id
            FROM briefdesc
            WHERE description = :briefdesc),
          (SELECT scientist_id
            FROM scientist
            WHERE name = :sci),
          (SELECT program_id
            FROM program
            WHERE name = :prog),
          :dateexp,
          (SELECT result_id
            FROM results
            WHERE drivelocation = :driveloco),
          (SELECT sop_id
              FROM sop
              WHERE name = :sopexp)

        )';

   Echo("<pre>\n".$sql."\n</pre>\n");
   #checks syntax, might blow up
   $stmt = $pdo->prepare($sql);
   $stmt->execute(array(
     ':nameexp'=> $_POST['nameexp'] ,
     ':benchling_id'=> $_POST['benchling_id'],
      ':briefdesc'=> $_POST['briefdesc'],
      ':sci'=> $_POST['sci'],
      ':prog'=> $_POST['prog'],
      ':dateexp'=> $_POST['dateexp'],
      ':fulldesc'=> $_POST['fulldesc'],
      ':sopexp'=> $_POST['sopexp'],
      ':doneexp'=> $_POST['doneexp'],
      ':resultsfiles'=> $_POST['resultsfiles'],
      ':driveloco'=> $_POST['driveloco']
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
$experiment_id= isset($_SESSION['experiment_id']) ? $_SESSION['experiment_id'] : '';
$benchling_id= isset($_SESSION['benchling_id']) ? $_SESSION['benchling_id'] : '';
$nameexp= isset($_SESSION['nameexp']) ? $_SESSION['nameexp'] : '';
$briefdesc= isset($_SESSION['briefdesc']) ? $_SESSION['briefdesc'] : '';
$sci= isset($_SESSION['sci']) ? $_SESSION['sci'] : '';
$prog= isset($_SESSION['prog']) ? $_SESSION['prog'] : '';
$dateexp= isset($_SESSION['dateexp']) ? $_SESSION['dateexp'] : '';
$doneexp= isset($_SESSION['doneexp']) ? $_SESSION['doneexp'] : '';
$fulldesc= isset($_SESSION['fulldesc']) ? $_SESSION['fulldesc'] : '';
$sopexp= isset($_SESSION['sopexp']) ? $_SESSION['sopexp'] : '';
$resultsfiles= isset($_SESSION['resultsfiles']) ? $_SESSION['resultsfiles'] : '';
$drivelocation= isset($_SESSION['drivelocation']) ? $_SESSION['drivelocation'] : '';




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
  <p>Add A New Experiment<p>
  <form method="post">
  <p>Name:<input type="text" name='nameexp' size="40"></p>
  <p>Benchling ID:<input type="text" name='benchling_id'></p>
  <p>Brief Description:<input type="text" name='briefdesc' size="40"></p>
  <p>Full Description:
    <textarea rows = "5" cols="60" name= fulldesc> </textarea></p>

  <!--- add scientist of new experiment - dropdown menu-->
  <tr>
    <p>Scientist:
    <?php
    $query = $pdo->query("SELECT name FROM `scientist` WHERE 1"); // Run your query
    echo '<select name="sci">'; // Open your drop down box
    // Loop through the query results, outputing the options one by one
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.htmlspecialchars($row['name']).'">'.htmlspecialchars($row['name']).'</option>';
    }
    echo '</select>';// Close your drop down box
    ?>
    </p>
  </tr>

  <!--- add SOPs used in new experiment - multiple select-->

  <tr>
    <p>SOP Used:
    <?php
    $query = $pdo->query("SELECT name FROM `SOP` WHERE 1"); // Run your query
    echo '<select name="sopexp" multiple>'; // Open your drop down box
    // Loop through the query results, outputing the options one by one
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.htmlspecialchars($row['name']).'">'.htmlspecialchars($row['name']).'</option>';
    }
    echo '</select>';// Close your drop down box
    ?>
    </p>
  </tr>
  <!--- add program of new experiment - dropdown menu-->
  <tr>
    <p>Program:
    <?php
    $query = $pdo->query("SELECT name FROM `program` WHERE 1"); // Run your query
    echo '<select name="prog">'; // Open your drop down box
    // Loop through the query results, outputing the options one by one
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.htmlspecialchars($row['name']).'">'.htmlspecialchars($row['name']).'</option>';
    }
    echo '</select>';// Close your drop down box
    ?>
    </p>
  </tr>

  <!--- add date of new experiment - calendar to today max -->
  <p>Date Started:<input type="date" name='dateexp' value=getCurrentDateAndTime()
       min="2018-01-01" max=getCurrentDateAndTime()></p>


  <p>Upload Results<input type="file" id='resultsfiles' name='resultsfiles' mulitple ></p>

  <p>Drive Location of Raw Data <input type="text" name='driveloco' size="40"></p>
  <p><input type="submit" value="Add New"/></p>
  </form>
  </body>
