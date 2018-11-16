//Missing Comments sorry!
//Missing JS validation for text fields and messages to inform user(red error text).
var g = {};

function submitRequest()
{

	g.req = new XMLHttpRequest();

	//true = synchronous
	g.req.open('POST', 'stickyServer.php', true);

	g.req.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	g.req.onreadystatechange = handleReply;

	g.req.send('text=' + g.textArea.value);

	//Refresh page (to avoid concurency issues).

}
function logoutRequest()
{
	g.req = new XMLHttpRequest();
	//true = synchronous
	g.req.open('POST', 'stickyServer.php', true);
	g.req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	g.req.onreadystatechange = handleReply;
	g.req.send('logout=' + true);

	//window.location.replace("./login.html");
}
/**
 * Z-Index are handled dynamically by css, and not stacked(highest on page) per spec request...
 *
 */
function handleReply()
{

	if((g.req.readyState==4)&&(g.req.status == 200)) 
	{
		var response = g.req.responseText;
	 	g.jobj = JSON.parse(response);

	 	//This may return null if no stickies are found (trigger exception.)
	 	//Exception has no affect on user.
	 	if (g.jobj == "NO")
	 	{
	 		alert("You are not authenticated and cannot be here!");
	 		window.location.replace("./login.html");
	 	}
	 	else
	 	{
	 		for (var i =0 ; i < g.jobj.length ; i++)
	 		{
	 			//dom creation stuff
			 	var bigDiv = document.createElement("div");
			 	var btn = document.createElement("button");
			 	var div = document.createElement("div");

			 	
			 	bigDiv.setAttribute('class','draggable  sticky');
			 	bigDiv.setAttribute('id', g.jobj[i].id);

			 	bigDiv.style.left = g.jobj[i].leftpos;
			 	bigDiv.style.top = g.jobj[i].toppos;
			 	bigDiv.style.zIndex = g.jobj[i].zindex;


			 	btn.setAttribute('class', 'xButton');

			 	
			 	btn.innerHTML='&#10006';
			 	div.innerHTML= "<p>"+g.jobj[i].text+"</p>";

			 	g.container.appendChild(bigDiv);
				bigDiv.appendChild(btn);
			 	bigDiv.appendChild(div);
	 		}


			//jquery stuff here
		 	//$("#draggable").draggable(); //. is a class
		 	$(".draggable").draggable({ containment:'window', distance:0, stack:".draggable", stop:function(event , ui){
		 		var Stoppos = $(this).position();
		 		var zIndex = $(this).zIndex();
		 		var childId = $(this).attr("id");

		 		stickyMoved(childId, Stoppos.left, Stoppos.top, zIndex);
		 	} });

		 	$("button").click(function(){
	    			stickyClosed($(this).parent().attr("id"));
			});
		}
	}
}
function stickyClosed(id){
	
	//get id of div and get parent and remove child.

	//g.container.removeChild(id);
	document.getElementById(id).remove();

	//true = synchronous
	g.req.open('POST', 'stickyServer.php', true);

	g.req.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	//g.req.onreadystatechange = handleReply;

	g.req.send('delete='+true+'&id='+id);

	//Refresh page (to avoid concurency issues).
}
function stickyMoved(childId, leftPos, topPos, zIndex) {

	g.req = new XMLHttpRequest();

	//true = synchronous
	g.req.open('POST', 'stickyServer.php', true);

	g.req.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	//g.req.onreadystatechange = handleReply;

	g.req.send('update='+true+'&id='+childId+'&left='+leftPos+'&top='+topPos+'&zindex='+zIndex);
	
}
function init()
{
	var submitButton = document.getElementById("submitBtn");
	var logoutButton = document.getElementById("logoutBtn");

	g.textArea = document.getElementById("textArea");
	g.container = document.getElementById("container");

	submitButton.addEventListener("click", submitRequest, false);
	logoutButton.addEventListener("click", logoutRequest, false);

	//--------------------------------
	g.req = new XMLHttpRequest();
	//true = synchronous
	g.req.open('POST', 'stickyServer.php', true);
	g.req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	g.req.onreadystatechange = handleReply;
	g.req.send('getStickies='+true);
}

window.onload=init;