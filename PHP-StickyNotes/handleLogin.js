//Missing Comments sorry!
//Missing JS validation for text fields and messages to inform user(red error text).
var g = {};


function loginOrRegister(e)
{
	var evt = e || window.event;
	var target = evt.target || evt.srcElement; 

	g.req = new XMLHttpRequest();
	
	//true = synchronous
	g.req.open('POST', 'stickyServer.php', true);
	g.req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	g.req.onreadystatechange = handleReply;

	if (target.id == "loginBtn")
	{

		g.req.send('login='+true+'&email='+g.emailBox.value+'&password='+g.passwordBox.value);
	}
	
	else if (target.id == "registerBtn")
	{
		g.req.send('register='+true+'&email='+g.emailBox.value+'&password='+g.passwordBox.value);
	}
}

function handleReply()
{
	if((g.req.readyState==4)&&(g.req.status == 200)) 
	{
		var response = g.req.responseText;
	 	g.jobj = JSON.parse(response);

	 	if (g.jobj == "match")
	 	{
	 		window.location.replace("./index.html");
	 	}
	 	else if (g.jobj == "not match")
	 		alert("Username/Password not correct!");
	 	else if (g.jobj == "1")
	 		alert("Username already taken!");

	}
}

function init()
{

	var loginButton = document.getElementById("loginBtn");
	var registerButton = document.getElementById("registerBtn");

	g.emailBox = document.getElementById("email");
	g.passwordBox = document.getElementById("password");

	loginButton.addEventListener("click", loginOrRegister, false);
	registerButton.addEventListener("click", loginOrRegister, false);
}

window.onload=init;