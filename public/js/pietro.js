$(document).ready(function() {
    $("#areaControl").on("change", function () {
        const f = this.options[this.selectedIndex].value;
        const e = Qurl.create();
        e.query("s_area", f);
        location.reload()
    });
    $("#roomControl").on("change", function () {
        const f = this.options[this.selectedIndex].value;
        const e = Qurl.create();
        e.query("s_room", f);
        location.reload()
    });

    $("#myimagemap").mapster({
        onClick: function() {
            const f = $(this).attr("data-color");
            if (f !== "plan-status-2") {
                window.open(this.href, "_self")
            } else {
                return false
            }
        },
        fillOpacity: 0.8,
        onMouseover: function() {
            const f = $(this).attr("data-color");
            if (f === "plan-status-1") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "25690c",
                    fillOpacity: 0.8,
                    stroke: true,
                    strokeColor: 'a78a49',
                    strokeOpacity: 1,
                    strokeWidth: 1
                })
            }
            if (f === "plan-status-2") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "aa1215",
                    fillOpacity: 0.8,
                    stroke: true,
                    strokeColor: 'a78a49',
                    strokeOpacity: 1,
                    strokeWidth: 1
                })
            }
            if (f === "plan-status-3") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "cc6e00",
                    fillOpacity: 0.8,
                    stroke: true,
                    strokeColor: 'a78a49',
                    strokeOpacity: 1,
                    strokeWidth: 1
                })
            }
            if (f === "plan-status-4") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "676767",
                    fillOpacity: 0.8,
                    stroke: true,
                    strokeColor: 'a78a49',
                    strokeOpacity: 1,
                    strokeWidth: 1
                })
            }
            if (f === "plan-status-5") {
                $(this).mapster("set", false).mapster("set", true, {
                    fillColor: "2168c1",
                    fillOpacity: 0.8,
                    stroke: true,
                    strokeColor: 'a78a49',
                    strokeOpacity: 1,
                    strokeWidth: 1
                })
            }
        },
        onMouseout: function() {
            $(this).mapster("set", false);

            $("area[data-color='plan-status-1']").mapster("set", true, {
                fillColor: "25690c",
                fillOpacity: 0.5,
                stroke: false
            });
            $("area[data-color='plan-status-2']").mapster("set", true, {
                fillColor: "aa1215",
                fillOpacity: 0.5,
                stroke: false
            });

            $("area[data-color='plan-status-3']").mapster("set", true, {
                fillColor: "cc6e00",
                fillOpacity: 0.5,
                stroke: false
            });

            $("area[data-color='plan-status-4']").mapster("set", true, {
                fillColor: "676767",
                fillOpacity: 0.5,
                stroke: false
            });

            $("area[data-color='plan-status-5']").mapster("set", true, {
                fillColor: "2168c1",
                fillOpacity: 0.5,
                stroke: false
            });
        }
    });

    $("area[data-color='plan-status-1']").mapster("set", true, {
        fillColor: "25690c",
        fillOpacity: 0.5
    });

    $("area[data-color='plan-status-2']").mapster("set", true, {
        fillColor: "aa1215",
        fillOpacity: 0.5
    });

    $("area[data-color='plan-status-3']").mapster("set", true, {
        fillColor: "cc6e00",
        fillOpacity: 0.5
    });

    $("area[data-color='plan-status-4']").mapster("set", true, {
        fillColor: "676767",
        fillOpacity: 0.5
    });

    $("area[data-color='plan-status-5']").mapster("set", true, {
        fillColor: "2168c1",
        fillOpacity: 0.5
    });
});