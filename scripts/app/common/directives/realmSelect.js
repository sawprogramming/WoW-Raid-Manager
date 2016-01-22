app.directive('realmSelect', function(RealmSvc) {
	return {
		restrict: 'E',
		require: "?ngModel",
		scope: {
			region: "="
		},
		replace: true,
		template: '<select ng-options="realm.Slug as realm.Name for realm in __RealmList"></select>',
		link: function(scope, elem, attrs, ctrl) {
			scope.$watch(
				'region',
				function(newValue, oldValue) {
					if(newValue != oldValue) {
						RealmSvc.GetRealms(newValue)
							.success(function(data) {
								scope.__RealmList = data;

								if(oldValue !== undefined) {
									ctrl.$setViewValue(data[0].Slug);
								}
							});
					}
				},
				true
			);
		}
	};
});