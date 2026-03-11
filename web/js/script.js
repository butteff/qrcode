$(document).ready(function(){
	
	$('#qr-form').submit(function(e){
		e.preventDefault();
		$('#errortext').hide();
		$('.okbtn').hide();
		$('.loader').show();
		let formData = $('#qr-form').serialize();
		
		$.ajax({
		    type: 'POST',           
		    url: '/index.php',       
		    data: formData,
		}).done(function(data) {
		    console.log("Success:", data);
		    if (data.status) {
		    	$('#shorturl').attr('href', data.shortUrl);
		    	$('#shorturl').text(data.shortUrl);
		    	$('#qrcode').attr('src', data.qr);
		    	$('.loader').hide();
		    	$('.okbtn').show();
		    	$('#modal').fadeIn();
		    	$('#errortext').hide();
			} else {
				$('.loader').hide();
		    	$('.okbtn').show();
		    	$('#errortext').text(data.message);
		    	$('#errortext').fadeIn();
			}
		})
	});

	$('.close').click(function(e) {
		$('#modal').fadeOut();
	});

});