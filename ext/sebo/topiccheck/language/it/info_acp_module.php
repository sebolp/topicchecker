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
	'PP_ME_PR'				=> 'Offrimi una birra per questa estensione',
	'PP_ME_EXT_TC'			=> '<label>Fai una donazione per questa estensione:</label><br><span>Questa estensione è completamente gratuita. E\' un progetto su cui ho speso del tempo per imparare e condividere con la community phpBB. Se ti piace questa estensione, o ha migliorato il tuo forum, prendi in considerazione l\'idea di <a href="%s" target="_blank" rel="noreferrer noopener">offrirmi una birra</a>. Grazie mille anche solo per aver scaricato TopicChecker!</span>',
	'PP_ME_EXT_ALT'			=> 'Dona con PayPal',

	'ACP_TOPICCHECK_TITLE' => 'Topic Title D. Checker',
	'ACP_TOPICCHECK_SETTINGS_TITLE' => 'Topic Title Duplicate Checker - Impostazioni generali',
	'ACP_TOPICCHECK_WORDS_TITLE'    => 'Topic Title Duplicate Checker - Parole poco specifiche',
	'ACP_TOPICCHECK_WORDS'          => 'Gestisci parole poco specifiche',
	'ACP_TOPICCHECK_FORUMS'         => 'Gestisci forum',

	'ACP_TOPICCHECK_FORUMS_EXPLAIN' => 'Seleziona i forum dove la ricerca Topic Check deve essere attiva.',
	'ACP_TOPICCHECK_SETTINGS_SAVED' => 'Impostazioni del forum salvate correttamente.',

	'WARNING'					  => 'Attenzione',
	'TOPICCHECK_LANGUAGE_MISSING_EXPLAIN' => 'Nessuna lingua rilevata nel database.',
	'TOPICCHECK_YOUR_LANGUAGE_MISSING_EXPLAIN' => 'Non sono state definite parole a bassa rilevanza per la lingua corrente. Aggiungi alcune parole per migliorare la funzionalità del Topic Checker nella tua lingua.',

	'TC_WORDS_EXPLAIN'				 => 'Modifica le parole poco rilevanti',
	'TC_WORDS_EXPLAIN_TEXT' => 'Queste parole vengono utilizzate nella ricerca effettuata da Topic Checker mentre un utente sta aprendo un nuovo topic ed inserendo un nuovo titolo. Avranno bassa rilevanza nell\'algoritmo di ricerca rispetto alle normali parole. Sono utili per filtrare le parole comuni. Senza queste parole, i risultati della ricerca potrebbero essere meno accurati.',
	'ACP_TOPICCHECK_WORDS_EXPLAIN_TEXTAREA' => 'Le parole poco rilevanti devono essere separate da una virgola.<br>Queste parole avranno una minor rilevanza nell\'algoritmo di ricerca.',

	'LANGUAGE_CHOOSE' => 'Seleziona la lingua',
	'LANGUAGE_CHOOSE_EXPLAIN' => 'Seleziona la lingua per la quale desideri aggiungere parole a bassa rilevanza.',
	'TC_LANG_CREATE_EXPLAIN' => 'Aggiungi la lingua del tuo forum per creare una nuova lista di parole a bassa rilevanza.',
	'TC_LANG_CREATE' => 'Aggiungi una nuova lingua',

	'WHAT_IS_TC_FOR' => 'A cosa serve Topic Title Duplicate Checker?',
	'WHAT_IS_TC_FOR_EXPLAIN' => '<strong>Topic Title Duplicate Checker</strong> aiuta gli utenti a evitare di creare topic duplicati.<br />Mentre un utente sta creando un nuovo topic ed inserendo un nuovo titolo, Topic Checker cerca automaticamente i topic esistenti con titoli simili e mostra i risultati della ricerca in tempo reale.<br />In questo modo, l\'utente può vedere se il topic che sta per creare è già stato trattato nel forum, riducendo così la creazione di topic duplicati.<br />Ogni utente userà le parole poco rilevanti definite per la sua lingua selezionata nelle impostazioni utente.',

	'TC_STEP_1_ADD_LANG' => 'Step 1: Aggiungi la tua lingua',
	'TC_STEP_2_CHOOSE_WORDS' => 'Step 2: Aggiungi parole comuni, con poca rilevanza di ricerca (opzionale)',
	'TC_STEP_3_CHOOSE_FORUMS' => 'Step 3: Scegli i forum dove abilitare Topic Title Duplicate Checker',
	'LANGUAGE_ALREADY_EXISTS' => 'La lingua selezionata esiste già nel database.',
	'LANGUAGE_ADDED_SUCCESS' => 'La nuova lingua è stata aggiunta correttamente.',
	'NO_LANGUAGE_SELECTED' => 'Nessuna lingua selezionata.',

]);
