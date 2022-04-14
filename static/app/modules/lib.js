export function tpl(strings, ...keys) {
    return (function(...values) {
        let dict = values[values.length - 1] || {};
        let result = [strings[0]];
        keys.forEach(function(key, i) {
            let value = Number.isInteger(key) ? values[key] : dict[key];
            result.push(value, strings[i + 1]);
        });
        return result.join('');
    });
}

export function fnAlertMessage(sMessage) {
    // alert(sMessage);
    $.messager.alert('', sMessage);
}


export function fnCreateEditor(sEditorID, sType="js", sContent=null, oOptions={})
{
    var iTabSize = oOptions['tabsize'] ?? 4;
    var sEditorID = oOptions['editor_id'] ?? "editor";
    var sEditorIDSel = `#${sEditorID}`;
    var sACEEditorID = `ace-${sEditorID}`;
    var oEditorIDSel = $(sEditorIDSel);

    if (sContent === null) {
        sContent = oEditorIDSel.text();
    }
    oEditorIDSel.parent().prepend('<div id="' + sACEEditorID + '" class="' + sEditorID + '"></div>');

    var oACEEditor = $("#"+sACEEditorID);
    oACEEditor.text(sContent);
    oEditorIDSel.hide();

    var oEditor = ace.edit(sACEEditorID);

    oEditor.focus();
    oEditor.gotoLine(3, 0);

    // set mode
    if (sType="php") {
        var PhpMode = require("ace/mode/php").Mode;
        oEditor.getSession().setMode(new PhpMode());
    } else if (sType="js") {
        var JSMode = require("ace/mode/javascript").Mode;
        oEditor.getSession().setMode(new JSMode());
    }

    // tab size
    if (iTabSize) {
        oEditor.getSession().setTabSize(iTabSize);
        oEditor.getSession().setUseSoftTabs(true);
    } else {
        oEditor.getSession().setUseSoftTabs(false);
    }

    return oEditor;
}