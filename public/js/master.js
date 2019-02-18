/**
 * 
 */

$(document).ready(function() {
	$('.delete').live('click', function(event){
		event.preventDefault();
		var location = $(this).attr('href');
		
		$.ajax({
			type: 'DELETE',
			url: location,
			context: $(this),
			success: function(html){
				$(this).parent().parent().parent().fadeOut();
				$('#post_count').text(parseInt($('#post_count').text()) - 1);
			},
			error: function (request, status, error) {
				alert('An error occurred, please try again.');
			}
			});
	});
	$('#more_posts').bind( 'click', function(event){
		event.preventDefault();
		var location = window.location.pathname + "/" + $('#post-list').children().size();
		
		$.ajax({
			type: 'GET',
			url: location,
			context: $('#post_list'),
			success: function(html){
				$(this).append(html);
				if ($('#post_list').children().size() <= (parseInt($('#post_count').text()))) {
					$('#load_more').hide();
				}
			},
			error: function (request, status, error) {
				alert('An error occurred, please try again.');
			}
		});
	});
});
