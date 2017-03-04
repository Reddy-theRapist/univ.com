var bill=0;
function SubmitOrder() {
    var total=GetBillTotal();
    // $("#arrivalDate").prop("min",function () {
    //     var X =new Date().toJSON().split('T');
    //     var rezult=X[0]+" "+X[1];
    //     return rezult;
    // });
    // console.log(new Date().toJSON());
    $("#arrivalDate").prop("min",function(){ return new Date().toLocaleDateString()+ ' '+new Date().toLocaleTimeString()});
    $("#arrivalDate").val(new Date().toLocaleDateString()+ ' '+new Date().toLocaleTimeString());
    // console.log("yo what the fuck!?");
    if (total>0)
    {
        $("#billTotal").html(total);
        $("#theModalWindowDiv").modal({backdrop:"static"});
    }
    else alert("Не выбран ни один товар");
}
function GetBillTotal(){
    var rezult=0;
    $("#theTable").find("tr").has(":checkbox:checked").each(function () {
        rezult+=$(this).find(".quantity").first().val()*$(this).find("td:eq(4)").first().html();
    });
    bill=rezult;
    return rezult;
}
function SendOrderDetails() {
    console.log('inside sendorderdetails');
    var PostData=new Object();
    PostData["ClientName"]=$("#FullName").val();
    PostData["Phone"]=$("#phone").val();
    PostData["RentTime"]=$("#rentTime").val();
    PostData["ArrivalDateTime"]=$("#arrivalDate").val();
    PostData["IDs"]="";
    PostData["Quantities"]="";
    PostData["COMMIT"]=1;

    $("#theTable").find("tr").has(":checkbox:checked").each(function () {
        PostData["IDs"]+=$(this).find(".theID").html()+";";
        PostData["Quantities"]+=$(this).find(".quantity").first().val()+";";
    });
    if (PostData["IDs"].length>0)
    {
        PostData["IDs"]= PostData["IDs"].slice(0, -1);
        PostData["Quantities"]= PostData["Quantities"].slice(0, -1);
    }

    // dump(PostData);
    $.ajax({
        method:"POST",
        data: PostData,
        dataType:"json",
        url:"ConfirmPayment.php",
        beforeSend:function () {
            console.log("before send fired");
            // console.log(dump(PostData));
        },
        success:function(data)
        {
            console.log("sending complete");
            console.log(data["debug"]);
            $("#theModalWindowDiv").modal("hide");
            if (data["errorcode"]!=-1)
                alert("an error occured:\nerror code = "+data["errorcode"]+"; error message="+data["errormessage"]);
        }
    });
}

function derValidateChanges()
{
    var rezult=true;
    var x = $("#rentTime");
    if (x.val()<1)
    {
        if (!x.parent().hasClass("has-error"))
            x.parent().addClass("has-error has-feedback");
        rezult=false;
    }
    else if (x.parent().hasClass("has-error"))
        x.parent().removeClass("has-error has-feedback");

    x = $("#arrivalDate");

    if (new Date(x.val())<new Date())
    {
        if (!x.parent().hasClass("has-error"))
            x.parent().addClass("has-error has-feedback");
        rezult=false;
    }
    else if (x.parent().hasClass("has-error"))
        x.parent().removeClass("has-error has-feedback");

    return rezult;
}
