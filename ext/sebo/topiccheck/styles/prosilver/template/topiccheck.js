(function($)
{
	'use strict';

	$(document).ready(function()
	{
		var $subject = $('#subject');
		var $container = $('#sebo-topic-check-container');
		var $list = $('#sebo-topic-check-list');
		var searchUrl = $container.data('url');
		var timer;

		if (!$subject.length || !$container.length)
		{
			return;
		}

		$subject.attr('autocomplete', 'off'); // Disable browser autocomplete

		$subject.on('input', function()
		{
			var query = $(this).val();
			clearTimeout(timer);

			if (query.length < 3)
			{
				$container.hide();
				return;
			}

			timer = setTimeout(function()
			{
				$.ajax({
					url: searchUrl,
					type: 'GET',
					data: {
						q: query
					},
					dataType: 'json',
					success: function(data)
					{
						$list.empty();

						if (data.length > 0)
						{
							$.each(data, function(i, item)
							{
								// 1. Highlight logic (multi-word)
								var words = query.split(/\s+/).filter(function(w)
								{
									return w.length > 0;
								});

								var escapedWords = $.map(words, function(w)
								{
									return w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
								});

								var pattern = '(' + escapedWords.join('|') + ')';
								var regex = new RegExp(pattern, 'gi');

								// 2. Create DOM elements
								var $li = $('<li>');
								var $link = $('<a>', {
									href: item.url,
									target: '_blank',
									rel: 'noopener noreferrer',
									class: 'tc-link'
								});

								var $titleContainer = $('<span>', {
									class: 'tc-title-container'
								});

								var $fullDisplay = $('<span>', {
									class: 'tc-breadcrumbs'
								});

								// Home icon, prepended before the forum path
								$('<i>', {
									class: 'icon fa-home fa-fw',
									'aria-hidden': 'true'
								}).appendTo($fullDisplay);

								// 3. Breadcrumbs
								$.each(item.breadcrumbs, function(index, crumb)
								{
									$('<span>', {
										class: 'tc-crumb'
									})
										.css('font-weight', 'normal')
										.text(crumb)
										.appendTo($fullDisplay);
								});

								// 4. Topic title
								var $title = $('<span>', {
									class: 'tc-crumb'
								}).css('font-weight', 'bold');

								var lastIndex = 0;
								var match;

								regex.lastIndex = 0;

								while ((match = regex.exec(item.title)) !== null)
								{
									if (match.index > lastIndex)
									{
										$title.append(
											document.createTextNode(
												item.title.substring(lastIndex, match.index)
											)
										);
									}

									$('<span>')
										.css('color', '#D31141')
										.text(match[0])
										.appendTo($title);

									lastIndex = match.index + match[0].length;

									if (match[0].length === 0)
									{
										regex.lastIndex++;
									}
								}

								if (lastIndex < item.title.length)
								{
									$title.append(
										document.createTextNode(
											item.title.substring(lastIndex)
										)
									);
								}

								$title.appendTo($fullDisplay);

								// 5. Older topic indicator
								if (item.old)
								{
									$('<strong>', {
										class: 'tc-badge-older'
									})
										.attr('data-tooltip', item.oldtext)
										.append(
											$('<i>', {
												class: 'fa fa-spin fa-hourglass-end',
												'aria-hidden': 'true'
											})
										)
										.appendTo($titleContainer);
								}

								// 6. Assemble title area
								$titleContainer.prepend($fullDisplay);

								// 7. External link icon
								var $openText = $('<span>', {
									class: 'tc-open-text'
								});

								$('<i>', {
									class: 'icon fa-external-link fa-fw tc-external-link-icon',
									'aria-hidden': 'true'
								}).appendTo($openText);

								$link
									.append($titleContainer)
									.append($openText);

								$li.append($link);
								$list.append($li);
							});

							$container.show();
						}
						else
						{
							$container.hide();
						}
					},
					error: function(xhr, status, error)
					{
						console.error('AJAX Error:', error);
					}
				});
			}, 300);
		});

		// Close dropdown when clicking outside
		$(document).on('click', function(e)
		{
			if (!$(e.target).closest('#sebo-topic-check-container').length && !$(e.target).is('#subject'))
			{
				$container.hide();
			}
		});
	});
})(jQuery);