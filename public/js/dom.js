var Qurl;(function(){var c;Qurl=function(){if(!(this instanceof Qurl)){return new Qurl()}};c=Qurl.prototype;c.query=function(f,g){if(!f){return e()}if(f&&typeof(g)==="undefined"){return e()[f]}if(f&&typeof(g)!=="undefined"){return b(f,g)}};function e(){var h=window.location.search,f=[],g={};if(!h){return g}h=h.replace("?","");f=h.split("&");f.forEach(function(i){var k=decodeURIComponent(i).split("="),l=k[0],j=k[1];g[l]=j});return g}function b(f,g){var i=e(),h=window.location.search;if(g===false){delete i[f]}else{i[f]=g}a(d(i));return i}function a(f){if(history.pushState){history.pushState({},document.title,f)}}function d(g){var f=[];Object.keys(g).forEach(function(h){if(typeof g[h]==="undefined"){f.push(h)}else{f.push(encodeURIComponent(h)+"="+encodeURIComponent(g[h]||""))}});if(f.length===0){return"?"}else{return"?"+f.join("&")}}Qurl.create=Qurl}());

$(document).ready(function() {
    var d = 0;
    var b = 100;
    $("#areaControl").on("change", function() {
        var f = this.options[this.selectedIndex].value;
        var e = Qurl.create();
        e.query("s_area", f);
		window.location.hash = "#szukaj-pietro";
        location.reload()
    });
    $("#roomControl").on("change", function() {
        var f = this.options[this.selectedIndex].value;
        var e = Qurl.create();
        e.query("s_room", f);
		window.location.hash = "#szukaj-pietro";
        location.reload()
    });
    $("#statusControl").on("change", function() {
        var f = this.options[this.selectedIndex].value;
        var e = Qurl.create();
        e.query("status", f);
		window.location.hash = "#szukaj-pietro";
        location.reload()
    });
    $("#myimagemap").mapster({
        onClick: function(g) {
            var f = $(this).attr("data-color");
            if (f != "red") {
                window.open(this.href, "_self")
            } else {
                return false
            }
        },
        fillOpacity: 0.8,
        onMouseover: function(g) {
            var f = $(this).attr("data-color");
            if (f == "red") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "ec2327"
                })
            }
            if (f == "blue") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "1788c9"
                })
            }
            if (f == "green") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "3a9019"
                })
            }
            if (f == "orange") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "de8300"
                })
            }
        },
        onMouseout: function(f) {
            $(this).mapster("set", false);
            // $("area[data-color='red']").mapster("set", true, {
                // fillColor: "ec2327",
                // fillOpacity: 0.8
            // });
	
			// $("area[data-color='blue']").mapster("set", true, {
				// fillColor: "1788c9",
				// fillOpacity: 0.8
			// });
			
			// $("area[data-color='green']").mapster("set", true, {
				// fillColor: "3a9019",
				// fillOpacity: 0.8
			// });
			
			// $("area[data-color='orange']").mapster("set", true, {
				// fillColor: "de8300",
				// fillOpacity: 0.8
			// });
        }
    });

    // $("area[data-color='red']").mapster("set", true, {
        // fillColor: "ec2327",
        // fillOpacity: 0.8
    // });
	
    // $("area[data-color='blue']").mapster("set", true, {
        // fillColor: "1788c9",
        // fillOpacity: 0.8
    // });
	
    // $("area[data-color='green']").mapster("set", true, {
        // fillColor: "3a9019",
        // fillOpacity: 0.8
    // });
			
	// $("area[data-color='orange']").mapster("set", true, {
		// fillColor: "de8300",
		// fillOpacity: 0.8
	// });
	
    function a(j, i) {
        var k = $("img#myimagemap"),
            g = k.width(),
            e = k.height(),
            h = 0,
            f = 0;
        if (g / j > e / i) {
            h = j
        } else {
            f = i
        }
        k.mapster("resize", h, f, d)
    }

    function c() {
        var f = $(".floor").width(),
            g = $(".floor").height(),
            e = false;
        if (e) {
            return
        }
        e = true;
        window.setTimeout(function() {
            var i = $(".floor").width(),
                h = $(".floor").height();
            if (i === f && h === g) {
                a(i, h)
            }
            e = false
        }, b)
    }
    $(window).bind("resize", c)
	
	$('#grid').click(function() {
		$('#offerList').fadeOut(300, function() {
			$(this).addClass('grid').removeClass('list').fadeIn(300);
			$.cookie('list_grid', 'g');
		});
		return false;
	});
	
	$('#list').click(function() {
		$('#offerList').fadeOut(300, function() {
			$(this).removeClass('grid').addClass('list').fadeIn(300);
			$.cookie('list_grid', 'l');
		});
		return false;
	});
	
});

$(window).load(function() {
	$(".floorload").fadeOut();
});