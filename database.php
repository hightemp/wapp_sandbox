<?php

ini_set('display_errors', 1);

include_once("./config.php");

define('T_CATEGORIES', 'tcategories');
define('T_FILES', 'tfiles');
define('T_DATA_FILES', 'tdatafiles');

define('T_TAGS', 'ttags');
define('T_TAGS_TO_OBJECTS', 'ttagstoobjectss');

if (Config::$aOptions["database"]["schema"] == "sqlite") {
    R::setup('sqlite:./db/dbfile.db');
} else {
    R::setup('mysql:host=localhost;dbname=mydatabase', 'user', 'password' );
}

if(!R::testConnection()) die('No DB connection!');

