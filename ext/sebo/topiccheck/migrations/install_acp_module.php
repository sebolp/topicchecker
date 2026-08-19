<?php

/**
 *
 * sebo-topiccheck. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace sebo\topiccheck\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\sebo\topiccheck\migrations\install_data'];
	}

	public function update_data()
	{
		return [
			// 1. Add Main Category under .MODS (This is the header, not a clickable module)
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_TOPICCHECK_TITLE']],

			// 2. Add "Words" Module (mode: words) under the new category
			['module.add', ['acp', 'ACP_TOPICCHECK_TITLE', [
				'module_basename'   => '\sebo\topiccheck\acp\main_module',
				'module_langname'   => 'ACP_TOPICCHECK_WORDS',
				'module_mode'       => 'words',
				'module_auth'       => 'ext_sebo/topiccheck && acl_a_board',
			]]],

			// 3. Add "Forums" Module (mode: settings) under the new category
			['module.add', ['acp', 'ACP_TOPICCHECK_TITLE', [
				'module_basename'   => '\sebo\topiccheck\acp\main_module',
				'module_langname'   => 'ACP_TOPICCHECK_FORUMS',
				'module_mode'       => 'settings',
				'module_auth'       => 'ext_sebo/topiccheck && acl_a_board',
			]]],
		];
	}

	public function revert_data()
	{
		return [
			// Remove the category (phpBB usually cleans up children automatically, but we are explicit)
			['module.remove', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_TOPICCHECK_TITLE']],

			['module.remove', ['acp', 'ACP_TOPICCHECK_TITLE', [
				'module_basename'   => '\sebo\topiccheck\acp\main_module',
				'module_langname'   => 'ACP_TOPICCHECK_WORDS',
				'module_mode'       => 'words',
				'module_auth'       => 'ext_sebo/topiccheck && acl_a_board',
			]]],

			['module.remove', ['acp', 'ACP_TOPICCHECK_TITLE', [
				'module_basename'   => '\sebo\topiccheck\acp\main_module',
				'module_langname'   => 'ACP_TOPICCHECK_FORUMS',
				'module_mode'       => 'settings',
				'module_auth'       => 'ext_sebo/topiccheck && acl_a_board',
			]]],
		];
	}
}
