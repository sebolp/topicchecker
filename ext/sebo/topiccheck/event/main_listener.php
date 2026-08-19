<?php

/**
 *
 * sebo-topiccheck. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\topiccheck\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\user;

class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var user */
	protected $user;

	public function __construct(
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		user $user
	)
	{
		$this->template = $template;
		$this->helper   = $helper;
		$this->user     = $user;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.page_header' => 'add_search_url_variable',
		];
	}

	public function add_search_url_variable($event)
	{
		// Check if we are on posting.php using phpBB's own page tracking
		$is_posting = ($this->user->page['page_name'] === 'posting.php');

		if ($is_posting)
		{
			$this->template->assign_vars([
				'U_SEBO_TOPIC_SEARCH' => $this->helper->route('sebo_topiccheck_search'),
			]);
		}
	}
}
