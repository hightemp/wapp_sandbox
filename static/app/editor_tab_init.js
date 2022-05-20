function fnSave() 
{
    return $.post(
        `ajax.php?method=save_file_content&id=${window.iID}`,
        { 
            id: window.iID,
            content: oE.getValue() 
        },
        () => {},
        'json'
    ).done(() => {
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
    })
}

function fnReloadOutput()
{
    $("#output")[0].contentWindow.location.reload();
}

$(document).ready(() => {
    window.oE.on('change', function() {
        
    });

    $("#editor-save-btn").click(() => {
        fnSave();
    })
    $("#editor-run-btn").click(() => {
        fnSave().done(() => fnReloadOutput())
    })
    $("#editor-reload-btn").click(() => {
        location.reload();
    })
    $("#output-reload-btn").click(() => {
        fnReloadOutput();
    })
})

$(document).on('keydown', oEvent => {
    if (oEvent.ctrlKey && oEvent.key === 's') {
        oEvent.preventDefault();
        fnSave();
    }
})