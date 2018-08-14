<?php $this->load->view("impl/header.php");?>

		<div id="page-wrapper">
			<div class="row" id="tasks-form">
				<div class="col-lg-6">
					<h1 class="page-header">Add User Form</h1>
										<iframe name="x" style="display:none;" ></iframe>
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
                        <button type="submit" class="btn btn-submit pull-right">Submit</button>
                    </form>
										<div><?php echo validation_errors(); ?></div>
                </div>
			</div><!-- /.col-lg-6 -->
			<div class="clearfix"></div>
			<!-- /.row -->
		</div>

<?php $this->load->view("impl/footer.php");?>
