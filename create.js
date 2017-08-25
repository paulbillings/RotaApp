$(document).ready(function(){
	
    formReady();
	
	$('.timepicker').timepicker({
		timeFormat: 'HH.mm',
		minTime: '1.00',
		maxTime: '23.00',
		startTime: '4.00',
		dynamic: false,
		dropdown: false,
		scrollbar: false
	});
	
	$('.week_ending').change(function(){
		$('.week_ending').datepicker('setDate', $(this).val());
	});
	
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
	
	$(".week_ending").datepicker({
	showButtonPanel: true,
	inline: true,
	dateFormat: 'yy-mm-dd',
	beforeShowDay: disableDays}).on('keypress', function(e){ e.preventDefault(); });
	
	$(".week_ending").datepicker().datepicker("setDate", displayDate);
	
}







