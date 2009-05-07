<?php 
  
  require('_includes/include-dbcon.inc.php');
  
  function create_hash($INPUT001 /* Plain text to be hashed */) {
    return sha1(count_chars($INPUT001, 3).$INPUT001);
  }
  
  if (!empty($_POST['password'])) {
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
                    'su',
                    'any',
                    '1'
                  );";
    $TEMP[001] = mysql_query($TEMP[000], $DB['link_identifier']['gui']) or die('Query failed '.mysql_error());
  }
  
    ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>FreeRADIUS GUI</title>
</head>

<body>

  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <table>
      <tr>
        <td><label for="firstname">First name:</label></td>
        <td><input type="text" name="firstname" id="firstname"></td>
      </tr>
      <tr>
        <td><label for="lastname">Last name:</label></td>
        <td><input type="text" name="lastname" id="lastname"></td>
      </tr>
      <tr>
        <td><label for="username">Username:</label></td>
        <td><input type="text" name="username" id="username"></td>
      </tr>
      <tr>
        <td><label for="password">Password:</label></td>
        <td><input type="text" name="password" id="password"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Spara"></td>
      </tr>
    </table>
  </form>
  <br><br>

</body>
</html>