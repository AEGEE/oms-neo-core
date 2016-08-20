$(function() {
	setInterval(noSessionTimeout, 120000); // We execute once every 2 minutes..
});

function noSessionTimeout() {
	$.ajax({
		url: 'noSessionTimeout',
		type: 'GET',
		async: true,
		success: function(response) {
			if(response != 1) {
				alert('Session timed out! Please login again!');
				location.reload();
			}
		}, 
		error: function(response) {
			alert('Session timed out! Please login again!');
			location.reload();
		}
	});
}
