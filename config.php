<?php

error_reporting(E_ERROR | E_PARSE);

class Config {
    public static $aOptions = [];

    public static function fnLoad()
    {
        static::$aOptions = json_decode(file_get_contents("./config.json"), true) ?: [];
    }

    public static function fnSave()
    {
        file_put_contents("./config.json", json_encode(static::$aOptions));
    }
}

Config::fnLoad();

$sBase = Config::$aOptions["base"];

$sBA = $sBase."/static/app";
$sB = $sBase."/static/app/jquery-easyui-1.10.2";

define('ROOT_PATH', __DIR__);
define('DATA_PATH', __DIR__."/data");
define('DATA_FILES_PATH', __DIR__."/data/files");
define('DATA_PHP_FILE_PATH', __DIR__."/data/files/js");
define('DATA_JS_FILES_PATH', __DIR__."/data/files/php");

include_once("./lib.php");
include_once("rb.php");

include_once("./models/files.php");
include_once("./models/tags.php");
