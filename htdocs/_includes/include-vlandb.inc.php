<?php 
  
      if (($_SESSION["section"] == "vlandb") && (isset($NAVIGATION[$_SESSION["section"]][$_SESSION["subsection"]]))) {
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
        if (($_SESSION["subsection"] == "edit") && ($VAR["form"]["last_submitted_form"] != "pickvlan") && ((count($_SESSION["messages"]["failure"]) == 0))) {
  
    ?>

<?php 
  
          $TEMP[000] = "
                        SELECT *
                        FROM `freeradius_gui_vlandb`
                        ORDER BY `freeradius_gui_vlandb`.`vlanpseudo` ASC
                       ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  
    ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=vlandb&amp;subsection=edit"; ?>" method="post">
              <table>
                <tr>
                  <td><label for="ID">VLAN</label></td>
                  <td>
                    <select id="ID" name="ID">
<?php 
  
          while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
  
    ?>
                      <option value="<?php echo $TEMP[002]["ID"]; ?>"><?php echo $TEMP[002]["vlanpseudo"]." [".$TEMP[002]["vlanid"]."]"; ?></option>
<?php 
  
          }
  
    ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input class="submit" type="submit" name="form-pickvlan" value="<?php echo $VAR["form"]["name_of_submit_button"]["pickvlan"]; ?>" /></td>
                </tr>
              </table>
            </form>
<?php 
  
          unset($TEMP);
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
        } elseif ($_SESSION["subsection"] == "view") {
  
    ?>

<?php 
  
          $TEMP[000] = "
                        SELECT *
                        FROM `freeradius_gui_vlandb`
                        ORDER BY `freeradius_gui_vlandb`.`vlanid` ASC
                       ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  
    ?>
            <table>
              <tr>
                <td class="bold">VLAN ID</td>
                <td class="bold">Pseudonamn</td>
              </tr>
<?php 
  
          while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
  
    ?>
              <tr>
                <td><?php echo $TEMP[002]["vlanid"]; ?></td>
                <td><?php echo $TEMP[002]["vlanpseudo"]; ?></td>
              </tr>
<?php 
  
          }
  
    ?>
            </table>
<?php 
  
          unset($TEMP);
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
        } else {
  
    ?>

            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]."&amp;subsection=".$_SESSION["subsection"]; ?>" method="post">
              <table>
                <tr>
                  <td><label for="vlanid">VLAN ID</label></td>
                  <td><input type="text" name="vlanid" id="vlanid" value="<?php echo $_SESSION['vlandb']['form']['vlanid']; ?>"<?php if ($_SESSION["subsection"] == 'edit') { ?> class="color_gray" readonly="readonly"<?php } ?> /><input type="hidden" name="ID" value="<?php echo $_SESSION['vlandb']['form']['ID']; ?>" /></td>
                  <td><span class="color_red">*</span>&nbsp;&nbsp;Siffror&nbsp;[M&ouml;jliga tecken: 0-9]</td>
                </tr>
                <tr>
                  <td><label for="vlanpseudo">Pseudonamn</label></td>
                  <td><input type="text" name="vlanpseudo" id="vlanpseudo" value="<?php echo $_SESSION["vlandb"]["form"]["vlanpseudo"]; ?>" /></td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input class="submit" type="submit" name="form-newvlan" value="<?php echo $VAR["form"]["name_of_submit_button"]["newvlan"]; ?>" /></td>
                  <td><span class="color_red">* = Obligatorisk uppgift</span></td>
                </tr>
              </table>
            </form>

<?php 
  
        }
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
      }
  
    ?>

