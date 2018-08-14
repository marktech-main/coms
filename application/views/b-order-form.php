<?php $this->load->view("impl/header.php");?>

		<div id="page-wrapper">
			<div class="row" id="tasks-form">
				<div class="col-lg-6">
					<h1 class="page-header">Request Form</h1>
										<iframe name="x" style="display:none;" ></iframe>

										<?php if($custom_request_form){ ?>
											<form name="request_form" role="form" method="post" action="<?php echo base_url()?>create">
												<div class="form-group">
														<label class="col-lg-3 col-sm-3">Request Type</label>
														<div class="col-lg-9 col-sm-9">
															<select name="transaction_type_id" class="form-control" onchange="javascript: setVal(this)">
																<option value="" selected hidden disabled>Choose Request Type</option>
																<?php
																	foreach ($transaction_types_list as $type) {
																		echo '<option value="'.encrypt($type['transaction_type_id']).'" '.set_select('transaction_type_id', encrypt($type['transaction_type_id'])).'  >'.$type['transaction_type_name'].'</option>';
																	}
																?>
																</select>
																<input type="hidden" name="transaction_type_name" value=""/>
														</div>
														<div class="clearfix"></div>
												</div>
												<div class="form-group">
														<label class="col-lg-3 col-sm-3">Website</label>
														<div class="col-lg-9 col-sm-9">
															<select name="website_id" class="form-control">
																<option value="" selected hidden disabled>Choose Website</option>
																<?php
																	foreach ($websites_list as $website) {
																		echo '<option value="'.encrypt($website['website_id']).'" '.set_select('website_id', encrypt($website['website_id'])).' >'.$website['website_name'].'</option>';
																	}
																?>
																</select>
														</div>
														<div class="clearfix"></div>
												</div>
	                        <div class="form-group">
	                            <label id="lbl_customer" class="col-lg-3 col-sm-3">Customer ID</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="text" name="customer_id" class="form-control" value="<?=set_value('customer_id')?>">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

													<div name="fee-control" <?php echo !$custom_request_form ? 'display:none;': '' ?> >
														<div class="form-group">
																<label class="col-lg-3 col-sm-3">Amount</label>
																<div class="col-lg-4 col-sm-4">
																	<input type="number" name="_amount" class="form-control" value="<?=set_value('_amount')?>" data-number-to-fixed="0" data-number-stepfactor="100">
																</div>
																<div class="col-lg-1 col-sm-1 fee_div">
																	<label> Fee </label>
																</div>
																<div class="col-lg-4 col-sm-4 to_website_game_div">
																	<input type="number" name="fee" class="form-control" value="<?=set_value('fee')?>" data-number-to-fixed="0" data-number-stepfactor="100">
																</div>
																<div class="clearfix"></div>
														</div>
													</div>

	                        <div name="amount-control" class="form-group">
	                            <label name="amount_label" class="col-lg-3 col-sm-3">Amount</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="number" name="amount" class="form-control" value="<?=set_value('amount')?>">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

													<div name="row-control">
														<div id="source-control" name="source-control" class="form-group">
		                            <label class="col-lg-3 col-sm-3">Game</label>
																<div>
			                            <div class="col-lg-4 col-sm-4">
																		<select name="from_game_id[]" class="form-control">
																			<option value="" selected hidden disabled>Choose Game</option>
																		</select>
			                            </div>
																	<div name="div-control" class="col-lg-5 col-sm-5">
				                            <div class="col-lg-9 col-sm-9">
																			<input type="number" name="game_amount[]" class="form-control" data-number-to-fixed="0" data-number-stepfactor="100">
				                            </div>
																		<div class="col-lg-3 col-sm-3">
																			<img style="cursor:pointer;" class="add-more-row" src="<?php echo base_url()?>images/sign-add-icon.png" >
																		</div>
																	</div>
			                            <div class="clearfix"></div>
																</div>
		                        </div>
													</div>

													<div name="resetpwd-control" style="display:none;">
														<div class="form-group">
															<label class="col-lg-3 col-sm-3">Game</label>
	                            <div class="col-lg-9 col-sm-9">
																<select name="from_game_id[]" class="form-control">
																	<option value="" selected hidden disabled>Choose Game</option>
																</select>
															</div>
															<div class="clearfix"></div>
														</div>
													</div>

													<div name="transfer-control" style="display:none;">
														<div class="form-group">
																<label class="col-lg-3 col-sm-3">Game</label>
																<div class="col-lg-4 col-sm-4">
																	<select name="from_game_id[]" class="form-control">
																		<option value="" selected hidden disabled>Choose Game</option>
																		</select>
																</div>
																<div class="col-lg-1 col-sm-1 to_website_game_div">
																	<label> TO </label>
																</div>
																<div class="col-lg-4 col-sm-4 to_website_game_div">
																	<select name="to_game_id[]" class="form-control">
																		<option value="" selected hidden disabled>Choose Game</option>
																	</select>
																</div>
																<div class="clearfix"></div>
														</div>
													</div>

													<div name="bank_account_name_div" class="form-group">
	                          <label class="col-lg-3 col-sm-3">Acct. Name</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <input id="bank_account_name" name="bank_account_name" type="text" class="form-control" value="<?=set_value('bank_account_name')?>">
	                          </div>
	                          <div class="clearfix"></div>
	                        </div>

													<div name="bank_account_number_div" class="form-group">
	                            <label class="col-lg-3 col-sm-3">Acct. Destination</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input name="bank_account_number" class="form-control" value="<?=set_value('bank_account_number')?>">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

													<div name="transaction_time_div" class="form-group" <?php echo !$custom_request_form ? 'display:none;': '' ?> >
															<label class="col-lg-3 col-sm-3">Transaction Time</label>
															<div class="col-lg-9 col-sm-9">
																<input type='text' class="form-control date_time" name='transaction_time' id="datetimepicker" placeholder="YYYY-MM-DD HH:MM:SS" value="<?=set_value('transaction_time')?>" />
															</div>
															<div class="clearfix"></div>
													</div>

	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Remark</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<textarea name="remark" class="form-control" rows="4"><?=set_value('remark')?></textarea>
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

													<div class="form-group">
														<label class="col-lg-3 col-sm-3">Priority</label>
														<div class="col-lg-9 col-sm-9">
															<div class="onoffswitch">
																	<input type="checkbox" name="priority" class="onoffswitch-checkbox" id="priority">
																	<label class="onoffswitch-label" for="priority">
																			<span class="onoffswitch-inner"></span>
																			<span class="onoffswitch-switch"></span>
																	</label>
															</div>
														</div>
													</div>
	                        <button type="submit" name="submit" class="btn btn-submit pull-right">Submit</button>
	                    </form>
										<?php }else{ ?>
											<form name="request_form" role="form" method="post" action="<?php echo base_url()?>create">
												<div class="form-group">
														<label class="col-lg-3 col-sm-3">Request Type</label>
														<div class="col-lg-9 col-sm-9">
															<select name="transaction_type_id" class="form-control" onchange="javascript: setVal(this)">
																<option value="" selected hidden disabled>Choose Request Type</option>
																<?php
																	foreach ($transaction_types_list as $type) {
																		echo '<option value="'.encrypt($type['transaction_type_id']).'" '.set_select('transaction_type_id', encrypt($type['transaction_type_id'])).' >'.$type['transaction_type_name'].'</option>';
																	}
																?>
																</select>
																<input type="hidden" name="transaction_type_name" value=""/>
														</div>
														<div class="clearfix"></div>
												</div>
	                        <div class="form-group">
	                            <label id="lbl_customer" class="col-lg-3 col-sm-3">Customer ID</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="text" name="customer_id" class="form-control" value="<?=set_value('customer_id')?>">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

	                        <div name="amount-control" class="form-group">
	                            <label class="col-lg-3 col-sm-3">Amount</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="number" name="amount" class="form-control" value="<?=set_value('amount')?>" data-number-to-fixed="0" data-number-stepfactor="100">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Website</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<select name="website_id" class="form-control">
																	<option value="" selected hidden disabled>Choose Website</option>
																	<?php
																		foreach ($websites_list as $website) {
																			echo '<option value="'.encrypt($website['website_id']).'" '.set_select('website_id', encrypt($website['website_id'])).' >'.$website['website_name'].'</option>';
																		}
																	?>
		                                <!-- <option>7bet</option>
		                                <option>Ligajudi</option>
		                                <option>Surgajudi</option>
		                                <option>Bandar Premium</option> -->
	                                </select>
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

													<div name="row-control">
														<div id="source-control" name="source-control" class="form-group">
		                            <label class="col-lg-3 col-sm-3">Game</label>
																<div>
			                            <div class="col-lg-4 col-sm-4">
																		<select name="from_game_id[]" class="form-control">
																			<option value="" selected hidden disabled>Choose Game</option>
																		</select>
			                            </div>
																	<div name="div-control" class="col-lg-5 col-sm-5">
				                            <div class="col-lg-9 col-sm-9">
																			<input type="number" name="game_amount[]" class="form-control" data-number-to-fixed="0" data-number-stepfactor="100">
				                            </div>
																		<div class="col-lg-3 col-sm-3">
																			<img style="cursor:pointer;" class="add-more-row" src="<?php echo base_url()?>images/sign-add-icon.png" >
																		</div>
																	</div>
			                            <div class="clearfix"></div>
																</div>
		                        </div>
													</div>

													<div name="resetpwd-control" style="display:none;">
														<div class="form-group">
															<label class="col-lg-3 col-sm-3">Game</label>
	                            <div class="col-lg-9 col-sm-9">
																<select name="from_game_id[]" class="form-control">
																	<option value="" selected hidden disabled>Choose Game</option>
																</select>
															</div>
															<div class="clearfix"></div>
														</div>
													</div>

													<div name="transfer-control" style="display:none;">
														<div class="form-group">
																<label class="col-lg-3 col-sm-3">Game</label>
																<div class="col-lg-4 col-sm-4">
																	<select name="from_game_id[]" class="form-control">
																		<option value="" selected hidden disabled>Choose Game</option>
																		</select>
																</div>
																<div class="col-lg-1 col-sm-1 to_website_game_div">
																	<label> TO </label>
																</div>
																<div class="col-lg-4 col-sm-4 to_website_game_div">
																	<select name="to_game_id[]" class="form-control">
																		<option value="" selected hidden disabled>Choose Game</option>
																	</select>
																</div>
																<div class="clearfix"></div>
														</div>
													</div>

	                        <div name="bank_account_name_div" class="form-group">
	                          <label class="col-lg-3 col-sm-3">Acct. Name</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <input id="bank_account_name" name="bank_account_name" type="text" class="form-control" value="<?=set_value('bank_account_name')?>">
	                          </div>
	                          <div class="clearfix"></div>
	                        </div>

	                        <div name="bank_account_number_div" class="form-group">
	                            <label class="col-lg-3 col-sm-3">Acct. Destination</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input name="bank_account_number" class="form-control" value="<?=set_value('bank_account_number')?>">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Remark</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<textarea name="remark" class="form-control" rows="4"><?=set_value('remark')?></textarea>
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

													<div class="form-group">
														<label class="col-lg-3 col-sm-3">Priority</label>
														<div class="col-lg-9 col-sm-9">
															<div class="onoffswitch">
															    <input type="checkbox" name="priority" class="onoffswitch-checkbox" id="priority">
															    <label class="onoffswitch-label" for="priority">
															        <span class="onoffswitch-inner"></span>
															        <span class="onoffswitch-switch"></span>
															    </label>
															</div>
														</div>
													</div>

	                        <button type="submit" name="submit" class="btn btn-submit pull-right">Submit</button>
	                    </form>
										<?php } ?>
										<div class="errors_div"><?php echo validation_errors(); ?></div>
                </div>
			</div><!-- /.col-lg-6 -->
			<div class="clearfix"></div>
			<!-- /.row -->
		</div>
		<!-- <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script> -->
			<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
			<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script> -->
<?php $this->load->view("impl/footer.php");?>

<script type="text/javascript" async>
$('#bank_account_name').autocomplete({
    serviceUrl: '<?php echo base_url()?>transaction/get_customer_bank_account',
    paramName: 'keywords',
    onSearchStart: function(){
      $('[name="bank_account_number"]').val('');
    },
    beforeRender: function(){
      $('[name="bank_account_number"]').val('');
    },
    onSelect: function (suggestion) {
        // alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
        $('[name="bank_account_number"]').val(suggestion.data);
    }
});

function setVal(obj){
	$('[name="transaction_type_name"]').val($('option:selected', $(obj)).text());
}
$(document).ready(function() {
	webshims.setOptions('forms-ext', {
	    replaceUI: 'auto',
	    types: 'number'
	});
	webshims.polyfill('forms forms-ext');


	// add extra row event handler
	var i = 0;
	$('.add-more-row').on('click', function() {
					$target =  $('[name="row-control"]');
					var regex = /^(.+?)(\d+)$/i;
					var cloneIndex = $(".clonedInput").length;
					$('#source-control').clone()
					        .appendTo($target)
					        .attr("id", "clonedInput" +  cloneIndex)
					        .find("*")
					        .each(function() {
					            var id = this.id || "";
					            var match = id.match(regex) || [];
					            if (match.length == 3) {
					                this.id = match[1] + (cloneIndex);
					            }
					        })
									.end()
									.find('label')
									.html('')
									.end()
									.find('[name="game_amount[]"]')
									.val('')
									.end()
									.find('.ws-number')
									.remove()
									.end()
									.find('.add-more-row')
									.toggleClass('add-more-row delete-extra-row')
									.prop('src','<?php echo base_url()?>images/sign-delete-icon.png');
					    cloneIndex++;
					$target.updatePolyfill();
					console.log('update')
					return false;
	});

	// delete extra row event handler
	$(document).on('click', '.delete-extra-row', function() {
					$(this).parents('[name="source-control"]').remove();
					return false;
	});

	$(document).ready(function(){
		if($('[name="transaction_time_div"]').length ){
			$('#datetimepicker').datetimepicker({
				format: 'YYYY-MM-DD HH:mm:ss',
				sideBySide : true
			});
			// $('.date_time').mask('0000-00-00 00:00:00');
		}
	});

	// auto calculate fee and amount value and assign to total amount
	$(document).on('keyup', 'input[type=number] + input', function(data){
		console.log('re-fomat amount output');
		<?php
			if($custom_request_form){
		?>
		var amount = $('[name="_amount"]').val() != '' ? $('[name="_amount"]').val() : 0;
		var fee = $('[name="fee"]').val() != '' ? $('[name="fee"]').val() : 0;
		var sum = parseInt(amount) + parseInt(fee);
		$('[name="amount"]').val(sum);
		<?php
			}
		?>
	});

	// transaction type event handler
	$('[name="transaction_type_id"]').on('change', function (data) {
			var type = $('option:selected', $(this)).text();

			<?php
				if($custom_request_form){
			?>
				if(type == 'DEPOSIT'){
					console.log('transfrom to customer form');
					$('[name="fee-control"]').show();
					$('[name="amount_label"]').text('Total Amount');
					$('[name="amount"]').prop("readonly", true);
					$('[name="transaction_time_div"]').show();
				}else{
					$('[name="fee-control"]').hide();
					$('[name="amount_label"]').text('Amount');
					$('[name="amount"]').prop("readonly", false);
					$('[name="transaction_time_div"]').hide();
					$('[name="_amount"]').val('');
					$('[name="fee"]').val('');
				}
			<?php
			}
			?>

			if (type == 'TRANSFER') { // toggle .to_website_game_div
				$('[name="row-control"]').hide();
				$('[name="transfer-control"]').show();
				$('[name="amount-control"]').show();
				// ajax call controller to get game list data
					$('[name="from_game_id[]"]').on('change', function(){
						// onChange re-rendering game list
						$('[name="to_game_id[]"]')
								.find('option')
								.remove()
						;

						$('[name="transfer-control"]').find('[name="from_game_id[]"] > option').clone().appendTo('[name="to_game_id[]"]');

						// $('[name="from_game_id[]"] > option').clone().appendTo('[name="to_game_id[]"]');
						$('[name="to_game_id[]"] > option[value="'+$(this).val()+'"]').remove();
						$('[name="to_game_id[]"]').prepend('<option selected hidden disabled value="">Choose Game</option>');
					});

				// hide bank section
				$('[name="bank_account_number_div"]').hide();
				$('[name="bank_account_name_div"]').hide();
				$('[name="resetpwd-control"]').hide();
				$('#lbl_customer').text('Customer ID');

			}else if(type == 'RESET-PASSWORD'){
				$('[name="row-control"]').hide();
				$('[name="transfer-control"]').hide();
				$('[name="bank_account_name_div"]').hide();
				$('[name="bank_account_number_div"]').hide();
				$('[name="amount-control"]').hide();
				$('[name="resetpwd-control"]').show();
				$('#lbl_customer').text('Customer ID');
			}else{
				if(type == 'NEW-REGISTER'){
					$('#lbl_customer').text('Customer Name');
				}else{
					$('#lbl_customer').text('Customer ID');
				}
				$('[name="amount-control"]').show();
				$('[name="row-control"]').show();
				$('[name="transfer-control"]').hide();
				$('[name="resetpwd-control"]').hide();
				$('[name="bank_account_number_div"]').show();
				$('[name="bank_account_name_div"]').show();
				$('[name="website_id"]').val('');
				$('[name="from_game_id[]"]')
						.find('option')
						.remove()
						.end()
						.append('<option selected hidden disabled value="">Choose Game</option>')
				;
				// clear game list
				$('[name="to_game_id[]"]')
						.find('option')
						.remove()
						.end()
						.append('<option selected hidden disabled value="">Choose Game</option>')
				;
			}
	});

// ajax call controller to get game list data
	$('[name="website_id"]').on('change', function(){
		// onChange re-rendering game list
		generate_game_list($(this).val());
	});

	function generate_game_list(website_id){
		$.ajax({
			type: "POST",
			url: '<?php echo base_url()?>transaction/get_game_list',
			data: {'website_id' : website_id},
			dataType: 'text',
			success: function(gameDataList){
				// injecting json data to <select> element
				$('[name="from_game_id[]"]')
						.find('option')
						.remove()
						.end()
						.append( gameDataList )
				;
			}
		});
		// clear game list
		$('[name="to_game_id[]"]')
				.find('option')
				.remove()
				.end()
				.append('<option selected hidden disabled value="">Choose Game</option>')
		;
	}

	$(document).on('click','[name="submit"]',function(){
			$request_form = $('[name="request_form"]').serializeArray();
			$('.errors_div').html('');
			$.ajax({
				type: "POST",
				url: '<?php echo base_url()?>create',
				data: $request_form,
				dataType: 'json',
				success: function(data){
					if(data.state){
								console.log(data.message);
						    window.location.href = data.redirect_url;
					}else{
						$('.errors_div').html(data.message);
					}

				}
			});
	    return false;
	});

});
</script>
