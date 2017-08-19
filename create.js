$(document).ready(function(){
	
    formReady();
	
});
		
function disableDays(date) {
	var day = date.getDay();
	return[(day == 6)];
}

function formReady() {
	var now = new Date();
    var day = now.getDay();
    var daysToSaturday;
	var displayDate;
	
    if (day == 6) {
        daysToSaturday = 0;
    } else {
        daysToSaturday = 6 - day;
    }
	
    var daysToNextSaturday = daysToSaturday + 7;
	if (daysToSaturday == 0) {
		//display next weeks rota
		displayDate = daysToNextSaturday;
	} else {
		//display this weeks rota
		displayDate = daysToSaturday;
	}
	//alert(displayDate);
	
	$(".datepicker").datepicker({
	showButtonPanel: true,
	dateFormat: 'yy-mm-dd',
	beforeShowDay: disableDays}).on('keypress', function(e){ e.preventDefault(); });
	
	$(".datepicker").datepicker().datepicker("setDate", displayDate);
	
}





