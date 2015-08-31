app.directive('playerSelect', function($parse, PlayerSvc) {
	return {
		restrict: 'E',
		require: "?ngModel",
		replace: true,
		template: '<select ng-options="player.ID as player.Name for player in __PlayerSelect"></select>',
		link: {
			pre: function(scope, elem, attrs) {
				PlayerSvc.GetPlayers().then(
					function(response) {
						var option, playerId, modelGetter = $parse(attrs['ngModel']), modelSetter;

						// sort the players alphabetically
						scope.__PlayerSelect = response.data.sort(function(a, b) {
							if(a.Name < b.Name) return -1;
							if(b.Name < a.Name) return 1;
							return 0;
						});

						// a player must be selected, so set to first player if null
						if((playerId = modelGetter(scope)) == null) {
							modelSetter = modelGetter.assign;
							modelSetter(scope, playerId = scope.__PlayerSelect[0].ID);
						}

						// set css class to the class for the ng-model value
						for(var i = 0; i < scope.__PlayerSelect.length; ++i) {
							if(scope.__PlayerSelect[i].ID == playerId) {
								angular.element(elem[0]).addClass(ClassIdToCss(parseInt(scope.__PlayerSelect[i].ClassID)));
								break;
							}
						}
					}
				);
			},
			post: function(scope, elem, attrs) {
				// color the options once they're set
				var unwatch = scope.$watch(
					function() {
						return elem[0].childNodes.length;
					},
					function (newValue, oldValue) {
						if(oldValue != newValue) {
							var options, length;

							// called once for performance reasons
							options = elem.children();
							length = elem.children().length;

							for(var i = 0; i < length; ++i) {
								options.eq(i).addClass(ClassIdToCss(parseInt(scope.__PlayerSelect[i].ClassID)));
							}

							unwatch();
						}
					},
					true
				);

				// change the css class of the <select> when option changes
				scope.$watch(
					function() {
						return elem[0].selectedIndex;
					},
					function(newValue, oldValue) {
						if(newValue != oldValue) {
							angular.element(elem[0]).removeClass(ClassIdToCss(parseInt(scope.__PlayerSelect[oldValue].ClassID)));
							angular.element(elem[0]).addClass(ClassIdToCss(parseInt(scope.__PlayerSelect[newValue].ClassID)));
						}
					},
					true
				);
			}
		}
	};
});