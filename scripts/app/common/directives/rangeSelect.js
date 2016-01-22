app.directive('rangeSelect', function(RaidTierSvc, ExpansionSvc, DateSvc) {
	return {
		restrict: 'E',
		require: "?ngModel",
		replace: true,
		template: '<select ng-options="tier as tier.Name group by tier.ExpansionName for tier in __RaidTiers"></select>',
		link: function(scope, elem, attrs, ctrl) {
			RaidTierSvc.GetAll().then(
				function(response) {
					var tiers = response.data
					ExpansionSvc.GetAll().then(
						function(response) {
							var expansions = response.data;


							for(var i = 0; i < tiers.length; ++i) {
								tiers[i].StartDate = DateSvc.toJavaScriptDate(tiers[i].StartDate);
								tiers[i].EndDate = DateSvc.toJavaScriptDate(tiers[i].EndDate);
								tiers[i].ExpansionName = expansions[tiers[i].ExpansionID - 1].Name;
							}

							scope.__RaidTiers = tiers;
							ctrl.$setViewValue(tiers[tiers.length - 1]);
						}
					);
				}
			);
		}
	};
});