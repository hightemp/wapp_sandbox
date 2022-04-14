import { tpl, fnAlertMessage } from "./lib.js"

export class Files {
    static sURL = ``

    static _oSelected = null;
    static _oSelectedCategory = null;
    
    static oURLs = {
        create: 'ajax.php?method=create_file',
        update: tpl`ajax.php?method=update_file&id=${0}`,
        delete: 'ajax.php?method=delete_file',
        list: tpl`ajax.php?method=list_files&category_id=${0}`,

        list_tree_categories: `ajax.php?method=list_tree_categories`,
        list_types: `ajax.php?method=list_types`,
    }
    static oWindowTitles = {
        create: 'Новый файл',
        update: 'Редактировать файл'
    }
    static oEvents = {
        files_save: "files:save",
        files_select: "files:select",
        categories_select: "categories:select",
    }

    static get oDialog() {
        return $('#files-dlg');
    }
    static get oDialogForm() {
        return $('#files-dlg-fm');
    }
    static get oComponent() {
        return $("#files-list");
    }
    static get oContextMenu() {
        return $("#files-mm");
    }

    static get oCategoryTreeList() {
        return $("#files-category_id");
    }
    static get oTypeList() {
        return $("#files-type");
    }
    

    static get oEditDialogSaveBtn() {
        return $('#files-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#files-dlg-cancel-btn');
    }

    static get oPanelAddButton() {
        return $('#files-add-btn');
    }
    static get oPanelEditButton() {
        return $('#files-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#files-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#files-reload-btn');
    }

    static get fnComponent() {
        return this.oComponent.datalist.bind(this.oComponent);
    }

    static get oSelectedCategory() {
        return this._oSelected;
    }

    static fnShowDialog(sTitle) {
        this.oDialog.dialog('open').dialog('center').dialog('setTitle', sTitle);
    }
    static fnDialogFormLoad(oRows={}) {
        this.oDialogForm.form('clear');
        this.oDialogForm.form('load', oRows);
        this.oCategoryTreeList.combotree('setValue', this._oSelectedCategory.id);
    }

    static fnShowCreateWindow() {
        this.sURL = this.oURLs.create;
        var oData = {}
        this.fnShowDialog(this.oWindowTitles.create);
        this.fnDialogFormLoad(oData);
    }

    static fnShowEditWindow(oRow) {
        if (oRow) {
            this.sURL = this.oURLs.update(oRow.id);
            this.fnShowDialog(this.oWindowTitles.update);
            this.fnDialogFormLoad(oRow);
        }
    }

    static fnReload() {
        this.fnComponent('reload', this.oURLs.list(this._oSelectedCategory.id));
    }

    static fnSave() {
        this.oDialogForm.form('submit', {
            url: this.sURL,
            iframe: false,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: (function(result){
                this.oDialog.dialog('close');
                this.fnReload();
                this.fnReloadLists();

                this.fnFireEvent_Save();
            }).bind(this)
        });
    }

    static fnDelete(oRow) {
        if (oRow){
            $.messager.confirm(
                'Confirm',
                'Удалить?',
                (function(r) {
                    if (r) {
                        $.post(
                            this.oURLs.delete,
                            { id: oRow.id },
                            (function(result) {
                                this.fnReload();
                            }).bind(this),
                            'json'
                        );
                    }
                }).bind(this)
            );
        }
    }

    static fnMoveToRoot(oRow) {
        if (oRow){
            $.post(
                this.oURLs.move_to_root_file,
                { id: oRow.id },
                (function(result) {
                    this.fnReload();
                    this.fnReloadLists();
                }).bind(this),
                'json'
            );
        }
    }

    static fnGetSelected() {
        return this.fnComponent('getSelected');
    }

    static fnSelect(oTarget) {
        this.fnComponent('select', oTarget);
    }

    static fnReloadLists() {
        this.oCategoryTreeList.combotree('reload');
        this.oTypeList.combobox('reload');
    }

    static fnBindEvents()
    {
        $(document).on('keydown', oEvent => {
            if (oEvent.ctrlKey && oEvent.key === 's') {
                oEvent.preventDefault();
                fnSave();
            }
        })
        
        $(document).on(this.oEvents.files_select, ((oEvent, oNode) => {

        }).bind(this))

        $(document).on(this.oEvents.categories_select, ((oEvent, oNode) => {
            this._oSelectedCategory = oNode;
            this.fnReloadLists();
            this.fnReload();
        }).bind(this))

        this.oEditDialogSaveBtn.click((() => {
            this.fnSave();
        }).bind(this))
        this.oEditDialogCancelBtn.click((() => {
            this.oDialog.dialog('close');
        }).bind(this))

        this.oPanelAddButton.click((() => {
            this.fnShowCreateWindow();
        }).bind(this))
        this.oPanelEditButton.click((() => {
            this.fnShowEditWindow(this.fnGetSelected());
        }).bind(this))
        this.oPanelRemoveButton.click((() => {
            this.fnDelete(this.fnGetSelected());
        }).bind(this))
        this.oPanelReloadButton.click((() => {
            this.fnReload();
        }).bind(this))
    }

    static fnFireEvent_Save() {
        $(document).trigger(this.oEvents.files_save);
    }

    static fnFireEvent_Select(oNode) {
        $(document).trigger(this.oEvents.files_select, [oNode])
    }

    static fnInitComponentCategoryTreeList()
    {
        this.oCategoryTreeList.combotree({
            url: this.oURLs.list_tree_categories,
            method: 'get',
            labelPosition: 'top',
            width: '100%',
        })
    }

    static fnInitComponentTypeList()
    {
        this.oTypeList.combobox({
            url: this.oURLs.list_types,
            valueField: 'text',
            textField: 'text',
            method: 'get',
            labelPosition: 'top',
            width: '100%',
        })
    }


    static fnInitComponent()
    {
        this.fnComponent({
            url: 'ajax.php',
            lines: true,

            onSelect: ((iIndex, oNode) => {
                this._oSelected = oNode;
                this.fnFireEvent_Select(oNode);
            }).bind(this),
            onRowContextMenu: (function(e, node) {
                e.preventDefault();
                this.fnSelect(node.target);
                this.oContextMenu.menu('show', {
                    left: e.pageX,
                    top: e.pageY,
                    onClick: ((item) => {
                        if (item.id == 'add') {
                            this.fnShowCreateWindow();
                        }
                        if (item.id == 'edit') {
                            this.fnShowEditWindow(node);
                        }
                        if (item.id == 'delete') {
                            this.fnDelete(node);
                        }
                        if (item.id == 'move_to_root_file') {
                            this.fnMoveToRoot(node);
                        }
                    }).bind(this)
                });
            }).bind(this),
        })
    }

    static fnPrepare()
    {
        this.fnBindEvents();
        this.fnInitComponentCategoryTreeList();
        this.fnInitComponentTypeList();
        this.fnInitComponent()
    }
}