var ChangedFields=[];
var RefreshRequired=false;
// var CurrentID=-1;
var ChangedValuesString;

// $(document).ready(function () {
//     $("#theModalWindowDiv").on('hidden.bs.modal', function () {
//         if (RefreshRequired)
//             setTimeout(function () {
//                 $.ajax({
//                     method:"GET",
//                     dataType:"json",
//                     url:'../phps/databasestuff/GetTablePage.php',
//                     data:{"ID":CurrentID, },
//                     success:function (data) {
//                         if (data["error"]!=CONSTANTS["ALL_DATA_LOADED"])
//                             $("tableDIV").find(".theID").filter(function(){ $(this).html()==CurrendID}).parent().html(data["HTML"]);
//                     }
//                 });
//             },1000);
//     })
// });

function ConfirmDeletion(id) {
    $("tableDIV").remove("tr").filter($(this).find("td:first").html()==id);
}


function ValidateChanges()
{
    function AllCharactersAreDigits(x) {
        var aString=x.val();
        for (var i = 0;i<aString.length;i++)
            if (isNaN(aString[i])&&aString[i]!="-")
                return false;
        return true;
    }
    var rezult=true;
    var ChangedValues=$("#theForm").find("#ChangedValues").first();

    $("#theForm").find(".anInput").each(function () {
        if ($(this).val()=="")
        {
            if (!$(this).parent().hasClass("has-error"))
                $(this).parent().addClass("has-error has-feedback");
            rezult=false;
        }
        else if ($(this).parent().hasClass("has-error"))
            $(this).parent().removeClass("has-error has-feedback");
    })
    
    for (var i = 0;i<ChangedFields.length;i++)
        switch (ChangedFields[i])
        {
            case "MiddleName":
                var x = $("#mname");
                if (x.val().length!=1)
                {
                    if (!x.parent().hasClass("has-error"))
                        x.parent().addClass("has-error has-feedback");
                    rezult=false;
                }
                else if (x.parent().hasClass("has-error"))
                    x.parent().removeClass("has-error has-feedback");
                break;
            case "Country":
                var x = $("#countrycode");
                if (x.val().length!=2)
                {
                    if (!x.parent().hasClass("has-error"))
                        x.parent().addClass("has-error has-feedback");
                    rezult=false;
                }
                else if (x.parent().hasClass("has-error"))
                    x.parent().removeClass("has-error has-feedback");
                break;
            case "PhoneNumber":
                var x = $("#pnumber");
                x.val(x.val().replace(/\s/g,""));
                if (!(x.val().length<=12&&x.val().length>=4&&AllCharactersAreDigits(x)))
                {
                    if (!x.parent().hasClass("has-error"))
                        x.parent().addClass("has-error has-feedback");
                    rezult=false;
                }
                else if (x.parent().hasClass("has-error"))
                    x.parent().removeClass("has-error has-feedback");
                break;
            case "Postcode":
                var x = $("#postcode");
                if (!AllCharactersAreDigits(x.val()))
                {
                    x.parent().addClass("has-error has-feedback");
                    rezult=false;
                }
                else if (x.parent().hasClass("has-error"))
                    x.removeClass("has-error has-feedback");
                break;
        }
        if (rezult)
            ChangedValuesString=ChangedValues.val();

        return rezult;
}

function SendChanges(CurrentID) {
    if (ChangedValuesString[ChangedValuesString.length-1]==";")
        ChangedValuesString= ChangedValuesString.slice(0,-1);

    function dump(obj)
    {
        var out = '';
        for (var i in obj)
            out += i + ": " + obj[i] + "\n";
        return out;
    }

    var ChangedValues = ChangedValuesString.split(";");
    // alert(ChangedValues);
    var PostData=new Object();
    for (var i = 0;i<ChangedValues.length;i++)
        PostData[ChangedValues[i]]=$("#theForm").find("[name="+ChangedValues[i]+"]").val();
    PostData["Commit"]=1;

    // dump(PostData);

    $.ajax({
        method:"POST",
        data: PostData,
        dataType:"json",
        url:"../phps/databasestuff/person_edit_form.php",
        success:function(data)
        {
            $("#theModalWindowDiv").modal("hide");
            if (data["errorcode"]==-1)
                $("#tableDIV").find(".theID").filter(function(){ return $(this).html()==CurrentID}).parent().html(data["HTML"]);
            else alert("an error occured:\nerror code = "+data["errorcode"]+"; error message="+data["errormessage"]);
        }
    });
}
function ChangeReg(Field)
{
    var ChangedValues=$("#theForm").find("#ChangedValues").first();
    if (ChangedValues.val().indexOf(Field)==-1)
        ChangedValues.val(ChangedValues.val()+Field+";");

    if (ChangedFields.indexOf(Field) == -1)
        ChangedFields.push(Field);
    // or ChangedFields[ChangedFields.length]=Field;
}

function CommitDeletion(CurrentID) {
    $.ajax({
        method:"POST",
        dataType:"json",
        data:{"Commit":1},
        url:"../phps/databasestuff/person_delete_form.php",
        success:function (data) {
            $("#theModalWindowDiv").modal("hide");
            if (data["errorcode"]==-1)
                $("#tableDIV").find(".theID").filter(function(){ return $(this).html()==CurrentID}).parent().fadeOut(1000,function () {
                    $(this).remove();
                })
        }
    });
}
function AddUser() {

    function dump(obj)
    {
        var out = '';
        for (var i in obj)
            out += i + ": " + obj[i] + "\n";
        return out;
    }

    var PostData=new Object();
    $("#theForm").find(".anInput").each(function () {
        // console.log("adding property:" + $(this).attr("name")+"=>"+);
        PostData[$(this).attr("name")]=$(this).val();
    });
    PostData["Commit"]=1;

    // dump(PostData);
    $.ajax({
        method:"POST",
        data: PostData,
        dataType:"json",
        url:"../phps/databasestuff/person_add_form.php",
        beforeSend:function () {
            console.log("before send fired");
            console.log(dump(PostData));
        },
        success:function(data)
        {
            console.log("sending complete");
            console.log(data["DEBUG"]);
            $("#theModalWindowDiv").modal("hide");
            if (data["errorcode"]!=-1)
                alert("an error occured:\nerror code = "+data["errorcode"]+"; error message="+data["errormessage"]);
        }
    });
}