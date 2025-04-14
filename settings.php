<?php

///////////////////////////////////////
// DEBUG SETTINGS
///////////////////////////////////////

// Print mail only
define("DEBUG_SEND_NO_MAIL", false); // LIVE: false

// Send mail to test adres ipv klant in api data
define("DEBUG_TEST_MAIL", false); // LIVE: false

// Niet naar database schrijven, skip write
define("DEBUG_NO_DATABASE", false); // LIVE: false

// Use test data
define("DEBUG_TWOSOLAR_USE_TESTDATA", false); // LIVE: false
// Testdata aanpassen op datum zodat het direct verstuurd wordt
define("DEBUG_TWOSOLAR_USE_TESTDATA_NO_DELAY", true); // LIVE: n.v.t.

// Alles direct afhandelen, wachttijd niet checken
define("DEBUG_NO_DELAY", false); // LIVE: false

// Change time days (increase delay with days)
define("DEBUG_BACK_IN_TIME", 0);

// Mail alles naar
define("DEBUG_MAIL_ADDRESS", "");

///////////////////////////////////////
// SETTINGS
///////////////////////////////////////

// Zapier Transport mail
define("ZAPIER_TRANSPORT_MAIL", "[MAIL@OUTLOOK.COM]");

// Log level for script log
define("LOG_LEVEL", 2);

// Datahandler
define("DATA_HANDLER", 'Mysql'); // 'FileService', 'Mysql', 'Pgsql'

// Mailer
define("MAILER", 'PHPMailer');   // 'Symfony', 'PHPMailer'

// API Bearer
define("TWOSOLOR_API_KEY", "");
define("TWOSOLOR_API_URL", "https://app.2solar.nl/api/");

// DATABASE
define("MYSQL_SERVER", "localhost");
define("MYSQL_DB", "");
define("MYSQL_USER", "");
define("MYSQL_PASS", "");

// SMTP MAIL
define("MAIL_SMTP_HOST", '');
define("MAIL_USERNAME", '');
define("MAIL_PASSWORD", "");
define("MAIL_SMTP_PORT", 465);
define("MAIL_FROM", "");
define("MAIL_FROM_NAME", "");
