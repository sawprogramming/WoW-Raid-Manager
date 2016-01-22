app.factory('OptionSvc', function($http) {
	return {
		GetOption: function(key) {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_option',
					'key': key
				}
			});
		},
		GetOptions: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_option'
				}
			});
		},
		UpdateOptions: function(pairs) {
			return $http({
				method: 'PUT',
				url: ajax_object.ajax_url,
				params: {
					'action': 'wro_option',
					'pairs': JSON.stringify(pairs)
				}
			});
		}
	};
});