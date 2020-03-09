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
            <li class='active'><a href='#editorTable' data-toggle='tab' role='tab' aria-controls='nav-editorTable' aria-selected='true'>Grid Table</a></li>
            <li><a href='#editorSource' data-toggle='tab' role='tab' aria-controls='nav-editorSource' aria-selected='false'>Source</a></li>
            <li><a href='#editorSidebar' data-toggle='tab' role='tab' aria-controls='nav-editorSidebar' aria-selected='false'>Sidebar</a></li>
            <li><a href='#editorTopChart' data-toggle='tab' role='tab' aria-controls='nav-editorTopChart' aria-selected='false'>Top Chart</a></li>
            <li><a href='#editorRules' data-toggle='tab' role='tab' aria-controls='nav-editorRules' aria-selected='false'>Rules</a></li>
            <li><a href='#editorHooks' data-toggle='tab' role='tab' aria-controls='nav-editorHooks' aria-selected='false'>Hooks</a></li>
            <li><a href='#editorActions' data-toggle='tab' role='tab' aria-controls='nav-editorActions' aria-selected='false'>Actions</a></li>
            <li><a href='#editorScript' data-toggle='tab' role='tab' aria-controls='nav-editorScript' aria-selected='false'>Script</a></li>
            <li><a href='#editorStyles' data-toggle='tab' role='tab' aria-controls='nav-editorStyles' aria-selected='false'>Styles</a></li>
            <li><a href='#editorProperties' data-toggle='tab' role='tab' aria-controls='nav-editorProperties' aria-selected='false'>Properties</a></li>
        </ul>
        <div class='tab-content'>
            <div id='editorTable' class='table-responsive tab-pane fade in active'>
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
                        <th>--</th>
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
                                <td><input name='sidebar_table' value='' class='form-control' type='text' /></td>
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
                                <th>--</th>
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
                                <td><input name='chart_table' value='' class='form-control' type='text' /></td>
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
                                <th>--</th>
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
                        <th>--</th>
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
                        <th>--</th>
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
                <thead><tr><th class='row-header' colspan=100>Header Actions</th></tr></thead>
                <tbody id='dcActions'></tbody>
                <thead><tr><th class='row-header' colspan=100>Row Buttons</th></tr></thead>
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
        </div>
    </div>
    <div class='col-xs-12 col-sm-3 col-md-3 col-lg-2 sidebarSpace'>
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

var ruleRow = "<tr><th class='slno'>{{nx}}</th><td data-name='rule_type'>{{rule_type}}</td><td data-name='rule_column' contenteditable=true>{{rule_column}}</td><td data-name='rule_value' contenteditable=true>{{rule_value}}</td><td data-name='rule_class' contenteditable=true>{{rule_class}}</td><td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>";
var gridRow = "<tr data-field='{{field}}'><th class='slno'>{{nx}}</th>"+
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
//<td data-name='calltype' contenteditable=true>{{calltype}}</td><td data-name='methods' contenteditable=true>{{methods}}</td><td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td>
$(function() {
    gridRow = Handlebars.compile(gridRow);
    ruleRow = Handlebars.compile(ruleRow);
    
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
                        nx = $("#editorSource .sqlWhere tbody").children().length+1;
                        relation = "eq";
                        if(b=="RAW") {
                            $("#editorSource .sqlWhere tbody").append("<tr class='rawwhere'><th class='slno'>"+nx+"</th><td colspan=100 contenteditable=true>"+a+"</td></tr>");
                        } else {
                            $("#editorSource .sqlWhere tbody").append("<tr><th class='slno'>"+nx+"</th>"+
                                "<td class='col1' contenteditable=true>"+a+"</td>"+
                                "<td><select class='form-control' data-value='"+relation+"'>"+
                                    dbLogicSelector+
                                "</select></td>"+
                                "<td class='col2' contenteditable=true>"+b+"</td>"+
                                "<td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td>"+
                                "</tr>");
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
            
            $("input[name=sidebar_table]").val(dcConfig.sidebar.source[colField].table);
            $("input[name=sidebar_groupby]").val(dcConfig.sidebar.source[colField].groupby);
            $("input[name=sidebar_cols]").val(dcConfig.sidebar.source[colField].cols);
            
            if(dcConfig.sidebar.source[colField].fill) {
                $("input[name=chart_filling]")[0].checked = true;
            } else {
                $("input[name=chart_filling]")[0].checked = false;
            }
            
            
            if(dcConfig.sidebar.source[colField].where!=null) {
                $.each(dcConfig.sidebar.source[colField].where, function(a,b) {
                    nx = $("#sidebarSourceWhere").children().length+1;
                    relation = "eq";
                    if(b=="RAW") {
                        $("#sidebarSourceWhere").append("<tr class='rawwhere'><th class='slno' width=100px>"+nx+"</th><td colspan=100 contenteditable=true>"+a+"</td></tr>");
                    } else {
                        $("#sidebarSourceWhere").append("<tr><th class='slno' width=100px>"+nx+"</th>"+
                            "<td class='col1' contenteditable=true>"+a+"</td>"+
                            "<td><select class='form-control' data-value='"+relation+"'>"+
                                dbLogicSelector+
                            "</select></td>"+
                            "<td class='col2' contenteditable=true>"+b+"</td>"+
                            "<td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td>"+
                            "</tr>");
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
            
            $("input[name=chart_table]").val(dcConfig.charts.source[colField].table);
            $("input[name=chart_groupby]").val(dcConfig.charts.source[colField].groupby);
            $("input[name=chart_cols]").val(dcConfig.charts.source[colField].cols);
            
            if(dcConfig.charts.source[colField].where!=null) {
                $.each(dcConfig.charts.source[colField].where, function(a,b) {
                    nx = $("#chartSourceWhere").children().length+1;
                    relation = "eq";
                    if(b=="RAW") {
                        $("#chartSourceWhere").append("<tr class='rawwhere'><th class='slno' width=100px>"+nx+"</th><td colspan=100 contenteditable=true>"+a+"</td></tr>");
                    } else {
                        $("#chartSourceWhere").append("<tr><th class='slno' width=100px>"+nx+"</th>"+
                            "<td class='col1' contenteditable=true>"+a+"</td>"+
                            "<td><select class='form-control' data-value='"+relation+"'>"+
                                dbLogicSelector+
                            "</select></td>"+
                            "<td class='col2' contenteditable=true>"+b+"</td>"+
                            "<td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td>"+
                            "</tr>");
                    }
                });
            }
        }
    }
    
    
    
    showHidePanels();
}
function editReportFilter() {
    
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
    xData = {};
    $("#editorTable tbody tr").each(function() {
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
        //console.log(field,this, dataObj);
    });
    
    switch($("#editorSource select[name=srctype]").val()) {
        case "sql":
            sqlWhere = {};
            $("#editorSource .sqlWhere tbody tr").each(function() {
                if($(this).hasClass("rawwhere")) {
                    sqlWhere[$(this).find("td[contenteditable]").text()] = "RAW";
                } else {
                    sqlWhere[$(this).find("td.col1").text()] = {"VALUE":$(this).find("td.col2").text(),"OP":$(this).find("select").val()};
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
            if(qData['rules'][$(this).find("td[data-name=rule_type]").text()]==null) {
                qData['rules'][$(this).find("td[data-name=rule_type]").text()] = {};
            }
            if(qData['rules'][$(this).find("td[data-name=rule_type]").text()][$(this).find("td[data-name=rule_column]").text()]==null) {
                qData['rules'][$(this).find("td[data-name=rule_type]").text()][$(this).find("td[data-name=rule_column]").text()] = {};
            }
            qData['rules'][$(this).find("td[data-name=rule_type]").text()][$(this).find("td[data-name=rule_column]").text()][$(this).find("td[data-name=rule_value]").text()] = $(this).find("td[data-name=rule_class]").text();
        });
    }
    
    if($("#sidebarPanel select[name='sidebartype']").val().length>0) {
        qData['sidebar'] = {
            "type":$("#sidebarPanel select[name='sidebartype']").val(),
            "source":{}
        };
        
        sqlWhere = {};
        $("#sidebarSourceWhere tr").each(function() {
            if($(this).hasClass("rawwhere")) {
                sqlWhere[$(this).find("td[contenteditable]").text()] = "RAW";
            } else {
                sqlWhere[$(this).find("td.col1").text()] = {"VALUE":$(this).find("td.col2").text(),"OP":$(this).find("select").val()};
            }
        });
        
        qData['sidebar']['source'][$("#sidebarPanel *[name='sidebarcolumn']").val()] = {
                "type":"sql",
                "table":$("#sidebarPanel input[name='sidebar_table']").val(),
                "groupby":$("#sidebarPanel input[name='sidebar_groupby']").val(),
                "cols":$("#sidebarPanel input[name='sidebar_cols']").val(),
                "where":sqlWhere
            };
    }
    
    if($("#topchartPanel select[name='charttype']").val().length>0) {
        qData['charts'] = {
            "type":$("#topchartPanel select[name='charttype']").val(),
            "title":$("#topchartPanel *[name='chart_title']").val(),
            "source":[]
        };
        sqlWhere = {};
        $("#chartSourceWhere tr").each(function() {
            if($(this).hasClass("rawwhere")) {
                sqlWhere[$(this).find("td[contenteditable]").text()] = "RAW";
            } else {
                sqlWhere[$(this).find("td.col1").text()] = {"VALUE":$(this).find("td.col2").text(),"OP":$(this).find("select").val()};
            }
        });
        
        qData['charts']['source'].push({
                "type":"sql",
                "fill":$("input[name=chart_filling]").is(":checked")?true:false,
                "table":$("#topchartPanel input[name='chart_table']").val(),
                "groupby":$("#topchartPanel input[name='chart_groupby']").val(),
                "cols":$("#topchartPanel input[name='chart_cols']").val(),
                "where":sqlWhere
            });
    }
    
    
    return qData;
}

function editReportFilter(src) {
    filterConfig = $(src).closest("td").data("filter");
    if(filterConfig==null) {
        filterConfig = {
            "type":"select",
	        "nofilter":"--",
	        "options":{}
        };
    }

    console.log(filterConfig);
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
</script>