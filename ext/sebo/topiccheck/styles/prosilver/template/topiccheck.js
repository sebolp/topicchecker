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
								// 1. Highlight Logic (Multi-word)
								// Split input query by spaces to find individual words
								var words = query.split(/\s+/).filter(function(w)
								{
									return w.length > 0;
								});

								// Escape special regex chars for each word
								var escapedWords = $.map(words, function(w)
								{
									return w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
								});

								// Create a regex that matches ANY of the words (OR logic) for highlighting
								// Example: (ancora|new)
								var pattern = '(' + escapedWords.join('|') + ')';
								var regex = new RegExp(pattern, 'gi');

								// Apply red color to ALL matched words
								var titleHighlighted = item.title.replace(regex, '<span style="color:#D31141">$1</span>');

								// 2. Breadcrumbs + Title
								var fullDisplay = '<span class="breadcrumbs">' +
									item.breadcrumbs +
									'<span class="crumb" style="font-weight: bold;">' + titleHighlighted + '</span>' +
									'</span>';

								// 3. ONLY Icon
								var openText = '<i class="icon fa-external-link fa-fw tc-external-link-icon" aria-hidden="true"></i>';

								// Determine if the "old" icon should be displayed
								var oldIcon = (item.old) ? ' <strong class="badge older" data-tooltip="' + item.oldtext + '"><i class="fa fa-spin fa-hourglass-end" aria-hidden="true"></i></strong>' : '';

								var li = '<li>' +
									'<a href="' + item.url + '" target="_blank" class="topic-check-link">' +
									'<span class="topic-check-title-container">' + fullDisplay + oldIcon + '</span>' +
									'<span class="topic-check-open-text">' + openText + '</span>' +
									'</a></li>';

								$list.append(li);
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
						console.error("AJAX Error:", error);
					}
				});
			}, 300);
		});

		// Close dropdown when clicking outside
		$(document).on('click', function(e)
		{
			if (!$(e.target).closest('#sebo-topic-check-wrapper').length && !$(e.target).is('#subject'))
			{
				$container.hide();
			}
		});
	});
})(jQuery);
