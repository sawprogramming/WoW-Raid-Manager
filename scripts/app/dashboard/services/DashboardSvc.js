app.factory('DashboardSvc', function($http) {
	return {
		DownloadBackup: function() {
			return $http({
				method: 'GET',
				url: ajax_object.ajax_url,
				responseType: 'arraybuffer',
				cache: false,
				params: {
					'action': 'wro_backup_dl'
				}
			});
		}
	};
});