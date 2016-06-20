function startDatepicker() {
	$('.datepicker').datepicker({
		format: 'dd.mm.yyyy.',
		startDate: 0,
		todayHighlight: true,
		startView: 0,
		orientation: "bottom auto",
		setDate: new Date()
	});
}

jQuery.fn.exists = function() {
	return this.length > 0;
}