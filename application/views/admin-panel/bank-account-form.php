<?php $this->load->view("impl/header.php");?>

		<div id="page-wrapper">
			<div class="row" id="tasks-form">
				<div class="col-lg-6">
					<h1 class="page-header">Add Bank Account Form</h1>
										<iframe name="x" style="display:none;" ></iframe>
                    <?php if(isset($customer_bank_account)){?>
                      <form role="form" method="post" action="<?php echo base_url()?>AdminPanel/updateBankAccount">
  											<div class="form-group">
  													<label class="col-lg-3 col-sm-3">Account Name</label>
  													<div class="col-lg-9 col-sm-9">
                              <input type="text" name="account_name" class="form-control" value="<?php echo $customer_bank_account->customer_bank_account_name; ?>">
  													</div>
  													<div class="clearfix"></div>
  											</div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3">Account Number</label>
                            <div class="col-lg-9 col-sm-9">
                              <input type="number" name="account_number" class="form-control" value="<?php echo $customer_bank_account->customer_bank_account_number; ?>">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <input type="hidden" name="customer_bank_account_id" value="<?php echo encrypt($customer_bank_account->customer_bank_account_id); ?>" />
                        <button type="submit" class="btn btn-submit pull-right">Submit</button>
                      </form>
                    <?php }else{?>
                      <form role="form" method="post" action="<?php echo base_url()?>AdminPanel/addBankAccount">
  											<div class="form-group">
  													<label class="col-lg-3 col-sm-3">Account Name</label>
  													<div class="col-lg-9 col-sm-9">
                              <input type="text" name="account_name" class="form-control">
  													</div>
  													<div class="clearfix"></div>
  											</div>
                        <div class="form-group">
                            <label class="col-lg-3 col-sm-3">Account Number</label>
                            <div class="col-lg-9 col-sm-9">
                              <input type="number" name="account_number" class="form-control">
                            </div>
                            <div class="clearfix"></div>
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
