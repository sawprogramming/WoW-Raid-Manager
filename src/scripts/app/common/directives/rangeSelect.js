(function () {
    'use strict';

    angular
        .module('WRO')
        .directive('rangeSelect', RangeSelect);

    RangeSelect.$inject = ['RaidTierSvc', 'ExpansionSvc', 'DateSvc'];

    function RangeSelect(RaidTierSvc, ExpansionSvc, DateSvc) {
        return {
            restrict : 'E',
            require  : "?ngModel",
            replace  : true,
            template : '<select ng-options="tier as tier.Name group by tier.ExpansionName for tier in __RaidTiers"></select>',
            link     : Link
        };

        ///////////////////////////////////////////////////////////////////////
        function Link(scope, elem, attrs, ctrl) {
            RaidTierSvc.GetAll().then(
                function (response) {
                    var tiers = response.data
                    ExpansionSvc.GetAll().then(
                        function success(response) {
                            var expansions  = response.data;
                            var now         = new Date();
                            var currentTier = 0;

                            for (var i = 0; i < tiers.length; ++i) {
                                tiers[i].StartDate     = DateSvc.toJavaScriptDate(tiers[i].StartDate);
                                tiers[i].EndDate       = DateSvc.toJavaScriptDate(tiers[i].EndDate);
                                tiers[i].ExpansionName = expansions[tiers[i].ExpansionID - 1].Name;

                                if (now >= tiers[i].StartDate && (now <= tiers[i].EndDate || tiers[i].EndDate == null)) {
                                    currentTier = i;
                                }
                            }

                            scope.__RaidTiers = tiers;                           
                            ctrl.$setViewValue(tiers[currentTier]);
                        }
                    );
                }
            );
        }
    }
})();