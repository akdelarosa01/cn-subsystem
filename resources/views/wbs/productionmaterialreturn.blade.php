@extends('layouts.master')

@section('title')
	WBS | Pricon Microelectronics, Inc.
@endsection

@push('css')
	<style type="text/css">
        table.table-fixedheader {
            width: 100%;
        }
        table.table-fixedheader, table.table-fixedheader>thead, table.table-fixedheader>tbody, table.table-fixedheader>thead>tr, table.table-fixedheader>tbody>tr, table.table-fixedheader>thead>tr>td, table.table-fixedheader>tbody>td {
            display: block;
        }
        table.table-fixedheader>thead>tr:after, table.table-fixedheader>tbody>tr:after {
            content:' ';
            display: block;
            visibility: hidden;
            clear: both;
        }
        table.table-fixedheader>tbody {
            overflow-y: scroll;
            height: 200px;
        }
        table.table-fixedheader>thead {
            overflow-y: scroll;
        }
        table.table-fixedheader>thead::-webkit-scrollbar {
            background-color: inherit;
        }

        table.table-fixedheader>thead>tr>td:after, table.table-fixedheader>tbody>tr>td:after {
            content:' ';
            display: table-cell;
            visibility: hidden;
            clear: both;
        }

        table.table-fixedheader>thead tr td, table.table-fixedheader>tbody tr td {
            float: left;
            word-wrap:break-word;
            height: 40px;
        }

        table.table-fifo>tbody {
            overflow-y: scroll;
            height: 375px;
        }
       /* #hd_barcode {
        	position: absolute;
		    z-index: -1;
        }*/
    </style>
@endpush

@section('content')

	<?php $state = ""; $readonly = ""; ?>
	@foreach ($userProgramAccess as $access)
		@if ($access->program_code == Config::get('constants.MODULE_CODE_PRDMATRET'))  <!-- Please update "2001" depending on the corresponding program_code -->
			@if ($access->read_write == "2")
			<?php $state = "disabled"; $readonly = "readonly"; ?>
			@endif
		@endif
	@endforeach
    
	<div class="page-content">

		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				@include('includes.message-block')
				<div class="portlet box blue" >
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-navicon"></i>  WBS Production Material Return
						</div>
					</div>
        			<div class="portlet-body">
                        <div class="row">
                            <form action="" class="form-horizontal">
                            	<div class="col-md-4">
                            		<div class="form-group">
                            			<label class="control-label col-md-3">Control No.</label>
                                        <div class="col-md-9">
                                            <input type="hidden" class="form-control input-sm" id="ret_info_id" name="ret_info_id"/>
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm add" id="controlno" name="controlno">

                                                <span class="input-group-btn">
                                                    <a href="javascript:navigate('first');" id="btn_min" class="btn blue input-sm"><i class="fa fa-fast-backward"></i></a>
                                                    <a href="javascript:navigate('prev');" id="btn_prv" class="btn blue input-sm"><i class="fa fa-backward"></i></a>
                                                    <a href="javascript:navigate('next');" id="btn_nxt" class="btn blue input-sm"><i class="fa fa-forward"></i></a>
                                                    <a href="javascript:navigate('last');" id="btn_max" class="btn blue input-sm"><i class="fa fa-fast-forward"></i></a>
                                                </span>
                                            </div>

                                            
                                        </div>
                            		</div>

                            		<div class="form-group">
                            			<label for="" class="control-label col-sm-3">P.O. No.</label>
                            			<div class="col-sm-9">
                            				<input type="text" class="form-control input-sm clear" id="po" name="po">
                            			</div>
                            		</div>

                            		<div class="form-group">
                            			<label for="" class="control-label col-sm-3">Date Returned</label>
                            			<div class="col-sm-9">
                            				<input type="text" class="form-control input-sm date-picker " data-date-format="yyyy-mm-dd" id="date_returned" name="date_returned">
                            			</div>
                            		</div>
                            	</div>
                        		<div class="col-md-4">
                            		<div class="form-group">
                            			<label for="" class="control-label col-sm-3">Remarks</label>
                            			<div class="col-sm-9">
                            				<textarea class="form-control input-sm" id="remarks" name="remarks" style="resize:none"></textarea>
                            			</div>
                            		</div>
                            		
                            		<div class="form-group">
                            			<label for="" class="control-label col-sm-3">Returned By</label>
                            			<div class="col-sm-9">
                            				<input type="text" class="form-control input-sm" id="returned_by" name="returned_by">
                            			</div>
                            		</div>
                        		</div>
                        		<div class="col-md-4">
                        			<div class="form-group">
                            			<label for="" class="control-label col-sm-3">Created By</label>
                            			<div class="col-sm-9">
                            				<input type="text" class="form-control input-sm" id="create_user" name="create_user" readonly>
                            			</div>
                            		</div>
                        			<div class="form-group">
                            			<label for="" class="control-label col-sm-3">Created Date</label>
                            			<div class="col-sm-9">
                            				<input type="text" class="form-control input-sm" id="created_at" name="created_at" readonly>
                            			</div>
                            		</div>
                            		<div class="form-group">
                            			<label for="" class="control-label col-sm-3">Updated By</label>
                            			<div class="col-sm-9">
                            				<input type="text" class="form-control input-sm" id="update_user" name="update_user" readonly>
                            			</div>
                            		</div>
                        			<div class="form-group">
                            			<label for="" class="control-label col-sm-3">Updated Date</label>
                            			<div class="col-sm-9">
                            				<input type="text" class="form-control input-sm" id="updated_at" name="updated_at" readonly>
                            			</div>
                            		</div>
                        		</div>
                            </form>
                        </div>

                        <div class="row">
                        	<div class="col-md-12">
                        		<div class="portlet box">
                    				<div class="portlet-body">
                    					<div class="row">
                    						<div class="col-md-12">
	                                			<table class="table table-bordered table-hover table-striped table-fixedheader" id="tbl_details" style="font-size: 10px;margin-top: 20px;">
	                                    			<thead>
	                                    				<tr>
                                                            <td class="table-checkbox" width="4.09%">
                                                                <input type="checkbox" class="group-checkable"/>
                                                            </td>
	                                    					<td width="9.09%">Issuance No.</td>
	                                    					<td width="9.09%">Item</td>
	                                    					<td width="16.09%">Description</td>
	                                    					<td width="9.09%">Lot No.</td>
	                                    					<td width="7.09%">Issued Qty.</td>
	                                    					<td width="7.09%">Required Qty.</td>
	                                    					<td width="7.09%">Return Qty.</td>
	                                    					<td width="9.09%">Actual Return Qty.</td>
	                                    					<td width="15.09%">Remarks</td>
                                                            <td width="7.09%"></td>
	                                    				</tr>
	                                    			</thead>
	                                    			<tbody id="tbl_details_body"></tbody>
	                                    		</table>
	                                		</div>
                    					</div>
                    					<div class="row">
                    						<div class="col-md-12 text-center">
		                                		<button type="button" class="btn btn-sm green" id="btn_add_details">
													<i class="fa fa-plus"></i> Add Details
												</button>
												<button type="button" class="btn btn-sm red" id="btn_delete_details">
													<i class="fa fa-trash"></i> Delete Details
												</button>
											</div>
                    					</div>
                    				</div>
                    			</div>
                        	</div>
                        </div>

                        <div class="row">
                        	<div class="col-md-12 text-center">
                        		<button type="button" class="btn btn-sm green" id="btn_add">
									<i class="fa fa-plus"></i> Add
								</button>
								<button type="button" class="btn btn-sm blue" id="btn_edit">
									<i class="fa fa-pencil"></i> Edit
								</button>
								<button type="button" class="btn btn-sm green" id="btn_save">
									<i class="fa fa-floppy-o"></i> Save
								</button>
								<button type="button" class="btn btn-sm red" id="btn_back">
									<i class="fa fa-times"></i> Back
								</button>
								<button type="button" class="btn btn-sm purple" id="btn_search">
									<i class="fa fa-search"></i> Search
								</button>
								<button type="button" class="btn btn-sm green-jungle" id="btn_excel">
									<i class="fa fa-file-excel-o"></i> Export To Excel
								</button>

							</div>
                        </div>
        			</div>
                                
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
		</div>
		<!-- END PAGE CONTENT-->
	</div>



    <div id="DetailsModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog gray-gallery">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Details</h4>
                </div>
                <form method="POST" action="{{url('/editdetailpmr')}}" class="form-horizontal" id="editpodetailfrm">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Issuance No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="issuance_no">
                                        <input type="hidden" class="form-control input-sm" id="detail_id">
                                    </div>
                                </div>

                                <div class="form-group">
                        			<label for="" class="control-label col-sm-3">Item Code</label>
                        			<div class="col-sm-9">
                        				<input type="text" class="form-control input-sm" id="item" name="item" readonly>
                        			</div>
                        		</div>

                        		<div class="form-group">
                                    <label class="control-label col-sm-3">Item Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="item_desc" name="item_desc" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3">Issued Qty.</label>
                                    <div class="col-sm-9">
                                    	<input type="text" class="form-control input-sm" id="issued_qty" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3">Required Qty.</label>
                                    <div class="col-sm-9">
                                    	<input type="text" class="form-control input-sm" id="required_qty" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3">Return Qty.</label>
                                    <div class="col-sm-9">
                                    	<input type="text" class="form-control input-sm" id="return_qty" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3">Actual Returned Qty.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="actual_returned_qty">
                                    </div>
                                </div>
                                
                        		<div class="form-group">
                        			<label for="" class="control-label col-sm-3">Lot no</label>
                        			<div class="col-sm-9">
                        				<input type="text" class="form-control input-sm" id="lot_no" name="lot_no" readonly>
                        			</div>
                        		</div>

                        		<div class="form-group">
                        			<label for="" class="control-label col-sm-3">Remarks</label>
                        			<div class="col-sm-9">
                        				<input type="text" class="form-control input-sm" id="detail_remarks" name="detail_remarks">
                        			</div>
                        		</div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn_save_details" class="btn btn-success">Save</button>
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('includes.modals')

@endsection

@push('script')
	<script type="text/javascript">
		var token = "{{ Session::token() }}";
		var saveMaterialReturnURL = "{{ url('/save-material-return') }}";
        var getMaterialReturnDataURL = "{{ url('/get-material-return-data') }}";
        var getIssuanceNoURL = "{{ url('/get-issuanceno') }}";
        var getItemDetailsURL = "{{ url('/get-item-details') }}";
        var barcodeURL = "{{ url('/wbsreturn-brprint?id=') }}";
        var deleteDetailsURL = "{{ url('/delete-item-return') }}";
	</script>
    <script src="{{ asset(config('constants.PUBLIC_PATH').'assets/global/scripts/common.js') }}" type="text/javascript"></script>
    <script src="{{ asset(config('constants.PUBLIC_PATH').'assets/global/scripts/productionmaterialreturn.js') }}" type="text/javascript"></script>
@endpush