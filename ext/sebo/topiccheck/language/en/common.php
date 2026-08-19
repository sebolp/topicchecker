<?php

/**
 *
 * topiccheck. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	/* >1.0.0 */
	'SEBO_TOPICCHECK_ERROR_MESSAGE' => 'An error occurred:',
	/* >1.2.0 */
	'SEBO_TOPICCHECK_OLDER_THAN_YEARS' => [
		1 => 'Last post published more than %d year ago',
		2 => 'Last post published more than %d years ago',
	],
	'SEBO_TOPICCHECK_RATE_LIMITED' => 'Too many search requests. Please wait a moment and try again.',
]);
