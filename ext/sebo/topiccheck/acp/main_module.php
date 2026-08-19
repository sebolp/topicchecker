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
 * TopicCheck ACP module.
 */
class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 * Main ACP module
	 *
	 * @param int    $id   The module ID
	 * @param string $mode The module mode (settings or words)
	 * @throws \Exception
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		/** @var \sebo\topiccheck\controller\acp_controller $acp_controller */
		$acp_controller = $phpbb_container->get('sebo.topiccheck.controller.acp');

		// Load the template
		switch ($mode)
		{
			case 'settings':
				$this->tpl_name = 'acp_topiccheck_settings';
				$this->page_title = 'ACP_TOPICCHECK_SETTINGS_TITLE';
				break;
			case 'words':
				$this->tpl_name = 'acp_topiccheck_words';
				$this->page_title = 'ACP_TOPICCHECK_WORDS_TITLE';
				break;
			default:
				trigger_error('NO_MODE', E_USER_ERROR);
		}

		// Make the $u_action url available in our ACP controller
		$acp_controller->set_page_url($this->u_action);

		// Load the display options handle in our ACP controller
		// We pass $mode to know which tab we are in
		$acp_controller->display_options($mode);
	}
}
