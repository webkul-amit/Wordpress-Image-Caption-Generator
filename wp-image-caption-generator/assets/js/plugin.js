(function($){
	var _count = 0;
	var _total = parseInt($('#batch-process-total').text());
	var stop_process = function() {
		reload();
	};
	$(document).on('itemprocessed', function(e, batch_count){
		let percentage = ((batch_count/_total) * 100).toFixed(3);
		$('.batch-process-progress-bar-inner').css('width', percentage+'%');
		$('#batch-process-processed').text(batch_count);
		$('#batch-process-percentage').text('('+percentage+'%)');
	});
	var process_next_item = function(index) {
		var nonce = icg.nonce;
		var ajax_url = icg.ajax_url;
		$.ajax({
			url: ajax_url + '?action=icg_process_next_batch_item&nonce='+nonce,
			type: 'POST',
			cache: false,
			data:{paged:index},
			beforeSend: function() {
				$('#batch-process-start').text(icg.text.processing).prop('disabled', true);
			},
			success: function(response) {
				console.log("root response", response.data);
				console.log("root index", index);
				if(response.data.data.length > 0) {
					process_image(response.data.data, index);
				} else {
					var message = '<span style="color: green;">finished</span>';
					$('.batch-process-current-item').html(message);
				}
			},
			error: function() {
				alert('HTTP Error.');
			},
		});
	}


	$(document).on('click', '#batch-process-start', function(e){
		let index = 1;
		e.preventDefault();
		process_next_item(index);
	});

	$(document).on('click', '#batch-process-stop', function(e){
		e.preventDefault();
		stop_process();
	});

	const process_image = (data, index) => {
		const promises = [];
		data.forEach((item)=>{
			let img = document.createElement('IMG');
			img.src = item.guid ? item.guid : ''; // set src to blob url
			promises.push(imageIsLoaded(item, img));
		});
		return Promise.all(promises).then(result=>{
			index += 1;
			console.log("item", data);
			console.log("index", index);
			process_next_item(index);
			return result;
		});
	}

	const imageIsLoaded = ( item, img  ) => {
		return new Promise((resolve, reject) => {
			try { 
				// Load the model.
				return mobilenet.load().then(model => {
					// Classify the image.
					return model.classify(img).then(predictions => {
						let caption = predictions[0].className;
						let _image = {
							id:item.ID,
							caption:caption,
						}
						return _updateCaption(_image).then(response => {
							// console.log("2");
							resolve(true);
						});
					});
				});
			} catch(err) {
				alert( err );
			}
		});
	};

	function _updateCaption(_image) {
		return new Promise((resolve, reject) => {
			var nonce = icg.nonce;
			var ajax_url = icg.ajax_url;
			$.ajax({
				url: ajax_url + '?action=icg_process_item_caption&nonce='+nonce,
				type: 'POST',
				cache: false,
				data:{item:_image},
				success: function(response) {
					// console.log("1");
					_count += 1;
					console.log("count", _count);
					$(document).trigger('itemprocessed', _count);
					resolve(true);
				},
				error: function() {
					alert('HTTP Error.');
				},
			});
		});
	}

})(jQuery);