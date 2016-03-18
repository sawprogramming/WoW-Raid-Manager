(function () {
    'use strict';

    angular
        .module('WRO')
        .directive('userSelect', UserSelect);

    UserSelect.$inject = ['$parse', 'UserSvc'];

    function UserSelect($parse, UserSvc) {
        return {
            restrict : 'E',
            require  : "?ngModel",
            replace  : true,
            template : '<select ng-options="user.ID as user.Username for user in __UserSelect"><option value=\'\'>-- No Username -- </option></select>',
            link     : Link
        };

        ///////////////////////////////////////////////////////////////////////
        function Link(scope, elem, attrs) {
            UserSvc.GetUsers().then(
                function (response) {
                    // sort the users alphabetically
                    scope.__UserSelect = response.data.sort(function(a, b) {
                        if      (a.Username < b.Username) return -1;
                        else if (b.Username < a.Username) return  1;
                        else                              return  0;
                    });
                }
            );
        }
    }
})();