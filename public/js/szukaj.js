$(document).ready(function() {
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
});