<?php

 // Initialize session data //
  session_start();

// ### [ VARIABLES ] ############################################################# //

  $VAR = array(
           "form" => array(
           "name_of_submit_button" => array(
                                        "login" => "Logga in",
                                        "passwd" => "Spara",
                                        "newuser" => "Spara",
                                        "newvlan" => "Spara",
                                        "newmac48" => "Spara",
                                        "edituser" => "Redigera",
                                        "editvlan" => "Redigera",
                                        "editmac48" => "Redigera",
                                        "pickuser" => "Redigera",
                                        "pickvlan" => "Redigera",
                                        "pickmac48" => "Byt VLAN",
                                        "importmac48" => "Importera",
                                        "removeuser" => "Ta bort",
                                        "removemac48" => "Ta bort",
                      ),
                    ),
           'radius' => array(
                         'shared_secret' => 'testing123', ## Shared secret with RADIUS server ##
                       ),
           "whitelist" => array(
                            0 => "/[^A-Za-z0-9_\-]/", ## Used for PASSWORD ##
                            1 => "/[^A-Fa-f0-9]/", ## Used for MAC48 ##
                          ),
           "DEBUG" => FALSE,
         );

 // Save user path in SESSION //
  if (isset($_GET["section"])) {
    $_SESSION["section"] = sanitize($_GET["section"]);
  }
  if (isset($_GET["subsection"])) {
    $_SESSION["subsection"] = sanitize($_GET["subsection"]);
  }

 // Define navigation structure //
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su"), TRUE)) {
    $NAVIGATION["vlandb"]["view"] = "Visa befintliga VLAN";
  }
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su"), TRUE)) {
    $NAVIGATION["vlandb"]["edit"] = "Redigera befintligt VLAN";
    $NAVIGATION["vlandb"]["add"] = "L&auml;gg till nytt VLAN";
  }
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw", "r"), TRUE)) {
    $NAVIGATION["userdb"]["view"] = "Visa befintliga anv&auml;ndare";
  }
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw"), TRUE)) {
    $NAVIGATION["userdb"]["edit"] = "Redigera befintlig anv&auml;ndare";
  }
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw"), TRUE)) {
    $NAVIGATION["userdb"]["add"] = "L&auml;gg till ny anv&auml;ndare";
  }
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw", "r"), TRUE)) {
    $NAVIGATION["macdb"]["view"] = "Visa befintliga MAC-adresser";
  }
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su", "rw"), TRUE)) {
    $NAVIGATION["macdb"]["edit"] = "Redigera befintlig MAC-adress";
    $NAVIGATION["macdb"]["add"] = "L&auml;gg till enstaka MAC-adresser";
    $NAVIGATION["macdb"]["import"] = "Importera flera MAC-adresser";
  }
  if (in_array($_SESSION["private"]["plain"]["permissions"], array("su"), TRUE)) {
    $NAVIGATION["logdb"]["view"] = "Visa loggar";
  }
    $NAVIGATION_TEXT["vlandb"] = "Administrera VLAN";
    $NAVIGATION_TEXT["userdb"] = "Administrera GUI-anv&auml;ndare";
    $NAVIGATION_TEXT["macdb"] = "Administrera MAC-adresser";
    $NAVIGATION_TEXT["logdb"] = "Administrera loggar";

  switch (TRUE) {
    case ($VAR["form"]["name_of_submit_button"]["login"] == sanitize($_POST["form-login"])):
      $VAR["form"]["last_submitted_form"] = "login";
      break;
    case ($VAR["form"]["name_of_submit_button"]["passwd"] == sanitize($_POST["form-passwd"])):
      $VAR["form"]["last_submitted_form"] = "passwd";
      break;
    case ($VAR["form"]["name_of_submit_button"]["newuser"] == sanitize($_POST["form-newuser"])):
      $VAR["form"]["last_submitted_form"] = "newuser";
      break;
    case ($VAR["form"]["name_of_submit_button"]["newvlan"] == sanitize($_POST["form-newvlan"])):
      $VAR["form"]["last_submitted_form"] = "newvlan";
      break;
    case ($VAR["form"]["name_of_submit_button"]["newmac48"] == sanitize($_POST["form-newmac48"])):
      $VAR["form"]["last_submitted_form"] = "newmac48";
      break;
    case ($VAR["form"]["name_of_submit_button"]["edituser"] == sanitize($_POST["form-edituser"])):
      $VAR["form"]["last_submitted_form"] = "edituser";
      break;
    case ($VAR["form"]["name_of_submit_button"]["editvlan"] == sanitize($_POST["form-editvlan"])):
      $VAR["form"]["last_submitted_form"] = "editvlan";
      break;
    case ($VAR["form"]["name_of_submit_button"]["editmac48"] == sanitize($_POST["form-editmac48"])):
      $VAR["form"]["last_submitted_form"] = "editmac48";
      break;
    case ($VAR["form"]["name_of_submit_button"]["pickuser"] == sanitize($_POST["form-pickuser"])):
      $VAR["form"]["last_submitted_form"] = "pickuser";
      break;
    case ($VAR["form"]["name_of_submit_button"]["pickvlan"] == sanitize($_POST["form-pickvlan"])):
      $VAR["form"]["last_submitted_form"] = "pickvlan";
      break;
    case ($VAR["form"]["name_of_submit_button"]["pickmac48"] == sanitize($_POST["form-pickmac48"])):
      $VAR["form"]["last_submitted_form"] = "pickmac48";
      break;
    case ($VAR["form"]["name_of_submit_button"]["importmac48"] == sanitize($_POST["form-importmac48"])):
      $VAR["form"]["last_submitted_form"] = "importmac48";
      break;
    case ($VAR["form"]["name_of_submit_button"]["removemac48"] == sanitize($_POST["form-removemac48"])):
      $VAR["form"]["last_submitted_form"] = "removemac48";
      break;
    case ($VAR["form"]["name_of_submit_button"]["removeuser"] == sanitize($_POST["form-removeuser"])):
      $VAR["form"]["last_submitted_form"] = "removeuser";
      break;
    default:
      unset($VAR["form"]["last_submitted_form"]);
      break;
  }

// ### [ CLASSES & FUNCTIONS ] ################################################### //

  function difference_in_time($INPUT001, $INPUT002) {
    return (date('U', strtotime($INPUT002)) - date('U', strtotime($INPUT001)));
  }

  function grep_mac_address($INPUT001 /* Text string to find mac addresses within */, $INPUT002 = 1 /* VLAN ID to give unassigned MAC addresses */) {
    preg_match_all('/\b(?:0x)?((?:[[:xdigit:]]{2}[-.:]?){6}),?([[:digit:]]{2,5})?\b/', $INPUT001, $TEMP['preg_match_all']);
    $TEMP['return']['mac48'] = array();
    foreach ($TEMP['preg_match_all'][1] AS $TEMP['key']['preg_match_all'] => $TEMP['value']['preg_match_all']) {
      $TEMP['value']['preg_match_all'] = str_replace(array('-', '.', ':'), '', strtolower($TEMP['value']['preg_match_all']));
     // Set VLAN ID //
      if (empty($TEMP['preg_match_all'][2][$TEMP['key']['preg_match_all']])) { ## Proceed if VLAN ID (,#####) was not set for MAC address ##
        $TEMP['vlanid'] = $INPUT002; ## Use 'Default VLAN ID' for unassigned MAC addresses ##
      } else { ## Proceed if VLAN ID (,#####) was set for MAC address ##
        if ($_SESSION["private"]["plain"]["permissions"] == "su") { ## Proceed if user is Super User ##
          if (in_array($TEMP['preg_match_all'][2][$TEMP['key']['preg_match_all']], $_SESSION['vlandb']['vlanid']['id'])) { ## Check if proposed VLAN ID exists in database ##
            $TEMP['vlanid'] = $TEMP['preg_match_all'][2][$TEMP['key']['preg_match_all']];
          } else {
            $TEMP['vlanid'] = $INPUT002; ## Use 'Default VLAN ID' for unassigned MAC addresses ##
          }
        } else { ## Proceed if user is Read & Write || Read ##
          if (in_array($TEMP['preg_match_all'][2][$TEMP['key']['preg_match_all']], $_SESSION["private"]["plain"]["vlanid"])) { ## Check if user has access to proposed VLAN ID ##
            $TEMP['vlanid'] = $TEMP['preg_match_all'][2][$TEMP['key']['preg_match_all']];
          } else {
            $TEMP['vlanid'] = $INPUT002; ## Use 'Default VLAN ID' for unassigned MAC addresses ##
          }
        }
      }
     // Check if MAC address is duplicate //
      if (!in_array($TEMP['value']['preg_match_all'], $TEMP['return']['mac48'])) {
        $TEMP['return']['mac48'][] = $TEMP['value']['preg_match_all'];
        $TEMP['return']['vlanid'][] = $TEMP['vlanid'];
      } else {
        $TEMP['return']['duplicates']['mac48'][] = $TEMP['value']['preg_match_all'];
        $TEMP['return']['duplicates']['vlanid'][] = $TEMP['vlanid'];
      }
    }
    if (is_array($TEMP['return']['duplicates']['mac48'])) {
      foreach (array_combine($TEMP['return']['duplicates']['vlanid'], $TEMP['return']['duplicates']['mac48']) AS $TEMP['key']['return_duplicates'] => $TEMP['value']['return_duplicates']) {
        $TEMP['key']['array_search'] = array_search($TEMP['value']['return_duplicates'], $TEMP['return']['mac48']);
        $TEMP['return']['duplicates']['mac48'][] = $TEMP['value']['return_duplicates'];
        $TEMP['return']['duplicates']['vlanid'][] = $TEMP['return']['vlanid'][$TEMP['key']['array_search']];
        unset($TEMP['return']['mac48'][$TEMP['key']['array_search']]);
        unset($TEMP['return']['vlanid'][$TEMP['key']['array_search']]);
      }
    }
    return $TEMP['return'];
    unset($TEMP);
  }

 // Run radtest to test selected MAC address //
  function validate_mac_address($INPUT001 /* MAC-address to validate against RADIUS */, $INPUT002 /* Shared secret with RADIUS */) {
    global $DB;
    preg_match('/\b[[:xdigit:]]{12}\b/', strtolower(sanitize($INPUT001)), $TEMP['match']); ## Match the first occurance of a valid MAC-address ##
    exec('/usr/bin/radtest '.escapeshellarg($TEMP['match'][0]).' '.escapeshellarg($TEMP['match'][0]).' 127.0.0.1 0 '.escapeshellarg($INPUT002), $TEMP['response']);
    if (eregi('rad_recv: Access-Accept', implode($TEMP['response'], '\n'))) {
      preg_match('/Tunnel-Private-Group-Id:0 = "([[:digit:]]{2,5})"/', implode($TEMP['response'], '\n'), $TEMP['TPG']);
      write_to_log('NOTICE', 'VALIDATE', 'MAC address '.addslashes($TEMP['match'][0]).' passed validation ('.$TEMP['TPG'][1].').');
      $_SESSION["messages"]["success"][] = addslashes($TEMP['match'][0]).' godk&auml;ndes och tillh&ouml;r VLAN: <span class="color_blue">'.$_SESSION['vlandb']['vlanid']['vlanid'][$TEMP['TPG'][1]].' ['.$TEMP['TPG'][1].']</span>.';
    } else {
      write_to_log('ERROR', 'VALIDATE', 'MAC address '.addslashes($TEMP['match'][0]).' failed validation.');
      $_SESSION["messages"]["failure"][] = addslashes($TEMP['match'][0]).' godk&auml;ndes inte.';
    }
    unset($TEMP);
    header("Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]."&subsection=".$_SESSION["subsection"]."&s_ma=".sanitize($_GET['s_ma']));
    exit;
  }

  function match_regexp($INPUT001 /* Regular expression to use */, $INPUT002 /* Text to search within */, $INPUT003 = FALSE /* Return match count */) {
    $VARIABLE001["match_count"] = preg_match_all($INPUT001, $INPUT002, $VARIABLE001["matches"]);
    if ($INPUT003 === TRUE) {
      return $VARIABLE001["match_count"];
    } else {
      return $VARIABLE001;
    }
  }

  function validate_hash($INPUT001 /* Plain text */, $INPUT002 /* Hashed text */) {
    if (sha1(count_chars($INPUT001, 3).$INPUT001) == $INPUT002) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function create_hash($INPUT001 /* Plain text to be hashed */, $INPUT002 = 40 /* Hash length */) {
    $TEMP[000] = sha1(count_chars($INPUT001, 3).$INPUT001);
    if (is_int($INPUT002) && ($INPUT002 != 40)) {
      $TEMP[000] = substr($TEMP[000], 0, $INPUT002);
    }
    return $TEMP[000];
  }

  function write_to_log($INPUT001 /* Type of log message [ERROR, WARNING, NOTICE] */, $INPUT002 /* Category of log message */, $INPUT003 /* Descriptive log message */, $INPUT004 = FALSE /* OPTIONAL: Stop the execution and logout */) {
    global $DB;
    $TEMP[000] = sprintf('
                          INSERT INTO `freeradius`.`freeradius_gui_logdb` (
                            `ID`,
                            `MODIFIED`,
                            `HOST`,
                            `DATETIME`,
                            `username`,
                            `type`,
                            `category`,
                            `description`,
                            `userdb_id`
                          )
                          VALUES (
                            NULL,
                            NOW( ),
                            "%s",
                            "%s",
                            "%s",
                            "%s",
                            "%s",
                            "%s",
                             %d
                          );',
                            $_SERVER["REMOTE_ADDR"],
                            date("Y-m-d H:i:s"),
                            $_SESSION["private"]["plain"]["username"],
                            $INPUT001,
                            $INPUT002,
                            $INPUT003,
                            $_SESSION["private"]["plain"]["userid"]
                          );
    $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
    if ($INPUT004 === TRUE) {
      log_out_of_gui();
    }
  }

  function array_in_array($needles, $haystack) {
    foreach ($needles as $needle) {
      if ( in_array($needle, $haystack) ) {
        return true;
      }
    }
    return false;
  }

  function manage_radgroupreply($INPUT001 /* Action [INSERT] */, $INPUT002 /* VLAN ID */) {
    global $DB;

   // Insert into RADIUS database //
    if ($INPUT001 == 'INSERT') {
      for ($i = 1; $i <= 3; $i++) {

        switch (TRUE) {
          case ($i === 1):
            $VARIABLE001 = 'Tunnel-Type';
            $VARIABLE002 = '=';
            $VARIABLE003 = 13;
            break;
          case ($i === 2):
            $VARIABLE001 = 'Tunnel-Medium-Type';
            $VARIABLE002 = '=';
            $VARIABLE003 = 6;
            break;
          case ($i === 3):
            $VARIABLE001 = 'Tunnel-Private-Group-Id';
            $VARIABLE002 = '=';
            $VARIABLE003 = sanitize($INPUT002);
            break;
        }

        $TEMP['insert'][000] = sprintf('
                                        INSERT INTO `radius`.`radgroupreply` (
                                          `id`,
                                          `GroupName`,
                                          `Attribute`,
                                          `op`,
                                          `Value`
                                        )
                                        VALUES (
                                          NULL,
                                           %d,
                                          "%s",
                                          "%s",
                                          "%s"
                                        );',
                                          sanitize($INPUT002),
                                          $VARIABLE001,
                                          $VARIABLE002,
                                          $VARIABLE003
                                        );
        $TEMP['insert'][001] = mysql_query($TEMP['insert'][000], $DB['link_identifier']['radius']) or die('Query failed '.mysql_error());

      }
    }

  }

  function manage_radcheck($INPUT001 /* Action [INSERT, DELETE] */, $INPUT002 /* MAC address */, $INPUT003 /* VLAN ID */) {
    global $DB;

   // Insert into RADIUS database //
    if ($INPUT001 == 'INSERT') {
      $TEMP['insert'][000] = sprintf('
                                      INSERT INTO `radius`.`radcheck` (
                                        `id`,
                                        `UserName`,
                                        `Attribute`,
                                        `op`,
                                        `Value`
                                      )
                                      VALUES (
                                        NULL,
                                        "%s",
                                        "Auth-Type",
                                        ":=",
                                        "Accept"
                                      );',
                                        sanitize($INPUT002)
                                      );
      $TEMP['insert'][001] = mysql_query($TEMP['insert'][000], $DB['link_identifier']['radius']) or die('Query failed '.mysql_error());
    }

   // Delete from RADIUS database //
    if ($INPUT001 == 'DELETE') {
      $TEMP['delete'][000] = sprintf('
                                      DELETE FROM `radius`.`radcheck` WHERE
                                        `radcheck`.`UserName` = "%s"
                                      LIMIT 1
                                      ',
                                        sanitize($INPUT002)
                                      );
      $TEMP['delete'][001] = mysql_query($TEMP['delete'][000], $DB['link_identifier']['radius']) or die('Query failed '.mysql_error());
    }

  }

  function manage_usergroup($INPUT001 /* Action [INSERT, UPDATE, DELETE] */, $INPUT002 /* MAC address */, $INPUT003 /* VLAN ID */) {
    global $DB;

   // Insert into RADIUS database //
    if ($INPUT001 == 'INSERT') {
      $TEMP['insert'][000] = sprintf('
                                      INSERT INTO `radius`.`radusergroup` (
                                        `UserName`,
                                        `GroupName`,
                                        `priority`
                                      )
                                      VALUES (
                                        "%s",
                                         %d,
                                        "1"
                                      );',
                                        sanitize($INPUT002),
                                        sanitize($INPUT003)
                                      );
      $TEMP['insert'][001] = mysql_query($TEMP['insert'][000], $DB['link_identifier']['radius']) or die('Query failed '.mysql_error());
    }

   // Update RADIUS database //
    if ($INPUT001 == 'UPDATE') {
      $TEMP['update'][000] = sprintf('
                                      UPDATE `radius`.`radusergroup` SET
                                        `radusergroup`.`GroupName` = %d
                                      WHERE
                                        `radusergroup`.`UserName` = "%s"
                                      LIMIT 1
                                      ',
                                        (int) sanitize($INPUT003),
                                        sanitize($INPUT002)
                                      );
      $TEMP['update'][001] = mysql_query($TEMP['update'][000], $DB['link_identifier']['radius']) or die('Query failed '.mysql_error());
    }

   // Delete from RADIUS database //
    if ($INPUT001 == 'DELETE') {
      $TEMP['delete'][000] = sprintf('
                                      DELETE FROM `radius`.`radusergroup` WHERE
                                        `radusergroup`.`UserName` = "%s"
                                      LIMIT 1
                                      ',
                                        sanitize($INPUT002)
                                      );
      $TEMP['delete'][001] = mysql_query($TEMP['delete'][000], $DB['link_identifier']['radius']) or die('Query failed '.mysql_error());
    }

    unset($TEMP);
  }

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

  function in_database($INPUT001 /* Proposed record */, $INPUT002 /* Column */, $INPUT003 /* Table */, $INPUT004 /* Database link */) {
    $TEMP[000] = sprintf('SELECT COUNT(*) FROM `'.$INPUT003.'` WHERE '.$INPUT002.' = "%1$s"', mysql_real_escape_string($INPUT001, $INPUT004));
    $TEMP[001] = mysql_query($TEMP[000], $INPUT004) or die('Could not execute query');
    if (mysql_result($TEMP[001], 0) == 0) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  function log_out_of_gui($INPUT001 = '' /* Message kind [success|failure] (Optional) */, $INPUT002 = '' /* Message (Optional) */) {
    write_to_log("NOTICE", "LOGIN", "User has logged out successfully.");
    $_SESSION = array();
    if (!empty($INPUT001) && !empty($INPUT002)) {
      $_SESSION["messages"][$INPUT001][] = $INPUT002;
    }
    header("Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]);
    exit;
  }

  function log_in_to_gui() {
    global $VAR, $DB;
    if ($VAR["flag"]["user_is_logged_in"] == FALSE) { ## Proceed if not logged in ##
      if ($VAR["form"]["last_submitted_form"] == "login") { ## Proceed if FORM-LOGIN has been submitted ##

       // Sanitize input //
        $TEMP["_POST"]["username"] = sanitize($_POST["username"]);
        $TEMP["_POST"]["password"] = sanitize($_POST["password"]);

       // Check if user and password is correct according to SQL DB //
        $TEMP["username"] = $_POST["username"];
        $TEMP[000] = "
                      SELECT *
                      FROM `freeradius_gui_userdb`
                      WHERE `username` = '".$TEMP["_POST"]["username"]."' AND `password` = 'FreeRADIUS-".create_hash($TEMP["_POST"]["password"])."'
                      ORDER BY ID ASC
                      LIMIT 1
                      ;";
        $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());

       // Perform if USERNAME, PASSWORD or both are incorrect //
        if (mysql_num_rows($TEMP[001]) == 0) {
          $_SESSION["messages"]["failure"][] = "Anv&auml;ndarnamn eller l&ouml;senord &auml;r felaktigt.";
          if (in_database($TEMP["_POST"]["username"], "username", "freeradius_gui_userdb", $DB['link_identifier']['gui']) === TRUE) {
            write_to_log("WARNING", "LOGIN", "User (".$TEMP['_POST']['username'].") has entered an incorrect password");
          } else {
            write_to_log("WARNING", "LOGIN", "Unknown user (".$TEMP['_POST']['username'].") has attempted to login unsuccessfully.");
          }
        }

       // Perform if USERNAME, PASSWORD or both are correct //
        elseif (mysql_num_rows($TEMP[001]) > 0) {
          $TEMP[002] = mysql_fetch_assoc($TEMP[001]);
         // Set USER DATA SESSION //
          $_SESSION["private"]["plain"]["firstname"] = $TEMP[002]["firstname"];
          $_SESSION["private"]["hash"]["firstname"] = sha1(count_chars($TEMP[002]["firstname"], 3).$TEMP[002]["firstname"]);
          $_SESSION["private"]["plain"]["lastname"] = $TEMP[002]["lastname"];
          $_SESSION["private"]["hash"]["lastname"] = sha1(count_chars($TEMP[002]["lastname"], 3).$TEMP[002]["lastname"]);
          $_SESSION["private"]["plain"]["userid"] = $TEMP[002]["ID"];
          $_SESSION["private"]["hash"]["userid"] = sha1(count_chars($TEMP[002]["ID"], 3).$TEMP[002]["ID"]);
          $_SESSION["private"]["plain"]["username"] = $TEMP[002]["username"];
          $_SESSION["private"]["hash"]["username"] = sha1(count_chars($TEMP[002]["username"], 3).$TEMP[002]["username"]);
          $_SESSION["private"]["plain"]["permissions"] = $TEMP[002]["permissions"];
          $_SESSION["private"]["hash"]["permissions"] = sha1(count_chars($TEMP[002]["permissions"], 3).$TEMP[002]["permissions"]);
          $_SESSION["private"]["plain"]["vlanid"] = explode("|", $TEMP[002]["vlandb_vlanid"]);
          $_SESSION["private"]["hash"]["vlanid"] = sha1(count_chars(explode("|", $TEMP[002]["vlandb_vlanid"]), 3).explode("|", $TEMP[002]["vlandb_vlanid"]));
         // Set PLAIN HASH for future security checks //
          unset($TEMP);
          foreach(array_keys($_SESSION["private"]["plain"]) AS $key => $value) {
            $TEMP .= $_SESSION["private"]["plain"][$value];
          }
          $_SESSION["private"]["hash"]["plain"] = sha1(count_chars($TEMP, 3).$TEMP);
          unset($TEMP);
         // Free resultset //
          if (isset($TEMP[001])) {
            mysql_free_result($TEMP[001]);
          }
         // Refresh page //
          write_to_log("NOTICE", "LOGIN", "User (".$_SESSION["private"]["plain"]["username"].") has logged in successfully."); ## Write to log ##
          $_SESSION["messages"]["success"][] = "V&auml;lkommen ".$_SESSION["private"]["plain"]["firstname"]." ".$_SESSION["private"]["plain"]["lastname"]."!";
          header("Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]); ## Reload page ##
          exit;
        }
      }
    }
  }

  function seconds_to_His($INPUT001) {
    $VARIABLE001["H"] = intval(intval($INPUT001) / 3600);
    $VARIABLE001["i"] = intval(($INPUT001 / 60) % 60);
    $VARIABLE001["s"] = intval($INPUT001 % 60);
    return $VARIABLE001;
  }

  if (!function_exists('array_combine')) {
    function array_combine($INPUT001 /* Array -> KEY */, $INPUT002 /* Array -> VALUE */) {
      $TEMP['return'] = array();
      $INPUT001 = array_values($INPUT001);
      $INPUT002 = array_values($INPUT002);
      foreach ($INPUT001 AS $TEMP['key'] => $TEMP['value']) {
        $TEMP['return'][(string)$TEMP['value']] = $INPUT002[$TEMP['key']];
      }
      return $TEMP['return'];
    }
  }

  function check_inactivity() {
    global $VAR; /* Make varialbe global for function */
    if ($VAR["flag"]["user_is_logged_in"] == TRUE) { /* Check if user is logged in */
      if (!isset($_SESSION['check_inactivity'])) { /* If session variable has not been set... */
        $_SESSION['check_inactivity'] = time(); /* ...set session variable */
      }
      if ((time() - $_SESSION['check_inactivity']) >= 600) { /* Automatically logged out after 10 minutes */
        write_to_log('NOTICE', 'LOGOUT', 'Automatically logged out due to inactivity.');
        $_SESSION["messages"]["logout"]["kind"] = "warning";
        $_SESSION["messages"]["logout"]["message"] = 'Automatiskt utloggad p&aring; grund av inaktivitet.';
        $_SESSION['check_inactivity'] = time(); /* Reset session variable */
        header("Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?LOGOUT");
        exit;
      }
    }
  }

// ### [ INITIAL ] ############################################################### //

 // Open a connection to the MySQL server //
  require('_includes/include-dbcon.inc.php');

  if (isset($_GET["LOGOUT"])) {
    log_out_of_gui($_SESSION["messages"]["logout"]["kind"], $_SESSION["messages"]["logout"]["message"]);
  }

 // User is not logged in as default //
  $VAR["flag"]["user_is_logged_in"] = FALSE;

 // Prevent URL tampering //
  if ((!empty($_SESSION["section"]) && !isset($NAVIGATION[$_SESSION["section"]])) || (!empty($_SESSION["subsection"]) && !isset($NAVIGATION[$_SESSION["section"]][$_SESSION["subsection"]]))) {
    write_to_log("WARNING", "NAVIGATION", "User (".$_SESSION["private"]["plain"]["username"].") has been tampering with the URL.", TRUE, 'URL attack - logged '.$_SERVER['REMOTE_ADDR']);
  }

 // Check if user is logged in (by validating SESSION information) //
  if (!empty($_SESSION["private"]["plain"]["username"])) {
    switch (FALSE) {
      case (validate_hash($_SESSION["private"]["plain"]["username"], $_SESSION["private"]["hash"]["username"])):
        write_to_log("ERROR", "LOGIN", "Hash validation for username failed.");
        $VAR["flag"]["user_is_logged_in"] = FALSE;
        break;
      case (validate_hash($_SESSION["private"]["plain"]["userid"], $_SESSION["private"]["hash"]["userid"])):
        write_to_log("ERROR", "LOGIN", "Hash validation for userid failed.");
        $VAR["flag"]["user_is_logged_in"] = FALSE;
        break;
      case (validate_hash($_SESSION["private"]["plain"]["permissions"], $_SESSION["private"]["hash"]["permissions"])):
        write_to_log("ERROR", "LOGIN", "Hash validation for permissions failed.");
        $VAR["flag"]["user_is_logged_in"] = FALSE;
        break;
      default:
        $VAR["flag"]["user_is_logged_in"] = TRUE;
        break;
    }
  }

  log_in_to_gui();

  check_inactivity();

  if (isset($_GET['valmac'])) {
    validate_mac_address(sanitize($_GET['valmac']), $VAR['radius']['shared_secret']);
  }

 // Check server health //
  $TEMP['server_health'][000] = "
                                 SELECT *
                                 FROM `freeradius`.`freeradius_serverhealth`
                                 ORDER BY id DESC
                                 LIMIT 1
                                 ;";
  $TEMP['server_health'][001] = mysql_query($TEMP['server_health'][000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  while ($TEMP['server_health'][002] = mysql_fetch_assoc($TEMP['server_health'][001])) {
    if ($TEMP['server_health'][002]['message'] == 'Server is not responding') {
        write_to_log('ERROR', 'AVAILABILITY', 'RADIUS server is not responding.');
        $_SESSION["messages"]['failure'][] = "RADIUS servern svarar inte.";
    } else {
      $TEMP['server_health']['difference_in_time'] = difference_in_time($TEMP['server_health'][002]["date"], date('Y-m-d H:i:s'));
      if ($TEMP['server_health']['difference_in_time'] > 600) {
        $TEMP['server_health']['dit']['array_His'] = seconds_to_His($TEMP['server_health']['difference_in_time']);
        $TEMP['server_health']['dit']['formatted'] = $TEMP['server_health']['dit']['array_His']['s'].' seconds';
        if ($TEMP['server_health']['dit']['array_His']['i'] > 0) {
          $TEMP['server_health']['dit']['formatted'] = $TEMP['server_health']['dit']['array_His']['i'].' minutes & '.$TEMP['server_health']['dit']['formatted'];
        }
        if ($TEMP['server_health']['dit']['array_His']['H'] > 0) {
          if ($TEMP['server_health']['dit']['array_His']['H'] > 1) {
            $TEMP['server_health']['dit']['suffix'] = 's';
          }
          $TEMP['server_health']['dit']['formatted'] = $TEMP['server_health']['dit']['array_His']['H'].' hour'.$TEMP['server_health']['dit']['suffix'].', '.$TEMP['server_health']['dit']['formatted'];
        }
        if ($TEMP['server_health']['difference_in_time'] > 1200) {
          $TEMP['server_health']['dit']['degree'] = 'failure';
        } else {
          $TEMP['server_health']['dit']['degree'] = 'warning';
        }
        write_to_log('ERROR', 'AVAILABILITY', "Server has not responded for the past <span class='bold'>".$TEMP['server_health']['dit']['formatted']."</span>.");
        $_SESSION["messages"][$TEMP['server_health']['dit']['degree']][] = "No response from RADIUS server for <span class='bold'>".$TEMP['server_health']['dit']['formatted']."</span>.";
        $_SESSION["messages"][$TEMP['server_health']['dit']['degree']][] = "Last noted health check on RADIUS server: ".$TEMP['server_health'][002]["date"];
        $_SESSION["messages"][$TEMP['server_health']['dit']['degree']][] = "Current time on server: ".date('Y-m-d H:i:s');
      }
    }
  }
  unset($TEMP['server_health']);

 // Fetch USERDB entries from database //
  unset($_SESSION["userdb"]["userid"]);
  $TEMP[000] = "
                SELECT *
                FROM `freeradius_gui_userdb`
                ORDER BY `freeradius_gui_userdb`.`ID` ASC
                ;";
  $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
    $_SESSION["userdb"]["userid"][$TEMP[002]["ID"]] = $TEMP[002]["username"];
  }
  unset($TEMP);

 // Fetch VLANDB entries from database //
  unset($_SESSION["vlandb"]["vlanid"]);
  $TEMP[000] = "
                SELECT *
                FROM `freeradius_gui_vlandb`
                ORDER BY `freeradius_gui_vlandb`.`vlanid` ASC
                ;";
  $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
    $_SESSION["vlandb"]["vlanid"]["id"][$TEMP[002]["ID"]] = $TEMP[002]["vlanid"];
    $_SESSION["vlandb"]["vlanid"]["vlanid"][$TEMP[002]["vlanid"]] = $TEMP[002]["vlanpseudo"];
  }
  unset($TEMP);

 // [USERDB] EDIT: User has been selected - fetch USERDB data from database //
  if ($VAR["form"]["last_submitted_form"] == "pickuser") { ## Proceed if correct form is submitted ##
    $TEMP["_POST"]["username"] = sanitize($_POST["userid"]);
    $TEMP[000] = "
                  SELECT *
                  FROM `freeradius_gui_userdb`
                  WHERE `ID` = '".$TEMP["_POST"]["username"]."'
                  LIMIT 1
                  ;";
    $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
    while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
      $_SESSION["userdb"]["form"]["userid"] = $TEMP[002]["ID"];
      $_SESSION["userdb"]["form"]["firstname"] = $TEMP[002]["firstname"];
      $_SESSION["userdb"]["form"]["lastname"] = $TEMP[002]["lastname"];
      $_SESSION["userdb"]["form"]["username"] = $TEMP[002]["username"];
      $_SESSION["userdb"]["form"]["permissions"] = $TEMP[002]["permissions"];
      $_SESSION["userdb"]["form"]["vlanid"] = explode("|", $TEMP[002]["vlandb_vlanid"]);
    }
    unset($TEMP);
  }

 // [VLANDB] EDIT: VLAN has been selected - fetch VLANDB data from database //
  if ($VAR["form"]["last_submitted_form"] == "pickvlan") { ## Proceed if correct form is submitted ##
    $TEMP["_POST"]["ID"] = sanitize($_POST["ID"]);
    $TEMP[000] = "
                  SELECT *
                  FROM `freeradius_gui_vlandb`
                  WHERE `ID` = '".$TEMP["_POST"]["ID"]."'
                  LIMIT 1
                  ;";
    $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
    while ($TEMP[002] = mysql_fetch_assoc($TEMP[001])) {
      $_SESSION["vlandb"]["form"]["ID"] = $TEMP[002]["ID"];
      $_SESSION["vlandb"]["form"]["vlanid"] = $TEMP[002]["vlanid"];
      $_SESSION["vlandb"]["form"]["vlanpseudo"] = $TEMP[002]["vlanpseudo"];
    }
    unset($TEMP);
  }

 // [PASSWD] Change password //
  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { ## Proceed if logged in ##
    if ($VAR["form"]["last_submitted_form"] == "passwd") { ## Proceed if FORM-PASSWD has been submitted ##
      unset($_SESSION["messages"]); ## Clear MESSAGES SESSION DATA ##

      $TEMP["_POST"]["oldpassword"] = sanitize($_POST["oldpassword"]);
      $TEMP["_POST"]["password"] = sanitize($_POST["password"]);
      $TEMP["_POST"]["retypepassword"] = sanitize($_POST["retypepassword"]);

      if (empty($TEMP["_POST"]["password"]) || empty($TEMP["_POST"]["oldpassword"])) {
        write_to_log('ERROR', 'USERDB', 'User left out PASSWORD when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">L&ouml;senord</span> saknas.";
      }

      if ($TEMP["_POST"]["password"] != $TEMP["_POST"]["retypepassword"]) {
        write_to_log('NOTICE', 'PASSWD', 'Password mismatch when trying to change password.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">L&ouml;senorden</span> st&auml;mmer inte &ouml;verens.";
      }

      if (count($_SESSION["messages"]["failure"]) == 0) { ## Proceed if no errors were encountered ##
        $TEMP[000] = "
                      UPDATE `freeradius`.`freeradius_gui_userdb` SET
                      `MODIFIED` = NOW( ),
                      `password` = 'FreeRADIUS-".create_hash($TEMP["_POST"]["password"])."'
                      WHERE `username` = '".$_SESSION["private"]["plain"]["username"]."' AND `password` = 'FreeRADIUS-".create_hash($TEMP["_POST"]["oldpassword"])."'
                      LIMIT 1
                      ;";
        $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
        if (mysql_affected_rows($DB['link_identifier']['gui']) == 0) { ## Perofrm if no rows were affected (ie. password not changed) ##
          write_to_log('NOTICE', 'PASSWD', 'Incorrect password submitted when trying to change password.');
          $_SESSION["messages"]["failure"][] = 'Felaktigt <span class="color_red">l&ouml;senord</span> angivet.';
        } else {
          write_to_log('NOTICE', 'PASSWD', 'Password changed successfully for user '.$_SESSION["private"]["plain"]["username"]);
          $_SESSION["messages"]["logout"]["kind"] = "success";
          $_SESSION["messages"]["logout"]["message"] = '<span class="color_blue">L&ouml;senordet</span> &auml;r bytt. V&auml;nligen logga in med ditt nya l&ouml;senord.';
        }
      }

      $TEMP["header_Location"] = "Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]; ## Set header-location ##
      if (count($_SESSION["messages"]["failure"]) > 0) { ## Proceed if required input is missing ##
        $TEMP["header_Location"] .= "?PASSWD"; ## Append to header-Location ##
      } else {
        $TEMP["header_Location"] .= "?LOGOUT"; ## Append to header-Location ##
      }
      header($TEMP["header_Location"]); ## Reload page ##
      exit;
    }
  }

 // [USERDB] ADD or EDIT user //
  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { ## Proceed if logged in ##
    if (($VAR["form"]["last_submitted_form"] == "newuser") || ($VAR["form"]["last_submitted_form"] == "edituser")) { ## Proceed if FORM-NEWUSER has been submitted ##
      unset($_SESSION["userdb"]["form"]); ## Clear FORM SESSION DATA ##
      unset($_SESSION["messages"]["failure"]); ## Clear FORM SESSION DATA ##

      $_POST["firstname"] = sanitize($_POST["firstname"]);
      $_POST["lastname"] = sanitize($_POST["lastname"]);
      $_POST["username"] = sanitize($_POST["username"]);
      $_POST["password"] = sanitize($_POST["password"]);
      $_POST["permissions"] = sanitize($_POST["permissions"]);
      $_POST["vlanid"] = $_POST["vlanid"];

     // Check if input is missing //

      if (!empty($_POST["userid"])) {
        $_SESSION["userdb"]["form"]["userid"] = $_POST["userid"];
      }

      if (!empty($_POST["firstname"])) {
        $_SESSION["userdb"]["form"]["firstname"] = $_POST["firstname"];
      } else {
        write_to_log('ERROR', 'USERDB', 'User left out FIRST NAME when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">F&ouml;rnamn</span> saknas.";
      }

      if (!empty($_POST["lastname"])) {
        $_SESSION["userdb"]["form"]["lastname"] = $_POST["lastname"];
      } else {
        write_to_log('ERROR', 'USERDB', 'User left out LAST NAME when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">Efternamn</span> saknas.";
      }

      if (!empty($_POST["username"])) { ## Check if form username was submitted ##
        if (match_regexp($VAR["whitelist"][0], $_POST["username"], TRUE) > 0) { ## Validate against whitelist ##
          write_to_log('ERROR', 'USERDB', 'Proposed USER NAME contained characters that were not allowed.');
          $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">Anv&auml;ndarnamn</span> inneh&aring;ller ej till&aring;tna tecken."; $_SESSION["userdb"]["form"]["username"] = $_POST["username"]; ## Store error message in session ##
        }
        if (strlen($_POST["username"]) > 16) { ## Check if max length is passed ##
          write_to_log('ERROR', 'USERDB', 'Proposed USER NAME had more characters than allowed.');
          $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">Anv&auml;ndarnamn</span> inneh&aring;ller fler &auml;n 16 tecken."; $_SESSION["userdb"]["form"]["username"] = $_POST["username"]; ## Store error message in session ##
        }
        if (strlen($_POST["username"]) < 4) { ## Check if min length is not passed ##
          write_to_log('ERROR', 'USERDB', 'Proposed USER NAME had fewer characters than allowed.');
          $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">Anv&auml;ndarnamn</span> inneh&aring;ller f&auml;rre &auml;n 4 tecken."; $_SESSION["userdb"]["form"]["username"] = $_POST["username"]; ## Store error message in session ##
        }
        if ($_POST["username"] /* Proposed username */ == $_SESSION["userdb"]["userid"][$_POST["userid"]] /* Current username */) { ## Check if username remains the same as before ##
          $_SESSION["userdb"]["form"]["username"] = $_POST["username"]; ## Username is not changed - Store username in session ##
        } else { ## Username is changed ##
          if (!in_array($_POST["username"], $_SESSION["userdb"]["userid"])) { ## Check if username is new and unique (not in database) ##
            $_SESSION["userdb"]["form"]["username"] = $_POST["username"]; ## Store unique username in session ##
          } else { ## Username already exist in database ##
            write_to_log('ERROR', 'USERDB', 'Proposed USER NAME already exists in database.');
            $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">Anv&auml;ndarnamn</span> finns redan."; ## Store error message in session ##
            $_SESSION["userdb"]["form"]["username"] = $_POST["username"]; ## Store proposed username in session ##
          }
        }
      } else { ## Perform if username was not submitted ##
        write_to_log('ERROR', 'USERDB', 'User left out USER NAME when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">Anv&auml;ndarnamn</span> saknas."; ## Store error message in session ##
      }

      if (!empty($_POST["permissions"])) {
        $_SESSION["userdb"]["form"]["permissions"] = $_POST["permissions"];
      } else {
        write_to_log('ERROR', 'USERDB', 'User left out PERMISSIONS when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">GUI-r&auml;ttigheter</span> saknas.";
      }

      if ($_POST["permissions"] == 'su') {
        $_SESSION["userdb"]["form"]["vlanid"] = array('any');
        $_POST["vlanid"] = array('any');
      } else {
        if (!empty($_POST["vlanid"])) {
          $_SESSION["userdb"]["form"]["vlanid"] = $_POST["vlanid"];
        } else {
          write_to_log('ERROR', 'USERDB', 'User left out VLAN permissions when submitting form.');
          $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">VLAN-r&auml;ttigheter</span> saknas.";
        }
      }

      if ($_SESSION["subsection"] == "add") { ## Perform if ACTION is to ADD USER ##
        if (empty($_POST["password"])) {
          write_to_log('ERROR', 'USERDB', 'User left out PASSWORD when submitting form.');
          $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">L&ouml;senord</span> saknas.";
        }
      }

      if ($_POST["password"] != $_POST["retypepassword"]) {
        write_to_log('ERROR', 'USERDB', 'User submitted PASSWORDS that do not match.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">L&ouml;senorden</span> st&auml;mmer inte &ouml;verens.";
      }

      if ($_SESSION["subsection"] == "add") { ## Perform if ACTION is to ADD USER ##
        if (count($_SESSION["messages"]["failure"]) == 0) { ## Proceed with ADD if required input is available ##
          unset($_SESSION["userdb"]["form"]); ## Clear FORM SESSION DATA ##
          unset($_SESSION["messages"]["failure"]); ## Clear ERROR MESSAGES ##
         // Add entry to SQL DB //
          $TEMP[000] = "
                        INSERT INTO `freeradius`.`freeradius_gui_userdb` (
                          `ID`,
                          `MODIFIED`,
                          `firstname`,
                          `lastname`,
                          `username`,
                          `password`,
                          `permissions`,
                          `vlandb_vlanid`,
                          `userdb_id`
                        )
                        VALUES (
                          NULL,
                          NOW( ),
                          '".$_POST["firstname"]."',
                          '".$_POST["lastname"]."',
                          '".$_POST["username"]."',
                          'FreeRADIUS-".create_hash($_POST["password"])."',
                          '".$_POST["permissions"]."',
                          '".implode("|", $_POST["vlanid"])."',
                          '".$_SESSION["private"]["plain"]["username"]."'
                        );";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          if ($TEMP[001]) {
            write_to_log("NOTICE", "USERDB", "User (".$_POST["username"].") has been added successfully.");
            $_SESSION["messages"]["success"][] = "Anv&auml;ndare <span class=\"color_blue\">".$_POST["username"]."</span> (".$_POST["firstname"]." ".$_POST["lastname"].") sparad.";
          }
        }

      } elseif ($_SESSION["subsection"] == "edit") { ## Perform if ACTION is to EDIT USER ##
        if (count($_SESSION["messages"]["failure"]) == 0) { ## Proceed with ADD if required input is available ##
          unset($_SESSION["userdb"]["form"]); ## Clear FORM SESSION DATA ##
          unset($_SESSION["messages"]["failure"]); ## Clear ERROR MESSAGES ##
         // Update entry in SQL DB //
          if (!empty($_POST["password"])) { ## Add password snippet to SQL query if password was edited ##
            $TEMP002 = "`password` = 'FreeRADIUS-".create_hash($_POST["password"])."',";
          }
          $TEMP[000] = "
                        UPDATE `freeradius`.`freeradius_gui_userdb` SET
                        `MODIFIED` = NOW( ),
                        `firstname` = '".$_POST["firstname"]."',
                        `lastname` = '".$_POST["lastname"]."',
                        `username` = '".$_POST["username"]."',
                        $TEMP002
                        `permissions` = '".$_POST["permissions"]."',
                        `vlandb_vlanid` = '".implode("|", $_POST["vlanid"])."'
                        WHERE `freeradius_gui_userdb`.`ID` = ".$_POST["userid"]."
                        LIMIT 1
                        ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          if ($TEMP[001]) {
            write_to_log("NOTICE", "USERDB", "User (".$_POST["username"].") has been updated successfully.");
            $_SESSION["messages"]["success"][] = "Anv&auml;ndare <span class=\"color_blue\">".$_POST["username"]."</span> (".$_POST["firstname"]." ".$_POST["lastname"].") uppdaterad.";
          }
        }
      }

      $TEMP["header_Location"] = "Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]; ## Set header-location ##
      if (count($_SESSION["messages"]["failure"]) > 0) { ## Proceed if required input is missing ##
        write_to_log("ERROR", "USERDB", "User (".$_POST["username"].") was not added/updated.");
        $TEMP["header_Location"] .= "&subsection=".$_SESSION["subsection"]; ## Append to header-Location ##
      } else {
        unset($_SESSION["subsection"]); ## Destroy variable ##
      }
      header($TEMP["header_Location"]); ## Reload page ##
      exit;
    }
  }

 // [VLANDB] ADD or EDIT vlan //
  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { ## Proceed if logged in ##
    if (($VAR["form"]["last_submitted_form"] == "newvlan") || ($VAR["form"]["last_submitted_form"] == "editvlan")) { ## Check if form has been submitted ##

     // Form validation //
      unset($_SESSION["messages"]); ## Remove any previous messages ##
      unset($_SESSION["vlandb"]["form"]); ## Remove any previous form input ##

      $_POST["vlanid"] = sanitize($_POST["vlanid"]);
      $_POST["vlanpseudo"] = sanitize($_POST["vlanpseudo"]);

      if (!empty($_POST["ID"])) { ## Check if input is missing ##
        $_SESSION["vlandb"]["form"]["ID"] = $_POST["ID"];
      }

      if (!empty($_POST["vlanid"])) { ## Check if input is missing ##
        if ($_POST["vlanid"] /* Proposed vlanid */ == $_SESSION["vlandb"]["vlanid"]["id"][$_POST["ID"]] /* Current vlanid */) { ## Check if vlanid is unchanhed ##
          $_SESSION["vlandb"]["form"]["vlanid"] = $_POST["vlanid"]; ## vlanid remains the same - Store into session ##
        } else { ## User has proposed new vlanid ##
          if (!array_key_exists($_POST["vlanid"], $_SESSION["vlandb"]["vlanid"]["vlanid"])) { ## Proceed if vlanid is new and unique (not in database) ##
            $_SESSION["vlandb"]["form"]["vlanid"] = $_POST["vlanid"]; ## Store unique vlanid into session ##
          } else { ## vlanid already exist in database ##
            write_to_log('ERROR', 'VLANDB', 'User proposed VLAN ID ('.$_POST["vlanid"].') which already exist in database.');
            $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">VLAN ID</span> finns redan."; ## Store error message in session ##
            $_SESSION["vlandb"]["form"]["vlanid"] = $_POST["vlanid"]; ## Store proposed vlanid into session ##
          }
        }
      } else { ## Perform if vlanid was not submitted ##
        write_to_log('ERROR', 'VLANDB', 'User left out VLAN ID when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">VLAN ID</span> saknas."; ## Store error message into session ##
      }

      if (!empty($_POST["vlanpseudo"])) { ## Check if input is missing ##
        $_SESSION["vlandb"]["form"]["vlanpseudo"] = $_POST["vlanpseudo"]; ## Store into session ##
      } else {
        write_to_log('ERROR', 'VLANDB', 'User left out VLAN PSEUDO NAME when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">Pseudonamn</span> saknas."; ## Store error message into session ##
      }

     // Store into database //
      if (count($_SESSION["messages"]["failure"]) == 0) { ## Proceed if input passed validation ##

       // Perform if ACTION is to ADD VLAN //
        if ($_SESSION["subsection"] == "add") {

         // Add entry to SQL DB //
          $TEMP[000] = "
                        INSERT INTO `freeradius`.`freeradius_gui_vlandb` (
                          `ID`,
                          `MODIFIED`,
                          `vlanid`,
                          `vlanpseudo`,
                          `userdb_id`
                        )
                        VALUES (
                          NULL,
                          NOW( ),
                          '".$_POST["vlanid"]."',
                          '".addslashes($_POST["vlanpseudo"])."',
                          '".$_SESSION["private"]["plain"]["userid"]."'
                        );";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          if ($TEMP[001]) {
            manage_radgroupreply('INSERT', $_POST["vlanid"]);
            write_to_log("NOTICE", "VLANDB", "VLAN (".addslashes($_POST["vlanpseudo"])."/".$_POST["vlanid"].") was added successfully.");
            $_SESSION["messages"]["success"][] = "VLAN <span class=\"color_blue\">".$_SESSION["vlandb"]["form"]["vlanpseudo"]."&nbsp;[".$_SESSION["vlandb"]["form"]["vlanid"]."]</span> sparat.";
          }

       // Perform if ACTION is to EDIT VLAN //
        } elseif ($_SESSION["subsection"] == "edit") {

         // Update entry in SQL DB //
          $TEMP[000] = "
                        UPDATE `freeradius`.`freeradius_gui_vlandb` SET
                        `MODIFIED` = NOW( ),
                        `vlanid` = '".$_POST["vlanid"]."',
                        `vlanpseudo` = '".addslashes($_POST["vlanpseudo"])."'
                        WHERE `freeradius_gui_vlandb`.`ID` = ".$_POST["ID"]."
                        LIMIT 1
                        ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          if ($TEMP[001]) {
            write_to_log("NOTICE", "VLANDB", "VLAN (".addslashes($_POST["vlanpseudo"])."/".$_POST["vlanid"].") was updated successfully.");
            $_SESSION["messages"]["success"][] = "VLAN <span class=\"color_blue\">".$_SESSION["vlandb"]["form"]["vlanpseudo"]."</span> sparat.";
          }
        }
      }

      $TEMP["header_Location"] = "Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]; ## Set header-location ##
      if (count($_SESSION["messages"]["failure"]) > 0) { ## Proceed if required input is missing ##
        write_to_log("ERROR", "VLANDB", "VLAN (".addslashes($_POST["vlanpseudo"])."/".$_POST["vlanid"].") was not added/updated.");
        $TEMP["header_Location"] .= "&subsection=".$_SESSION["subsection"]; ## Append to header-Location ##
      } else {
        unset($_SESSION["subsection"]); ## Destroy variable ##
      }
      header($TEMP["header_Location"]); ## Reload page ##
      exit;
    }
  }


 // [MACDB] DELETE mac48 //
  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { ## Proceed if logged in ##
    if ($VAR["form"]["last_submitted_form"] == "removemac48") {

      $_SESSION["macdb"]["form"]["ID"] = (int) $_POST["ID"];

      $TEMP['mac48'][000] = "
                             SELECT *
                             FROM `freeradius_gui_macdb`
                             WHERE `ID` = '".$_SESSION["macdb"]["form"]["ID"]."'
                             LIMIT 1
                             ;";
      $TEMP['mac48'][001] = mysql_query($TEMP['mac48'][000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
      while ($TEMP['mac48'][002] = mysql_fetch_assoc($TEMP['mac48'][001])) {
        $_SESSION["macdb"]["form"]["mac48"] = $TEMP['mac48'][002]["mac48"];
      }

     // Store into database //
      $TEMP[000] = "
                    DELETE FROM `freeradius_gui_macdb` WHERE
                      `freeradius_gui_macdb`.`ID` = ".$_SESSION["macdb"]["form"]["ID"]."
                    LIMIT 1;
                  ;";
      $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']);
      if ($TEMP[001]) {
        manage_usergroup('DELETE', $_SESSION["macdb"]["form"]["mac48"], "");
        manage_radcheck('DELETE', $_SESSION["macdb"]["form"]["mac48"], "");
        write_to_log("NOTICE", "MACDB", "MAC-address (".$_SESSION["macdb"]["form"]["mac48"].") was removed.");
        $_SESSION["messages"]["success"][] = "MAC-adress <span class=\"color_blue\">".$_SESSION["macdb"]["form"]["mac48"]."</span> &auml;r borttagen.";
      } elseif (!$TEMP[001]) {
        write_to_log("ERROR", "MACDB", "MAC-address (".$_SESSION["macdb"]["form"]["mac48"].") was not removed.");
        $_SESSION["messages"]["failure"][] = "Error MAC48";
      }

      $TEMP["header_Location"] = "Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]; ## Set header-location ##
      if (count($_SESSION["messages"]["failure"]) > 0) { ## Perform if errors were encountered ##
        $TEMP["header_Location"] .= "&subsection=".$_SESSION["subsection"]; ## Append to header-Location ##
      } else { ## Perform if no errors were encountered ##
        unset($_SESSION["subsection"]); ## Destroy variable ##
      }
      header($TEMP["header_Location"]); ## Reload page ##
      exit;
    }
  }


 // [USERDB] DELETE user //
  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { ## Proceed if logged in ##
    if ($VAR["form"]["last_submitted_form"] == "removeuser") {

      $_SESSION["userdb"]["form"]["ID"] = (int) $_POST["userid"];

      $TEMP['user'][000] = "
                            SELECT *
                            FROM `freeradius_gui_userdb`
                            WHERE `ID` = '".$_SESSION["userdb"]["form"]["ID"]."'
                            LIMIT 1
                            ;";
      $TEMP['user'][001] = mysql_query($TEMP['user'][000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
      while ($TEMP['user'][002] = mysql_fetch_assoc($TEMP['user'][001])) {
        $_SESSION["userdb"]["form"]["ID"] = $TEMP['user'][002]["ID"];
        $_SESSION["userdb"]["form"]["username"] = $TEMP['user'][002]["username"];
      }

     // Remove from database //
      $TEMP[000] = "
                    DELETE FROM `freeradius_gui_userdb` WHERE
                      `freeradius_gui_userdb`.`ID` = ".$_SESSION["userdb"]["form"]["ID"]."
                    LIMIT 1;
                  ;";
      $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']);
      if ($TEMP[001]) {
        write_to_log("NOTICE", "USERDB", "User (".$_SESSION["userdb"]["form"]["username"].") was removed.");
        $_SESSION["messages"]["success"][] = "Anv&auml;ndare <span class=\"color_blue\">".$_SESSION["userdb"]["form"]["username"]."</span> &auml;r borttagen.";
      } elseif (!$TEMP[001]) {
        write_to_log("ERROR", "USERDB", "User (".$_SESSION["userdb"]["form"]["username"].") was not removed.");
        $_SESSION["messages"]["failure"][] = "Anv&auml;ndare <span class=\"color_red\">".$_SESSION["userdb"]["form"]["username"]."</span> var ej borttagen.";
      }

      $TEMP["header_Location"] = "Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]; ## Set header-location ##
      if (count($_SESSION["messages"]["failure"]) > 0) { ## Perform if errors were encountered ##
        $TEMP["header_Location"] .= "&subsection=".$_SESSION["subsection"]; ## Append to header-Location ##
      } else { ## Perform if no errors were encountered ##
        unset($_SESSION["subsection"]); ## Destroy variable ##
      }
      header($TEMP["header_Location"]); ## Reload page ##
      exit;
    }
  }


 // [MACDB] IMPORT mac48 //
  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { ## Proceed if logged in ##
    if (($VAR["form"]["last_submitted_form"] == "importmac48") && (is_array($_POST["mac48"]))) {

     // Save form entries into session //
      $_SESSION["macdb"]["form"]["mac48"] = $_POST["mac48"];
      $_SESSION["macdb"]["form"]["vlanid"] = $_POST["vlanid"];
      $_SESSION["macdb"]["form"]["overwrite"] = $_POST["overwrite"];
      $_SESSION["macdb"]["form"]["defaultvlanid"] = $_POST["defaultvlanid"];

     // Validate input //
      if (count(array_filter($_SESSION["macdb"]["form"]["mac48"])) != count(array_filter($_SESSION["macdb"]["form"]["vlanid"]))) { ## Filter '', 'foo' and NULL and compare the count of remaining values ##
        write_to_log('ERROR', 'MACDB', 'VLAN ID:s were not assigned to all MAC addresses during import.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">VLAN ID</span> har inte angivits f&ouml;r alla MAC-adresser.";
      }

     // Store into database //
      if (count($_SESSION["messages"]["failure"]) == 0) { ## Proceed if input passed validation ##
       // Check mySQL version //
        preg_match('/[1-9]\.(?:[0-9]\.?)*/', mysql_get_server_info(), $TEMP['mysql_get_server_info']);
        if ($TEMP['mysql_get_server_info'][0] >= 4.1) {
          $TEMP['mysql_get_server_info']['gtoet4.1'] = TRUE;
        } else {
          $TEMP['mysql_get_server_info']['gtoet4.1'] = FALSE;
        }
       // Overwrite existing duplicates? //
        foreach ($_SESSION["macdb"]["form"]["mac48"] AS $TEMP[002] => $TEMP[003]) {
          $INSERTorREPLACE = "INSERT";
          if (($_SESSION["macdb"]["form"]["overwrite"] == "1") && ($TEMP['mysql_get_server_info']['gtoet4.1'] == TRUE)) {
            $OVERWRITE = "ON DUPLICATE KEY UPDATE `vlandb_vlanid` = '".$_SESSION["macdb"]["form"]["vlanid"][$TEMP[002]]."'";
          } elseif (($_SESSION["macdb"]["form"]["overwrite"] == "1") && ($TEMP['mysql_get_server_info']['gtoet4.1'] == FALSE)) {
            $INSERTorREPLACE = "REPLACE";
            $OVERWRITE = "";
          }
          $TEMP[000] = "
                        ".$INSERTorREPLACE." INTO `freeradius`.`freeradius_gui_macdb` (
                          `ID`,
                          `MODIFIED`,
                          `mac48`,
                          `vlandb_vlanid`,
                          `userdb_id`
                        ) VALUES (
                          NULL,
                          NOW( ),
                          '".$_SESSION["macdb"]["form"]["mac48"][$TEMP[002]]."',
                          '".$_SESSION["macdb"]["form"]["vlanid"][$TEMP[002]]."',
                          '".$_SESSION["private"]["plain"]["userid"]."'
                        )
                        ".$OVERWRITE."
                      ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']);
          if ($TEMP[001]) {
            manage_usergroup('DELETE',  $_SESSION["macdb"]["form"]["mac48"][$TEMP[002]], NULL);
            manage_usergroup('INSERT', $_SESSION["macdb"]["form"]["mac48"][$TEMP[002]], $_SESSION["macdb"]["form"]["vlanid"][$TEMP[002]]);
            manage_radcheck('DELETE', $_SESSION["macdb"]["form"]["mac48"][$TEMP[002]], NULL);
            manage_radcheck('INSERT', $_SESSION["macdb"]["form"]["mac48"][$TEMP[002]], "");
            write_to_log("NOTICE", "MACDB", "MAC-address (".$_SESSION["macdb"]["form"]["mac48"][$TEMP[002]].") was imported successfully.");
            $_SESSION["messages"]["success"][] = "MAC-adress <span class=\"color_blue\">".$_SESSION["macdb"]["form"]["mac48"][$TEMP[002]]."/".$_SESSION["macdb"]["form"]["vlanid"][$TEMP[002]]."</span> sparad.";
          } elseif (!$TEMP[001]) {
            if ($_SESSION["macdb"]["form"]["overwrite"] == "1") {
              write_to_log("ERROR", "MACDB", "MAC-address (".$_SESSION["macdb"]["form"]["mac48"][$TEMP[002]].") failed import.");
            } else {
              $_SESSION["messages"]["success"][] = "MAC-adress <span class=\"color_red\">".$_SESSION["macdb"]["form"]["mac48"][$TEMP[002]]."/".$_SESSION["macdb"]["form"]["vlanid"][$TEMP[002]]."</span> finns i databasen och sparades inte.";
            }
          }
        }
      }

      $TEMP["header_Location"] = "Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]; ## Set header-location ##
      if (count($_SESSION["messages"]["failure"]) > 0) { ## Perform if errors were encountered ##
        $TEMP["header_Location"] .= "&subsection=".$_SESSION["subsection"]; ## Append to header-Location ##
      } else { ## Perform if no errors were encountered ##
        unset($_SESSION["subsection"]); ## Destroy variable ##
      }
//      header($TEMP["header_Location"]); ## Reload page ##
//      exit;
    }
  }

 // [MACDB] ADD or EDIT mac48 //
  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { ## Proceed if logged in ##
    if (($VAR["form"]["last_submitted_form"] == "newmac48") || ($VAR["form"]["last_submitted_form"] == "editmac48")) { ## Check if form has been submitted ##

     // Validate input //
      $_POST["mac48"] = strtolower(sanitize($_POST["mac48"]));
      $_POST["vlanid"] = sanitize($_POST["vlanid"]);
      $_SESSION["macdb"]["form"]["ID"] = (int) $_POST["ID"];
      preg_match('/\b[\da-f]{12}\b/', strtolower($_POST["mac48"]), $TEMP[001]); ## Match the first occurance of a valid MAC-address in a string ##
      $_SESSION["macdb"]["form"]["mac48"] = $TEMP[001][0]; ## Assign match from preg_match above to variable ##
      $_SESSION["macdb"]["form"]["vlanid"] = (int) $_POST["vlanid"];

      if (strlen($_POST["mac48"]) != 12) {
        write_to_log('ERROR', 'MACDB', 'MAC address ('.$_POST["mac48"].') has an incorrect length.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">MAC-adressen</span> har felaktig l&auml;ngd.";
      }
      if (empty($_POST["mac48"])) {
        write_to_log('ERROR', 'MACDB', 'User left out MAC address when submitting form.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">MAC-adress</span> ej angiven.";
      }
      if ($_SESSION["subsection"] == "add") {
        if ($_SESSION["macdb"]["form"]["mac48"] != $_SESSION["macdb"]["form"]["mac48_original"]) {
          if (in_database($_POST["mac48"], "mac48", "freeradius_gui_macdb", $DB['link_identifier']['gui'])) {
            write_to_log('ERROR', 'MACDB', 'MAC address ('.$_POST["mac48"].') already exist in database.');
            $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">MAC-adressen</span> finns redan i databasen.";
          }
        }
      }
      if (match_regexp($VAR["whitelist"][1], $_POST["mac48"], TRUE) > 0) {
        write_to_log('ERROR', 'MACDB', 'MAC address ('.$_POST["mac48"].') contains incorrect characters.');
        $_SESSION["messages"]["failure"][] = "<span class=\"color_red\">MAC-adressen</span> inneh&aring;ller felaktiga tecken.";
      }

     // Store into database //
      if (count($_SESSION["messages"]["failure"]) == 0) { ## Proceed if input passed validation ##

       // Perform if ACTION is to ADD mac48 //
        if ($_SESSION["subsection"] == "add") {
         // Add entry to SQL DB //
          $TEMP[000] = "
                        INSERT INTO `freeradius`.`freeradius_gui_macdb` (
                          `ID`,
                          `MODIFIED`,
                          `mac48`,
                          `vlandb_vlanid`,
                          `userdb_id`
                        )
                        VALUES (
                          NULL,
                          NOW( ),
                          '".$_SESSION["macdb"]["form"]["mac48"]."',
                          '".$_SESSION["macdb"]["form"]["vlanid"]."',
                          '".$_SESSION["private"]["plain"]["userid"]."'
                        );";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          if ($TEMP[001]) {
            manage_usergroup('INSERT', $_POST["mac48"], $_POST["vlanid"]);
            manage_radcheck('INSERT', $_POST["mac48"], "");
            write_to_log("NOTICE", "MACDB", "MAC-address (".$_SESSION["macdb"]["form"]["mac48"].") was added successfully.");
            $_SESSION["messages"]["success"][] = "MAC-adress <span class=\"color_blue\">".$_POST["mac48"]."/".$_POST["vlanid"]."</span> sparad.";
          } elseif (!$TEMP[001]) {
            write_to_log("ERROR", "MACDB", "MAC-address (".$_SESSION["macdb"]["form"]["mac48"].") failed being added.");
          }

       // Perform if ACTION is to EDIT mac48 //
        } elseif ($_SESSION["subsection"] == "edit") {
         // Update entry in SQL DB //
          $TEMP[000] = "
                        UPDATE `freeradius`.`freeradius_gui_macdb` SET
                          `MODIFIED` = NOW( ),
                          `mac48` = '".$_SESSION["macdb"]["form"]["mac48"]."',
                          `vlandb_vlanid` = '".$_SESSION["macdb"]["form"]["vlanid"]."',
                          `userdb_id` = '".$_SESSION["private"]["plain"]["userid"]."'
                        WHERE `freeradius_gui_macdb`.`ID` = ".$_SESSION["macdb"]["form"]["ID"]."
                        LIMIT 1
                        ;";
          $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
          if ($TEMP[001]) {
            manage_usergroup('UPDATE', $_POST["mac48"], $_POST["vlanid"]);
            write_to_log("NOTICE", "MACDB", "MAC-address (".$_POST['mac48'].") was updated successfully.");
            $_SESSION["messages"]["success"][] = "MAC-adress <span class=\"color_blue\">".$_POST['mac48']."/".$_POST['vlanid']."</span> sparad.";
          } elseif (!$TEMP[001]) {
            write_to_log("ERROR", "MACDB", "MAC-address (".$_POST['mac48'].") failed being updated.");
            $_SESSION["messages"]["failure"][] = "MAC-adress <span class=\"color_red\">".$_POST['mac48']."/".$_POST['vlanid']."</span> sparades inte.";
          }
        }
      }

      $TEMP["header_Location"] = "Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?section=".$_SESSION["section"]; ## Set header-location ##
      if (count($_SESSION["messages"]["failure"]) > 0) { ## Proceed if required input is missing ##
        $TEMP["header_Location"] .= "&subsection=".$_SESSION["subsection"]; ## Append to header-Location ##
      } else {
        unset($_SESSION["subsection"]); ## Destroy variable ##
      }
      header($TEMP["header_Location"]); ## Reload page ##
      exit;
    }
  }

  header("Content-type: text/html; charset=utf-8");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past //
  header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // Page is always modified //
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 - Forces caches to obtain a new copy of the page from the origin server //
  header("Cache-Control: no-store"); // Directs caches not to store the page under any circumstance //
  header("Pragma: no-cache"); // HTTP 1.0 backward compatibility //
  echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

// ############################################################################### //

    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv" lang="sv">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <title>Rad Radius GUI (RRG) 1.1.8</title>
    <script type="text/javascript">
      var xmlHttp;

      function showResults(str) {
        document.getElementById("status").innerHTML = "Searching..."
        xmlHttp = GetXmlHttpObject();
        if (xmlHttp == null) {
          alert("Browser does not support HTTP Request");
          return;
        }
<?php

  if (($_SESSION['section'] == 'logdb') && ($_SESSION['subsection'] == 'view')) { echo '        var url = "_includes/include-logdb-search.inc.php";'; }

    ?>
        url = url + "?q=" + str;
        url = url + "&amp;sid=" + Math.random();
        url = url + "&amp;searchQuery=" + document.getElementById("searchQuery").value;
        xmlHttp.onreadystatechange = stateChanged;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
      }

      function stateChanged() {
        if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == "complete")) {
          document.getElementById("dynDiv").innerHTML = xmlHttp.responseText;
        if (document.getElementById("status").innerHTML != "") {
          document.getElementById("status").innerHTML = "";
        }
        }
      }

      function GetXmlHttpObject() {
        var xmlHttp = null;
        try {
          // Firefox, Opera 8.0+, Safari
          xmlHttp = new XMLHttpRequest();
        }
        catch (e) {
          //Internet Explorer
          try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
          }
          catch (e) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
        }
        return xmlHttp;
      }

<?php

  if ($VAR["flag"]["user_is_logged_in"] == TRUE) { /* Print if user is logged in */

    ?>
      var inactivityTimer;
      window.onload=resetInactivityTimer;
      function inactivityAlert() {
        alert("Varning! Du kommer att loggas ut om 3 minuter pga. inaktivitet.")
        //location.href='http://<?php echo $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."?LOGOUT"; ?>'
      }
      function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer=setTimeout(inactivityAlert,7*60*1000) /* Give a warning after 7 minutes (3 minutes to go) */
        <?php $_SESSION['check_inactivity'] = time(); /* Reset session variable */ ?>
      }
<?php

  }

    ?>
    </script>
    <style type="text/css" media="screen">
    <!--
      a img {
        border: 0;
      }

      a, a:visited {
        color: rgb(0, 0, 160);
        text-decoration: none;
      }

      a:hover {
        color: rgb(100, 100, 255);
      }

      .bold {
        font-weight: bold;
      }

      body {
        font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
        font-size: 11px;
        line-height: 20px;
        color: rgb(0, 0, 0);
        margin: 0;
        padding: 0;
        background-color: rgb(239, 239, 239);
      }

      td {
        padding: 1px;
        margin: 0;
        vertical-align: top;
        text-align: left;
      }

      p {
        padding: 0;
        margin: 0;
        margin-bottom: 10px;
      }

      #container {
        width: 880px;
        background-color: rgb(255, 255, 255);
        color: inherit;
        border-bottom: rgb(194, 194, 194) 1px solid;
        border-right: rgb(194, 194, 194) 1px solid;
      }

      #content {
        padding: 10px;
        color: inherit;
        background-color: inherit;
        min-height: 580px;
        height: auto !important;
      }

      input, form, label, textarea {
        padding: 0;
        margin: 0;
      }

      input, textarea, select {
        border: 1px solid rgb(0, 0, 0);
      }

      input {
        padding-top: 1px;
        padding-bottom: 1px;
        padding-left: 3px;
        padding-right: 3px;
      }

      input, input[type="submit"], input.submit, select, option {
        font-family: Monospace;
        font-size: 12px;
      }

      input[type="submit"], input.submit {
        padding: 1px;
        margin: 0;
      }

      .td_select, .td_select option {
        font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: rgb(0, 0, 160);
        background-color: rgb(242, 242, 242);
        font-weight: normal;
      }

      .td_select {
        font-weight: bold;
        border: 1px solid rgb(220, 220, 220);
      }

      .box {
        padding: 8px;
        margin-bottom: 10px;
        color: inherit;
        background-color: rgb(242, 242, 242);
        border: 1px solid rgb(194, 194, 194);
      }

      .error_box {
        padding: 8px;
        margin-bottom: 10px;
        color: inherit;
        background-color: rgb(255, 180, 180);
        border: 2px solid rgb(255, 80, 80);
      }

      .warning_box {
        padding: 8px;
        margin-bottom: 10px;
        color: inherit;
        background-color: rgb(255, 245, 180);
        border: 2px solid rgb(255, 200, 0);
      }

      .success_box {
        padding: 8px;
        margin-bottom: 10px;
        color: inherit;
        background-color: rgb(180, 180, 255);
        border: 2px solid rgb(60, 60, 255);
      }

      .description {
        font-style: italic;
        padding: 8px;
        margin-top: 5px;
        margin-bottom: 5px;
        color: inherit;
        width: 400px;
        background-color: rgb(180, 180, 255);
        border: 2px solid rgb(60, 60, 255);
      }

      .description .example {
        border-top: 1px solid rgb(60, 60, 255);
        border-bottom: 1px solid rgb(60, 60, 255);
        padding: 1px;
      }

      .box_header {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 2px;
      }

      .box_subheader {
        font-size: 11px;
        text-indent: 0px;
        margin-bottom: 4px;
      }

      .box_content {
        padding: 6px;
      }

      .box_content td {
        padding-right: 6px;
      }

      .color_orange {
        color: rgb(255, 200, 0);
        background-color: inherit;
      }

      .color_red {
        color: rgb(255, 0, 0);
        background-color: inherit;
      }

      .color_gray {
        color: rgb(150, 150, 150);
        background-color: inherit;
      }

      .color_blue {
        color: rgb(0, 0, 255);
        background-color: inherit;
      }

      #header {
        padding: 10px;
        height: 26px;
        background-image: url(_images/background-header.png);
        color: inherit;
        background-color: inherit;
        background-position: top 0%;
        background-repeat: repeat-x;
        border-bottom: 1px solid rgb(194, 194, 194);
      }

      img.alignright {
        float: right;
      }

      img.alignleft {
        float: left;
      }
    -->
    </style>
  </head>

  <body>

    <div id="container">

      <div id="header">
        <a href="<?php echo $_SERVER["PHP_SELF"]."?LOGOUT"; ?>"><img src="_images/logo-rad_radius_gui.png" width="185" height="30" alt="Rad Radius GUI logotyp" class="alignleft" /></a>
        <!-- <a href="#"><img src="_images/logo-###.png" alt="#" class="alignright" /></a> -->
      </div>

      <div id="content">

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php

  if (count($_SESSION["messages"]["failure"]) > 0) {

    ?>
        <div class="error_box">
          <div class="box_header"><span class="color_red">Felmeddelande:</span></div>
          <div class="box_content">
<?php

    foreach ($_SESSION["messages"]["failure"] AS $TEMP["key"] => $TEMP["value"]) {

    ?>
            -&nbsp;<?php echo $TEMP["value"]; ?><br />
<?php

    }

    ?>
          </div>
        </div>
<?php

    unset($TEMP["key"], $TEMP["value"]);
  }

    ?>

<?php

  if (count($_SESSION["messages"]["warning"]) > 0) {

    ?>
        <div class="warning_box">
          <div class="box_header"><span class="color_orange">Meddelande:</span></div>
          <div class="box_content">
<?php

    foreach ($_SESSION["messages"]["warning"] AS $TEMP["key"] => $TEMP["value"]) {

    ?>
            -&nbsp;<?php echo $TEMP["value"]; ?><br />
<?php

    }

    ?>
          </div>
        </div>
<?php

    unset($TEMP["key"], $TEMP["value"]);
  }

    ?>

<?php

  if (count($_SESSION["messages"]["success"]) > 0) {

    ?>
        <div class="success_box">
          <div class="box_header"><span class="color_blue">Meddelande:</span></div>
          <div class="box_content">
<?php

    foreach ($_SESSION["messages"]["success"] AS $TEMP["key"] => $TEMP["value"]) {

    ?>
            -&nbsp;<?php echo $TEMP["value"]; ?><br />
<?php

    }

    ?>
          </div>
        </div>
<?php

    unset($TEMP["key"], $TEMP["value"], $_SESSION["messages"]["success"]);
  }

    ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php

  if ($VAR["flag"]["user_is_logged_in"] == FALSE) {

    ?>
        <div class="box">
          <div class="box_header">V&auml;nligen ange anv&auml;ndarnamn och l&ouml;senord!</div>
          <div class="box_content">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="login" method="post">
              <table>
                <tr>
                  <td><label for="username">Anv&auml;ndarnamn</label></td>
                  <td><input type="text" name="username" id="username" /></td>
                </tr>
                <tr>
                  <td><label for="password">L&ouml;senord</label></td>
                  <td><input type="password" name="password" id="password" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input class="submit" type="submit" name="form-login" value="<?php echo $VAR["form"]["name_of_submit_button"]["login"]; ?>" /></td>
                </tr>
              </table>
            </form>
          </div>
        </div>
<?php

  }

    ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php


  if ($VAR["flag"]["user_is_logged_in"] == TRUE) {

    ?>
        <div class="box">
          <div class="box_header">Anv&auml;ndarinformation</div>
          <div class="box_content">
            &ndash;&nbsp;Inloggad som <?php echo $_SESSION["private"]["plain"]["username"]; ?>&nbsp;(<?php echo $_SESSION["private"]["plain"]["firstname"]; ?>&nbsp;<?php echo $_SESSION["private"]["plain"]["lastname"]; ?>)<br />
            <a href="<?php echo $_SERVER["PHP_SELF"]."?PASSWD" ?>">&raquo;&nbsp;Byt l&ouml;senord</a><br />
            <a href="<?php echo $_SERVER["PHP_SELF"]."?LOGOUT" ?>">&raquo;&nbsp;Logga ut</a><br />
          </div>
        </div>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php

  if (isset($_GET["PASSWD"])) {

    ?>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <div class="success_box">
            <div class="box_header">Byte av l&ouml;senord</div>
            <div class="box_content">
              <table>
                <tr>
                  <td><label for="oldpassword">Befintligt l&ouml;senord</label></td>
                  <td><input type="password" name="oldpassword" id="oldpassword" /></td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td><label for="password">Nytt l&ouml;senord</label></td>
                  <td><input type="password" name="password" id="password" /></td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td><label for="retypepassword">Repetera nytt l&ouml;senord</label></td>
                  <td><input type="password" name="retypepassword" id="retypepassword" /></td>
                  <td><span class="color_red">*</span></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input class="submit" type="submit" name="form-passwd" value="<?php echo $VAR["form"]["name_of_submit_button"]["passwd"]; ?>" /></td>
                  <td><span class="color_red">* = Obligatorisk uppgift</span></td>
                </tr>
              </table>
            </div>
          </div>
        </form>

<?php

  }

    ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

        <div class="box">
          <div class="box_header"><?php if (!empty($_SESSION["section"])) { ?><a href="?section=&amp;subsection="><?php } ?>Administrationsverktyg<?php if (!empty($_SESSION["section"])) { ?></a><?php } if (!empty($_SESSION["section"])) { ?>&nbsp;&laquo;&nbsp;<?php if (!empty($_SESSION["subsection"])) { ?><a href="?section=<?php echo $_SESSION["section"]; ?>&amp;subsection="><?php } echo $NAVIGATION_TEXT[$_SESSION["section"]]; if (!empty($_SESSION["subsection"])) { ?></a><?php }} if (!empty($_SESSION["subsection"])) { ?>&nbsp;&laquo;&nbsp;<?php echo $NAVIGATION[$_SESSION["section"]][$_SESSION["subsection"]]; } ?></div>
          <div class="box_content">
<?php

    if (empty($_SESSION["section"])) {
      foreach ($NAVIGATION AS $key => $value) {

    ?>
            <a href="?section=<?php echo $key; ?>&amp;subsection=">&raquo;&nbsp;<?php echo $NAVIGATION_TEXT[$key]; ?></a><br />
<?php

      }
    }

    if ((!empty($_SESSION["section"])) && (empty($_SESSION["subsection"]))) {
      foreach ($NAVIGATION[$_SESSION["section"]] AS $key => $value) {

    ?>
            <a href="?section=<?php echo $_SESSION["section"]; ?>&amp;subsection=<?php echo $key; ?>">&raquo;&nbsp;<?php echo $value; ?></a><br />
<?php

      }
    }

    if ((!empty($_SESSION["section"])) && (!empty($_SESSION["subsection"]))) {

    ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php require("_includes/include-userdb.inc.php"); ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php require("_includes/include-vlandb.inc.php"); ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php require("_includes/include-mac48.inc.php"); ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php require("_includes/include-logdb.inc.php"); ?>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php

    }

    ?>
          </div>
        </div>

<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>
<?php ############################################################################################################################## ?>

<?php

  }

    ?>

      </div>

    </div>

  </body>

</html>

<?php

  if ($VAR["DEBUG"] === TRUE) {
    echo "<pre>";
    print_r($GLOBALS);
    echo "</pre>";
  }

 // Destroy variables //
  unset($_SESSION["messages"]);
  unset($_SESSION["userdb"]["form"]);
  unset($_SESSION["vlandb"]["form"]);
  unset($_SESSION["macdb"]["form"]);
  unset($_SESSION["logdb"]["form"]);

// Disconnect SQL server //
  mysql_close($DB['link_identifier']['gui']);

    ?>
