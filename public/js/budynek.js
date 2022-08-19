$(document).ready(function(){

	$("#plans").slick({
		infinite: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		dots: false,
		swipe: false,
	});

	$(".plan-nav a").click(function(b){
		const f = $(this).attr("data-floornumber");
		$(".plan-nav a").removeClass('bttn-active');
		$(this).addClass('bttn-active');
		$('#plans').slick('slickGoTo', f);

		$('table tbody tr').hide();
		if(f > 0) {
			$('table tbody tr[data-floor="' + f + '"]').show();
		} else {
			$('table tbody tr').show();
		}
		return false
	});

	$("#buildingmap").mapster({
		onClick: function(g) {
			const f = $(this).attr("data-floornumber");
			const floor_number = parseInt(f) + parseInt(1);

			$('#plans').slick('slickGoTo', floor_number);
			$(".plan-nav a").removeClass('bttn-active');
			$('.plan-nav a[data-floornumber="' + floor_number.toString() + '"]').addClass('bttn-active');

			$('table tbody tr').hide();
			$('table tbody tr[data-floor="' + floor_number.toString() + '"]').show();

			return false
		},
		fillOpacity: 0.8,
		onMouseover: function() {
			const f = $(this).attr("data-color");
			if (f === "plan-status-1") {
				$(this).mapster("set", false).mapster("set", true, {
					fillColor: "3a9019",
					fillOpacity: 0.8
				})
			}
			if (f === "plan-status-2") {
				$(this).mapster("set", false).mapster("set", true, {
					fillColor: "ec2327",
					fillOpacity: 0.8
				})
			}
			if (f === "plan-status-3") {
				$(this).mapster("set", false).mapster("set", true, {
					fillColor: "f29378",
					fillOpacity: 0.8
				})
			}
			if (f === "plan-status-4") {
				$(this).mapster("set", false).mapster("set", true, {
					fillColor: "c58f59",
					fillOpacity: 0.8
				})
			}
		},
		onMouseout: function() {
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