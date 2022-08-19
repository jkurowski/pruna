$(document).ready(function(){
	$("#areaControl").on("change", function () {
		var f = this.options[this.selectedIndex].value;
		var e = Qurl.create();
		e.query("s_area", f);
		location.reload()
	});
	
	$("#roomControl").on("change", function () {
		var f = this.options[this.selectedIndex].value;
		var e = Qurl.create();
		e.query("s_room", f);
		location.reload()
	});
	
	$("#statusControl").on("change", function () {
		var f = this.options[this.selectedIndex].value;
		var e = Qurl.create();
		e.query("s_status", f);
		location.reload()
	});

    $(".floor-list a").hover(function() {
        var e = $(this).attr("data-tag");
		$("area[alt='"+ e +"']").mapster("set", true, {
			fillColor: "f7b392",
			fillOpacity: 0.8
		})
    }, function() {
        $("area").mapster("set", false);
    });

	$('#myimagemap').mapster({
		fillColor: 'f7b392',
		fillOpacity: 0.8,
		clickNavigate: true,
		onMouseover: function(g) {
			var f = $(this).attr("alt");
			$(".floor-list a[data-tag='"+ f +"']").addClass('hoverlist');
		},
		onMouseout: function(f) {
			$(".floor-list a").removeClass('hoverlist');
		}
	});
});