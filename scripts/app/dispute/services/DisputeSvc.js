app.factory("DisputeSvc", function($http) {
	return {
		AddRecord: function(entity) {
			return $http({
				method: 'POST',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_dispute',
					'entity': JSON.stringify(entity)
				}
			});
		},
		GetAll: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_dispute',
					'func': 'all'
				}
			});
		},
		GetResolved: function(id) {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_dispute',
					'func': 'resolved',
					'id': id
				}
			});
		},
		GetUnresolved: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_dispute',
					'func': 'unresolved'
				}
			});
		},
		UpdateRecord: function(entity) {
			return $http({
				method: 'PUT',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_dispute',
					'entity': JSON.stringify(entity)
				}
			});
		}
	};
});