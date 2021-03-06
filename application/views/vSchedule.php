<script src="<?php echo base_url()?>assets/js/slimScroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/js/changePhoto.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/js/autoresize.jquery.min.js" type="text/javascript"></script>
<table cellpadding="0" cellspacing="0" border="0" id="mainTable">
	<tbody>
		<tr>
			<td valign="top" class="content ui-widget-content">
				<table class="homecontenttable" cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td>
							<div class="ui-widget-content">
								<div class="ui-widget-header widget-title">
									<form method="post" id="year_selector_form" action="<?=site_url('schedule')?>" style="display: inline;">
										<select id="year_selector" name="year_selector" style="font-weight: bold;font-size: 10px;padding: 0;margin: 0;">
											<?php for($i = date("Y",strtotime(NOW))+3; $i >= 2010; $i--):?>
											<option value="<?php echo $i?>" <?php echo $selected_year == $i ? 'selected="selected"' : ''?>><?=$i?></option>
											<?php endfor;?>
										</select>
									</form>
									Program Schedules
									<span class="floatright" style="font-weight: normal;font-size: 9px;margin-top: -1px;margin-right: 5px;">
										<?php if(userPrivilege('isAdmin') OR userPrivilege('program_setting')):?><a class="button programList">program list</a><?php endif;?>
										<?php if(userPrivilege('isAdmin') OR userPrivilege('download_schedule_pdf')):?><a class="button selectMonth">download pdf</a><?php endif;?>
									</span>
								</div>
								<div class="sidebar-content widget-content">
									<div style="width: 49.9%;float: left;">
										<?php foreach($right as $month):?>
										<div class="sidebar-container ui-widget-content">
											<div class="ui-widget-header widget-title">
											<?php echo $month['month']?>
											</div>
											<div class="sidebar-content widget-content">
												<table class="tableList" cellpadding="2" cellspacing="0" border="0" width="100%">
													<thead>
														<tr>
															<th width="30%">Program</th>
															<th>Date</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach($month['programs'] as $program):?>
														<tr class="<?php echo (strtotime(NOW) >= strtotime($program['start_date']) AND strtotime(NOW) <= strtotime($program['end_date'])) ? 'runningStyles' : ''?>">
															<td width="30%">
																<?php if(!empty($program['schedule_id'])):?>																
																	<a class="detailsProgramSchedule" id="<?php echo $program['schedule_id']?>"><b><?php echo $program['title'].' '.$program['batch'] ?></b></a>
																<?php elseif(isset($program['program_id']) AND !empty($program['program_id']) AND ((strtotime(NOW) >= strtotime($program['start_date']) AND strtotime(NOW) <= strtotime($program['end_date'])) OR userPrivilege('isAdmin'))):?>
																	<a class="updateOldSchedule" id="<?php echo $program['program_id']?>"><b><?php echo $program['title'].' '.$program['batch'] ?></b></a>
																<?php else:?>
																	<b><?php echo $program['title'].' '.$program['batch'] ?></b>
																<?php endif;?>
															</td>
															<td><?php echo $program['sessions']?></td>
														</tr>
														<?php endforeach;?>
													</tbody>
												</table>
											</div>
										</div>
										<?php endforeach;?>
									</div>
									<div style="width: 49.9%;float: right">	
										<?php foreach($left as $month):?>
										<div class="sidebar-container ui-widget-content">
											<div class="ui-widget-header widget-title">
											<?php echo $month['month']?>
											</div>
											<div class="sidebar-content widget-content">
												<table class="tableList" cellpadding="2" cellspacing="0" border="0" width="100%">
													<thead>
														<tr>
															<th width="30%">Program</th>
															<th>Date</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach($month['programs'] as $program):?>
														<tr class="<?php echo (strtotime(NOW) >= strtotime($program['start_date']) AND strtotime(NOW) <= strtotime($program['end_date'])) ? 'runningStyles' : ''?>">
															<td width="30%">																
																<?php if(!empty($program['schedule_id'])):?>																
																	<a class="detailsProgramSchedule" id="<?php echo $program['schedule_id']?>"><b><?php echo $program['title'].' '.$program['batch'] ?></b></a>
																<?php elseif(isset($program['program_id']) AND !empty($program['program_id']) AND ((strtotime(NOW) >= strtotime($program['start_date']) AND strtotime(NOW) <= strtotime($program['end_date'])) OR userPrivilege('isAdmin'))):?>
																	<a class="updateOldSchedule" id="<?php echo $program['program_id']?>"><b><?php echo $program['title'].' '.$program['batch'] ?></b></a>
																<?php else:?>
																	<b><?php echo $program['title'].' '.$program['batch'] ?></b>
																<?php endif;?>
															</td>
															<td><?php echo $program['sessions']?></td>
														</tr>
														<?php endforeach;?>
													</tbody>
												</table>
											</div>
										</div>
										<?php endforeach;?>
									</div>	
									<div class="clearer"></div>	
								</div>
							</div>							
						</td>
					</tr>
				</table>
			</td>			
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){

	$(".button").button();

	$(".tableList th").each(function(){			 
		$(this).addClass("ui-state-default");			 
	});
	
	$(".tableList td").each(function(){
		$(this).addClass("ui-widget-content");
	});
	

	$('.tableList tr td:not(:last-child),.tableList tr th:not(:last-child)').css('border-right', '0');
	$('.tableList tr td').css('border-top', '0');

	$(".programList").bind("click",function(){
		var fdata = {
				ajax : 1
				};
		myDialogBox('<?php echo site_url('schedule/programs_list')?>',fdata,'program_list','All Programs',{width : '605'});
	    return false;
	});		
	

	$('.detailsProgramSchedule').bind("click",function(){
	    var dialog = $("#dialog");
	    if ($("#dialog").length == 0) {
	        dialog = $('<div id="dialog" style:padding-top:15px;></div>').appendTo('body');
	    } 
		var fdata = {
				ajax:1,
				schedule_id : this.id
				};
		showMyLoader();	
		$.ajax({
			url : '<?php echo site_url('schedule/schedule_details')?>',
			data : fdata,
			type : 'POST',
			success : function (msg){
					responce = $.parseJSON(msg);
					dialog.html(responce.content).dialog({
		                zIndex:1001,
		                resizable:false,
		                width : 'auto',
		                minHeight : '120',	               
		                modal: true,
		                dialogClass: 'dialogWithDropShadow',
		                open: function (){ 
		                	$('.ui-widget-overlay').css('opacity','0.6').css('background','#777').css('width','100%');
			                $('.ui-dialog').css('paddingLeft','0').css('paddingRight','0').removeClass('ui-corner-all').css('borderTop','0').css('overflow','visible');
			                $('.ui-dialog-titlebar').css('padding','0').css('marginTop','-3px').css('border','0');
			                $('#ui-dialog-title-dialog').css('width','100%').css('minWidth','300px').css('margin','0').html('<div class="contentEditor" style="width:auto;"><div class="editor-header" >' + responce.title + '</div></div>');
			                $('.ui-dialog-titlebar-close').css('margin','0').css('padding','1px').css('z-index','1005').css('right','-9px').css('top','-9px').css('background','url("<?php echo base_url()?>assets/images/fancy_close.png") no-repeat scroll 0 0 transparent').hover(function(){$(this).removeClass('ui-state-hover');}).find('span').css('background','none');
		                }
	                
		            });
		            hideMyLoader();
				}
		});
	    return false;

	});	

	$(".updateOldSchedule").click(function(){
		var fdata = {
				ajax:1,
				program_id : this.id
				};
		ajaxCallBoxOpen('<?php echo site_url('schedule/old_schedule_editor')?>',fdata);
	});	

	$(".sessionDetails").bind("click",function(){
		var fdata = {
				ajax : 1,
				session_id : this.id
				};
		myDialogBox('<?php echo site_url('schedule/session_details')?>',fdata,'session_details','Session Details',{width : '350'});
	    return false;
	});	


	$('#year_selector').change(function(){
		$('#year_selector_form').trigger('submit');
	});
			

	$('.selectMonth').click(function(){
		var fdata = {selected_year : $('#year_selector').val()};
		ajaxCallBoxOpen('<?php echo site_url('schedule/select_months')?>',fdata);		
	});

	$('.venue_alert,.speaker_alert,.both_alert').mouseover(function(){
		var needle = $(this).attr('date_id');
		$('.homecontenttable a').each(function(i,e){
			if($(e).attr('date_id') == needle){
				$(e).addClass('highlight_date');
			}
		});
	}).mouseout(function(){
		var needle = $(this).attr('date_id');
		$('.homecontenttable a').each(function(i,e){
			if($(e).attr('date_id') == needle){
				$(e).removeClass('highlight_date');
			}
		});
	});
	
});
</script>	