app.factory('PlayerSvc', function($http) {
	return {
		AddPlayer: function(obj) {
			return $http({
				method: 'PUT',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_player',
					'name': obj.Name,
					'classId': obj.ClassID
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