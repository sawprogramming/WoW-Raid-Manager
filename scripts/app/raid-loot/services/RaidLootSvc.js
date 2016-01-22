app.factory('RaidLootSvc', function($http) {
	return {
		GetAll: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_raidloot',
					'func': 'all'
				}
			});
		},
		GetInRange: function(startDate, endDate) {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_raidloot',
					'func': 'range',
					'startDate': startDate,
					'endDate': endDate
				}
			});
		},
		Delete: function(id) {
			return $http({
				method: 'DELETE',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_raidloot',
					'id': id
				}
			});
		}
	};
});