var numTraits;

$(document).ready( function() {
	numTraits = parseInt($("#reorder").attr("num"));
	
	$(".hireable").click(function() {
		var setting = $(this).val();
		if(setting == 'n') {
			$(".hire").attr('disabled', 'disabled');
		} else {
			$(".hire").attr('disabled', '');
		}
	});
	
	$(".breedable").click(function() {
		var setting = $(this).val();
		if(setting == 'n') {
			$(".breed").attr('disabled', 'disabled');
		} else {
			$(".breed").attr('disabled', '');
		}
	});
	
	$(".moveArrowUp").click(function() {
		var move = parseInt($(this).attr("num"));
		var trait = $("#trait"+move).val();
		var nameHTML = $("#trait"+move+"name").html();
		var colorHTML =  $("#trait"+move+"color").html();
		var boxHTML = $("#trait"+move+"box").html();
		
		if(move > 0 && move < numTraits) {
			var other = move - 1;
			var trait2 = $("#trait"+other).val();
			$("#trait"+move+"name").html($("#trait"+other+"name").html());
			$("#trait"+move+"color").html($("#trait"+other+"color").html());
			$("#trait"+move+"box").html($("#trait"+other+"box").html());
			$("#trait"+other+"name").html(nameHTML);
			$("#trait"+other+"color").html(colorHTML);
			$("#trait"+other+"box").html(boxHTML);
			$("#trait"+other).val(trait);
			$("#trait"+move).val(trait2);
		}
		return false;			   
	});
	
	$(".moveArrowDown").click(function() {
		var move = parseInt($(this).attr("num"));
		var trait = $("#trait"+move).val();
		var nameHTML = $("#trait"+move+"name").html();
		var colorHTML =  $("#trait"+move+"color").html();
		var boxHTML = $("#trait"+move+"box").html();
		
		if(move >= 0 && move < numTraits - 1) {
			var other = move + 1;
			var trait2 = $("#trait"+other).val();
			$("#trait"+move+"name").html($("#trait"+other+"name").html());
			$("#trait"+move+"color").html($("#trait"+other+"color").html());
			$("#trait"+move+"box").html($("#trait"+other+"box").html());
			$("#trait"+other+"name").html(nameHTML);
			$("#trait"+other+"color").html(colorHTML);
			$("#trait"+other+"box").html(boxHTML);
			$("#trait"+other).val(trait);
			$("#trait"+move).val(trait2);
		}
		return false;				   
	});
});