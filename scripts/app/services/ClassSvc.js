app.factory('ClassSvc', function($http) {
	return {
		GetClasses: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_class'
				}
			});
		}
	};
});