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
        <h5>&nbsp;&nbsp;Form Editor : <span id='dcTitle'></span> <div id='dcTags' class='pull-right' style='margin-right:10px;'></div></h5>
        <ul class='nav nav-tabs nav-justified'>
            <li class='active'><a href='#editorForm' data-toggle='tab' role='tab' aria-controls='nav-editorTable' aria-selected='true'>Form Design</a></li>
            <li><a href='#editorForceFill' data-toggle='tab' role='tab' aria-controls='nav-editorForceFill' aria-selected='false'>ForceFill</a></li>
            <li><a href='#editorHooks' data-toggle='tab' role='tab' aria-controls='nav-editorHooks' aria-selected='false'>Hooks</a></li>
            <li><a href='#editorScript' data-toggle='tab' role='tab' aria-controls='nav-editorScript' aria-selected='false'>Script</a></li>
            <li><a href='#editorStyles' data-toggle='tab' role='tab' aria-controls='nav-editorStyles' aria-selected='false'>Styles</a></li>
            <li><a href='#editorProperties' data-toggle='tab' role='tab' aria-controls='nav-editorProperties' aria-selected='false'>Properties</a></li>
        </ul>
        <div class='tab-content'>
            <div id='editorForm' class='tableColumReceiver table-responsive tab-pane fade in active'>
                <div id='formDesigner' class='formDesigner'>
                    
                </div>
            </div>
            <div id='editorForceFill' class='table-responsive tab-pane fade'>
                <table class='table table-responsive table-hover'>
                <thead>
                    <tr>
                        <th width='100px'>SL#</th>
                        <th>Data Field</th>
                        <th>Force Fill Value</th>
                        <th>--</th>
                    </tr>
                </thead>
                <tbody></tbody>
                </table>
            </div>
            <div id='editorHooks' class='table-responsive tab-pane fade'>
                <table class='table table-responsive table-hover'>
                <thead>
                    <tr>
                        <th width='100px'>SL#</th>
                        <th>Hook State/Type</th>
                        <th>Call Type</th>
                        <th>Methods, etc</th>
                        <th>--</th>
                    </tr>
                </thead>
                <tbody></tbody>
                </table>
            </div>
            <div id='editorScript' class='table-responsive tab-pane fade'>
                <textarea id='codeEditorScript' class='editorArea' placeholder='Script Editor/JS ...'></textarea>
            </div>
            <div id='editorStyles' class='table-responsive tab-pane fade'>
                <textarea id='codeEditorStyle' class='editorArea' placeholder='Style Sheet/CSS Editor'></textarea>
            </div>
            <div id='editorProperties' class='table-responsive tab-pane fade'>
                <table class='table table-responsive'>
                <tbody></tbody></table>
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-3 col-md-3 col-lg-2 sidebarSpace'>
        <ul class='nav nav-tabs nav-justified'>
            <li class='active'><a href='#dbTables' data-toggle='tab' role='tab' aria-controls='nav-dbTables' aria-selected='true'>Tables</a></li>
            <li><a href='#dbFormElements' data-toggle='tab' role='tab' aria-controls='nav-dbFormElements' aria-selected='false'>More ...</a></li>
        </ul>
        <div class='tab-content'>
            <div id='dbTables' class='tab-pane fade in active'>
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
        "codeReviewDC"=>["icon"=>"<i class='fa fa-code'></i>","title"=>"Review"],
        //"codeEditorDC"=>["icon"=>"<i class='fa fa-pencil'></i>","title"=>"Editor"],
        ['type'=>"bar"],
        "saveDC"=>["icon"=>"<i class='fa fa-save'></i>","title"=>"Save"],
        
        //"trashDC"=>["icon"=>"<i class='fa fa-trash'></i>","tips"=>"Delete"],
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
.form-actions {
    display:none;
}
.field-tools {
    /*margin-bottom: -20px;*/
    position: absolute;
    right: 20px;
    display: none;
}
.field-container:hover .field-tools {
    display: inline-block;
}
.field-container:hover .field-tools .fa {
    margin: 3px;
    cursor: pointer;
}
.ui-state-highlight {
    background: red;
    min-height: 80px;
}
</style>
<script>
const dcHash = "<?=$dcHash?>";
const dcMode = "<?=$slugs['dcmode']?>";

var forceFillRow = "<tr><th class='slno'>{{nx}}</th><td data-name='data_column' contenteditable=true>{{data_column}}</td><td data-name='data_value' contenteditable=true>{{data_value}}</td><td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>";

var formFields = {};
var formKeys = [];
$(function() {
    forceFillRow = Handlebars.compile(forceFillRow);
    
    w = $(".sidebarSpace").width();
    if(w==null || w<200) w = 200;
    
    $("#pgtoolbar .nav.navbar-right").append("<li style='margin-top: 3px;width: "+w+"px;'><select class='form-control' id='tableDropdown'></select></li>");
    $("#tableDropdown").change(function() {
        loadColumnList();
    });
    
    $("#tableDropdown").load(_service("datacontrolsEditor","listTables","select"), function() {
        loadColumnList();
        
        loadDC();
    });
    
    loadFormElements();
    
    $(".tableColumReceiver").droppable({
          accept: "li.list-group-item.list-table-columns, li.list-group-item.list-formula",
          classes: {
            //"ui-droppable-active": "ui-state-active",
            //"ui-droppable-hover": "ui-state-hover"
          },
          drop: function( event, ui ) {
                src = $(ui.draggable[0]);
                if(src.hasClass("list-table-columns")) {
                    addColumnField(ui.draggable[0], this);
                } else if(src.hasClass("list-form-item")) {
                    addFormElement(ui.draggable[0], this);
                }
          }
        });
    $("#formDesigner").delegate("*[data-cmd]","click", function() {
        doFormFieldAction(this);
    });
    
});
function refreshUI() {
    window.location.reload();
    // $("#tableDropdown").load(_service("datacontrolsEditor","listTables","select"), function() {
    //     loadColumnList();
    // });
    //loadFormulas();
}
function updateDCPanelUI() {
    $("#editorForceFill tbody").html("");
    
    if(dcConfig.forcefill!=null) {
        $.each(dcConfig.forcefill, function(a,b) {
            $("#editorForceFill tbody").append(forceFillRow({
                    "nx":$("#editorForceFill tbody").children().length+1,
                    "data_column":a,
                    "data_value":b,
                }));
        });
    }
    if(dcConfig.source!=null && dcConfig.source.table!=null) {
        $("#tableDropdown").val(dcConfig.source.table);
        loadColumnList();
    }
    
    formFields = dcConfig.fields;
    updateFormFieldModel();
    
    renderFormPreview();
}

function collectDCMoreData(qData) {
    if($("#editorForceFill tbody").children().length>0) {
        qData['forcefill'] = {};
        $("#editorForceFill tbody tr").each(function() {
            if($(this).find("td[data-name=data_column]").text().length>0) {
                qData['forcefill'][$(this).find("td[data-name=data_column]").text()]=$(this).find("td[data-name=data_value]").text();
            }
        });
    }

    qData['source'] = {
            "type":"sql",
            "table":$("#tableDropdown").val(),
            "where": ["md5(id)"]
        };
    
    qData['fields'] = {};
    //updateFormFieldModel();
    $.each(formKeys, function(field) {
        if(formFields['field']!=null) {
            qData['fields'][field] = formFields['field'];
        }
    });
    
    
    return qData;
}

function renderFormPreview() {
    if(dcConfig.fields==null) dcConfig.fields = {};
    
    $("#formDesigner").parent().prepend("<div class='ajaxloading ajaxloading5'>Loading Form Designer ...</div>");
    
    //"fields="+encodeURIComponent(JSON.stringify(formFields))
    //processAJAXPostQuery(_service("datacontrolsEditor", "formPreview", "raw")+"&dchash="+dcHash+"&dcmode="+dcMode, 
    //        "",function(data) {
    $("#formDesigner").load(_service("datacontrolsEditor", "formPreview", "raw")+"&dchash="+dcHash+"&dcmode="+dcMode, function() {
        rerenderFormPreview();
        $("#formDesigner").parent().find(".ajaxloading").detach();
    });
}
function rerenderFormPreview() {
    $("#formDesigner .formbox fieldset").html("");
    $.each(formFields, function(key, fld) {
        renderFieldPreview(key,fld);
    });
}
function renderFieldPreview(fieldkey, fieldData) {
    processAJAXPostQuery(_service("datacontrolsEditor", "formFieldPreview", "raw")+"&dchash="+dcHash+"&dcmode="+dcMode, "field="+encodeURIComponent(JSON.stringify(fieldData))+"&key="+fieldkey,function(data) {
        if($("#formDesigner .tab-pane").length>0) {
            $("#formDesigner .tab-pane.active fieldset").append(data);
        } else if($("#formDesigner .panel-collapse.collapse.in").length>0) {
            $("#formDesigner .panel-collapse.collapse.in fieldset").append(data);
        } else if($("#formDesigner fieldset").length>0) {
            $("#formDesigner fieldset").append(data);
        }
        
        $(".formbox .field-container").each(function() {
            if($(this).find(".field-tools").length<=0) {
                $(this).prepend("<div class='field-tools pull-right'>"+
                        "<i class='fa fa-gear' data-cmd='editField'></i>"+
                        "<i class='fa fa-times' data-cmd='removeField'></i>"+
                        "</div>");
            }
            
            nm = $(this).find("select[name],input[name],textarea[name]").attr("name");
            $(this).data("column",nm);
        });
    });
}

function doFormFieldAction(src) {
    cmd = $(src).data("cmd");
    
    switch(cmd) {
        case "removeField":
            $(src).closest(".field-container").detach();
            break;
        case "editField":
            break;
        default:
            console.log("Field action missing",cmd);
    }
}

function updateFormFieldModel() {
    if(formFields==null) formFields = {};
    formKeys = Object.keys(formFields);
}

function addFormElement(colEle, source) {
    if(colEle!=null) {
        console.log(colEle, "FORMELMENT");
    }
}

function addColumnField(colEle, source) {
    if(colEle!=null) {
        colKey = $(colEle).data("colkey");
        colType = $(colEle).data("type");//.split("(")[0]
        colNull = $(colEle).data("null");
        colKey = $(colEle).data("colkey");

        newField = {
                "label": toTitle($(colEle).data("column")),
                "group": "Info",
                "required": false,
                "type": "text",
            };
        if(colNull=="NO") {
            newField.required = true;
        }

        switch(colType.toLowerCase().split("(")[0]) {
            case "int":case "float":case "double":
                newField.type = "number";
                break;
            case "datetime":
                newField.type = "datetime";
                break;
            case "date":
                newField.type = "date";
                break;
            case "time":
                newField.type = "time";
                break;
            case "text":case "longtext":case "shorttext":
                newField.type = "textarea";
                break;
        }
        
        renderFieldPreview(colKey, newField);
    }
}
</script>