var CurrentPage=0;
var TableLoaded=false;
// var AllDataLoaded=false;
var AJAX_RUNNING=false;
var CONSTANTS =
    {
        "BY_PAGE":1, "ON_SCROLL":2,
        "ALL_DATA_LOADED":1000,
        "EDIT":1, "DELETE":2, "ADD":3
    }
//jQuery based AJAX: $.get, $.ajax


$(document).ready(function () {
    $(window).scroll(function () {
        if($(window).scrollTop() == $(document).height() - $(window).height())
            if (TableLoaded)
                // if (!AllDataLoaded)
                    if (!AJAX_RUNNING)
                    {
                        $.ajax({
                            method:"GET",
                            dataType:"json",
                            url: '../phps/databasestuff/GetTablePage.php?page='+ CurrentPage++ + "&FetchType="+CONSTANTS.ON_SCROLL,
                            beforeSend: function(){
                                AJAX_RUNNING=true;
                                $('#loadingDIV').show("fast");
                            },
                            complete: function(){
                                $('#loadingDIV').hide("fast");
                                AJAX_RUNNING=false;
                            },
                            success: function (data)
                            {
                                if (data["error"]!=CONSTANTS.ALL_DATA_LOADED)
                                    $("#tableDIV").find("table").find("tbody").find("tr:last-child").after(data["HTML"]);
                                // else AllDataLoaded=true;
                            }
                        })
                    }
    })
});

function FetchNextPage(isNext) {
    if (isNext)
        $.get("../phps/databasestuff/GetTablePage.php",{page:++CurrentPage, FetchType:CONSTANTS.BY_PAGE},function (data, status) {
                $("#tableDIV").fadeTo("fast",0, function(){
                    $("#tableDIV").html(data);
                }).fadeTo("fast",1);
        });
    else $.ajax(
            {
                method:"GET",//is default
                url: '../phps/databasestuff/GetTablePage.php?page='+ --CurrentPage+ '&FetchType='+CONSTANTS.BY_PAGE,
                dataType:"json",
                success: function (data)
                {
                    $("#tableDIV").fadeTo("fast",0, function(){
                        $("#tableDIV").html(data);
                    }).fadeTo("fast",1);
                }
            });
}

//alternative jQuery based AJAX: load
function alternativePageLoad(nextPage)
{
    $("tableDIV").fadeTo("fast",0).html("");
    $("#tableDIV").load("../phps/databasestuff/GetTablePage.php",{page:nextPage, FetchType:CONSTANTS.BY_PAGE},function (data,status) {
        }).fadeTo("fast",1);
}

function submitEditing()
{
    $.ajax({
                type: 'POST',
                url: 'response.php?action=edit',
                data: 'name=Andrew&nickname=Aramis',
                // data:{ name:"Andrew", → probably is going to be transformed into a query such as one above ↑
                //         nickname:"Aramis"},
                success: function(data){
                    $('.results').append(data);
                }
            });
}


function FetchFirstPage()
{
    var xmlR=new XMLHttpRequest();
    xmlR.timeout=30000;
    xmlR.ontimeout=function () {
        alert('timeout reached, request state: '+this.readyState+'; responsetext='+this.responseText);
    }
    xmlR.onerror=function () {
        alert('shiiit boyy, error, request state: '+this.readyState+'; responsetext='+this.responseText);
    }
    xmlR.onreadystatechange=function(){
        if (this.readyState==4) //UNSENT=0; OPENED=1; HEADERS_RECEIVED=2; LOADING=3; DONE=4;
            if (this.status!=200)
                alert('damn idk: '+this.status+';'+xmlR.statusText);
            else
            {
                var Package=JSON.parse(this.responseText);
                if (Package["error"]==CONSTANTS.ALL_DATA_LOADED)
                    CONSTANTS.ALL_DATA_LOADED=true;
                else $("#tableDIV").html(Package["HTML"]);
                TableLoaded=true;
                $("#requestTable").hide("fast");
                $("#NabnarItems").append("<li><button  type='button' class='btn btn-primary extendedBtn-top' onclick='ShowModalWindow(3,-1);'>Добавить запись</button></li>");

            }
        };

    xmlR.open("GET", "../phps/databasestuff/GetTablePage.php?page="+ CurrentPage++ +"&FetchType="+CONSTANTS.BY_PAGE);
    xmlR.send();


}


function ShowModalWindow(reason, pID)//1: EDIT, 2:DELETE
{
    switch (reason)
    {
        case CONSTANTS.EDIT:
            $.ajax({
                method:"GET",
                url: '../phps/databasestuff/person_edit_form.php',
                data:{ "pID":pID},
                success: function (data)
                {
                    $("#theModalWindowDiv").html(data);
                    $("#theModalWindowDiv").modal({backdrop:"static"});
                }

            });
            // $("#theModalWindowDiv").load(encodeURI("../phps/databasestuff/person_edit_form.php"),{id:pID}, function () {
            //     $("#theModalWindowDiv").modal("show");
            // });
            break;
        case CONSTANTS.DELETE:
            $.ajax({
                method:"GET",
                dataType:"json",
                url: '../phps/databasestuff/person_delete_form.php',
                data:{ "pID":pID},

                success: function (data)
                {
                    if (data["errorcode"]==-1)
                    {
                        console.log(data["HTML"]);//empty().append
                        $("#theModalWindowDiv").html(data["HTML"]);
                        $("#theModalWindowDiv").modal({backdrop:"static"});
                    }
                    else console.log("errorcode="+data["errorcode"]+"\nerrormessage="+data["errormessage"]);
                }
            });
            break;
        case CONSTANTS.ADD:
            $.ajax({
                method:"GET",
                url: '../phps/databasestuff/person_add_form.php',
                success: function (data)
                {
                    $("#theModalWindowDiv").html(data);
                    $("#theModalWindowDiv").modal({backdrop:"static"});
                }
            });
            break;
    }
}

//old but gold  echo '<li class="previous"><a href="#" onclick="FetchPage('.htmlspecialchars("<?php echo --\$CurrentPage?>") .');" id="previouspage">Previous</a></li>';
