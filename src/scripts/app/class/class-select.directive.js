(function () {
    'use strict';

    angular
        .module('WRO')
        .directive('classSelect', ClassSelect);

    ClassSelect.$inject = ['$parse', 'ClassSvc'];

    function ClassSelect($parse, ClassSvc) {
        return {
            restrict : 'E',
            require  : "?ngModel",
            replace  : true,
            template : '<select ng-options="class.ID as class.Name for class in __ClassSelect"></select>',
            link: {
                pre  : PreLink,
                post : PostLink
            }
        };

        ///////////////////////////////////////////////////////////////////////
        function PreLink(scope, elem, attrs) {
            var option, modelGetter = $parse(attrs['ngModel']), modelSetter;

            ClassSvc.GetClasses().then(
                function (response) {
                    scope.__ClassSelect = response.data;

                    // a player must have a class, so set class to first class if null
                    if (modelGetter(scope) == null) {
                        modelSetter = modelGetter.assign;
                        modelSetter(scope, scope.__ClassSelect[0].ID);
                    }

                    // set css class to the class for the ng-model value
                    angular.element(elem[0]).addClass(ClassIdToCss(parseInt(modelGetter(scope))));
                }
            );
        }

        function PostLink(scope, elem, attrs) {
            // color the options once they're set
            var unwatch = scope.$watch(
                function () {
                    return elem[0].childNodes.length;
                },
                function (newValue, oldValue) {
                    if (scope.__ClassSelect != null && newValue == scope.__ClassSelect.length) {
                        angular.forEach(scope.__ClassSelect, function (item, index) {
                            var option = elem.find('option[label="' + item.Name + '"]');
                            angular.element(option).addClass(ClassIdToCss(parseInt(item.ID)));
                        });

                        unwatch();
                    }
                },
                true
            );

            // change the css class of the <select> when option changes
            scope.$watch(
                function () {
                    return elem[0].selectedIndex;
                },
                function (newValue, oldValue) {
                    if (newValue != oldValue) {
                        angular.element(elem[0]).removeClass(ClassIdToCss(parseInt(scope.__ClassSelect[oldValue].ID)));
                        angular.element(elem[0]).addClass(ClassIdToCss(parseInt(scope.__ClassSelect[newValue].ID)));
                    }
                },
                true
            );
        }
    }
})();