<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',split:true" title="" style="width:500px;">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',split:true" title="" style="width:200px;">
                <div 
                    class="easyui-panel" 
                    title="  " 
                    style="padding:0px;"
                    data-options="tools:'#categories-tt', fit:true"
                >
                    <ul id="categories-tree" class="easyui-tree"></ul>
                </div>
                <div id="categories-tt">
                    <a href="javascript:void(0)" class="icon-add" id="categories-add-btn"></a>
                    <a href="javascript:void(0)" class="icon-edit" id="categories-edit-btn"></a>
                    <a href="javascript:void(0)" class="icon-remove" id="categories-remove-btn"></a>
                    <a href="javascript:void(0)" class="icon-reload" id="categories-reload-btn"></a>
                </div>
            </div>
            <div data-options="region:'center',title:'',iconCls:'icon-ok'">
                <div 
                    class="easyui-panel" 
                    title="  " 
                    style="padding:0px;"
                    data-options="tools:'#files-tt', fit:true"
                >
                    <ul id="files-list" class="easyui-datalist" title="" lines="true" data-options="fit:true"></ul>
                </div>
                <div id="files-tt">
                    <a href="javascript:void(0)" class="icon-add" id="files-add-btn"></a>
                    <a href="javascript:void(0)" class="icon-edit" id="files-edit-btn"></a>
                    <a href="javascript:void(0)" class="icon-remove" id="files-remove-btn"></a>
                    <a href="javascript:void(0)" class="icon-reload" id="files-reload-btn"></a>
                </div>
            </div>
        </div>
    </div>
    <div data-options="region:'center',title:'',iconCls:'icon-ok'">
        <div id="tabs" class="easyui-tabs" style="width:100%;height:100%"></div>
    </div>
</div>

<div style="position:fixed">
    <!-- Категории -->
    <div id="categories-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#categories-dlg-buttons'">
        <form id="categories-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Категория:</label>
                <input id="categories-category_id" name="category_id" class="easyui-combotree" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" required="true" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
        </form>
    </div>
    <div id="categories-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="categories-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" id="categories-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <!-- Файлы -->
    <div id="files-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#files-dlg-buttons'">
        <form id="files-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Категория:</label>
                <input id="files-category_id" name="category_id" required="true" class="easyui-combotree" style="width:100%">
            </div>
            <div style="margin-bottom:10px" id="files-name-fieldblock">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" style="width:100%;" id="files-name">
            </div>
            <div style="margin-bottom:10px">
                <label>Тип:</label>
                <input id="files-type" name="type" required="true" class="easyui-combobox" style="width:100%">
            </div>
            <div style="margin-bottom:10px" id="files-name-fieldblock">
                <label>Путь к файлу:</label>
                <input name="file_path" class="easyui-textbox" style="width:100%;" data-options="readonly:true">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
        </form>
    </div>
    <div id="files-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="files-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" id="files-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <div id="categories-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'add'">Добавить категорию</div>
        <div data-options="id:'move_to_root_category'">Переместить в корень</div>
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="files-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'add'">Добавить файл</div>
        <!-- <div data-options="id:'move_to_root_task'">Переместить в корень</div> -->
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
</div>

<script type="module">
import * as m from "./static/app/modules/__init__.js";

$(document).ready(() => {
    m.Categories.fnPrepare();
    m.Files.fnPrepare();
    m.RightTabs.fnPrepare();
})
</script>