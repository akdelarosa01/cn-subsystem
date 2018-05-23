@extends('layouts.master')

@section('title')
	WBS | Pricon Microelectronics, Inc.
@endsection

@section('content')

	<?php $state = ""; $readonly = ""; ?>
	@foreach ($userProgramAccess as $access)
		@if ($access->program_code == Config::get('constants.MODULE_CODE_WBS'))  <!-- Please update "2001" depending on the corresponding program_code -->
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
							<i class="fa fa-navicon"></i>  WBS
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <form action="" class="form-horizontal">
                                    	<div class="col-md-6">
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Item Code</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm clear" id="itemcode" name="itemcode">
                                    			</div>
                                    		</div>
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Item Name</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm clear" id="itemname" name="itemname" readonly>
                                    			</div>
                                    		</div>
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Lot No.</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm clear" id="lotno" name="lotno" readonly>
                                    			</div>
                                    		</div>
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Lot Qty</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm clear" id="lotqty" name="lotqty">
                                    			</div>
                                    		</div>
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Remarks</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm clear" id="disposition" name="disposition">
                                    			</div>
                                    		</div>
                                    	</div>
                                		<div class="col-md-6">
                                			<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Created By</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm" id="createdby" name="createdby" readonly>
                                    			</div>
                                    		</div>
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Created Date</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm" value="{{date('Y-m-d')}}" data-date-format="yyyy-mm-dd" id="createddate" name="createddate">
                                    			</div>
                                    		</div>
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Updated By</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm" id="updatedby" name="updatedby" readonly>
                                    			</div>
                                    		</div>
                                    		<div class="form-group">
                                    			<label for="" class="control-label col-sm-2">Updated Date</label>
                                    			<div class="col-sm-9">
                                    				<input type="text" class="form-control input-sm" value="{{date('Y-m-d')}}" data-date-format="yyyy-mm-dd" id="updateddate" name="updateddate">
                                    				<input type="hidden" id="hd_status" name="hd_status">
                                    				<input type="hidden" id="hd_id" name="hd_id">
                                    				<input type="hidden" id="hd_qty" name="hd_qty">
                                    			</div>
                                    		</div>
                                		</div>
                                    </form>
                                </div>

                                <div class="row">
                                	<div class="col-md-12">
                                		<div class="portlet box">
                                			<div class="portlet-body">
                                				<table class="table table-bordered table-hover table-striped table-responsive" id="itemtbl">
                                        			<thead>
                                        				<tr>
                                        					<td style="width: 8%;"></td>
                                        					<td>Item Code</td>
                                        					<td>Item Name</td>
                                        					<td>Qty</td>
                                        					<td>Lot No.</td>
                                        					<td>Expiration</td>
                                        					<td>Disposition</td>
                                        				</tr>
                                        			</thead>
                                        			<tbody id="tblfordisposition">
                                        				<!-- content here! -->
                                        			</tbody>
                                        		</table>
                                			</div>
                                		</div>
                                	</div>
                                </div>

                                <div class="row">
                                	<div class="col-md-12 text-center">
										<button type="button" onclick="javascript:save();" class="btn btn-sm green" id="btn_add">
											<i class="fa fa-plus"></i> Add
										</button>
										<button type="button" class="btn btn-sm grey-gallery" id="btn_clear">
											<i class="fa fa-eraser"></i> Clear
										</button>
										<button type="button" class="btn btn-sm red" id="btn_disregard">
											<i class="fa fa-thumbs-o-down"></i> Disregard
										</button>
										<button type="button" class="btn btn-sm purple" id="btnexport_excel">
											<i class="fa fa-print"></i> Export to Excel
										</button>
									</div>
                                </div>
								
							</div>
						</div>

					</div>
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
		</div>
		<!-- END PAGE CONTENT-->
	</div>

	<!-- DELETE MESSAGE MODAL -->
    <div id="deleteBox" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-sm gray-gallery">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="del-modal-title"></h4>
                </div>
                <form class="form-horizontal">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-10" id="del-modal-message"></label>
                            </div>
                        </div>
                    </div>
       
                    <div class="modal-footer">
                    	<button type="button" onclick="javascript:deleteDisposition();" class="btn btn-success" id="btn_delete">Delete</button>
                        <button type="button" data-dismiss="modal" class="btn btn-danger" id="btn_del_close">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<!-- MESSAGE MODAL -->
    <div id="messageBox" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-sm gray-gallery">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <form class="form-horizontal">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-10" id="modal-message"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
	<script type="text/javascript">
		$(function() {
			loadDisposition();
			$('input[name=createdby]').val("{{Auth::user()->user_id}}");
			$('input[name=updatedby]').val("{{Auth::user()->user_id}}");
			$('#hd_status').val("ADD");
			$('#hd_id').val("");
			$('#hd_qty').val("");
			$('#createddate').datepicker();
			$('#updateddate').datepicker();
			$('#createddate').on('change',function(){
				$(this).datepicker('hide');
			});
			$('#updateddate').on('change',function(){
				$(this).datepicker('hide');
			});

			$('#tblfordisposition').on('click', '.btn_editdetails',function() {
				$('#hd_status').val("EDIT");
				var editText = $(this).val().split('|');
				var id = editText[0];
				var itemcode = editText[1];
				var itemname = editText[2];
				var lotno = editText[3];
				var lotqty = editText[4];
				var disposition = editText[5];
				var createdby = editText[6];
				var createddate = editText[7];
				var updatedby = editText[8];
				var updateddate = editText[9];
				
				//retrieving records to each fields--
				$('#id').val(id);
				$('#itemcode').val(itemcode);
				$('#itemname').val(itemname);
				$('#lotno').val(lotno);
				$('#lotqty').val(lotqty);
				$('#disposition').val(disposition);
				$('#createdby').val(createdby);
				$('#createddate').val(createddate);
				$('#updatedby').val(updatedby);
				$('#updateddate').val(updateddate);
				itemcodechange();
			});

			$('#tblfordisposition').on('click', '.btn_deletedetails',function() {
				$('#hd_id').val($(this).val());
				$('#deleteBox').modal('show');
				$('.del-modal-title').html("Warning Message!");
				$('#del-modal-message').html("Are you sure you want to Delete this record?");
			});

			$('#btn_del_close').on('click',function(){
				$('#hd_id').val("");
			});

			$('#btn_clear').on('click',function(){
				$('.clear').val("");
			});

			$('#btn_disregard').on('click',function(){
				$('.clear').val("");
				$('#hd_status').val("ADD");
				$('#hd_qty').val("");
			});

			$('#btnexport_excel').on('click',function(){
				var url = "{{ url('/dispositionExportToExcel')  }}";
				window.location.href = url;
			});

			$('#itemcode').on('change',function(){
				itemcodechange();
			});

		});/*End of Main function*/

		function save(){
			var hd_qty = $('#hd_qty').val();
			var itemcode = $('#itemcode').val();
			var itemname = $('#itemname').val();
			var lotno = $('#lotno').val();
			var lotqty = $('#lotqty').val();
			var disposition = $('#disposition').val();
			var createdby = $('#createdby').val();
			var createddate = $('#createddate').val();
			var updatedby = $('#updatedby').val();
			var updateddate = $('#updateddate').val();
			var status = $('#hd_status').val();
			var finalqty = hd_qty - lotqty;
			var editText = $('.btn_editdetails').val().split('|');
			var id = editText[0];
			var formUrl = "{{ url('/dispositionsave') }}";
			var token = '{{ Session::token() }}';
			var myData = {
				_token:token,
				itemcode:itemcode,
				itemname:itemname,
				lotno:lotno,
				lotqty:lotqty,
				disposition:disposition,
				createdby:createdby,
				createddate:createddate,
				updatedby:updatedby,
				updateddate:updateddate,
				status:status,
				id:id,
				hdqty:finalqty
			};
			
			if(itemcode == "" || itemname == "" || lotno == "" || lotqty == "" || disposition == "" || createdby == "" || createddate == "" || updatedby == "" || updateddate == ""){
				$('#messageBox').modal('show');
				$('.modal-title').html("Warning Message!");
				$('#modal-message').html("All fields are required!");
			}else{
				$('#tblfordisposition').html("");
				$.ajax({
					url:formUrl,
					method:'POST',
					data:myData,
				}).done(function(data, textStatus, jqXHR){
					if(data == "SAVED"){
						$('#messageBox').modal('show');
						$('.modal-title').html("Success Message!");
						$('#modal-message').html("Record Successfully Saved!");
					}else if(data == "UPDATED"){
						$('#messageBox').modal('show');
						$('.modal-title').html("Success Message!");
						$('#modal-message').html("Record Successfully Updated!");
					}else{
						$('#messageBox').modal('show');
						$('.modal-title').html("Warning Message!");
						$('#modal-message').html("Record Not Saved!");
					}
					loadDisposition();
					$('#hd_status').val("ADD");
					$('#hd_qty').val("");
					$('.clear').val("");
				}).fail(function(jqXHR,textStatus,errorThrown){
					console.log(errorThrown+'|'+textStatus);
				});	
			}
			
		}

		function deleteDisposition(){
			var id = $('#hd_id').val();
			var formUrl = "{{ url('/deleteDisposition') }}";
			var token = '{{ Session::token() }}';
			var myData = {
				_token:token,
				id:id,
			};
			$('#tblfordisposition').html("");
			$.ajax({
				url:formUrl,
				method:'POST',
				data:myData,
			}).done(function(data, textStatus, jqXHR){
				$('#deleteBox').modal('hide');
				$('#hd_status').val("ADD");
				if(data == "DELETED"){
					$('#messageBox').modal('show');
					$('.modal-title').html("Success Message!");
					$('#modal-message').html("Record Successfully Deleted!");
				}else{
					$('#messageBox').modal('show');
					$('.modal-title').html("Warning Message!");
					$('#modal-message').html("Record Not Deleted!");
				}
				loadDisposition();
				$('.clear').val("");
			}).fail(function(jqXHR,textStatus,errorThrown){
				console.log(errorThrown+'|'+textStatus);
			});
		}

		function loadDisposition(){
			var formUrl = "{{ url('/wbsdispositiongetrows') }}";
			var token = '{{ Session::token() }}';
			var formData = {
				_token:token,
			};
			$.ajax({
				url:formUrl,
				method:'GET',
				data:formData,
			}).done(function(data, textstatus, jqXHR){
				getDataTable(data);
			}).fail(function(jqXHR, textstatus, errorThrown){
				console.log(textstatus+'|'+errorThrown);
			});
		}

		function getDataTable(data){
			var cnt = 0
			$.each(data,function(i,x){
				cnt++;
				var tblrow 	=	'<tr>'+
									'<td>'+
										'<button type="button" class="btn input-sm btn-circle green btn_editdetails" value="'+x.id+'|'+x.itemcode+'|'+x.itemname+'|'+x.lotno+'|'+x.lotqty+'|'+x.disposition+'|'+x.createdby+'|'+x.createddate+'|'+x.updatedby+'|'+x.updateddate+'">'+
                							'<i class="fa fa-edit"></i>'+
                						'</button>'+
                						'<button type="button" class="btn input-sm btn-circle red btn_deletedetails" value="'+x.id+'">'+
                							'<i class="fa fa-trash"></i>'+
                						'</button>'+
									'</td>'+
									'<td>'+x.itemcode+'</td>'+
									'<td>'+x.itemname+'</td>'+
									'<td>'+x.lotqty+'</td>'+
									'<td>'+x.lotno+'</td>'+
									'<td>'+x.createddate+'</td>'+
									'<td>'+x.disposition+'</td>'+
								'</tr>';

				$('#tblfordisposition').append(tblrow);
			});
		}

		function itemcodechange(){
			var itemcode = $('#itemcode').val();
			var formUrl = "{{ url('/itemcodechange') }}";
			var myData = {
				itemcode:itemcode,
			};
			$.ajax({
				url:formUrl,
				method:'GET',
				data:myData,
			}).done(function(data, textStatus, jqXHR){
				console.log(data);
				$('#itemname').val(data[0]['item_desc']);
				$('#lotno').val(data[0]['lot_no']);
				$('#hd_qty').val(data[0]['qty']);
			}).fail(function(jqXHR, textStatus, errorThrown){
				console.log(errorThrown+'|'+textStatus);
			});
		}
	</script>
@endpush