var dcConfig = null;

//templaes
var eleSidebarTable = '<li class="list-group-item list-table-columns" data-colkey="{{colkey}}" data-table="{{table}}" data-column="{{name}}" data-type="{{type}}" data-null="{{null}}" data-key="{{key}}" data-default="{{default}}" data-extras="{{extras}}">{{name}}</li>';
var eleSidebarFormula = '<li class="list-group-item list-formula" data-formula="{{formula}}">{{name}}</li>';
var eleSidebarFormElements = '<li class="list-group-item list-form-item" data-element="{{element}}">{{name}}</li>';

var propRow = "<tr><th>{{title}}</th><td class='{{class}}'>{{{formfield name value}}}</td></tr>";
var actRow = "<tr><th class='slno'>{{nx}}</th><td data-name='fieldaction' contenteditable=true>{{action}}</td><td data-name='label' contenteditable=true>{{label}}</td><td data-name='icon' contenteditable=true>{{icon}}</td><td data-name='class' contenteditable=true>{{class}}</td><td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>";
var hookRow = "<tr><th class='slno'>{{nx}}</th><td data-name='hookstate'>{{{formfield 'hook_states' hookstate}}}</td><td data-name='calltype'>{{{formfield 'hook_calltype' calltype}}}</td><td data-name='methods' contenteditable=true>{{methods}}</td><td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>";

$(function() {
    Handlebars.registerHelper('formfield', function (name, value) {
        //console.log(name, value, typeof value, isNaN(value));
        if(name == "report_rule_types") {
            return "<select class='form-control' name='"+name+"' data-value='"+value+"'>"+
                        "<option value='row_class'>Row Logic</option>"+
                        "<option value='col_class'>Column Logic</option>"+
                    "</select>";
        } else if(name == "hook_calltype") {
            return "<select class='form-control' name='"+name+"' data-value='"+value+"'>"+
                        "<option value='helpers'>helpers</option>"+
                        "<option value='modules'>modules</option>"+
                        "<option value='vendors'>vendors</option>"+
                        "<option value='method'>method</option>"+
                        "<option value='api'>api</option>"+
                        "<option value='file'>file</option>"+
                    "</select>";
        } else if(name == "hook_states") {
            if(dcMode=="reports") {
                return "<select class='form-control' name='"+name+"' data-value='"+value+"'>"+
                        "<option value='fieldupdate'>fieldupdate</option>"+
                        "<option value='fieldpush'>fieldpush</option>"+
                    "</select>";
            } else if(dcMode == "forms") {
                return "<select class='form-control' name='"+name+"' data-value='"+value+"'>"+
                        "<option value='preload'>preload</option>"+
                        "<option value='presubmit'>presubmit</option>"+
                        "<option value='postsubmit'>postsubmit</option>"+
                        "<option value='dataposted'>dataposted</option>"+
                    "</select>";
            } else {
                return "<select class='form-control' name='"+name+"' data-value='"+value+"'>"+
                        
                    "</select>";
            }
        } else if(name == "showExtraColumn") {
            return "<select class='form-control' name='"+name+"' data-value='"+value+"'>"+
                        "<option value=''>None</option>"+
                        "<option value='checkbox'>Checkbox</option>"+
                        "<option value='radio'>Radio</option>"+
                    "</select>";
        } else if(name == "rowlink") {
            return "<input class='form-control' type='text' name='"+name+"' value='"+value+"' />";
        } else if(name == "privilege") {
            return "<input class='form-control' type='text' name='"+name+"' value='"+value+"' />";
        } else if(["hidden","sortable","groupable","searchable"].indexOf(name)>=0) {
            if(value) {
                return "<input class='form-control' type='checkbox' name='"+name+"' checked />";
            } else {
                return "<input class='form-control' type='checkbox' name='"+name+"' />";
            }
        } else if(name == "formatter") {
            return "<select class='form-control' name='"+name+"' data-value='"+value+"'>"+
                        "<option value=''>None</option>"+
                        "<option value='date'>Date</option>"+
                        "<option value='time'>Time</option>"+
                        "<option value='datetime'>Datetime</option>"+
                        "<option value='currency'>Currency</option>"+
                        "<option value='number'>Number</option>"+
                        "<option value='url'>Url</option>"+
                        "<option value='email'>Email</option>"+
                        "<option value='phone'>Phone</option>"+
                        "<option value='geolocation'>Geolocation</option>"+
                        "<option value='color'>Color</option>"+
                        "<option value='avatar'>Avatar</option>"+
                        "<option value='photo'>Photo</option>"+
                        "<option value='attachment'>Attachment</option>"+
                        "<option value='mediafile'>Mediafile</option>"+
                        "<option value='method'>Method</option>"+
                        "<option value='embed'>Embed</option>"+
                        "<option value='video'>Video</option>"+
                        "<option value='iframe'>Iframe</option>"+
                        "<option value='content'>Content</option>"+
                        "<option value='json'>JSON</option>"+
                        "<option value='checkbox'>Checkbox</option>"+
                        "<option value='pretty'>Pretty</option>"+
                        "<option value='uppercase'>Uppercase</option>"+
                        "<option value='lowercase'>Lowercase</option>"+
                    "</select>";
        }
        
        
        if(typeof value == 'number') {
            return "<input class='form-control' type='number' name='"+name+"' value='"+value+"' />";
        } else if(typeof value == 'boolean') {
            if(value) {
                return "<label class='form-checkbox'><input class='form-control' type='radio' name='"+name+"' value='true' checked /> Enabled</label>"+
                        "<label class='form-checkbox'><input class='form-control' type='radio' name='"+name+"' value='false' /> Disabled</label>";
            } else {
                return "<label class='form-checkbox'><input class='form-control' type='radio' name='"+name+"' value='true' /> Enabled</label>"+
                        "<label class='form-checkbox'><input class='form-control' type='radio' name='"+name+"' value='false' checked /> Disabled</label>";
            }
        } else if(typeof value == 'object') {
            return "<textarea class='form-control' name='"+name+"'>"+JSON.stringify(value,null,"\t")+"</textarea>";
        } else if(typeof value == 'string' && value.length>40) {
            return "<textarea class='form-control' name='"+name+"'>"+value+"</textarea>";
        }
        
        return "<input class='form-control' type='text' name='"+name+"' value='"+value+"' />";
    });
    
    //Compile All Templates
    eleSidebarTable = Handlebars.compile(eleSidebarTable);
    eleSidebarFormula = Handlebars.compile(eleSidebarFormula);
    eleSidebarFormElements = Handlebars.compile(eleSidebarFormElements);
    propRow = Handlebars.compile(propRow);
    actRow  = Handlebars.compile(actRow);
    hookRow = Handlebars.compile(hookRow);
    
    $("tbody").delegate("tr .removeMe", "click", function() {
        $(this).closest("tr").detach();
        if(typeof updateUIImpact == "function") updateUIImpact();
    });
    
    $(".bodySpace").delegate(".actionIcon[data-cmd]", "click", function() {
        cmd = $(this).data("cmd");
        
        if(typeof window[cmd] == "function") { 
            window[cmd](this);
        } else console.info("Function Not Found",cmd);
    });
    
    $(".tableDropdown").load(_service("datacontrolsEditor","listTables","select"), function() {
        $(this).val($(this).data("value"));
    });
});

function refreshUI() {
    window.location.reload();
    // $("#tableDropdown").load(_service("datacontrolsEditor","listTables","select"), function() {
    //     loadColumnList();
    // });
    //loadDBFormulas();
}

function loadTableDropdown() {
    
}
function loadColumnList() {
    $("#tableTree").html("<div class='ajaxloading ajaxloading8'></div>");
    if($("#tableDropdown").val()==null || $("#tableDropdown").val().length<=0) {
        $("#tableTree").html("No Tables Selected");
    }
    
    processAJAXQuery(_service("datacontrolsEditor", "listColumns", "json")+"&showHidden=false&tbl="+$("#tableDropdown").val(), function(data) {
        if(data.Data!=null) {
            $("#tableTree").html("");
            $.each(data.Data, function(a,b) {
                $("#tableTree").append(eleSidebarTable(b));
            });
            
            $("#tableTree li.list-group-item").draggable({
                    opacity: 0.7, 
                    zIndex: 9999,
                    helper: function() {
                        $copy = $(this).clone();
                        //$copy.css("width","300px");
                        return $copy;
                    },
                    appendTo: "#pgworkspace",
                    containment: '#pgworkspace',
                    scroll: false,
                    cancel: ".disabled",
                    //revert: true,
                    //connectToSortable: ".tableColumReceiver",
                    //handle: "h2"
                });
        } else {
            $("#tableTree").html("No Columns Found");
        }
    }, "json");
}
function loadDBFormulas() {
    $("#formulaTree").html("<div class='ajaxloading ajaxloading8'></div>");
    
    processAJAXQuery(_service("datacontrolsEditor", "listFormulas", "json"), function(data) {
        if(data.Data!=null) {
            $("#formulaTree").html("");
            $.each(data.Data, function(a,b) {
                if(b.type==null || b.type.length<=0) b.type="general";

                if($("#formulaTree .list-group-group[data-refid="+b.type+"]").length<=0) {
                    $("#formulaTree").append("<div class='list-group-group' data-refid='"+b.type+"'><h5 onclick='toggleSubComponent(this)'>"+toTitle(b.type)+" <i class='fa fa-angle-down pull-right'></i></h5><div class='list-group-block hidden fade'></div></div>");
                }
    
                $("#formulaTree .list-group-group[data-refid="+b.type+"] .list-group-block").append(eleSidebarFormula(b));
                //$("#formulaTree").append(eleSidebarFormula(b));
            });
            $("#formulaTree .list-group-group[data-refid=general] .list-group-block").removeClass("hidden").addClass("in");
            $("#formulaTree .list-group-group[data-refid=general]").find(".fa.fa-angle-down").removeClass("fa-angle-down").addClass("fa-angle-up");
            
            $("#formulaTree li.list-group-item").draggable({
                    opacity: 0.7, 
                    zIndex: 9999,
                    helper: function() {
                        $copy = $(this).clone();
                        //$copy.css("width","300px");
                        return $copy;
                    },
                    appendTo: "#pgworkspace",
                    containment: '#pgworkspace',
                    scroll: false,
                    cancel: ".disabled",
                    //revert: true,
                    //connectToSortable: ".tableColumReceiver",
                    //handle: "h2"
                });
        } else {
            $("#formulaTree").html("No Formulas Found");
        }
    }, "json");
}
function loadFormElements() {
    $("#formElementTree").html("<div class='ajaxloading ajaxloading8'></div>");
    
    processAJAXQuery(_service("datacontrolsEditor", "listFormElements", "json"), function(data) {
        if(data.Data!=null) {
            $("#formElementTree").html("");
            $.each(data.Data, function(a,b) {
                $("#formElementTree").append(eleSidebarFormElements(b));
            })
        } else {
            $("#formElementTree").html("No Formulas Found");
        }
    }, "json");
}
function loadDesignBlocks() {
    $("#blockTree").html("<div class='ajaxloading ajaxloading8'></div>");
    
    processAJAXQuery(_service("datacontrolsEditor", "listDesignBlock", "json"), function(data) {
        if(data.Data!=null) {
            $("#blockTree").html("");
            $.each(data.Data, function(a,b) {
                $("#blockTree").append(eleSidebarFormElements(b));
            })
        } else {
            $("#blockTree").html("No Block Found");
        }
    }, "json");
}
function loadDC() {
    processAJAXQuery(_service("datacontrolsEditor", "fetch", "json","&dchash="+dcHash+"&dcmode="+dcMode), function(data) {
        if(data.Data==null) {
            lgksAlert("Error loading DataControl File");
            return;
        }
        if(data.Data.status=="error") {
            lgksAlert(data.Data.msg);
            return;
        }
        
        dcConfig = data.Data;
        
        updateDCCommonPanels();
        if(typeof updateDCPanelUI == "function") {
            updateDCPanelUI();
        }
        
        initMiscUI();
    }, "json");
}

function updateDCCommonPanels() {
    $("#dcTitle").html(dcConfig.title);
    $("#dcTags").html("<label class='label label-success'>"+dcConfig.category+"</label>");
    
    if(dcConfig.source!=null && dcConfig.source.table!=null) {
        $("#tableDropdown").val(dcConfig.source.table.split(",")[0]);
        loadColumnList();
    }
    
    //Properties
    if($("#editorProperties").length>0) {
        $("#editorProperties tbody").html("");
        $.each(dcConfig, function(a,b) {
            if(typeof b != "object" && ["schema"].indexOf(a)<0) {
                $("#editorProperties tbody").append(propRow({"name":a,"title":toHumanCase(a),"value":b}));
            }
        });
    }
    
    
    if(dcConfig.actions!=null && $("#dcActions").length>0) {
        $("#dcActions").html("");
        $.each(dcConfig.actions, function(a,b) {
            b.action = a;
            b.nx = $("#dcActions").children().length+1;
            $("#dcActions").append(actRow(b));
        });
    }
    
    if(dcConfig.buttons!=null && $("#dcButtons").length>0) {
        $("#dcButtons").html("");
        $.each(dcConfig.buttons, function(a,b) {
            b.action = a;
            b.nx = $("#dcButtons").children().length+1;
            $("#dcButtons").append(actRow(b));
        });
    }
    
    if(dcConfig.hooks!=null && $("#editorHooks").length>0) {
        $("#editorHooks tbody").html("");
        $.each(dcConfig.hooks, function(a,b) {
            bkeys = Object.keys(b);
            $.each(bkeys, function(c, d) {
                if(Array.isArray(b[d])) b[d] = b[d].join(",");
                
                $("#editorHooks tbody").append(hookRow({
                    "nx": $("#editorHooks tbody").children().length+1,
                    "hookstate":a,
                    "calltype":d,
                    "methods":b[d],
                }));
            })
        });
    }
    
    
    
    
    
    
    
    
    
    
    if($("#codeEditorStyle").length>0) {
        $("#codeEditorStyle").val("");
        processAJAXQuery(_service("datacontrolsEditor", "fetchStyle", "raw","&dchash="+dcHash+"&dcmode="+dcMode), function(data) {
            $("#codeEditorStyle").val(data);
        }, "raw");
    }
    
    if($("#codeEditorScript").length>0) {
        $("#codeEditorScript").val("");
        processAJAXQuery(_service("datacontrolsEditor", "fetchScript", "raw","&dchash="+dcHash+"&dcmode="+dcMode), function(data) {
            $("#codeEditorScript").val(data);
        }, "raw");
    }
}

function initMiscUI() {
    $(".bodySpace select[data-value]").each(function() {
        if(typeof $(this).data("value")=="boolean") $(this).val("false");
        else $(this).val($(this).data("value"));
    });
}

function collectDCData() {
    qData = {};
    if($("#editorProperties tbody").length>0) {
        $("input[name],select[name],textarea[name]","#editorProperties tbody").each(function(a,b) {
            if($(this).attr("type")=="radio" || $(this).attr("type")=="checkbox") {
                if($(this).is(":checked")) {
                    qData[$(this).attr("name")] = (this.value=="true")?true:false;
                }
            } else {
                qData[$(this).attr("name")] = $(this).val();
            }
        });
    }
    
    if($("#dcActions").length>0 && $("#dcActions tr").length>0) {
        qData['actions'] = {};
        $("#dcActions tr").each(function(a, b) {
            $(this).removeClass("alert-danger").removeClass("alert");
            
            if($(this).find("td[data-name=fieldaction]").text().length>0) {
                qData['actions'][$(this).find("td[data-name=fieldaction]").text()] = {
                    "label":$(this).find("td[data-name=label]").text(),
                    "icon":$(this).find("td[data-name=icon]").text(),
                    "class":$(this).find("td[data-name=class]").text(),
                };
            } else {
                $(this).addClass("alert alert-danger");
            }
        });
    }
    if($("#dcButtons").length>0 && $("#dcButtons tr").length>0) {
        qData['buttons'] = {};
        $("#dcButtons tr").each(function(a, b) {
            $(this).removeClass("alert-danger").removeClass("alert");
            
            if($(this).find("td[data-name=fieldaction]").text().length>0) {
                qData['buttons'][$(this).find("td[data-name=fieldaction]").text()] = {
                    "label":$(this).find("td[data-name=label]").text(),
                    "icon":$(this).find("td[data-name=icon]").text(),
                    "class":$(this).find("td[data-name=class]").text(),
                };
            } else {
                $(this).addClass("alert alert-danger");
            }
        });
    }
    
    if($("#editorHooks").length>0 && $("#editorHooks tbody tr").length>0) {
        xData = {};
        $("#editorHooks tbody tr").each(function() {
            $(this).removeClass("alert-danger").removeClass("alert");
            
            hookState = $(this).find("td[data-name=hookstate] select").val();
            hookCalltype = $(this).find("td[data-name=calltype] select").val();
            
            if($(this).find("td[data-name=methods]").text().length>0) {
                if(xData[hookState]==null) xData[hookState] = {};
                if(xData[hookState][hookCalltype]==null) xData[hookState][hookCalltype] = [];
                
                xData[hookState][hookCalltype] = $.merge(xData[hookState][hookCalltype], $(this).find("td[data-name=methods]").text().split(","));
            } else {
                $(this).addClass("alert alert-danger");
            }
        });
        qData['hooks'] = xData;
    }
    
    if(typeof collectDCMoreData == "function") {
        qData = collectDCMoreData(qData);
        
        if(qData===false) {
            console.log("DC Validation Failed");
            return;
        }
    }
    
    return qData;
}

function codeEditorDC() {
    
}

function codeReviewDC() {
    lgksOverlay("<textarea id='codeReview' class='editorArea' readonly>"+JSON.stringify(collectDCData(), null, "\t")+"</textarea>", "Code Preview", {"className":"overlayBox codeReviewPanel"});
}

function previewDC() {
    processAJAXQuery(_service("datacontrolsEditor", "previewlink", "json","&dchash="+dcHash+"&dcmode="+dcMode), function(data) {
        if(data.Data.status=="success") {
            window.open(data.Data.link);
        } else {
            lgksToast(data.Data.msg);
        }
    },"json")
}

function saveDC() {
    jsonData = JSON.stringify(collectDCData(), null, "\t");
    processAJAXPostQuery(_service("datacontrolsEditor", "save", "raw","&dchash="+dcHash+"&dcmode="+dcMode), 
        "&qdata="+encodeURIComponent(jsonData)+
        "&style="+encodeURIComponent($("#codeEditorStyle").val())+
        "&script="+encodeURIComponent($("#codeEditorStyle").val()), function(response) {
        if(response.toUpperCase().indexOf("ERROR")==0) {
            lgksToast(response);
        } else {
            if($("tbody tr.alert").length>0) {
                lgksAlert("Save Successfull with some error<br>Errors have been marked RED on screen<br><br>Please check and save again.");
            } else {
                lgksToast(response);
            }
        }
    });
}

function toggleSubComponent(src) {
    $(src).closest(".list-group-root").find(".list-group-block.fade.in").removeClass("in").addClass("hidden");
    $(src).closest(".list-group-root").find(".fa.fa-angle-up").removeClass("fa-angle-up").addClass("fa-angle-down");
    
    if($(src).closest(".list-group-group").find(".fade").hasClass("hidden")) {
        $(src).find(".fa").removeClass("fa-angle-down").addClass("fa-angle-up");
        $(src).closest(".list-group-group").find(".fade").removeClass("hidden").addClass("in");
    } else {
        $(src).find(".fa").removeClass("fa-angle-up").addClass("fa-angle-down");
        $(src).closest(".list-group-group").find(".fade").addClass("hidden").removeClass("in");
    }
}

//Blank Records
function addBlankHook(src) {
  $("#editorHooks tbody").append(hookRow({
            "nx": $("#editorHooks tbody").children().length+1,
            "hookstate":"",
            "calltype":"",
            "methods":"",
        }));
}
function addBlankAction(src) {
  $("#dcActions").append(actRow({
      "nx":$("#dcActions").children().length+1,
  }));
}
function addBlankButton(src) {
  $("#dcButtons").append(actRow({
      "nx":$("#dcButtons").children().length+1,
  }));
}

//Where Query Logic
function addQueryWhere_RAW(src, sql) {
    if(sql==null) sql ="";
    nx = $(src).closest("table").find("tbody tr").length+1;
    $(src).closest("table").find("tbody").append("<tr class='rawwhere'><th class='slno'>"+nx+"</th><td colspan=3 contenteditable=true>"+sql+"</td><td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td></tr>");
}
function addQueryWhere_LOGIC(src, a, b) {
    relation = "eq";
    if(a==null) a ="";
    if(b==null) b ="";
    
    if(typeof b == "object") {
        if(b.OP!=null) relation = b.OP;
        if(b.VALUE!=null) b = b.VALUE;
    }
    
    nx = $(src).closest("table").find("tbody tr").length+1;
    $(src).closest("table").find("tbody").append("<tr><th class='slno'>"+nx+"</th>"+
            //"<td class='col1' contenteditable=true><select data-name='source_where_col1' class='form-control' data-value='"+a+"'></select></td>"+
            "<td class='col1' contenteditable=true>"+a+"</td>"+
            "<td><select class='form-control' data-value='"+relation+"'>"+
                dbLogicSelector+
            "</select></td>"+
            "<td class='col2' contenteditable=true>"+b+"</td>"+
            "<td class='action'><i class='removeMe fa fa-times pull-right clr_red'></i></td>"+
            "</tr>");
}

//Utility Functions
function toHumanCase(str) {
    if(str==null || str.length<=0)  return "";
    else return toTitle(str).match(/[A-Z][a-z]+/g).join(" ");
}
