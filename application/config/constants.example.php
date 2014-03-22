<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Application Constants
|--------------------------------------------------------------------------
|
| These are constants for the application
|
*/

// Challenges
define('TIMED_CHALLENGE', 0);
define('STEPS_CHALLENGE', 1);
define('SLEEP_CHALLENGE', 2);
define('MAX_CHALLENGE_CATEGORY', 2);
define('BASELINE', '2014-03-17');
define('VALID_STATS_BASELINE', '2014-03-17');
define('VALID_STATS_PERCENTAGE', 0.5);
define('WEEKLY_POINT_MAX', 50);
define('ACCESS_SECRET', 'root');
define('NO_SYNC_REMINDER', '- 4 days');
define('BADGE_DELAY_DAYS', 3);
define('WEEKLY_BADGE_PROCESS_DAY', 2); // tuesday
define('WEEKLY_TALLY_PROCESS_DAY', 3); // Wednesday
/* End of file constants.php */
/* Location: ./application/config/constants.php */