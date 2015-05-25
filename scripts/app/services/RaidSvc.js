app.factory('RaidSvc', function($http) {
	return {
		AddRaid: function(name) {
			return $http({
				method: 'PUT',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_raid',
					'name': name
				}
			});
		},
		DeleteRaid: function(id) {
			return $http({
				method: 'DELETE',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_raid',
					'id': id
				}
			});
		},
		GetRaids: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_raid'
				}
			});
		}
	};
});