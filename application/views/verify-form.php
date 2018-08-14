<?php $this->load->view("impl/header.php");?>
<?php if( !(($can_update_request && $are_you_operator) || $am_i_requester || $this->privilege['is_supervisor']) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') ){
	header("Location: http://".$_SERVER['SERVER_NAME']);
	die();
}

$senior_log =  json_decode($request_transaction->senior_log);

if(!empty($senior_log)) {
	$senior_log_current_name = end($senior_log)->senior;
	$senior_log_current_status = end($senior_log)->status;
} else {
	$senior_log_current_name = '';
	$senior_log_current_status = '';
}

if($request_transaction->created_by_name == 'affiliate') {
	$is_affiliate = true;
} else {
	$is_affiliate = false;
}

?>
<style>
.form-group {
	color: wheat !important;
}
</style>
		<div id="page-wrapper">
			<div class="row" id="tasks-form">
				<div class="col-lg-6">
					<?php if($can_update_request && $are_you_operator){
									echo '<h1 class="page-header">Verify Form</h1>';
								}else{
									echo '<h1 class="page-header">Request Details</h1>';
								} ?>
										<iframe name="x" style="display:none;" ></iframe>
                    <form role="form" method="post" action="<?php echo base_url()?>update">
											<div class="form-group">
														<label class="col-lg-3 col-sm-3">PIC</label>
														<div class="col-lg-9 col-sm-9">
															
															<span class="<?=($is_affiliate)?'affiliate':''?>"><?=$request_transaction->created_by_name; ?></span>
														</div>
													<div class="clearfix"></div>
											</div>
                      <div class="form-group">
                          <label class="col-lg-3 col-sm-3">Transaction Code</label>
                          <div class="col-lg-9 col-sm-9">
                            <?php echo $request_transaction->transaction_id; ?>
                          </div>
                          <div class="clearfix"></div>
                      </div>
											<div class="form-group">
													<label class="col-lg-3 col-sm-3">Request Type</label>
													<div class="col-lg-9 col-sm-9">
                            <?php echo $request_transaction->transaction_type_name; ?>
													</div>
													<div class="clearfix"></div>
											</div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3"><?php echo $request_transaction->transaction_type_name == 'NEW-REGISTER' ? 'Customer Name' : 'Customer ID'; ?></label>
                            <div class="col-lg-4 col-sm-4">
                              <?php echo $request_transaction->customer_id; ?>
                            </div>
														<div class="col-lg-5 col-sm-5" style="padding: 0 0 0 15px;">
															<?php if($request_transaction->priority == TRUE){?>
															<img alt="priority" title="priority" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/vip.png" alt="" width="24" height="24" data-pin-nopin="true">
															<?php }?>
														</div>
                            <div class="clearfix"></div>
                        </div>
												<?php if($request_transaction->transaction_type_name != 'RESET-PASSWORD' && $request_transaction->transaction_type_name != 'OTHERS'){?>

													<?php if(!empty($request_transaction->fee)){ ?>
														<div name="fee-control">
															<div class="form-group">
																	<label class="col-lg-3 col-sm-3">Amount</label>
																	<div class="col-lg-4 col-sm-4">
																		<?php echo number_format(($request_transaction->amount - $request_transaction->fee), 0, '.', ','); ?>
																	</div>
																	<div class="col-lg-1 col-sm-1">
																		<label> Fee </label>
																	</div>
																	<div class="col-lg-4 col-sm-4">
																		<?php echo number_format($request_transaction->fee, 0, '.', ','); ?>
																	</div>
																	<div class="clearfix"></div>
															</div>
														</div>
													<?php } ?>
														<div class="form-group">
		                            <?php if(!empty($request_transaction->fee)){ ?>
																	<label class="col-lg-3 col-sm-3">Total Amount</label>
																<?php }else{ ?>
																	<label class="col-lg-3 col-sm-3">Amount</label>
																<?php } ?>
		                            <div class="col-lg-9 col-sm-9">
		                              <?php echo number_format($request_transaction->amount, 0, '.', ','); ?>
		                            </div>
		                            <div class="clearfix"></div>
		                        </div>
												<?php } ?>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3">Website</label>
                            <div class="col-lg-9 col-sm-9">
                              <?php echo $request_transaction->website_name; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <!--
                         render data with condition
                         IF transaction type is TRANSFER display from_game_id and to_game_id
                         IF transaction type is not TRANSFER display from_game_id and amount per game
                        -->
                        <!-- <div name="transfer-control">
                          <div class="form-group">
                              <label class="col-lg-3 col-sm-3">Game</label>
                              <?php
                              // if($request_transaction->transaction_type_name == 'TRANSFER'){
                                // foreach ($transaction_data as $k => $v) {
                                  // echo '<div style="padding-bottom: 10px;" class="col-lg-3 col-sm-3"> '. $v['from_website_game_name'] .' </div><div class="col-lg-3 col-sm-3"><label> TO </label></div><div style="padding-bottom: 10px;" class="col-lg-3 col-sm-3"> ' . $v['to_website_game_name'] . ' </div>';
                                  // echo '<p>'. $v['from_website_game_name'] .' <label> TO </label> '. $v['to_website_game_name'] .'</p>';
                                // }
                              // }else if($request_transaction->transaction_type_name == 'RESET-PASSWORD'){
																// foreach ($transaction_data as $k => $v) {
                                  // echo '<div style="padding-bottom: 10px;" class="col-lg-3 col-sm-3"> '. $v['website_game_name'] .' </div>';
                                // }
															// }else{
                                // foreach ($transaction_data as $k => $v) {
                                  // echo '<div style="padding-bottom: 10px;" class="col-lg-4 col-sm-4"> '. $v['website_game_name'] .' </div><div class="col-lg-1 col-sm-1"><label>Amount</label></div><div style="padding-bottom: 10px;" class="col-lg-4 col-sm-4"> ' . number_format($v['game_amount'], 0, '.', ',') . ' </div>';
                                // }
                              // }
                              ?>
                            <div class="clearfix"></div>
                          </div>
                        </div> -->

												<div name="transfer-control">
                          <div class="form-group">
                              <label class="col-lg-3 col-sm-3">Game</label>
                              <div class="col-lg-9 col-sm-9">
                              <?php
                              if($request_transaction->transaction_type_name == 'TRANSFER'){
                                foreach ($transaction_data as $k => $v) {
                                  echo '<div style="padding-left: 0px; padding-bottom: 10px;" class="col-lg-3 col-sm-3"> '. $v['from_website_game_name'] .' </div><div class="col-lg-3 col-sm-3"><label> TO </label></div><div style="padding-bottom: 10px;" class="col-lg-3 col-sm-3"> ' . $v['to_website_game_name'] . ' </div>';
                                  // echo '<p>'. $v['from_website_game_name'] .' <label> TO </label> '. $v['to_website_game_name'] .'</p>';
                                }
                              }else if($request_transaction->transaction_type_name == 'RESET-PASSWORD' || $request_transaction->transaction_type_name == 'OTHERS'){
																foreach ($transaction_data as $k => $v) {
                                  echo '<div style="padding-left: 0px; padding-bottom: 10px;" class="col-lg-3 col-sm-3"> '. $v['website_game_name'] .' </div>';
                                }
															}else{
                                foreach ($transaction_data as $k => $v) {
                                  echo '<div style="padding-left: 0px; padding-bottom: 10px;" class="col-lg-5 col-sm-5"> '. $v['website_game_name'] .' </div><div style="padding-bottom: 10px;" class="col-lg-4 col-sm-4"> ' . number_format($v['game_amount'], 0, '.', ',') . ' </div>';
                                }
                              }
                              ?>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        </div>


                        <?php
                          if($request_transaction->transaction_type_name != 'TRANSFER' && $request_transaction->transaction_type_name != 'RESET-PASSWORD' && $request_transaction->transaction_type_name != 'OTHERS'){
                        ?>
                        <div name="bank_account_name_div" class="form-group">
                            <label class="col-lg-3 col-sm-3">Acct. Name</label>
                            <div class="col-lg-9 col-sm-9">
                              <?php echo $request_transaction->customer_bank_account_name; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
												<div name="bank_account_number_div" class="form-group">
														<label class="col-lg-3 col-sm-3">Acct. Destination</label>
														<div class="col-lg-9 col-sm-9">
															<?php echo $request_transaction->customer_bank_account_number; ?>
														</div>
														<div class="clearfix"></div>
												</div>
                        <?php } ?>

												<?php if(!empty($request_transaction->transaction_time)){ ?>
													<div name="fee-control">
														<div class="form-group">
																<label class="col-lg-3 col-sm-3">transaction time</label>
																<div class="col-lg-9 col-sm-9">
																	<?php echo $request_transaction->transaction_time;?>
																</div>
																<div class="clearfix"></div>
														</div>
													</div>
												<?php } ?>

												<div class="form-group">
                            <label class="col-lg-3 col-sm-3">Remark</label>
                            <div class="col-lg-9 col-sm-9" name="remark_content">
															<?php if(($can_update_request && $are_you_operator) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'DONE' && $request_transaction->status != 'SUCCESSFUL')){ ?>
																<textarea name="remark" class="form-control" rows="4"><?php echo $request_transaction->remark; ?></textarea>
															<?php	}else{ ?>
																	<?php echo $request_transaction->remark; ?>
															<?php } ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>

												<div name="log_div" class="form-group">
                            <label class="col-lg-3 col-sm-3">Logs</label>
                            <div class="col-lg-9 col-sm-9" style="overflow-y: auto; max-height: 150px;" name="log_content">

																<?php
																$log =  json_decode($request_transaction->log_info);
																if(empty($log)){
																	echo '<span>NO DATA</span>';
																}else{
																	foreach(array_reverse($log) as $k=>$v){
																		// if($request_transactiontransaction->come_from == 'Affiliate') {
																		// 	$create_name = 'Affiliate';
																		// } else {
																		// 	$create_name = $v->complete_name;
																		// }
																		echo '<span>'.$v->complete_name.' <<time class="timeago" datetime="'.$v->timestamp.'">'.$v->timestamp.'</time>> : '.$v->content.'<hr style="margin:5 !important;border: 0 !important;border-bottom: 1px dotted #999999 !important;"></span>';
																	}
																}
																?>
                            </div>
                            <div class="clearfix"></div>
                        </div>

												<div class="form-group">
														<label class="col-lg-3 col-sm-3">Come From</label>
														<div class="col-lg-9 col-sm-9">
															<span class="<?=($request_transaction->come_from == 'Affiliate')?'affiliate':''?>"><?=$request_transaction->come_from; ?></span>
														</div>
														<div class="clearfix"></div>
												</div>

                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3">Status</label>
                            <div class="col-lg-9 col-sm-9" name="status_content" id="status_content">
															<?php if(($can_update_request && $are_you_operator) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL' && $request_transaction->status != 'DONE')){ ?>
															<select name="status" class="form-control" id="<?=($is_affiliate)?'aff_stat':''?>">
																<option value="PROCESSING" <?php echo $request_transaction->status == 'PROCESSING' ? 'selected' : '' ?> >PROCESSING</option>
								                                <option value="PENDING" <?php echo $request_transaction->status == 'PENDING' ? 'selected' : '' ?> >PENDING</option>
								                                <option value="CANCELLED" <?php echo $request_transaction->status == 'CANCELLED' ? 'selected' : '' ?> >CANCELLED</option>
								                                <?php if($is_affiliate) { ?>
								                                 		<option value="SUCCESSFUL" <?php echo $request_transaction->status == 'SUCCESSFUL' ? 'selected' : '' ?> >SUCCESSFUL</option>	
								                                <?php } else { ?>
								                                		<option value="DONE" <?php echo $request_transaction->status == 'DONE' ? 'selected' : '' ?> >DONE</option>	
								                                <?php } ?>
								                                
								                            </select>
								                            <div class="input-group hidden" id="customer_id">
														      <input name="customer_id" type="text" class="form-control" placeholder="Customer ID">
														    </div>
														<?php	}else{ 
																switch ($request_transaction->status) {
																	case 'DONE':
																		if($am_i_requester) { ?>
																			<select name="status" class="form-control">
												                                <option value="" <?php echo $request_transaction->status == 'DONE' ? 'selected' : '' ?> disabled>DONE</option>
												                                <option value="SUCCESSFUL" <?php echo $request_transaction->status == 'SUCCESSFUL' ? 'selected' : '' ?> >SUCCESSFUL</option>
												                            </select>
																		<?php } else {
																			echo $request_transaction->status;
																		}
																		break;																		
																	case 'SUCCESSFUL':
																		echo $request_transaction->status.'<span name="reviewSuccessful"></span>';
																		break;
																	default:
																		echo $request_transaction->status;
																		break;
																}
															} ?>
                            </div>
														<div name="confirm_pending" class="col-lg-5 col-sm-5">
                              <?php if($request_transaction->status == 'PENDING' && !$request_transaction->is_pending && $am_i_requester){ ?>
                                <span><?php echo $request_transaction->pending_reason; ?></span>
                                <button type="button" class="btn btn-danger pull-right" name="n_confirm_pending_btn" action="decline-pending" data-target="<?php echo encrypt($request_transaction->transaction_id); ?>">Decline</button>
                                <button type="button" class="btn btn-success pull-right" name="y_confirm_pending_btn" action="accept-pending" data-target="<?php echo encrypt($request_transaction->transaction_id); ?>" style="margin-right:5px;">Accept</button>
                              <?php } ?>
														</div>
                            <div class="clearfix"></div>
                        </div>

                        <div name="reason-control" class="form-group" style="display:none;">
                            <label class="col-lg-3 col-sm-3">Reason</label>
                            <div class="col-lg-9 col-sm-9"  id="status_content">
															<select name="reason" class="form-control">
																<option selected hidden disabled value="">Choose Reason</option>
																<option value="banking down">Banking website down</option>
																<option value="product down">Product website down</option>
																<option value="waiting CS info">Waiting CS info</option>
															</select>
														</div>
														<div class="clearfix"></div>
												</div>

                        						<input type="hidden" name="transaction_id" value="<?php echo encrypt($request_transaction->transaction_id); ?>" />

                        						<!-- senior verfication -->
                        						<br>
                        						<?php if(($can_update_request && $are_you_operator) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') && $request_transaction->status != 'PENDING') {?>
                        						<hr class="sep">
                        						<div class="form-group">
						                            <label class="col-lg-3 col-sm-3">Senior Validation</label>
							                            <div class="col-lg-9 col-sm-9">
															<!-- <button type="button" class="btn btn-outline-primary">Primary</button>
															<button type="button" class="btn btn-outline-secondary">Secondary</button> -->
															<div class="btn-group sv_options_container" data-toggle="buttons">
																<label class="btn btn-primary sv_options verify">
															    	<input type="radio" name="options" value="verify" autocomplete="off">VERIFY
															    </label>
																<label class="btn btn-primary sv_options fix">
															    	<input type="radio" name="options" value="fix" autocomplete="off">FIX
																</label>
															</div>
															<div class="btn-group sv_options_container_disable" data-toggle="buttons">
																<label class="btn btn-primary sv_options verify">VERIFY</label>
																<label class="btn btn-primary sv_options fix">FIX</label>
															</div>
							                            </div>
						                            <div class="clearfix"></div>
						                        </div>
						                        <?php } ?>
                        						<div class="form-group" id="pword_required">
	                        						<label class="col-lg-3 col-sm-3" id="option_label">Senior Validation</label>
	                        						<div class="col-lg-9 col-sm-9" id="verify_section">

	                        								<input id="toggle-event" type="checkbox" data-toggle="toggle">
															<div class="verifying"> </div>
															<div class="input-group" id="vpword">
														      <input name="spword" type="password" class="form-control" placeholder="Password">
														      <span class="input-group-btn">
														        <button class="btn btn-default btn-vpword" type="button">Go</button>
														      </span>
														    </div>
														    <p class="val_pword"></p>

	                        						</div>
	                        						<div class="clearfix"></div>
                        						</div>
                        						<br>
                        						<div class="form-group" id="senior_logs">
	                        						<label class="col-lg-3 col-sm-3">Senior Logs</label>

                            							<div class="col-lg-9 col-sm-9 senior_log_content" id="<?=($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') ? 'active_status' : 'inactive_status'?>" style="overflow-y: auto; max-height: 150px;">
                            								<?php
                            								if(empty($senior_log)) {
                            									echo '<span id="unverified">UNVERIFIED</span><hr>';
                            								} else {
                            									foreach (array_reverse($senior_log) as $item) {

                            										echo '<i class="fa fa-circle fa-1" aria-hidden="true"></i><span class="verified_status">'.$item->status.' by '.$item->senior.' : <time class="timeago" datetime="'.$item->stamptime.'">'.$item->stamptime.'</time></span><hr>';
                            									}
                            								}?>
                           								</div>
	                        						<div class="clearfix"></div>
                        						</div>
                        						<!-- .senior verfication -->

												<?php if(($can_update_request && $are_you_operator) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL')){ ?>
													<button type="submit" class="btn btn-submit pull-right">SUBMIT</button>
												<?php } else if ($am_i_requester && $request_transaction->status == 'DONE') { ?>
													<button type="submit" class="btn btn-submit pull-right">SUBMIT</button>
												<?php } else { ?>
													<button type="button" name="return_btn" class="btn btn-submit pull-right">RETURN</button>
												<?php } ?>

												<!-- <?php if((!$can_update_request || !$are_you_operator) || ($request_transaction->status == 'CANCELLED' || $request_transaction->status == 'SUCCESSFUL')){ ?>
													<button type="button" name="return_btn" class="btn btn-submit pull-right">RETURN</button>
												<?php	}else{ ?>
													<button type="submit" class="btn btn-submit pull-right">SUBMIT</button>
												<?php } ?> -->
                    </form>
										<div><?php echo validation_errors(); ?></div>
                </div>

								<!-- .chatbox -->
								<?php if(!$is_affiliate) { ?>
								<div class="col-lg-6">
									<h1 class="page-header">Chat history</h1>
									<div name="messagewindow" class="panel-body chat-panel">
	                    <ul class="chat">
												<?php
												$state = array_search(decrypt($this->session->userdata('user_data'))['user_id'], array_column($chat_history, 'user_id'));
												$check_state = '';
												foreach ($chat_history as $data) {
													if(is_integer($state)){
														if($data['user_id'] != decrypt($this->session->userdata('user_data'))['user_id']){
															$chat_content = '';
															$chat_content .= '<li class="left clearfix"><span class="chat-img pull-left">';
															$chat_content .= '<img src="http://placehold.it/50/55C1E7/fff&amp;text='.strtoupper(substr($data['username'], 0, 2)).'" alt="User Avatar" class="img-circle">';
															$chat_content .= '</span>';
															$chat_content .= '<div class="chat-body clearfix">';
															$chat_content .= '<div class="header">';
															$chat_content .= '<strong class="primary-font">'.$data['username'].'</strong> <small class="pull-right text-muted">';
															$chat_content .= '<span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="'.$data['timestamp'].'">'.$data['timestamp'].'</time></small>';
															$chat_content .= '</div>';
															$chat_content .= '<p>';
															$chat_content .= $data['content'];
															$chat_content .= '</p>';
															$chat_content .= '</div>';
															$chat_content .= '</li>';
															echo $chat_content;
														}else{
															$chat_content = '';
															$chat_content .= '<li class="right clearfix"><span class="chat-img pull-right">';
															$chat_content .= '<img src="http://placehold.it/50/FA6F57/fff&amp;text='.strtoupper(substr($data['username'], 0, 2)).'" alt="User Avatar" class="img-circle">';
															$chat_content .= '</span>';
															$chat_content .= '<div class="chat-body clearfix">';
															$chat_content .= '<div class="header">';
															$chat_content .= '<small class=" text-muted"><span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="'.$data['timestamp'].'">'.$data['timestamp'].'</time></small>';
															$chat_content .= '<strong class="pull-right primary-font">'.$data['username'].'</strong>';
															$chat_content .= '</div>';
															$chat_content .= '<p>';
															$chat_content .= $data['content'];
															$chat_content .= '</p>';
															$chat_content .= '</div>';
															$chat_content .= '</li>';
															echo $chat_content;
														}
													}else{
														if(!empty($check_state) && $check_state != $data['user_id']){
															$chat_content = '';
															$chat_content .= '<li class="left clearfix"><span class="chat-img pull-left">';
															$chat_content .= '<img src="http://placehold.it/50/55C1E7/fff&amp;text='.strtoupper(substr($data['username'], 0, 2)).'" alt="User Avatar" class="img-circle">';
															$chat_content .= '</span>';
															$chat_content .= '<div class="chat-body clearfix">';
															$chat_content .= '<div class="header">';
															$chat_content .= '<strong class="primary-font">'.$data['username'].'</strong> <small class="pull-right text-muted">';
															$chat_content .= '<span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="'.$data['timestamp'].'">'.$data['timestamp'].'</time></small>';
															$chat_content .= '</div>';
															$chat_content .= '<p>';
															$chat_content .= $data['content'];
															$chat_content .= '</p>';
															$chat_content .= '</div>';
															$chat_content .= '</li>';
															echo $chat_content;
														}else{
															$check_state = $data['user_id'];
															$chat_content = '';
															$chat_content .= '<li class="right clearfix"><span class="chat-img pull-right">';
															$chat_content .= '<img src="http://placehold.it/50/FA6F57/fff&amp;text='.strtoupper(substr($data['username'], 0, 2)).'" alt="User Avatar" class="img-circle">';
															$chat_content .= '</span>';
															$chat_content .= '<div class="chat-body clearfix">';
															$chat_content .= '<div class="header">';
															$chat_content .= '<small class=" text-muted"><span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="'.$data['timestamp'].'">'.$data['timestamp'].'</time></small>';
															$chat_content .= '<strong class="pull-right primary-font">'.$data['username'].'</strong>';
															$chat_content .= '</div>';
															$chat_content .= '<p>';
															$chat_content .= $data['content'];
															$chat_content .= '</p>';
															$chat_content .= '</div>';
															$chat_content .= '</li>';
															echo $chat_content;
														}
													}
												}
												?>
	                    </ul>
	                </div>
									<?php if( (($can_update_request && $are_you_operator) || $am_i_requester ) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') ){ ?>
	                <div class="panel-footer">
	                    <div class="input-group">
	                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here...">
	                        <span class="input-group-btn">
	                            <button class="btn btn-warning btn-sm" id="btn-chat">
	                                Send</button>
	                        </span>
	                    </div>
	                </div>
									<?php } ?>
								</div>

								<?php } // endif affiliate?> 
			</div><!-- /.col-lg-6 -->

			<div class="clearfix"></div>
			<!-- /.row -->
		</div>

<?php $this->load->view("impl/footer.php");?>
<script type="text/javascript" async>
function setVal(obj){
	$('[name="transaction_type_name"]').val($('option:selected', $(obj)).text());
}
$(document).ready(function() {

	var conversation_id = '<?php echo $request_transaction->transaction_id ?>';

	/** Senior verification **/
	var verified = '<?php echo $senior_log_current_name ?>';
	var verification_status = '<?php echo $senior_log_current_status ?>';
	var operator_payment = '<?php echo $are_you_operator ?>';
	var am_i_requester = '<?php echo $am_i_requester ?>';
	var sv_option = '';
	var first_validation = 0;
	var verified_pword_status = 0;

	$(".sv_options").on('change', sv_option_event);
	$("#aff_stat").on('change', status_event);

	function status_event() {
		console.log(this.value);
		if(this.value == 'SUCCESSFUL'){
			$('.btn-submit').prop('disabled', true);
			$('#customer_id').removeClass('hidden');
			// check if customer id already added
			$("#customer_id input").on("input", function() {
			    console.log(this.value);
			    if(this.value == '') {
					$('.btn-submit').prop('disabled', true);
			    } else {
			    	$('.btn-submit').prop('disabled', false);
			    }
			});
		} else {
			$('.btn-submit').prop('disabled', false);
			$('#customer_id').addClass('hidden');
		}
	}

	function sv_option_event() {
		console.log('sv_option_event');
		sv_option = $('.sv_options.active input').val();
	 	console.log(sv_option);
	 	switch(sv_option) {
		    case 'verify':
		        $('#option_label').text('Verifying');
		        break;
		    case 'fix':
		        $('#option_label').text('Fixing');
		        break;
		}
		$('#pword_required').show();
	}

	switch(verification_status) {
	    case 'VERIFYING':
	    	verifying(conversation_id, '');
        	$('.sv_options.verify').addClass('active');
       		$('#option_label').text('Verifying');
	        sv_option = 'verify';
	        console.log('CASE VERIFYING');
	        break;
	    case 'FIXING':
	    	verifying(conversation_id, '');
	        $('.sv_options.fix').addClass('active');
	        $('#option_label').text('Fixing');
	        sv_option = 'fix';
	        console.log('CASE FIXING');
	        break;
	}


	$(".sv_options.disabled").on('click',function(){
		console.log('clicked');
		$('.sv_options').addClass('disabled');
		$(this).removeClass("active");
	  	$(this).removeClass("focus");
	  	switch(verification_status) {
		    case 'VERIFYING':
		    	console.log('clicked Verifying');
		    	//$('.verify.disabled').addClass("active");
		        break;
		    case 'FIXING':
		    	console.log('clicked Fixing');
		    	//$('.fix.disabled').addClass("active");
		        break;
		}
	});

	/*$(".sv_options.disabled").mouseout(function(){
		console.log('mouseout');
		console.log(verification_status);
		$('.sv_options').addClass('disabled');
		$('.sv_options.disabled').removeClass("active");
	  	$('.sv_options.disabled').removeClass("focus");
	  	switch(verification_status) {
		    case 'VERIFYING':
		    	console.log('mouseout Verifying');
		    	$('.verify.disabled').addClass("active");
		        break;
		    case 'FIXING':
		    	console.log('mouseout Fixing');
		    	$('.fix.disabled').addClass("active");
		        break;
		}
	}); */

	function ongoing() {
	    verified_pword_status = 1;
		$('#toggle-event').bootstrapToggle('on');
        $('#pword_required').show();
        $('#vpword').hide();
		if(!am_i_requester) {
			$('.btn-submit').prop('disabled', true);
		}
      	$(".sv_options_container").hide();
      	$(".sv_options_container_disable").show();
        $(".sv_options_container_disable").children().attr("disabled","disabled");
        switch(verification_status) {
		    case 'VERIFYING':
		    	console.log('ongoing Verifying');
		    	$('.sv_options_container_disable .verify').addClass("active");
		        break;
		    case 'FIXING':
		    	console.log('ongoing Fixing');
		    	$('.sv_options_container_disable .fix').addClass("active");
		        break;
		}
        $(".sv_options").off('change');

	}

	function end_of_validation(status) {
		console.log('end_of_validation');
		$(".sv_options").on('change', sv_option_event);
		$('#pword_required').hide();
		$('.verifying').html('');
		$('.sv_options').removeClass('active');
		verified_pword_status = 0;
		$(".sv_options_container").show();
		$(".sv_options_container_disable").hide();
		$(".sv_options_container_disable").children().removeAttr("disabled");
		//$(".sv_options_container .sv_options").removeClass('disabled');
		$('.sv_options_container label input').attr('checked',false);
		if(status == 'VERIFIED') {
		   $('.btn-submit').prop('disabled', false); 
		} else {
		   location.reload();
		}
	}

	function verifying(trans_id, senior_log) {
    	if(trans_id == conversation_id) {
    		<?php if( (($can_update_request && $are_you_operator) || $am_i_requester ) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') ){ ?>
    			console.log('can requester');
		    	ongoing();
	    		$('.verifying').css('display', 'inline-block').html('Ongoing '+verification_status.toLowerCase()+' by '+verified+'<img src="/images/verifying.svg">');
	    		if(senior_log != '') {
	    			var status = senior_log.status;
			    	var stamptime = senior_log.stamptime;
			    	var senior = senior_log.senior;
					$log_html = '<i class="fa fa-circle fa-1" aria-hidden="true"></i><span class="verified_status">'+status+' by '+senior+' : <time class="timeago" datetime="'+stamptime+'">'+stamptime+'</time></span><hr>';
					var client_status = '<?php echo $senior_log_current_status ?>';
					if(first_validation == 0 && client_status == '') {
						first_validation = 1;
						$('#senior_logs .senior_log_content').html('');
					}
	    			$('#senior_logs .senior_log_content').prepend($log_html);
	    		}
    		<?php } ?>
    	}
    	if(!operator_payment) {
    		$('#verify_section .toggle.btn').hide();
    	}

    }

    function senior_verified(trans_id, senior_log) {
    	console.log('JSON senior log');
    	var senior_log = jQuery.parseJSON(JSON.stringify(senior_log));
    	var status = senior_log.status;
    	var stamptime = senior_log.stamptime;
    	var senior = senior_log.senior;
		 $("time.timeago").timeago();
		$log_html = '<i class="fa fa-circle fa-1" aria-hidden="true"></i><span class="verified_status">'+status+' by '+senior+' : <time class="timeago" datetime="'+stamptime+'">'+stamptime+'</time></span><hr>';
    	if(trans_id == conversation_id) {
			$('#senior_logs .senior_log_content').prepend($log_html);
			end_of_validation(status);
		}
    }


    $('#toggle-event').bootstrapToggle({
     	on: 'Enabled',
    	off: 'Disabled'
    });

    $('#vpword').hide();
    $('#toggle-event').change(function() {
    	console.log('ONOFF change event');
	 	var sverify = $(this).prop('checked');
	 	console.log('sverify: '+sverify);
	 	if(sverify) {
	 		console.log('1');
	 		$('#vpword').show();
	 		$('.btn-submit').prop('disabled', true);
	 	} else {
	 		console.log('2');
	 		clear_pword();
	 		console.log('ID'+conversation_id+' - SENIOR: '+verified+' - pword: '+verified_pword_status);
	 		if(verified != '' && verified_pword_status != 0) {
	 			console.log('3');
	 			console.log('-- TRIGGER end_senior_verification: '+ conversation_id+', '+verified);
		 		socket.emit('end_senior_verification',{'trans_id':conversation_id, 'senior':verified, 'senior_validation_option':sv_option},function(response){
	    			console.log('server response - end_senior_verification'+response);
	    		});
	    		console.log('SENIOR NAME: '+verified);
	    		/*if(verified != '') {
	    			$('#toggle-event').bootstrapToggle('off');
	    			console.log('END NOT TRIGGER');
	    		}*/
	 		} else {
	 			console.log('4');
	 			$('.btn-submit').prop('disabled', false);
	 		}
	 	}
    });

    $('.btn-vpword').on('click', function() {
    	$('.val_pword, .verifying').hide();
    	var pword = $('[name="spword"]').val();
    	if(pword != '') {
    		console.log('-- TRIGGER start_senior_verification');
    		socket.emit('start_senior_verification',{'trans_id':conversation_id, 'pword':pword, 'senior_validation_option':sv_option},function(response){
    			console.log('server response - start_senior_verification'+response);
    		});
		} else {
			$('.val_pword').text('* password required').show();
		}
    });

    socket.on('client_start_senior_verification', function(data){
	    if(data.trans_id == conversation_id) {
	    	console.log('Transaction ID: '+conversation_id+' RETURN ID FROM SERVER: '+data.trans_id);
	    	<?php if( (($can_update_request && $are_you_operator)) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') ){ ?>
			console.log('client_start_senior_verification');
			$.ajax({
				type: "POST",
				url: '<?php echo base_url()?>SeniorVerify/start_senior_validation',
				data: {
					'trans_id'					: data.trans_id,
					'pword'						: data.pword,
					'senior_validation_option'	: data.senior_validation_option
				},
				dataType: 'json',
				success: function(response){
					console.log('RESPONSE FROM start_senior_validation');
					var result_start = jQuery.parseJSON(JSON.stringify(response));
					result_start.trans_id = data.trans_id;
					socket.emit('sending_update_start_senior_verify',result_start);
				}
			});
			<?php } ?>
		}
	});

	socket.on('receiving_update_start_senior_verify', function(response){
		console.log('receiving_update_start_senior_verify');
		console.log(response);
		var result = response;
		if(result.status != 'INVALID') {
			if(result.trans_id == conversation_id){
				verified = result.senior;
				verification_status = result.status;
				verifying(result.trans_id, result);
				verified_pword_status = 1;
			}
			verifying(result.trans_id, result);
			verified_pword_status = 1;
		} else {
			$('.val_pword').text('* wrong password').show();
		}
	});

	socket.on('client_end_senior_verification', function(data){
		if(data.trans_id == conversation_id) {
			console.log('Transaction ID: '+conversation_id+' RETURN ID FROM SERVER: '+data.trans_id);
			<?php if( (($can_update_request && $are_you_operator) ) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') ){ ?>
		  	console.log('client_end_senior_verification');
		  	$.ajax({
				type: "POST",
				url: '<?php echo base_url()?>SeniorVerify/end_senior_validation',
				data: {
					'trans_id'					: data.trans_id,
					'senior'					: data.senior,
					'senior_validation_option'	: data.senior_validation_option
				},
				dataType: 'json',
				success: function(response){
					//$('.btn-submit').prop('disabled', false);
					var result_end = jQuery.parseJSON(JSON.stringify(response));
					result_end.trans_id = data.trans_id;
					socket.emit('sending_update_end_senior_verify',result_end);
					// verification_status = result.status
					// senior_verified(data.trans_id, response);
	    			// 	verified = '';
				}
			});
			<?php } ?>
	  	}
	});

	socket.on('receiving_update_end_senior_verify', function(response){
		console.log('receiving_update_end_senior_verify');
		console.log(response);
		var result = response;
		verification_status = result.status
		senior_verified(result.trans_id, response);
		verified = '';
	});

	function clear_pword(){
		$('#vpword').hide();
		$('[name="spword"]').val('');
	 	$('.val_pword').text('');
	}
	/** .end of senior verification **/
	
	/*
	* Disable submit button after click
	*/
	$('#tasks-form form').on('submit', function() {
		console.log('disable btn-submit');
		$('.btn-submit').prop('disabled', true);
	});

	// rendering chat history
	socket.emit('subscribe', conversation_id);
	$('[name="messagewindow"]').animate({
	scrollTop: $('[name="messagewindow"]')[0].scrollHeight}, 2000);
	// add extra row event handler
	jQuery("time.timeago").timeago();
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
									.find('.add-more-row')
									.toggleClass('add-more-row delete-extra-row')
									.prop('src','<?php echo base_url()?>images/sign-delete-icon.png');
					    cloneIndex++;
					return false;
	});

	// delete extra row event handler
	$(document).on('click', '.delete-extra-row', function() {
					$(this).parents('[name="source-control"]').remove();
					return false;
	});

	// transaction type event handler
	$('[name="transaction_type_id"]').on('change', function (data) {
			var type = $('option:selected', $(this)).text();
			if (type == 'TRANSFER') { // toggle .to_website_game_div
				$('[name="row-control"]').toggle();
				$('[name="transfer-control"]').toggle();
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
				$('[name="bank_account_number_div"]').toggle();
				$('[name="bank_account_name_div"]').toggle();

			}else{
				$('[name="row-control"]').show();
				$('[name="transfer-control"]').hide();

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
		$.ajax({
		  type: "POST",
		  url: '<?php echo base_url()?>transaction/get_game_list',
		  data: {'website_id' : $(this).val()},
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
	});

	// return to dashboard page
	$('[name="return_btn"]').click(function(){
		if($('[name="reviewSuccessful"]').length){
			localStorage.setItem("fromSuccess", "1");
		}
		window.location.href = '<?php echo base_url()?>';
	});

	// check is there any unread msg then send socket to update
	if(localStorage["new_ms_"+conversation_id]){
		// console.log('read localStorage');
		// console.log(localStorage.getItem("new_ms_"+conversation_id));
		socket.emit('active_receiver',{'room':'<?php echo $request_transaction->transaction_id ?>', 'sender_id':'<?php echo $sender_id ?>', 'receiver_id':'<?php echo $receiver_id ?>'},function(fn){
			console.log('have seen chat');
			localStorage.removeItem("new_ms_"+conversation_id);
		}); // send data to sockter for update data
	}

});

(function() {

  'use strict';

  // define variables
  var items = document.querySelectorAll(".timeline li");

  // check if an element is in viewport
  // http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
  function isElementInViewport(el) {
    var rect = el.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  function callbackFunc() {
    for (var i = 0; i < items.length; i++) {
      if (isElementInViewport(items[i])) {
        items[i].classList.add("in-view");
      }
    }
  }

  // listen for events
  window.addEventListener("load", callbackFunc);
  window.addEventListener("resize", callbackFunc);
  window.addEventListener("scroll", callbackFunc);

})();
</script>


<?php if( (($can_update_request && $are_you_operator) || $am_i_requester ) && ($request_transaction->status != 'CANCELLED' && $request_transaction->status != 'SUCCESSFUL') ){ ?>
<script type="text/javascript" async>
console.log('i am requester / operator');
$(document).on('click','[action="accept-pending"]',function(){
	$transaction_id = $(this).data('target');
  $.ajax({
    method: "POST",
    url: 'transaction/update_pending_transaction',
    data: {'transaction_id': $transaction_id, 'status': 'ACCEPT'},
    dataType: "JSON",
    success: function(data){
     //  console.log(data);
      if(data.state){ // if true display notification
        // $.notify(data.message, {className: data.message_status.toLowerCase()});
        // play_new_request_audio(); // play sound for new request
        console.log('accept pending request');
        $('[name="confirm_pending"]').html(''); // clearing form
        $.notify('You accepted request to pending transaction.', {className: 'info'});
        update_notification_bar();
				update_content($transaction_id);
      }
    }
  });
});



$(document).on('click','[action="decline-pending"]',function(){
	$transaction_id = $(this).data('target');
  $.ajax({
    method: "POST",
    url: 'transaction/update_pending_transaction',
    data: {'transaction_id': $transaction_id, 'status': 'DECLINE'},
    dataType: "JSON",
    success: function(data){
     //  console.log(data);
      if(data.state){ // if true display notification
        // $.notify(data.message, {className: data.message_status.toLowerCase()});
        // play_new_request_audio(); // play sound for new request
        console.log('decline pending request');
        // $('[name="confirm_pending"]').html(''); // clearing form
        $.notify('You declined request to pending transaction.', {className: 'info'});
        update_notification_bar();
				update_content($transaction_id);
      }
    }
  });
});

var conversation_id = '<?php echo $request_transaction->transaction_id ?>';
$('#btn-input').on('keypress',function(e){
	if(e.keyCode == 13){
		$('#btn-chat').click();
	}
});

$('#btn-chat').on('click', function() {
	console.log('Chat message sent');
	var dt = new Date();
	var mysql = dt.getMySQL();
	$message = $('#btn-input').val();
	$sender_id = '<?php echo $sender_id ?>';
	$receiver_id = '<?php echo $receiver_id ?>';
	$sender = '<?php echo decrypt($this->session->userdata('user_data'))['username']; ?>';
	socket.emit('send_message', {
	    room: conversation_id,
	    message: $message,
			sender_id: $sender_id,
			receiver_id: $receiver_id,
			sender: $sender
	});
	$('#btn-input').val('');
	var chat_content = '';
	chat_content += '<li class="right clearfix"><span class="chat-img pull-right">';
	chat_content += '<img src="http://placehold.it/50/FA6F57/fff&amp;text='+($sender.substring(0,2)).toUpperCase()+'" alt="User Avatar" class="img-circle">';
	chat_content += '</span>';
	chat_content += '<div class="chat-body clearfix">';
	chat_content += '<div class="header">';
	chat_content += '<small class=" text-muted"><span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="'+mysql+'">'+mysql+'</time></small>';
	chat_content += '<strong class="pull-right primary-font"><?php echo decrypt($this->session->userdata('user_data'))['username']; ?></strong>';
	chat_content += '</div>';
	chat_content += '<p>';
	chat_content += $message;
	chat_content += '</p>';
	chat_content += '</div>';
	chat_content += '</li>';
	$('ul.chat').append(chat_content);
	jQuery("time.timeago").timeago();
	$('[name="messagewindow"]').animate({
  scrollTop: $('[name="messagewindow"]')[0].scrollHeight}, 2000);
});
</script>
<?php } ?>
<script type="text/javascript" async>
var is_window_focus = true;
var is_tab_active = true;
var transaction_id = '<?php echo encrypt($request_transaction->transaction_id); ?>';
socket.on('conversation_private_post', function(data) {
	var dt = new Date();
	var mysql = dt.getMySQL();
    console.log(data);
		var sender = data.sender;
		var chat_content = '';
		chat_content += '<li class="left clearfix"><span class="chat-img pull-left">';
		chat_content += '<img src="http://placehold.it/50/55C1E7/fff&amp;text='+sender.substring(0,2).toUpperCase()+'" alt="User Avatar" class="img-circle">';
		chat_content += '</span>';
		chat_content += '<div class="chat-body clearfix">';
		chat_content += '<div class="header">';
		chat_content += '<strong class="primary-font">'+data.sender+'</strong> <small class="pull-right text-muted">';
		chat_content += '<span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="'+mysql+'">'+mysql+'</time></small>';
		chat_content += '</div>';
		chat_content += '<p>';
		chat_content += data.message;
		chat_content += '</p>';
		chat_content += '</div>';
		chat_content += '</li>';
		$('ul.chat').append(chat_content);
		jQuery("time.timeago").timeago();
		$('[name="messagewindow"]').animate({
		scrollTop: $('[name="messagewindow"]')[0].scrollHeight}, 2000);

		var conversation_id = data.room;
    var my_id = '<?php echo decrypt($this->session->userdata('user_data'))['user_id'] ?>';
		console.log(data);
		console.log('m.id : '+my_id);
		console.log('d.id : '+data.receiver);
		console.log('d.id : '+data.sender_id);
		if(my_id == data.receiver){
			console.log('checking');
			if(is_window_focus && is_tab_active){
				console.log('active');
				socket.emit('active_receiver',{'room':data.room, 'sender_id':my_id, 'receiver_id':data.sender_id},function(fn){
					console.log('have seen chat');
					localStorage.removeItem("new_ms_"+conversation_id);
				}); // send data to sockter for update data
			}else{
				console.log('non-active');
				// set / add localStorage msg flag by room id
				console.log('creadte localStorage');
				localStorage.setItem("new_ms_"+conversation_id, true);
				$.titleAlert("Yor have new message!", {
				    requireBlur:true,
				    stopOnFocus:true,
				    duration:30000,
				    interval:3000
				});
				$('.chat li:last-child').addClass('animated infinite pulse');
				// create and play new msg audio
				play_new_msg_audio();
			}
		}

});

	socket.on('update_pending_transaction', function(message){
		console.log('extends socket on update_pending_transaction A');
		console.log(message);
		console.log('transaction_id == message.transaction_id');
		console.log(transaction_id);
		console.log(message.transaction_id);
		console.log(transaction_id == message.transaction_id);

		// if transaction id not match then skip
		if(transaction_id == message.transaction_id){
			console.log('update current pending status content');
			update_content(message.transaction_id, true);
		}
		// custom function to update only log_content

		// update_content(message.transaction_id, true)

		// function update_content(id, log_only)
	});

// extends socket event for update_request_transaction to update page contents
 socket.on('update_request_transaction', function(message){
	 console.log('extends socket on update_request_transaction B');
	 console.log(message);
	 console.log('transaction_id == message.transaction_id');
	 console.log(transaction_id);
	 console.log(message.transaction_id);
	 console.log(transaction_id == message.transaction_id);
	 if(transaction_id == message.transaction_id){
		 update_content(message.transaction_id, false);
	 }
	//  $.ajax({
	// 	 method: "POST",
	// 	 url: 'transaction/get_request_transaction_update',
	// 	 data: message,
	// 	 dataType: "JSON",
	// 	 success: function(data){
	// 		 console.log(data);
	// 		 var log_array = JSON.parse(data.log_info).reverse();
	// 		 console.log('log_array');
	// 		 console.log(log_array);
	// 		 console.log('loop_array');
	// 		 var content = '';
	// 		 for (var log in log_array) {
	// 		   console.log(log_array[log]);
	// 			 content += '<span>'+log_array[log].complete_name+'<<time class="timeago" datetime="'+log_array[log].timestamp+'">'+log_array[log].timestamp+'</time>> : '+log_array[log].content+'<hr style="margin:5 !important;border: 0 !important; border-bottom: 1px dotted #999999 !important;"></span>';
	// 			//  $('log_content').html('<span>'+log_array[log].complete_name+'<<time class="timeago" datetime="'+log_array[log].timestamp+'">'+log_array[log].timestamp+'</time>> : '+log_array[log].content+'<hr style="margin:5 !important;border: 0 !important; border-bottom: 1px dotted #999999 !important;"></span>');
	// 		 }
	// 		 $('[name="log_content"]').fadeOut(500, function() {
	// 				 $(this).html(content).fadeIn(500); // update total_deposit
	// 		 });
	// 		 $('[name="status_content"]').fadeOut(500, function() {
	// 				 $(this).html(data.status).fadeIn(500); // update total_deposit
	// 		 });
	// 		 if($('[name="remark_content"]').text() !== data.remark){
  //        $('[name="remark_content"]').fadeOut(500, function() {
  //            $(this).text(data.remark).fadeIn(500); // update total_deposit
  //        });
  //      }
	// 		 if(data.status == "PENDING"){
	// 			 console.log('remain console');
	// 		 }else{
	// 			 console.log('clear console');
	// 			 $('[name="confirm_pending"]').html('');
	// 		 }
	// 		//  echo '<span>'.$v->complete_name.' <<time class="timeago" datetime="'.$v->timestamp.'">'.$v->timestamp.'</time>> : '.$v->content.'<hr style="margin:5 !important;border: 0 !important;border-bottom: 1px dotted #999999 !important;"></span>';
	// 	 }
	//  });
 });

 function update_content(e, log_only){
	 $.ajax({
		 method: "POST",
		 url: 'transaction/get_request_transaction_update',
		 data: {'transaction_id': e},
		 dataType: "JSON",
		 success: function(data){
			 console.log('problem here try to debugging');
			 console.log(data);
			 console.log(data.status);
			 var log_array = JSON.parse(data.log_info).reverse();
			 console.log('log_array');
			 console.log(log_array);
			 console.log('loop_array');
			 var content = '';
			 for (var log in log_array) {
				 console.log(log_array[log]);
				 content += '<span>'+log_array[log].complete_name+'<<time class="timeago" datetime="'+log_array[log].timestamp+'">'+log_array[log].timestamp+'</time>> : '+log_array[log].content+'<hr style="margin:5 !important;border: 0 !important; border-bottom: 1px dotted #999999 !important;"></span>';
				//  $('log_content').html('<span>'+log_array[log].complete_name+'<<time class="timeago" datetime="'+log_array[log].timestamp+'">'+log_array[log].timestamp+'</time>> : '+log_array[log].content+'<hr style="margin:5 !important;border: 0 !important; border-bottom: 1px dotted #999999 !important;"></span>');
			 }

			 $('[name="log_content"]').fadeOut(500, function() {
					 $(this).html(content).fadeIn(500); // update total_deposit
			 });

			 if(!log_only){
				 if($('[name="status_content"]').text() !== data.status){
						$('[name="status_content"]').fadeOut(500, function() {
								$(this).text(data.status).fadeIn(500); // update total_deposit
						});
					}

				 if($('[name="remark_content"]').text() !== data.remark){
						$('[name="remark_content"]').fadeOut(500, function() {
								$(this).text(data.remark).fadeIn(500); // update total_deposit
						});
					}
				 if(data.status == "PENDING"){
					 console.log('remain console');
					//  var html = '';
					//  html += '<span>'+data.pending_reason+'</span>';
					//  html += '<button type="button" class="btn btn-danger pull-right" name="n_confirm_pending_btn" action="decline-pending" data-target="'+e+'">Decline</button>';
					//  html += '<button type="button" class="btn btn-success pull-right" name="y_confirm_pending_btn" action="accept-pending" data-target="'+e+'" style="margin-right:5px;">Accept</button>';
					//  $('[name="confirm_pending"]').html(html);
				 }else{
					 console.log('clear console');
					 $('[name="confirm_pending"]').html('');
				 }
			 }
			//  echo '<span>'.$v->complete_name.' <<time class="timeago" datetime="'.$v->timestamp.'">'.$v->timestamp.'</time>> : '.$v->content.'<hr style="margin:5 !important;border: 0 !important;border-bottom: 1px dotted #999999 !important;"></span>';
		 }
	 });
 }

(function() {
	Date.prototype.getMySQL = getMySQLDateTime;
	function getMySQLDateTime() {
		var year, month, day, hours, minutes, seconds;
		year = String(this.getFullYear());
		month = String(this.getMonth() + 1);
		if (month.length == 1) {
			month = "0" + month;
		}
		day = String(this.getDate());
		if (day.length == 1) {
			day = "0" + day;
		}
		hours = String(this.getHours());
		if (hours.length == 1) {
			hours = "0" + hours;
		}
		minutes = String(this.getMinutes());
		if (minutes.length == 1) {
			minutes = "0" + minutes;
		}
		seconds = String(this.getSeconds());
		if (seconds.length == 1) {
			seconds = "0" + seconds;
		}
		// should return something like: 2011-06-16 13:36:00
		return year + "-" + month + "-" + day + ' ' + hours + ':' + minutes + ':' + seconds;
	}
})();

/////////////////////////////////////////
// main visibility API function
// check if current tab is active or not
var vis = (function(){
    var stateKey,
        eventKey,
        keys = {
                hidden: "visibilitychange",
                webkitHidden: "webkitvisibilitychange",
                mozHidden: "mozvisibilitychange",
                msHidden: "msvisibilitychange"
    };
    for (stateKey in keys) {
        if (stateKey in document) {
            eventKey = keys[stateKey];
            break;
        }
    }
    return function(c) {
        if (c) document.addEventListener(eventKey, c);
        return !document[stateKey];
    }
})();


/////////////////////////////////////////
// wtf complex logic

// check if current tab is active or not
vis(function(){
		if(vis()){
			console.log("tab is visible - has focus");
			is_tab_active = false;
		} else {
			console.log("tab is invisible - has blur");
			is_tab_active = false;
		}
});

// check if browser window has focus
var notIE = (document.documentMode == undefined),
		isChromium = window.chrome;

if (notIE && !isChromium) {

		// checks for Firefox and other  NON IE Chrome versions
		$(window).on("focusin", function () {
			 console.log("focus");
			 // to set global param for checking is_window_active
			 is_window_focus = true;
			 // check local storage first
			 var conversation_id = '<?php echo $request_transaction->transaction_id ?>';
			 var my_id = '<?php echo decrypt($this->session->userdata('user_data'))['user_id'] ?>';
			 var sender_id = '<?php echo $sender_id ?>';
			 var receiver_id = '<?php echo $receiver_id ?>';
			 console.log('tm.id : '+my_id);
			 console.log('ts.id : '+sender_id);
			 console.log('tr.id : '+receiver_id);
			 if(localStorage["new_ms_"+conversation_id]){
				 socket.emit('active_receiver',{'room':conversation_id, 'sender_id':'<?php echo $sender_id ?>', 'receiver_id':'<?php echo $receiver_id ?>'},function(fn){
					 console.log('have seen chat');
					 localStorage.removeItem("new_ms_"+conversation_id);
				 }); // send data to sockter for update data
			 }
		}).on("focusout", function () {
				console.log("blur");
				is_window_focus = false;
		});

} else {

		// checks for IE and Chromium versions
		if (window.addEventListener) {

				// bind focus event
				window.addEventListener("focus", function (event) {
					console.log("focus");
					is_window_focus = true;
					var conversation_id = '<?php echo $request_transaction->transaction_id ?>';
					var my_id = '<?php echo decrypt($this->session->userdata('user_data'))['user_id'] ?>';
					var sender_id = '<?php echo $sender_id ?>';
					var receiver_id = '<?php echo $receiver_id ?>';
					console.log('tm.id : '+my_id);
					console.log('ts.id : '+sender_id);
					console.log('tr.id : '+receiver_id);
					if(localStorage["new_ms_"+conversation_id]){
	 				 socket.emit('active_receiver',{'room':conversation_id, 'sender_id':'<?php echo $sender_id ?>', 'receiver_id':'<?php echo $receiver_id ?>'},function(fn){
	 					 console.log('have seen chat');
	 					 localStorage.removeItem("new_ms_"+conversation_id);
						 $('.chat li.animated').removeClass('animated infinite pulse');
	 				 }); // send data to sockter for update data
	 			 }
				}, false);

				// bind blur event
				window.addEventListener("blur", function (event) {
					console.log("blur");
					is_window_focus = false;
				}, false);

		} else {

				// bind focus event
				window.attachEvent("focus", function (event) {
					console.log("focus");
					is_window_focus = true;
				});

				// bind focus event
				window.attachEvent("blur", function (event) {
						console.log("blur");
						is_window_focus = FALSE;
				});
		}
}

// reason for pendding
(function() {
	$('[name="status"]').on('change', function (data) {
		var type = $('option:selected', $(this)).val();
		if(type=="PENDING"){
			$('[name="reason-control"]').show();
		}else{
			$('[name="reason-control"]').hide();
		}
	});
})();

</script>
