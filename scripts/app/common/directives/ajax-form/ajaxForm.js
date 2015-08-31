app.directive('ajaxForm', function() {
	return {
		restrict: 'E',
		scope: {
			status: '='
		},
		replace: true,
		transclude: true,
		templateUrl: plugin_url.app + '/common/directives/ajax-form/ajaxForm.html',
		link: function(scope, elem, attrs) {
			scope.__images = {
				ajaxLoaderLg: plugin_url.images + '/ajax-loader-lg.gif'
			};
		}
	};
});