app.filter('inactivePlayers', function() {
	return function(players, includeInactivePlayers) {
		var filtered = [];

		for(var i = 0; i < players.length; ++i) {
			if(players[i].Active)           filtered.push(players[i]);
			else if(includeInactivePlayers) filtered.push(players[i]);
		}

		return filtered;
	};
});