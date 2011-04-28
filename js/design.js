var numShown = 1;
var traitMenu = "";
var colorShown = false;
var colors = { 
"pink":"FFBBBB","red":"BB0000","orange":"FF8800","yellow":"FFFF00","green":"009900",
"teal":"33BBBB","darkblue":"000080","blue":"0000BB","lightblue":"8888ff","purple":"DD99FF",
"gray":"999999","grey":"999999","brown":"886633","black":"000000","white":"FFFFFF" };

$(document).ready( function() {
	//Set variables
	numShown = document.design_form.numTraits.value;
	traitMenu = $(".traitDropdown:first").html();
	if(traitMenu != null) { traitMenu = (traitMenu.replace(" selected","")).replace('value="none"','value="none" selected'); }
	
	//Bind click events
	bindClick();
	bindX();
	bindPicker("base");
	bindPicker("eye");
	bindPicker("feetear");
	for(i = 1; i <= numShown; i++) {
		bindPicker("trait" + i);
	}
	
	document.design_form.onsubmit = function(e) {
		for(var i = 1; i <= numShown; i++) {
			if(i > 1 && $(":input[name=trait"+i+"]").val() == "none") {
				removeRow($("#traitRow" + i + " .removeTraitRow"));
				i--;
			}
		}
		if($(":input[name=trait1]").val() == "none") { 
			if(numShown > 1) {
				removeRow($("#traitRow1 .removeTraitRow"));
			} else {				
				numShown --; 
			}
		}
		document.design_form.numTraits.value = numShown;
		document.design_form.submit();
	};
	
	$("#showColorList").click(function() { showColorList(this); return false; });
});
function showColorList(clicked) {
	if(colorShown == false) {
		var pos = $(clicked).offset();
		//var holder = $("#content").offset();
		var top = pos.top + 20;// - holder.top + 20;
		var left = pos.left;// - holder.left;
		$('#listHolder').css({left: left + 'px', top: top + 'px', position: 'absolute'});
		$('#listHolder').removeClass('hidden');
		clicked.innerHTML = "Hide list";
		colorShown = true;
	} else {
		colorShown = false;
		$('#listHolder').addClass('hidden');
		clicked.innerHTML = "Full list";
	}
};

function bindPicker(varname) {
	var curhex = $('#' + varname + 'Color').val();
	if (curhex in colors) { curhex = colors[curhex]; }
	$('.' + varname + 'BackgroundSelector').css('background-color', '#' + curhex);
	$('.' + varname + 'ColorSelector').ColorPicker({
		color: '#' + curhex,
		onShow: function (colpkr) {
			$(colpkr).fadeIn(300);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(300);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('.' + varname + 'BackgroundSelector').css('background-color', '#' + hex);
			$('#' + varname + 'Color').val(hex.toUpperCase());
		}
	});						   
};

function bindClick() {	
	$("#add-trait").unbind();
	$("#add-trait").click( function (e) {
		numShown ++;
		var html = '<tr id="traitRow' 
		+ numShown 
		+ '"><td align="center" class="align-top" colspan="2"><select class="width100" name="trait' + numShown + '" size="1">'
		+ traitMenu
		+ '</select></td><td align="center" class="align-top" colspan="2"><input type="text" value="FFFFFF" id="trait'
		+ numShown 
		+ 'Color" class="width100 float-left" name="trait' + numShown + '_color" />'
		+ '<div class="trait' + numShown + 'ColorSelector colorSelector">'
		+ '<div class="trait' + numShown + 'BackgroundSelector backgroundSelector" style="background-color: rgb(255, 255, 255);">'
		+ '</div></div> '
		+ '<a href="#"><img src="./images/icons/arrow_up.png" alt="^" class="moveRowUp" rowId="' + numShown + '" /></a>'
		+ '<a href="#"><img src="./images/icons/arrow_down.png" alt="v" class="moveRowDown" rowId="' + numShown + '" /></a>'
		+ '<a href="#"><img src="./images/icons/cross.png" class="removeTraitRow" rowId="' + numShown + '" alt="X" /></a>'
		+ '</td></tr>'
		+ '<tr><td colspan="4" align="right" id="addRow">'
		+ '	<input id="add-trait" type="button" class="submit-input" value="Add another trait" /> &nbsp;&nbsp;&nbsp; <input type="submit" class="submit-input" value="Generate Preview" />'
		+ '</td></tr>';
		$("#addRow").remove();
		$(".content-table").append(html);
		bindClick();
		bindX();
		bindPicker("trait" + numShown);
	});
};

function bindX() {
	$(".removeTraitRow").click(function () {
		removeRow($(this));
		return false;
	});
	$(".moveRowUp").unbind();
	$(".moveRowUp").click(function() {
		moveRowUp($(this));
		return false;
	});
	$(".moveRowDown").unbind();
	$(".moveRowDown").click(function() {
		moveRowDown($(this));
		return false;
	});
};

function removeRow(element) {
	var rowID = element.attr("rowId");
	$("#traitRow" + rowID).remove();
	if(rowID != numShown) {
		for(var i = parseInt(rowID) + 1; i <= numShown; i++) {
			var newID = i - 1;
			$("#traitRow" + i + " .removeTraitRow").attr("rowId",newID);
			$("#traitRow" + i).attr("id","traitRow" + newID);
			$(":input[name=trait"+i+"]").attr("name","trait" + newID);
			$(":input[name=trait"+i+"_color]").attr("name","trait" + newID + "_color");
			$(":input[name=trait"+i+"_color_text]").attr("name","trait" + newID + "_color_text");
		}
			
	}
	numShown--;
};

function moveRowUp(element) {
	var rowID = parseInt(element.attr("rowId")); //Find current row Id
	if(rowID == 1) { return; } //Skip moving up row 1
	var prevID = rowID - 1;
	
	var curTrait = $(":input[name=trait"+rowID+"]").val();
	var curColor = $(":input[name=trait"+rowID+"_color]").val();
	var prevTrait = $(":input[name=trait"+prevID+"]").val();
	var prevColor = $(":input[name=trait"+prevID+"_color]").val();
	
	$(":input[name=trait"+rowID+"]").val(prevTrait);
	$(":input[name=trait"+rowID+"_color]").val(prevColor);
	$(":input[name=trait"+prevID+"]").val(curTrait);
	$(":input[name=trait"+prevID+"_color]").val(curColor);
	$(".trait" + rowID + "ColorSelector div").css('backgroundColor', '#' + prevColor);
	$(".trait" + prevID + "ColorSelector div").css('backgroundColor', '#' + curColor);
};

function moveRowDown(element) {
	var rowID = parseInt(element.attr("rowId")); //Find current row Id
	if(rowID == numShown) { return; } //Skip moving down last row
	var prevID = rowID + 1;
	
	var curTrait = $(":input[name=trait"+rowID+"]").val();
	var curColor = $(":input[name=trait"+rowID+"_color]").val();
	var prevTrait = $(":input[name=trait"+prevID+"]").val();
	var prevColor = $(":input[name=trait"+prevID+"_color]").val();
	
	$(":input[name=trait"+rowID+"]").val(prevTrait);
	$(":input[name=trait"+rowID+"_color]").val(prevColor);
	$(":input[name=trait"+prevID+"]").val(curTrait);
	$(":input[name=trait"+prevID+"_color]").val(curColor);
	$(".trait" + rowID + "ColorSelector div").css('backgroundColor', '#' + prevColor);
	$(".trait" + prevID + "ColorSelector div").css('backgroundColor', '#' + curColor);
		
};