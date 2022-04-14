<?php

if ($sMethod == 'list_types') {
    $aResult = [
        [
            "text" => "php"
        ],
        [
            "text" => "js"
        ],
    ];

    die(json_encode($aResult));
}

if ($sMethod == 'list_files') {
    $aList = R::findAll(T_FILES, 'tcategories_id = ? ORDER BY id DESC', [$aRequest['category_id']]);
    $aResult = [];

    foreach ($aList as $oFile) {
        $aResult[] = [
            'id' => $oFile->id,
            'text' => $oFile->name.".".$oFile->type,
            'name' => $oFile->name,
            'description' => $oFile->description,
            'category_id' => $oFile->tcategories_id,
            'file_path' => fnGetFilePathByID($oFile->id),
            'type' => $oFile->type,
        ];
    }

    die(json_encode(array_values($aResult)));
}

if ($sMethod == 'get_file') {
    $aResponse = R::findOne(T_FILES, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_file') {
    fnRemoveFileByID($aRequest['id']);

    die(json_encode([]));
}

if ($sMethod == 'save_file_content') {
    fnSaveFileContent($aRequest['id'], $aRequest['content']);

    die(json_encode([]));
}

if ($sMethod == 'update_file') {
    $oFile = R::findOne(T_FILES, "id = ?", [$aRequest['id']]);

    $oFile->updated_at = date("Y-m-d H:i:s");
    $oFile->name = $aRequest['name'];
    $oFile->description = $aRequest['description'];
    $oFile->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
    $oFile->type = $aRequest['type'];

    R::store($oFile);

    die(json_encode([
        "id" => $oFile->id, 
        "text" => $oFile->text
    ]));
}

if ($sMethod == 'create_file') {    
    $oFile = R::dispense(T_FILES);

    $oFile->created_at = date("Y-m-d H:i:s");
    $oFile->updated_at = date("Y-m-d H:i:s");
    $oFile->timestamp = time();
    $oFile->name = $aRequest['name'];
    $oFile->description = $aRequest['description'];
    $oFile->tcategories = R::findOne(T_CATEGORIES, "id = ?", [$aRequest['category_id']]);
    $oFile->type = $aRequest['type'];
    $oFile->filename = $oFile->timestamp.".".$aRequest['type'];

    R::store($oFile);

    die(json_encode([
        "id" => $oFile->id, 
        "text" => $oFile->text
    ]));
}

if ($sMethod == 'run_php_file') {
    $oFile = R::findOne(T_FILES, "id = ?", [$aRequest['id']]);
    $sFilePath = DATA_PATH."/".$oFile->filename;

    $sContent = file_get_contents($sFilePath);
    $sContent = preg_match("/^\s*<\?php/i", "", $sContent);
    
    $sBuffer = "";
    ob_start();
    try {
        eval($sContent);
        $sBuffer = ob_end_clean();
    } catch(Exception $oE) {
        $sBuffer = $oE->getMessage();
    }

    die($sBuffer);
}