<!-- add details -->
    <div id="addDetModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog gray-gallery modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">Add Details</h4>
                </div>
				<form class="form-horizontal">
	                <div class="modal-body">
	                    <div class="row">
	                        <div class="col-md-6">
	                                <div class="form-group">
	                                    <div class="col-sm-12">
	                                       <p>
	                                           All fields are required.
	                                       </p>
	                                    </div>
	                                </div>
									<input type="hidden" name="transno" id="transno">
									<input type="hidden" name="hdnstatus" id="hdnstatus">
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Detail ID.</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="issueDetID" name="issueDetID" disabled="true">
											<input type="hidden" id="issueDetID1" name="issueDetID1">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Item/Part No.</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="itemnodet" name="itemnodet" disabled="true">
											<input type="hidden" id="itemnodet1" name="itemnodet1">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Item Description</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="itemdesc" name="itemdesc" disabled="true">
											<input type="hidden" id="itemdesc1" name="itemdesc1">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Request Detail ID</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="reqdetid" name="reqdetid" disabled="true">
											<input type="hidden" id="reqdetid1" name="reqdetid1">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Request Qty.</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="reqqtydet" name="reqqtydet" disabled="true">
											<input type="hidden" id="reqqtydet1" name="reqqtydet1">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Served Qty.</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="servedqtydet" name="servedqtydet" disabled="true">
											<input type="hidden" id="servedqtydet1" name="servedqtydet1">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Issued Qty.</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="issqtydet" name="issqtydet">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Lot. No</label>
	                                    <div class="col-sm-9">
	                                       <input type="text" class="form-control input-sm" id="lotnodet" name="lotnodet">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-sm-3">Location</label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control input-sm" id="locdet" name="locdet" disabled="true">
											<input type="hidden" id="locdet1" name="locdet1">
                                            <input type="hidden" id="fifoid" name="fifoid">
	                                    </div>
	                                </div>

	                        </div>

	                        <div class="col-md-6">
	                        	<div class="table-responsive">
									<table class="table table-bordered table-fifo"  id="tblfifoAdd" style="font-size:10px">
										<thead>
											<tr>
												<td style="width: 9.66%"></td>
												<td style="width: 16.66%">Item Code</td>
												<td style="width: 24.66%">Description</td>
												<td style="width: 15.66%">Qty</td>
												<td style="width: 16.66%">Lot</td>
												<td style="width: 16.66%">Received Date</td>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
	                        </div>
	                    </div>

	                </div>
	                <div class="modal-footer">
	                    <a href="javascript:;" id="btn_saveAddDetail" class="btn btn-success">Save</a>
	                    <button type="button" id="addDet_close" data-dismiss="modal" class="btn btn-danger">Close</button>
	                </div>
				</form>
            </div>
        </div>
    </div>

	<!-- AJAX LOADER -->
	<div id="loading" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-sm gray-gallery">
			<div class="modal-content ">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-8">
							<img src="{{ asset(Config::get('constants.PUBLIC_PATH').'assets/images/ajax-loader.gif') }}" class="img-responsive">
						</div>
						<div class="col-sm-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Cancel Confirmation Pop-message -->
	<div id="deleteModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm blue">
			<form role="form" method="POST" action="{{ url('/wbswmi-cancel') }}">
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

	<!--msg-->
	<div id="msg" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-sm gray-gallery">
			<div class="modal-content ">
				<div class="modal-header">
					<h4 id="title" class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<p id="err_msg"></p>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!--msg-->
	<div id="msgdone" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-sm gray-gallery">
			<div class="modal-content ">
				<div class="modal-header">
					<h4 id="titledone" class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<p id="err_msgdone"></p>
				</div>
				<div class="modal-footer">
					<a href="{{url('/wbswhsmatissuance')}}" class="btn btn-danger">Close</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Search Modal -->
	<div id="searchModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-full">
			<div class="modal-content blue">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Search</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-5">
							<div class="form-horizontal">
								<div class="form-group">
	                                <label for="srch_from" class="col-md-3 control-label">Date</label>
	                                <div class="col-md-9">
	                                    <div class="input-group input-large date-picker input-daterange" data-date="<?php echo date("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
	                                        <input type="text" class="form-control input-sm reset" name="srch_from" id="srch_from"/>
	                                        <span class="input-group-addon">to </span>
	                                        <input type="text" class="form-control input-sm reset" name="srch_to" id="srch_to"/>
	                                    </div>
	                                </div>
	                            </div>
								<div class="form-group">
									<label for="inputname" class="col-md-3 control-label" style="font-size:12px">Issuance No.</label>
									<div class="col-md-9">
										<input type="text" class="form-control input-sm" id="srch_issuanceno" placeholder="Issuance No." name="srch_issuanceno" autofocus <?php echo($readonly); ?> />
									</div>
								</div>
								<div class="form-group">
									<label for="inputname" class="col-md-3 control-label" style="font-size:12px">Request No.</label>
									<div class="col-md-9">
										<input type="text" class="form-control input-sm" id="srch_reqno" placeholder="Request No." name="srch_reqno" <?php echo($readonly); ?> />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Status</label>
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<input type="checkbox" id="srch_serving" class="md-check" name="Alert" value="Serving">
											<label for="srch_open">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											Alert </label>
										</div>
										<div class="md-checkbox">
											<input type="checkbox" id="srch_close" class="md-check" name="Close" value="Closed">
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
									{{-- <label for="inputname" class="col-md-4 control-label" style="font-size:12px">Status</label>
									<div class="col-md-8">
										<label><input type="checkbox" class="checkboxes" style="font-size:12px" value="Serving" id="srch_serving" name="Alert" checked="true"/>Alert</label>
										<label><input type="checkbox" class="checkboxes" style="font-size:12px" value="Closed" id="srch_close" id="search_close" name="Close"/>Close</label>
										<label><input type="checkbox" class="checkboxes" style="font-size:12px" value="Cancelled" id="srch_cancelled" name="Cancelled"/>Cancelled</label>
									</div> --}}
								</div>
							</div>
						</div>
						<div class="col-md-7">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover table-fixedheader" style="font-size:10px">
									<thead>
										<tr>
											<td style="width: 8.5%"></td>
											<td style="width: 14.5%">Issuance No.</td>
											<td style="width: 14.5%">Request No.</td>
											<td style="width: 12.5%">Status</td>
											<td style="width: 12.5%">Created By</td>
											<td style="width: 12.5%">Created Date</td>
											<td style="width: 12.5%">Updated By</td>
											<td style="width: 12.5%">Updated Date</td>
										</tr>
									</thead>
									<tbody id="srch_tbl_body">
									</tbody>
								</table>
							</div>

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
		</div>
	</div>