app.factory('UserSvc', function($http) {
	return {
		GetUsers: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_user'
				}
			});
		}
	};
});