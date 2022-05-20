import { tpl, fnAlertMessage, fnCreateEditor } from "./lib.js"

export class RightTabs {
    static sURL = ``

    static oTabsFilesPHPIndexes = {};
    static oTabsFilesPHPIDs = {};
    static oTabsFilesPHPNotSavedIDs = {};
    static oEditors = {};
    static oTabsFilesJSIndexes = {};
    static oTabsFilesJSIDs = {};
    static oTabsFilesJSNotSavedIDs = {};

    static oSelectedCell = null;
    static iSelectedRow = 0;
    static iSelectedColumn = 0;

    static oEvents = {
        tabs_save_content: "tabs:save_content",
        files_select: "files:select",
    }

    static oURLs = {
        get_file: tpl`ajax.php?method=get_file&id=${0}`,
        update_file_content: tpl`ajax.php?method=update_file_content&id=${0}`,
    }

    static oSelectors = {
        tabs_title: tpl`.tabs .tabs-title[data-index='${0}']`
    }

    static get oComponent() {
        return $("#tabs");
    }

    static get fnComponent() {
        return this.oComponent.tabs.bind(this.oComponent);
    }

    static fnGetFile(iID) {
        return $(`#files-${iID}`);
    }

    static fnBindEvents()
    {
        $(document).on(this.oEvents.files_select, ((oEvent, oNode) => {
            this.fnActionOpenFile(oNode.id, oNode.type);
        }).bind(this));

        $(document).on('keydown', (oEvent => {
            if (oEvent.ctrlKey && oEvent.key === 's') {
                oEvent.preventDefault();
                var iI = this.fnGetSelectedTabIndex();
                if (this.oTabsFilesPHPIDs[iI]) {
                    this.fnFireEvent_TabSaveContent();
                    this.fnActionSavePHPFileContent();
                }
                if (this.oTabsFilesJSIDs[iI]) {
                    this.fnFireEvent_TabSaveContent();
                    this.fnActionSaveJSFileContent();
                }
            }
        }).bind(this));
    }

    static fnFireEvent_TabSaveContent() {
        $(document).trigger(this.oEvents.tabs_save_content);
    }

    static fnInitComponent()
    {
        this.fnComponent({
            fit:true,
            tabPosition: 'left',
        })
    }

    static fnGetSelected() {
        return this.fnComponent('getSelected');
    }

    static fnGetTabIndex(oTab) {
        return this.fnComponent('getTabIndex', oTab);
    }

    static fnGetSelectedTabIndex() {
        return this.fnComponent('getTabIndex', this.fnGetSelected());
    }

    static fnSelect(oTarget) {
        this.fnComponent('select', oTarget);
    }

    static fnAddTab(oOptions) {
        this.fnComponent('add', oOptions);
    }

    static fnGetTabTitle(iIndex) {
        return $(this.oSelectors.tabs_title(iIndex)).text();
    }
    static fnSetTabTitle(iIndex, sTitle) {
        $(this.oSelectors.tabs_title(iIndex)).text(sTitle);
    }

    static fnAddTabTitleStar(iIndex) {
        var sTitle = this.fnGetTabTitle(iIndex);
        sTitle = `*${sTitle}`;
        this.fnSetTabTitle(iIndex, sTitle);
    }
    static fnRemoveTabTitleStar(iIndex) {
        var sTitle = this.fnGetTabTitle(iIndex);
        sTitle = sTitle.replace(/^\*/, '');
        this.fnSetTabTitle(iIndex, sTitle);
    }

    static fnSetDirtyPHPFile(iID) {
        if (this.oTabsFilesPHPNotSavedIDs[iID]) return;
        this.fnAddTabTitleStar(this.oTabsFilesPHPIndexes[iID]);
        this.oTabsFilesPHPNotSavedIDs[iID] = true;
    }
    static fnUnsetDirtyPHPFile(iID) {
        if (!this.oTabsFilesPHPNotSavedIDs[iID]) return;
        this.fnRemoveTabTitleStar(this.oTabsFilesPHPIndexes[iID]);
        this.oTabsFilesPHPNotSavedIDs[iID] = false;
    }
    static fnSetDirtyJSFile(iID) {
        if (this.oTabsFilesJSNotSavedIDs[iID]) return;
        this.fnAddTabTitleStar(this.oTabsFilesJSIndexes[iID]);
        this.oTabsFilesJSNotSavedIDs[iID] = true;
    }
    static fnUnsetDirtyJSFile(iID) {
        if (!this.oTabsFilesJSNotSavedIDs[iID]) return;
        this.fnRemoveTabTitleStar(this.oTabsFilesJSIndexes[iID]);
        this.oTabsFilesJSNotSavedIDs[iID] = false;
    }

    static fnActionSavePHPFileContent()
    {
        var iI = this.fnGetSelectedTabIndex();
        var iID = this.oTabsFilesPHPIDs[iI];

        $.post(
            this.oURLs.update_file_content(iID),
            {
                id: iID,
                content: this.oEditors[this.oTabsFilesPHPIDs[iI]].editor.value()
            }
        ).done((() => {
            this.fnUnsetDirtyNote(this.oTabsFilesPHPIDs[iI]);
            alert('123');

            $.messager.show({
                title: 'Сохранено',
                msg: 'Сохранено',
                showType:'show',
                style:{
                    left:'',
                    right:0,
                    top:document.body.scrollTop+document.documentElement.scrollTop,
                    bottom:''
                }
            });
        }).bind(this))
    }

    static fnActionSaveJSFileContent()
    {
        var iI = this.fnGetSelectedTabIndex();
        var iID = this.oTabsFilesJSIDs[iI];

        $.post(
            this.oURLs.update_file_content(iID),
            {
                id: iID,
                content: this.oEditors[this.oTabsFilesJSIDs[iI]].editor.value()
            }
        ).done((() => {
            this.fnUnsetDirtyTable(this.oTabsFilesJSIDs[iI]);

            $.messager.show({
                title: 'Сохранено',
                msg: 'Сохранено',
                showType: 'show',
                style:{
                    left:'',
                    right:0,
                    top:document.body.scrollTop+document.documentElement.scrollTop,
                    bottom:''
                }
            });
        }).bind(this))
    }

    static fnSelectByID(iID)
    {
        console.log(iID);
        if (this.oTabsFilesPHPIndexes[iID]) {
            this.fnSelect(this.oTabsFilesPHPIndexes[iID]);
        } else if (this.oTabsFilesJSIndexes[iID]) {
            this.fnSelect(this.oTabsFilesJSIndexes[iID]);
        }
    }

    static fnActionOpenFile(iID, sType)
    {
        if (this.fnGetFile(iID).length) {
            this.fnSelectByID(iID);
            return;
        }
        $.post(
            this.oURLs.get_file(iID),
            { id: iID },
            ((oR) => {
                this.fnAddTab({
                    title: oR.name+"."+oR.type,
                    content: `<iframe id="files-${iID}" src="index.php?id=${iID}&editor=${sType}"></iframe>`,
                    closable: true,
                });

                var iI = this.fnGetSelectedTabIndex();

                $(`.tabs .tabs-title:contains('${oR.name}')`).attr('data-index', iI);

                if (sType == 'php') {
                    this.oTabsFilesPHPIndexes[iID] = iI;
                    this.oTabsFilesPHPIDs[iI] = iID;
                }
                if (sType == 'js') {
                    this.oTabsFilesJSIndexes[iID] = iI;
                    this.oTabsFilesJSIDs[iI] = iID;
                }                
            }).bind(this),
            'json'
        );
    }

    static fnPrepare()
    {
        this.fnInitComponent();
        this.fnBindEvents();
    }
}
