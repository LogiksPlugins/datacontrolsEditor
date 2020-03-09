<?php
if(!defined('ROOT')) exit('No direct script access allowed');

loadModuleLib("datacontrols","api");

$slugs = _slug("a/dcmode/editmode/d");
//printArray($slugs);

if(strlen($slugs['dcmode'])<=0) {
    echo "<h1 align=center>DataControls Editor Not Found</h1>";
    return;
}
if(!isset($_GET['fpath']) || strlen($_GET['fpath'])<=0) {
    echo "<h1 align=center>DataControls Source File Not Found</h1>";
    return;
}

$basePath = getBasePath();
$srcPath = $basePath.$_GET['fpath'];

if(!file_exists($srcPath)) {
    echo "<h1 align=center>DataControls Source File Does Not Exist</h1>";
    return;
}

$editorFile = __DIR__."/editors/{$slugs['dcmode']}.php";

if(file_exists($editorFile)) {
    $dcHash = md5(time()."-".rand(100000,100000000));
    $_SESSION['DCEDITOR'][$dcHash] = [
            "TYPE"=>$slugs['dcmode'],
            "SRCPATH"=>$srcPath,
        ];
    //println($dcHash);
    include_once $editorFile;
} else {
    echo "<h1 align=center>DataControls Editor For `{$slugs['dcmode']}` Is Not Supported</h1>";
}
?>