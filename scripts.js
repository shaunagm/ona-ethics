( function( $ ) {

	var $buttons = $('#buttons');
	var $editform = $('#editform');


	$('#menu-toggle').on('click', function(){
		$(this).siblings('ul').toggleClass('expanded');
	});


	$('.ethics-question.checkbox input[type=checkbox]').on('click', function (){
		$label = $(this).parent();
		if ( $label.is('.selected') ){
			$label.removeClass('selected');
			$label.find('.demo-icon').removeClass('icon-check-2').addClass('icon-check-empty');
		} else {
			$label.addClass('selected');
			$label.find('.demo-icon').removeClass('icon-check-empty').addClass('icon-check-2');
		}
	});

	$('.editable').on('hover', function(){
		$(this).prepend($buttons.show());
		$buttons.attr('href', $(this).attr('data-href'));
	});
	$('.code').on('mouseleave', function(){
		$buttons.hide();
	});

} )( jQuery );