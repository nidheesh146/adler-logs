@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> <span>Agents</span> <span>All Agents</span> </div>
			<h4 class="az-content-title" style="font-size: 20px;">Agents
              <div class="right-button">
                
                  <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="http://kssp.com/agent/agents?download=excel" class="dropdown-item">Excel</a>
          
                  </div>
              <div>  
              </div>
              <button style="float: right;font-size: 14px;" onclick="document.location.href='http://kssp.com/agent/create/state'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Agent  </button>
          </div>
        </h4>
			<div class="az-dashboard-nav">
				<nav class="nav"> </nav>
			</div>
	
			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th>Agent ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>PIN code</th>
							<th>Credit limit /
								<br> Balance</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody >
						<tr>
							<th> <a href=" http://kssp.com/agent/create/agent/gdp">AG03</a> </th>
							<th>Connor Flores Kyla Barnes</th>
							<td>kanoxi@mailinator.com</td>
							<td>9037715996</td>
							<td>3541</td>
							<th><span style="float: right;">1000000.000 / 
                                                                          <a href="http://kssp.com/agent-payment/state/gdp"><span style="color: red;">-166.320</span></a>
								</span>
							</th>
							<td>
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu"> <a href="http://kssp.com/agent-subscription/create/agent/gdp" class="dropdown-item"><i class="fas fa-paper-plane" style="font-size: 13px;margin: 0;"></i> 
                                          Order request
                                      </a> <a href=" http://kssp.com/agent/create/agent/gdp" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> <a href=" http://kssp.com/agent-subscribers-action/agents/gdp/action" class="dropdown-item" onclick="return confirm('Are you sure you want to deactive this ?');"><i class="fas fa-times"></i> Deactive</a> <a href=" http://kssp.com/agent-subscribers-action/agents/gdp/delete" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> </div>
							</td>
						</tr>
						<tr>
							<th> <a href=" http://kssp.com/agent/create/agent/gdp">AG03</a> </th>
							<th>Connor Flores Kyla Barnes</th>
							<td>kanoxi@mailinator.com</td>
							<td>9037715996</td>
							<td>3541</td>
							<th><span style="float: right;">1000000.000 / 
                                                                          <a href="http://kssp.com/agent-payment/state/gdp"><span style="color: red;">-166.320</span></a>
								</span>
							</th>
							<td>
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu"> <a href="http://kssp.com/agent-subscription/create/agent/gdp" class="dropdown-item"><i class="fas fa-paper-plane" style="font-size: 13px;margin: 0;"></i> 
                                          Order request
                                      </a> <a href=" http://kssp.com/agent/create/agent/gdp" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> <a href=" http://kssp.com/agent-subscribers-action/agents/gdp/action" class="dropdown-item" onclick="return confirm('Are you sure you want to deactive this ?');"><i class="fas fa-times"></i> Deactive</a> <a href=" http://kssp.com/agent-subscribers-action/agents/gdp/delete" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> </div>
							</td>
						</tr>
						
						<tr>
							<th> <a href=" http://kssp.com/agent/create/agent/ejj">AG02</a> </th>
							<th>Rafael Bonner Kermit Hale</th>
							<td>agent@gmail.com</td>
							<td>9037715996</td>
							<td>3427</td>
							<th><span style="float: right;">1000000.000 / 
                                                                          <a href="http://kssp.com/agent-payment/state/ejj"><span style="color: red;">0.000</span></a>
								</span>
							</th>
							<td>
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu"> <a href="http://kssp.com/agent-subscription/create/agent/ejj" class="dropdown-item"><i class="fas fa-paper-plane" style="font-size: 13px;margin: 0;"></i> 
                                          Order request
                                      </a> <a href=" http://kssp.com/agent/create/agent/ejj" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> <a href=" http://kssp.com/agent-subscribers-action/agents/ejj/action" class="dropdown-item" onclick="return confirm('Are you sure you want to deactive this ?');"><i class="fas fa-times"></i> Deactive</a> <a href=" http://kssp.com/agent-subscribers-action/agents/ejj/delete" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> </div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="box-footer clearfix">
					<style>
					.pagination-nav {
						width: 100%;
					}
					
					.pagination {
						float: right;
						margin: 0px;
						margin-top: -16px;
					}
					</style>
				</div>
			</div>
		</div>
	</div>
	<!-- az-content-body -->
</div>



<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>


<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script>
  $(function(){
    'use strict'

    $('#example1').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
      }
    });

    
  });
</script>


@stop