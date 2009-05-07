<?php 
  
      if (($_SESSION["section"] == "logdb") && ($_SESSION["subsection"] == "view")) {
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
          $TEMP[000] = "
                        SELECT *
                        FROM `freeradius_gui_logdb`
                        ORDER BY `freeradius_gui_logdb`.`ID` DESC
                        LIMIT 50
                       ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  
    ?>
              <form method="get" action="">
                <p>S&ouml;k bland loggar: <input type="text" name="searchQuery" id="searchQuery" value="" onkeyup="showResults(this.value);" />&nbsp;<span id="status"></span></p>
              </form>
                
              <div id="dynDiv">
                <table>
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
              </div>
<?php 
  
          unset($TEMP);
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
      }
  
    ?>