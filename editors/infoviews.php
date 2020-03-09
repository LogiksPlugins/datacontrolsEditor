<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!isset($_REQUEST['panel'])) $_REQUEST['panel']="designer";

// printArray([
//         $srcPath,
//         $basePath,
//         $dcHash
//     ]);

loadModule("pages");

function pageSidebar() {
    return "<div id='componentTree' class='componentTree list-group list-group-root'>
        <select class='form-control select'>
        </select>
    </div>";
}

function pageContentArea() {
    return "
    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-10 bodySpace'>
        <h5>&nbsp;&nbsp;Infoview Editor : <span id='dcTitle'></span> <div id='dcTags' class='pull-right' style='margin-right:10px;'></div></h5>
        <ul class='nav nav-tabs nav-justified'>
            <li class='active'><a href='#editorProperties' data-toggle='tab' role='tab' aria-controls='nav-editorProperties' aria-selected='true'>Properties</a></li>
            <li class='disabled'><a href='#editorTabs' role='tab' aria-controls='nav-editorTabs' aria-selected='false'>Tabs Details</a></li>
            <li class='disabled'><a href='#editorTabsAttributes' role='tab' aria-controls='nav-editorTabsAttributes' aria-selected='false'>Tabs Attributes</a></li>
            <!--<li><a href='#editorHooks' data-toggle='tab' role='tab' aria-controls='nav-editorHooks' aria-selected='false'>Hooks</a></li>-->
            <li><a href='#editorScript' data-toggle='tab' role='tab' aria-controls='nav-editorScript' aria-selected='false'>Script</a></li>
            <li><a href='#editorStyles' data-toggle='tab' role='tab' aria-controls='nav-editorStyles' aria-selected='false'>Styles</a></li>
            
        </ul>
        <div class='tab-content'>
            <div id='editorTabs' class='table-responsive tab-pane fade'>TAB DETAILS
                <table class='table table-responsive table-hover'>
                    <tbody></tbody>
                </table>
            </div>
            <div id='editorTabsAttributes' class='table-responsive tab-pane fade'>TAB ATTRIBUTES
                <table class='table table-responsive table-hover'>
                    <tbody></tbody>
                </table>
            </div>
            <div id='editorHooks' class='table-responsive tab-pane fade'>
                editorHooks
            </div>
            <div id='editorScript' class='table-responsive tab-pane fade'>
                <textarea id='codeEditorScript' class='editorArea' placeholder='Script Editor/JS ...'></textarea>
            </div>
            <div id='editorStyles' class='table-responsive tab-pane fade'>
                <textarea id='codeEditorStyle' class='editorArea' placeholder='Style Sheet/CSS Editor'></textarea>
            </div>
            <div id='editorProperties' class='table-responsive tab-pane fade in active'>
                editorProperties
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-3 col-md-3 col-lg-2 sidebarSpace'>
        <ul class='nav nav-tabs nav-justified'>
            <li class='active'><a href='#tabList' data-toggle='tab' role='tab' aria-controls='nav-tabList' aria-selected='true'>Tabs</a></li>
        </ul>
        <div class='tab-content'>
            <div id='tabList' class='tab-pane fade in active'>
                <div id='tableTree' class='componentTree list-group list-group-root'>
                  
              </div>
            </div>
            <div id='dbFormElements' class='tab-pane fade'>
                <div id='formElementTree' class='componentTree list-group list-group-root'>
                  
              </div>
            </div>
        </div>
    </div>
    ";
}

$toolBar = [
        "refreshUI"=>["icon"=>"<i class='fa fa-refresh'></i>","tips"=>"Refresh"],
        ['type'=>"bar"],
        // "cloneDC"=>["icon"=>"<i class='fa fa-copy'></i>","title"=>"Clone"],
        "previewDC"=>["icon"=>"<i class='fa fa-eye'></i>","title"=>"Preview"],
        ['type'=>"bar"],
        "codeEditorDC"=>["icon"=>"<i class='fa fa-code'></i>","title"=>"Review"],
        "saveDC"=>["icon"=>"<i class='fa fa-save'></i>","title"=>"Save"],
        
        "addInfoviewTab"=>["icon"=>"<i class='fa fa-plus'></i>","align"=>"right","title"=>"Add Tab"],
        
        //"trashDC"=>["icon"=>"<i class='fa fa-trash'></i>","tips"=>"Delete"],
        
            //["title"=>"Search Store","type"=>"search","align"=>"right"],
        
        // "panelDesigner"=>["title"=>"Reports","align"=>"left","class"=>($_REQUEST['panel']=="designer")?"active":""],
//          "panelScript"=>["title"=>"Script","align"=>"left","class"=>($_REQUEST['panel']=="script")?"active":""],
        // "panelStyle"=>["title"=>"Styles","align"=>"left","class"=>($_REQUEST['panel']=="style")?"active":""],
//          "panelVisuals"=>["title"=>"Visuals","align"=>"right","class"=>($_REQUEST['panel']=="visuals")?"active":""],
//          "panelViews"=>["title"=>"Views","align"=>"right","class"=>($_REQUEST['panel']=="views")?"active":""],
];

$moduleName = "datacontrolsEditor";

echo _css([$moduleName]);
echo _js($moduleName);

printPageComponent(false,[
    "toolbar"=>$toolBar,
    "sidebar"=>false,
    "contentArea"=>"pageContentArea"
  ]);
?>
<style>

</style>
<script>
const dcHash = "<?=$dcHash?>";
const dcMode = "<?=$slugs['dcmode']?>";
var eleSidebarTable = '<li class="list-group-item" data-column="{{name}}" data-type="{{type}}" data-null="{{null}}" data-key="{{key}}" data-default="{{default}}" data-extras="{{extras}}">{{name}}</li>';
var eleSidebarFormElements = '<li class="list-group-item" data-element="{{element}}">{{name}}</li>';
var dcConfig = null;
$(function() {
    eleSidebarTable = Handlebars.compile(eleSidebarTable);
    eleSidebarFormElements = Handlebars.compile(eleSidebarFormElements);
    
    w = $(".sidebarSpace").width();
    if(w==null || w<200) w = 200;
    
    $("#pgtoolbar .nav.navbar-right").append("<li style='margin-top: 3px;width: "+w+"px;'><select class='form-control' id='tableDropdown'></select></li>");
    // $("#tableDropdown").change(loadColumnList);
    
    // $("#tableDropdown").load(_service("datacontrolsEditor","listTables","select"), function() {
    //     loadColumnList();
    // });
    
    // loadFormElements();
    loadDC();
    
});
function refreshUI() {
    window.location.reload();
    // $("#tableDropdown").load(_service("datacontrolsEditor","listTables","select"), function() {
    //     loadColumnList();
    // });
    //loadFormulas();
}
function updateDCPanelUI() {
    console.log(dcConfig);
    $("#dcTitle").html(dcConfig.title);
    $("#dcTags").html("<label class='label label-success'>"+dcConfig.category+"</label>");
}
</script>