$(document).ready( function() {
	$(".hireable").click(function() {
		var setting = $(this).val();
		$(".hire_extra").removeClass("hidden");
		if(setting == 'n') {
			$(".hire_extra").addClass("hidden");
		}
	});
	
	$(".breedable").click(function() {
		var setting = $(this).val();
		$(".breed_extra").removeClass("hidden");
		if(setting == 'n') {
			$(".breed_extra").addClass("hidden");
		}
	});
});