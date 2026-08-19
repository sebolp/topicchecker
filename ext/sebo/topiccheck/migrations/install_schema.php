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

class install_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'sebo_topiccheck_words');
	}

	public static function depends_on()
	{
	return ['\phpbb\db\migration\data\v330\v330'];
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
				// Stopwords table: One row per language
				$this->table_prefix . 'sebo_topiccheck_words' => [
					'COLUMNS' => [
						'lang_iso'  => ['VCHAR:5', 'en'], // Primary Key (e.g. 'it', 'en')
						'word_list' => ['MTEXT', ''],     // Comma separated list
					],
					'PRIMARY_KEY' => 'lang_iso',
				],
				// Forum activation table (unchanged)
				$this->table_prefix . 'sebo_topiccheck_forums' => [
					'COLUMNS' => [
						'forum_id' => ['UINT', 0],
						'active'   => ['BOOL', 0],
					],
					'PRIMARY_KEY' => 'forum_id',
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'sebo_topiccheck_words',
				$this->table_prefix . 'sebo_topiccheck_forums',
			],
		];
	}
}
