
$(document).ready(function () {
	// load all feeds
	$.getJSON('/ajax/feeds', function(reply) {
		if (reply.result != 'ok')
			return;
		
		$.each(reply.data, function(key, value) {
			$('<li>' + value.name + '<li>').appendTo('#feed-list');
		})
	});
	
	// load bunch of articles
	$.getJSON('/ajax/articles', function(reply) {
		if (reply.result != 'ok')
			return;
		
		render_articles(reply.data);
	});

	// show selected article
	$('#articles').on('click', 'a.title', function() {
		scroll = false;
		row = $(this).parents('tr')[0];

		if ( ! $(row).hasClass('selected')) {
			scroll = true;
		}

		$('#articles tr').not(row).removeClass('selected');
		$(row).toggleClass('selected');

		if (scroll) {
			// scroll to top
			$('html, body').scrollTop($(row).offset().top-50);
		}

		// mark as read
		if ( ! $(row).hasClass('read')) {
			$.post('/ajax/read', {article: $(row).data('id')});
			$(row).addClass('read');
		}

		return false;
	});

	// add new feed
	$('#modal-add-feed form').submit(function ()  {
		
		// animate
		$('<li class="loading">Loading...<li>').appendTo('#feed-list');

		$.post('/ajax/feeds', $('#modal-add-feed form').serialize(), function (reply) {
			$('#feed-list .loading').remove();

			if (reply.result != 'ok')
				return;

			$('<li>' + reply.data.feed.name + '<li>').appendTo('#feed-list');

			render_articles(reply.data.articles);
		});

		$('#modal-add-feed').modal('hide');

		return false;
	});

	// submit add feed form
	$('#modal-add-feed .btn-primary').click(function ()  {
		$('#modal-add-feed form').submit();
	});

	function render_articles(articles) {
		$('#articles').empty();

		$.each(articles, function(key, value) {
			feed = $('<td></td>').addClass('feed').html(value.feed.name);

			title = $('<a></a>').addClass('title').html(value.title);
			date = $('<span></span>').addClass('date').html(value._pub_date);
			content = $('<div></div>').addClass('content').html(value.content);
			article = $('<td></td>')
				.append(title)
				.append(date)
				.append(content);

			row = $('<tr></tr>')
				.data('id', value.id)
				.append(feed)
				.append(article);

			if (value._read) {
				row.addClass('read');
			}

			$('#articles').append(row);
		})
	}
})
