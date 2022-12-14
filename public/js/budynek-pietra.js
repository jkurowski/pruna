$(document).ready(function(){
	$('#myimagemap').mapster({
		onClick: function(g) {
			if (this.href !== "#") {
				window.open(this.href, "_self")
			} else {
				return false
			}
		},
		fillOpacity: 0.8,
		onMouseover: function() {
			console.log('onMouseover');
			$(this).mapster("set", false);
			$(this).mapster("set", true, {
				fillColor: '3b3b3b',
				fillOpacity: 0,
				stroke: true,
				strokeColor: 'a78a49',
				strokeOpacity: 1,
				strokeWidth: 2,
			});
		},
		onMouseout: function(f) {
			console.log('onMouseout');
			$(this).mapster("set", false);
			$(this).mapster("set", true, {
				fillColor: '3b3b3b',
				fillOpacity: 0.7,
				stroke: true,
				strokeColor: 'a78a49',
				strokeOpacity: 1,
				strokeWidth: 2,
			});
		}
	});

	$("area").mapster("set", true, {
		fillColor: '3b3b3b',
		fillOpacity: 0.7,
		stroke: true,
		strokeColor: 'a78a49',
		strokeOpacity: 1,
		strokeWidth: 2,
	});
});