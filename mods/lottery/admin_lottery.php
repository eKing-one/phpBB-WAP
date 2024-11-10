<?php
/***************************************************************************
 *                               admin_lottery.php
 *                            -------------------
 *                    官方网站：http://phpbb-wap.com
 *                    程序作者：爱疯的云
 *                    ＱＱ联系：53109774
 *                    电子邮件：zhangyunwap@qq.com
 *                    最后修改：2011-08-01
 *                    文件说明：彩票(admin)
 *                               
 ***************************************************************************/

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['游戏插件']['彩票游戏'] = "$file";
	return;
}

define('IN_PHPBB', 1);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../../";
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);
include($phpbb_root_path . 'mods/includes/constants.' . $phpEx);
include($phpbb_root_path . 'mods/includes/function_duration.' . $phpEx);
include($phpbb_root_path . 'language/lang_chinese/mods/lang_lottery.' . $phpEx);

#
# Register action variable!
#

if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }

$thetime = time();

#
# Main lottery pages
#
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'lottery_config_body.tpl')
	);

	$sql = "SELECT *
		FROM " . LOTTERY_TABLE . "
		WHERE id > 0";
	if ( !($result = $db->sql_query($sql)) )
	{
		trigger_error(sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	$time_left = ( $board_config['lottery_start'] ) ? ($board_config['lottery_start'] + $board_config['lottery_length']) - $thetime : '-1';
	$duration_left = duration($time_left);

	$pool = ($sql_count * $board_config['lottery_cost']) + $board_config['lottery_base'];
	$total_entries = $sql_count;

	#
	# Begin Items listing for addition to prize pool
	# ONLY do this if the shop items are enabled, incase there is no shop!
	#
	if ( $board_config['lottery_items'] && defined('SHOP_TABLE') )
	{
		$sql = "SELECT id, name
			FROM " . SHOP_ITEMS_TABLE . "
			ORDER BY `name`";
		if ( !($result = $db->sql_query($sql)) )
		{
			trigger_error(sprintf($lang['lottery_error_selecting'], 'shop_items'), '', __LINE__, __FILE__, $sql);
		}

		$sql_count = $db->sql_numrows($result);

		if ( $sql_count < 1 )
		{
			#
			# Code to toggle no history!
			#
			$template->assign_block_vars('switch_no_items', array(
			'MESSAGE' => $lang['lottery_no_items']));
		}
		else
		{
			#
			# Loop over the items in the DB and add them to a drop down after RANDOM item!
			#
			for ($i = 0; $i < $sql_count; $i++)
			{
				if (!( $row = $db->sql_fetchrow($result) ))
				{
					trigger_error(sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
				}

				$template->assign_block_vars('item_listrow', array(
					'ITEM_ID' => $row['id'],
					'ITEM_NAME' => $row['name']
				));
			}

			$template->assign_block_vars('switch_are_items', array());
		}

		#
		# Begin items listing for items already in prize pool!
		# ONLY do this if the shop items are enabled, incase there is no shop!
		#
		$item_array = explode(';', $board_config['lottery_win_items']);
		$item_count = count($item_array);

		if ( $item_count && (!empty($item_array[0])) )
		{
			for ($i = 0; $i < $item_count; $i++)
			{
				$item_array[$i] = ( $item_array[$i] == "random" ) ? $lang['lottery_rand'] : $item_array[$i];

				$template->assign_block_vars('pool_listrow', array(
					'ITEM_NAME' => $item_array[$i]
				));
			}

			$template->assign_block_vars('switch_pool_items', array());
		}

		#
		# Begin listing of all shops. This is for the RAND settings!
		# 
		$sql = "SELECT *
			FROM " . SHOP_TABLE . "
			ORDER BY `shopname`";
		if ( !($result = $db->sql_query($sql)) )
		{
			trigger_error(sprintf($lang['lottery_error_selecting'], 'shops'), '', __LINE__, __FILE__, $sql);
		}

		if ( $sql_count = $db->sql_numrows($result) )
		{
			#
			# Loop over the shops list results!
			#
			for ($i = 0; $i < $sql_count; $i++)
			{
				if (!( $row = $db->sql_fetchrow($result) ))
				{
					trigger_error(sprintf($lang['lottery_error_selecting'], 'shop'), '', __LINE__, __FILE__, $sql);
				}

				$shop_selected = ( $board_config['lottery_random_shop'] == $row['shopname'] ) ? 'SELECTED' : '';

				$template->assign_block_vars('rand_listrow', array(
					'SHOP_NAME' => $row['shopname'],
					'SELECTED' => $shop_selected
				));
			}

		}

		$template->assign_block_vars('switch_enabled_items', array());
	}

	#
	# Begin Cash checks & loop
	#
	# Begin Cash checks & loop
	#
	if ( DEFINED('CASH_TABLE') )
	{
		$sql = "SELECT *
			FROM " . CASH_TABLE;

		if ( !($result = $db->sql_query($sql)) )
		{
			trigger_error(sprintf($lang['lottery_error_selecting'], 'cash'), '', __LINE__, __FILE__, $sql);
		}
		if ( ($sql_count = $db->sql_numrows($result)) && ($sql_count > 0) )
		{
			for ($i = 0; $i < $sql_count; $i++)
			{
				if (!( $cash_row = $db->sql_fetchrow($result) ))
				{
					trigger_error(sprintf($lang['lottery_error_selecting'], 'cash'), '', __LINE__, __FILE__, $sql);
				}

				$selected = ( $cash_row['cash_name'] = $board_config['lottery_currency'] ) ? 'SELECTED' : '';

				$template->assign_block_vars('cash_listrow', array(
					'CASH_NAME' => $cash_row['cash_name'],
					'SELECTED' => $shop_selected
				));
			}
			$template->assign_block_vars('switch_cash', array());
		}
	}


	#
	# Grab last winner from lottery -- ORDERED BY TIME
	#
	$sql = "SELECT t1.*, t2.username
		FROM " . LOTTERY_HISTORY_TABLE . " t1, " . USERS_TABLE . " t2
		WHERE t2.user_id = t1.user_id
		ORDER BY time DESC";

	if ( !($result = $db->sql_query($sql)) )
	{
		trigger_error(sprintf($lang['lottery_error_selecting'], 'lottery history'), '', __LINE__, __FILE__, $sql);
	}

	if ( $db->sql_numrows($result) > 0 )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			trigger_error(sprintf($lang['lottery_error_selecting'], 'lottery'), '', __LINE__, __FILE__, $sql);
		}
		$last_won = $row['username'];
	}
	else { $last_won = $lang['lottery_no_one']; }

	#
	# Begin lottery SELECTion setups!
	#
	$s_status_off = ( $board_config['lottery_status'] ) ? '' : 'SELECTED';
	$s_reset_off = ( $board_config['lottery_reset'] ) ? '' : 'SELECTED';
	$s_ticktype_mult = ( $board_config['lottery_ticktype'] == 'single' ) ? '' : 'SELECTED';
	$s_items_off = ( $board_config['lottery_items'] ) ? '' : 'SELECTED';
	$s_history_off = ( $board_config['lottery_history'] ) ? '' : 'SELECTED';
	$s_display_off = ( $board_config['lottery_show_entries'] ) ? '' : 'SELECTED';
	$s_multi_tickets_off = ( $board_config['lottery_mb'] ) ? '' : 'SELECTED';
	
	$template->assign_vars(array(
		'V_L_NAME' => $board_config['lottery_name'],
		'V_L_BASE' => $board_config['lottery_base'],
		'V_L_COST' => $board_config['lottery_cost'],
		'V_L_LENGTH' => $board_config['lottery_length'],
		'L_MULTI_TICKETS_MAX' => $board_config['lottery_mb_amount'],
		'L_RAND_COST_MIN' => $board_config['lottery_item_mcost'],
		'L_RAND_COST_MAX' => $board_config['lottery_item_xcost'],

		'L_STATUS_OFF' => $s_status_off,
		'L_RESET_OFF' => $s_reset_off,
		'L_TICKTYPE_MULT' => $s_ticktype_mult,
		'L_ITEMS_OFF' => $s_items_off,
		'L_HISTORY_OFF' => $s_history_off,
		'L_DISPLAY_OFF' => $s_display_off,
		'L_MULTI_TICKETS_OFF' => $s_multi_tickets_off,

		'L_LAST_WON' => $last_won,
		'L_TOTAL_ENTRIES' => $total_entries,
		'L_POOL' => $pool,
		'L_DURATION' => $duration_left,
		'L_TIME_LEFT' => $time_left,
		'L_TABLE_TITLE' => $lang['lottery_statistics'],
		'L_CONFIG_TITLE' => $lang['lottery_edit_settings'],
		'L_ENTRIES_TOTAL' => $lang['lottery_total_entries'],
		'L_DURATION_LEFT' => $lang['lottery_duration_left'],
		'L_LEFT_TIME' => $lang['lottery_time_left'],
		'L_SECONDS' => $lang['lottery_seconds'],
		'L_LOTTERY_POOL' => $lang['lottery_pool'],
		'L_WON_LAST' => $lang['lottery_last_won'],
		'L_LOTTERY_STATUS' => $lang['lottery_status'],
		'L_AUTO_RESTART' => $lang['lottery_auto_restart'],
		'L_NAME' => $lang['lottery_name'],
		'L_BASE_AMOUNT' => $lang['lottery_base_amount'],
		'L_ENTRY_COST' => $lang['lottery_entry_cost'],
		'L_DRAW_PERIODS' => $lang['lottery_draw_periods'],
		'L_TICKETS_ALLOWED' => $lang['lottery_tickets_allowed'],
		'L_SINGLE' => $lang['lottery_single'],
		'L_MULTIPLE' => $lang['lottery_multiple'],
		'L_MULT_TICKETS' => $lang['lottery_mult_tickets'],
		'L_ON' => $lang['lottery_on'],
		'L_OFF' => $lang['lottery_off'],
		'L_MAX' => $lang['lottery_max'],
		'L_FULL_DISPLAY' => $lang['lottery_full_display'],
		'L_ITEM_POOL' => $lang['lottery_item_pool'],
		'L_HISTORY' => $lang['lottery_history'],
		'L_CURRENCY' => $lang['lottery_currency'],
		'L_UPDATE' => $lang['lottery_update'],
		'L_FROM_SHOP' => $lang['lottery_from_shop'],
		'L_ALL_SHOPS' => $lang['lottery_all_shops'],
		'L_MIN_COST' => $lang['lottery_min_cost'],
		'L_MAX_COST' => $lang['lottery_max_cost'],
		'L_UPDATE_SETTINGS' => $lang['lottery_update_settings'],
		'L_CURRENT_ITEMS' => $lang['lottery_current_items'],
		'L_REMOVE_ITEM' => $lang['lottery_remove_item'],
		'L_ADD_ITEMS' => $lang['lottery_add_items'],
		'L_RANDOM' => $lang['lottery_rand'],
		'L_ADD_ITEM' => $lang['lottery_add_item'],
		'L_ITEMS_TITLE' => $lang['lottery_items_table'],
		'L_RAND_ITEMS_TITLE' => $lang['lottery_items_settings'],

		'S_CONFIG_ACTION' => append_sid('admin_lottery.' . $phpEx),
		'TITLE' => $lang['lottery_editor'],
		'EXPLAIN' => $lang['lottery_index_explain'])
	);
	
}

elseif ( $action == 'update')
{
	#
	# Main Update Function (Reused from the shop mod!)
	#
	$getarray = array('lottery_status', 'lottery_name', 'lottery_base', 'lottery_cost', 'lottery_length', 'lottery_ticktype', 'lottery_reset', 'lottery_mb', 'lottery_mb_amount', 'lottery_show_entries', 'lottery_items', 'lottery_history', 'lottery_currency');
	$int_array = array('lottery_status', 'lottery_base', 'lottery_cost', 'lottery_length', 'lottery_reset', 'lottery_mb', 'lottery_mb_amount', 'lottery_show_entries', 'lottery_items', 'lottery_history');

	$getarraynum = count($getarray);



	for ($i = 0; $i < $getarraynum; $i++)

	{
		// Dynamically fetch POST and GET data
		if( isset($HTTP_GET_VARS[$getarray[$i]]) || isset($HTTP_POST_VARS[$getarray[$i]]) ) { ${$getarray[$i]} = ( isset($HTTP_POST_VARS[$getarray[$i]]) ) ? $HTTP_POST_VARS[$getarray[$i]] : $HTTP_GET_VARS[$getarray[$i]]; }
		else { ${$getarray[$i]} = ''; }

		// intval variable if it should be an int!
		${$getarray[$i]} = ( in_array($getarray[$i], $int_array) ) ? intval(${$getarray[$i]}) : ${$getarray[$i]};

		if ( $board_config[$getarray[$i]] != ${$getarray[$i]} )
		{

			$sql = "UPDATE " . CONFIG_TABLE . "
				SET config_value = '" . ${$getarray[$i]} . "'
				WHERE config_name = '$getarray[$i]'";

			if ( !($db->sql_query($sql)) ) { message_die(CRITICAL_ERROR, 'ERROR: Updating Global Variables!'); }

		}
	}


	if ( $status && !($board_config['lottery_status']) )
	{  
		$usql = "UPDATE ". CONFIG_TABLE . "
			SET config_value = '$thetime'
			WHERE config_name = 'lottery_start'"; 
	}
	elseif ( !($status) && $board_config['lottery_status'] )
	{ 
		$usql = "UPDATE ". CONFIG_TABLE . "
			SET config_value = '0'
			WHERE config_name = 'lottery_start'"; 
	}
	if ( $usql )
	{
		if ( !($db->sql_query($usql)) )
		{
			trigger_error(sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}
	}

	trigger_error($lang['lottery_updated'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
}

elseif ( $action == 'item_pool' )
{
	if ( isset($HTTP_GET_VARS['del_item']) || isset($HTTP_POST_VARS['del_item']) ) { $del_item = ( isset($HTTP_POST_VARS['del_item']) ) ? $HTTP_POST_VARS['del_item'] : $HTTP_GET_VARS['del_item']; }
	if ( isset($HTTP_GET_VARS['add_item']) || isset($HTTP_POST_VARS['add_item']) ) { $add_item = ( isset($HTTP_POST_VARS['add_item']) ) ? $HTTP_POST_VARS['add_item'] : $HTTP_GET_VARS['add_item']; }

	if ( !(defined('SHOP_TABLE')) ) { trigger_error('Shop is not installed!'); }

	if ( !empty($del_item) )
	{
		if ( isset($HTTP_GET_VARS['item_name']) || isset($HTTP_POST_VARS['item_name']) ) { $item_id = ( isset($HTTP_POST_VARS['item_name']) ) ? $HTTP_POST_VARS['item_name'] : $HTTP_GET_VARS['item_name']; }
		else { trigger_error("Cannot read item_name variable!"); }
		$item_id = ( $item_id == 'Random' ) ? 'random' : stripslashes($item_id);

		if ( ( substr($board_config['lottery_win_items'], 0, strlen($item_id)) == $item_id  ) && ( ( substr($board_config['lottery_win_items'], strlen($item_id), 1) == ';' ) || ( substr($board_config['lottery_win_items'], strlen($item_id), 1) == '' ) ) )
		{
			$lottery_items = substr_replace($board_config['lottery_win_items'], "", 0, strlen($item_id . ";"));
		}
		else
		{
			$lottery_items = substr_replace($board_config['lottery_win_items'], "", strpos($board_config['lottery_win_items'], ';' . $item_id), strlen(';' . $item_id));
		}		

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . str_replace("'", "''", $lottery_items) . "'
			WHERE config_name = 'lottery_win_items'";
		if ( !($result = $db->sql_query($sql)) )
		{
			trigger_error(sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}

		trigger_error($lang['lottery_item_removed'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
	}
	elseif ( !empty($add_item) )
	{
		if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); }
		else { trigger_error("Cannot read item_id variable!"); }

		if ( $item_id != -1 )
		{
			$sql = "SELECT *
				FROM " . SHOP_ITEMS_TABLE . "
				WHERE id = $item_id";

			if ( !($result = $db->sql_query($sql)) )
			{
				trigger_error(sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
			}

			if ( !($db->sql_numrows($result)) )
			{
				trigger_error($lang['lottery_no_item']);
			}

			if (!( $row = $db->sql_fetchrow($result) ))
			{
				trigger_error(sprintf($lang['lottery_error_selecting'], 'shop items'), '', __LINE__, __FILE__, $sql);
			}

			$lottery_items = ( empty($board_config['lottery_win_items']) ) ? $row['name'] : $board_config['lottery_win_items'] . ';' . $row['name'];
		}
		else
		{
			$lottery_items = ( empty($board_config['lottery_win_items']) ) ? 'random' : $board_config['lottery_win_items'] . ';random';
		}

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . str_replace("'", "''", $lottery_items) . "'
			WHERE config_name = 'lottery_win_items'";
		if ( !($result = $db->sql_query($sql)) )
		{
			trigger_error(sprintf($lang['lottery_error_updating'], 'config'), '', __LINE__, __FILE__, $sql);
		}

		trigger_error($lang['lottery_item_added'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
	}

	else { trigger_error($lang['lottery_invalid_action']); }
}

elseif ( $action == 'rand_settings' )
{
	if ( !(defined('SHOP_TABLE')) ) { trigger_error('Shop is not installed!'); }

	#
	# Main Update Function (Reused from the shop mod!)
	#
	$getarray = array('lottery_item_mcost', 'lottery_item_xcost', 'lottery_random_shop');
	$int_array = array('lottery_item_mcost', 'lottery_item_xcost');

	$getarraynum = count($getarray);



	for ($i = 0; $i < $getarraynum; $i++)

	{
		// Dynamically fetch POST and GET data
		if( isset($HTTP_GET_VARS[$getarray[$i]]) || isset($HTTP_POST_VARS[$getarray[$i]]) ) { ${$getarray[$i]} = ( isset($HTTP_POST_VARS[$getarray[$i]]) ) ? $HTTP_POST_VARS[$getarray[$i]] : $HTTP_GET_VARS[$getarray[$i]]; }
		else { ${$getarray[$i]} = ''; }

		// intval variable if it should be an int!
		${$getarray[$i]} = ( in_array($getarray[$i], $int_array) ) ? intval(${$getarray[$i]}) : ${$getarray[$i]};

		if ( $board_config[$getarray[$i]] != ${$getarray[$i]} )
		{

			$sql = "UPDATE " . CONFIG_TABLE . "
				SET config_value = '" . ${$getarray[$i]} . "'
				WHERE config_name = '$getarray[$i]'";

			if ( !($db->sql_query($sql)) ) { message_die(CRITICAL_ERROR, 'ERROR: Updating Global Variables!'); }

		}
	}

	trigger_error($lang['lottery_random_items_updated'] . "<br /><br />" . sprintf($lang['lottery_click_to_return'], '<a href="' . append_sid('admin_lottery.' . $phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>"));
}

else { trigger_error($lang['lottery_invalid_action']); }


//
// Generate the page
//
$template->pparse('body');

include('page_footer_mods.' . $phpEx);

?>