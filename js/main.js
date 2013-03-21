
$(document).ready(function () {
	selected_feed = 0;

	// load all feeds
	reset_feeds();
	
	// load bunch of articles
	select_feed(0);

	// bind j/k navigation
	$(document).on('keydown', function (e) {
		inputs = ['input', 'button', 'select', 'textarea'];
		if (inputs.indexOf(e.target.tagName.toLowerCase()) > -1) {
			return
		}

		char = String.fromCharCode(e.keyCode).toLowerCase();

		if ('jk'.indexOf(char) == -1) {
			return;
		}

		article = $('#articles .selected');

		switch (char) {
			case 'j':
				if (article.length == 0) {
					nextarticle = $('#articles tr:first');	
				} else {
					nextarticle = article.next('tr');
				}
				break;
			case 'k':
				nextarticle = article.prev('tr');
				break;
			default:
				return;
		}

		if (nextarticle) {
			show_article(nextarticle);
		}
	})

	// show all
	$('#feed-list-container #all').on('click', function() {
		select_feed(0);
	});

	// show articles from selected feed
	$('#feed-list').on('click', 'li', function() {
		id = $(this).data('id');
		select_feed(id);
	});
	
	// show selected article
	$('#articles').on('click', 'a.title', function() {
		scroll = false;
		row = $(this).parents('tr')[0];
		show_article(row);

		return false;
	});

	// mark current feed as read
	$('#mark-feed-read').click(function ()  {
		$.post('/ajax/read', {feed: selected_feed}, function (data) {
			alert('yay');
		});
	});

	// add new feed
	$('#modal-add-feed form').submit(function ()  {
		
		// animate
		$('<li class="loading">Loading...<li>').appendTo('#feed-list');

		$.post('/ajax/feeds', $('#modal-add-feed form').serialize(), function (reply) {
			$('#feed-list .loading').remove();

			if (reply.result != 'ok')
				return;

			reset_feeds();
			select_feed(reply.data.id);
		});

		$('#modal-add-feed form').reset();
		$('#modal-add-feed').modal('hide');

		return false;
	});

	// submit add feed form
	$('#modal-add-feed .btn-primary').click(function ()  {
		$('#modal-add-feed form').submit();
	});

	function show_article(row) {
		if ( ! $(row).hasClass('selected')) {
			scroll = true;
		}
		
		// start loading images
		$(row).find('img').each(function() {
			src = $(this).data('src');
			if (src) {
				$(this).attr('src', src);
			}
		})

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

			badge = $('#feed-list li[data-feed-id="' + $(row).data('feed-id') + '"] .badge');
			count = badge.html()-1;
			if (count < 1) {
				badge.hide();
			} else {
				badge.html(badge.html()-1);
			}
		}
	}

	function reset_feeds() {
		$('#feed-list').empty();

		$.getJSON('/ajax/feeds', function(reply) {
			if (reply.result != 'ok')
				return;
			
			$.each(reply.data, function(key, value) {
				feed = $('<a></a>')
					.addClass('name')
					.html(value.name);
				badge = $('<span></span>')
					.addClass('badge')
					.html(value._unread);
				row = $('<li></li>')
					.attr('data-feed-id', value.id)
					.append(feed)
					.append(badge)
					.data('id', value.id)

				$('#feed-list').append(row);
			})
		});
	}

	function select_feed(feed) {
		$.getJSON('/ajax/articles', {feed: feed}, function(reply) {
			if (reply.result != 'ok')
				return;

			selected_feed = feed;
			render_articles(reply.data);
		});
	}

	function reset_articles() {
		$.getJSON('/ajax/articles', function(reply) {
			if (reply.result != 'ok')
				return;
			
			render_articles(reply.data);
		});
	}

	function render_articles(articles) {
		$('#articles').empty();
		window.scrollTo(0,0);

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
				.data('feed-id', value.feed.id)
				.append(feed)
				.append(article);

			if (value._read) {
				row.addClass('read');
			}

			$('#articles').append(row);
		})
	}
})
