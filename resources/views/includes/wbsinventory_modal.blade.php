<div id="form_inventory_modal" class="modal fade" role="dialog" data-backdrop="static">
	<div class="modal-dialog">

		<div class="modal-content blue">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">ADD\EDIT Item</h3>
			</div>
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/wbs-inventory-save') }}" id="frm_markup">
				<div class="modal-body">
					{!! csrf_field() !!}
					<input type="hidden" id="id" name="id">

					<div class="form-group" id="item_code_div">
						<label for="inputcode" class="col-md-3 control-label">Item Code</label>
						<div class="col-md-9">
							<input type="text" class="form-control validate" id="item_code" name="item_code" readonly>
							<span class="help-block">
                                <strong id="item_code_msg"></strong>
                            </span>
						</div>
					</div>

					<div class="form-group" id="item_desc_div">
						<label for="inputcode" class="col-md-3 control-label">Description</label>
						<div class="col-md-9">
							<input type="text" class="form-control validate" id="item_desc" name="item_desc" autofocus>
							<span class="help-block">
                                <strong id="item_desc_msg"></strong>
                            </span>
						</div>
					</div>

					<div class="form-group" id="lot_no_div">
						<label for="inputcode" class="col-md-3 control-label">Lot No.</label>
						<div class="col-md-9">
							<input type="text" class="form-control validate" id="lot_no" name="lot_no">
							<span class="help-block">
                                <strong id="lot_no_msg"></strong>
                            </span>
						</div>
					</div>

					<div class="form-group" id="qty_div">
						<label for="inputcode" class="col-md-3 control-label">Qty.</label>
						<div class="col-md-9">
							<input type="text" class="form-control validate" id="qty" name="qty">
							<span class="help-block">
                                <strong id="qty_msg"></strong>
                            </span>
						</div>
					</div>

					<div class="form-group" id="location_div">
						<label for="inputcode" class="col-md-3 control-label">Location</label>
						<div class="col-md-9">
							<input type="text" class="form-control validate" id="location" name="location">
							<span class="help-block">
                                <strong id="location_msg"></strong>
                            </span>
						</div>
					</div>

					<div class="form-group" id="supplier_div">
						<label for="inputcode" class="col-md-3 control-label">Supplier</label>
						<div class="col-md-9">
							<input type="text" class="form-control validate" id="supplier" name="supplier">
							<span class="help-block">
                                <strong id="supplier_msg"></strong>
                            </span>
						</div>
					</div>

					<div class="form-group">
						<label for="inputcode" class="col-md-3 control-label"></label>
						<div class="col-md-9">
							<label><input type="checkbox" id="nr" name="nr"> Not for IQC</label>
						</div>
					</div>

					<div class="form-group" id="status_div">
						<label for="inputname" class="col-md-3 control-label">Status</label>
						<div class="col-md-9">
							<select class="form-control validate" id="status" name="status">
								<option value="0">Pending</option>
								<option value="1">Accept</option>
								<option value="2">Reject</option>
								<option value="3">On-going</option>
							</select>
							<span class="help-block">
                                <strong id="status_msg"></strong>
                            </span>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
						{{-- <button type="submit" class="btn btn-success" {{ $state }}><i class="fa fa-save"></i> Save</button> --}}
						<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
 						<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
				</div>
			</form>
		</div>
			
	</div>
</div>