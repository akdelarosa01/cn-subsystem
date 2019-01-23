@extends('layouts.master')

@section('title')
	QC Database | Pricon Microelectronics, Inc.
@endsection
@push('css')
	<link href="{{ asset(Config::get('constants.PUBLIC_PATH').'assets/global/css/table-fixedheader.css')}}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')

	@include('includes.header')
	<?php $state = ""; $readonly = ""; ?>
	@foreach ($userProgramAccess as $access)
		@if ($access->program_code == Config::get('constants.MODULE_CODE_QCDB'))  <!-- Please update "2001" depending on the corresponding program_code -->
			@if ($access->read_write == "2")
			<?php $state = "disabled"; $readonly = "readonly"; ?>
			@endif
		@endif
	@endforeach
	<div class="page-content">

		<div class="row">
			<div class="col-md-12">
				<div class="btn-group pull-right">
					<a href="javascript:;" class="btn green" id="btn_upload">
                        <i class="fa fa-upload"></i> Upload Data
                    </a>
                    <a href="javascript:;" class="btn grey-gallery" id="btn_groupby">
                        <i class="fa fa-group"></i> Group By
                    </a>
                    
                    <a href="javascript:;" class="btn purple" id="btn_search">
                        <i class="fa fa-search"></i> Search
                    </a>
					<a href="javascript:;" class="btn yellow-gold" id="btn_pdf">
                        <i class="fa fa-file-text-o"></i> Export to Pdf
                    </a>
                    <a href="javascript:;" class="btn green-jungle" id="btn_excel">
                        <i class="fa fa-file-text-o"></i> Export to Excel
                    </a>

                    <a href="javascript:;" class="btn blue" id="btn_history">
                        <i class="fa fa-book"></i> Item History
                    </a>
				</div>
			</div>
		</div>
		<hr>
		<div class="row col-sm-offset-3">
			<div class="col-sm-3">
				<a href="javascript:;" class="btn green btn-block" id="btn_iqcresult">
					<i class="fa fa-search"></i> Inspection
				</a>
			</div>

			<div class="col-sm-3">
				<a href="javascript:;" class="btn green btn-block" id="btn_iqcresult_man">
					<i class="fa fa-search"></i> Manual Input
				</a>
			</div>

			<div class="col-sm-3">
				<a href="javascript:;" class="btn green btn-block" id="btn_requali">
					<i class="fa fa-history"></i> Re-qualification
				</a>
			</div>
		</div>

		<div class="row">
            <div class="col-sm-12" id="main_pane">
            	<div class="tabbable-custom">
                	<ul class="nav nav-tabs" role="tablist">
                		<li role="presentation"  class="active"><a href="#on-going" aria-controls="on-going" role="tab" data-toggle="tab">On-Going</a></li>
                        <li role="presentation"><a href="#inspected" aria-controls="inspected" role="tab" data-toggle="tab">Inspected</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="on-going">
                        	<table class="table table-hover table-bordered table-striped" id="on-going-inspection" style="font-size: 10px;">
                                <thead>
                                    <tr>
                                    	<td class="table-checkbox">
                                            <input type="checkbox" class="group-checkable ongoing_checkall" />
                                        </td>
                                        <td></td>
                                        <td>Invoice No.</td>
                                        <td>Inspector</td>
                                        <td>Inspection Date</td>
                                        <td>Inspection Time</td>
                                        <td>Application Ctrl No.</td>
                                        <td>FY#</td>
                                        <td>WW#</td>
                                        <td>Sub</td>
                                        <td>Part Code</td>
                                        <td>Part Name</td>
                                        <td>Supplier</td>
                                        <td>Lot No.</td>
                                        <td>AQL</td>
										<td>Judgement</td>
                                    </tr>
                                </thead>
                                <tbody id="tblforongoing">
                                </tbody>
                            </table>
                            <div class="row">
                            	<div class="col-md-12 text-center">
                            		<button type="button" class="btn red" id="btn_delete_ongoing">
                            			<i class="fa fa-trash"></i> Delete
                            		</button>
                            	</div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="inspected">
                        	<div class="table-responsive">
                        		<table class="table table-hover table-bordered table-striped table-condensed" id="iqcdatatable" style="font-size: 10px;">
                                    <thead>
                                        <tr>
                                        	<td class="table-checkbox">
                                                <input type="checkbox" class="group-checkable iqc_checkall" />
                                            </td>
                                            <td></td>
                                            <td>Invoice No.</td>
							                <td>Inspector</td>
											<td>Date Inspected</td>
											<td>Inspection Time</td>
											<td>App. No.</td>
											<td>App. Date</td>
											<td>App time</td>
											<td>FY</td>
											<td>WW</td>
											<td>Submission</td>
											<td>Part Code</td>
											<td>Part Name</td>
											<td>Supplier</td>
											<td>Lot No.</td>
											<td>AQL</td>
											<td>Lot Qty</td>
											<td>Type of Inspection</td>
											<td>Severity of Inspection</td>
											<td>Inspection Level</td>
											<td>Accept</td>
											<td>Reject</td>
											<td>Shift</td>
											<td>Lot Inspected</td>
											<td>Lot Accepted</td>
											<td>Sample Size</td>
											<td>No. of Defects</td>
											<td>Classification</td>
											<td>Family</td>
											<td>Remarks</td>
											<td>Judgement</td>
										</tr>
                                    </thead>
                                    <tbody id="tblforiqcinspection">
                                    </tbody>
                                </table>
                        	</div>
                            	
                            <div class="row">
                            	<div class="col-md-12 text-center">
                            		<button type="button" class="btn red" id="btn_delete_inspected">
                            			<i class="fa fa-trash"></i> Delete
                            		</button>
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" id="group_by_pane"></div>
        </div>
	</div>

	@include('includes.iqc_inspection_modal')
	@include('includes.modals')
@endsection

@push('script')
<script src="{{ asset(config('constants.PUBLIC_PATH').'assets/global/scripts/common.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
    	//$('#time_ins_from').mask('AA:AA AA', {placeholder: '__:__ __'});
		getIQCInspection("{{url('/iqcdbgetiqcdata')}}");
		getOnGoing();

		$('.timepicker').timepicker({
            autoclose: true,
            minuteStep: 5,
        });

		$('#rq_inspection_body').scroll(function() {
			if($('#rq_inspection_body').scrollTop() + $('#rq_inspection_body').height() >= $('#rq_inspection_body').height()) {
				row = row+2;
				getRequalification(row);
			}
		});

		$('#frm_upload').on('submit', function(event) {
			var formObj = $('#frm_upload');
			var formURL = formObj.attr("action");
			var formData = new FormData(this);
			event.preventDefault();

			var inspection_data = $('#inspection_data').val();
			var inspection_mod = $('#inspection_mod').val();
			var requali_data = $('#requali_data').val();
			var requali_mod = $('#requali_mod').val();

			if (inspection_data != '' && checkFile(inspection_data) == false) {
				msg("The Inspection data in not a valid excel file.",'failed')
			} else if (inspection_mod != '' &&  checkFile(inspection_mod) == false) {
				msg("The Mode of defects for Inspection data in not a valid excel file.",'failed')
			} else if (requali_data != '' &&  checkFile(requali_data) == false) {
				msg("The Re-qualification data in not a valid excel file.",'failed')
			} else if (requali_mod != '' &&  checkFile(requali_mod) == false) {
				msg("The Mode of defects for Re-qualification data in not a valid excel file.",'failed')
			} else {
				$.ajax({
					url: formURL,
					method: 'POST',
					data:  formData,
					mimeType:"multipart/form-data",
					contentType: false,
					cache: false,
					processData:false,
				}).done( function(data, textStatus, jqXHR) {
					var out = JSON.parse(data);
					msg(out.msg,'success')
					console.log(out);
				}).fail( function(data, textStatus, jqXHR) {
					msg("There's an error occurred while processing.",'failed')
				});
			}
		});

		$('#time_ins_from').on('change', function() {
			var time = setTime($(this).val());
			if (time.includes('::')) {
				$(this).val(time.replace('::',':'));
			} else {
				$(this).val(time);
			}
		});

		$('#time_ins_to').on('change', function() {
			var time = setTime($(this).val());
			if (time.includes('::')) {
				$(this).val(time.replace('::',':'));
			} else {
				$(this).val(time);
			}
			getShift($('#time_ins_from').val(),$(this).val(),'#shift');
		});

		$('#time_ins_from_man').on('change', function() {
			var time = setTime($(this).val());

			if (time.includes('::')) {
				$(this).val(time.replace('::',':'));
			} else {
				$(this).val(time);
			}
		});
		
		$('#time_ins_to_man').on('change', function() {
			var time = setTime($(this).val());
			if (time.includes('::')) {
				$(this).val(time.replace('::',':'));
			} else {
				$(this).val(time);
			}
		});

		// INSPECTION SIDE

        $('#btn_iqcresult').on('click', function() {
        	clear();
   			// $('#invoice_no').prop('readonly',false);
			// $('#partcode').prop('readonly',true);
			// $('#lot_no').prop('readonly',true);

			getDropdowns();

			getFiscalYear();
			getIQCworkWeek();
			//$('#classification').val(['Appearance Inspection']);

			$('#accept').val(0);
			$('#reject').val(1);
			$('#lot_inspected').val(1);
			$('#lot_accepted').val(1);

			if ($('#lot_accepted').val() == 1) {
				$('#no_defects_label').hide();
				$('#no_of_defects').hide();
				$('#mode_defects_label').hide();
				$('#btn_mod_ins').hide();
				$('#judgement').val('Accepted');
			}

			$('#save_status').val('ADD');

			$('#partcodelbl_div').hide();
			$('#partcode_div').show();

			$('#IQCresultModal').modal('show');
        });

        $('#btn_iqcresult_man').on('click', function() {
        	clear();
   			// $('#invoice_no').prop('readonly',false);
			// $('#partcode').prop('readonly',true);
			// $('#lot_no').prop('readonly',true);

			getDropdowns_man();

			$('#no_defects_label_man').hide();
			$('#no_of_defects_man').hide();
			$('#mode_defects_label_man').hide();
			$('#btn_mod_ins_man').hide();

			$('#save_status_man').val('ADD');

			$('#ManualModal').modal('show');
        });

        $('#btn_upload').on('click', function(){
            $('#uploadModal').modal('show');
        });

        $('#btn_groupby').on('click', function(){
            $('#GroupByModal').modal('show');
        });

        $('#btn_history').on('click', function() {
        	$('#historyModal').modal('show');
        });

        $('#btn_search').on('click', function(){
			getPartcodeSearch();
            $('#SearchModal').modal('show');
        });

		$('#btn_searchnow').on('click', function() {
			searchItemInspection();
		});

        $('#invoice_no').on('change', function(){
        	$('#er_invoice_no').html('');
            getItems();
        });

        $('#partcode').on('change', function(){
            getItemDetails();
        });

		$('#lot_no').on('change', function() {
			calculateLotQty($(this).select2('val'));
		});

		$('#severity_of_inspection').on('change', function() {
			samplingPlan();
		});

		$('#inspection_lvl').on('change', function() {
			samplingPlan();
		});

		$('#aql').on('change', function() {
			samplingPlan();
		});

		// $('#time_ins_from').on('change', function() {
		// 	getShift();
		// });
		// $('#time_ins_to').on('change', function() {
		// 	getShift();
		// });

		$('#btn_clearmodal').on('click', function() {
			clear();
		});

		$('#btn_mod_ins').on('click', function() {
			iqcdbgetmodeofdefectsinspection();
			$('#mod_inspectionModal').modal('show');
		});

		$('#bt_save_modeofdefectsinspection').on('click', function() {
			saveModeOfDefectsInspection();
		});

		$('#lot_accepted').on('change', function() {
			openModeOfDefects();
		});

		$('#tblformodinspection').on('click', '.modinspection_edititem',function() {
			var mod = $(this).attr('data-mod');
			var qty = $(this).attr('data-qty');
			var id = $(this).attr('data-id');

			$('#mod_inspection').select2('data',{
				id: mod,
				text:mod
			});
			$('#qty_inspection').val(qty);
			$('#id_inspection').val(id);
			$('#status_inspection').val('EDIT');
		});

		$('.checkAllitemsinspection').on('change', function(e) {
			$('input:checkbox.modinspection_checkitem').not(this).prop('checked', this.checked);
		});

		$('.iqc_checkall').on('change', function(e) {
			$('input:checkbox.iqc_checkitems').not(this).prop('checked', this.checked);
		});

		$('.ongoing_checkall').on('change', function(e) {
			$('input:checkbox.ongiong_checkitems').not(this).prop('checked', this.checked);
		});

		$('#tblforiqcinspection').on('click','.btn_editiqc',function() {
			$('#partcode_div').hide();
			$('#partcodelbl_div').show();
			//$('#partcode').select2('container').hide();
			getDropdowns();
			$('#invoice_no').prop('readonly',true);
			$('#invoice_no').val($(this).attr('data-invoice_no'));
			getItems();

			$('#partcodelbl').val($(this).attr('data-partcode'));
			getItemDetailsEdit();
			//$('#partcode').hide();

			$('#partname').val($(this).attr('data-partname'));
			$('#classification').val([$(this).attr('data-classification')]);
			$('#family').val([$(this).attr('data-family')]);
			$('#supplier').val($(this).attr('data-supplier'));
			$('#app_date').val($(this).attr('data-app_date'));
			$('#app_time').val($(this).attr('data-app_time'));
			$('#app_no').val($(this).attr('data-app_no'));

			$('#lot_no').val([$(this).attr('data-lot_no')]);

			$('#lot_qty').val($(this).attr('data-lot_qty'));
			$('#type_of_inspection').val([$(this).attr('data-type_of_inspection')]);
			$('#severity_of_inspection').val([$(this).attr('data-severity_of_inspection')]);
			$('#inspection_lvl').val([$(this).attr('data-inspection_lvl')]);
			$('#aql').val([$(this).attr('data-aql')]);
			$('#accept').val($(this).attr('data-accept'));
			$('#reject').val($(this).attr('data-reject'));
			$('#date_inspected').val($(this).attr('data-date_ispected'));
			$('#ww').val($(this).attr('data-ww'));
			$('#fy').val($(this).attr('data-fy'));
			$('#time_ins_from').val($(this).attr('data-time_ins_from'));
			$('#time_ins_to').val($(this).attr('data-time_ins_to'));
			$('#shift').val([$(this).attr('data-shift')]);
			$('#inspector').val($(this).attr('data-inspector'));
			$('#submission').val([$(this).attr('data-submission')]);
			$('#judgement').val($(this).attr('data-judgement'));
			$('#lot_inspected').val($(this).attr('data-lot_inspected'));
			$('#lot_accepted').val($(this).attr('data-lot_accepted'));
			$('#sample_size').val($(this).attr('data-sample_size'));
			$('#no_of_defects').val($(this).attr('data-no_of_defects'));
			$('#remarks').val($(this).attr('data-remarks'));

			$('#save_status').val('EDIT');
			$('#iqc_result_id').val($(this).attr('data-id'));
			//$('#partcode').hide();
			//$('#partcode').select2('container').hide();

			if ($().val() == 1) {
				$('#no_defects_label').hide();
				$('#no_of_defects').hide();
				$('#mode_defects_label').hide();
				$('#btn_mod_ins').hide();
			} else {
				openModeOfDefects();
			}

			if ($('#accept').val() == '') {
				getFiscalYear();
				$('#accept').val(0);
				$('#reject').val(1);
			}

			$('#IQCresultModal').modal('show');
		});

		$('#tblforongoing').on('click','.btn_editongiong',function() {
			getDropdowns();
			$('#invoice_no').prop('readonly',true);
			$('#invoice_no').val($(this).attr('data-invoice_no'));
			//getItems();
			$('#partcodelbl').val($(this).attr('data-partcode'));
			$('#partcode').val($(this).attr('data-partcode'));
			getItemDetailsEdit();

			console.log($(this).attr('data-lot_no').split(','));

			$('#partname').val($(this).attr('data-partname'));
			$('#supplier').val($(this).attr('data-supplier'));
			$('#app_date').val($(this).attr('data-app_date'));
			$('#app_time').val($(this).attr('data-app_time'));
			$('#app_no').val($(this).attr('data-app_no'));

			$('#lot_no').val([$(this).attr('data-lot_no')]);
			// $('#lot_no').val($(this).attr('data-lot_no').split(','));
			$('#lot_qty').val($(this).attr('data-lot_qty'));
			$('#type_of_inspection').val([$(this).attr('data-type_of_inspection')]);
			$('#severity_of_inspection').val([$(this).attr('data-severity_of_inspection')]);
			$('#inspection_lvl').val([$(this).attr('data-inspection_lvl')]);
			$('#aql').val([$(this).attr('data-aql')]);
			$('#accept').val($(this).attr('data-accept'));
			$('#reject').val($(this).attr('data-reject'));
			$('#date_inspected').val($(this).attr('data-date_ispected'));
			$('#ww').val($(this).attr('data-ww'));
			$('#fy').val($(this).attr('data-fy'));
			$('#time_ins_from').val($(this).attr('data-time_ins_from'));
			$('#time_ins_to').val($(this).attr('data-time_ins_to'));
			$('#shift').val([$(this).attr('data-shift')]);
			$('#inspector').val($(this).attr('data-inspector'));
			$('#submission').val([$(this).attr('data-submission')]);
			$('#judgement').val($(this).attr('data-judgement'));
			$('#lot_inspected').val($(this).attr('data-lot_inspected'));
			$('#lot_accepted').val($(this).attr('data-lot_accepted'));
			$('#sample_size').val($(this).attr('data-sample_size'));
			$('#no_of_defects').val($(this).attr('data-no_of_defects'));
			$('#remarks').val($(this).attr('data-remarks'));

			if ($(this).attr('data-batching') > 0) {
				$('#is_batching').prop('checked', true);
			} else {
				$('#is_batching').prop('checked', false);
			}

			$('#save_status').val('EDIT');
			$('#iqc_result_id').val($(this).attr('data-id'));

			$('#partcodelbl_div').hide();
			$('#partcode_div').show();
			// $('#partcode').select2('container').show();

			//openModeOfDefects();

			if ($('#classification').val() == '') {
				$('#classification').val(['Appearance Inspection']);
			}

			if ($('#type_of_inspection').val() == '') {
				$('#type_of_inspection').val(['Single']);
			}

			if ($('#accept').val() == '') {
				getFiscalYear();
				getIQCworkWeek();

				$('#accept').val(0);
				$('#reject').val(1);
				$('#lot_inspected').val(1);
				$('#lot_accepted').val(1);
			}

			if ($('#lot_accepted').val() == 1) {
				$('#judgement').val('Accepted');
				$('#no_defects_label').hide();
				$('#no_of_defects').hide();
				$('#mode_defects_label').hide();
				$('#btn_mod_ins').hide();
			}

			$('#IQCresultModal').modal('show');
		});

		$('#bt_delete_modeofdefectsinspection').on('click', function() {
			$('#delete_type').val('modins');
			$('#confirmDeleteModal').modal('show');
		});

		$('#btn_deleteyes').on('click', function() {
			var type = $('#delete_type').val();

			if (type == 'inspection') {
				deleteInspection();
			}

			if (type == 'requali') {
				deleteRequali();
			}

			if (type == 'modrq') {
				deleteModRQ();
			}

			if (type == 'modins') {
				deleteModIns();
			}

			if (type == 'on-going') {
				deleteOnGoing();
			}
		});

		$('#btn_delete_inspected').on('click', function() {
			$('#delete_type').val('inspection');
			$('#confirmDeleteModal').modal('show');
		});

		$('#btn_delete_ongoing').on('click', function() {
			$('#delete_type').val('on-going');
			$('#confirmDeleteModal').modal('show');
		});

		//REQUALIFICATION
		$('#btn_requali').on('click', function() {
			getItemsRequalification();
			getDropdownsRequali();
			$('#no_defects_label_rq').hide();
			$('#no_of_defects_rq').hide();
			$('#mode_defects_label_rq').hide();
			$('#btn_mod_rq').hide();
			$('#save_status_rq').val('ADD');

			getRequalification(5);

            $('#ReQualiModal').modal('show');
        });

		$('#partcode_rq').on('change', function() {
			getAppNo();
		});

		$('#app_no_rq').on('change', function() {
			getDetailsRequalification();
			getVisualInspectionRequalification();
		});

		$('#lot_no_rq').on('change', function() {
			calculateLotQtyRequalification($(this).select2('val'));
		});

		$('#btn_mod_rq').on('click', function() {
			$('#status_requalification').val('ADD');
			iqcdbgetmodeofdefectsRequali();
			$('#mod_requalificationModal').modal('show');
		});

		$('#lot_accepted_rq').on('change', function() {
			if ($(this).val() == 0) {
				$('#no_defects_label_rq').show();
				$('#no_of_defects_rq').show();
				$('#mode_defects_label_rq').show();
				$('#btn_mod_rq').show();
				$('#judgement_rq').val('Rejected');
			} else {
				$(this).val(1);
				$('#no_defects_label_rq').hide();
				$('#no_of_defects_rq').hide();
				$('#mode_defects_label_rq').hide();
				$('#btn_mod_rq').hide();

				$('#judgement_rq').val('Accepted');
			}
		});

		$('#rq_inspection_body').on('click', '.btn_editRequali',function() {
			$('#ctrl_no_rq').val($(this).attr('data-ctrl_no'));
			$('#partcode_rq').select2('val',$(this).attr('data-partcode'));
			getDropdownsRequali();
			getAppNo();

			$('#partname_rq').val($(this).attr('data-partname'));
			$('#supplier_rq').val($(this).attr('data-supplier'));
			$('#app_date_rq').val($(this).attr('data-app_date'));
			$('#app_time_rq').val($(this).attr('data-app_time'));
			$('#app_no_rq').val([$(this).attr('data-app_no')]);

			getDetailsRequalification();
			getVisualInspectionRequalification();

			$('#lot_no_rq').val([$(this).attr('data-lot_no')]);
			$('#lot_qty_rq').val($(this).attr('data-lot_qty'));

			$('#date_ispected_rq').val($(this).attr('data-date_ispected'));
			$('#ww_rq').val($(this).attr('data-ww'));
			$('#fy_rq').val($(this).attr('data-fy'));
			$('#time_ins_from_rq').val($(this).attr('data-time_ins_from'));
			$('#time_ins_to_rq').val($(this).attr('data-time_ins_to'));
			$('#shift_rq').val([$(this).attr('data-shift')]);
			$('#inspector_rq').val($(this).attr('data-inspector'));
			$('#submission_rq').val([$(this).attr('data-submission')]);
			$('#judgement_rq').val($(this).attr('data-judgement'));
			$('#lot_inspected_rq').val($(this).attr('data-lot_inspected'));
			$('#lot_accepted_rq').val($(this).attr('data-lot_accepted'));
			$('#no_of_defects_rq').val($(this).attr('data-no_of_defects'));
			$('#remarks_rq').val($(this).attr('data-remarks'));
			$('#id_rq').val($(this).attr('data-id'));

			$('#save_status_rq').val('EDIT');
		});

		$('.checkAllitemsrq').on('change', function(e) {
			$('input:checkbox.checitemrq').not(this).prop('checked', this.checked);
		});

		$('#btn_deleteRequali').on('click', function() {
			$('#delete_type').val('requali');
			$('#confirmDeleteModal').modal('show');
		});

		$('.checkAllitemsrequalification').on('change', function(e) {
			$('input:checkbox.modrq_checkitem').not(this).prop('checked', this.checked);
		});

		$('#btn_deletemodrq').on('click', function() {
			$('#delete_type').val('modrq');
			$('#confirmDeleteModal').modal('show');
		});

		$('#tblformodrequalification').on('change', '.modrq_edititem', function() {
			var mod = $(this).attr('data-mod');
			var qty = $(this).attr('data-qty');
			var id = $(this).attr('data-id');

			$('#mod_rq').select2('data',{
				id: mod,
				text:mod
			});
			$('#qty_rq').val(qty);
			$('#id_requalification').val(id);
			$('#status_requalification').val('EDIT');
		});


		// EXPORTS
		$('#btn_pdf').on('click', function() {
			window.location.href= "{{ url('/iqcprintreport?') }}";
		});

		$('#btn_excel').on('click', function() {
			window.location.href= "{{ url('/iqcprintreportexcel')  }}";
		});

		$('#btn_searchHistory').on('click', function() {
			var tblhistorybody = '';
			$('#tblhistorybody').html('');
			var url = "{{url('/iqcdbgethistory')}}";
			var token = "{{Session::token()}}";
			var data = {
				_token:token,
				item: $('#hs_partcode').val(),
				lotno: $('#hs_lotno').val(),
				judgement: $('#hs_judgement').val(),
				from: $('#hs_from').val(),
				to: $('#hs_to').val(),
			};

			$.ajax({
				url: url,
				type: "GET",
				dataType: "JSON",
				data: data
			}).done( function(data,textStatus,jqXHR) {
				var color = '';
				$.each(data, function(i, x) {
					if (x.judgement == 'Accepted') {
						color = '#009490';
					} else {
						color = '#f04646';
					}
					tblhistorybody = '<tr>'+
										'<td style="width: 11.67%">'+x.invoice_no+'</td>'+
										'<td style="width: 11.67%">'+x.partcode+'</td>'+
										'<td style="width: 30.67%">'+x.partname+'</td>'+
										'<td style="width: 16.67%">'+x.lot_no+'</td>'+
										'<td style="width: 12.67%">'+x.lot_qty+'</td>'+
										'<td style="background-color:'+color+'; width: 16%;">'+x.judgement+'</td>'+
									'</tr>';
					$('#tblhistorybody').append(tblhistorybody);
				});
			}).fail( function(data,textStatus,jqXHR) {
				msg("There's some error while processing.",'failed');
			});
		});

		//MANUAL INPUT
		$('#severity_of_inspection_man').on('change', function() {
			samplingPlan_man();
		});

		$('#inspection_lvl_man').on('change', function() {
			samplingPlan_man();
		});

		$('#aql_man').on('change', function() {
			samplingPlan_man();
		});

		// $('#time_ins_from_man').on('change', function() {
		// 	getShift_man();
		// });
		// $('#time_ins_to_man').on('change', function() {
		// 	getShift_man();
		// });

		$('#btn_clearmodal_man').on('click', function() {
			clear();
		});

		$('#btn_mod_ins_man').on('click', function() {
			iqcdbgetmodeofdefectsinspection();
			$('#mod_inspectionModal').modal('show');
		});

		$('#lot_accepted_man').on('change', function() {
			openModeOfDefects_man();
		});
    });

	function setTime(time_input) {
		var time = time_input.replace('::',':');
		var h = time.substring(0,2);
		var m = time.substring(2,5);
		var a = time.substring(6,8);

		if (m == undefined || m == '' || m == null) {
			m = '00';
		}

		if (h < 12 && h > 0) {
			if (a == undefined || a == '' || a == null || a == 'A') {
				a = 'AM';
			}
			return h+":"+m+" "+a;
		} else if (h == 00 || h == 0) {
			if (a == undefined || a == '' || a == null || a == 'A') {
				a = 'AM';
			}
			return "12"+":"+m+" "+a;
		}  else if (h == 12) {
			if (a == undefined || a == '' || a == null || a == 'P') {
				a = 'PM';
			}
			return h+":"+m+" "+a;
		} else {
			if (a == undefined || a == '' || a == null || a == 'P') {
				a = 'PM';
			}
			switch(h) {
				case '13':
					var time_now = "01"+":"+m+" "+a;
					return time_now;
					break;
				case '14':
					var time_now = "02"+":"+m+" "+a;
					return time_now;
					break;
				case '15':
					var time_now = "03"+":"+m+" "+a;
					return time_now;
					break;
				case '16':
					var time_now = "04"+":"+m+" "+a;
					return time_now;
					break;
				case '17':
					var time_now = "05"+":"+m+" "+a;
					return time_now;
					break;
				case '18':
					var time_now = "06"+":"+m+" "+a;
					return time_now;
					break;
				case '19':
					var time_now = "07"+":"+m+" "+a;
					return time_now;
					break;
				case '20':
					var time_now = "08"+":"+m+" "+a;
					return time_now;
					break;
				case '21':
					var time_now = "09"+":"+m+" "+a;
					return time_now;
					break;
				case '22':
					var time_now = "10"+":"+m+" "+a;
					return time_now;
					break;
				case '23':
					var time_now = "11"+":"+m+" "+a;
					return time_now;
					break;
				default:
					return time;
					break;
			}
		}
	}


	// INSPECTION SIDE
	function getIQCInspection(url) {
		$('#iqcdatatable').dataTable().fnClearTable();
        $('#iqcdatatable').dataTable().fnDestroy();
        $('#iqcdatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            lengthMenu: [
	            [5, 10, 20, 100, -1],
	            [5, 10, 20, 100,"All"]
	        ],
	        pageLength: 10, 
            columns: [
                {data: function(data){
                        return '<input type="checkbox" class="iqc_checkitems" value="'+data.id+'"/>';
                },orderable: false, searchable:false, name:"id" },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'invoice_no', name: 'invoice_no'},
                { data: 'inspector', name: 'inspector'},
				{ data: 'date_ispected', name: 'date_ispected'},
				{ data: function(data) {
					return data.time_ins_from;
				}, name: 'time_ins_from'},
				{ data: 'app_no', name: 'app_no'},
				{ data: 'app_date', name: 'app_date'},
				{ data: 'app_time', name: 'app_time'},
				{ data: 'fy', name: 'fy'},
				{ data: 'ww', name: 'ww'},
				{ data: 'submission', name: 'submission'},
				{ data: 'partcode', name: 'partcode'},
				{ data: 'partname', name: 'partname'},
				{ data: 'supplier', name: 'supplier'},
				{ data: 'lot_no', name: 'lot_no'},
				{ data: 'aql', name: 'aql'},
				{ data: 'lot_qty', name: 'lot_qty' },
				{ data: 'type_of_inspection', name: 'type_of_inspection' },
				{ data: 'severity_of_inspection', name: 'severity_of_inspection' },
				{ data: 'inspection_lvl', name: 'inspection_lvl' },
				{ data: 'accept', name: 'accept' },
				{ data: 'reject', name: 'reject' },
				{ data: 'shift', name: 'shift' },
				{ data: 'lot_inspected', name: 'lot_inspected' },
				{ data: 'lot_accepted', name: 'lot_accepted' },
				{ data: 'sample_size', name: 'sample_size' },
				{ data: 'no_of_defects', name: 'no_of_defects' },
				{ data: 'classification', name: 'classification' },
				{ data: 'family', name: 'family' },
				{ data: 'remarks', name: 'remarks' },
				{ data: 'judgement', name: 'judgement'}
            ],
            aoColumnDefs: [
                {
                    aTargets:[31], // You actual column with the string 'America'
                    fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                        $(nTd).css('font-weight', '700');
                        if(sData == "Accepted") {
                            $(nTd).css('background-color', '#c49f47');
                            $(nTd).css('color', '#fff');
                        }
                        if(sData == "Rejected") {
                            $(nTd).css('background-color', '#cb5a5e');
                            $(nTd).css('color', '#fff');
                        }
                        if(sData == "On-going") {
                            $(nTd).css('background-color', '#3598dc');
                            $(nTd).css('color', '#fff');
                        }
                    },
                }
            ],
            order: [[0,'desc']]
        });
	}

	function getOnGoing() {
		$('#on-going-inspection').dataTable().fnClearTable();
        $('#on-going-inspection').dataTable().fnDestroy();
        $('#on-going-inspection').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{url('/iqcdbgetongoing')}}",
            lengthMenu: [
	            [5, 10, 20, 100, -1],
	            [5, 10, 20, 100,"All"]
	        ],
	        pageLength: 10, 
            columns: [
                {data: function(data){
                        return '<input type="checkbox" class="ongiong_checkitems" value="'+data.id+'"/>';
                },orderable: false, searchable:false, name:"id" },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'invoice_no', name: 'invoice_no'},
                { data: 'inspector', name: 'inspector'},
				{ data: 'date_ispected', name: 'date_ispected'},
				{ data: function(data) {
					return data.time_ins_from+' - present';
				}, name: 'time_ins_from'},
				{ data: 'app_no', name: 'app_no'},
				{ data: 'fy', name: 'fy'},
				{ data: 'ww', name: 'ww'},
				{ data: 'submission', name: 'submission'},
				{ data: 'partcode', name: 'partcode'},
				{ data: 'partname', name: 'partname'},
				{ data: 'supplier', name: 'supplier'},
				{ data: 'lot_no', name: 'lot_no'},
				{ data: 'aql', name: 'aql'},
				{ data: 'judgement', name: 'judgement'}, 
            ],
            aoColumnDefs: [
                {
                    aTargets:[15], // You actual column with the string 'America'
                    fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                        $(nTd).css('font-weight', '700');
                        if(sData == "Accepted") {
                            $(nTd).css('background-color', '#c49f47');
                            $(nTd).css('color', '#fff');
                        }
                        if(sData == "Rejected") {
                            $(nTd).css('background-color', '#cb5a5e');
                            $(nTd).css('color', '#fff');
                        }
                        if(sData == "On-going") {
                            $(nTd).css('background-color', '#3598dc');
                            $(nTd).css('color', '#fff');
                        }
                    },
                }
            ],
            order: [[0,'desc']]
        });
	}

	function saveInspection() {
		$('#loading').modal('show');

		if (requiredFields(':input.required') == true) {
			var url = "{{url('/iqcsaveinspection')}}";
			var token = "{{Session::token()}}";
			var partcode = $('#partcode').val();
			var batching = 0;

			if ($('#save_status').val() == 'EDIT') {
				partcode = $('#partcodelbl').val();
			}

			if ($('#is_batching').is(":checked")) {
				batching = 1;
			}

			var data = {
				_token: token,
				save_status: $('#save_status').val(),
				id: $('#iqc_result_id').val(),
				invoice_no: $('#invoice_no').val(),
				partcode: partcode,
				partname: $('#partname').val(),
				supplier: $('#supplier').val(),
				app_date: $('#app_date').val(),
				app_time: $('#app_time').val(),
				app_no: $('#app_no').val(),
				lot_no: $('#lot_no').val(),
				lot_qty: $('#lot_qty').val(),
				type_of_inspection: $('#type_of_inspection').val(),
				severity_of_inspection: $('#severity_of_inspection').val(),
				inspection_lvl: $('#inspection_lvl').val(),
				aql: $('#aql').val(),
				accept: $('#accept').val(),
				reject: $('#reject').val(),
				date_inspected: $('#date_inspected').val(),
				ww: $('#ww').val(),
				fy: $('#fy').val(),
				time_ins_from: $('#time_ins_from').val(),
				time_ins_to: $('#time_ins_to').val(),
				shift: $('#shift').val(),
				inspector: $('#inspector').val(),
				submission: $('#submission').val(),
				judgement: $('#judgement').val(),
				lot_inspected: $('#lot_inspected').val(),
				lot_accepted: $('#lot_accepted').val(),
				sample_size: $('#sample_size').val(),
				no_of_defects: $('#no_of_defects').val(),
				remarks: $('#remarks').val(),
				classification: $('#classification').val(),
				family: $('#family').val(),
				is_batching: batching
			};

			$.ajax({
				url: url,
				type: "POST",
				dataType: "JSON",
				data: data
			}).done( function(data,textStatus,jqXHR) {
				$('#loading').modal('hide');

				if (data.return_status == 'success') {
					msg(data.msg,'success');
					//clear();
					// $('#IQCresultModal').modal('hide');
					getIQCInspection("{{url('/iqcdbgetiqcdata')}}");
					getOnGoing();
				}
			}).fail( function(data,textStatus,jqXHR) {
				$('#loading').modal('hide');
				msg("There's some error while processing.",'failed');
			});
		} else {
			$('#loading').modal('hide');
			msg("Please fill out all required fields.",'failed');
		}	
	}

	function clear() {
		$('.clear').val('');
		$('.clearselect').select2('data', {
			id: '',
			text: ''
		})
		$('#invoice_no').prop('readonly',false);
		$('#er_invoice_no').html('');
	}

	function samplingPlan() {
		var url = "{{url('/iqcsamplingplan')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token,
			soi: $('#severity_of_inspection').val(),
			il: $('#inspection_lvl').val(),
			aql: $('#aql').val(),
			lot_qty: $('#lot_qty').val()
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data, textStatus, jqXHR){
			$('#accept').val(data.accept);
			$('#reject').val(data.reject);
			$('#sample_size').val(data.sample_size);
			$('#date_inspected').val(data.date_inspected);
			$('#lot_inspected').val(1);
			$('#inspector').val(data.inspector);
			getFiscalYear();
		}).fail(function(data, textStatus, jqXHR){
			msg("There's some error while processing.",'failed');
		});
	}

	function getDropdowns() {
		var url = "{{url('/iqcgetdropdowns')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data,textStatus,jqXHR) {
			$('#type_of_inspection').select2({
				data:data.tofinspection,
				placeholder: "Select Type of Inspection"
			});
			$('#severity_of_inspection').select2({
				data:data.sofinspection,
				placeholder: "Select Severity of Inspection"
			});
			$('#inspection_lvl').select2({
				data:data.inspectionlvl,
				placeholder: "Select Inspection Level"
			});
			$('#aql').select2({
				data:data.aql,
				placeholder: "Select AQL"
			});
			$('#submission').select2({
				data: data.submission,
				placeholder: "Select Submission"
			});
			$('#submission').val('1st');
			$('#submission').trigger('change');

			$('#mod_inspection').select2({
				data: data.mod,
				placeholder: "Select Mode of Defects"
			});

			$('#supplier').select2({
				data: data.supplier,
				placeholder: "Select Supplier"
			});

			// $('#classification').select2({
			// 	data: data.classification,
			// 	placeholder: "Select Classification"
			// });

			$('#family').select2({
				data: data.family,
				placeholder: "Select Family"
			});
		}).fail(function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

    function getFiscalYear() {
        var date = new Date();
        var month = date.getMonth();
        var year = date.getFullYear();

        if (month < 3) {
            year = year - 1;
        }

        $('#fy').val(year);
    }

    function getItems() {
         var url = "{{url('/iqcdbgetitems')}}";
         var token = "{{ Session::token() }}";
         var data = {
              _token: token,
              invoiceno: $('#invoice_no').val()
         };

         $.ajax({
              url: url,
              type: "GET",
              data: data,
         }).done( function(data, textStatus, jqXHR) {
         	if (data.length < 1) {
         		console.log(data);
         		$('#er_invoice_no').html("Your Invoice might be invalid / Not yet received / All Item codes were inspected.");
         	} else {
	     		$('#partcode').prop('readonly',false);
				$('#lot_no').prop('readonly',false);
				$('#partcode').select2({
					data: data,
					placeholder: "Select an Item"
				});
         	}
			 
         }).fail( function(data, textStatus, jqXHR) {
              msg("There's some error while processing.",'failed');
         });
    }

    function getItemDetails() {
    	$('#lot_no').select2({
			tags: true,
			data: '',
			placeholder: 'Select Lot Number'
		});

		var partcode = $('#partcode').val();

		if ($('#partcode').val() == '') {
			partcode = $('#partcodelbl').val();
		}
    	
        var url = "{{url('/iqcdbgetitemdetails')}}";
        var token = "{{ Session::token() }}";
        var data = {
             _token: token,
             invoiceno: $('#invoice_no').val(),
             item: partcode
        };

        $.ajax({
             url: url,
             type: "GET",
             data: data,
        }).done( function(data, textStatus, jqXHR) {
			var details = data.details;
			$('#partname').val(details.item_desc);
			$('#supplier').val(details.supplier);
			$('#app_date').val(details.app_date);
			$('#app_time').val(details.app_time);
			$('#app_no').val(details.receive_no);

			console.log(data.lot);

			$('#lot_no').select2({
				tags: true,
				data: data.lot,
				placeholder: 'Select Lot Number'
			});
        }).fail( function(data, textStatus, jqXHR) {
             msg("There's some error while processing.",'failed');
        });
    }

    function getItemDetailsEdit() {
    	$('#lot_no').select2({
			tags: true,
			data: '',
			placeholder: 'Select Lot Number'
		});

		var partcode = $('#partcodelbl').val();

		// if ($('#partcode').val() == '') {
		// 	partcode = $('#partcodelbl').val();
		// }
    	
        var url = "{{url('/iqcdbgetitemdetails')}}";
        var token = "{{ Session::token() }}";
        var data = {
             _token: token,
             invoiceno: $('#invoice_no').val(),
             item: partcode
        };

        $.ajax({
             url: url,
             type: "GET",
             data: data,
        }).done( function(data, textStatus, jqXHR) {
			var details = data.details;

			var lots = [];

			$.each(data.lot, function(i, x) {
				var lot = x.id
				lots.push(lot.replace(' ',''));
			});

			// console.log(lots);
			$('#lot_no').select2({
				tags: true,
				data: data.lot,
				placeholder: 'Select Lot Number'
			});

			$('#lot_no').select2({maximumSelectionLength: 1000,tags:lots});
        }).fail( function(data, textStatus, jqXHR) {
             msg("There's some error while processing.",'failed');
        });
    }

	function calculateLotQty(lotno) {
		var items = $('#partcode').val();

		if (items == '') {
			items = $('#partcodelbl').val();
		}

		var url = "{{url('/iqccalculatelotqty')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token:token,
			invoiceno: $('#invoice_no').val(),
			item: items,
			lot_no: lotno
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done( function(data, textStatus, jqXHR) {
			$('#lot_qty').val(data);
		}).fail( function(data, textStatus, jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function saveModeOfDefectsInspection() {
		var url = "{{url('/iqcdbsavemodeofdefects')}}";
		var token  = "{{Session::token()}}";
		var data = {
			_token: token,
			invoiceno: $('#invoice_no').val(),
			item: $('#partcode').val(),
			mod: $('#mod_inspection').val(),
			lot_no: $('#lot_no').val(),
			qty: $('#qty_inspection').val(),
			status: $('#status_inspection').val(),
			id: $('#id_inspection').val(),
			current_count: $('#mod_total_qty').val(),
			sample_size: $('#sample_size').val()
		};

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			if (data.return_status == "success") {
				msg(data.msg,'success');
				console.log(data.count);
			} else {
				msg(data.msg,'failed');
				console.log(data.count);
			}
			iqcdbgetmodeofdefectsinspection();
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function getModinspectionlist(data) {
		var cnt = 0;
		var no_of_defectives = 0;
		var qty = 0;
		var max = [];
		$.each(data, function(i,x) {
			cnt++;
			tblformodinspection = '<tr>'+
										'<td style="width: 8%">'+
											'<input type="checkbox" class="modinspection_checkitem checkboxes" value="'+x.id+'">'+
										'</td>'+
										'<td style="width: 12%">'+
											'<a href="javascript:;" class="btn blue input-sm modinspection_edititem" data-mod="'+x.mod+'" data-qty="'+x.qty+'" data-id="'+x.id+'">'+
												'<i class="fa fa-edit"></i>'+
											'</a>'+
										'</td>'+
										'<td style="width: 5%">'+cnt+'</td>'+
										'<td style="width: 55%">'+x.mod+'</td>'+
										'<td style="width: 20%">'+x.qty+'</td>'+
									'</tr>';

			if (x.qty == $('#sample_size').val()) {
				no_of_defectives = x.qty;
			} else {
				max.push(x.qty);
				no_of_defectives = Math.max.apply(null,max);
			}
			//no_of_defectives = parseFloat(no_of_defectives) + parseFloat(x.qty);
			
			qty = parseFloat(qty) + parseFloat(x.qty);
			$('#tblformodinspection').append(tblformodinspection);
		});
		$('#mod_count').val(cnt);
		$('#mod_total_qty').val(qty);
		$('#no_of_defects').val(no_of_defectives);
		$('#status_inspection').val('ADD');
	}

	function iqcdbgetmodeofdefectsinspection() {
		$('#tblformodinspection').html('');
		var tblformodinspection = '';
		var url = "{{url('/iqcdbgetmodeofdefectsinspection')}}";
		var token  = "{{Session::token()}}";
		var data = {
			_token: token,
			invoiceno: $('#invoice_no').val(),
			item: $('#partcode').val(),
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			getModinspectionlist(data);
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function getAllChecked(element) {
		var chkArray = [];

		$(element+":checked").each(function() {
			chkArray.push($(this).val());
		});

		return chkArray;
	}

	function getInspectionMonth(date) {
		var monthNames = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"
		];

		var d = new Date(date);
		return monthNames[d.getMonth()];
	}

	function requiredFields(requiredClass) {
		var reqlength = $(requiredClass).length;
	    var value = $(requiredClass).filter(function () {
	        return this.value != '';
	    });

	    if (value.length !== reqlength) {
	        return false;
	    } else {
			console.log('true');
	        return true;
	    }
	}

	//search button
	function getPartcodeSearch() {
		var url = "{{url('/iqcdbgetitemsearch')}}";
		var token = "{{ Session::token() }}";
		var data = {
			 _token: token,
		};

		$.ajax({
			 url: url,
			 type: "GET",
			 data: data,
		}).done( function(data, textStatus, jqXHR) {
			if (data == null || data == "") {
				$('#search_partcode_error').html("No Inspections Available");
			} else {
				$('#search_partcode').select2({
	   				 data: data,
	   				 placeholder: "Select an Item"
	   			 });
			}
		}).fail( function(data, textStatus, jqXHR) {
			 msg("There's some error while processing.",'failed');
		});
	}

	function searchItemInspection() {
		var tblforiqcinspection = '';
		$('#tblforiqcinspection').html('');
		var url = "{{url('/iqcdbsearchinspection')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token,
			item: $('#search_partcode').val(),
			from: $('#search_from').val(),
			to: $('#search_to').val()
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			getIQCdataTable(data,tblforiqcinspection);
			$('#SearchModal').modal('hide');
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	//REQUALIFICATION
	function getItemsRequalification() {
		var url = "{{url('/iqcdbgetitemrequali')}}";
		var token = "{{ Session::token() }}";
		var data = {
			 _token: token,
		};

		$.ajax({
			 url: url,
			 type: "GET",
			 data: data,
		}).done( function(data, textStatus, jqXHR) {
			if (data == null || data == "") {
				$('#er_partcode_rq').html("No Inspections Available");
			} else {
				$('#partcode_rq').select2({
	   				 data: data,
	   				 placeholder: "Select an Item"
	   			 });
			}
		}).fail( function(data, textStatus, jqXHR) {
			 msg("There's some error while processing.",'failed');
		});
	}

	function getAppNo() {
		var url = "{{url('/iqcdbgetappnorequali')}}";
		var token = "{{ Session::token() }}";
		var data = {
			 _token: token,
			 item: $('#partcode_rq').val()
		};

		$.ajax({
			 url: url,
			 type: "GET",
			 data: data,
		}).done( function(data, textStatus, jqXHR) {
			if (data == null || data == "") {
				$('#er_app_no_rq').html("No Available Application Number.");
			} else {
				$('#app_no_rq').select2({
	   				 data: data,
	   				 placeholder: "Select an Item"
	   			 });
			}
		}).fail( function(data, textStatus, jqXHR) {
			 msg("There's some error while processing.",'failed');
		});
	}

	function getDetailsRequalification() {
		var url = "{{url('/iqcdbgetdetailsrequali')}}";
		var token = "{{ Session::token() }}";
		var data = {
			 _token: token,
			 item: $('#partcode_rq').val(),
			 app_no: $('#app_no_rq').val()
		};

		$.ajax({
			 url: url,
			 type: "GET",
			 data: data,
		}).done( function(data, textStatus, jqXHR) {
			var details = data.details;
			$('#partname_rq').val(details.partname);
			$('#supplier_rq').val(details.supplier);
			$('#app_date_rq').val(details.app_date);
			$('#app_time_rq').val(details.app_time);
			$('#lot_qty_rq').val(details.lot_qty);

			$('#lot_no_rq').select2({
				tags: true,
				data: data.lots,
				placeholder: 'Select Lot Number'
			});

			$('#lot_no_rq').select2('val',data.lotval);
		}).fail( function(data, textStatus, jqXHR) {
			 msg("There's some error while processing.",'failed');
		});
	}

	function calculateLotQtyRequalification(lotno) {
		var url = "{{url('/iqccalculatelotqtyrequali')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token:token,
			app_no: $('#app_no_rq').val(),
			item: $('#partcode_rq').val(),
			lot_no: lotno
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done( function(data, textStatus, jqXHR) {
			$('#lot_qty_rq').val(data);
			console.log(data);
		}).fail( function(data, textStatus, jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function getVisualInspectionRequalification() {
		var url = "{{url('/iqcdbvisualinspectionrequali')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token:token,
			app_no: $('#app_no_rq').val(),
			item: $('#partcode_rq').val(),
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done( function(data, textStatus, jqXHR) {
			$.each(data, function(i,x){
				$('#date_ispected_rq').val(x.date_ispected);
				$('#ww_rq').val(x.ww);
				$('#fy_rq').val(x.fy);
				$('#time_ins_from_rq').val(x.time_ins_from);
				$('#time_ins_to_rq').val(x.time_ins_to);
				$('#shift_rq').select2('val',[x.shift]);
				$('#inspector_rq').val(x.inspector);
				$('#submission_rq').select2('val',[x.submission]);
				$('#judgement_rq').val(x.judgement);
				$('#lot_inspected_rq').val(x.lot_inspected);
				$('#lot_accepted_rq').val(x.lot_accepted);
				$('#no_of_defects_rq').val(x.no_of_defects);
				$('#remarks_rq').val(x.remarks);
			});

			if ($('#lot_accepted_rq').val() < 1) {
				$('#no_defects_label_rq').show();
				$('#no_of_defects_rq').show();
				$('#mode_defects_label_rq').show();
				$('#btn_mod_rq').show();
			} else {
				$('#no_defects_label_rq').hide();
				$('#no_of_defects_rq').hide();
				$('#mode_defects_label_rq').hide();
				$('#btn_mod_rq').hide();;
			}
		}).fail( function(data, textStatus, jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function getDropdownsRequali() {
		var url = "{{url('/iqcgetdropdownsrequali')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data,textStatus,jqXHR) {
			$('#shift_rq').select2({
				data: data.shift,
				placeholder: "Select Shift"
			});
			$('#submission_rq').select2({
				data: data.submission,
				placeholder: "Select Submission"
			});
			$('#mod_rq').select2({
				data: data.mod,
				placeholder: "Select Mode of Defects"
			});

			$('#supplier_rq').select2({
				data: data.supplier,
				placeholder: "Select Supplier"
			});
		}).fail(function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function saveRequalification() {
		$('#loading').modal('show');
		if (requiredFields(':input.requiredRequali') == true) {
			var url = "{{url('/iqcsaverequali')}}";
			var token = "{{Session::token()}}";
			var data = {
				_token: token,
				save_status: $('#save_status_rq').val(),
				id: $('#id_rq').val(),
				ctrlno: $('#ctrl_no_rq').val(),
				partcode: $('#partcode_rq').val(),
				partname: $('#partname_rq').val(),
				supplier: $('#supplier_rq').val(),
				app_date: $('#app_date_rq').val(),
				app_time: $('#app_time_rq').val(),
				app_no: $('#app_no_rq').val(),
				lot_no: $('#lot_no_rq').val(),
				lot_qty: $('#lot_qty_rq').val(),
				date_inspected: $('#date_ispected_rq').val(),
				ww: $('#ww_rq').val(),
				fy: $('#fy_rq').val(),
				time_ins_from: $('#time_ins_from_rq').val(),
				time_ins_to: $('#time_ins_to_rq').val(),
				shift: $('#shift_rq').val(),
				inspector: $('#inspector_rq').val(),
				submission: $('#submission_rq').val(),
				judgement: $('#judgement_rq').val(),
				lot_inspected: $('#lot_inspected_rq').val(),
				lot_accepted: $('#lot_accepted_rq').val(),
				no_of_defects: $('#no_of_defects_rq').val(),
				remarks: $('#remarks_rq').val(),
			};

			$.ajax({
				url: url,
				type: "POST",
				dataType: "JSON",
				data: data
			}).done( function(data,textStatus,jqXHR) {
				$('#loading').modal('hide');

				if (data.return_status == 'success') {
					msg(data.msg,'success');
				}
			}).fail( function(data,textStatus,jqXHR) {
				$('#loading').modal('hide');
				msg("There's some error while processing.",'failed');
			});
		} else {
			$('#loading').modal('hide');
			msg("Please fill out all required fields.",'failed');
		}
	}

	function getRequalification(row) {
		var rq_inspection_body = '';
		$('#rq_inspection_body').html('');
		var url = "{{url('/iqcdbgetrequalidata')}}";
		var token = "{{ Session::token() }}";
		var data = {
			 _token: token,
			 row: row
		};

		$.ajax({
			 url: url,
			 type: "GET",
			 data: data,
		}).done( function(data, textStatus, jqXHR) {
			getRequalidataTable(data,rq_inspection_body);
		}).fail( function(data, textStatus, jqXHR) {
			 msg("There's some error while processing.",'failed');
		});
	}

	function getRequalidataTable(data,rq_inspection_body) {
		$.each(data, function(i,x) {
			rq_inspection_body = '<tr>'+
										'<td class="table-checkbox" style="width: 2%">'+
											'<input type="checkbox" class="checkboxes checitemrq" value="'+x.id+'"/>'+
										'</td>'+
										'<td>'+
											'<a href="javascript:;" class="btn btn-primary input-sm btn_editRequali" '+
												'data-ctrl_no="'+x.ctrl_no_rq+'" '+
												'data-partcode="'+x.partcode_rq+'" '+
												'data-partname="'+x.partname_rq+'" '+
												'data-supplier="'+x.supplier_rq+'" '+
												'data-app_date="'+x.app_date_rq+'" '+
												'data-app_time="'+x.app_time_rq+'" '+
												'data-app_no="'+x.app_no_rq+'" '+
												'data-lot_no="'+x.lot_no_rq+'" '+
												'data-lot_qty="'+x.lot_qty_rq+'" '+
												'data-date_ispected="'+x.date_ispected_rq+'" '+
												'data-ww="'+x.ww_rq+'" '+
												'data-fy="'+x.fy_rq+'" '+
												'data-shift="'+x.shift_rq+'" '+
												'data-time_ins_from="'+x.time_ins_from_rq+'" '+
												'data-time_ins_to="'+x.time_ins_to_rq+'" '+
												'data-inspector="'+x.inspector_rq+'" '+
												'data-submission="'+x.submission_rq+'" '+
												'data-judgement="'+x.judgement_rq+'" '+
												'data-lot_inspected="'+x.lot_inspected_rq+'" '+
												'data-lot_accepted="'+x.lot_accepted_rq+'" '+
												'data-no_of_defects="'+x.no_of_defects_rq+'" '+
												'data-remarks="'+x.remarks_rq+'"'+
												'data-id="'+x.id+'">'+
												'<i class="fa fa-edit"></i>'+
											'</a>'+
										'</td>'+
										'<td>'+x.ctrl_no_rq+'</td>'+
										'<td>'+x.partcode_rq+'</td>'+
										'<td>'+x.partname_rq+'</td>'+
										'<td>'+x.lot_no_rq+'</td>'+
										'<td>'+x.app_date_rq+'</td>'+
										'<td>'+x.app_time_rq+'</td>'+
										'<td>'+x.app_no_rq+'</td>'+
									'</tr>';
			$('#rq_inspection_body').append(rq_inspection_body);
		});
	}

	function iqcdbgetmodeofdefectsRequali() {
		$('#tblformodinspection').html('');
		var tblformodinspection = '';
		var url = "{{url('/iqcdbgetmodeofdefectsrequali')}}";
		var token  = "{{Session::token()}}";
		var data = {
			_token: token,
			item: $('#partcode_rq').val(),
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			getModrqlist(data);
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function getModrqlist(data) {
		var cnt = 1;
		var no_of_defectives = 0;
		$.each(data, function(i,x) {
			tblformodrequalification = '<tr>'+
										'<td>'+
											'<input type="checkbox" class="modrq_checkitem checkboxes" value="'+x.id+'">'+
										'</td>'+
										'<td>'+
											'<a href="javascript:;" class="btn blue input-sm modrq_edititem" data-mod="'+x.mod+'" data-qty="'+x.qty+'" data-id="'+x.id+'">'+
												'<i class="fa fa-edit"></i>'+
											'</a>'+
										'</td>'+
										'<td>'+cnt+'</td>'+
										'<td>'+x.mod+'</td>'+
										'<td>'+x.qty+'</td>'+
									'</tr>';
			cnt++;
			no_of_defectives = parseFloat(no_of_defectives) + parseFloat(x.qty);
			$('#tblformodrequalification').append(tblformodrequalification);
		});

		$('#no_of_defects_rq').val(no_of_defectives);
		$('#status_requalification').val('ADD');
	}

	function saveModeOfDefectsRequali() {
		var url = "{{url('/iqcdbsavemodeofdefectsrq')}}";
		var token  = "{{Session::token()}}";
		var data = {
			_token: token,
			item: $('#partcode_rq').val(),
			mod: $('#mod_rq').val(),
			qty: $('#qty_rq').val(),
			status: $('#status_requalification').val(),
			id: $('#id_requalification').val()
		};

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			if (data.return_status == "success") {
				msg(data.msg,'success');
			} else {
				msg(data.msg,'failed');
			}
			iqcdbgetmodeofdefectsRequali();
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	// GROUP BY
	function getGroupbyContents(field,content) {
		var url = "{{url('iqcdbgroupbygetcontent')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token,
			field: $(field).val(),
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data,textStatus,jqXHR) {
			if (data == '' || data == null) {

			} else {
				$(content).select2({
					data:data
				});
			}
		}).fail(function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function groupByTable(field1,content1,field2,content2,field3,content3,gfrom,gto) {
		tblforiqcinspection = '';
		tblforlarlrrdppm = '';
		$('#tblforiqcinspection').html('');
		$('#tblforlarlrrdppm').html('');
		var url = "{{url('/iqcdbgroupbytable')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token,
			field1: field1,
			content1: content1,
			field2: field2,
			content2: content2,
			field3: field3,
			content3: content3,
			from: gfrom,
			to: gto,
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data,textStatus,jqXHR) {
			DPPMtable(data,tblforlarlrrdppm)
		}).fail(function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function getInspectionBydate(gfrom,gto) {
		tblforiqcinspection = '';
		$('#tblforiqcinspection').html('');
		var url = "{{url('/iqcdbinspectionbydate')}}";
		var token = "{{Session::token()}}";
		var data = {
			from: gfrom,
			to: gto,
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data,textStatus,jqXHR) {
			// getIQCInspection("{{url('/iqcdbgetiqcdata')}}");
			getIQCdataTable(data,tblforiqcinspection);
		}).fail(function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function DPPMtable(data,tblforlarlrrdppm) {
		$.each(data, function(i,x) {
			var lar = (((x.lot_qty - x.no_of_defects)/x.lot_qty)*100).toFixed(2);
			// var lar = (lqminusnoddividedbylq * 100).toFixed(2);

			var lrr = ((x.no_of_defects / x.lot_qty)*100).toFixed(2);
			// var lrr = (noddivlq * 100).toFixed(2);
			
			// //getting the dppm value-------
			var noddivss = 0;
			if(x.no_of_defects > 0 && x.sample_size > 0){
				var noddivss = x.no_of_defects/x.sample_size;
			}
			var dppm = (noddivss * 1000000).toFixed(2);

			var reject = x.lot_inspected - x.lot_accepted;
			tblforlarlrrdppm = '<tr>'+
									'<td style="width:11.11%"></td>'+
									'<td style="width:11.11%">'+x.lot_inspected+'</td>'+
		        					'<td style="width:11.11%">'+x.lot_accepted+'</td>'+
		                            '<td style="width:11.11%">'+reject+'</td>'+
		                            '<td style="width:11.11%">'+x.sample_size+'</td>'+
		                            '<td style="width:11.11%">'+x.no_of_defects+'</td>'+
		                            '<td style="width:11.11%">'+lar+'</td>'+
		                            '<td style="width:11.11%">'+lrr+'</td>'+
		                            '<td style="width:11.11%">'+dppm+'</td>'+
								'</tr>';
			$('#tblforlarlrrdppm').append(tblforlarlrrdppm);
		});
	}

	function checkFile(fileName) {
		var ext = fileName.split('.').pop();
		if (ext == 'xls' || ext == 'XLS') {
			return true
		} else {
			return false;
		}
	}

	function deleteInspection() {
		var url = "{{url('/iqcdbdeleteinspection')}}";
		var token  = "{{Session::token()}}";
		var data = {
			id: getAllChecked('.iqc_checkitems'),
			_token: token
		}

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			if (data.return_status == "success") {
				msg(data.msg,'success');
			} else {
				msg(data.msg,'failed');
			}
			getIQCInspection("{{url('/iqcdbgetiqcdata')}}");
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function deleteRequali() {
		var url = "{{url('/iqcdbdeleterequali')}}";
		var token  = "{{Session::token()}}";
		var data = {
			id: getAllChecked('.checitemrq'),
			_token: token
		}

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			if (data.return_status == "success") {
				msg(data.msg,'success');
			} else {
				msg(data.msg,'failed');
			}
			getRequalification(5);
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function deleteModRQ() {
		var url = "{{url('/iqcdbdeletemodeofdefectsrequali')}}";
		var token  = "{{Session::token()}}";
		var data = {
			id: getAllChecked('.modrq_checkitem'),
			_token: token
		}

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			if (data.return_status == "success") {
				msg(data.msg,'success');
			} else {
				msg(data.msg,'failed');
			}
			iqcdbgetmodeofdefectsRequali();
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function deleteModIns() {
		var url = "{{url('/iqcdbdeletemodeofdefects')}}";
		var token  = "{{Session::token()}}";
		var data = {
			id: getAllChecked('.modinspection_checkitem'),
			_token: token
		}

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			if (data.return_status == "success") {
				msg(data.msg,'success');
			} else {
				msg(data.msg,'failed');
			}
			iqcdbgetmodeofdefectsinspection();
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function deleteOnGoing() {
		var url = "{{url('/iqcdbdeleteongoing')}}";
		var token  = "{{Session::token()}}";
		var data = {
			id: getAllChecked('.ongiong_checkitems'),
			_token: token
		}

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: data
		}).done( function(data,textStatus,jqXHR) {
			if (data.return_status == "success") {
				msg(data.msg,'success');
			} else {
				msg(data.msg,'failed');
			}
			getOnGoing();
		}).fail( function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function openModeOfDefects() {
		if ($('#lot_accepted').val() == 0) {
			$('#no_defects_label').show();
			$('#no_of_defects').show();
			$('#mode_defects_label').show();
			$('#btn_mod_ins').show();
			$('#judgement').val('Rejected');
		} else {
			$('#lot_accepted').val(1);
			$('#no_defects_label').hide();
			$('#no_of_defects').hide();
			$('#mode_defects_label').hide();
			$('#btn_mod_ins').hide();
			$('#judgement').val('Accepted');
		}
	}

	function samplingPlan_man() {
		var url = "{{url('/iqcsamplingplan')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token,
			soi: $('#severity_of_inspection_man').val(),
			il: $('#inspection_lvl_man').val(),
			aql: $('#aql_man').val(),
			lot_qty: $('#lot_qty_man').val()
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data, textStatus, jqXHR){
			$('#accept_man').val(data.accept);
			$('#reject_man').val(data.reject);
			$('#sample_size_man').val(data.sample_size);
			$('#date_inspected_man').val(data.date_inspected);
			$('#lot_inspected_man').val(1);
			$('#inspector_man').val(data.inspector);
			$('#ww_man').val(data.workweek);
			getFiscalYear_man();
		}).fail(function(data, textStatus, jqXHR){
			msg("There's some error while processing.",'failed');
		});
	}

	function getDropdowns_man() {
		var url = "{{url('/iqcgetdropdowns')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data,textStatus,jqXHR) {
			$('#type_of_inspection_man').select2({
				data:data.tofinspection,
				placeholder: "Select Type of Inspection"
			});
			$('#severity_of_inspection_man').select2({
				data:data.sofinspection,
				placeholder: "Select Severity of Inspection"
			});
			$('#inspection_lvl_man').select2({
				data:data.inspectionlvl,
				placeholder: "Select Inspection Level"
			});
			$('#aql_man').select2({
				data:data.aql,
				placeholder: "Select AQL"
			});
			$('#shift_man').select2({
				data: data.shift,
				placeholder: "Select Shift"
			});
			$('#submission_man').select2({
				data: data.submission,
				placeholder: "Select Submission"
			});
			$('#submission_man').val('1st');
			$('#submission_man').trigger('change');

			$('#mod_inspection_man').select2({
				data: data.mod,
				placeholder: "Select Mode of Defects"
			});

			$('#supplier_man').select2({
				data: data.supplier,
				placeholder: "Select Supplier"
			});

			$('#classfication_man').select2({
				data: data.classfication,
				placeholder: "Select Classification"
			});

			$('#family_man').select2({
				data: data.family,
				placeholder: "Select Family"
			});
		}).fail(function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

    function getFiscalYear_man() {
        var date = new Date();
        var month = date.getMonth();
        var year = date.getFullYear();

        // if (month < 3) {
        //     year = year - 1;
        // }

        $('#fy_man').val(year);
    }

    function openModeOfDefects_man() {
		if ($('#lot_accepted_man').val() == 0) {
			$('#no_defects_label_man').show();
			$('#no_of_defects_man').show();
			$('#mode_defects_label_man').show();
			$('#btn_mod_ins_man').show();
			$('#judgement_man').val('Rejected');
		} else {
			$('#lot_accepted_man').val(1);
			$('#no_defects_label_man').hide();
			$('#no_of_defects_man').hide();
			$('#mode_defects_label_man').hide();
			$('#btn_mod_ins_man').hide();
			$('#judgement_man').val('Accepted');
		}
	}

    function saveInspection_man() {
		$('#loading').modal('show');

		//if (requiredFields(':input.required') == true) {
			var url = "{{url('/iqcsaveinspection')}}";
			var token = "{{Session::token()}}";
			var data = {
				_token: token,
				save_status: $('#save_status_man').val(),
				id: $('#iqc_result_id_man').val(),
				invoice_no: $('#invoice_no_man').val(),
				partcode: $('#partcode_man').val(),
				partname: $('#partname_man').val(),
				supplier: $('#supplier_man').val(),
				app_date: $('#app_date_man').val(),
				app_time: $('#app_time_man').val(),
				app_no: $('#app_no_man').val(),
				lot_no: $('#lot_no_man').val(),
				lot_qty: $('#lot_qty_man').val(),
				type_of_inspection: $('#type_of_inspection_man').val(),
				severity_of_inspection: $('#severity_of_inspection_man').val(),
				inspection_lvl: $('#inspection_lvl_man').val(),
				aql: $('#aql_man').val(),
				accept: $('#accept_man').val(),
				reject: $('#reject_man').val(),
				date_inspected: $('#date_inspected_man').val(),
				ww: $('#ww_man').val(),
				fy: $('#fy_man').val(),
				time_ins_from: $('#time_ins_from_man').val(),
				time_ins_to: $('#time_ins_to_man').val(),
				shift: $('#shift_man').val(),
				inspector: $('#inspector_man').val(),
				submission: $('#submission_man').val(),
				judgement: $('#judgement_man').val(),
				lot_inspected: $('#lot_inspected_man').val(),
				lot_accepted: $('#lot_accepted_man').val(),
				sample_size: $('#sample_size_man').val(),
				no_of_defects: $('#no_of_defects_man').val(),
				remarks: $('#remarks_man').val(),
				classification: $('#classification_man').val(),
				family: $('#family_man').val(),
			};

			$.ajax({
				url: url,
				type: "POST",
				dataType: "JSON",
				data: data
			}).done( function(data,textStatus,jqXHR) {
				$('#loading').modal('hide');

				if (data.return_status == 'success') {
					msg(data.msg,'success');
					clear();
					// $('#ManualModal').modal('hide');
					getIQCInspection("{{url('/iqcdbgetiqcdata')}}");
					getOnGoing();
				}
			}).fail( function(data,textStatus,jqXHR) {
				$('#loading').modal('hide');
				msg("There's some error while processing.",'failed');
			});
		// } else {
		// 	$('#loading').modal('hide');
		// 	msg("Please fill out all required fields.",'failed');
		// }	
	}

	function getIQCworkWeek() {
		var url = "{{url('/iqcgetworkweek')}}";
		var token = "{{Session::token()}}";
		var data = {
			_token: token
		};

		$.ajax({
			url: url,
			type: "GET",
			data: data
		}).done(function(data,textStatus,jqXHR) {
			$('#ww').val(data.workweek);
		}).fail(function(data,textStatus,jqXHR) {
			msg("There's some error while processing.",'failed');
		});
	}

	function getShift(from,to,el) {
		var data = {
			_token: "{{ Session::token() }}",
			from: from,
			to: to
		};

		$.ajax({
			url: "{{ url('/iqc-get-shift') }}",
			type: 'GET',
			dataType: 'JSON',
			data: data
		}).done( function(data, textStatus,jqXHR) {
			$(el).val([data.shift]);
			console.log(data);
		}).fail( function(data, textStatus,jqXHR) {
			console.log(data);
		});
	}

</script>

<script type="text/javascript">
	var token = "{{ Session::token() }}";
	var GroupByURL = "{{ url('/iqc-groupby-values') }}";
	var GetSingleGroupByURL = "{{ url('/iqc-groupby-dppmgroup1') }}";
    var GetdoubleGroupByURL = "{{ url('/iqc-groupby-dppmgroup2') }}";
    var GettripleGroupByURL = "{{ url('/iqc-groupby-dppmgroup3') }}";
    var GetdoubleGroupByURLdetails = "{{ url('/iqc-groupby-dppmgroup2_Details') }}";
    var GettripleGroupByURLdetails = "{{ url('/iqc-groupby-dppmgroup3_Details') }}";
    var pdfURL = "{{ url('/iqcprintreport?') }}";
    var excelURL = "{{ url('/iqcprintreportexcel')  }}";
</script>

<script src="{{ asset(config('constants.PUBLIC_PATH').'assets/global/scripts/iqc_inspection_groupby.js') }}" type="text/javascript"></script>
@endpush
