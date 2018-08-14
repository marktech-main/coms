<?php $this->load->view("impl/header.php");?>

		<div id="page-wrapper">
			<div class="row" id="tasks-form">
				<div class="col-lg-6">
					<h1 class="page-header">Add User Form</h1>
										<iframe name="x" style="display:none;" ></iframe>
										<?php if(isset($user_account)){?>
											<form role="form" method="post" action="<?php echo base_url()?>AdminPanel/updateUser">
												<div class="form-group">
														<label class="col-lg-3 col-sm-3">Division</label>
														<div class="col-lg-9 col-sm-9">
															<select name="division_id" class="form-control">
																<option value="" selected hidden disabled>Choose Division</option>
	                              <?php
																	foreach ($division_list as $division) {
																		echo '<option value="'.encrypt($division['division_id']).'" '.($division['division_id'] == $user_account->division_id ? "selected" : "").' >'.$division['division_name'].'</option>';
																	}
																?>
															</select>
														</div>
														<div class="clearfix"></div>
												</div>
	                      <div class="form-group">
	                          <label class="col-lg-3 col-sm-3">User Role</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <select name="user_role_id" class="form-control">
	                              <option value="" selected hidden disabled>Choose User Role</option>
	                              <?php
																	foreach ($user_role_list as $user_role) {
																		echo '<option value="'.encrypt($user_role['user_role_id']).'" '.($user_role['user_role_id'] == $user_account->user_role_id ? "selected" : "").' >'.$user_role['user_role_name'].'</option>';
																	}
																?>
	                            </select>
	                          </div>
	                          <div class="clearfix"></div>
	                      </div>
	                      <div class="form-group">
	                          <label class="col-lg-3 col-sm-3">Complete Name</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <input type="text" name="complete_name" class="form-control" value="<?php echo $user_account->complete_name; ?>">
	                          </div>
	                          <div class="clearfix"></div>
	                      </div>
	                      <div class="form-group">
	                          <label class="col-lg-3 col-sm-3">Email</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <input type="email" name="email" class="form-control" value="<?php echo $user_account->email; ?>">
	                          </div>
	                          <div class="clearfix"></div>
	                      </div>
	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Username</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="text" name="username" class="form-control" value="<?php echo $user_account->username; ?>">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Password</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="text" name="password" class="form-control" value="<?php echo decrypt($user_account->password); ?>">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>
													<div class="form-group">
														<label class="col-lg-3 col-sm-3">Is Payment?</label>
														<div class="col-lg-9 col-sm-9">
															<div class="onoffswitch">
																	<input type="checkbox" name="is_ppr_payment" class="onoffswitch-checkbox" id="is_ppr_payment" <?php echo ($user_account->is_ppr_payment == TRUE) ? ' checked' : ''; ?> >
																	<label class="onoffswitch-label" for="is_ppr_payment">
																			<span class="onoffswitch-inner"></span>
																			<span class="onoffswitch-switch"></span>
																	</label>
															</div>
														</div>
													</div>
													<input type="hidden" name="user_id" class="form-control" value="<?php echo encrypt($user_account->user_id); ?>">
	                        <button type="submit" class="btn btn-submit pull-right">Submit</button>
	                    </form>
                    <?php }else{?>
											<form role="form" method="post" action="<?php echo base_url()?>AdminPanel/addUser">
												<div class="form-group">
														<label class="col-lg-3 col-sm-3">Division</label>
														<div class="col-lg-9 col-sm-9">
															<select name="division_id" class="form-control">
																<option value="" selected hidden disabled>Choose Division</option>
	                              <?php
																	foreach ($division_list as $division) {
																		echo '<option value="'.encrypt($division['division_id']).'">'.$division['division_name'].'</option>';
																	}
																?>
															</select>
														</div>
														<div class="clearfix"></div>
												</div>
	                      <div class="form-group">
	                          <label class="col-lg-3 col-sm-3">User Role</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <select name="user_role_id" class="form-control">
	                              <option value="" selected hidden disabled>Choose User Role</option>
	                              <?php
																	foreach ($user_role_list as $user_role) {
																		echo '<option value="'.encrypt($user_role['user_role_id']).'">'.$user_role['user_role_name'].'</option>';
																	}
																?>
	                            </select>
	                          </div>
	                          <div class="clearfix"></div>
	                      </div>
	                      <div class="form-group">
	                          <label class="col-lg-3 col-sm-3">Complete Name</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <input type="text" name="complete_name" class="form-control">
	                          </div>
	                          <div class="clearfix"></div>
	                      </div>
	                      <div class="form-group">
	                          <label class="col-lg-3 col-sm-3">Email</label>
	                          <div class="col-lg-9 col-sm-9">
	                            <input type="email" name="email" class="form-control">
	                          </div>
	                          <div class="clearfix"></div>
	                      </div>
	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Username</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="text" name="username" class="form-control">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Password</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="password" name="password" class="form-control">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

	                        <div class="form-group">
	                            <label class="col-lg-3 col-sm-3">Confirm Password</label>
	                            <div class="col-lg-9 col-sm-9">
	                            	<input type="password" name="confirm_password" class="form-control">
	                            </div>
	                            <div class="clearfix"></div>
	                        </div>

													<div class="form-group">
														<label class="col-lg-3 col-sm-3">Is Payment?</label>
														<div class="col-lg-9 col-sm-9">
															<div class="onoffswitch">
																	<input type="checkbox" name="is_ppr_payment" class="onoffswitch-checkbox" id="is_ppr_payment">
																	<label class="onoffswitch-label" for="is_ppr_payment">
																			<span class="onoffswitch-inner"></span>
																			<span class="onoffswitch-switch"></span>
																	</label>
															</div>
														</div>
													</div>

	                        <button type="submit" class="btn btn-submit pull-right">Submit</button>
	                    </form>
                    <?php }?>
										<div><?php echo validation_errors(); ?></div>
        </div>
			</div><!-- /.col-lg-6 -->
			<div class="clearfix"></div>
			<!-- /.row -->
		</div>

<?php $this->load->view("impl/footer.php");?>
