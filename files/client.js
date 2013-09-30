var pjs = null;

var serverToken;

function initClient()
{
	pjs = Processing.getInstanceById(getProcessingSketchId());

	// check the the token is valid and is last
	var urlToken = getURLParameter("token");

	$.get( "getlasttoken.php", function(data) {
		$( ".result" ).html(data);

		xmlDoc = $.parseXML(data);
		$data = $( xmlDoc );

		$(data).find("token").each(function()
		{
			serverToken = $(this).text();
		});

		if (serverToken != urlToken) {
			pjs.alreadyCompleted();
			return;
		}

		initSketch();
	});
}

function initSketch()
{
	// get last painting from server
	$.get( "getlastdata.php", function( data ) {

  		var templateAndVerify = xmlToPainting(data);

		var template = templateAndVerify.painting;
		var verificationCode = templateAndVerify.verificationCode;

		// init sketch template with data
		pjs.initTemplate(template);
		pjs.setVerificationCode(verificationCode);

		// enable sketch drawing
		pjs.startSketch();
	});
}

function done()
{
	if (pjs == null)
		return "";

	var didDraw = pjs.userDidDraw();
	if (didDraw == false) {
		alert("please copy the image on the right.");
		return;
	}

	var name = document.getElementById('yourname').value;
	if (name == undefined || name == "") {
		alert("please type your name");
		return;
	}

	document.getElementById("doneButton").disabled = 'true';

	var painting = pjs.getPainting();
	var str = paintingToXml(painting);

	// save the data on the server
	$.post( "savedata.php", {
		user_id: "user_id_"+name,
		task_id: "task_id_"+name,
		username: name,
		data: str,
		token: serverToken} );

	pjs.taskCompleted();
}

function paintingToXml(painting) {
	var str = "<paint>";
	for (var s=0; s<painting.size(); s++)
	{
		var stroke = painting.get(s);
		str += "<stroke>";
		for (var p=0; p<stroke.points.size(); p++)
		{
			var point = stroke.points.get(p);
			str += "<point x='"+point.x+"' y='"+point.y+"' frame='"+point.frameNum+"'></point>";
		}
		str += "</stroke>"
	}
	str += "</paint>";

	return str;
}

function xmlToPainting(xml)
{
	var xmlDoc;

	if (pjs == null) {
		return;
	}

	xmlDoc = $.parseXML(xml);
	var $xml = $( xmlDoc );

	var painting = new pjs.ArrayList();
	$(xml).find("stroke").each(function()
	{
		stroke = new pjs.GStroke(0, 0);
		$(this).find("point").each(function()
		{
			var x = $(this).attr("x");
			var y = $(this).attr("y");
			var frame = $(this).attr("frame");

			stroke.addPoint(parseInt(x, 10), parseInt(y, 10), parseInt(frame, 10));

		});
		painting.add(stroke);
	});

	var verify;
	$(xml).find("verify").each(function()
	{
		verify = $(this).text();
	});

	var paintingWithVerify = {};
	paintingWithVerify.painting = painting;
	paintingWithVerify.verificationCode = verify;

	return paintingWithVerify;
}

function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}
window.onload = function() {
	setTimeout(initClient, 2000);
};
