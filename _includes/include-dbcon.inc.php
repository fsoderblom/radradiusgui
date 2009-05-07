<?php

  ### Open a connection to a MySQL Server ###

 // Configuration for FreeRADIUS GUI DB //
  connect_to_mysql(
    $TEMP['server']           = '',                 /* The MySQL server. It can also include a port number. */
    $TEMP['username']         = '',                 /* The username. */
    $TEMP['password']         = '',                 /* The password. */
    $TEMP['database_name']    = '',                 /* The name of the database that is to be selected. */
    $TEMP['link_identifier']  = 'gui',              /* The MySQL connection. It is also used for naming error messages. */
    $DB['link_identifier'][$TEMP['link_identifier']]
  );
  unset($TEMP); /* Destroy the temporarily allocated variables */

 // Configuration for FreeRADIUS RADIUS DB //
  connect_to_mysql(
    $TEMP['server']           = '',                 /* The MySQL server. It can also include a port number. */
    $TEMP['username']         = '',                 /* The username. */
    $TEMP['password']         = '',                 /* The password. */
    $TEMP['database_name']    = '',                 /* The name of the database that is to be selected. */
    $TEMP['link_identifier']  = 'radius',           /* The MySQL connection. It is also used for naming error messages. */
    $DB['link_identifier'][$TEMP['link_identifier']]
  );
  unset($TEMP); /* Destroy the temporarily allocated variables */

 // Connect to SQL server //
  function connect_to_mysql($INPUT001, $INPUT002, $INPUT003, $INPUT004, $INPUT005, &$INPUT006) {
    $INPUT006 = $DB['link_identifier'][$INPUT005] = @mysql_connect($INPUT001, $INPUT002, $INPUT003) or die('Could not connect to '.$INPUT005.'-database');
    mysql_select_db($INPUT004) or die('Could not select '.$INPUT005.'-database');
    mysql_query('SET NAMES utf8');
    mysql_query('SET CHARACTER SET utf8');
  }

    ?>