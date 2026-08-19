<?php

/**
 *
 * sebo-topiccheck. An extension for the phpBB Forum Software package.
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
	'PP_ME_PR'				=> 'Buy me a beer for creating this extension',
	'PP_ME_EXT_TC'			=> '<label>Make a donation for this extension:</label><br><span>This extension is completely free. It is a project that I spend my time on for the enjoyment and use of the phpBB community. If you enjoy using this extension, or if it has benefited your forum, please consider <a href="%s" target="_blank" rel="noreferrer noopener">buying me a beer</a>. It would be greatly appreciated. Thank you for downloading TopicChecker!</span>',
	'PP_ME_EXT_ALT'			=> 'Donate via PayPal',

	'ACP_TOPICCHECK_TITLE' => 'Topic Title D. Checker',
	'ACP_TOPICCHECK_SETTINGS_TITLE'          => 'Topic Title Duplicate Checker - General settings',
	'ACP_TOPICCHECK_WORDS_TITLE'          => 'Topic Title Duplicate Checker - Low Relevance Words',
	'ACP_TOPICCHECK_WORDS'          => 'Manage Low Relevance Words',
	'ACP_TOPICCHECK_FORUMS'         => 'Manage Forums',

	'ACP_TOPICCHECK_FORUMS_EXPLAIN' => 'Select the forums where the Topic Checker search should be active.',
	'ACP_TOPICCHECK_SETTINGS_SAVED' => 'Forum settings saved successfully.',

	'WARNING'					  => 'Warning',
	'TOPICCHECK_LANGUAGE_MISSING_EXPLAIN' => 'No language detected in the database.',
	'TOPICCHECK_YOUR_LANGUAGE_MISSING_EXPLAIN' => 'No low relevance words have been defined for your current language. Please add some words to improve the Topic Checker functionality in your language.',

	'TC_WORDS_EXPLAIN'				 => 'Edit low relevance words',
	'TC_WORDS_EXPLAIN_TEXT' => 'These words will have lower relevance in the search algorithm than normal words. They are useful to filter out common words. Without these words, the search results may be less accurate.',
	'ACP_TOPICCHECK_WORDS_EXPLAIN_TEXTAREA' => 'Low relevance stopwords must be separated by comma.<br>These words will have lower relevance in the search algorithm than normal words.',

	'LANGUAGE_CHOOSE' => 'Choose Language',
	'LANGUAGE_CHOOSE_EXPLAIN' => 'Select the language for which you want to add low relevance words.',
	'TC_LANG_CREATE_EXPLAIN' => 'Add your forum language to create a new list of low relevance words.',
	'TC_LANG_CREATE' => 'Add a new language',

	'WHAT_IS_TC_FOR' => 'What is Topic Title Duplicate Checker for?',
	'WHAT_IS_TC_FOR_EXPLAIN' => '<strong>Topic Title Duplicate Checker</strong> helps users avoid creating duplicate topics.<br />While a user is creating a new topic and writing a new title, Topic Checker automatically searches for existing topics with similar titles and shows results in real time.<br />In this way, the user can see if the topic they are about to create has already been treated in the forum, thus reducing the creation of duplicate topics.<br />Every user will use the low relevance words defined for their selected language in their own control panel.',

	'TC_STEP_1_ADD_LANG' => 'Step 1: Add your forum language',
	'TC_STEP_2_CHOOSE_WORDS' => 'Step 2: Add common words, with low relevance in searching topics (optional)',
	'TC_STEP_3_CHOOSE_FORUMS' => 'Step 3: Choose forums where enable Topic Title Duplicate Checker',

	'LANGUAGE_ALREADY_EXISTS' => 'The selected language already exists in the database.',
	'LANGUAGE_ADDED_SUCCESS' => 'New language has been added successfully.',
	'NO_LANGUAGE_SELECTED' => 'No language selected.',

]);
