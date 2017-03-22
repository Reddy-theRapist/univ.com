/**
 * Created by YoloSwaggerson on 10.03.2017.
 */

function ban_faggot(faggot_id, screen_name)
{
    var xmlR=new XMLHttpRequest();
    xmlR.timeout=30000;
    xmlR.ontimeout=function () {
        console.log('timeout reached, request state: '+this.readyState+'; responsetext='+this.responseText);
    }
    xmlR.onerror=function () {
        console.log('shiiit boyy, error, request state: '+this.readyState+'; responsetext='+this.responseText);
    }
    xmlR.onreadystatechange=function()
    {
        if (this.readyState==4) //UNSENT=0; OPENED=1; HEADERS_RECEIVED=2; LOADING=3; DONE=4;
            if (this.status!=200)
                console.log('damn idk: '+this.status+';'+xmlR.statusText);
            else
            {
                // console.log(this.responseText);
                // return;
                var Package=JSON.parse(this.responseText);
                console.log(Package["debug"]);
                if (Package["op_status"]==1)
                {
                    $("#bitch_"+screen_name).fadeTo("fast",0.1,function ()
                    {
                        $("#bitch_"+screen_name).html("Зобанен").off("click").removeClass("btn-warning").addClass("btn-danger");
                    }).fadeTo("fast",1);
                    console.log("чмоха зобанена");
                }
            }
    };

    var body = 'fagID=' + encodeURIComponent(faggot_id);
    console.log("sending body:"+body);
    xmlR.open("POST", "twitter/ban_EventHandler.php",true);
    xmlR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlR.send(body);
}

function subscribe_faggot(faggot_id, screen_name)
{
    console.log("bitch_"+screen_name);
    console.log($("#bitch_"+screen_name).html());
    var xmlR=new XMLHttpRequest();
    xmlR.timeout=30000;
    xmlR.ontimeout=function ()
    {console.log('timeout reached, request state: '+this.readyState+'; responsetext='+this.responseText);}

    xmlR.onerror=function ()
    {console.log('shiiit boyy, error, request state: '+this.readyState+'; responsetext='+this.responseText);}

    xmlR.onreadystatechange=function()
    {
        if (this.readyState==4) //UNSENT=0; OPENED=1; HEADERS_RECEIVED=2; LOADING=3; DONE=4;
            if (this.status!=200)
                console.log('damn idk: '+this.status+';'+xmlR.statusText);
            else
            {
                var Package=JSON.parse(this.responseText);
                console.log(Package["debug"]);
                if (Package["op_status"]==1)
                {
                    $("#bitch_"+screen_name).fadeTo("fast",0.1,function ()
                    {
                        $("#bitch_"+screen_name).off("click").html("патписка есть").removeClass("btn-primary").addClass("btn-success");
                    }).fadeTo("fast",1);
                    console.log("ооо братишка, уважаю");
                }
            }
    };

    var body = 'fagID=' + encodeURIComponent(faggot_id);
    console.log("sending body:"+body);
    xmlR.open("POST", "twitter/subscribe_EventHandler.php",true);
    xmlR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlR.send(body);
}