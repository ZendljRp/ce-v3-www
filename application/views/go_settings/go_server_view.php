<?php
############################################################################################
####  Name:             go_server_view.php                                              ####
####  Type: 		    ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

if (! $isAdvance)
	$isAdvance = 0;
?>
<script>
$(function()
{
	var isAdvance = <?php echo $isAdvance; ?>;
	if (isAdvance)
	{
		$('.advance_settings').show();
		$('#advance_link').html('[ - <? echo strtoupper($this->lang->line("go_adv_settings")); ?> ]');
		$('#isAdvance').val('1');
	}
	
	$('#hiddenToggle').html('<?=$server_info->server_id ?>');
	
    $('.toolTip').tipTip();

	$('#advance_link').click(function()
	{
		if ($('.advance_settings').is(':hidden'))
		{
			$('.advance_settings').show();
			$('#advance_link').html('[ - <? echo strtoupper($this->lang->line("go_adv_settings")); ?> ]');
			$('#isAdvance').val('1');
		} else {
			$('.advance_settings').hide();
			$('#advance_link').html('[ + <? echo strtoupper($this->lang->line("go_adv_settings")); ?> ]');
			$('#isAdvance').val('0');
		}
	});

	$('#advance_link').hover(function()
	{
		$(this).css('color','#F00');
	},
	function()
	{
		$(this).css('color','#000');
	});
	
	$('#server_description,#server_ip').keydown(function(event)
	{
		$(this).css('border','solid 1px #999');
	});
	
	$("#conf_secret").keyup(function(e)
	{
		var pwd_field = $(this);
		var pwd_span = $("#testSpan");

		var strong_regex = new RegExp( "^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])", "g" );
		var medium_regex = new RegExp( "^(?=.{6,})(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9]))).*$", "g" );

		if (strong_regex.test(pwd_field.val())) 
			{
			if (!pwd_span.html().match(/Strong/))
				{pwd_span.html("<small style=\"color:green\"><? echo strtoupper($this->lang->line("go_strong")); ?></small>");}
			} 
		else if (medium_regex.test(pwd_field.val())) 
			{
			if (!pwd_span.html().match(/Medium/))
				{pwd_span.html("<small style=\"color:orange\"><? echo strtoupper($this->lang->line("go_medium")); ?></small>");}
			}
		else 
			{
			if (!pwd_span.html().match(/Weak/))
				{pwd_span.html("<small style=\"color:red\"><? echo strtoupper($this->lang->line("go_weak")); ?></small>");}
			}
	});
	
	// Submit Settings
	$('#saveSettings').click(function()
	{
		var isEmpty = 0;
		if ($('#server_description').val() === "")
		{
			$('#server_description').css('border','solid 1px red');
			isEmpty = 1;
		}
		if ($('#server_ip').val() === "" || $('#server_ip').val().length < 7)
		{
			$('#server_ip').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("<? echo $this->lang->line("go_server_id_navailable"); ?>");
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			var items = $('#modifyServer').serialize();
			$.post("<?=$base?>index.php/go_servers_ce/go_server_wizard", { items: items, action: "modify_server" },
			function(data){
				if (data=="SUCCESS")
				{
					alert("<? echo $this->lang->line("go_success_caps"); ?>");
					
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
			});
		}
	});
	
	$.validator.addMethod('IP4Checker', function(value) {
	var ip = "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$";
		return value.match(ip);
	}, ' <small style="color:red;"><? echo $this->lang->line("go_invalid_ip_add"); ?></small>');

	$('#modifyServer').validate({
		rules: {
			server_ip: {
				IP4Checker: true
			}
		}
	});
	
	var carShow = 0;
	var carCnt = <?php echo count($server_carriers); ?>;
	$('#carrier_list').click(function()
	{
		if (!carShow)
		{
			carShow = 1;
			$('#carrier_list_table').slideDown("slow");
			$('#carrier_list').html('- <? echo $this->lang->line("go_carriers_server"); ?>');
			if (carCnt > 10)
				$('#carrier_list_table').next().show();
		} else {
			carShow = 0;
			$('#carrier_list_table').slideUp();
			$('#carrier_list').html('+ <? echo $this->lang->line("go_carriers_server"); ?>');
			if (carCnt > 10)
				$('#carrier_list_table').next().hide();
		}
	});
	
	var phoShow = 0;
	var phoCnt = <?php echo count($server_phones); ?>;
	$('#phone_list').click(function()
	{
		if (!phoShow)
		{
			phoShow = 1;
			$('#phone_list_table').slideDown("slow");
			$('#phone_list').html('- <? echo $this->lang->line("go_phones_server"); ?>');
			if (phoCnt > 10)
				$('#phone_list_table').next().show();
		} else {
			phoShow = 0;
			$('#phone_list_table').slideUp();
			$('#phone_list').html('+ <? echo $this->lang->line("go_phones_server"); ?>');
			if (phoCnt > 10)
				$('#phone_list_table').next().hide();
		}
	});
	
	var conShow = 0;
	var conCnt = <?php echo count($server_conferences); ?>;
	$('#conference_list').click(function()
	{
		if (!conShow)
		{
			conShow = 1;
			$('#conference_list_table').slideDown("slow");
			$('#conference_list').html('- <? echo $this->lang->line("go_confer_server"); ?>');
			if (conCnt > 10)
				$('#conference_list_table').next().show();
		} else {
			conShow = 0;
			$('#conference_list_table').slideUp();
			$('#conference_list').html('+ <? echo $this->lang->line("go_confer_server"); ?>');
			if (conCnt > 10)
				$('#conference_list_table').next().hide();
		}
	});
	
	// Pagination
	var options = {
		optionsForRows : [10,25,50,100,"<? strtoupper($this->lang->line('go_all')); ?>"],
		rowsPerPage : 10
	}
	
	$('#carrier_list_table').tablePagination(options);
	$('#phone_list_table').tablePagination(options);
	$('#conference_list_table').tablePagination(options);
	
	$('#carrier_list_table').next().css('display','none');
	$('#phone_list_table').next().css('display','none');
	$('#conference_list_table').next().css('display','none');
	
	// Tool Tip
	$(".toolTip").tipTip();
});

function update_system(server,ip)
{
	$('#system_load').load('<? echo $base; ?>index.php/go_servers_ce/go_system_load/'+server+'/'+ip);
	$('#live_channels').load('<? echo $base; ?>index.php/go_servers_ce/go_live_channels/'+server+'/'+ip);
	$('#disk_usage').load('<? echo $base; ?>index.php/go_servers_ce/go_disk_usage/'+server+'/'+ip);
}

function update_divs()
{
	update_system('<?=$server_info->server_id ?>','<?=$server_info->server_ip ?>');
}

update_divs();
var intervalHandle = null;
intervalHandle = setInterval(update_divs, 5000);
</script>
<style>
.buttons {
	color:#7A9E22;
	cursor:pointer;
}

.buttons:hover{
	font-weight:bold;
}

#tablePagination {
	width: 95%;
	margin-left: auto;
	margin-right: auto;
}
</style>
<?php
switch ($type)
{
	case "modify":
		break;
	
	default:
?>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo $this->lang->line("go_modify_server"); ?>: <?php echo "{$server_info->server_id}"; ?></div>
<br />
<form id="modifyServer" method="POST">
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_server_id"); ?>:
		</td>
		<td>
		&nbsp;<span><?=$server_info->server_id ?></span>
		<?=form_hidden('server_id',$server_info->server_id,'id="server_id" maxlength="10" size="10"') ?>
		<span id="aloading"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_server_desc"); ?>:
		</td>
		<td>
		<?=form_input('server_description',$server_info->server_description,'id="server_description" maxlength="255" size="30"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_server_ip"); ?>:
		</td>
		<td>
		<?=form_input('server_ip',$server_info->server_ip,'id="server_ip" maxlength="15" size="20"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_active"); ?>:
		</td>
		<td>
		<?php
		$activeArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('active',$activeArray,$server_info->active,'id="active"');
		?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_system_load"); ?>:
		</td>
		<td>
		&nbsp;<span id="system_load"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_live_channels"); ?>:
		</td>
		<td>
		&nbsp;<span id="live_channels"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_disk_usage"); ?>:
		</td>
		<td>
		&nbsp;<span id="disk_usage"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_admin_user_group"); ?>:
		</td>
		<td>
		<?php
		$groupArray = array("---ALL---"=> strtoupper($this->lang->line("go_all_user_groups")));
		foreach ($user_groups as $group)
		{
			$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
		}
		echo form_dropdown('user_group',$groupArray,$carrier_info->user_group,'id="user_group"');
		?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_asterisk_version"); ?>
		</td>
		<td>
		<?=form_input('asterisk_version',$server_info->asterisk_version,'id="asterisk_version" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_max_trunks"); ?>:
		</td>
		<td>
		<?=form_input('max_vicidial_trunks',$server_info->max_vicidial_trunks,'id="max_vicidial_trunks" maxlength="4" size="5"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_max_call_per_second"); ?>:
		</td>
		<td>
		<?=form_input('outbound_calls_per_second',$server_info->outbound_calls_per_second,'id="outbound_calls_per_second" maxlength="4" size="5"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_bal_dialing"); ?>:
		</td>
		<td>
		<?php
		$activeArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('vicidial_balance_active',$activeArray,$server_info->vicidial_balance_active,'id="vicidial_balance_active"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_bal_rank"); ?>:
		</td>
		<td>
		<?=form_input('vicidial_balance_rank',$server_info->vicidial_balance_rank,'id="vicidial_balance_rank" maxlength="2" size="4"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_bal_offlimits"); ?>:
		</td>
		<td>
		<?=form_input('vicidial_balance_offlimits',$server_info->vicidial_balance_offlimits,'id="vicidial_balance_offlimits" maxlength="4" size="5"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_telnet_host"); ?>:
		</td>
		<td>
		<?=form_input('telnet_host',$server_info->telnet_host,'id="telnet_host" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_telnet_port'"); ?>:
		</td>
		<td>
		<?=form_input('telnet_port',$server_info->telnet_port,'id="telnet_port" maxlength="5" size="6"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_manager_user"); ?>:
		</td>
		<td>
		<?=form_input('ASTmgrUSERNAME',$server_info->ASTmgrUSERNAME,'id="ASTmgrUSERNAME" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_manager_secret"); ?>:
		</td>
		<td>
		<?=form_input('ASTmgrSECRET',$server_info->ASTmgrSECRET,'id="ASTmgrSECRET" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_manager_update_user"); ?>:
		</td>
		<td>
		<?=form_input('ASTmgrUSERNAMEupdate',$server_info->ASTmgrUSERNAMEupdate,'id="ASTmgrUSERNAMEupdate" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_manager_listen_user"); ?>:
		</td>
		<td>
		<?=form_input('ASTmgrUSERNAMElisten',$server_info->ASTmgrUSERNAMElisten,'id="ASTmgrUSERNAMElisten" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_manager_send_user"); ?>:
		</td>
		<td>
		<?=form_input('ASTmgrUSERNAMEsend',$server_info->ASTmgrUSERNAMEsend,'id="ASTmgrUSERNAMEsend" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_conf_file_secret"); ?>:
		</td>
		<td>
		<?=form_input('conf_secret',$server_info->conf_secret,'id="conf_secret" maxlength="20" size="20"') ?>
		&nbsp;<span id="testSpan"><small style="color:red"><? echo $this->lang->line("go_weak"); ?></small></span>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_local_gmt"); ?>:
		</td>
		<td>
		<?php
		$gmtArray = array(
			'12.75'=>'12.75','12.00'=>'12.00','11.00'=>'11.00','10.00'=>'10.00',
			'9.50'=>'9.50','9.00'=>'9.00','8.00'=>'8.00','7.00'=>'7.00',
			'6.50'=>'6.50','6.00'=>'6.00','5.75'=>'5.75','5.50'=>'5.50',
			'5.00'=>'5.00','4.50'=>'4.50','4.00'=>'4.00','3.50'=>'3.50',
			'3.00'=>'3.00','2.00'=>'2.00','1.00'=>'1.00','0.00'=>'0.00',
			'-1.00'=>'-1.00','-2.00'=>'-2.00','-3.00'=>'-3.00','-3.50'=>'-3.50',
			'-4.00'=>'-4.00','-5.00'=>'-5.00','-6.00'=>'-6.00','-7.00'=>'-7.00',
			'-8.00'=>'-8.00','-9.00'=>'-9.00','-10.00'=>'-10.00','-11.00'=>'-11.00','-12.00'=>'-12.00'
		);
		echo form_dropdown('local_gmt',$gmtArray,$server_info->local_gmt,'id="local_gmt"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_voicemal_dump_exten"); ?>:
		</td>
		<td>
		<?=form_input('voicemail_dump_exten',$server_info->voicemail_dump_exten,'id="voicemail_dump_exten" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_autodial_exten"); ?>:
		</td>
		<td>
		<?=form_input('answer_transfer_agent',$server_info->answer_transfer_agent,'id="answer_transfer_agent" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_default_context"); ?>:
		</td>
		<td>
		<?=form_input('ext_context',$server_info->ext_context,'id="ext_context" maxlength="20" size="20"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_system_performance_log"); ?>:
		</td>
		<td>
		<?php
		$sysPerfArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('sys_perf_log',$sysPerfArray,$server_info->sys_perf_log,'id="sys_perf_log"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_server_logs"); ?>:
		</td>
		<td>
		<?php
		$logsArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('vd_server_logs',$logsArray,$server_info->vd_server_logs,'id="vd_server_logs"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_agi_output"); ?>:
		</td>
		<td>
		<?php
		$agiArray = array('NONE'=>'NONE','STDERR'=>'STDERR','FILE'=>'FILE','BOTH'=>'BOTH');
		echo form_dropdown('agi_output',$agiArray,$server_info->agi_output,'id="agi_output"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_carrier_logging_active"); ?>:
		</td>
		<td>
		<?php
		$carrierLogArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('carrier_logging_active',$carrierLogArray,$server_info->carrier_logging_active,'id="carrier_logging_active"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_recording_web_link"); ?>:
		</td>
		<td>
		<?php
		$recArray = array('SERVER_IP'=>'SERVER_IP','ALT_IP'=>'ALT_IP','EXTERNAL_IP'=>'EXTERNAL_IP');
		echo form_dropdown('recording_web_link',$recArray,$server_info->recording_web_link,'id="recording_web_link"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_alternate_recording_server_ip"); ?>:
		</td>
		<td>
		<?=form_input('alt_server_ip',$server_info->alt_server_ip,'id="alt_server_ip" maxlength="100" size="30"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_external_server_ip"); ?>:
		</td>
		<td>
		<?=form_input('external_server_ip',$server_info->external_server_ip,'id="external_server_ip" maxlength="100" size="30"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_active_twin_server_ip"); ?>:
		</td>
		<td>
		<?=form_input('active_twin_server_ip',$server_info->active_twin_server_ip,'id="active_twin_server_ip" maxlength="15" size="16"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_active_agent_server"); ?>Active Asterisk Server:
		</td>
		<td>
		<?php
		$aArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('active_asterisk_server',$aArray,$server_info->active_asterisk_server,'id="active_asterisk_server"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_active_agent_server"); ?>:
		</td>
		<td>
		<?php
		$aArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('active_agent_login_server',$aArray,$server_info->active_agent_login_server,'id="active_agent_login_server"');
		?>
		</td>
	</tr class="advance_settings" style="display: none;">
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_generate_conf_files"); ?>:
		</td>
		<td>
		<?php
		$gArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('generate_vicidial_conf',$gArray,$server_info->generate_vicidial_conf,'id="generate_vicidial_conf"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_rebuild_conf_files"); ?>:
		</td>
		<td>
		<?php
		$rArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('rebuild_conf_files',$rArray,$server_info->rebuild_conf_files,'id="rebuild_conf_files"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_rebuild_moh"); ?>:
		</td>
		<td>
		<?php
		$mohArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('rebuild_music_on_hold',$mohArray,$server_info->rebuild_music_on_hold,'id="rebuild_music_on_hold"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_sounds_update"); ?>:
		</td>
		<td>
		<?php
		$sArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('sounds_update',$sArray,$server_info->sounds_update,'id="sounds_update"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:40%;height:10px;">
		<? echo $this->lang->line("go_recording_limit"); ?>:
		</td>
		<td>
		<?=form_input('vicidial_recording_limit',$server_info->vicidial_recording_limit,'id="vicidial_recording_limit" maxlength="6" size="8"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:center;width:40%;height:10px;" colspan="2">
		<? echo $this->lang->line("go_custom_dialplan_entry"); ?>:
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td colspan="2" style="text-align: center;">
			<?php
			$options = array(
			  'name'        => 'custom_dialplan_entry',
			  'value'       => "{$server_info->custom_dialplan_entry}",
			  'cols'		=> '65',
			  'rows'        => '5',
			  'style'       => 'resize: none;',
			);
			echo form_textarea($options);
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td><td>&nbsp;</td>
	</tr>
	<tr>
    	<td><span id="advance_link" style="cursor:pointer;font-size:9px;">[ + <? echo $this->lang->line("go_adv_settings"); ?> ]</span><input type="hidden" id="isAdvance" value="0" /></td><td style="text-align:right;"><span id="saveSettings" class="buttons"><? echo $this->lang->line("go_save_settings"); ?></span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
</form>
<span style="display: none">
<br style="font-size:9px;" />
<table border="0" cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:center;font-weight:bold;" colspan="2"><? echo strtoupper($this->lang->line("go_trunks_this_server")); ?></td>
	</tr>
</table>
<br />
<form>
<?php form_hidden('server_ip',$server_info->server_ip); ?>
<table border="0" cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:center;font-weight:bold;" colspan="2"><? echo $this->lang->line("go_add_new_server_tr"); ?></td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;"><? echo $this->lang->line("go_trunks"); ?>:</td>
		<td><?php echo form_input('dedicated_trunks',null,'id="dedicated_trunks" maxlength="4" size="6"'); ?></td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;"><? echo $this->lang->line("go_campaign"); ?>:</td>
		<td>
		<?php
		foreach ($allowed_campaigns as $audi)
		{
				$camp_array[$audi->campaign_id] = "{$audi->campaign_id} - {$audi->campaign_name}";
		}
		
		echo form_dropdown('campaign_id',$camp_array,null,'id="campaign_id"');
		?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;"><? echo $this->lang->line("go_restriction"); ?>:</td>
		<td><?php echo form_dropdown('trunk_restriction',array('MAXIMUM_LIMIT'=>'MAXIMUM LIMIT','OVERFLOW_ALLOWED'=>'OVERFLOW ALLOWED'),'id="trunk_restriction"'); ?></td>
	</tr>
	<tr>
		<td style="text-align:center;" colspan="2"><span id="addTrunks" class="buttons"><? echo strtoupper($this->lang->line("go_add_trunks")); ?></span></td>
	</tr>
</table>
</form>
</span>
<br style="font-size:9px;" />
<div id="carrier_list" style="float: left;padding-left: 20px;" class="buttons">+ <? echo $this->lang->line("go_carriers_within_server"); ?></div>
<table id="carrier_list_table" cellpadding="0" cellspacing="0" style="width: 95%;margin-left: auto;margin-right: auto;display: none;">
	<thead>
		<tr>
			<th style="white-space: nowrap;font-weight: bold;padding: 3px;"><? echo strtoupper($this->lang->line("go_carrier_id")); ?></th>
			<th style="white-space: nowrap;font-weight: bold;padding: 3px;"><? echo strtoupper($this->lang->line("go_name")); ?></th>
			<th style="white-space: nowrap;font-weight: bold;padding: 3px;"><? echo strtoupper($this->lang->line("go_registration")); ?></th>
			<th style="white-space: nowrap;font-weight: bold;padding: 3px;"><? echo strtoupper($this->lang->line("go_active")); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($server_carriers as $carrier)
{
	if ($x==0) {
		$bgcolor = "#E0F8E0";
		$x=1;
	} else {
		$bgcolor = "#EFFBEF";
		$x=0;
	}
	
	if (strlen($carrier->registration_string) > 40)
	{
		$registration = substr($carrier->registration_string,0,40);
	} else {
		$registration = $carrier->registration_string;
	}
	
	echo "<tr style='background-color:$bgcolor;'>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;padding:3px;\">{$carrier->carrier_id}</td>\n";
	echo "<td style=\"white-space: nowrap;border-top:#D0D0D0 dashed 1px;padding:3px;\">{$carrier->carrier_name}</td>\n";
	echo "<td style=\"white-space: nowrap;border-top:#D0D0D0 dashed 1px;padding:3px;cursor:pointer;\" class=\"toolTip\" title=\"{$carrier->registration_string}\">$registration</td>\n";
	echo "<td style=\"text-align: center;border-top:#D0D0D0 dashed 1px;padding:3px;\">{$carrier->active}</td>\n";
	echo "</tr>\n";
}
?>
	</tbody>
</table>
<br />
<div id="phone_list" style="float: left;padding-left: 20px;" class="buttons">+ <? echo $this->lang->line("go_phones_server"); ?></div>
<table id="phone_list_table" cellpadding="0" cellspacing="0" style="width: 95%;margin-left: auto;margin-right: auto;display: none;">
	<thead>
		<tr>
			<th style="white-space: nowrap;font-weight: bold;"><? echo strtoupper($this->lang->line("go_exten")); ?></th>
			<th style="white-space: nowrap;font-weight: bold;"><? echo strtoupper($this->lang->line("go_name")); ?></th>
			<th style="white-space: nowrap;font-weight: bold;"><? echo strtoupper($this->lang->line("go_active")); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($server_phones as $phone)
{
	if ($x==0) {
		$bgcolor = "#E0F8E0";
		$x=1;
	} else {
		$bgcolor = "#EFFBEF";
		$x=0;
	}
	
	echo "<tr style='background-color:$bgcolor;'>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;padding:3px;\">{$phone->extension}</td>\n";
	echo "<td style=\"white-space: nowrap;border-top:#D0D0D0 dashed 1px;padding:3px;\">{$phone->fullname}</td>\n";
	echo "<td style=\"text-align: center;border-top:#D0D0D0 dashed 1px;padding:3px;\">{$phone->active}</td>\n";
	echo "</tr>\n";
}
?>
	</tbody>
</table>
<br />
<div id="conference_list" style="float: left;padding-left: 20px;" class="buttons">+ <? echo $this->lang->line("go_conference_server"); ?></div>
<table id="conference_list_table" cellpadding="0" cellspacing="0" style="width: 95%;margin-left: auto;margin-right: auto;display: none;">
	<thead>
		<tr>
			<th style="white-space: nowrap;font-weight: bold;"><? echo strtoupper($this->lang->line("go_conference")); ?></th>
			<th style="white-space: nowrap;font-weight: bold;"><? echo strtoupper($this->lang->line("go_exten")); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($server_conferences as $conference)
{
	if ($x==0) {
		$bgcolor = "#E0F8E0";
		$x=1;
	} else {
		$bgcolor = "#EFFBEF";
		$x=0;
	}
	
	echo "<tr style='background-color:$bgcolor;'>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;padding:3px;\">{$conference->conf_exten}</td>\n";
	echo "<td style=\"white-space: nowrap;border-top:#D0D0D0 dashed 1px;padding:3px;\">{$conference->extension}</td>\n";
	echo "</tr>\n";
}
?>
	</tbody>
</table>
<?php
		break;
}
?>
<br style="font-size:30px;" />
