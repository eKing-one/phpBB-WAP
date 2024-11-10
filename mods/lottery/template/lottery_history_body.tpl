<div class="navbar"><a href="{U_INDEX}">{L_INDEX}</a>&gt;<a href="index.php">彩票</a>&gt;历史记录</div>
<form method="post" action="{S_MODE_ACTION}">
	<div class="catSides">
		<span class="cattitle">历史记录</span>
	</div>
	<!-- BEGIN listrow -->
		<div class="{listrow.ROW_CLASS}">
			{listrow.HISTORY_NUM}.<br />
			<b>{L_WINNER}</b>：{listrow.HISTORY_WINNER}<br />
			<b>{L_AMOUNT_WON}</b>：{listrow.HISTORY_AMOUNT} {listrow.HISTORY_CURRENCY}<br />
			<b>{L_TIME_WON}</b>：{listrow.HISTORY_TIME}
		</div>
	<!-- END listrow -->
	<!-- BEGIN switch_no_history -->
		<div class="row1">{switch_no_history.MESSAGE}</div>
	<!-- END switch_no_history -->
	<!-- BEGIN switch_title_info -->
		{L_TOTAL_HISTORY}<br />
		{PAGE_NUMBER}
		{PAGINATION}
	<!-- END switch_title_info -->
</form>
<!-- BEGIN switch_last_winner -->
<div class="row1">
	{L_LAST_WINNER}：<b>{switch_last_winner.WINNER_NAME}</b>
</div>
<!-- END switch_last_winner -->
 

<!-- BEGIN switch_are_actions -->
<form method="post" action="{S_MODE_CONFIG}">
	<input type="hidden" name="action" value="options" />
	<div class="catSides">
		<span class="cattitle">{L_ACTIONS_TITLE}</span>
	</div>
<!-- END switch_are_actions -->
<!-- BEGIN switch_tickets_single -->
	<div class="row1">
		<input type="submit" name="buy_ticket" value="{I_BUY_TICKET}" class="liteoption" />
	</div>
<!-- END switch_tickets_single -->
<!-- BEGIN switch_tickets_multi -->
	<div class="row1">
		<input type="text" name="amount" size="5" maxlength="5" value="1" class="post" />
		<input type="submit" name="buy_tickets" value="{I_BUY_TICKETS}" class="liteoption" />
	</div>
<!-- END switch_tickets_multi -->
<!-- BEGIN switch_view_history -->
	<div class="row1">
		<input type="submit" name="view_history" value="{I_VIEW_HISTORY}" class="liteoption" />
	</div>
<!-- END switch_view_history -->
<!-- BEGIN switch_view_personal -->
	<div class="row1">
		<input type="submit" name="view_personal" value="{I_VIEW_PHISTORY}" class="liteoption" />
	</div>
<!-- END switch_view_personal -->
<!-- BEGIN switch_are_actions -->
</form>
<!-- END switch_are_actions -->