<?php $this->load->view("impl/header.php");?>
<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <div class="table-responsive">
          <table id="dt_customer_bank_account" width="100%" name="dt_customer_bank_account"
                 class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                 aria-describedby="dt_basic_info">
              <thead>
              <tr>
                  <th>Account Name</th>
                  <th>Account Number</th>
                  <th>Created By</th>
                  <th>Created Date</th>
                  <th>Updated By</th>
                  <th>Updated Date</th>
                  <th></th>
              </tr>
              </thead>
              <tbody>

              </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view("impl/footer.php");?>
<script>
//
// DataTables initialisation
//
$(document).ready(function() {
  /* Responsive Table */
  var responsiveHelper_dt_basic = undefined;
  var responsiveHelper_datatable_fixed_column = undefined;
  var responsiveHelper_datatable_col_reorder = undefined;
  var responsiveHelper_datatable_tabletools = undefined;

  var breakpointDefinition = {
      tablet : 1024,
      phone : 480
  };
  $.fn.dataTable.ext.errMode = 'throw';

  window.oTable = $('#dt_customer_bank_account').dataTable({
      "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
      "t"+
      "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
      "order": [[ 3, "desc" ]],
      "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, 'ALL'] ],
      "autoWidth" : false,
      processing: true,
      serverSide: true,
      "ajax": {
          url: 		'<?php echo base_url()?>AdminPanel/get_json_customer_bank_account_list',
          type: 		'POST',
          dataType: 	'json'
      },
      "aoColumns": [
          { "mData": "customer_bank_account_name" },
          { "mData": "customer_bank_account_number" },
          { "mData": "created_by_name" },
          { "mData": "created_date" },
          { "mData": "updated_by_name" },
          { "mData": "updated_date" },
          {
              "mData": "customer_bank_account_id",
              "render":function(data, type, full, meta){
                  $html='';
                  $html +='<a class="btn btn-success btn-xs"  action="edit" data-target="'+full.customer_bank_account_id+'"><i class="fa fa-cog fa-fw fa-pulse"></i>edit</a>';
                  return $html;
              },
              "sClass": "center",
              targets: 'no-sort',
              orderable: false
          }
      ],
      "preDrawCallback" : function() {
          // Initialize the responsive datatables helper once.
          if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_customer_bank_account'), breakpointDefinition);
          }
      },
      "rowCallback" : function(nRow) {
          responsiveHelper_dt_basic.createExpandIcon(nRow);
      },
      "drawCallback" : function(oSettings) {
          responsiveHelper_dt_basic.respond();
      },
      language: {
          searchPlaceholder: "Search records"
      }
  });
} );

// redirect to add-bank-account-form
$(document).on('click', '[action="edit"]', function(){
 var redirect = 'updateBankAccount';
 $.redirectPost(redirect, {customer_bank_account_id: $(this).data('target'), state: '1'});
});
</script>
