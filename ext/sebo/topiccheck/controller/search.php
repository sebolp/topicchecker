<?php

/**
 *
 * sebo-topiccheck. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, sebo, https://www.fiatpandaclub.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

declare(strict_types=1);

namespace sebo\topiccheck\controller;

use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\controller\helper;
use phpbb\user;
use phpbb\auth\auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class search
{
	/** @var \phpbb\language\language */
	protected $language;

	/** @var driver_interface */
	protected $db;

	/** @var request_interface */
	protected $request;

	/** @var helper */
	protected $helper;

	/** @var user */
	protected $user;

	/** @var auth */
	protected $auth;

	/** @var string */
	protected $table_prefix;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 */
	public function __construct(
		\phpbb\language\language $language,
		driver_interface $db,
		request_interface $request,
		helper $helper,
		user $user,
		auth $auth,
		string $table_prefix,
		string $phpbb_root_path = '',
		string $php_ext = 'php'
	)
	{
		$this->language = $language;
		$this->db = $db;
		$this->request = $request;
		$this->helper = $helper;
		$this->user = $user;
		$this->auth = $auth;
		$this->table_prefix = $table_prefix;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Handle the AJAX search request
	 */
	public function handle(): JsonResponse
	{
		try
		{
			// Load common lang file for error messages
			$this->language->add_lang('common', 'sebo/topiccheck');

			$search_query = $this->request->variable('q', '', true);
			$search_query = trim((string) $search_query);

			$results = [];

			if (mb_strlen($search_query) >= 3)
			{
				// ---------------------------------------------------------
				// 1. FORUM PERMISSIONS & ACTIVATION (NO CACHE)
				// ---------------------------------------------------------

				// A. User Read Permissions
				$readable_forums = $this->auth->acl_getf('f_read', true);
				$readable_ids = array_keys($readable_forums);

				if (empty($readable_ids))
				{
					return new JsonResponse([]);
				}

				// Exclude password protected forums unless access was granted
				$sql_ary = [
					'SELECT'	=> 'f.forum_id',
					'FROM'		=> [
						$this->table_prefix . 'forums' => 'f',
					],
					'LEFT_JOIN'	=> [
						[
							'FROM'	=> [$this->table_prefix . 'forums_access' => 'fa'],
							'ON'	=> 'fa.forum_id = f.forum_id
								AND fa.session_id = \'' . $this->db->sql_escape($this->user->session_id) . '\'',
						],
					],
					'WHERE'		=> $this->db->sql_in_set('f.forum_id', $readable_ids) . '
						AND (f.forum_password = \'\' OR fa.user_id = ' . (int) $this->user->data['user_id'] . ')',
				];

				$sql = $this->db->sql_build_query('SELECT', $sql_ary);
				$result = $this->db->sql_query($sql);

				$readable_ids = [];

				while ($row = $this->db->sql_fetchrow($result))
				{
					$readable_ids[] = (int) $row['forum_id'];
				}

				$this->db->sql_freeresult($result);

				if (empty($readable_ids))
				{
					return new JsonResponse([]);
				}

				// B. Forums activated in DB (sebo_topiccheck_forums)
				// Build the query using the SQL array method for phpBB DBAL
				$sql_ary = [
					'SELECT'	=> 'forum_id',
					'FROM'		=> [
						$this->table_prefix . 'sebo_topiccheck_forums' => 'stf',
					],
					'WHERE'		=> 'active = 1',
				];

				$sql = $this->db->sql_build_query('SELECT', $sql_ary);
				$result = $this->db->sql_query($sql);
				$active_forum_ids = [];
				while ($row = $this->db->sql_fetchrow($result))
				{
					$active_forum_ids[] = (int) $row['forum_id'];
				}
				$this->db->sql_freeresult($result);

				// C. Intersection (Readable AND Active)
				$allowed_forum_ids = array_intersect($readable_ids, $active_forum_ids);

				if (empty($allowed_forum_ids))
				{
					return new JsonResponse([]);
				}

				// ---------------------------------------------------------
				// 2. STOPWORDS LOGIC (FROM DB - EXPLODED STRING)
				// ---------------------------------------------------------

				$low_value_words = [];
				$current_lang = $this->user->lang_name;

				// Build the query to fetch the word list for the current language
				$sql_ary = [
					'SELECT'	=> 'word_list',
					'FROM'		=> [
						$this->table_prefix . 'sebo_topiccheck_words' => 'stw',
					],
					'WHERE'		=> "lang_iso = '" . $this->db->sql_escape($current_lang) . "'",
				];

				$sql = $this->db->sql_build_query('SELECT', $sql_ary);
				$result = $this->db->sql_query($sql);
				$row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				if ($row && !empty($row['word_list']))
				{
					// Explode string to array
					$raw_words = explode(',', $row['word_list']);
					// Trim whitespaces
					$low_value_words = array_map('trim', $raw_words);
				}

				// ---------------------------------------------------------
				// 3. SEARCH & SCORING LOGIC
				// ---------------------------------------------------------

				$keywords = explode(' ', $search_query);
				$where_conditions = [];
				$score_cases = [];

				foreach ($keywords as $word)
				{
					$word = trim($word);

					if (!empty($word) && mb_strlen($word) > 2)
					{
						$like_safe_word = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $word);
						$search_escaped = $this->db->sql_escape($like_safe_word);
						$where_conditions[] = "t.topic_title LIKE '%" . $search_escaped . "%' ESCAPE '\\\\'";

						// SCORING
						$weight = 10;
						if (in_array(mb_strtolower($word), array_map('mb_strtolower', $low_value_words)))
						{
							$weight = 2;
						}
						else if (mb_strlen($word) > 6)
						{
							$weight = 15;
						}
						$score_cases[] = "(CASE WHEN t.topic_title LIKE '%" . $search_escaped . "%' THEN " . $weight . " ELSE 0 END)";
					}
				}

				// Ensure forum IDs are strict integers
				$allowed_forum_ids = array_map('intval', $allowed_forum_ids);

				if (!empty($where_conditions))
				{
					$sql_where = implode(' OR ', $where_conditions);

					$order_by_sql = 't.topic_time DESC';

					if (!empty($score_cases))
					{
						$sql_order_relevance = implode(' + ', $score_cases);
						$order_by_sql = '(' . $sql_order_relevance . ') DESC, ' . $order_by_sql;
					}

					// Only return approved topics.
					// TopicCheck must not disclose unapproved, reapproved,
					// or soft-deleted topics to any user.
					$sql_ary = [
						'SELECT'	=> 't.topic_id, t.topic_title, t.topic_time, t.topic_last_post_time, t.forum_id, f.forum_name, f.left_id, f.right_id',
						'FROM'		=> [
							TOPICS_TABLE	=> 't',
						],
						'LEFT_JOIN' => [
							[
								'FROM'	=> [FORUMS_TABLE => 'f'],
								'ON'	=> 't.forum_id = f.forum_id',
							],
						],
						'WHERE'		=> '(' . $sql_where . ')
							AND ' . $this->db->sql_in_set('t.forum_id', $allowed_forum_ids) . '
							AND t.topic_visibility = ' . (int) ITEM_APPROVED,
						'ORDER_BY'	=> $order_by_sql,
					];

					$sql = $this->db->sql_build_query('SELECT', $sql_ary);
					$result = $this->db->sql_query_limit($sql, 50);

					// Load the whole forum tree once (nested set) to avoid N+1 queries
					$sql_ary_forums = [
						'SELECT'	=> 'forum_id, forum_name, left_id, right_id',
						'FROM'		=> [
							FORUMS_TABLE	=> 'f',
						],
						'ORDER_BY'	=> 'left_id ASC',
					];

					$sql_forums = $this->db->sql_build_query('SELECT', $sql_ary_forums);
					$result_forums = $this->db->sql_query($sql_forums);

					$all_forums = [];

					while ($forum_row = $this->db->sql_fetchrow($result_forums))
					{
						$all_forums[] = $forum_row;
					}
					$this->db->sql_freeresult($result_forums);

					while ($row = $this->db->sql_fetchrow($result))
					{
						$topic_id = (int) $row['topic_id'];
						$forum_id = (int) $row['forum_id'];

						// Breadcrumbs
						$breadcrumbs = [];

						foreach ($all_forums as $forum_candidate)
						{
							if ((int) $forum_candidate['left_id'] < (int) $row['left_id'] && (int) $forum_candidate['right_id'] > (int) $row['right_id'])
							{
								$breadcrumbs[] = $forum_candidate['forum_name'];
							}
						}

						$breadcrumbs[] = $row['forum_name'];

						// URL route generation
						try
						{
							$url = $this->helper->route('phpbb_viewtopic_route', [
								'f' => $forum_id,
								't' => $topic_id,
							]);
						}
						catch (\Exception $e)
						{
							// Security fallback
							$url = append_sid($this->phpbb_root_path . 'viewtopic.' . $this->php_ext, [
								'f' => $forum_id,
								't' => $topic_id,
							]);
						}

						$time_threshold = time() - 31536000; // Current time minus 1 year (in seconds)

						// Flag as old topic if last post time is older than 1 year
						$results[] = [
							'topic_id'		=> $topic_id,
							'title'			=> $row['topic_title'],
							'breadcrumbs'	=> $breadcrumbs,
							'url'			=> $url,
							'old'			=> ((int) $row['topic_last_post_time'] < $time_threshold),
							'oldtext'		=> $this->language->lang('SEBO_TOPICCHECK_OLDER_THAN_YEAR'),
						];
					}
					$this->db->sql_freeresult($result);
				}
			}

			return new JsonResponse($results);
		}
		catch (\Exception $e)
		{
			return new JsonResponse([
				'error' => true,
				'message' => $this->language->lang('SEBO_TOPICCHECK_ERROR_MESSAGE'),
			], 500);
		}
	}
}
