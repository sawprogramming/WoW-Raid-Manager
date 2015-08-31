app.directive('ajaxContent', function() {
	return {
		restrict: 'E',
		scope: {
			content: '=status',
			src: '='
		},
		replace: true,
		transclude: true,
		templateUrl: plugin_url.app + '/common/directives/ajax-content/ajaxContent.html',
		link: function(scope, elem, attrs) {
			scope.__images = {
				ajaxLoaderLg: plugin_url.images + '/ajax-loader-lg.gif'
			};
		}
	};
});