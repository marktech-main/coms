<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

<div class="container">
	<br>  
	<p class="text-center">Affiliate</p>
	<hr>
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
			<header class="card-header">
				<h4 class="card-title mt-2">Register</h4>
			</header>
			<article class="card-body">
			<form method="POST" action="https://coms.super7tech.com/api/register/create">
				<div class="form-group">
					<label>Customer Name</label>
					<input type="text" class="form-control" placeholder="" name="customer_id">
				</div>
				<div class="form-group">
					<label>Amount</label>
					<input type="text" class="form-control" placeholder="" name="amount">
				</div>
				<div class="form-group">
					<label>Website ID</label>
					<input type="text" class="form-control" placeholder="" name="website_id">
				</div>
				<div class="form-group">
					<label>Game ID</label>
					<input type="text" class="form-control" placeholder="" name="from_game_id[]">
				</div>
				<div class="form-group">
					<label>Game Amount</label>
					<input type="text" class="form-control" placeholder="" name="game_amount[]">
				</div>
				<div class="form-group">
					<label>Acct. Name</label>
					<input type="text" class="form-control" placeholder="" name="bank_account_name">
				</div>
				<div class="form-group">
					<label>Acct. Destination</label>
					<input type="text" class="form-control" placeholder="" name="bank_account_number">
				</div>
				<div class="form-group">
					<label>Remark</label>
					<textarea class="form-control" name="remark"></textarea>
				</div>
			    <div class="form-group">
			        <button type="submit" class="btn btn-primary btn-block"> Register  </button>
			    </div>
			    
			</form>
			</article> <!-- card-body end .// -->
			</div> <!-- card.// -->
		</div> <!-- col.//-->
	</div> <!-- row.//-->
</div> 
<!--container end.//-->
