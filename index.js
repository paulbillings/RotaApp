/**
 * This is the main class
 */
var rota = {
    initialize: function() {
        this.bindEvents();
    },
    bindEvents: function() {
		
		$(document).ready(function(){
			alert('Hello world');
			$("#week_ending").datepicker();
		});	 
		
		this.rotaApp = new rotaApp();
    } 

	
};
rota.initialize();