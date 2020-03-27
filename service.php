<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$_ENV['NOSHOW_COLUMNS'] = [
        "id",
        "created_by",
        "created_on",
        "edited_by",
        "edited_on",
    ];

handleActionMethodCalls();

function _service_listTables() {
    if(!_db()) return [];
    
    $tbls = _db()->get_tableList();
    
    return $tbls;
}
function _service_listColumns() {
    if(!_db()) return [];
    if(!isset($_GET['tbl']) || strlen($_GET['tbl'])<=0) return [];
    if(!isset($_GET['showHidden'])) $_GET['showHidden'] = "true";
    
    $_GET['tbl'] = explode(",",$_GET['tbl']);
    
    if(count($_GET['tbl'])>1) {
        $showtblname = true;
    } else {
        $showtblname = false;
    }
    
    $finalCols = [];
    foreach($_GET['tbl'] as $tbl) {
        $cols = _db()->get_columnList($tbl,false);
    
        if($_GET['showHidden']!="false") {
            if(isset($cols['id'])) unset($cols['id']);
            if(isset($cols['created_by'])) unset($cols['created_by']);
            if(isset($cols['created_on'])) unset($cols['created_on']);
            if(isset($cols['edited_by'])) unset($cols['edited_by']);
            if(isset($cols['edited_on'])) unset($cols['edited_on']);
        }
        
        foreach($cols as $k=>$v) {
            if(in_array($k, $_ENV['NOSHOW_COLUMNS'])) continue;
            
            if($showtblname) {
                $tblColName = "{$tbl}.{$v[0]}";
            } else {
                $tblColName = $v[0];
            }
            
            $finalCols[$tblColName] = [
                    "colkey"=>"{$tbl}.{$v[0]}",
                    "table"=>$tbl,
                    "name"=>$tblColName,
                    "type"=>$v[1],
                    "null"=>$v[2],
                    "key"=>$v[3],
                    "default"=>$v[4],
                    "extras"=>$v[5],
                ];
        }
    }
    
    return $finalCols;
}

function _service_datalistGroupidList() {
    $listData = _db()->_selectQ("do_lists","groupid,count(*) as max",["blocked"=>"false"])->_groupBy("groupid")->_GET();
    
    $fData = [];
    
    foreach($listData as $row) {
        $fData[$row['groupid']." ({$row['max']})"] = $row['groupid'];
    }
    
    return $fData;
}

function _service_listFormulas() {
    $jsonData = file_get_contents(__DIR__."/data/formulas.json");
    $jsonData = json_decode($jsonData, true);
    if($jsonData==null) $jsonData = [];
    return $jsonData;
}

function _service_listFormElements() {
    $jsonData = file_get_contents(__DIR__."/data/formfields.json");
    $jsonData = json_decode($jsonData, true);
    if($jsonData==null) $jsonData = [];
    return $jsonData;
}

function _service_listDesignBlock() {
    $jsonData = file_get_contents(__DIR__."/data/designBlocks.json");
    $jsonData = json_decode($jsonData, true);
    if($jsonData==null) $jsonData = [];
    return $jsonData;
}

function _service_previewlink() {
    if(!isset($_GET['dchash'])) return ["status"=>"error", "msg"=>"Hash Not Defined"];
    
    $dcHash = $_GET['dchash'];
    
    if(!isset($_SESSION['DCEDITOR'][$dcHash])) return ["status"=>"error", "msg"=>"Configuration Not Found, Try reloading"];
    
    $dcParams = $_SESSION['DCEDITOR'][$dcHash];
    
    $link = false;
    switch($dcParams['TYPE']) {
        case "reports":
            $link = substr($dcParams['SRCPATH'],strpos($dcParams['SRCPATH'],"reports/")+8);
            $link = str_replace(".json","",str_replace("/",".",$link));
            $link = "modules/reports/{$link}";
            break;
        case "forms":
            $link = substr($dcParams['SRCPATH'],strpos($dcParams['SRCPATH'],"forms/")+6);
            $link = str_replace(".json","",str_replace("/",".",$link));
            $link = "modules/forms/{$link}/new";
            break;
        case "infoview":case "infoviews":
            $link = substr($dcParams['SRCPATH'],strpos($dcParams['SRCPATH'],"forms/")+6);
            $link = str_replace(".json","",str_replace("/",".",$link));
            $link = "modules/infoviews/{$link}";
            break;
    }
    if($link) {
        $link = _link($link,false,CMS_SITENAME);
        
        return [
                "status"=>"success",
                "link"=>$link,
            ];
    } else {
        return ["status"=>"error", "msg"=>"Preview link not supported"];
    }
}

function _service_fetch() {
    if(!isset($_GET['dchash'])) return ["status"=>"error", "msg"=>"Hash Not Defined"];
    
    $dcHash = $_GET['dchash'];
    
    if(!isset($_SESSION['DCEDITOR'][$dcHash])) return ["status"=>"error", "msg"=>"Configuration Not Found, Try reloading"];
    
    $dcParams = $_SESSION['DCEDITOR'][$dcHash];
    //printArray($dcParams);exit();
    
    $dcFile = $dcParams['SRCPATH'];
    
    if(!file_exists($dcFile)) return ["status"=>"error", "msg"=>"Source File Does Not Exist"];
    
    $dcJSON = file_get_contents($dcFile);
    
    try {
        $dcJSON = json_decode($dcJSON, true);
        
        if(!isset($dcJSON['title'])) $dcJSON['title'] = "";
        if(!isset($dcJSON['category'])) $dcJSON['category'] = "";
        if(!isset($dcJSON['privilege'])) $dcJSON['privilege'] = "*";
        if(!isset($dcJSON['blocked'])) $dcJSON['blocked'] = false;
        if(!isset($dcJSON['schema'])) $dcJSON['schema'] = "{$dcParams['TYPE']}-1";
        
        return $dcJSON;
    } catch(Exception $e) {
        return ["status"=>"error", "msg"=>"Source file corrupt"];
    }
}

function _service_fetchScript() {
    if(!isset($_GET['dchash'])) return "";
    
    $dcHash = $_GET['dchash'];
    
    if(!isset($_SESSION['DCEDITOR'][$dcHash])) return "";
    
    $dcParams = $_SESSION['DCEDITOR'][$dcHash];
    //printArray($dcParams);exit();
    
    $dcFile = $dcParams['SRCPATH'];
    
    if(!file_exists($dcFile)) return "";
    
    $srcFile = str_replace(".json",".js",$dcFile);
    
    if(file_exists($srcFile)) {
        return file_get_contents($srcFile);
    } else {
        return "";
    }
}

function _service_fetchStyle() {
    if(!isset($_GET['dchash'])) return "";
    
    $dcHash = $_GET['dchash'];
    
    if(!isset($_SESSION['DCEDITOR'][$dcHash])) return "";
    
    $dcParams = $_SESSION['DCEDITOR'][$dcHash];
    //printArray($dcParams);exit();
    
    $dcFile = $dcParams['SRCPATH'];
    
    if(!file_exists($dcFile)) return "";
    
    $srcFile = str_replace(".json",".css",$dcFile);
    
    if(file_exists($srcFile)) {
        return file_get_contents($srcFile);
    } else {
        return "";
    }
}

function _service_save() {
    if(!isset($_GET['dchash'])) return "ERROR";
    
    $dcHash = $_GET['dchash'];
    
    if(!isset($_SESSION['DCEDITOR'][$dcHash])) return "ERROR";
    
    $dcParams = $_SESSION['DCEDITOR'][$dcHash];
    //printArray($dcParams);exit();
    
    $dcFile = $dcParams['SRCPATH'];
    $styleFile = CMS_APPROOT."css/{$dcParams['TYPE']}/".str_replace(".json",".css",basename($dcFile));
    //$styleFile = str_replace(".json",".css",$dcFile);
    $scriptFile = CMS_APPROOT."js/{$dcParams['TYPE']}/".str_replace(".json",".js",basename($dcFile));
    //$scriptFile = str_replace(".json",".js",$dcFile);
    //exit($scriptFile);
            
    if(file_exists($dcFile) && !is_writable($dcFile)) return "Error, Source File Not Editable";
    if(file_exists($styleFile) && !is_writable($styleFile)) return "Error, Style File Not Editable";
    if(file_exists($scriptFile) && !is_writable($scriptFile)) return "Error, Script File Not Editable";

    if(isset($_POST['style'])) {
        if(strlen($_POST['style'])>0 || file_exists($styleFile)) {
            $a = file_put_contents($styleFile, $_POST['style']);
        }
    }
    
    if(isset($_POST['script'])) {
        if(strlen($_POST['script'])>0 || file_exists($scriptFile)) {
            $a = file_put_contents($scriptFile, $_POST['script']);
        }
    }
    
    if(isset($_POST['qdata'])) {
        $qData = json_decode($_POST['qdata'], true);
        if($qData==null) {
            return "Error, JSON Encoding Failed";
        }
        
        if(!isset($qData['style'])) {
            if(file_exists($styleFile)) {
                $qData['style'] = str_replace(".css","",basename($styleFile));
            }
        }
        if(!isset($qData['script'])) {
            if(file_exists($scriptFile)) {
                $qData['script'] = str_replace(".js","",basename($scriptFile));
            }
        }
        
        $dcContent = [];
        if(file_exists($dcFile)) {
            $dcContent = json_decode(file_get_contents($dcFile), true);
            
            if($dcContent==null) $dcContent = [];
        }
        
        $qData = array_merge($dcContent, $qData);
        
        $a = file_put_contents($dcFile, json_encode($qData,JSON_PRETTY_PRINT));
    }
    
    echo "Save Successfull";
}

function _service_generate() {
    if(!isset($_GET['dcmode'])) return ["status"=>"error", "msg"=>"Mode Not Defined"];
    
    $fpath = "";
    
    return [
            "status"=>"success",
            "fpath"=>$fpath,
            "dcmode"=>$_GET['dcmode'],
        ];
}

function _service_formPreview() {
    // if(!isset($_POST['fields'])) return "ERROR";
    
    // $fieldsData = json_decode($_POST['fields'], true);
    // if($fieldsData==null) {
    //     return "Error, JSON Encoding Failed";
    // }
    
    formPreviewAddons();
    
    printForm("new", [
            "title"=>"Test Form",
            "category"=>"Test",
            "disable_simpleform"=>true,
            "template"=>"simple",
            //"source"=>[],
            "fields"=>[]
        ]);
    
    //printArray([$fieldsData,$dcParams,$dcFile]);exit();
}
function _service_formFieldPreview() {
    if(!isset($_POST['field'])) return "ERROR";
    if(!isset($_POST['key'])) return "ERROR";
    
    $fieldInfo = json_decode($_POST['field'], true);
    if($fieldInfo==null) {
        return "Error, JSON Encoding Failed";
    }
    
    $fieldkey = $_POST['key'];
    
    formPreviewAddons();
    
    $fieldInfo['fieldkey'] = $fieldkey;
    
    $fieldHTML = getFormFieldset([$fieldkey=>$fieldInfo]);
    
    $fieldHTML = substr($fieldHTML, 10, strlen($fieldHTML)-21);
    
    echo $fieldHTML;
}

function formPreviewAddons() {
    loadHelpers("shortfuncs");
    
    if(!function_exists("_css")) {
        include_once ROOT. "api/libs/logiksPages/boot.php";
        include_once ROOT. "api/libs/logikssession.php";
    }
    
    loadModuleLib("forms","api");
}

?>