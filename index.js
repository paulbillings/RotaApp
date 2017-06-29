$(document).ready(function(){
	$("#week_ending").datepicker({
	showButtonPanel: true,
	dateFormat: 'yy-mm-dd',
	beforeShowDay: disableDays});
});
		
function disableDays(date) {
	var day = date.getDay();
	return[(day == 6)];
}