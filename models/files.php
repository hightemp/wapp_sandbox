<?php

function fnPrepareFilePath($sType, $sFileName)
{
    return DATA_FILES_PATH."/".$sType."/".$sFileName;
}

function fnGetFile($iID)
{
    return R::findOne(T_FILES, "id = ?", [$iID]);
}

function fnGetFilePathByID($iID)
{
    $oFile = fnGetFile($iID);
    
    return fnPrepareFilePath($oFile->type, $oFile->filename);
}

function fnGetFileRelPathByID($iID)
{
    $sFilePath = fnGetFilePathByID($iID);

    return str_replace(ROOT_PATH, "", $sFilePath);
}

function fnGetFileContentByID($iID)
{
    return file_get_contents(fnGetFilePathByID($iID));
}

function fnSaveFileContent($iID, $sContent)
{
    $sFilePath = fnGetFilePathByID($iID);
    file_put_contents($sFilePath, $sContent);
}

function fnRemoveFileByID($iID)
{
    $sFilePath = fnGetFilePathByID($iID);
    R::trashBatch(T_FILES, [$iID]);
    unlink($sFilePath);
}