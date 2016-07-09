refresh();
		
function refresh()
{
	
	$.ajax({
		type: "GET",
		url: "table2.php",
		error: function(err) {
			console.log(err);
		},
		success: function(result) {
			$("span.t1").html(result);
		}
	});
	$.ajax({
		type: "GET",
		url: "msg.php",
		error: function(err) {
			console.log(err);
		},
		success: function(result) {
			$("span.msg").html(result);
		}
	});
	$.ajax({
		type: "GET",
		url: "prog2.php",
		error: function(err) {
			console.log(err);
		},
		success: function(result) {
			$("div.prog").html(result);
		}
	});
	
	$.ajax({
		type: "GET",
		url: "tl2.php",
		error: function(err) {
			console.log(err);
		},
		success: function(result) {
			$("span.tl1").html(result);
		}
	});
	
	$.ajax({
		type: "GET",
		url: "pot2.php",
		error: function(err) {
			console.log(err);
		},
		success: function(result) {
			$("span.pot1").html(result);
		}
	});
	
	$.ajax({
		type: "GET",
		url: "hash2.php",
		error: function(err) {
			console.log(err);
		},
		success: function(result) {
			$("span.hash1").html(result);
		}
	});
	
	
	
	setTimeout(refresh, 1000);
}