var codeW = jq("#code li").size() * 140;
	
	jq(".qr").css("width",codeW + 9 + "px");
	jq("#code").css("width",codeW+6+"px");
	jq("#code ul").css({
		"width":codeW - 20 + "px"
	});
	jq(".qrCode .qr").css("left",-codeW -9 +"px");
	
jq(".qrCode").hover(function() {
	jq(this).addClass("qrHover");
	jq(this).children("b").stop().css("background-color", "#D4D4D4").animate({
			"backgroundColor": "#D4D4D4"
		},
		100,
		function() {
			jq(this).css({
				"backgroundColor": "#D4D4D4"
			});
		});
	jq(this).closest(".qrCode").find(".qr").show();
},function() {
	jq(this).removeClass("qrHover");
	jq(this).children("b").stop().css("background-color", "#D4D4D4").animate({
			"backgroundColor": "#D4D4D4"
		},
		100,
		function() {
			jq(this).css({
				"backgroundColor": "#D4D4D4"
			});
		});
	jq(this).closest(".qrCode").find(".qr").hide();
}); 
