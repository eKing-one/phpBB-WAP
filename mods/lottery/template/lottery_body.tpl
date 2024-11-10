<div class="navbar"><a href="{U_INDEX}">{L_INDEX}</a>&gt;彩票</div>
<div class="catSides">
	<span class="cattitle">{L_INFO_TITLE}</span>
</div>
<div class="row1">
	{L_TICKET_OWNED}：{TICKETS_OWNED}
</div>
<div class="row1">
	{L_TICKETS_COST}：{L_TICKET_COST} {L_CURRENCY}
</div>
<div class="row1">
	{L_BASE_POOL}：{L_PRIZE_BASE} {L_CURRENCY}
</div>
<!-- BEGIN switch_full_display -->
	<div class="row1">
		{L_CURRENT_POOL}：{L_CURRENT_ENTRIES}
	</div>
	<div class="row1">
		{L_TOTAL_POOL}：{L_TOTAL_PRIZE} {L_CURRENCY}
	</div>
<!-- END switch_full_display -->
<!-- BEGIN switch_items -->
	<div class="row1">
		{L_ITEM_DRAW}：{L_ITEM_PRIZE}
	</div>
<!-- END switch_items -->
<div class="row1">
	{L_TIME_DRAW}：{L_DURATION}
</div>
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
  