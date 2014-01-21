/** @Name		IceBox
 ** @URI		http://pxoink.net/articles/icebox
 ** @Author		PxO Ink
 ** @AuthorURI	http://pxoink.net
 ** @License	Proprietary.
 ** @Copyright	© 2014 PxO Ink. All Rights Reserved.
 **/

//If jQuery exists.
if (typeof jQuery !== 'undefined') { 
	//When the document is ready.
	jQuery(document).ready(function() { 
		//For each element to invoke icebox.
		jQuery('.icebox').each(function() { 
			//For each invoking.
			jQuery(this).click(function(e) {
				//Get the element.
				$e	=	jQuery(jQuery(this).attr('data-icebox-id')); 
				
				//Create elements.
				var	$shadow	=	jQuery('<div>', {id: 'ice-shadow'});
				var	$close	=	jQuery('<a>', {id: 'icebox-close', 
					href: 'javascript:void(0);', title: 'Close'}).text('X');
				var	$div	=	jQuery('<div>').append($e.html());
				var	$icebox	=	jQuery('<div>', {id: 'icebox'}).append($close).append($div);
				
				//Append the shadow and icebox.
				jQuery('body').append($shadow).append($icebox);
				
				//Fade the shadow in.
				jQuery('#ice-shadow').fadeIn('400', function() {
					//Fade the icebox in.
					jQuery('#icebox').fadeIn('400', function() {
						//Center the element.
						jQuery(this).css("top", (((jQuery(window).height() - jQuery(this).outerHeight()) / 2) + jQuery(window).scrollTop()));
						jQuery(this).css("left", (((jQuery(window).width() - jQuery(this).outerWidth()) / 2) + jQuery(window).scrollLeft()));
						
						//Set close listener.
						jQuery('#icebox-close, #ice-shadow').click(function() {
							//Fade the icebox out.
							jQuery('#icebox').fadeOut('400', function() {
								//Fade the shadow out.
								jQuery('#ice-shadow').fadeOut('400', function() {
									//Kill the icebox and the shadow.
									jQuery('#ice-shadow, #icebox').remove();
								});
							});	
						});
					});
				});
				
				//Prevent default functionality.
				e.preventDefault();
				
				//Return false.
				return false;
			});
		});
	});
} else {
	//Log the error.
	console.error('jQuery is not loaded.');
}