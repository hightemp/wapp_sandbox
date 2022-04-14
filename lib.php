<?php

function fnBuildRecursiveCategoriesTree(&$aResult, $aCategories) 
{
    $aResult = [];

    foreach ($aCategories as $oCategory) {
        $aTreeChildren = [];

        $aChildren = R::findAll(T_CATEGORIES, " tcategories_id = {$oCategory->id}");
        fnBuildRecursiveCategoriesTree($aTreeChildren, $aChildren);

        $aResult[] = [
            'id' => $oCategory->id,
            'text' => $oCategory->name,
            'name' => $oCategory->name,
            'description' => $oCategory->description,
            'category_id' => $oCategory->tcategories_id,
            'children' => $aTreeChildren,
            'notes_count' => $oCategory->countOwn(T_FILES)
        ];
    }
}

function fnBuildRecursiveCategoriesTreeDelete($oCategory) 
{
    $aChildren = R::findAll(T_CATEGORIES, " tcategories_id = {$oCategory->id}");

    foreach ($aChildren as $oChildCategory) {
        fnBuildRecursiveCategoriesTreeDelete($oChildCategory);
        R::trashBatch(T_CATEGORIES, [$oChildCategory->id]);
    }

    $aList = R::findAll(T_FILES, " tcategories_id = {$oCategory->id}");

    foreach ($aList as $oFile) {
        R::trashBatch(T_FILES, $oFile->id);
    }

    R::trashBatch(T_CATEGORIES, [$oCategory->id]);
}