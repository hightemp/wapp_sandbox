<?php 
$sFilePath = fnGetFileRelPathByID($_GET['id']);
$sFileName = basename($sFilePath);
$sContent = fnGetFileContentByID($_GET['id']);
?>

<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',split:true" title="" style="width:50%;">
        <div 
            class="easyui-panel" 
            title="<?php echo $sFileName ?>" 
            style="padding:0px;"
            data-options="tools:'#editor-tt', fit:true"
        >
            <textarea id="editor" style="width:100%;height:100%"><?php echo $sContent; ?></textarea>
        </div>
        <div id="editor-tt">
            <a href="javascript:void(0)" class="icon-save" id="editor-save-btn"></a>
            <a href="javascript:void(0)" class="icon-ok" id="editor-run-btn"></a>
            <a href="javascript:void(0)" class="icon-reload" id="editor-reload-btn"></a>
        </div>
    </div>
    <div data-options="region:'center',title:'',iconCls:'icon-ok'">
        <div 
            class="easyui-panel" 
            title=" " 
            style="padding:0px;"
            data-options="tools:'#output-tt', fit:true"
        >
            <iframe id="output" src="/js_output.php?file=<?php echo urlencode($sFilePath) ?>"></iframe>
        </div>
        <div id="output-tt">
            <a href="javascript:void(0)" class="icon-reload" id="output-reload-btn"></a>
        </div>
    </div>
</div>

<script type="module">
import * as lib from './static/app/modules/lib.js'

window.iID = <?php echo $_GET['id']; ?>;
window.oE = lib.fnCreateEditor("editor", "js");
</script>
<script src="/static/app/editor_tab_init.js"></script>