var currentActiveButton = null;
var brekotkinInAction=false;
// var currentActiveURL=null;
var theIFRAME;


function initializeShit() {
	theIFRAME=document.getElementById("hiddenCharacter");
	theIFRAME.src="https://www.youtube.com/embed/0hTn_f7SR4I?autoplay=0";
	getPageContent('../htmls/main.html','Главная');
}

function hereComesTheBrekotkin() {
	// theIFRAME=document.getElementById("hiddenCharacter");
	toggleVideo(brekotkinInAction);
}

function toggleVideo(state)
{
	 if(state)
	 {
		 theIFRAME.src=theIFRAME.src.substr(0,theIFRAME.src.length-1)+'0';
		  $('#hiddenCharacter').hide(1000);
	 }
	 else
	 {
		 theIFRAME.src=theIFRAME.src.substr(0,theIFRAME.src.length-1)+'1';
		  $('#hiddenCharacter').delay(1000).show(1000);
	 }
	 brekotkinInAction=!brekotkinInAction;
}

function wrapPage(html_content)
{
	document.getElementById('HeadingContent').innerHTML='→ '+newTitle;
	
	$('#InnerContentDIV').fadeTo("fast",0.1,function(){
		$('#InnerContentDIV').load(url,function(){
			$('#InnerContentDIV').fadeTo("fast",1);
			if (url.indexOf('contacts')!=-1)
				getTheMap();
		});
	});
}

function getPageContent(url, newTitle){

	document.getElementById('HeadingContent').innerHTML='→ '+newTitle;

	$('#InnerContentDIV').fadeTo("fast",0.1,function(){
		$('#InnerContentDIV').load(url,function(){
			$('#InnerContentDIV').fadeTo("fast",1);
			if (url.indexOf('contacts')!=-1)
				getTheMap();
		});
	});
}

function testFunctionA()
{
	alert('fahk yeu!');
}

function testFunctionB(message)
{
	alert(message);
}


function getObjectMap(objectID){

	var map = new YMaps.Map(YMaps.jQuery("#objectLocation")[0]);
	map.setCenter(new YMaps.GeoPoint(65.544095, 57.152636), 14);
  map.addControl(new YMaps.ToolBar());
  map.addControl(new YMaps.Zoom());
  map.addControl(new YMaps.ScaleLine());
}

function getTheMap()
{
	$('.maps').show();
	YMaps.jQuery(function ()
	{
       // Создает экземпляр карты и привязывает его к созданному контейнеру
       var map = new YMaps.Map(YMaps.jQuery("#ourLocation")[0]);
       // Устанавливает начальные параметры отображения карты: центр карты и коэффициент масштабирования
       map.setCenter(new YMaps.GeoPoint(65.544095, 57.152636), 14);
		//  map.addControl(new YMaps.TypeControl());
	 	map.addControl(new YMaps.ToolBar());
		map.addControl(new YMaps.Zoom());
		// map.addControl(new YMaps.MiniMap());
		map.addControl(new YMaps.ScaleLine());
		var office1 = new YMaps.Placemark(new YMaps.GeoPoint(65.529654,57.156811));
		office1.name="Главный офис";
		office1.description="здесь серьёзный бизнес";
		var office2 = new YMaps.Placemark(new YMaps.GeoPoint(65.504549,57.164478));
		office2.name="Запасной офис";
		office2.description="здесь бизнес чуть попроще";
		var office3 = new YMaps.Placemark(new YMaps.GeoPoint(65.568027,57.152860));
		office3.name="Пункт продаж";
		office3.description="продаю людей, бананы. шучу, не бананы.";
		map.addOverlay(office1);
		map.addOverlay(office2);
		map.addOverlay(office3);

      });
}

function setActiveButton(buttonID)
{

	$('#'+buttonID).addClass('activeNBB');//.animate({'padding-bottom':20},"fast");

	if (currentActiveButton!=null)
			$('#'+currentActiveButton).removeClass('activeNBB');
	currentActiveButton=buttonID;
	// stretchTheIFrame();
}

function scrollTowards(elementID) {
	$("html, body").animate({ scrollTop: $('#'+elementID).offset().top }, "fast");
}

function scrollMe(toTop) {
	//they say i must include html, body in $()
	if (toTop)
		$("theInnerContentFrame").animate({scrollTop:0},"fast");
	else $("theInnerContentFrame").animate({scrollTop:$('#theInnerContentFrame').height()},"fast");
	// if (toTop)
	// 	window.scrollTo(0,0);
	// else window.scrollTo(0,document.body.scrollHeight);
}
function scrollTable(tableID, toBottom)
{
		var theTable=document.getElementById(tableID);
		theTable.scrollIntoView(toBottom);
}

function setTheDate(elementID)
{
	 var today=new Date();
	 function getFullDate(aDate)
	 {return aDate.getDate(aDate)+'/'+(aDate.getMonth(aDate)+1)+'/'+aDate.getFullYear(aDate)}
	 document.getElementById(elementID).innerHTML = "Новости на сегодня: " + getFullDate(today);
}
