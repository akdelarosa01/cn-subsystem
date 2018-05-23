$( document ).ready(function(e) {
    loadMassAlert();
	// setInterval(function(){
	// 	loadMassAlert() // this will run after every 5 seconds
	// }, 5000);

	focusbarcode();
	$('#hd_status').val("focus");
	$('#tblSummary').on('click', '.viewdetails', function() {
		var transno = $(this).attr('data-transno');
		var status = $(this).attr('data-status');
		checkIfNotClose(transno);
		viewDetails(e,transno,status);
	});

	$('#tblViewDetail').on('change', $('.smdetailschk'), function() {
		isCheck($('.smdetailschk'));
	});

	$('.details_check').on('change', function(e) {
		$('input:checkbox.smdetailschk').not(this).prop('checked', this.checked);
		isCheck($('.smdetailschk'));
	});

    $('#btn_material_request').on('click', function() {
        window.location.href=whsMaterialRequestPDF+"?_token="+token+"&&issuanceno="+$('#issuancenowhs').val();
    });

	$('#btn_search').click(function(){
		$('#hd_status').val("search");
	});


	$('#btn_addToIssuance').on('click', function() {
		clearIssuance();
		var detid = [];
		var id = [];
		$(".smdetailschk:checked").each(function() {
			detid.push($(this).attr('data-detailid'));
			id.push($(this).attr('data-id'));
		});

		var whstransno = $('#viewdetailchk'+detid).attr('data-whstransno');
		var transno = $('#viewdetailchk'+detid).attr('data-transno');
		var status = $('#viewdetailchk'+detid).attr('data-stats');
		var req_status = $('#req_status').val();
		var total_req_qty = $('#total_req_qty').val();
		var cd = getDate();
		var tblIssuance = "";

		$('#issuancenowhs').val(whstransno);
		$('#reqno').val(transno);
		$('#statuswhs').val(status);
		$('#createdbywhs').val(cu);
		$('#createddatewhs').val(cd);
		$('#updatedbywhs').val(cu);
		$('#updateddatewhs').val(cd);
		$('#totreqqty').val(total_req_qty);

		var issueDetID = 1;
		if (req_status == 'Serving') {
			var url = whsServingURL;
	
			var data = {
				_token: token,
				whstransno: whstransno,
				reqtransno: transno,
			};

			$.ajax({
				url: url,
				method: 'GET',
				data:  data,
			}).done( function(data, textStatus, jqXHR) {
				$.each(data, function(i,x) {
					 tblIssuance = '<tr>'+
									'<td width="4.5%;">'+
										// '<a href="javascript:;" class="btn btn-circle green btn-sm addissuanceDetails"'+
										// 'id="addissuance" data-transno="'+transno+'" data-whstransno="'+whstransno+'" data-issDetailid="'+issueDetID+'" '+
										// 'data-code="'+x.item+'" data-name="'+x.item_desc+'" data-requestqty="'+x.request_qty+'" data-stats="'+x.status+'"'+
										// 'data-servedqty="'+x.issued_qty_t+'" data-location="'+x.location+'" data-issuedkit="'+x.issued_qty_o+'">'+
										// 	'<i class="fa fa-plus"></i>'+
										// '</a>'+
									'</td>'+
									'<td width="12.5%;">'+issueDetID+
									'</td>'+
									'<td width="12.5%;">'+x.item+
									'</td>'+
									'<td width="19.8%;">'+x.item_desc+
									'</td>'+
									'<td width="12.4%;">'+x.issued_qty_o+
									'</td>'+
									'<td width="12.5%;" id="issqtytd'+issueDetID+'">'+x.issued_qty_t+
									'</td>'+
									'<td width="12.5%;" id="lottd'+issueDetID+'">'+x.lot_no+
									'</td>'+
									'<td width="12.5%;">'+x.location+
									'</td>'+
								'</tr>';
					$('#tblIssuance').append(tblIssuance);
					issueDetID++;
				});

				$.each(detid, function(i,ids) {
					//var issueDetID = $('#viewdetailchk'+ids).attr('data-issDetailid');
					var itemnodet = $('#viewdetailchk'+ids).attr('data-code');
					var itemdesc = $('#viewdetailchk'+ids).attr('data-name');
					var issuedkit = $('#viewdetailchk'+ids).attr('data-issuedkit');
					var locdet = $('#viewdetailchk'+ids).attr('data-location');
					var requestqty = $('#viewdetailchk'+ids).attr('data-requestqty');
					var servedqty = $('#viewdetailchk'+ids).attr('data-servedqty');

					tblIssuance = '<tr>'+
										'<td width="4.5%;" class="text-center">'+
											'<a href="javascript:;" class="btn btn-circle green btn-sm addissuanceDetails"'+
											'id="addissuance" data-transno="'+transno+'" data-whstransno="'+whstransno+'" data-issDetailid="'+issueDetID+'" '+
											'data-code="'+itemnodet+'" data-name="'+itemdesc+'" data-requestqty="'+requestqty+'" data-stats="'+status+'"'+
											'data-servedqty="'+servedqty+'" data-location="'+locdet+'" data-issuedkit="'+issuedkit+'">'+
												'<i class="fa fa-plus"></i>'+
											'</a>'+
										'</td>'+
										'<td width="12.5%;">'+issueDetID+
											'<input type="hidden" name="detid[]" value="'+issueDetID+'">'+
										'</td>'+
										'<td width="12.5%;">'+itemnodet+
											'<input type="hidden" name="itemiss[]" value="'+itemnodet+'">'+
										'</td>'+
										'<td width="19.8%;">'+itemdesc+
											'<input type="hidden" name="itemdesciss[]" value="'+itemdesc+'">'+
										'</td>'+
										'<td width="12.4%;">'+issuedkit+
											'<input type="hidden" name="issuedkit[]" value="'+issuedkit+'">'+
										'</td>'+
										'<td width="12.5%;" id="issqtytd'+issueDetID+'">'+
											'<input type="hidden" name="qtyiss[]" id="issqty'+issueDetID+'">'+
										'</td>'+
										'<td width="12.5%;" id="lottd'+issueDetID+'">'+
											'<input type="hidden" name="lotiss[]" id="lot'+issueDetID+'">'+
										'</td>'+
										'<td width="12.5%;">'+locdet+
											'<input type="hidden" name="lociss[]" value="'+locdet+'">'+
											'<input type="hidden" name="requestqty[]" value="'+requestqty+'"/>'+
											'<input type="hidden" name="sqty[]" value="'+servedqty+'"/>'+
											'<input type="hidden" name="id[]" value="'+id[i]+'"/>'+
											'<input type="hidden" name="newentry[]" value="'+id[i]+'"/>'+
										'</td>'+
									'</tr>';
					$('#tblIssuance').append(tblIssuance);
					issueDetID++;

				});
			}).fail( function() {
				msg("There was an error occurred.",'error');
			});
		} else {
			$.each(detid, function(i,ids) {
				//var issueDetID = $('#viewdetailchk'+ids).attr('data-issDetailid');
				var itemnodet = $('#viewdetailchk'+ids).attr('data-code');
				var itemdesc = $('#viewdetailchk'+ids).attr('data-name');
				var issuedkit = $('#viewdetailchk'+ids).attr('data-issuedkit');
				var locdet = $('#viewdetailchk'+ids).attr('data-location');
				var requestqty = $('#viewdetailchk'+ids).attr('data-requestqty');
				var servedqty = $('#viewdetailchk'+ids).attr('data-servedqty');

				tblIssuance = '<tr>'+
									'<td width="4.5%;" class="text-center">'+
										'<a href="javascript:;" class="btn btn-circle green btn-sm addissuanceDetails"'+
										'id="addissuance" data-transno="'+transno+'" data-whstransno="'+whstransno+'" data-issDetailid="'+issueDetID+'" '+
										'data-code="'+itemnodet+'" data-name="'+itemdesc+'" data-requestqty="'+requestqty+'" data-stats="'+status+'"'+
										'data-servedqty="'+servedqty+'" data-location="'+locdet+'" data-issuedkit="'+issuedkit+'">'+
											'<i class="fa fa-plus"></i>'+
										'</a>'+
									'</td>'+
									'<td width="12.5%;">'+issueDetID+
										'<input type="hidden" name="detid[]" value="'+issueDetID+'">'+
									'</td>'+
									'<td width="12.5%;">'+itemnodet+
										'<input type="hidden" name="itemiss[]" value="'+itemnodet+'">'+
									'</td>'+
									'<td width="19.8%;">'+itemdesc+
										'<input type="hidden" name="itemdesciss[]" value="'+itemdesc+'">'+
									'</td>'+
									'<td width="12.4%;">'+issuedkit+
										'<input type="hidden" name="issuedkit[]" value="'+issuedkit+'">'+
									'</td>'+
									'<td width="12.5%;" id="issqtytd'+issueDetID+'">'+
										'<input type="hidden" name="qtyiss[]" id="issqty'+issueDetID+'">'+
									'</td>'+
									'<td width="12.5%;" id="lottd'+issueDetID+'">'+
										'<input type="hidden" name="lotiss[]" id="lot'+issueDetID+'">'+
									'</td>'+
									'<td width="12.5%;">'+locdet+
										'<input type="hidden" name="lociss[]" value="'+locdet+'">'+
										'<input type="hidden" name="requestqty[]" value="'+requestqty+'"/>'+
										'<input type="hidden" name="sqty[]" value="'+servedqty+'"/>'+
										'<input type="hidden" name="id[]" value="'+id[i]+'"/>'+
										'<input type="hidden" name="newentry[]" value="'+id[i]+'"/>'+
									'</td>'+
								'</tr>';
				$('#tblIssuance').append(tblIssuance);
				issueDetID++;

			});
		}

		addState()
		switchTabs();
	});

	$('#tblIssuance').on('click', '.addissuanceDetails', function(e) {
		//declaration of variables

		var issDetailid = $(this).attr('data-issDetailid');
		var detailid = $(this).attr('data-detailid');
		var code = $(this).attr('data-code');
		var name = $(this).attr('data-name');
		var issuedkit = $(this).attr('data-issuedkit');
		var requestqty = $(this).attr('data-requestqty');
		var servedqty = $(this).attr('data-servedqty');
		var location = $(this).attr('data-location');
		var trnsno = $(this).attr('data-transno');
		var stats = $(this).attr('data-stats');

		var issuedqty = parseFloat(requestqty) - parseFloat(servedqty);

		loadfifo(code);

		//assign values
		$('#issueDetID').val(issDetailid);
		$('#issueDetID1').val(issDetailid);

		$('#reqdetid').val(detailid);
		$('#reqdetid1').val(detailid);

		$('#itemnodet').val(code);
		$('#itemnodet1').val(code);

		$('#itemdesc').val(name);
		$('#itemdesc1').val(name);

		$('#reqqtydet').val(requestqty);
		$('#reqqtydet1').val(requestqty);

		$('#servedqtydet').val(servedqty);
		$('#servedqtydet1').val(servedqty);

		$('#locdet').val(location);
		$('#locdet1').val(location);

		$('#issqtydet').val(issuedqty);
		$('#isskitqty').val(issuedkit);

		$('#rs_transno').val(trnsno);
		$('#rs_status').val(stats);

		$('#addDetModal').modal('show');
	});

	$('#tblfifoAdd').on('click', '.btn_select_lot', function() {
		var lotno = $(this).attr('data-lotno');
		var qty = $(this).attr('data-qty');
        var fifoid = $(this).attr('data-id');
		$('#lotnodet').val(lotno);
		$('#issqtydet').val(qty);
        $('#fifoid').val(fifoid);
		$('#hd_barcode').blur();
	});

	// $('#lotnodet').on('change',function(){
	// 	var lotno = $('.btn_select_lot').attr('data-lotno');
	// 	var qty = $('.btn_select_lot').attr('data-qty');
	// 	$('#lotnodet').val(lotno);
	// 	$('#issqtydet').val(qty);
	// 	$('#hd_barcode').blur();
	// });

	$('#tblIssuance').on('click', '#addissuance', function() {

		$('#hd_status').val("add");
		$('#lotnodet').val('');
		$('#issqtydet').val('');

		$('#addDetModal').on('shown.bs.modal',function(){
			$('#lotnodet').focus();
		});
	});

	$('#addDet_close').click(function(){
		focusbarcode();
		$('#hd_status').val("focus");

	});

	$('#search_close').click(function(){
		$('#hd_status').val("focus");
		focusbarcode();
	});


	//barcode scanning fire--------------
	$('#hd_barcode').change(function(){
		$('#addDetModal').modal('show');
		$('#hd_status').val("add");

		if($('#hd_status').val() == "add"){
		  	$('body').click(function(){
		  		$('#hd_barcode').blur();
		  	});
    	}

		$('#addDetModal').on('shown.bs.modal',function(){
			$('#lotnodet').focus();
		});

			var barcode = $('#hd_barcode').val();
			var issuanceno = $('#issuancenowhs').val();
			loadfifo(barcode);
			var formUrl = getMatBcodeURL;
			var formData = {
				_token:token,
				barcode:barcode,
			};

		//
		$.ajax({
			url:formUrl,
			method:'GET',
			data:formData,
			dataType: "JSON"
		}).done(function(data, textstatus, jqXHR){
			console.log(data);
				$('#issueDetID').val(data.id);
				$('#itemnodet').val(data.item);
				$('#itemdesc').val(data.item_desc);
				$('#reqqtydet').val(data.request_qty);
				$('#servedqtydet').val(data.issued_qty_t);
				$('#locdet').val(data.location);
				$('#issqtydet').val("");
				$('#lotnodet').val("");

		}).fail(function(jqXHR,textStatus,errorThrown){
			msg("There was and error occurred.",'error');
		});
	});

	$('#btn_saveAddDetail').on('click', function() {
		var issueDetID = $('#issueDetID').val();
		var itemnodet = $('#itemnodet').val();
		var itemdesc = $('#itemdesc').val();
		var reqdetid = $('#reqdetid').val();
		var reqqtydet = $('#reqqtydet').val();
		var servedqtydet = $('#servedqtydet').val();
		var issqtydet = $('#issqtydet').val();
		var lotnodet = $('#lotnodet').val();
		var locdet = $('#locdet').val();
		var issuedkit = $('#isskitqty').val();
		var transno = $('#rs_transno').val();
		var status = $('#rs_status').val();
        var fifoid = $('#fifoid').val();
		var cd = getDate();
		var tblIssuance = "";

        if (parseFloat(issqtydet) > parseFloat(reqqtydet)) {
            msg("Issuance quantity must not be more than the request quantity",'failed');
        } else {
            $('#issqtytd'+issueDetID).html(issqtydet+'<input type="hidden" name="qtyiss[]" id="issqty'+issueDetID+'">');
            $('#issqty'+issueDetID).val(issqtydet);
            $('#lottd'+issueDetID).html(lotnodet+'<input type="hidden" name="lotiss[]" id="lot'+issueDetID+'">'+
                                        '<input type="hidden" name="fifoidiss[]" id="fifoidiss'+issueDetID+'">');
            $('#lot'+issueDetID).val(lotnodet);
            $('#fifoidiss'+issueDetID).val(fifoid);

            $('#addDetModal').modal('hide');
        }
	});

	$('#btn_save').on('click', function() {
		$('#loading').modal('show');
		var url = saveWhsIssuanceURL;

		var data = {
			_token: token,
			issuancenowhs: $('input[name=issuancenowhs]').val(),
			reqno: $('input[name=reqno]').val(),
			statuswhs: $('input[name=statuswhs]').val(),
			createdbywhs: $('input[name=createdbywhs]').val(),
			createddatewhs: $('input[name=createddatewhs]').val(),
			updatedbywhs: $('input[name=updatedbywhs]').val(),
			updateddatewhs: $('input[name=updateddatewhs]').val(),
			totreqqty: $('input[name=totreqqty]').val(),
			detid: $('input[name="detid[]"]').map(function(){return $(this).val();}).get(),
			itemiss: $('input[name="itemiss[]"]').map(function(){return $(this).val();}).get(),
			itemdesciss: $('input[name="itemdesciss[]"]').map(function(){return $(this).val();}).get(),
			issuedkit: $('input[name="issuedkit[]"]').map(function(){return $(this).val();}).get(),
			qtyiss: $('input[name="qtyiss[]"]').map(function(){return $(this).val();}).get(),
			lotiss: $('input[name="lotiss[]"]').map(function(){return $(this).val();}).get(),
			lociss: $('input[name="lociss[]"]').map(function(){return $(this).val();}).get(),
			requestqty: $('input[name="requestqty[]"]').map(function(){return $(this).val();}).get(),
			sqty: $('input[name="sqty[]"]').map(function(){return $(this).val();}).get(),
			id: $('input[name="id[]"]').map(function(){return $(this).val();}).get(),
			newentry: $('input[name="newentry[]"]').map(function(){return $(this).val();}).get(),
            fifoid: $('input[name="fifoidiss[]"]').map(function(){return $(this).val();}).get(),
            db_id: $('input[name="db_id[]"]').map(function(){return $(this).val();}).get(),
            pmr_detail_id: $('input[name="pmr_detail_id[]"]').map(function(){return $(this).val();}).get(),
		};


		$.ajax({
			url: url,
			type: "POST",
			data: data,
		}).done( function(data, textStatus, jqXHR) {
			$('#loading').modal('hide');
			$('input[name=issuancenowhs]').val('');

			loadLatestIssuance('',$('input[name=issuancenowhs]').val());
			loadMassAlert();
			$('#tblViewDetail').html('');

			msg(data.msg,'success');
			$('#summarytabtoggle').attr('data-toggle','tab');
		}).fail( function(data, textStatus, jqXHR) {
			$('#loading').modal('hide');
			msg("There was an error occured.",'error');
		});
	});

	$('#btn_edit').on('click', function() {
		editState();
	});

	$('#btn_cancel').on('click', function() {
		var url = whsIssuanceCancel;

		var data = {
			_token: token,
			issuancenowhs: $('input[name=issuancenowhs]').val(),
			req_no: $('input[name=reqno]').val()
		};

		$.ajax({
			url: url,
			type: "POST",
			data: data,
		}).done( function(data, textStatus, jqXHR) {
			msg(data.msg,'success');
		}).fail( function(data, textStatus, jqXHR) {
			msg("There was an error occured.",'error');
		});
	});

	$('#btn_discard').on('click', function() {
		window.location.href= whsMateIssuanceURL;
	});

	$('#summarytab').on('click', function() {
		if ($('#summarytabtoggle').attr('data-toggle') != '') {
			$(this).addClass('active');
			$('#requestsummary').addClass('active');
			$('#issuancetab').removeClass('active');
			$('#issuance').removeClass('active');
		}
	});

	$('#issuancetab').on('click', function() {
		$(this).addClass('active');
		$('#issuance').addClass('active');
		$('#summarytab').removeClass('active');
		$('#requestsummary').removeClass('active');
		$('#hd_barcode').focus();
		loadLatestIssuance();
	});

	$('#btn_search').on('click', function() {
		$('#searchModal').modal('show');
	});

	$('#btn_report_excel').on('click',function(){
		var issuanceno = $('#issuancenowhs').val();
		window.location.href = whsExcelReport +"?issuanceno="+ issuanceno;
	});

	$('#srch_tbl_body').on('click','.btn_search_edit', function(){
		loadLatestIssuance('',$(this).attr('data-issuanceno'));
	});
});

function loadMassAlert() {
	$('#tblSummary').dataTable().fnClearTable();
    $('#tblSummary').dataTable().fnDestroy();
    $('#tblSummary').DataTable({
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: getmassalertURL,
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'transno', name: 'transno', orderable: false },
            { data: 'created_at', name: 'created_at', orderable: false },
            { data: 'pono', name: 'pono', orderable: false },
            { data: 'destination', name: 'destination', orderable: false },
            { data: 'line', name: 'line', orderable: false },
            { data: 'status', name: 'status', orderable: false },
            { data: 'requestedby', name: 'requestedby', orderable: false },
            { data: 'lastservedby', name: 'lastservedby', orderable: false },
            { data: 'lastserveddate', name: 'lastserveddate', orderable: false }
        ]
    });
}

// function checkRequest() {
// 	$.ajax({
// 		url: checkReuestURL,
// 		type: 'GET',
// 		dataType: 'JSON',
// 		data: {_token: token},
// 	}).done(function(data,textStatus,jqXHR) {
// 		console.log("success");
// 	}).fail(function(data,textStatus,jqXHR) {
// 		console.log("error");
// 	});
// }

function viewDetails(e,transno,status) {
	var formURL = viewdetailsURL;
	var formData = {
		transno : transno,
		_token : token,
	};
	var tblViewDetail = "";
	//e.preventDefault(); //Prevent Default action.
	$.ajax({
		url: formURL,
		method: 'POST',
		data:  formData,
	}).done( function(data, textStatus, jqXHR) {
		$('#tblViewDetail').html('');
		var cnt = 1;
		var locked = '';
		var total_req_qty = 0;

		$.each(data,function(i,details) {
			var checkbox = '';
			if (details.requestqty > details.servedqty) {
				checkbox = '<input type="checkbox" class="checkboxes smdetailschk" value="1" id="viewdetailchk'+details.detailid+'" data-id="'+details.id+'" data-transno="'+details.transno+'"'+
							' data-issDetailid="'+cnt+'" data-detailid="'+details.detailid+'"'+ 'data-whstransno="'+details.whstransno+'"'+ 'data-classification="'+details.classification+'"'+
							'data-code="'+details.code+'" data-name="'+details.name+'" data-requestqty="'+details.requestqty+'" data-stats="'+status+'"'+
							'data-servedqty="'+details.servedqty+'" data-location="'+details.location+'" data-issuedkit="'+details.issuedqty+'" '+locked+'/>';
			}
			tblViewDetail = '<tr>'+
								'<td style="width:5.1%;" class="text-center">'+checkbox+
									'<input type="hidden" name="det-issDetailid[]" id="det-issDetailid'+cnt+'"/>'+
								'</td>'+
								'<td style="width:11.1%;">'+details.detailid+
									'<input type="hidden" name="det-detailid[]" id="det-detailid'+cnt+'"/>'+
								'</td>'+
								'<td style="width:11.1%;">'+details.code+
									'<input type="hidden" name="det-code[]" id="det-code'+cnt+'"/>'+
								'</td>'+
								'<td style="width:17.1%;">'+details.name+
									'<input type="hidden" name="det-name[]" id="det-name'+cnt+'"/>'+
								'</td>'+
								'<td style="width:11.1%;">'+details.classification+
									'<input type="hidden" name="det-class[]" id="det-class'+cnt+'"/>'+
								'</td>'+
								'<td style="width:11.1%;">'+details.issuedqty+
									'<input type="hidden" name="det-issuedqty[]" id="det-issuedqty'+cnt+'"/>'+
								'</td>'+
								'<td style="width:11.1%;">'+details.requestqty+
									'<input type="hidden" name="det-requestqty[]" id="det-requestqty'+cnt+'"/>'+
								'</td>'+
								'<td style="width:11.1%;">'+details.servedqty+
									'<input type="hidden" name="det-servedqty[]" id="det-servedqty'+cnt+'"/>'+
									'<input type="hidden" name="det-lot[]" id="det-lot'+cnt+'"/>'+
									'<input type="hidden" name="det-location[]" id="det-location'+cnt+'"/>'+
								'</td>'+
								'<td style="width:11.1%;">'+details.created_at+'</td>'+
							'</tr>';
			total_req_qty = parseFloat(total_req_qty) + parseFloat(details.requestqty);
			$('#tblViewDetail').append(tblViewDetail);
			cnt++;
			$('#total_req_qty').val(total_req_qty);
		});
	}).fail(function(data, jqXHR, textStatus, errorThrown) {
		msg("There was an error occured.",'error');
	});
}

function switchTabs() {
	$('#summarytab').removeClass('active');
	$('#requestsummary').removeClass('active');
	//
	$('#issuancetab').addClass('active');
	$('#issuance').addClass('active');
	$('#summarytabtoggle').attr('data-toggle','');
}

function isCheck(element) {
	if(element.is(':checked')) {
		$('#btn_addToIssuance').removeAttr('disabled');
	} else {
		$('#btn_addToIssuance').attr('disabled','true');
	}
}

function checkIfNotClose(transno) {
	var formURL = wbswhscheckifnotcloseURL;
	var formData = {
		transno : transno,
		_token : token,
	};
	var tblViewDetail = "";
	//e.preventDefault(); //Prevent Default action.
	$.ajax({
		url: formURL,
		method: 'GET',
		data:  formData,
	}).done( function(data, textStatus, jqXHR) {
		$('#req_status').val(data);
	}).fail( function(data, textStatus, jqXHR) {
		msg("There was an error occured.",'error');
	});
}

function viewstate() {
	$('#btn_save').hide();
	$('#btn_edit').show();
	$('#btn_cancel').show();
	$('#btn_discard').hide();
	$('#btn_search').show();
	$('#btn_print').show();

	$('#btn_min').prop('disabled',false);
	$('#btn_prv').prop('disabled',false);
	$('#btn_nxt').prop('disabled',false);
	$('#btn_max').prop('disabled',false);

    $('.addissuanceDetails').prop('disabled', true);

    if ($('#statuswhs').val() == 'Closed' || $('#statuswhs').val() == 'Cancelled') {
    	$('#btn_save').hide();
        $('#btn_edit').hide();
        $('#btn_cancel').hide();
        $('#btn_discard').hide();
        $('#btn_search').show();
        $('#btn_print').show();
    }
}

function editState() {
	$('#btn_save').show();
	$('#btn_edit').hide();
	$('#btn_cancel').hide();
	$('#btn_discard').show();
	$('#btn_search').hide();
	$('#btn_print').hide();

	$('#btn_min').prop('disabled',true);
	$('#btn_prv').prop('disabled',true);
	$('#btn_nxt').prop('disabled',true);
	$('#btn_max').prop('disabled',true);

    $('.addissuanceDetails').prop('disabled', false);
}

function addState() {
	$('#btn_save').show();
	$('#btn_edit').hide();
	$('#btn_cancel').hide();
	$('#btn_discard').show();
	$('#btn_search').hide();
	$('#btn_print').hide();

	$('#btn_min').prop('disabled',true);
	$('#btn_prv').prop('disabled',true);
	$('#btn_nxt').prop('disabled',true);
	$('#btn_max').prop('disabled',true);

    $('.addissuanceDetails').prop('disabled', false);
}

function clearIssuance() {
	$('#tblIssuance tr').remove();
	$('.clear').val('');
}

function getDate(){
	var d = new Date();

	var month = d.getMonth()+1;
	var day = d.getDate();

	var output = d.getFullYear() + '/' +
		((''+month).length<2 ? '0' : '') + month + '/' +
		((''+day).length<2 ? '0' : '') + day;

	return output;
}

function loadfifo(code) {
	var dataColumn = [
		{ data: 'action', name: 'action', orderable: false, searchable: false },
		{ data: 'item', name: 'item', orderable: false },
		{ data: 'item_desc', name: 'item_desc', orderable: false },
		{ data: 'qty', name: 'qty', orderable: false },
		{ data: 'lot_no', name: 'lot_no', orderable: false },
		{ data: 'received_date', name: 'received_date', orderable: false }
	];

	getDatatable('tblfifoAdd',wbswhsissuancefifotblURL+"?code="+code,dataColumn,[],0);
}

// function loadbarcodefifo(issuance,code,table) {
// 	table.html('');
//  	var url = wbswhsissuancefifotblbcURL;

//  	var tblfifoAdd = '';
//  	var data = {
//  		_token: token,
//  		code  : code,
//  		issuance : issuance
//  	};

//  	$.ajax({
//  		url: url,
//         type: "GET",
//         data: data,
//  	}).done(function(data) {
//  		if(data == "norecord"){
//  			msg("No record found.",'failed');
//  		}else{
//  			$.each(data, function(i, x) {
//      			tblfifoAdd = '<tr>'+
//      							'<td style="width: 9.66%">'+
//      								'<a href="javascript:;" class="btn btn-primary btn_select_lot input-sm" data-id="'+x.id+'" data-lotno="'+x.lot_no+'" data-qty="'+x.qty+'">'+
//      									'<i class="fa fa-pencil"></i>'+
//      								'</a>'+
//      							'</td>'+
//                                 '<td style="width: 16.66%">'+x.item+'</td>'+
//                                 '<td style="width: 24.66%">'+x.item_desc+'</td>'+
//                                 '<td style="width: 15.66%">'+x.qty+'</td>'+
//                                 '<td style="width: 16.66%">'+x.lot_no+'</td>'+
//                                 '<td style="width: 16.66%">'+x.created_at+'</td>'+
//                             '</tr>';

//         		table.append(tblfifoAdd);
//      		});
//  		}

//  	}).fail(function(data) {
//  		msg("There was an error occured.",'error');
//  	});
// }

function navigate(to) {
	loadLatestIssuance(to,$('#issuancenowhs').val())
}

function loadLatestIssuance(to,issuanceno) {
	var url = whslatestissuanceURL;
	var data = {
		_token: token,
		issuanceno: issuanceno,
		to: to
	};

	$.ajax({
		url: url,
		type: "GET",
		data: data,
	}).done( function(data, textStatus, jqXHR) {
		$('#tblIssuance').html('');
		// var obj = JSON.parse(data);
		// console.log(obj);
		if (data.status == 'success') {
			var iss = data.issuance;
			var details = data.details;
			issuanceInfo(iss);
			issuanceDetails(details);
		} else {
			msg(data.msg,data.status);
		}
	}).fail( function(data, textStatus, jqXHR) {
		msg("There was an error occurred.",'error');
	});
}

function issuanceInfo(iss) {
	$('input[name=issuancenowhs]').val(iss.issuance_no);
	$('input[name=reqno]').val(iss.request_no);
	$('input[name=statuswhs]').val(iss.status);
	$('input[name=createdbywhs]').val(iss.create_user);
	$('input[name=createddatewhs]').val(iss.created_at);
	$('input[name=totreqqty]').val(iss.total_req_qty);
	$('input[name=updatedbywhs]').val(iss.update_user);
	$('input[name=updateddatewhs]').val(iss.updated_at);
	getBalance(iss.issuance_no);
}

function issuanceDetails(details) {
	$.each(details, function(i,x) {
		tblIssuance = '<tr>'+
							'<td width="4.5%;">'+
								'<a href="javascript:;" class="btn btn-circle green btn-sm addissuanceDetails"'+
								'id="addissuance" data-transno="'+x.request_no+'" data-issDetailid="'+x.detail_id+'" '+
								'data-code="'+x.item+'" data-name="'+x.item_desc+'" data-requestqty="'+x.request_qty+'" data-stats="'+x.status+'"'+
								'data-servedqty="'+x.issued_qty_t+'" data-location="'+x.location+'" data-issuedkit="'+x.issued_qty_o+'" data-lotiss="'+x.lot_no+'">'+
									'<i class="fa fa-plus"></i>'+
								'</a>'+
                                '<input type="hidden" name="db_id[]" value="'+x.id+'">'+
							'</td>'+
							'<td width="12.5%;">'+x.detail_id+
								'<input type="hidden" name="detid[]" value="'+x.detail_id+'">'+
							'</td>'+
							'<td width="12.5%;">'+x.item+
								'<input type="hidden" name="itemiss[]" value="'+x.item+'">'+
							'</td>'+
							'<td width="19.8%;">'+x.item_desc+
								'<input type="hidden" name="itemdesciss[]" value="'+x.item_desc+'">'+
							'</td>'+
							'<td width="12.4%;">'+x.issued_qty_o+
								'<input type="hidden" name="issuedkit[]" value="'+x.issued_qty_o+'">'+
							'</td>'+
							'<td width="12.5%;" id="issqtytd'+x.detail_id+'">'+x.issued_qty_t+
                                '<input type="hidden" name="qtyiss[]" id="issqty'+x.detail_id+'" value="'+x.issued_qty_t+'">'+
							'</td>'+
							'<td width="12.5%;" id="lottd'+x.detail_id+'">'+x.lot_no+
                                '<input type="hidden" name="lotiss[]" id="lot'+x.detail_id+'" value="'+x.lot_no+'">'+
                                '<input type="hidden" name="fifoidiss[]">'+
							'</td>'+
							'<td width="12.5%;">'+x.location+
								'<input type="hidden" name="lociss[]" value="'+x.location+'">'+
								'<input type="hidden" name="requestqty[]" value="'+x.request_qty+'"/>'+
								'<input type="hidden" name="sqty[]" value="'+x.issued_qty_t+'"/>'+
                                '<input type="hidden" name="pmr_detail_id[]" value="'+x.pmr_detail_id+'"/>'+
							'</td>'+
						'</tr>';
		$('#tblIssuance').append(tblIssuance);
	});
	viewstate();
}

function filterData(action) {
	$('#srch_tbl_body').html('');
	if (action == 'SRCH') {
		var url = whsSearchURL;
    
     	var srch_tbl_body = '';
     	var condition_arr = {
     		srch_issuanceno: $('#srch_issuanceno').val(),
			srch_reqno: $('#srch_reqno').val(),
			srch_serving: $('#srch_serving').val(),
			srch_closed: $('#srch_close').val(),
			srch_cancelled: $('#srch_cancelled').val(),
            srch_from: $('#srch_from').val(),
            srch_to: $('#srch_to').val(),
     	}
     	var data = {
     		_token: token,
     		condition_arr: condition_arr
     	};

     	$.ajax({
     		url: url,
            type: "POST",
            data: data,
            dataType: 'JSON'
     	}).done(function(data) {
     		console.log(data);
     		$.each(data, function(i, x) {
     			srch_tbl_body = '<tr>'+
     									'<td style="width: 8.5%">'+
                                           	'<button type="button" class="btn blue input-sm btn_search_edit" data-id="'+x.id+'" data-issuanceno="'+x.issuance_no+'">'+
                                              	'<i class="fa fa-edit"></i>'+
                                           	'</button>'+
                                      	'</td>'+
										'<td style="width: 14.5%">'+x.issuance_no+'</td>'+
										'<td style="width: 14.5%">'+x.request_no+'</td>'+
										'<td style="width: 12.5%">'+x.status+'</td>'+
										'<td style="width: 12.5%">'+x.create_user+'</td>'+
										'<td style="width: 12.5%">'+x.created_at+'</td>'+
										'<td style="width: 12.5%">'+x.update_user+'</td>'+
										'<td style="width: 12.5%">'+x.updated_at+'</td>'+
									'</tr>';

        		$('#srch_tbl_body').append(srch_tbl_body);
     		});
     	}).fail(function(data) {
     		msg("There was an error occurred.",'error');;
     	});
	} else{

	}
}

function getBalance(issuanceno) {
	$.ajax({
		url: getTotalBalanceURL,
		type: 'GET',
		dataType: 'JSON',
		data: {issuanceno: issuanceno,_token: token},
	}).done(function(data,textStatus,jqXHR) {
		$('#totbalqty').val(data);
	}).fail(function(data,textStatus,jqXHR) {
		msg('There was an aerror getting total balance.','error');
	});	
}

function focusbarcode(){
	$('body').click(function(){
	  	$('#hd_barcode').focus();
	});
}