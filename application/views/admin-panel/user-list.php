<?php $this->load->view("impl/header.php");?>
<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <div class="table-responsive">
          <table id="dt_user" width="100%" name="dt_user"
                 class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                 aria-describedby="dt_basic_info">
              <thead>
              <tr>
                  <th>Division</th>
                  <th>Role</th>
                  <th>Complete Name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Active</th>
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

  window.oTable = $('#dt_user').dataTable({
      "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
      "t"+
      "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
      "order": [[ 3, "asc" ]],
      "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, 'ALL'] ],
      "autoWidth" : false,
      processing: true,
      serverSide: true,
      "ajax": {
          url: 		'<?php echo base_url()?>AdminPanel/get_json_user_list',
          type: 		'POST',
          dataType: 	'json'
      },
      "aoColumns": [
          { "mData": "division_name" },
          { "mData": "user_role_name" },
          { "mData": "complete_name" },
          { "mData": "username" },
          { "mData": "email" },
          {
              "mData": "is_active",
              "render":function(data, type, full, meta){
                  switch (data) {
                      case '1' :
                          $html='';
                          $html += '<div class="onoffswitch">';
                          $html += '<input type="checkbox" data-target="'+full.user_id+'" name="user_active_'+full.username+'" class="onoffswitch-checkbox" id="user_active_'+full.username+'" action="toggle_active" checked>';
                          $html += '<label class="onoffswitch-label" for="user_active_'+full.username+'">';
                          $html += '<span class="onoffswitch-inner"></span>';
                          $html += '<span class="onoffswitch-switch"></span>';
                          $html += '</label>';
                          $html += '</div>';
                          return $html;
                          break;
                      case '0' :
                          $html='';
                          $html += '<div class="onoffswitch">';
                          $html += '<input type="checkbox" data-target="'+full.user_id+'" name="user_active_'+full.username+'" class="onoffswitch-checkbox" id="user_active_'+full.username+'" action="toggle_active">';
                          $html += '<label class="onoffswitch-label" for="user_active_'+full.username+'">';
                          $html += '<span class="onoffswitch-inner"></span>';
                          $html += '<span class="onoffswitch-switch"></span>';
                          $html += '</label>';
                          $html += '</div>';
                          return $html;
                          break;
                      default :
                          return 'N/A';
                          break;
                  }
              },
              "sWidth": "10%",
              "sClass": "center",
              targets: 'no-sort',
              orderable: false
          },
          {
              "mData": "user_id",
              "render":function(data, type, full, meta){
                  $html='';
                  $html +='<a class="btn btn-success btn-xs"  action="edit" data-target="'+full.user_id+'"><i class="fa fa-cog fa-fw fa-pulse"></i>edit</a>';
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
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_user'), breakpointDefinition);
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

  $(document).on('change','[action="toggle_active"]', function(e) {
      e.preventDefault;
      $user_id = e.target.dataset.target;
      $user_active = $(this).is(":checked") ? 1 : 0;
      // console.log([$user_id, $user_active])
      $.ajax({
        method: "POST",
        url: '<?php echo base_url()?>AdminPanel/change_user_active',
        data: {'user_id' : $user_id, 'user_active': $user_active},
        dataType: "JSON",
        success: function(data){
          console.log(data);
         $.notify(data.message, {className: data.status});
        }
      });
  });

} );

// redirect to add-bank-account-form
$(document).on('click', '[action="edit"]', function(){
 var redirect = 'updateUser';
 $.redirectPost(redirect, {user_id: $(this).data('target'), state: '1'});
});
</script>
