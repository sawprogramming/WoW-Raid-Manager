app.factory('RealmSvc', function($http) {
	return {
		GetRealms: function(region) {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_realm',
					'region': region
				}
			});
		}
	};
});