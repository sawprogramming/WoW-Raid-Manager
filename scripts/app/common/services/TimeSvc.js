app.factory('TimeSvc', function($http) {
	return {
		toJavaScriptTime: function(time) {
			return time * 1000;
		},
		toPhpTime: function(time) {
			return time / 1000;
		}
	}
});