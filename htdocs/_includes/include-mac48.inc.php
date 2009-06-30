<?php 
  
      if (($_SESSION["section"] == "macdb") && (isset($NAVIGATION[$_SESSION["section"]][$_SESSION["subsection"]]))) {
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
        if (($_SESSION["subsection"] == "edit") && ($VAR["form"]["last_submitted_form"] != "pickmac48") && (!(int) $_GET["ID"]) && ((count($_SESSION["messages"]["failure"]) == 0))) {
  
    ?>

<?php 
  
          $TEMP[000] = "
                        SELECT *
                        FROM `freeradius_gui_macdb`
                        ORDER BY `freeradius_gui_macdb`.`mac48` ASC
                       ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  
    ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]."&amp;subsection=".$_SESSION["subsection"]; ?>" method="post">
              <table>
                <tr>
                  <td><label for="ID">MAC-adress</label></td>
                  <td>
                    <select id="ID" name="ID">
<?php 
  
          while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
            if (($_SESSION["private"]["plain"]["permissions"] == "su") || (in_array($TEMP[002]['vlandb_vlanid'], $_SESSION["private"]["plain"]["vlanid"]))) { ## Prevent non-access-VLANs from being listed ##
  
    ?>
                      <option value="<?php echo $TEMP[002]["ID"]; ?>"><?php echo $TEMP[002]["mac48"]; ?></option>
<?php 
  
            }
          }
  
    ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>
                    <input class="submit" type="submit" name="form-pickmac48" value="<?php echo $VAR["form"]["name_of_submit_button"]["pickmac48"]; ?>" />
                    <input class="submit" type="submit" name="form-removemac48" value="<?php echo $VAR["form"]["name_of_submit_button"]["removemac48"]; ?>" onclick="javascript:return confirm('&Auml;r du s&auml;ker p&aring; att du vill ta bort MAC-adressen?');" />
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><p class="description">V&auml;lj 'Byt VLAN' ifall du vill &auml;ndra VLAN f&ouml;r befintlig MAC-adress.<br>&Ouml;nskar du &auml;ndra en felaktigt inmatad MAC-adress skall du v&auml;lja 'Ta bort' och sedan l&auml;gga till MAC-adressen som vanligt.</p></td>
                </tr>
              </table>
            </form>
<?php 
  
          unset($TEMP);
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
        } elseif (($_SESSION["subsection"] == "add") || ($_SESSION["subsection"] == "edit")) {
  
    ?>

<?php 
  
         // Fetch information about selected entry from database //
          if ((int) $_GET["ID"]) {
            $_POST["ID"] = $_GET["ID"];
          }
          if ((int) $_POST["ID"]) { ## Convert $_POST["ID"] to (int) and proceed if TRUE ##
            $TEMP[000] = "
                          SELECT *
                          FROM `freeradius_gui_macdb`
                          WHERE `ID` = '".$_POST["ID"]."'
                          LIMIT 1
                          ;";
            $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
            while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
              $_SESSION["macdb"]["form"]["ID"] = $TEMP[002]["ID"];
              $_SESSION["macdb"]["form"]["mac48"] = $TEMP[002]["mac48"];
              $_SESSION["macdb"]["form"]["mac48_original"] = $TEMP[002]["mac48"];
              $_SESSION["macdb"]["form"]["vlanid"] = $TEMP[002]["vlandb_vlanid"];
            }
          }
          unset($TEMP);
  
    ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]."&amp;subsection=".$_SESSION["subsection"]; ?>" method="post">
              <table>
                <tr>
                  <td><label for="mac48">MAC-adress</label><span class="color_red">&nbsp;*</span></td>
                  <td><input type="text" name="mac48" id="mac48" value="<?php echo $_SESSION['macdb']['form']['mac48']; ?>" maxlength="12"<?php if ($_SESSION["subsection"] == "edit") { ?> class="color_gray" readonly<?php } ?>><input type="hidden" name="ID" value="<?php echo $_SESSION["macdb"]["form"]["ID"]; ?>"></td>
                </tr>
                <tr>
                  <td><label for="vlanid">Tillh&ouml;rande VLAN</label><span class="color_red">&nbsp;*</span></td>
                  <td>
                    <select name="vlanid" id="vlanid">
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
                    ?><option value="<?php echo $TEMP[002]["vlanid"]; ?>"<?php if ($TEMP[002]["vlanid"] == $_SESSION["macdb"]["form"]["vlanid"]) { echo " selected"; } ?>><?php echo $TEMP[002]["vlanpseudo"]; ?> [<?php echo $TEMP[002]["vlanid"]; ?>]</option><?php 
            }
          }
          unset($TEMP);
  
    ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><span class="color_red">* = Obligatorisk uppgift</span></td>
<?php 
  
          if ($_SESSION["subsection"] == "edit") {
  
    ?> 
                  <td><p class="description">V&auml;lj nytt VLAN samt 'Spara' ifall du vill &auml;ndra VLAN f&ouml;r befintlig MAC-adress.<br>&Ouml;nskar du &auml;ndra en felaktigt inmatad MAC-adress skall du v&auml;lja 'Redigera befintlig MAC adress' och sedan 'Ta bort' och d&auml;refter l&auml;gga till MAC-adressen som vanligt.</p></td>
<?php 
  
          } else {
  
    ?>
                  <td><p class="description">Vid inmatning av enstaka MAC-adresser, anger du adressen med 12 tecken inneh&aring;llandes siffror och bokst&auml;verna A till F (utan skiljetecken).<br>Exempel: <span class="example">0123456789AB</span></p></td>
<?php 
  
          }
  
    ?>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input class="submit" type="submit" name="form-newmac48" value="<?php echo $VAR["form"]["name_of_submit_button"]["newmac48"]; ?>" /></td>
                </tr>
              </table>
            </form>

<?php ############################################################################################################################## ?>

<?php 
  
        } elseif ($_SESSION["subsection"] == "view") {
  
    ?>

<?php 
  
          if (!empty($_POST['sortvlan'])) {
            $_POST['sortvlan'] = (int) sanitize($_POST['sortvlan']);
            $TEMP['sql_where'] = '&& (`freeradius_gui_vlandb`.`vlanid` = '.$_POST['sortvlan'].')';
          }
          
          if ($_GET['s_ma'] == 'd') {
            $TEMP['sql_order_by'] = 'ORDER BY `freeradius_gui_macdb`.`mac48` DESC';
          } elseif ($_GET['s_ma'] == 'a') {
            $TEMP['sql_order_by'] = 'ORDER BY `freeradius_gui_macdb`.`mac48` ASC';
          } else {
            $TEMP['sql_order_by'] = 'ORDER BY `freeradius_gui_macdb`.`mac48` ASC';
          }
          $TEMP[000] = "
                        SELECT `freeradius_gui_macdb`.`ID`, `freeradius_gui_macdb`.`vlandb_vlanid`, `freeradius_gui_vlandb`.`vlanpseudo`, `freeradius_gui_macdb`.`mac48`
                        FROM `freeradius_gui_vlandb`, `freeradius_gui_macdb`
                        WHERE (`freeradius_gui_vlandb`.`vlanid` = `freeradius_gui_macdb`.`vlandb_vlanid`)
                       ".$TEMP['sql_where']."
                       ".$TEMP['sql_order_by']."
                       ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  
    ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]."&amp;subsection=".$_SESSION["subsection"]; ?>" id="sortform" method="post">
              <table>
                <tr>
                  <td class="bold"><a href="<?php echo $_SERVER['PHP_SELF']."?".htmlentities($_SERVER['QUERY_STRING']); if (empty($_GET['s_ma']) || ($_GET['s_ma'] == 'a')) { echo '&amp;s_ma=d'; } else { echo '&amp;s_ma=a'; } ?>">MAC-adress</a></td>
                  <td class="bold">
                    <select name="sortvlan" class="td_select" onchange="javascript:document.forms['sortform'].submit();">
                      <option>VLAN</option>
<?php 
  
         // Fetch available VLANs from SQL DB //
          $TEMP['vlan'][000] = "
                        SELECT *
                        FROM `freeradius_gui_vlandb`
                        ORDER BY `freeradius_gui_vlandb`.`vlanpseudo` ASC
                        ;";
          $TEMP['vlan'][001] = mysql_query($TEMP['vlan'][000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          while ($TEMP['vlan'][002] = mysql_fetch_assoc($TEMP['vlan'][001])) {
            if (($_SESSION["private"]["plain"]["permissions"] == "su") || (in_array($TEMP['vlan'][002]["vlanid"], $_SESSION["private"]["plain"]["vlanid"]))) { ## Prevent non-access-VLANs from being listed ##
                    ?><option value="<?php echo $TEMP['vlan'][002]["vlanid"]; ?>"<?php if ($TEMP['vlan'][002]["vlanid"] == $_POST['sortvlan']) { echo " selected"; } ?>><?php echo $TEMP['vlan'][002]["vlanpseudo"]; ?>&nbsp;</option><?php 
            }
          }
          unset($TEMP['vlan']);
  
    ?>
                    </select>
                  </td>
                  <td class="bold">Testa MAC-adress</td>
<?php 
  
          if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw"), TRUE)) {
  
    ?>
                  <td class="bold">Byt VLAN f&ouml;r MAC-adress</td>
<?php 
  
          }
  
    ?>
                </tr>
<?php 
  
          while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
            if (($_SESSION["private"]["plain"]["permissions"] == "su") || (in_array($TEMP[002]["vlandb_vlanid"], $_SESSION["private"]["plain"]["vlanid"]))) { ## Prevent non-access-VLANs from being listed ##
  
    ?>
                <tr>
                  <td><?php echo $TEMP[002]["mac48"]; ?></td>
                  <td><?php echo $TEMP[002]["vlanpseudo"]; ?></td>
                  <td><a href="<?php echo $_SERVER['PHP_SELF']."?".htmlentities($_SERVER['QUERY_STRING'])."&amp;valmac=".$TEMP[002]["mac48"]; ?>">Testa MAC-adress</a></td>
<?php 
  
          if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw"), TRUE)) {
  
    ?>
                  <td><a href="<?php echo $_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]."&amp;subsection=edit&amp;ID=".$TEMP[002]["ID"]; ?>">Byt VLAN</a></td>
<?php 
  
          }
  
    ?>
                </tr>
<?php 
  
            }
          }
  
    ?>
              </table>
            </form>
<?php 
  
          unset($TEMP);
  
    ?>

<?php ############################################################################################################################## ?>

<?php 
  
        } elseif ($_SESSION["subsection"] == "import") {
  
    ?>

<?php 
  
          if (!empty($_POST["importmac48"])) {
            $_SESSION["macdb"]["form"] = grep_mac_address($_POST['importmac48'], $_POST['defaultvlanid']);
          }
  
    ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]."&amp;subsection=".$_SESSION["subsection"]; ?>" method="post">
              <table>
<?php 

          if ((count($_SESSION["macdb"]["form"]["mac48"]) == 0) || (is_array($_SESSION["macdb"]["form"]["duplicates"])) || (((empty($_SESSION["macdb"]["form"]["mac48"])) && (count($_SESSION["messages"]["failure"])) == 0))) {
  
    ?>
                <tr>
                  <td colspan="2"><label for="importmac48">Importera flera MAC-adresser</label><br><textarea rows="9" name="importmac48" id="importmac48" style="width: 418px;"><?php if ((count($_SESSION['macdb']['form']['mac48']) == 0) && (count($_SESSION['macdb']['form']['duplicates']['mac48']) == 0)) { echo $_POST["importmac48"]; } ?><?php if (is_array($_SESSION["macdb"]["form"]["duplicates"])) { echo $TEMP['text_string'] = "Dubletter:"; echo "\n".str_repeat('=', strlen(html_entity_decode($TEMP['text_string'])))."\n"; foreach (array_combine($_SESSION['macdb']['form']['duplicates']['vlanid'], $_SESSION['macdb']['form']['duplicates']['mac48']) AS $TEMP['key']['array_combine'] => $TEMP['value']['array_combine']) { echo $TEMP['value']['array_combine'].",".$TEMP['key']['array_combine']."\n"; } echo "\n"; ?><?php if (!empty($_SESSION['macdb']['form']['mac48'])) { echo $TEMP['text_string'] = "Funna MAC-adresser:"; echo "\n".str_repeat('=', strlen(html_entity_decode($TEMP['text_string'])))."\n"; foreach (array_combine($_SESSION['macdb']['form']['mac48'], $_SESSION['macdb']['form']['vlanid']) AS $TEMP['key']['array_combine'] => $TEMP['value']['array_combine']) { echo $TEMP['key']['array_combine'].",".$TEMP['value']['array_combine']."\n"; } } echo "\n"; } ?></textarea></td>
                </tr>
                <tr>
                  <td colspan="2">
                    <label for="defaultvlanid">MAC-adresser utan specificerat VLAN skall tillh&ouml;ra:</label><br>
  
                    <select name="defaultvlanid">
                      <option value="">&ndash;&nbsp;V&auml;lj VLAN&nbsp;&ndash;</option>
<?php 
  
              foreach ($_SESSION["vlandb"]["vlanid"]["vlanid"] AS $TEMP['key']['session_vlanid'] => $TEMP['value']['session_vlanid']) {
                if (in_array($TEMP['key']['session_vlanid'], $_SESSION["private"]["plain"]["vlanid"]) || ($_SESSION["private"]["plain"]["permissions"] == "su")) {
  
    ?>
                      <option value="<?php echo $TEMP['key']['session_vlanid']; ?>"><?php echo $TEMP['value']['session_vlanid'];?></option>
<?php 
  
                }
              }
  
    ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td colspan="2"><p class="description">Vid importering av flera MAC-adresser, kan du klistra in i l&ouml;ptext alternativt kommaseparerad text i f&auml;ltet ovan.<br>Anger du kommaseparerad text, skall du f&ouml;lja detta format:<br>Exempel: <span class="example">0123456789AB,nnnnn</span> (varav nnnnn &auml;r &ouml;nskat VLAN ID).</span></p></td>
                </tr>
                <tr>
                  <td colspan="2"><p class="description">Verktyget hittar MAC-adresser med f&ouml;ljande format:<br>Exempel: <span class="example">01:23:45:67:89:ab</span>, <span class="example">0123:4567:89ab</span>, <span class="example">0x0123456789ab</span> och <span class="example">0123456789ab</span> med f&ouml;ljande skiljetecken: <span class="bold">: , -</span></p></td>
                </tr>
<?php 
  
          } else {
  
    ?>

<?php 
  
            foreach (array_combine($_SESSION["macdb"]["form"]["mac48"], $_SESSION["macdb"]["form"]["vlanid"]) AS $TEMP['key']['session_mac48'] => $TEMP['value']['session_mac48']) {
  
    ?>
                <tr>
                  <td><input type="text" name="mac48[]" value="<?php echo $TEMP['key']['session_mac48']; ?>" readonly="readonly" />
                    <select name="vlanid[]">
                      <option value="">&ndash;&nbsp;V&auml;lj VLAN&nbsp;&ndash;</option>
<?php 
  
              foreach ($_SESSION["vlandb"]["vlanid"]["vlanid"] AS $TEMP['key']['session_vlanid'] => $TEMP['value']['session_vlanid']) {
                if (in_array($TEMP['key']['session_vlanid'], $_SESSION["private"]["plain"]["vlanid"]) || ($_SESSION["private"]["plain"]["permissions"] == "su")) {
  
    ?>
                      <option value="<?php echo $TEMP['key']['session_vlanid']; ?>"<?php if ($TEMP['value']['session_mac48'] == $TEMP['key']['session_vlanid']) { echo ' selected="selected"'; } ?>><?php echo $TEMP['value']['session_vlanid'];?></option>
<?php 
  
                }
              }
  
    ?>
                    </select>
                  </td>
                </tr>
<?php 
  
            }
            unset($TEMP);
  
    ?>
                <tr>
                  <td colspan="2"><input type="checkbox" name="overwrite" id="overwrite" value="1" checked="checked" /><label for="overwrite">&nbsp;Skriv &ouml;ver befintliga MAC-adresser</label></td>
                </tr>
                <tr>
                  <td colspan="2"><p class="description">V&auml;ljer du att skriva &ouml;ver befintliga MAC-adresser, kommer verktyget att ers&auml;tta befintlig MAC-adress samt tillh&ouml;rande VLAN med de uppgifter du anger ovan.</p></td>
                </tr>
<?php 
  
          }
  
    ?>
                <tr>
                  <td colspan="2"><input class="submit" type="submit" name="form-importmac48" value="<?php echo $VAR["form"]["name_of_submit_button"]["importmac48"]; ?>" /></td>
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