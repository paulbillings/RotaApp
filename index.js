/**
 * This is the main class
 */
var rota = {
    initialize: function() {
        this.bindEvents();
    },
    bindEvents: function() {
				 
		function rotaApp() {
			this.have = function () {
				alert("It worked");
			}
		}
		
		this.rotaApp = new rotaApp();
    }    
};
rota.initialize();