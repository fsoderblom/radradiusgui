<?php

      if (($_SESSION["section"] == "userdb") && (isset($NAVIGATION[$_SESSION["section"]][$_SESSION["subsection"]]))) {

    ?>

<?php ############################################################################################################################## ?>

<?php

        if (($_SESSION["subsection"] == "edit") && ($VAR["form"]["last_submitted_form"] != "pickuser") && ((count($_SESSION["messages"]["failure"]) == 0))) {

    ?>

<?php

          unset($TEMP["WHERE"]);
          if (in_array($_SESSION["private"]["plain"]["permissions"], array("su"), TRUE)) {
            $TEMP["WHERE"] = "WHERE `permissions` = 'su' OR `permissions` = 'rw' OR `permissions` = 'r'";
          }
          if (in_array($_SESSION["private"]["plain"]["permissions"], array("rw"), TRUE)) {
            $TEMP["WHERE"] = "WHERE `permissions` = 'rw' OR `permissions` = 'r'";
          }
          if (in_array($_SESSION["private"]["plain"]["permissions"], array("r"), TRUE)) {
            $TEMP["WHERE"] = "WHERE `permissions` = 'r'";
          }
          $TEMP[000] = "
                        SELECT *
                        FROM `freeradius_gui_userdb`
                       ".$TEMP["WHERE"]."
                        ORDER BY `freeradius_gui_userdb`.`firstname` ASC
                       ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die($TEMP[000].'Query failed '.mysql_error());

    ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=userdb&amp;subsection=edit"; ?>" method="post">
              <table>
                <tr>
                  <td><label for="userid">Anv&auml;ndare</label></td>
                  <td>
                    <select id="userid" name="userid">
<?php

          while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {

    ?>
                      <option value="<?php echo $TEMP[002]["ID"]; ?>"><?php echo $TEMP[002]["firstname"]." ".$TEMP[002]["lastname"]." (".$TEMP[002]["username"].")"; ?></option>
<?php

          }

    ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>
                    <input class="submit" type="submit" name="form-pickuser" value="<?php echo $VAR["form"]["name_of_submit_button"]["pickuser"]; ?>" />
                    <input class="submit" type="submit" name="form-removeuser" value="<?php echo $VAR["form"]["name_of_submit_button"]["removeuser"]; ?>" onclick="javascript:return confirm('&Auml;r du s&auml;ker p&aring; att du vill ta bort anv&auml;ndaren?');" />
                  </td>
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
                        FROM `freeradius_gui_userdb`
                        ORDER BY `freeradius_gui_userdb`.`firstname` ASC
                       ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());

    ?>
            <table>
              <tr>
                <td class="bold">F&ouml;rnamn</td>
                <td class="bold">Efternamn</td>
                <td class="bold">Anv&auml;ndarnamn</td>
<?php

          if (in_array($_SESSION["private"]["plain"]["permissions"], array("su"), TRUE)) {

    ?>
                <td class="bold">R&auml;ttigheter</td>
                <td class="bold">VLAN ID</td>
<?php

          }

    ?>
              </tr>
<?php

          while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
            if (($_SESSION["private"]["plain"]["permissions"] == "su") || (array_in_array(explode("|", $TEMP[002]["vlandb_vlanid"]), $_SESSION["private"]["plain"]["vlanid"]))) { ## Prevent non-access-VLANs from being listed ##

    ?>
              <tr>
                <td><?php echo $TEMP[002]["firstname"]; ?></td>
                <td><?php echo $TEMP[002]["lastname"]; ?></td>
                <td><?php echo $TEMP[002]["username"]; ?></td>
<?php

              if (in_array($_SESSION["private"]["plain"]["permissions"], array("su"), TRUE)) {

    ?>
                <td><?php echo $TEMP[002]["permissions"]; ?></td>
                <td><?php echo $TEMP[002]["vlandb_vlanid"]; ?></td>
<?php

              }

    ?>
              </tr>
<?php

            }
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

            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=userdb&amp;subsection=".$_SESSION["subsection"]; ?>" method="post">
              <table>
                <tr>
                  <td><label for="firstname">F&ouml;rnamn</label></td>
                  <td><input type="text" name="firstname" id="firstname" value="<?php echo $_SESSION["userdb"]["form"]["firstname"]; ?>" /></td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td><label for="lastname">Efternamn</label></td>
                  <td><input type="text" name="lastname" id="lastname" value="<?php echo $_SESSION["userdb"]["form"]["lastname"]; ?>" /></td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td><label for="username">&Ouml;nskat anv&auml;ndarnamn</label></td>
                  <td><input type="text" name="username" id="username" value="<?php echo $_SESSION["userdb"]["form"]["username"]; ?>" /><input type="hidden" name="userid" value="<?php echo $_SESSION["userdb"]["form"]["userid"]; ?>" /></td>
                  <td><span class="color_red">*</span>&nbsp;&nbsp;4-16 tecken [M&ouml;jliga tecken: a-z, A-Z, 0-9, -, _]</td>
                </tr>
                <tr>
                  <td>GUI-r&auml;ttigheter</td>
                  <td>
                    <input type="radio" name="permissions" value="r" <?php if ($_SESSION["userdb"]["form"]["permissions"] == "r") { echo "checked=\"checked\" "; } ?>/>&nbsp;L&auml;sa<br />
                    <?php if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw"), TRUE)) { ?><input type="radio" name="permissions" value="rw" <?php if ($_SESSION["userdb"]["form"]["permissions"] == "rw") { echo "checked=\"checked\" "; } ?>/>&nbsp;L&auml;sa &amp; skriva<br /><?php } ?>
                    <?php if (in_array($_SESSION["private"]["plain"]["permissions"], array("su"), TRUE)) { ?><input type="radio" name="permissions" value="su" <?php if ($_SESSION["userdb"]["form"]["permissions"] == "su") { echo "checked=\"checked\" "; } ?>/>&nbsp;Administrat&ouml;r<br /><?php } ?>
                  </td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td>VLAN-r&auml;ttigheter</td>
                  <td>
<?php

         // Fetch available VLANs from SQL DB //
          $TEMP[000] = "
                        SELECT *
                        FROM `freeradius_gui_vlandb`
                        ORDER BY `freeradius_gui_vlandb`.`vlanpseudo` ASC
                        ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
            if (($_SESSION["private"]["plain"]["permissions"] == "su") || (in_array($TEMP[002]["vlanid"], $_SESSION["private"]["plain"]["vlanid"]))) { ## Prevent non-access-VLANs from being listed ##
        ?><input type="checkbox" name="vlanid[]" value="<?php echo $TEMP[002]["vlanid"]; ?>"<?php if (((is_array($_SESSION["userdb"]["form"]["vlanid"])) && (in_array($TEMP[002]["vlanid"], $_SESSION["userdb"]["form"]["vlanid"]))) || ($_SESSION["userdb"]["form"]["vlanid"][0] == 'any')) { echo " checked=\"checked\""; } ?> />&nbsp;<?php echo $TEMP[002]["vlanpseudo"]; ?><br /><?php
            }
          }
          unset($TEMP);

    ?>
                  </td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td><label for="password">L&ouml;senord</label></td>
                  <td><input type="password" name="password" id="password" /></td>
                  <td><?php if ($_SESSION["subsection"] == "edit") { ?>(Endast n&ouml;dv&auml;ndigt vid l&ouml;senordsbyte)<?php } else { ?><span class="color_red">*</span><?php } ?></td>
                </tr>
                <tr>
                  <td><label for="retypepassword">Repetera l&ouml;senord</label></td>
                  <td><input type="password" name="retypepassword" id="retypepassword" /></td>
                  <td><?php if ($_SESSION["subsection"] == "edit") { ?><?php } else { ?><span class="color_red">*</span><?php } ?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input class="submit" type="submit" name="form-newuser" value="<?php echo $VAR["form"]["name_of_submit_button"]["newuser"]; ?>" /></td>
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

