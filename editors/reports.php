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
        <h5>&nbsp;&nbsp;Report Editor : <span id='dcTitle'></span> <div id='dcTags' class='pull-right' style='margin-right:10px;'></div></h5>
        <ul class='nav nav-tabs nav-justified'>
            <li class='active'><a href='#editorTable' data-toggle='tab' role='tab' aria-controls='nav-editorTable' aria-selected='true'>Grid</a></li>
            <li><a href='#editorSource' data-toggle='tab' role='tab' aria-controls='nav-editorSource' aria-selected='false'>Source</a></li>
            <li><a href='#editorSidebar' data-toggle='tab' role='tab' aria-controls='nav-editorSidebar' aria-selected='false'>Sidebar</a></li>
            <li><a href='#editorTopChart' data-toggle='tab' role='tab' aria-controls='nav-editorTopChart' aria-selected='false'>TopChart</a></li>
            <li><a href='#editorRules' data-toggle='tab' role='tab' aria-controls='nav-editorRules' aria-selected='false'>Rules</a></li>
            <li><a href='#editorHooks' data-toggle='tab' role='tab' aria-controls='nav-editorHooks' aria-selected='false'>Hooks</a></li>
            <li><a href='#editorActions' data-toggle='tab' role='tab' aria-controls='nav-editorActions' aria-selected='false'>Actions</a></li>
            <li><a href='#editorProperties' data-toggle='tab' role='tab' aria-controls='nav-editorProperties' aria-selected='false'>Properties</a></li>
            
            <li class='dropdown'>
                <a class='dropdown-toggle' data-toggle='dropdown' href='#' style='white-space: nowrap;'>Templates <span class='caret'></span></a>
                <ul class='dropdown-menu'>
                    <li><a href='#editorKanban' data-toggle='tab' role='tab' aria-controls='nav-editorKanban' aria-selected='false'>Kanban</a></li>
                    <li><a href='#editorCards' data-toggle='tab' role='tab' aria-controls='nav-editorCards' aria-selected='false'>Cards</a></li>
                    <li><a href='#editorCalendar' data-toggle='tab' role='tab' aria-controls='nav-editorCalendar' aria-selected='false'>Calendar</a></li>
                </ul>
            </li>
            <li class='dropdown'>
                <a class='dropdown-toggle' data-toggle='dropdown' href='#' style='white-space: nowrap;'>More <span class='caret'></span></a>
                <ul class='dropdown-menu'>
                    <li><a href='#editorScript' data-toggle='tab' role='tab' aria-controls='nav-editorScript' aria-selected='false'>Script</a></li>
                    <li><a href='#editorStyles' data-toggle='tab' role='tab' aria-controls='nav-editorStyles' aria-selected='false'>Styles</a></li>
                </ul>
            </li>
        </ul>
        <div class='tab-content'>
            <div id='editorTable' class='tableColumReceiver table-responsive tab-pane fade in active'>
                <table class='table table-responsive table-hover'>
                <thead>
                    <tr>
                        <th width='100px'>SL#</th>
                        <th>Table</th>
                        <th>Column</th>
                        <th>Label</th>
                        <th>Hidden</th>
                        <th>Searchable</th>
                        <th>Sortable</th>
                        <th>Classes</th>
                        <th>Formatter</th>
                        <th>Filter</th>
                        <th>--</th>
                    </tr>
                </thead><tbody></tbody></table>
            </div>
            <div id='editorSource' class='table-responsive tab-pane fade'>
                <table class='table table-responsive'>
                    <tbody>
                        <tr>
                            <th width=50%>Source Type</th>
                            <td><select class='form-control' name='srctype'>
                                <option value='sql'>SQL</option>
                                <option value='php'>PHP</option>
                                <option value='rest-json'>REST JSON</option>
                                <option value='rest-xml'>REST XML</option>
                            </select></td>
                        </tr>
                        <tr>
                            <th width=50%>Source (SQL Table, REST URI, etc)</th>
                            <td><input name='srcreference' value='' class='form-control' type='text' placeholder='Source (SQL Table, REST URI, etc)' /></td>
                        </tr>
                        <tr>
                            <th width=50%>Per Page Limit</th>
                            <td><select class='form-control' name='srcpagelimit'>
                                <option>5</option>
                                <option>10</option>
                                <option selected>20</option>
                                <option>50</option>
                                <option>100</option>
                                <option>500</option>
                                <option>1000</option>
                                <option>5000</option>
                            </select></td>
                        </tr>
                    </body>
                </table>
                <table class='table table-responsive table-hover sqlWhere'>
                <thead>
                    <tr>
                        <th width='100px'>SL#</th>
                        <th>Column 1</th>
                        <th width='100px'>Relation</th>
                        <th>Column 2</th>
                        <th>
                            <i class='fa fa-list fa-2x pull-right' onclick='addQueryWhere_RAW(this)' title='Add RAW Query Condition'></i>
                            <i class='fa fa-columns fa-2x pull-right' onclick='addQueryWhere_LOGIC(this)' title='Add Logical Query Condition'></i>
                        </th>
                    </tr>
                </thead><tbody></tbody></table>
            </div>
            <div id='editorSidebar' class='table-responsive tab-pane fade'>
                <div id='sidebarPanel' class='tabPanel'>
                    <table class='table table-responsive'>
                        <thead>
                            <tr>
                                <th width=50%>Sidebar Type</th>
                                <td><select class='form-control' name='sidebartype'>
                                    <option value=''>Disabled</option>
                                    <option value='none'>None</option>
                                    <option value='list'>List</option>
                                </select></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width=50%>Source Field/Column</th>
                                <td><select class='form-control grid_fields' name='sidebarcolumn' data-value=''>
                                    
                                </select></td>
                            </tr>
                            <tr><th class='row-header' colspan=100>Source</th></tr>
                            <tr>
                                <th width=50%>Source Table</th>
                                <td><select name='sidebar_table' class='form-control tableDropdown'></select></td>
                            </tr>
                            <tr>
                                <th width=50%>Source Columns</th>
                                <td><input name='sidebar_cols' value='' class='form-control' type='text' placeholder='category, title, value' /></td>
                            </tr>
                            <tr>
                                <th width=50%>Source Group Column</th>
                                <td><input name='sidebar_groupby' value='' class='form-control' type='text' /></td>
                            </tr>
                        </body>
                    </table>
                    <table class='table table-responsive table-hover'>
                        <thead>
                            <tr><th class='row-header' colspan=100>Source Where Query</th></tr>
                            <tr>
                                <th width='100px'>SL#</th>
                                <th>Column 1</th>
                                <th width='100px'>Relation</th>
                                <th>Column 2</th>
                                <th>
                                    <i class='fa fa-list fa-2x pull-right' onclick='addQueryWhere_RAW(this)' title='Add RAW Query Condition'></i>
                                    <i class='fa fa-columns fa-2x pull-right' onclick='addQueryWhere_LOGIC(this)' title='Add Logical Query Condition'></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody id='sidebarSourceWhere'>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id='editorTopChart' class='table-responsive tab-pane fade'>
                <div id='topchartPanel' class='tabPanel'>
                    <table class='table table-responsive'>
                        <thead>
                            <tr>
                                <th width=50%>Chart Type</th>
                                <td><select class='form-control' name='charttype'>
                                    <option value=''>Disabled</option>
                                    <option value='none'>None</option>
                                    <option value='line'>Line</option>
                                    <option value='bar'>Bar Chart</option>
                                </select></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width=50%>Chart Title</th>
                                <td><input name='chart_title' value='' class='form-control' type='text' /></td>
                            </tr>
                            <tr>
                                <th width=50%>Chart Fill</th>
                                <td><input name='chart_filling' value='' class='form-control' type='checkbox' /></td>
                            </tr>
                            <tr><th class='row-header' colspan=100>Source</th></tr>
                            <tr>
                                <th width=50%>Source Table</th>
                                <td><select name='chart_table' class='form-control tableDropdown'></select></td>
                            </tr>
                            <tr>
                                <th width=50%>Source Columns</th>
                                <td><input name='chart_cols' value='' class='form-control' type='text' placeholder='category, title, value' /></td>
                            </tr>
                            <tr>
                                <th width=50%>Source Group Column</th>
                                <td><input name='chart_groupby' value='' class='form-control' type='text' /></td>
                            </tr>
                        </body>
                    </table>
                    <table class='table table-responsive table-hover'>
                        <thead>
                            <tr><th class='row-header' colspan=100>Source Where Query</th></tr>
                            <tr>
                                <th width='100px'>SL#</th>
                                <th>Column 1</th>
                                <th width='100px'>Relation</th>
                                <th>Column 2</th>
                                <th>
                                    <i class='fa fa-list fa-2x pull-right' onclick='addQueryWhere_RAW(this)' title='Add RAW Query Condition'></i>
                                    <i class='fa fa-columns fa-2x pull-right' onclick='addQueryWhere_LOGIC(this)' title='Add Logical Query Condition'></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody id='chartSourceWhere'>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id='editorRules' class='table-responsive tab-pane fade'>
                <table class='table table-responsive table-hover'>
                <thead>
                    <tr>
                        <th width='100px'>SL#</th>
                        <th>Rule Type</th>
                        <th>Rule Column</th>
                        <th>Value</th>
                        <th>Class</th>
                        <th>
                            <i class='fa fa-plus fa-2x pull-right' onclick='addBlankRule(this)' title='Add Blank Rule'></i>
                        </th>
                    </tr>
                </thead><tbody></tbody></table>
            </div>
            <div id='editorHooks' class='table-responsive tab-pane fade'>
                <table class='table table-responsive table-hover'>
                <thead>
                    <tr>
                        <th width='100px'>SL#</th>
                        <th>Hook State/Type</th>
                        <th>Call Type</th>
                        <th>Methods, etc</th>
                        <th>
                            <i class='fa fa-plus fa-2x pull-right' onclick='addBlankHook(this)' title='Add Blank Hook'></i>
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
                </table>
            </div>
            <div id='editorActions' class='table-responsive tab-pane fade'>
                <table class='table table-responsive table-hover'>
                    <thead>
                        <tr>
                            <th width='100px'>SL#</th>
                            <th>Action</th>
                            <th>Label</th>
                            <th>Icon</th>
                            <th>Class</th>
                            <th>--</th>
                        </tr>
                    </thead>
                    <thead><tr><th class='row-header' colspan=100>Header Actions
                        <i class='fa fa-plus fa-2x pull-right' onclick='addBlankAction(this)' title='Add New Action'></i>
                    </th></tr></thead>
                    <tbody id='dcActions'></tbody>
                    <thead><tr><th class='row-header' colspan=100>Row Buttons
                        <i class='fa fa-plus fa-2x pull-right' onclick='addBlankButton(this)' title='Add Row Button'></i>
                    </th></tr></thead>
                    <tbody id='dcButtons'></tbody>
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
            
            <div id='editorKanban' class='table-responsive tab-pane fade'>
                <h1 align=center class='alert alert-danger'>WIP Kanban</h1>
                <table class='table table-responsive table-hover'>
                    
                </table>
            </div>
            <div id='editorCards' class='table-responsive tab-pane fade'>
                <h1 align=center class='alert alert-danger'>WIP Cards</h1>
                <table class='table table-responsive table-hover'>
                    
                </table>
            </div>
            <div id='editorCalendar' class='table-responsive tab-pane fade'>
                <h1 align=center class='alert alert-danger'>WIP Calendar</h1>
                <table class='table table-responsive table-hover'>
                    
                </table>
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-3 col-md-3 col-lg-2 sidebarSpace noselect'>
        <ul class='nav nav-tabs nav-justified'>
            <li class='active'><a href='#dbTables' data-toggle='tab' role='tab' aria-controls='nav-dbTables' aria-selected='true'>Tables</a></li>
            <li><a href='#dbFormulas' data-toggle='tab' role='tab' aria-controls='nav-dbFormulas' aria-selected='false'>Formulas</a></li>
        </ul>
        <div class='tab-content'>
            <div id='dbTables' class='tab-pane fade in active'>
                <div id='tableTree' class='componentTree list-group list-group-root'>
    	            
    	        </div>
            </div>
            <div id='dbFormulas' class='tab-pane fade'>
                <div id='formulaTree' class='componentTree list-group list-group-root'>
    	            
    	        </div>
            </div>
        </div>
    </div>"
    .file_get_contents(dirname(__DIR__)."/comps/report_filter.html")
    .file_get_contents(dirname(__DIR__)."/comps/report_formula.html");
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
  
$dbLogicSelector = [];
foreach($_ENV['DBLOGIC'] as $a=>$b) {
    $dbLogicSelector[] = "<option value='{$a}'>{$b}</option>";
}
$dbLogicSelector = implode("",$dbLogicSelector);
?>
<style>

</style>
<script>
const dcHash = "<?=$dcHash?>";
const dcMode = "<?=$slugs['dcmode']?>";
const dbLogicSelector = "<?=$dbLogicSelector?>";

var ruleRow = "<tr><th class='slno'>{{nx}}</th><td data-name='rule_type'>{{{formfield 'report_rule_types' rule_type}}}</td><td data-name='rule_column' contenteditable=true>{{rule_column}}</td><td data-name='rule_value' contenteditable=true>{{rule_value}}</td><td data-name='rule_class' contenteditable=true>{{rule_class}}</td><td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>";
var gridRow = "<tr data-field='{{field}}' class='trfield'><th class='slno'>{{nx}}</th>"+
    "<td data-name='table'>{{table}}</td>"+
    "<td data-name='column'>{{column}}</td>"+
    "<td data-name='label' contenteditable=true>{{label}}</td>"+
    "<td data-name='hidden' class='nopaddingBox'>{{{formfield 'hidden' hidden}}}</td>"+
    "<td data-name='searchable' class='nopaddingBox'>{{{formfield 'searchable' searchable}}}</td>"+
    "<td data-name='sortable' class='nopaddingBox'>{{{formfield 'sortable' sortable}}}</td>"+
    "<td data-name='classes' contenteditable=true>{{classes}}</td>"+
    "<td data-name='formatter' class='nopadding'>{{{formfield 'formatter' formatter}}}</td>"+
    "<td data-name='filter' class='text-center'><i class='fa fa-tags actionIcon' data-cmd='editReportFilter' title='Edit Filter for this field' ></i></td>"+
    "<td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>";
var gridFormula = "<tr data-colkey='{{colkey}}' class='trformula'><th class='slno'>{{nx}}</th>"+
    "<td data-name='formula' contenteditable=true colspan=2>{{formula}}</td>"+
    "<td data-name='label' contenteditable=true>{{label}}</td>"+
    "<td data-name='hidden' class='nopaddingBox'>{{{formfield 'hidden' hidden}}}</td>"+
    "<td data-name='searchable' class='nopaddingBox'>{{{formfield 'searchable' searchable}}}</td>"+
    "<td data-name='sortable' class='nopaddingBox'>{{{formfield 'sortable' sortable}}}</td>"+
    "<td data-name='classes' contenteditable=true>{{classes}}</td>"+
    "<td data-name='formatter' class='nopadding'>{{{formfield 'formatter' formatter}}}</td>"+
    "<td data-name='filter' class='text-center'><i class='fa fa-tags actionIcon' data-cmd='editReportFilter' title='Edit Filter for this field' ></i></td>"+
    "<td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>";
$(function() {
    gridRow = Handlebars.compile(gridRow);
    ruleRow = Handlebars.compile(ruleRow);
    gridFormula = Handlebars.compile(gridFormula);
    
    w = $(".sidebarSpace").width();
    if(w==null || w<200) w = 200;
    
    $("#pgtoolbar .nav.navbar-right").append("<li style='margin-top: 3px;width: "+w+"px;'><select class='form-control' id='tableDropdown'></select></li>");
    $("#tableDropdown").change(loadColumnList);
    
    $("#tableDropdown").load(_service("datacontrolsEditor","listTables","select"), function() {
        loadColumnList();
        
        loadDC();
    });
    
    $("#editorSource select[name=srctype]").change(function() {
        if(this.value=="sql") {
            $("#editorSource input[name=srcreference]").attr("readonly","true");
        } else {
            $("#editorSource input[name=srcreference]").removeAttr("readonly");
        }
    });
    
    $("#editorSidebar select[name='sidebartype']").change(function() {
        showHidePanels();
    });
    
    $("#editorTopChart select[name='charttype']").change(function() {
        showHidePanels();
    });
    
    $( ".tableColumReceiver" ).droppable({
          accept: "li.list-group-item.list-table-columns, li.list-group-item.list-formula",
          classes: {
            //"ui-droppable-active": "ui-state-active",
            //"ui-droppable-hover": "ui-state-hover"
          },
          drop: function( event, ui ) {
                src = $(ui.draggable[0]);
                if(src.hasClass("list-table-columns")) {
                    addBlankRow(ui.draggable[0], this);
                } else if(src.hasClass("list-formula")) {
                    addBlankFormula(ui.draggable[0], this);
                }
          }
        });
    
    loadDBFormulas();
});

function updateDCPanelUI() {
    //console.log(dcConfig);
    $("#editorTable tbody").html("");
    $("#sidebarSourceWhere").html("");
    $("#editorRules tbody").html("");
    
    $.each(dcConfig.datagrid, function(a, b) {
        b.field = a;
        b.nx = $("#editorTable tbody").children().length+1;
        b.table = a.split(".");
        if(b.table.length<=1) {
            b.table = "";
            b.column = a;
        } else {
            b.column = b.table[1];
            b.table = b.table[0];
        }
        
        $("#editorTable tbody").append(gridRow(b));
        
        if(b.filter!=null) {
            if($("#editorTable tbody tr[data-field='"+a+"']").length>0) {
                $("#editorTable tbody tr[data-field='"+a+"'] td[data-name='filter']").data("filter",b.filter);
                $("#editorTable tbody tr[data-field='"+a+"'] td[data-name='filter'] .actionIcon").addClass("clr_RED");
            }
        }
    });
    updateGridColumnSelectors();
    
    if(dcConfig.source!=null) {
        $("#editorSource select[name=srctype]").val(dcConfig.source.type);
        if(dcConfig.source.limit!=null) $("#editorSource select[name=srcpagelimit]").val(dcConfig.source.limit);
        $("#editorSource .sqlWhere tbody").html("");
        
        switch(dcConfig.source.type.toLowerCase()) {
            case "sql":
                $("#editorSource input[name=srcreference]").val(dcConfig.source.table);
                $("#editorSource input[name=srcreference]").attr("readonly","true");
                
                $.each(dcConfig.source.where, function(a, b) {
                        if(b=="RAW") {
                            addQueryWhere_RAW("#editorSource .sqlWhere tbody",a);
                        } else {
                            addQueryWhere_LOGIC("#editorSource .sqlWhere tbody",a,b);
                        }
                    });
                break;
            case "php":
                $("#editorSource input[name=srcreference]").val(dcConfig.source.file);
                $("#editorSource input[name=srcreference]").removeAttr("readonly");
                break;
            case "rest-json":
                $("#editorSource input[name=srcreference]").val(dcConfig.source.uri);
                $("#editorSource input[name=srcreference]").removeAttr("readonly");
                break;
            case "rest-xml":
                $("#editorSource input[name=srcreference]").val(dcConfig.source.uri);
                $("#editorSource input[name=srcreference]").removeAttr("readonly");
                break;
        }
    }
    
    if(dcConfig.rules!=null) {
        $.each(dcConfig.rules, function(a,b) {
            $.each(b, function(c, d) {
                $.each(d, function(e, f) {
                    $("#editorRules tbody").append(ruleRow({
                            "nx":$("#editorRules tbody").children().length+1,
                            "rule_type":a,
                            "rule_column":c,
                            "rule_value":e,
                            "rule_class":f,
                        }));
                });
            });
        });
    }
    
    if(dcConfig.sidebar!=null) {
        //console.log(dcConfig.sidebar.source);
        $("select[name=sidebartype]").val(dcConfig.sidebar.type);
        if(Object.keys(dcConfig.sidebar.source).length>0) {
            colField = Object.keys(dcConfig.sidebar.source)[0];
            
            $("select[name=sidebarcolumn]").data("value", colField);
            //$("select[name=sidebarcolumn]").val();
            
            $("select[name=sidebar_table]").val(dcConfig.sidebar.source[colField].table);
            $("select[name=sidebar_table]").data("value",dcConfig.sidebar.source[colField].table);
            $("input[name=sidebar_groupby]").val(dcConfig.sidebar.source[colField].groupby);
            $("input[name=sidebar_cols]").val(dcConfig.sidebar.source[colField].cols);
            
            if(dcConfig.sidebar.source[colField].fill) {
                $("input[name=chart_filling]")[0].checked = true;
            } else {
                $("input[name=chart_filling]")[0].checked = false;
            }
            
            
            if(dcConfig.sidebar.source[colField].where!=null) {
                $.each(dcConfig.sidebar.source[colField].where, function(a,b) {
                    if(b=="RAW") {
                        addQueryWhere_RAW("#sidebarSourceWhere",a);
                    } else {
                        addQueryWhere_LOGIC("#sidebarSourceWhere",a,b);
                    }
                });
            }
        }
    }
    
    if(dcConfig.charts!=null) {
        $("#topchartPanel select[name=charttype]").val(dcConfig.charts.type);
        $("#topchartPanel *[name=chart_title]").val(dcConfig.charts.title);
        
        if(Object.keys(dcConfig.charts.source).length>0) {
            colField = Object.keys(dcConfig.charts.source)[0];
            
            //$("select[name=sidebarcolumn]").data("value", colField);
            //$("select[name=sidebarcolumn]").val();
            
            $("select[name=chart_table]").val(dcConfig.charts.source[colField].table);
            $("select[name=chart_table]").data("value",dcConfig.charts.source[colField].table);
            $("input[name=chart_groupby]").val(dcConfig.charts.source[colField].groupby);
            $("input[name=chart_cols]").val(dcConfig.charts.source[colField].cols);
            
            if(dcConfig.charts.source[colField].where!=null) {
                $.each(dcConfig.charts.source[colField].where, function(a,b) {
                    if(b=="RAW") {
                        addQueryWhere_RAW("#chartSourceWhere",a);
                    } else {
                        addQueryWhere_LOGIC("#chartSourceWhere",a,b);
                    }
                });
            }
        }
    }
    
    updateDCPanelTemplateUI();
    showHidePanels();
}

function updateDCPanelTemplateUI() {
    //Template Views
    // reportTemplates = {
    //     "kanban":{
    //         "title":"Kanban View"
    //     },
    //     "cards":{
    //         "title":"Cards/Mobility List View"
    //     },
    //     "calendar":{
    //         "title":"Calendar View"
    //     },
    // };
    // $("#editorViews>table").html("");
    // $.each(reportTemplates, function(key, template) {
    //     if(template.title==null) template.title = toTitle(key)+ " View";
    //     $("#editorViews>table").append("<thead data-refid='template_"+key+"'><tr><th class='row-header' colspan=100>"+template.title+"</th></tr></thead><tbody data-template='"+key+"' data-refid='template_"+key+"'></tbody>");
        
    //     if(dcConfig[key]!=null) {
    //         tempTemplate = $.extend(template, dcConfig[key]);
    //     } else {
    //         tempTemplate = template;
    //     }
        
    //     $("#editorViews>table tbody[data-refid='template_"+key+"']").append(propRow({"name":key+"_enabled","title":"Enabled","value":false,"class":"enabled"}));
    //     $.each(tempTemplate, function(a,b) {
    //         if(a=="title") return;
    //         $("#editorViews>table tbody[data-refid='template_"+key+"']").append(propRow({"name":a,"title":toHumanCase(a),"value":b}));
    //     });
    // });
}

function showHidePanels() {
    if($("#editorSidebar select[name='sidebartype']").val()=="") {
        $("#editorSidebar table:first-child tbody").hide();
        $("#editorSidebar table:not(:first-child)").hide();
    } else {
        $("#editorSidebar table:first-child tbody").show();
        $("#editorSidebar table:not(:first-child)").show();
    }
    
    if($("#editorTopChart select[name='charttype']").val()=="") {
        $("#editorTopChart table:first-child tbody").hide();
        $("#editorTopChart table:not(:first-child)").hide();
    } else {
        $("#editorTopChart table:first-child tbody").show();
        $("#editorTopChart table:not(:first-child)").show();
    }
}

function collectDCMoreData(qData) {
    //Validate the Entire DataControl
    if($("table.sqlWhere tbody>tr").length < $("input[name=srcreference]").val().split(",").length-1) {
        lgksAlert("No of where clause does not match minimum requirement for "+$("input[name=srcreference]").val().split(",").length+" Tables");
        return false;
    }
    
    //If DataControl is valid, pass the collected data into the system
    xData = {};
    $("#editorTable tbody tr").each(function() {
        if($(this).hasClass("trfield")) {
            field = $(this).data("field");
            dataObj = {
                    "label":$(this).find("td[data-name=label]").text(),
                    "hidden":$(this).find("td[data-name=hidden] input").is(":checked"),
                    "searchable":$(this).find("td[data-name=searchable] input").is(":checked"),
                    "sortable":$(this).find("td[data-name=sortable] input").is(":checked"),
                    //"groupable":$(this).find("td[data-name=groupable] input").is(":checked"),
                    "classes":$(this).find("td[data-name=classes]").text(),
                };
            if($(this).find("td[data-name=formatter] select").val()!=null && $(this).find("td[data-name=formatter] select").val().length>0) {
                dataObj['formatter'] = $(this).find("td[data-name=formatter] select").val();
            }
            filterConfig = $(this).find("td[data-name=filter]").data("filter");
            if(filterConfig!=null) {
                dataObj['filter'] = filterConfig;
            }
            
            xData[field] = dataObj;
        } else if($(this).hasClass("trformula")) {
            field = $(this).find("td[data-name=formula]").text();
            if(field!=null && field.length>0) {
                dataObj = {
                        "label":$(this).find("td[data-name=label]").text(),
                        //"alias":
                        "hidden":$(this).find("td[data-name=hidden] input").is(":checked"),
                        "searchable":$(this).find("td[data-name=searchable] input").is(":checked"),
                        "sortable":$(this).find("td[data-name=sortable] input").is(":checked"),
                        //"groupable":$(this).find("td[data-name=groupable] input").is(":checked"),
                        "classes":$(this).find("td[data-name=classes]").text(),
                    };
                if($(this).find("td[data-name=formatter] select").val()!=null && $(this).find("td[data-name=formatter] select").val().length>0) {
                    dataObj['formatter'] = $(this).find("td[data-name=formatter] select").val();
                }
                filterConfig = $(this).find("td[data-name=filter]").data("filter");
                if(filterConfig!=null) {
                    dataObj['filter'] = filterConfig;
                }
                
                xData[field] = dataObj;
            }
        }
        //console.log(field,this, dataObj);
    });
    
    switch($("#editorSource select[name=srctype]").val()) {
        case "sql":
            sqlWhere = {};
            $("#editorSource .sqlWhere tbody tr").each(function() {
                $(this).removeClass("alert-danger").removeClass("alert");
                if($(this).hasClass("rawwhere")) {
                    if($(this).find("td[contenteditable]").text().length>0) {
                        sqlWhere[$(this).find("td[contenteditable]").text()] = "RAW";
                    } else {
                        $(this).addClass("alert alert-danger");
                    }
                } else {
                    if($(this).find("td.col1").text().length>0 && $(this).find("td.col2").text().length>0) {
                        sqlWhere[$(this).find("td.col1").text()] = {"VALUE":$(this).find("td.col2").text(),"OP":$(this).find("select").val()};
                    } else {
                        $(this).addClass("alert alert-danger");
                    }
                }
            });
            
            qData['source'] = {
                    "type": $("#editorSource select[name=srctype]").val(),
                    "table": $("#editorSource input[name=srcreference]").val(),
                    "cols": Object.keys(xData).join(","),
                    "where":sqlWhere,
                    "limit": $("#editorSource select[name=srcpagelimit]").val(),
                };
            break;
        case "php":
            qData['source'] = {
                    "type": $("#editorSource select[name=srctype]").val(),
                    "file": $("#editorSource input[name=srcreference]").val(),
                    "limit": $("#editorSource select[name=srcpagelimit]").val(),
                };
            break;
        case "rest-json":
            qData['source'] = {
                    "type": $("#editorSource select[name=srctype]").val(),
                    "uri": $("#editorSource input[name=srcreference]").val(),
                    "limit": $("#editorSource select[name=srcpagelimit]").val(),
                };
            break;
        case "rest-xml":
            qData['source'] = {
                    "type": $("#editorSource select[name=srctype]").val(),
                    "uri": $("#editorSource input[name=srcreference]").val(),
                    "limit": $("#editorSource select[name=srcpagelimit]").val(),
                };
            break;
    }
    
    qData['datagrid'] = xData;
    
    if($("#editorRules tbody tr").length>0) {
        qData['rules'] = {};
        $("#editorRules tbody tr").each(function() {
            rule_type = $(this).find("td[data-name=rule_type] select").val();
            if(qData['rules'][rule_type]==null) {
                qData['rules'][rule_type] = {};
            }
            if(qData['rules'][rule_type][$(this).find("td[data-name=rule_column]").text()]==null) {
                qData['rules'][rule_type][$(this).find("td[data-name=rule_column]").text()] = {};
            }
            qData['rules'][rule_type][$(this).find("td[data-name=rule_column]").text()][$(this).find("td[data-name=rule_value]").text()] = $(this).find("td[data-name=rule_class]").text();
        });
    }
    
    if($("#sidebarPanel select[name='sidebartype']").val().length>0) {
        qData['sidebar'] = {
            "type":$("#sidebarPanel select[name='sidebartype']").val(),
            "source":{}
        };
        
        sqlWhere = {};
        $("#sidebarSourceWhere tr").each(function() {
            $(this).removeClass("alert-danger").removeClass("alert");
            if($(this).hasClass("rawwhere")) {
                if($(this).find("td[contenteditable]").text().length>0) {
                    sqlWhere[$(this).find("td[contenteditable]").text()] = "RAW";
                } else {
                    $(this).addClass("alert alert-danger");
                }
            } else {
                if($(this).find("td.col1").text().length>0 && $(this).find("td.col2").text().length>0) {
                    sqlWhere[$(this).find("td.col1").text()] = {"VALUE":$(this).find("td.col2").text(),"OP":$(this).find("select").val()};
                } else {
                    $(this).addClass("alert alert-danger");
                }
            }
        });
        
        qData['sidebar']['source'][$("#sidebarPanel *[name='sidebarcolumn']").val()] = {
                "type":"sql",
                "table":$("#sidebarPanel select[name='sidebar_table']").val(),
                "groupby":$("#sidebarPanel input[name='sidebar_groupby']").val(),
                "cols":$("#sidebarPanel input[name='sidebar_cols']").val(),
                "where":sqlWhere
            };
        //$("#sidebarPanel select[name='sidebar_table']").data("value", $("#sidebarPanel select[name='sidebar_table']").val());
    }
    
    if($("#topchartPanel select[name='charttype']").val().length>0) {
        qData['charts'] = {
            "type":$("#topchartPanel select[name='charttype']").val(),
            "title":$("#topchartPanel *[name='chart_title']").val(),
            "source":[]
        };
        sqlWhere = {};
        $("#chartSourceWhere tr").each(function() {
            $(this).removeClass("alert-danger").removeClass("alert");
            if($(this).hasClass("rawwhere")) {
                if($(this).find("td[contenteditable]").text().length>0) {
                    sqlWhere[$(this).find("td[contenteditable]").text()] = "RAW";
                } else {
                    $(this).addClass("alert alert-danger");
                }
            } else {
                if($(this).find("td.col1").text().length>0 && $(this).find("td.col2").text().length>0) {
                    sqlWhere[$(this).find("td.col1").text()] = {"VALUE":$(this).find("td.col2").text(),"OP":$(this).find("select").val()};
                } else {
                    $(this).addClass("alert alert-danger");
                }
            }
        });
        
        qData['charts']['source'].push({
                "type":"sql",
                "fill":$("input[name=chart_filling]").is(":checked")?true:false,
                "table":$("#topchartPanel select[name='chart_table']").val(),
                "groupby":$("#topchartPanel input[name='chart_groupby']").val(),
                "cols":$("#topchartPanel input[name='chart_cols']").val(),
                "where":sqlWhere
            });
        //$("#topchartPanel select[name='chart_table']").data("value", $("#topchartPanel select[name='chart_table']").val());
    }
    
    //Templates
    // $("#editorKanban>table tbody").each(function() {
    //     if($(this).find("td.enabled input:checked").length>0 && $(this).find("td.enabled input:checked").val()=="true") {
    //         //console.log("HELLO WORLD");
    //     }
    // });
    
    return qData;
}

function updateGridColumnSelectors() {
    $("select.grid_fields").each(function() {
        $(this).data("value",$(this).val());
    });
    $("#editorTable tbody tr").each(function() {
        fld = $(this).data("field");
        $("select.grid_fields").append("<option value='"+fld+"'>"+fld+"</option>");
    });
    $("select.grid_fields").each(function() {
        $(this).val($(this).data("value"));
    });
}

function updateUIImpact() {
    if($("input[name=srcreference]").length>0) {
        tblList = [];
        $("#editorTable tr td[data-name=table]").each(function() {
            table = $(this).text().trim();
            if(tblList.indexOf(table)<0) {
                tblList.push(table);
            }
        });
        
        // tblList = $("input[name=srcreference]").val();
        // if(tblList==null || tblList.length<=0) tblList = [];
        // else tblList = tblList.split(",");

        // if(tblList.indexOf(table)<0) {
        //     tblList.push(table);
        // }

        $("input[name=srcreference]").val(tblList.join(","));
    }
}

function addBlankFormula(colEle, source) {
    if(colEle!=null) {
        formula = $(colEle).data('formula');
        showFormulaEditor(formula, colEle, source);
    }
}

function addBlankRow(colEle, source) {
    if(colEle!=null) {
        colkey = $(colEle).data("colkey");
        table = $(colEle).data("table");
        column = $(colEle).data("column");
        type = $(colEle).data("type");
        defaultValue = $(colEle).data("default");
        
        if($("#editorTable tbody").find("tr[data-field='"+colkey+"']").length>0) {
            lgksAlert("Table Column Is already available in field list");
            $("#editorTable tbody").find("tr[data-field='"+colkey+"']").addClass("alert alert-danger");
            setTimeout(function() {
                $("#editorTable tbody tr.alert").removeClass("alert-danger").removeClass("alert");
            }, 5000);
            return;
        }

        if(type=="enum('false','true')" || type=="enum('true','false')") {
            $("#editorTable tbody").append(gridRow({
                "nx":$("#editorTable tbody").children().length+1,
                "field":colkey,
                "table":table,
                "column":column,
                "label":toTitle(column),
                "formatter":"checkbox",
                "sortable":true,
                "searchable":true,
                "hidden":($("#editorTable tbody").children().length>8),
            }));
        } else if(type.substr(0,4)=="enum") {
            $("#editorTable tbody").append(gridRow({
                "nx":$("#editorTable tbody").children().length+1,
                "field":colkey,
                "table":table,
                "column":column,
                "label":toTitle(column),
                "sortable":true,
                "searchable":true,
                "hidden":($("#editorTable tbody").children().length>8),
            }));
        } else {
            $("#editorTable tbody").append(gridRow({
                "nx":$("#editorTable tbody").children().length+1,
                "field":colkey,
                "table":table,
                "column":column,
                "label":toTitle(column),
                "sortable":true,
                "searchable":true,
                "hidden":($("#editorTable tbody").children().length>8),
            }));
        }
        
        if($("#editorTable tbody tr:last-child td[data-name=formatter] select").length>0) {
            $("#editorTable tbody tr:last-child td[data-name=formatter] select").each(function() {
                $(this).val($(this).data("value"));
            });
        }

        updateUIImpact();
        //console.log(colEle, source, table);
    }
}

function addBlankRule(src) {
    $("#editorRules tbody").append(ruleRow({
            "nx":$("#editorRules tbody").children().length+1,
            "rule_type":"",
            "rule_column":"",
            "rule_value":"",
            "rule_class":"",
        }));
}
</script>