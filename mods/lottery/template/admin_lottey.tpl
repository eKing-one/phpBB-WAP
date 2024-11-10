<div class="navbar"><a href="{U_INDEX}">{L_INDEX}</a>&gt;<a href="{U_ADMIN}">{L_ADMIN}</a>&gt;<a href="{U_MODS_ADMIN}">{L_MODS_ADMIN}</a>&gt;彩票管理</div>
<span class="genmed">{EXPLAIN}
<div class="catSides">
	<span class="cattitle">{L_TABLE_TITLE}</span>
</div>
<div class="row1">
	{L_ENTRIES_TOTAL}：{L_TOTAL_ENTRIES}
</div>
<div class="row1">
	{L_DURATION_LEFT}：{L_DURATION}
</div>
<div class="row1">
	{L_LEFT_TIME}：{L_TIME_LEFT} [{L_SECONDS}]
</div>
<div class="row1">
	{L_LOTTERY_POOL}：{L_POOL}
</div>
<div class="row1">
	{L_WON_LAST}：{L_LAST_WON}
</div>
<form action="{S_CONFIG_ACTION}" method="post">
	<input type="hidden" name="action" value="update" />
	<div class="catSides">
		<span class="cattitle">{L_CONFIG_TITLE}</span>
	</div>
	<div class="row1">
		{L_LOTTERY_STATUS}：<br />
		<select name="lottery_status">
			<option value="1">{L_ON}</option>
			<option value="0" {L_STATUS_OFF}>{L_OFF}</option>
		</select>
	</div>
	<div class="row1">
		{L_AUTO_RESTART}：<br />
		<select name="lottery_reset">
			<option value="1">{L_ON}</option>
			<option value="0" {L_RESET_OFF}>{L_OFF}</option>
		</select>
	</div>
	<div class="row1">
		{L_NAME}：<br />
		<input type="text" name="lottery_name" size="32" value="{V_L_NAME}" maxlength="32" class="post" />
	</div>
	<div class="row1">
		{L_BASE_AMOUNT}：<br />
		<input type="text" name="lottery_base" size="10" value="{V_L_BASE}" maxlength="15" class="post" />
	</div>
	<div class="row1">
		{L_ENTRY_COST}：<br />
		<input type="text" name="lottery_cost" size="10" value="{V_L_COST}" maxlength="15" class="post" />
	</div>
	<div class="row1">
		{L_DRAW_PERIODS}：<br />
		<input type="text" name="lottery_length" value="{V_L_LENGTH}" size="10" maxlength="15" class="post" />秒
	</div>
	<div class="row1">
		{L_TICKETS_ALLOWED}：<br />
		<select name="lottery_ticktype">
			<option value="single">{L_SINGLE}</option>
			<option value="multiple" {L_TICKTYPE_MULT}>{L_MULTIPLE}</option>
		</select>
	</div>
	<div class="row1">
		{L_MULT_TICKETS}：<br />
		<select name="lottery_mb">
			<option value="1">{L_ON}</option>
			<option value="0" {L_MULTI_TICKETS_OFF}>{L_OFF}</option>
		</select>
	</div>
	<div class="row1">
		{L_MULT_TICKETS} [{L_MAX}]：<br />
		<input type="text" name="lottery_mb_amount" value="{L_MULTI_TICKETS_MAX}" size="10" maxlength="15" class="post" />
	</div>
	<div class="row1">
		{L_FULL_DISPLAY}：<br />
		<select name="lottery_show_entries">
			<option value="1">{L_ON}</option>
			<option value="0" {L_DISPLAY_OFF}>{L_OFF}</option>
		</select>
	</div>
	<div class="row1">
		{L_ITEM_POOL}：<br />
		<select name="lottery_items">
			<option value="1">{L_ON}</option>
			<option value="0" {L_ITEMS_OFF}>{L_OFF}</option>
		</select>
	</div>
	<div class="row1">
		{L_HISTORY}：<br />
		<select name="lottery_history">
			<option value="1">{L_ON}</option>
			<option value="0" {L_HISTORY_OFF}>{L_OFF}</option>
		</select>
	</div>
	<!-- BEGIN switch_cash -->
	<div class="row1">
		{L_CURRENCY}：<br />
		<select name="lottery_currency">
			<!-- END switch_cash -->
				<!-- BEGIN cash_listrow -->
					<option value="{cash_listrow.CASH_NAME}" {cash_listrow.SELECTED}>{cash_listrow.CASH_NAME}</option>
				<!-- END cash_listrow -->
			<!-- BEGIN switch_cash -->
		</select>
	</div>
	<!-- END switch_cash -->
	<input type="submit" value="{L_UPDATE}" name="Update" class="mainoption">
</form>