@extends('layouts.master')

@section('title')
	QC Database | Pricon Microelectronics, Inc.
@endsection

@section('content')

	<?php $state = ""; $readonly = ""; ?>
	@foreach ($userProgramAccess as $access)
		@if ($access->program_code == Config::get('constants.MODULE_CODE_FGSDB'))  <!-- Please update "2001" depending on the corresponding program_code -->
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
					<div class="portlet-body">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="portlet box grey-gallery" >
                							<div class="portlet-title">
                								<div class="caption">
                									<i class="fa fa-line-chart"></i> FGS
                								</div>
                                                <div class="actions">
                                                    <div class="btn-group">
                                                        <a href="javascript:;" class="btn green" id="btn_add">
                                                            <i class="fa fa-plus"></i> Add New
                                                        </a>
                                                        <a href="javascript:;" class="btn blue" id="btn_groupby">
                                                            <i class="fa fa-group"></i> Group By
                                                        </a>
                                                        <button type="button" onclick="javascript:deleteAllchecked();" class="btn red" id="btn_delete">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                        <a href="javascript:;" class="btn purple" id="btn_search">
                                                            <i class="fa fa-search"></i> Search
                                                        </a>
                                                        <a href="javascript:;" class="btn yellow-gold" id="btn_pdf">
                                                            <i class="fa fa-file-text-o"></i> Print to Pdf
                                                        </a>
                                                        <a href="javascript:;" class="btn green-jungle" id="btn_excel">
                                                            <i class="fa fa-file-text-o"></i> Print to Excel
                                                        </a>
                                                    </div>
                                                </div>
                							</div>
                							<div class="portlet-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table class="table table-hover table-bordered table-striped" id="fgsdatatable">
                                                            <thead>
                                                                <tr>
                                                                	<td width="4.28%" class="table-checkbox">
                                                                        <input type="checkbox" class="group-checkable checkAllitems" />
                                                                    </td>
                                                                    <td width="5.28%"></td>
                                                                    <td width="14.28%">Date Inspection</td>
                                                                    <td width="20.28%">P.O. #</td>
                                                                    <td width="27.28%">Series Name</td>
                                                                    <td width="14.28%">Quantity</td>
                                                                    <td width="14.28%">Total No. of Lots</td>

                                                                </tr>
                                                            </thead>
                                                            <tbody id="tblforfgs">
                                                        
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <input class="form-control input-sm" type="hidden" value="" name="hd_report_status" id="hd_report_status"/>
                                </div>

							</div>

					</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
		</div>
		<!-- END PAGE CONTENT-->
	</div>


	<!-- ADD NEW MODAL -->
	<div id="AddNewModal" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-md gray-gallery">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">OQC FGS</h4>
				</div>
				<form class="form-horizontal">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label col-sm-3">Date</label>
									<div class="col-sm-9">
										<input class="form-control input-sm date-picker" type="text" name="date" id="date"/>										
									</div>
								</div>
								<div class="form-group">
                                    <label class="control-label col-sm-3">P.O. Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="po_no" name="po_no">
                                        <div id="er_po_no"></div>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="control-label col-sm-3">Device Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="device_name" name="device_name" readonly>
                                        <div id="er_device_name"></div>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="control-label col-sm-3">Quantity</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="quantity" name="quantity">
                                        <div id="er_quantity"></div>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="control-label col-sm-3">Total No. of Lots</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="total_lots" name="total_lots">
                                        <input type="hidden" class="form-control input-sm" id="hd_status" name="hd_status">
                                        <input type="hidden" class="form-control input-sm" id="id" name="id">
                                        <div id="er_total_lots"></div>
                                    </div>
                                </div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue btn-sm" id="btn_clear"><i class="fa fa-eraser"></i> Clear</button>
						<button type="button" onclick="javascript:Save();" class="btn green btn-sm" id="btn_save"><i class="fa fa-floppy-o"></i> Save</button>
						<button type="button" data-dismiss="modal" class="btn red btn-sm"><i class="fa fa-times"></i> Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- GROUP BY MODAL -->
    <div id="GroupByModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-md gray-gallery">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Group Items By:</h4>
                </div>
                <form class="form-horizontal">
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-2">Date From</label>
                                <div class="col-sm-10">
                                        <input type="text" class="form-control datepicker input-sm " id="groupby_datefrom" name="groupby_datefrom">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-2">Date To</label>
                                <div class="col-sm-10">
                                        <input type="text" class="form-control datepicker input-sm " id="groupby_dateto" name="groupby_dateto">
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-2">Group #1</label>
                                <div class="col-sm-10">
                                    <select class="form-control input-sm show-tick" name="group1" id="group1">
                                        <option value=""></option>
										<option value="date">Date Inspected</option>
										<option value="po_no">PO Number</option>
										<option value="device_name">Series Name</option>
										<option value="qty">Quantity</option>
										<option value="total_num_of_lots">Total No. of Lots</option>
                                    </select>
                                </div>
                             
                            </div>  
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-2">Group #2</label>
                                <div class="col-sm-10">
                                    <select class="form-control input-sm show-tick" name="group2" id="group2">
                                        <option value=""></option>
										<option value="date">Date Inspected</option>
										<option value="po_no">PO Number</option>
										<option value="device_name">Series Name</option>
										<option value="qty">Quantity</option>
										<option value="total_num_of_lots">Total No. of Lots</option>
                                    </select>
                                </div>
                            </div>  
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-2">Group #3</label>
                                <div class="col-sm-10">
                                    <select class="form-control input-sm show-tick" name="group3" id="group3">
                                        <option value=""></option>
										<option value="date">Date Inspected</option>
										<option value="po_no">PO Number</option>
										<option value="device_name">Series Name</option>
										<option value="qty">Quantity</option>
										<option value="total_num_of_lots">Total No. of Lots</option>
                                    </select>
                                </div>
                            </div>  
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="javascript:groupby();" class="btn btn-success" id="">OK</button>
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


	<!-- SEARCH MODAL -->
	<div id="SearchModal" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog gray-gallery">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Search</h4>
				</div>
				<form class="form-horizontal">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								 <div class="form-group">
                                    <label class="control-label col-sm-3">PO Number</label>
                                    <div class="col-sm-7">
                                        <input class="form-control input-sm" type="text" value="" name="search_pono" id="search_pono"/>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="control-label col-sm-3">From</label>
									<div class="col-sm-7">
										<input class="form-control input-sm date-picker" type="text" value="" name="search_from" id="search_from"/>
										<div id="er_search_from"></div>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-3">To</label>
									<div class="col-sm-7">
										<input class="form-control input-sm date-picker" type="text" value="" name="search_to" id="search_to"/>
										<div id="er_search_to"></div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" onclick="javascript:searchby();" class="btn btn-success" id="">OK</button>
						<button type="button" data-dismiss="modal" class="btn btn-danger" id="btn_search-close">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Empty FIELD SEARCH -->
	<div id="emptyModal" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog gray-gallery">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Warning!</h4>
				</div>
				<form class="form-horizontal">
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<label class="control-label col-sm-10">Please search record/s first before you print reports</label>
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
<script src="{{ asset(config('constants.PUBLIC_PATH').'assets/global/scripts/common.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    var dataColumn = [
        { data: function(data) {
            return '<input type="checkbox" class="input-sm checkboxes" value="'+data.id+'" name="checkitem" id="checkitem"></input>';
        }, name: 'id' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
        { data: function(data) {
            return data.date+'<input type="hidden" id="hd_date_inspected" value="'+data.date+'" name="hd_date_inspected[]">';
        }, name: 'date' },
        { data: function(data) {
            return data.po_no+'<input type="hidden" id="hd_pono" value="'+data.po_no+'" name="hd_pono[]">';
        }, name: 'po_no' },
        { data: function(data) {
            return data.device_name+'<input type="hidden" id="hd_device_name" value="'+data.device_name+'" name="hd_device_name[]">';
        }, name: 'device_name' },
        { data: function(data) {
            var qty = '';
            if(data.qty == null){
                qty = 0;
            }else{
                qty = data.qty;
            }
            return qty+'<input type="hidden" id="hd_qty" value="'+qty+'" name="hd_qty[]">';
        }, name: 'qty' },
        { data: function(data) {
            return data.total_num_of_lots+'<input type="hidden" id="hd_total_lots" value="'+data.total_num_of_lots+'" name="hd_total_lots[]">';
        }, name: 'total_num_of_lots' }
    ];
$(function() {
    loadFGSdata("{{ url('/FGSgetrows') }}?mode=");
    $('#hd_report_status').val("");
    /*$('#fgsdatatable').DataTable();*/
    $('select[name=group1]').select2();
   	$('select[name=group2]').select2();
    $('select[name=group3]').select2();
    $('select[name=group4]').select2();
    $('#date').datepicker();
    $('#groupby_datefrom').datepicker();
    $('#groupby_dateto').datepicker();
    $('#search_from').datepicker();
    $('#search_to').datepicker();
    $('#date').on('change',function(){
          $(this).datepicker('hide');
    });

	$('#groupby_datefrom').on('change',function(){
          $(this).datepicker('hide');
    });

    $('#groupby_dateto').on('change',function(){
          $(this).datepicker('hide');
    });

    $('#search_from').on('change',function(){
          $(this).datepicker('hide');
    });

    $('#search_to').on('change',function(){
          $(this).datepicker('hide');
    });

	$('#btn_add').on('click', function() {
		$('#AddNewModal').modal('show');
		$('#hd_status').val("ADD");
		$('#po_no').val("");
		$('#device_name').val("");
		$('#quantity').val("");
		$('#total_lots').val("");
		$('#er_po_no').html("");
		$('#er_device_name').html("");
		$('#er_quantity').html("");
		$('#er_total_lots	').html("");
	});

	$('#btn_clear').click(function(){
		$('#po_no').val("");
		$('#device_name').val("");
		$('#quantity').val("");
		$('#total_lots').val("");
		$('#hd_status').val("");
	});

	$('#btn_groupby').on('click', function() {
        $('#GroupByModal').modal('show');
        $('#groupby_datefrom').val("");
        $('#groupby_dateto').val("");
        $('#group1').select2('val',"");
        $('#group1content').select2('val',"");

        //to classify group by when reporting----------
        $('#hd_report_status').val("GROUPBY");
        $('#hd_search_from').val("");
        $('#hd_search_to').val("");
        $('#hd_search_pono').val("");
    });

	$('#btn_search').on('click', function() {
        $('#SearchModal').modal('show');
        $('#search_pono').val("");
        $('#search_from').val("");
        $('#search_to').val("");
        $('#er_search_from').html(""); 
        $('#er_search_to').html(""); 

        //to classify group by when reporting----------
        $('#hd_report_status').val("SEARCH");
        $('#hd_search_from').val("");
        $('#hd_search_to').val("");
        $('#hd_groupfield').val("");
        $('#hd_value').val("");
    });

	$('#btn_clear').on('click', function () {
		$('#po_no').val('');
		$('#device_name').val('');
		$('#quantity').val('');
		$('#total_lots').val('');
	});

	$('.checkAllitems').change(function(){
        if($('.checkAllitems').is(':checked')){
            $('.deleteAll-task').removeClass("disabled");
            $('#add').addClass("disabled");
            $('input[name=checkitem]').parents('span').addClass("checked");
            $('input[name=checkitem]').prop('checked',this.checked);
        }else{
            $('input[name=checkitem]').parents('span').removeClass("checked");
            $('input[name=checkitem]').prop('checked',this.checked);
            $('.deleteAll-task').addClass("disabled");
            $('#add').removeClass("disabled");
        }       
    });

    $('.checkboxes').change(function(){
        $('input[name=checkAllitem]').parents('span').removeClass("checked");
        $('input[name=checkAllitem]').prop('checked',false);
        if($('.checkboxes').is(':checked')){
            $('.deleteAll-task').removeClass("disabled");
            $('#add').addClass("disabled");
        }else{
            $('.deleteAll-task').addClass("disabled");
            $('#add').removeClass("disabled");
        }  
    });

    $('#tblforfgs').on('click','.edit-task', function() {
       	$('#AddNewModal').modal('show');
       	$('#hd_status').val("EDIT");
        var edittext = $(this).val().split('|');
        var editid = edittext[0];
        var date = edittext[2];
        var pono = edittext[1];
        var device = edittext[3];
       	var qty = edittext[4];
        var tlots = edittext[5];
        var dbcon = edittext[6];
        $('#date').val(date);
		$('#po_no').val(pono);
		$('#device_name').val(device);
		$('#quantity').val(qty);
		$('#total_lots').val(tlots);
		$('#dbcon').val("{{Auth::user()->productline}}");
		$('#id').val(editid);  
    });
        /*    if (window.event.keyCode == 13 ) return false;*/
    $('#po_no').keyup(function(){ 
        $('#er_po_no').html(""); 
    });

    $('#device_name').keyup(function(){
        $('#er_device_name').html(""); 
    });
    $('#quantity').keyup(function(){
        $('#er_quantity').html(""); 
    });
    $('#total_lots').keyup(function(){
        $('#er_total_lots').html(""); 
    });
    $('#search_from').click(function(){
        $('#er_search_from').html(""); 
    });
    $('#search_to').click(function(){
        $('#er_search_to').html(""); 
    });

    $('#po_no').on('change',function(){
        var pono = $(this).val();
        $.ajax({
            url:"{{ url('/getfgsYPICSrecords') }}",
            method:'get',
            data:{
                pono:pono
            },
        }).done(function(data, textStatus, jqXHR){
            console.log(data); 
            $('#device_name').val(data[0]['DEVNAME']);
            if(pono == ""){
	    		$('#device_name').val("");
	    	}
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown+'|'+textStatus);
        });
    });

    $('#btn_pdf').on('click', function() {
        var searchpono = $('#search_pono').val();
        var datefrom = $('#search_from').val();
        var dateto = $('#search_to').val();
        var status = $('#hd_report_status').val();

        var tableData = {
            date_inspected: $('input[name^="hd_date_inspected[]"]').map(function(){return $(this).val();}).get(),
            pono: $('input[name^="hd_pono[]"]').map(function(){return $(this).val();}).get(),
            device_name: $('input[name^="hd_device_name[]"]').map(function(){return $(this).val();}).get(),
            qty: $('input[name^="hd_qty[]"]').map(function(){return $(this).val();}).get(),
            total_lots: $('input[name^="hd_total_lots[]"]').map(function(){return $(this).val();}).get(),
            status:status,
            searchpono:searchpono,
            datefrom:datefrom,
            dateto:dateto
        };
        var url = "{{ url('/fgsprintreport?data=') }}" + encodeURIComponent(JSON.stringify(tableData));
        window.location.href= url;
    });

    $('#btn_excel').on('click', function() {
        var searchpono = $('#search_pono').val();
        var datefrom = $('#search_from').val();
        var dateto = $('#search_to').val();
        var status = $('#hd_report_status').val();

        var tableData = {
            date_inspected: $('input[name^="hd_date_inspected[]"]').map(function(){return $(this).val();}).get(),
            pono: $('input[name^="hd_pono[]"]').map(function(){return $(this).val();}).get(),
            device_name: $('input[name^="hd_device_name[]"]').map(function(){return $(this).val();}).get(),
            qty: $('input[name^="hd_qty[]"]').map(function(){return $(this).val();}).get(),
            total_lots: $('input[name^="hd_total_lots[]"]').map(function(){return $(this).val();}).get(),
            status:status,
            searchpono:searchpono,
            datefrom:datefrom,
            dateto:dateto
        };
       
        var url = "{{ url('/fgsprintreportexcel?data=')  }}" + encodeURIComponent(JSON.stringify(tableData));
        window.location.href= url;
    });

});//--------------------------------------------end of script------------------------------

function Save(){
	var date = $('#date').val();
	var pono = $('#po_no').val();
	var device = $('#device_name').val();
	var quantity = $('#quantity').val();
	var tlots = $('#total_lots').val();
	var status = $('#hd_status').val();
	var dbcon = "{{Auth::user()->productline}}";
	var id = $('#id').val();
	var myData = {date:date,pono:pono,device:device,quantity:quantity,tlots:tlots,status:status,dbcon:dbcon,id:id};
	if(pono == ""){     
        $('#er_po_no').html("PO Number field is empty"); 
        $('#er_po_no').css('color', 'red');       
        return false;  
    }
    if(device == ""){     
        $('#er_device_name').html("Device Name field is empty"); 
        $('#er_device_name').css('color', 'red');       
        return false;  
    }
    if(quantity == ""){     
        $('#er_quantity').html("Quantity field is empty"); 
        $('#er_quantity').css('color', 'red');       
        return false;  
    }
    if(tlots == ""){     
        $('#er_total_lots').html("Total Lots field is empty"); 
        $('#er_total_lots').css('color', 'red');       
        return false;  
    }

	$.post("{{ url('/fgsSave') }}",
	{
		_token:$('meta[name=csrf-token]').attr('content'),
		data:myData
	}).done(function(data, textStatus, jqXHR){
		/*console.log(data);*/
		window.location.href = "{{ url('/fgs') }}";
	}).fail(function(jqXHR, textStatus, errorThrown){
		console.log(errorThrown+'|'+textStatus);
	});

}

function deleteAllchecked(){
    var tray = [];
    $('.checkboxes:checked').each(function(){
        tray.push($(this).val());
    });

    var traycount = tray.length;
    var myData = {tray:tray,traycount:traycount};
    $.ajax({
            url:"{{ url('/fgsDelete') }}",
            method:'get',
            data:myData
                 
    }).done(function(data, textStatus, jqXHR){
        window.location.href="{{ url('/fgs') }}";
       /* alert(data);*/
    }).fail(function(jqXHR, textStatus,errorThrown){
        console.log(errorThrown+'|'+textStatus);
    });
}

function groupby(){
	var datefrom= $('#groupby_datefrom').val();
    var dateto = $('#groupby_dateto').val();
    var g1 = $('select[name=group1]').val();
    var g2 = $('select[name=group2]').val();
    var g3 = $('select[name=group3]').val();
    var urls = "{{ url('/FGSgetrows') }}"+"?_token="+"{{Session::token()}}"+"&&mode=group"+"&&g1="+g1+"&&g2="+g2+"&&g3="+g3+"&&datefrom="+datefrom+"&&dateto="+dateto;

    loadFGSdata(urls);
}

function searchby(){
    var datefrom = $('#search_from').val();
    var dateto = $('#search_to').val();
    var pono = $('#search_pono').val();
    var urls = "{{ url('/FGSgetrows') }}"+"?_token="+"{{Session::token()}}"+"&&mode=search"+"&&datefrom="+datefrom+"&&dateto="+dateto+"&&pono"+pono;

    loadFGSdata(urls);
}

function loadFGSdata(urls){
    // $.get(urls,
    // {
    //     _token:$('meta[name=csrf-token]').attr('content')
    // }).done(function(data, textStatus, jqXHR){
    //     console.log(data);
    //     var cnt = 0;
    //     FGSgetDataTable(data);
    // });
    getDatatable('fgsdatatable',urls,dataColumn,[],0);
}

function FGSgetDataTable(data) {
    var cnt = 0;
    $.each(data,function(i,val){
        cnt++;
        var report_status = $('#hd_report_status').val();
        var qty = '';
        if(val.qty == null){
            qty = 0;
        }else{
            qty = val.qty;
        }
 
        var tblrow = '<tr>'+
                '<td width="4.28%">'+
                    '<input type="checkbox" class="input-sm checkboxes" value="'+val.id+'" name="checkitem" id="checkitem"></input> '+
                '</td>'+                        
                '<td width="5.28%">'+           
                    '<button type="button" name="edit-task" class="btn btn-sm btn-primary edit-task" value="'+val.id+'|'+val.po_no+'|'+val.date+'|'+val.device_name+'|'+val.qty+'|'+val.total_num_of_lots+'|'+val.dbcon+'">'+
                        '   <i class="fa fa-edit"></i> '+
                    '</button>'+
                '</td>'+
                '<td width="14.28%">'+val.date+'<input type="hidden" id="hd_date_inspected" value="'+val.date+'" name="hd_date_inspected[]"></td>'+
                '<td width="20.28%">'+val.po_no+'<input type="hidden" id="hd_pono" value="'+val.po_no+'" name="hd_pono[]"></td>'+
                '<td width="27.28%">'+val.device_name+'<input type="hidden" id="hd_device_name" value="'+val.device_name+'" name="hd_device_name[]"></td>'+
                '<td width="14.28%">'+qty+'<input type="hidden" id="hd_qty" value="'+qty+'" name="hd_qty[]"></td>'+
                '<td width="14.28%">'+val.total_num_of_lots+'<input type="hidden" id="hd_total_lots" value="'+val.total_num_of_lots+'" name="hd_total_lots[]"></td>'+
            '</tr>';    
        $('#tblforfgs').append(tblrow);
    });
}

</script>
@endpush
