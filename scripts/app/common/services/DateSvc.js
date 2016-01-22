app.factory('DateSvc', function($http) {
	return {
		toJavaScriptDate: function(dateString) {
			var date = null;
			if(dateString != null) {
				date = new Date(dateString);
				date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
			}
			return date;
		}
	}
});