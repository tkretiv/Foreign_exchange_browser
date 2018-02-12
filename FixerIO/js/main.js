$(function() {

	$("#date").val(getToday());
	 
	$("#submit").click(function() {
		$("#Rates").append("<h4>Wait please it could take some seconds..</h4>");
		alert($("#date").val());
     	$.get('php/rateChange.php?dateRequest='+$("#date").val()+'&clientRequest=1', function(response) {
			//alert(response);
			var html='<table id="idTab"><thead><tr><th> Currency</th><th class="sorted desc" onclick="sort(1)">  Rate change </th></tr></thead>';
			var obj = JSON.parse(response);
    		for (var key in obj) {
    			var value = obj[key];
				html+='<tr><td>'+key+'</td><td>'+obj[key]+'</td></tr>';
			}

			html+='</table>';
			//console.log(html);
			$("#Rates").empty();
			$("#Rates").append(html);
		});
	});

});

function getToday(){
	  var now = new Date();
    var month = (now.getMonth() + 1);               
    var day = now.getDate();
    if (month < 10) 
        month = "0" + month;
    if (day < 10) 
        day = "0" + day;
    var today = now.getFullYear() + '-' + month + '-' + day;
    return today;
}

function sort(n) {
	
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("idTab");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
      	//spanHtml = "asc";
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
      	//spanHtml="desc";
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++; 
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }

  }
 
  if (n=1){
  	 headers = table.getElementsByTagName("TH");
   if (headers[n].className.indexOf("sorted")>-1)
    {
    	if(headers[n].className.indexOf(dir)>-1){
      		headers[n].className = "";
      	}
      	else headers[n].className = "sorted "+dir;
    }
    else {
      headers[n].className = "sorted "+dir;
    }
}
}
