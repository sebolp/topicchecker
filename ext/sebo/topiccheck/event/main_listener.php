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
use phpbb\request\request_interface;

class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var request_interface */
	protected $request;

	public function __construct(
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		request_interface $request
	)
	{
		$this->template = $template;
		$this->helper   = $helper;
		$this->request  = $request;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.page_header' => 'add_search_url_variable',
		];
	}

	public function add_search_url_variable($event)
	{
		// check if in posting.php with REQUEST_URI
		$script_name = $this->request->server('PHP_SELF', '');
		$is_posting  = (substr($script_name, -11) === 'posting.php');

		if ($is_posting)
		{
			$this->template->assign_vars([
				'U_SEBO_TOPIC_SEARCH' => $this->helper->route('sebo_topiccheck_search'),
			]);
		}
	}
}
