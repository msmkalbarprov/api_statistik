<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

//$db['default']['hostname'] = 'driver=SQL Server Native Client 10.0;server=36.92.96.237;uid=sa;pwd=ServerBpKad1*7@;database=simakda_2021;MARS_Connection=yes;';
// $db['default']['hostname'] = 'driver=SQL Server;server=103.148.49.74;uid=sa;pwd=ServerBpKad1*7@;database=simakda_2022;';
// $db['default']['username'] = '';
// $db['default']['password'] = '';
// $db['default']['database'] = 'simakda_2022';
// $db['default']['dbdriver'] = 'odbc';
// $db['default']['dbprefix'] = '';
// $db['default']['pconnect'] = FALSE;
// $db['default']['db_debug'] = TRUE;
// $db['default']['cache_on'] = FALSE;
// $db['default']['cachedir'] = '';
// $db['default']['char_set'] = 'utf8';
// $db['default']['dbcollat'] = 'utf8_general_ci';
// $db['default']['swap_pre'] = '';
// $db['default']['autoinit'] = TRUE;
// $db['default']['stricton'] = FALSE;


// $db['default']['hostname'] = 'driver=SQL Server;server=103.148.49.74;uid=sa;pwd=ServerBpKad1*7@;database=simakda_2022;';
 // $db['default']['hostname'] = 'db_simakda_2022';
// $db['default']['hostname'] = '103.148.49.74';
// // $db['default']['port'] 	   = '1433';
// $db['default']['username'] = 'sa';
// $db['default']['password'] = 'ServerBpKad1*7@';
// $db['default']['database'] = 'simakda_2022';
// $db['default']['dbdriver'] = 'sqlsrv';
// $db['default']['dbprefix'] = '';
// $db['default']['pconnect'] = FALSE;
// $db['default']['db_debug'] = TRUE;
// $db['default']['cache_on'] = FALSE;
// $db['default']['cachedir'] = '';
// $db['default']['char_set'] = 'utf8';
// $db['default']['dbcollat'] = 'utf8_general_ci';
// $db['default']['swap_pre'] = '';
// $db['default']['autoinit'] = TRUE;
// $db['default']['stricton'] = FALSE;

$db['default']['hostname'] = 'driver=SQL Server;server=WIN-E22T0AOIA6O;uid=sa;pwd=Server7m@kda;database=simakda_2023;';
$db['default']['username'] = '';
$db['default']['password'] = '';
$db['default']['database'] = 'db_simakda_2023';
$db['default']['dbdriver'] = 'odbc';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* 
$db['default']['hostname'] = 'tox_7';
$db['default']['username'] = 'sa';
$db['default']['password'] = 'toxx';
$db['default']['database'] = 'siadinda_simakda';
$db['default']['dbdriver'] = 'odbc';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

 */


/* 
$db['s_procedure']['hostname'] = 'localhost';
$db['s_procedure']['username'] = 'root';
$db['s_procedure']['password'] = '';
$db['s_procedure']['database'] = 'simakda_ci';
$db['s_procedure']['dbdriver'] = 'mysqli';
$db['s_procedure']['dbprefix'] = '';
$db['s_procedure']['pconnect'] = FALSE;
$db['s_procedure']['db_debug'] = TRUE;
$db['s_procedure']['cache_on'] = FALSE;
$db['s_procedure']['cachedir'] = '';
$db['s_procedure']['char_set'] = 'utf8';
$db['s_procedure']['dbcollat'] = 'utf8_general_ci';
$db['s_procedure']['swap_pre'] = '';
$db['s_procedure']['autoinit'] = TRUE;
$db['s_procedure']['stricton'] = FALSE;
 */


/* End of file database.php */
/* Location: ./application/config/database.php */