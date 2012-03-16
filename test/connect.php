<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js" type="text/javascript"></script>
<script src="https://api.trello.com/1/client.js?key=86d92dd4ed3335f15c1625c87c490fd3" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
var onAuthorize = function()
{
	console.log('hey');
	updateLoggedIn();
	$("#output").empty();

	Trello.members.get("me", function(member)
	{
		$("#fullName").text(member.fullName);

		var $cards = $("<div>")
			.text("Loading Cards...")
			.appendTo("#output");
		
		// Output a list of all of the cards that the member 
		// is assigned to
		Trello.get("members/me/cards", function(cards)
		{
			$cards.empty();
			$.each(cards, function(ix, card)
			{
				$("<a>")
					.attr({href: card.url, target: "trello"})
					.addClass("card")
					.text(card.name)
					.appendTo($cards);
			});  
		});
	});
};

var updateLoggedIn = function()
{
	var isLoggedIn = Trello.authorized();
	$("#loggedout").toggle(!isLoggedIn);
	$("#loggedin").toggle(isLoggedIn);        
};
    
var logout = function()
{
	Trello.deauthorize();
	updateLoggedIn();
};
                        
Trello.authorize(
{
	interactive: false,
	success: onAuthorize
});

$("#connectLink").click(function()
{
	console.log('hey');
	alert("hey");
	Trello.authorize(
	{
		type: "popup",
		success: onAuthorize
	})
});

$("#disconnect").click(logout);
});
</script>
<style type="text/css">
body {
    font-family: arial;
    font-size: 12px;
}

#loggedout {
    text-align: center;
    font-size: 20px;
    padding-top: 30px;
}
#loggedin { 
    display: none;
}

#header {
    padding: 4px;
    border-bottom: 1px solid #000;
    background: #eee;
}

#output {
    padding: 4px;
}

.card { 
    display: block;
    padding: 2px;
}
</style>
</head>
<body>
<div id="loggedout">
	<a id="connectLink" href="#">Connect To Trello</a>
</div>
<div id="loggedin">
	<div id="header">
		Logged in to as <span id="fullName"></span> 
		<a id="disconnect" href="#">Log Out</a>
	</div>
	<div id="output"></div>
</div>
</body>
</html>
