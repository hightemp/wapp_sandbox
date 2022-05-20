<?php

if ($sMethod == 'data_files_list_types') {
    $aResult = [
        [
            "text" => "txt"
        ],
        [
            "text" => "json"
        ],
        [
            "text" => "csv"
        ],
        [
            "text" => "xlsx"
        ],
    ];

    die(json_encode($aResult));
}

if ($sMethod == 'list_data_files') {
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

if ($sMethod == 'get_data_file') {
    $aResponse = R::findOne(T_FILES, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_data_file') {
    fnRemoveFileByID($aRequest['id']);

    die(json_encode([]));
}

if ($sMethod == 'save_data_file_content') {
    fnSaveFileContent($aRequest['id'], $aRequest['content']);

    die(json_encode([]));
}

if ($sMethod == 'update_data_file') {
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

if ($sMethod == 'create_data_file') {    
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