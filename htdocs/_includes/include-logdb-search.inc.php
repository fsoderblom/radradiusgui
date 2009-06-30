<?php 

  function sanitize($value) {
    if (!is_numeric($value)) {
      if (get_magic_quotes_gpc()) { // Handle magic quotes if turned on //
        $value = stripslashes($value);
      }
      if (($result = @mysql_real_escape_string($value)) === FALSE) { // Use mysql_escape_string if not connected to MySQL //
        $value = mysql_escape_string($value);
      } else {
        $value = $result;
      }
    }
    return($value); 
  }
  if (count($_POST)) { // If any $_POST data, sanitize it //
    array_walk($_POST, 'sanitize');
  }
  if (count($_GET)) { // If any $_GET data, sanitize it //
    array_walk($_GET, 'sanitize');
  }

  if (!empty($_GET["q"])) {
    
    require('include-dbcon.inc.php');
    
   // Sanitize $_GET//
    $q = sanitize($_GET["q"]);
    
    $TEMP[004] = preg_split("/[\s,\+\.]+/", $q);
    foreach($TEMP[004] AS $TEMP[005]) {
      if ((!empty($TEMP[005])) && (strlen($TEMP[005]) >= 1 /* Search character limit */)) {
        $TEMP[003] .= "(";
        $TEMP[003] .= "(DATETIME LIKE '%".$TEMP[005]."%') || ";
        $TEMP[003] .= "(username LIKE '%".$TEMP[005]."%') || ";
        $TEMP[003] .= "(type LIKE '%".$TEMP[005]."%') || ";
        $TEMP[003] .= "(category LIKE '%".$TEMP[005]."%') || ";
        $TEMP[003] .= "(description LIKE '%".$TEMP[005]."%')";
        $TEMP[003] .= ") && ";
      }
    }
    $TEMP[003] = substr($TEMP[003], 0, -4); /* Remove last occurance of OR ( || ) */
    
   // Execute SQL query //
    $TEMP[000] = "
                  SELECT *
                  FROM `freeradius_gui_logdb`
                  WHERE ".$TEMP[003]."
                  ORDER BY `freeradius_gui_logdb`.`ID` DESC
                  LIMIT 20
                 ;";
    $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());

    ?>

              <table class="dynDivEntry">
                <tr>
                  <td class="bold">Datum/Tid</td>
                  <td class="bold">Anv&auml;ndarnamn</td>
                  <td class="bold">Typ</td>
                  <td class="bold">Kategori</td>
                  <td class="bold">Beskrivning</td>
                </tr>
<?php 

    while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
  
    ?>
                <tr>
                  <td><?php echo $TEMP[002]["DATETIME"]; ?></td>
                  <td><?php echo $TEMP[002]["username"]; ?></td>
                  <td><?php echo $TEMP[002]["type"]; ?></td>
                  <td><?php echo $TEMP[002]["category"]; ?></td>
                  <td><?php echo $TEMP[002]["description"]; ?></td>
                </tr>
<?php 
  
    }

    ?>
              </table>
<?php 
  
    unset($TEMP);
  }
  
    ?>
