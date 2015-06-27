app.factory('PlayerSvc', function($http) {
	return {
		AddPlayer: function(obj) {
			return $http({
				method: 'POST',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_player',
					'entity': JSON.stringify(obj)
				}
			});
		},
		DeletePlayer: function(id) {
			return $http({
				method: 'DELETE',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_player',
					'id': id
				}
			});
		},
		EditPlayer: function(entity) {
			return $http({
				method: 'PUT',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_player',
					'entity': JSON.stringify(entity)
				}
			})
		},
		GetPlayers: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_player'
				}
			});
		}
	};
});