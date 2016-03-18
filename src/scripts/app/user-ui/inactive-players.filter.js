(function () {
    'use strict';

    angular
        .module('WRO')
        .filter('inactivePlayers', FilterInactivePlayers);

    function FilterInactivePlayers() {
        return Filter;
        
        ///////////////////////////////////////////////////////////////////////
        function Filter(players, includeInactivePlayers) {
            var filtered = [];

            for (var i = 0; i < players.length; ++i) {
                if(players[i].Active || includeInactivePlayers) filtered.push(players[i]);
            }

            return filtered;
        }
    }
})();