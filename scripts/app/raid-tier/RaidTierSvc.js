app.factory('RaidTierSvc', function($http) {
	return {
		GetAll: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_raidtier',
				}
			});
		}
	};
});