$(document).ready(function() {
    $(".floormap").mapster({
        onClick: function(g) {
            var f = $(this).attr("data-color");
            if (f != "plan-status-2") {
                window.open(this.href, "_self")
            } else {
                return false
            }
        },
        fillOpacity: 0.8,
        onMouseover: function(g) {
            var f = $(this).attr("data-color");
            if (f == "plan-status-1") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "3a9019",
                    fillOpacity: 0.8
                })
            }
            if (f == "plan-status-2") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "ec2327",
                    fillOpacity: 0.8
                })
            }
            if (f == "plan-status-3") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "f29378",
                    fillOpacity: 0.8
                })
            }
            if (f == "plan-status-4") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "c58f59",
                fillOpacity: 0.8
                })
            }
        },
        onMouseout: function(f) {
            $(this).mapster("set", false);

			$("area[data-color='plan-status-1']").mapster("set", true, {
				fillColor: "3a9019",
				fillOpacity: 0.5
			});
            $("area[data-color='plan-status-2']").mapster("set", true, {
                fillColor: "ec2327",
                fillOpacity: 0.5
            });

            $("area[data-color='plan-status-3']").mapster("set", true, {
                fillColor: "f29378",
                fillOpacity: 0.5
            });

            $("area[data-color='plan-status-4']").mapster("set", true, {
				fillColor: "c58f59",
				fillOpacity: 0.5
			});
        }
    });

    $("area[data-color='plan-status-1']").mapster("set", true, {
        fillColor: "3a9019",
        fillOpacity: 0.5
    });

    $("area[data-color='plan-status-2']").mapster("set", true, {
        fillColor: "ec2327",
        fillOpacity: 0.5
    });

    $("area[data-color='plan-status-3']").mapster("set", true, {
        fillColor: "f29378",
        fillOpacity: 0.5
    });

    $("area[data-color='plan-status-4']").mapster("set", true, {
		fillColor: "c58f59",
		fillOpacity: 0.5
	});
});