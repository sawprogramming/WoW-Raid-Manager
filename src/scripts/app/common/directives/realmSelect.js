(function () {
    'use strict';

    angular
        .module('WRO')
        .directive('realmSelect', RealmSelect);

    RealmSelect.$inject = ['RealmSvc'];

    function RealmSelect(RealmSvc) {
        return {
            restrict : 'E',
            require  : "?ngModel",
            scope    : {
                region: "="
            },
            replace  : true,
            template : '<select ng-options="realm.Slug as realm.Name for realm in __RealmList"><option value=\'\'>-- Choose a Realm -- </option></select>',
            link     : Link
        };

        ///////////////////////////////////////////////////////////////////////
        function Link(scope, elem, attrs, ctrl) {
            if (scope.region !== undefined) {
                RealmSvc.GetRealms(scope.region).then(
                    function success(response) {
                        scope.__RealmList = response.data;
                    }
                );
            }

            scope.$watch(
                'region',
                function (newValue, oldValue) {
                    if (newValue != oldValue) {
                        RealmSvc.GetRealms(newValue).then(
                            function success(response) {
                                scope.__RealmList = response.data;

                                if (oldValue !== undefined) {
                                    ctrl.$setViewValue('');
                                }
                            }
                        );
                    }
                },
                true
            );
        }
    }
})();