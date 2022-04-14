
console.log = (function(...aArgs) {
    let pre = document.createElement("pre");
    pre.innerText = JSON.stringify(aArgs, null, 4);
    document.body.append(pre);
}).bind(console);

async function fnLoadFile(sFile)
{
    return await fetch(`/data/files/data/${sFile}`)
        .then((response) => {
            return response.text();
        })
}