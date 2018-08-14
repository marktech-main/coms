<?php $this->load->view("impl/header.php");?>

		<div id="page-wrapper">
			<div class="row" id="tasks-form">
				<div class="col-lg-6">
                <h1 class="page-header">Profile</h1>
                    <form role="form" method="post" action="<?php echo base_url()?>profile">
                      <div class="form-group">
                          <label class="col-lg-3 col-sm-3">Division Name</label>
                          <div class="col-lg-9 col-sm-9">
                            <?php echo $user_profile->division_name; ?>
                          </div>
                          <div class="clearfix"></div>
                      </div>
											<div class="form-group">
													<label class="col-lg-3 col-sm-3">Role</label>
													<div class="col-lg-9 col-sm-9">
                            <?php echo $user_profile->user_role_name; ?>
													</div>
													<div class="clearfix"></div>
											</div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3">Complete Name</label>
                            <div class="col-lg-9 col-sm-9">
                              <?php echo $user_profile->complete_name; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3">Username</label>
                            <div class="col-lg-9 col-sm-9">
                            <?php echo $user_profile->username; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3">Email</label>
                            <div class="col-lg-9 col-sm-9">
                              <?php echo $user_profile->email; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div name="bank_account_number_div" class="form-group" style="margin-bottom:16px;">
                            <label class="col-lg-3 col-sm-3">Current Password</label>
                            <div>
                              <div class="col-lg-4 col-sm-4">
                                <input type="password" name="oldpwd" class="form-control">
                              </div>
                              <div class="col-lg-5 col-sm-5">
                                <span> Type current password </span>
                              </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div name="bank_account_number_div" class="form-group">
                            <label class="col-lg-3 col-sm-3">New Password</label>
                            <div>
                              <div class="col-lg-4 col-sm-4">
                                <input type="password" name="newpwd" class="form-control">
                              </div>
                              <div class="col-lg-5 col-sm-5">
                                <span> 8-16 alpha-numeric (a-z, A-Z, 0-9) characters ONLY, CASE SENSITIVE </span>
                              </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div name="bank_account_number_div" class="form-group">
                            <label class="col-lg-3 col-sm-3">Verify New Password</label>
                            <div>
                              <div class="col-lg-4 col-sm-4">
                                <input type="password" name="confirmpwd" class="form-control">
                              </div>
                              <div class="col-lg-5 col-sm-5">
                                <span> re-enter new password again </span>
                              </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <input type="hidden" name="transaction_id" value="1" />
												<button type="submit" class="btn btn-submit pull-right">Submit</button>
                    </form>
										<div><?php echo validation_errors(); ?></div>
                </div>
			</div><!-- /.col-lg-6 -->
			<div class="clearfix"></div>
			<!-- /.row -->
		</div>

<?php $this->load->view("impl/footer.php");?>
