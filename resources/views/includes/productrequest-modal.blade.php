<!-- add po -->
<div id="addpoModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog grey-galleey">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Load PO Details</h4>
            </div>
            <form method="POST" action="{{url('/posearch')}}" class="form-horizontal" id="addpofrm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-sm-2">PO No.</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="posearch" name="posearch">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Load</button>
                    <a href="" data-dismiss="modal" class="btn btn-danger">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select PO Details -->
<div id="SelectPODetailsModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog grey-gallery">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Select PO Details</h4>
            </div>
            <form method="POST" action="{{url('/savedetailpmr')}}" class="form-horizontal" id="selectpodetailfrm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="scroller" style="height: 200px">
                                <table class="table table-responsive table-bordered table-striped table-hover"  style="font-size:10px">
                                    <thead>
                                        <tr>
                                            <td></td>
                                            <td>Code</td>
                                            <td>Description</td>
                                            <td>Issued QTY</td>
                                            <td>Location</td>
											<input type="hidden" name="po" id="po" value="">
                                        </tr>
                                    </thead>
                                    <tbody id="tblpodetail">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-md-3" style="text-align:left;">
                        <input type="checkbox" class="checkboxes checkall input-sm" id="checkall" name="checkall"/> Select All
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="" data-dismiss="modal" class="btn btn-danger">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit PO Details -->
<div id="EditPODetailsModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog grey-gallery">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">Edit PO Details</h4>
            </div>
            <form method="POST" action="{{url('/editdetailpmr')}}" class="form-horizontal" id="editpodetailfrm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-3">Detail ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="editDetailid"disabled="true">
									<input type="hidden" class="form-control input-sm" id="editDetailid1" name="editDetailid">
									<input type="hidden" class="form-control input-sm" id="cntval">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Item/Part No.</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="editcode" disabled="true">
									<input type="hidden" class="form-control input-sm" id="editcode1" name="editcode">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Item Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="editdesc"disabled="true">
									<input type="hidden" class="form-control input-sm" id="editdesc1" name="editdesc">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Classification</label>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="editclassification" name="editclassification">
                                        <option value=""></option>
										@foreach ($class as $key => $classification)
											<option value="{{$classification->description}}">{{$classification->description}}</option>
										@endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Issued Qty. (Kitting)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="editIssuedqty" disabled="true">
									<input type="hidden" class="form-control input-sm" id="editIssuedqty1" name="editIssuedqty">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Request Qty.</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm mask_reqqty" id="editRequestqty" name="editRequestqty" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Requested By</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="editRequestedby" disabled="true">
									<input type="hidden" class="form-control input-sm" id="editRequestedby1" name="editRequestedby">
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label class="control-label col-sm-3">Location</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="editLocation" disabled="true"> --}}
									<input type="hidden" class="form-control input-sm" id="editLocation1" name="editLocation">
                               {{--  </div>
                            </div> --}}
                            <div class="form-group">
                                <label class="control-label col-sm-3">Remarks</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" id="editRmrks" name="editRmrks">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="saveEditdetail" class="btn btn-success" <?php if(isset($action)){if($action == 'VIEW'){ echo 'disabled';} } ?> >Save</button>
					<!-- <a href="" data-dismiss="modal" id="saveEditdetail" class="btn btn-success">Save</a> -->
                    <!-- <button type="submit" class="btn btn-success">Save</button> -->
                    <a href="" data-dismiss="modal" class="btn btn-danger">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Cancel Confirmation Pop-message -->
<div id="deleteModal" class="modal fade" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-sm blue">
		<form role="form" method="POST" action="{{ url('/cancelpmr') }}">
			<div class="modal-content ">
				<div class="modal-body">
					<p>Are you sure you want to cancel this transaction?</p>
					{!! csrf_field() !!}
					<input type="hidden" name="id" id="delete_inputId"/>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="delete">Yes</button>
					<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Edit Batch Validation Pop-message -->
<div id="invalidEditBatchModal" class="modal fade" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-sm blue">
		<div class="modal-content ">
			<div class="modal-body">
				<p>Request Qty contains invalid values. <br/> It must must not be greater than Issued Qty.</p>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="javascript: showAddBatch('EDIT');" class="btn btn-primary" id="btnok">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Search Modal -->
<div id="searchModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-full">

        <!-- Modal content-->
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/wbsprodmatrequest') }}">
            {!! csrf_field() !!}
            <div class="modal-content blue">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                        	<div class="form-group">
                                <label for="req_from" class="col-md-3 control-label">Date</label>
                                <div class="col-md-9">
                                    <div class="input-group input-large date-picker input-daterange" data-date="<?php echo date("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control input-sm reset" name="req_from" id="req_from"/>
                                        <span class="input-group-addon">to </span>
                                        <input type="text" class="form-control input-sm reset" name="req_to" id="req_to"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputsearch_pono" class="col-md-3 control-label" style="font-size:12px">PO No.</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control input-sm" id="srch_pono" placeholder="PO No." name="srch_pono" autofocus <?php echo($readonly); ?> />
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <label for="inputsearch_proddes" class="col-md-3 control-label" style="font-size:12px">Product Destination</label>
                                <div class="col-md-9">
                                    <select class="form-control input-sm select2me" id="srch_prodes" name="srch_prodes">
                                       <option value="-1">---Select---</option>
                                       @foreach($prod as $prd)
                                       <option value="{{$prd->id}}" >{{$prd->description}}</option>
                                       @endforeach
                                   </select>
                                </div>
                           	</div>

                           	<div class="form-group">
	                            <label for="inputcode" class="col-md-3 control-label" style="font-size:12px">Line Destination</label>
                                <div class="col-md-9">
                                    <select class="form-control input-sm select2me" id="srch_linedes" name="srch_linedes">
                                        <option value="-1">---Select---</option>
                                         @foreach($line as $ln)
                                         <option value="{{$ln->id}}" >{{$ln->description}}</option>
                                         @endforeach
                                    </select>
                                </div>
		                    </div>

		                    <div class="form-group">
		                    	<label class="col-md-3 control-label">Status</label>
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<input type="checkbox" id="srch_open" class="md-check" name="Open" value="Open">
										<label for="srch_open">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										Alert </label>
									</div>
									<div class="md-checkbox">
										<input type="checkbox" id="srch_close" class="md-check" name="Close" value="Close">
										<label for="srch_close">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										Close </label>
									</div>
									<div class="md-checkbox">
										<input type="checkbox" id="srch_cancelled" class="md-check" name="Cancelled" value="Cancelled">
										<label for="srch_cancelled">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										Cancelled </label>
									</div>
								</div>
		                    </div>
		                </div>
		                <div class="col-md-7">
		                	<table class="table table-striped table-bordered table-hover tabl-fixedheader" style="font-size:10px">
		                        <thead>
		                            <tr>
		                                <td width="10%"></td>
		                                <td width="10%">Transaction No.</td>
		                                <td width="10%">PO No.</td>
		                                <td width="10%">Product Destination</td>
		                                <td width="10%">Line Destination</td>
		                                <td width="10%">Status</td>
		                                <td width="10%">Created By</td>
		                                <td width="10%">Created Date</td>
		                                <td width="10%">Updated By</td>
		                                <td width="10%">Updated Date</td>
		                            </tr>
		                        </thead>
		                        <tbody id="srch_tbl_body">
		                        </tbody>
		                    </table>
		                </div>
		            </div>
                </div>
                <div class="modal-footer">
		            <input type="hidden" class="form-control input-sm" id="editId" name="editId">
		            <button type="button" style="font-size:12px" onclick="javascript: filterData('SRCH'); " class="btn blue-madison"><i class="glyphicon glyphicon-filter"></i> Filter</button>
		            <button type="button" style="font-size:12px" onclick="javascript: filterData('CNCL'); " class="btn green" ><i class="glyphicon glyphicon-repeat"></i> Reset</button>
		            <button type="button" style="font-size:12px" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
		        </div>
            </div>
        </form>
    </div>
</div>


<!-- Message -->
<div id="msgModal" class="modal fade" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-sm grey-gallery">
		<div class="modal-content ">
		   <div class="modal-body">
			  <p id="msg">{{Session::get('msg')}}</p>
		  </div>
		  <div class="modal-footer">
			  <button type="button" data-dismiss="modal" class="btn btn-primary">OK</button>
		  </div>
	  </div>
  </div>
</div>

<div id="errmsg" class="modal fade" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-sm grey-gallery">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="errtitle"></h4>
			</div>
			<div class="modal-body">
				<p id="error"></p>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-primary">OK</button>
			</div>
		</div>
	</div>
</div>