<?php 

include_once("./database.php");

use RedBeanPHP\Logger as Logger;

if ($argv[1] == "nuke") {
    R::nuke();
    die();
}

if ($argv[1] == "truncate_category") {
    R::wipe(T_CATEGORIES);
    die();
}

if ($argv[1] == "list_tables") {
    $listOfTables = R::inspect();
    die(json_encode($listOfTables));
}

if ($argv[1] == "list_fields") {
    $fields = R::inspect($argv[2]);
    die(json_encode($fields));
}

if ($argv[1] == "create_scheme") {
    class MigrationLogger implements Logger {

        private $file;
    
        public function __construct( $file ) {
            $this->file = $file;
        }
    
        public function log() {
            $query = func_get_arg(0);
            if (preg_match( '/^(CREATE|ALTER)/', $query )) {
                file_put_contents( $this->file, "{$query};\n",  FILE_APPEND );
            }
        }
    }
    
    $ml = new MigrationLogger( sprintf( __DIR__.'/sql/migration_%s.sql', date('Y_m_d__H_i_s') ) );
    
    R::getDatabaseAdapter()
        ->getDatabase()
        ->setLogger($ml)
        ->setEnableLogging(TRUE);
    
    R::nuke();

    $oCategory = R::dispense(T_CATEGORIES);

    $oCategory->name = 'Тестовая категория';
    $oCategory->description = 'Тестовая категория';

    $oCategory2 = R::dispense(T_CATEGORIES);
    $oCategory2->name = 'Тестовая категория 2';
    $oCategory2->description = 'Тестовая категория 2';
    R::store($oCategory2);

    $oCategory->tcategories = $oCategory2;

    R::store($oCategory);


    $oFile = R::dispense(T_FILES);

    $oFile->created_at = date("Y-m-d H:i:s");
    $oFile->updated_at = date("Y-m-d H:i:s");
    $oFile->timestamp = time();
    $oFile->name = 'Тестовая заметка';
    $oFile->description = 'Тестовая заметка';
    $oFile->type = "php";
    $oFile->filename = 'test.php';
    $oFile->tcategories = $oCategory2;

    R::store($oFile);


    $oTag = R::dispense(T_TAGS);

    $oTag->created_at = date("Y-m-d H:i:s");
    $oTag->updated_at = date("Y-m-d H:i:s");
    $oTag->timestamp = time();
    $oTag->name = 'Тестовый тэг';

    R::store($oTag);

    $oTagToObjects = R::dispense(T_TAGS_TO_OBJECTS);

    $oTagToObjects->ttags = $oTag;
    $oTagToObjects->content_id = $oNote->id;
    $oTagToObjects->content_type = 'tnotes';
    $oTagToObjects->poly('contentType');

    R::store($oTagToObjects);

    R::trashBatch(T_FILES, [$oTask->id]);
    R::trashBatch(T_CATEGORIES, [$oCategory->id]);
    R::trashBatch(T_CATEGORIES, [$oCategory2->id]);
    R::trashBatch(T_TAGS, [$oTag->id]);
    R::trashBatch(T_TAGS_TO_OBJECTS, [$oTagToObjects->id]);

    die(json_encode([]));
}

function fnBuildRecursiveCategoriesTree(&$aResult, $aCategories) 
{
    $aResult = [];

    foreach ($aCategories as $oCategory) {
        $aTreeChildren = [];

        $aChildren = R::children($oCategory, " id != {$oCategory->id}");
        fnBuildRecursiveCategoriesTree($aTreeChildren, $aChildren);

        $aResult[] = [
            'id' => $oCategory->id,
            'text' => $oCategory->name,
            'children' => $aTreeChildren,
            'notes_count' => $oCategory->countOwn(T_FILES)
        ];
    }
}

