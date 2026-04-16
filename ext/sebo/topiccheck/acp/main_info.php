<?php

/**
 *
 * sebo-topiccheck. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\topiccheck\acp;

/**
 * TopicCheck ACP module info.
 */
class main_info
{
	public function module()
	{
		return [
			'filename' => '\sebo\topiccheck\acp\main_module',
			'title'    => 'ACP_TOPICCHECK_TITLE',
			'modes'    => [
				'settings' => [
					'title' => 'ACP_TOPICCHECK_FORUMS',
					'auth'  => 'ext_sebo/topiccheck && acl_a_board',
					'cat'   => ['ACP_TOPICCHECK_TITLE'],
				],
			],
		];
	}
}
