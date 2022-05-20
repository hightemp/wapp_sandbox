<?php 

include_once("./database.php");

$sMainFile = "includes/layout.php";

if (isset($_GET['editor'])) {
    if ($_GET['editor']=='js') {
        $sMainFile = "includes/js_editor.php";
    } else if ($_GET['editor']=='php') {
        $sMainFile = "includes/php_editor.php";
    } else {
        $sMainFile = "includes/error.php";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>

    <link rel="shortcut icon" href="<?php echo $sBase ?>/static/app/favicon.png" type="image/png">

    <script src="<?php echo $sBA ?>/ace/ace.js" charset="utf-8"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $sB ?>/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $sB ?>/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $sBA ?>/styles_index.css">
    <script type="text/javascript" src="<?php echo $sB ?>/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $sB ?>/jquery.easyui.min.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/ace/ace.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/ace/mode-php.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/ace/worker-php.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/ace/mode-javascript.min.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/ace/worker-javascript.min.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/ace/ext-code_lens.min.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/ace/ext-searchbox.min.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/all.js"></script>

    <script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/highlight.js/latest/styles/github.min.css">
</head>
<body>
    <div id="main-panel">
        <?php include_once $sMainFile ?>
    </div>
</body>
</html>