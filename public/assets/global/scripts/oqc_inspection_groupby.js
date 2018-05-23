$( function() {
    $('#field1').on('change',function(){
        GroupByValues($(this).val(),$('#content1'));
    });

    $('#field2').on('change',function(){
        GroupByValues($(this).val(),$('#content2'));
    });

    $('#field3').on('change',function(){
        GroupByValues($(this).val(),$('#content3'));
    });

    $('#frm_DPPM').on('submit', function(e) {
        $('#main_pane').hide();
        $('#group_by_pane').show();
        e.preventDefault();
        openloading();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'JSON',
            data: $(this).serialize()
        }).done(function(data,xhr,textStatus) {
            console.log(data);
            // closeloading();
            calculateLARDPPM(data);
        }).fail(function(data,xhr,textStatus) {
            msg("There was an error while calculating",'error');
        }).always(function() {
            closeloading();
        });
    });

    $('.view_inspection').live('click', function(e) {
        $('#inspection_id').val($(this).attr('data-id'));
        $('#assembly_line').val($(this).attr('data-assembly_line'));
        $('#lot_no').val($(this).attr('data-lot_no'));
        $('#app_date').val($(this).attr('data-app_date'));
        $('#app_time').val($(this).attr('data-app_time'));
        $('#prod_category').val($(this).attr('data-prod_category'));
        $('#po_no').val($(this).attr('data-po_no'));
        $('#series_name').val($(this).attr('data-device_name'));
        $('#customer').val($(this).attr('data-customer'));
        $('#po_qty').val($(this).attr('data-po_qty'));
        $('#family').val($(this).attr('data-family'));
        $('#type_of_inspection').val($(this).attr('data-type_of_inspection'));
        $('#severity_of_inspection').val($(this).attr('data-severity_of_inspection'));
        $('#inspection_lvl').val($(this).attr('data-inspection_lvl'));
        $('#aql').val($(this).attr('data-aql'));
        $('#accept').val($(this).attr('data-accept'));
        $('#reject').val($(this).attr('data-reject'));
        $('#date_inspected').val($(this).attr('data-date_inspected'));
        $('#ww').val($(this).attr('data-ww'));
        $('#fy').val($(this).attr('data-fy'));
        $('#time_ins_from').val($(this).attr('data-time_ins_from'));
        $('#time_ins_to').val($(this).attr('data-time_ins_to'));
        $('#shift').val($(this).attr('data-shift'));
        $('#inspector').val($(this).attr('data-inspector'));
        $('#submission').val($(this).attr('data-submission'));
        $('#coc_req').val($(this).attr('data-coc_req'));
        $('#judgement').val($(this).attr('data-judgement'));
        $('#lot_qty').val($(this).attr('data-lot_qty'));
        $('#sample_size').val($(this).attr('data-sample_size'));
        $('#lot_inspected').val($(this).attr('data-lot_inspected'));
        $('#lot_accepted').val($(this).attr('data-lot_accepted'));
        $('#no_of_defects').val($(this).attr('data-num_of_defects'));
        $('#remarks').val($(this).attr('data-remarks'));
        $('#inspection_save_status').val('EDIT');

        getNumOfDefectives($(this).attr('data-id'));

        if ($(this).attr('data-type') == 'PROBE PIN') {
            $('#is_probe').prop('checked', true);
        }

        checkAuhtor($(this).attr('data-inspector'));

        $('#inspection_modal').modal('show');
    });

    $('#btn_close_groupby').live('click', function() {
        $('#main_pane').show();
        $('#group_by_pane').hide();
    });

    $('#btn_clear_grpby').on('click', function() {
        clearGrpByFields();
    });

    $('#btn_pdf_groupby').live('click', function() {
        window.location.href=PDFGroupByReportURL;
    });
    $('#btn_excel_groupby').live('click', function() {
        window.location.href=ExcelGroupByReportURL;
    });
});


function GroupBy() {
	$('#groupby_modal').modal('show');
}

function GroupByValues(field,element) {
	element.html('<option value=""></option>');
	var data = {
		_token: token,
		field: field
	}
	$.ajax({
		url: GroupByURL,
		type: 'GET',
		dataType: 'JSON',
		data: data,
	}).done(function(data,xhr,textStatus) {
		$.each(data, function(i, x) {
			element.append('<option value="'+x.field+'">'+x.field+'</option>');
		});
	}).fail(function(data,xhr,textStatus) {
		msg("There was an error while processing the values.",'error');
	}).always(function() {
		console.log("complete");
	});
}

function calculateLARDPPM(data) {
    var grp1 = '';
    var grp1_count = 2;
    var grp2 = '';
    var grp2_count = 2;
    var grp3 = '';
    var grp3_count = 2;
    var counter1 = 0;
    var node_child_count = 1;
    var node_parent_count = 1;
    var nxt_node = 1;
    var details = '';
    $('#group_by_pane').html('<div class="btn-group pull-right">'+
                                '<button class="btn btn-danger btn-sm" id="btn_close_groupby">'+
                                    '<i class="fa fa-times"></i> Close'+
                                '</button>'+
                                '<button class="btn btn-info btn-sm" id="btn_pdf_groupby">'+
                                    '<i class="fa fa-file-pdf-o"></i> PDF'+
                                '</button>'+
                                '<button class="btn btn-success btn-sm" id="btn_excel_groupby">'+
                                    '<i class="fa fa-file-excel-o"></i> Excel'+
                                '</button></div><br><br>');
    var details_body = '';
    

    $.each(data, function(i, x) {
        if (i === 'node1' && x.length > 0) {

            $.each(x, function(ii,xx) {
                var panelcolor = 'panel-info';

                if (parseInt(xx.DPPM) > 0) {
                    panelcolor = 'panel-danger';
                }

                grp1 = '';
                grp1 += '<div class="panel-group accordion scrollable" id="grp'+node_parent_count.toString()+'">';
                grp1 += '<div class="panel '+panelcolor+'">';
                grp1 += '<div class="panel-heading">';
                grp1 += '<h4 class="panel-title">';
                grp1 += '<a class="accordion-toggle" data-toggle="collapse" data-parent="#grp'+node_parent_count.toString()+'" href="#grp_val'+node_parent_count.toString()+'">';
                grp1 += jsUcfirst(xx.group)+': '+xx.group_val;
                grp1 += ' | LAR = '+xx.LAR+' ('+xx.no_of_accepted+' / '+xx.no_of_lots_inspected+')';
                grp1 += ' | DPPM = '+xx.DPPM+' ('+xx.no_of_defects+' / '+xx.sample_size+')';
                grp1 += '</a>';
                grp1 += '</h4>';
                grp1 += '</div>';
                grp1 += '<div id="grp_val'+node_parent_count.toString()+'" class="panel-collapse collapse">';
                grp1 += '<div class="panel-body table-responsive" id="child'+nxt_node.toString()+'">';

                if (xx.details.length > 0) {
                    details = '';
                    details_body = '';
                    details += '<table style="font-size:9px" class="table table-condensed table-bordered">';
                    details += '<thead>';
                    details += '<tr>';
                    details += '<td></td>';
                    details += '<td></td>';
                    details += '<td><strong>PO</strong></td>';
                    details += '<td><strong>Device Name</strong></td>';
                    details += '<td><strong>Customer</strong></td>';
                    details += '<td><strong>PO Qty</strong></td>';
                    details += '<td><strong>Family</strong></td>';
                    details += '<td><strong>Assembly Line</strong></td>';
                    details += '<td><strong>Lot No</strong></td>';
                    details += '<td><strong>App. Date</strong></td>';
                    details += '<td><strong>App. Time</strong></td>';
                    details += '<td><strong>Category</strong></td>';
                    details += '<td><strong>Type of Inspection</strong></td>';
                    details += '<td><strong>Severity of Inspection</strong></td>';
                    details += '<td><strong>Inspection Level</strong></td>';
                    details += '<td><strong>AQL</strong></td>';
                    details += '<td><strong>Accept</strong></td>';
                    details += '<td><strong>Reject</strong></td>';
                    details += '<td><strong>Date inspected</strong></td>';
                    details += '<td><strong>WW</strong></td>';
                    details += '<td><strong>FY</strong></td>';
                    details += '<td><strong>Time Inspected</strong></td>';
                    details += '<td><strong>Shift</strong></td>';
                    details += '<td><strong>Inspector</strong></td>';
                    details += '<td><strong>Submission</strong></td>';
                    details += '<td><strong>COC Requirement</strong></td>';
                    details += '<td><strong>Judgement</strong></td>';
                    details += '<td><strong>Lot Qty</strong></td>';
                    details += '<td><strong>Sample Size</strong></td>';
                    details += '<td><strong>Lot Inspected</strong></td>';
                    details += '<td><strong>Lot Accepted</strong></td>';
                    details += '<td><strong>No. of Defects</strong></td>';
                    details += '<td><strong>Remarks</strong></td>';
                    details += '<td><strong>Type</strong></td>';
                    details += '</tr>';
                    details += '</thead>';
                    details += '<tbody id="details_tbody">';

                    var cnt = 1;

                    $.each(xx.details, function(iii,xxx) {
                        
                        details_body += '<tr>';
                        details_body += '<td>'+cnt+'</td>';
                        details_body += '<td><button class="btn btn-sm view_inspection blue" data-id="'+xxx.id+'"'+ 
                                                'data-assembly_line="'+xxx.assembly_line+'"'+
                                                'data-lot_no="'+xxx.lot_no+'"'+
                                                'data-app_date="'+xxx.app_date+'"'+
                                                'data-app_time="'+xxx.app_time+'"'+
                                                'data-prod_category="'+xxx.prod_category+'"'+
                                                'data-po_no="'+xxx.po_no+'"'+
                                                'data-device_name="'+xxx.device_name+'"'+
                                                'data-customer="'+xxx.customer+'"'+
                                                'data-po_qty="'+xxx.po_qty+'"'+
                                                'data-family="'+xxx.family+'"'+
                                                'data-type_of_inspection="'+xxx.type_of_inspection+'"'+
                                                'data-severity_of_inspection="'+xxx.severity_of_inspection+'"'+
                                                'data-inspection_lvl="'+xxx.inspection_lvl+'"'+
                                                'data-aql="'+xxx.aql+'"'+
                                                'data-accept="'+xxx.accept+'"'+
                                                'data-reject="'+xxx.reject+'"'+
                                                'data-date_inspected="'+xxx.date_inspected+'"'+
                                                'data-ww="'+xxx.ww+'"'+
                                                'data-fy="'+xxx.fy+'"'+
                                                'data-time_ins_from="'+xxx.time_ins_from+'"'+
                                                'data-time_ins_to="'+xxx.time_ins_to+'"'+
                                                'data-shift="'+xxx.shift+'"'+
                                                'data-inspector="'+xxx.inspector+'"'+
                                                'data-submission="'+xxx.submission+'"'+
                                                'data-coc_req="'+xxx.coc_req+'"'+
                                                'data-judgement="'+xxx.judgement+'"'+
                                                'data-lot_qty="'+xxx.lot_qty+'"'+
                                                'data-sample_size="'+xxx.sample_size+'"'+
                                                'data-lot_inspected="'+xxx.lot_inspected+'"'+
                                                'data-lot_accepted="'+xxx.lot_accepted+'"'+ 
                                                'data-num_of_defects="'+xxx.num_of_defects+'"'+
                                                'data-remarks="'+xxx.remarks+'"><i class="fa fa-edit"></i>'+
                                            '</button>'+
                                        '</td>';
                        details_body += '<td>'+xxx.po_no+'</td>';
                        details_body += '<td>'+xxx.device_name+'</td>';
                        details_body += '<td>'+xxx.customer+'</td>';
                        details_body += '<td>'+xxx.po_qty+'</td>';
                        details_body += '<td>'+xxx.family+'</td>';
                        details_body += '<td>'+xxx.assembly_line+'</td>';
                        details_body += '<td>'+xxx.lot_no+'</td>';
                        details_body += '<td>'+xxx.app_date+'</td>';
                        details_body += '<td>'+xxx.app_time+'</td>';
                        details_body += '<td>'+xxx.prod_category+'</td>';
                        details_body += '<td>'+xxx.type_of_inspection+'</td>';
                        details_body += '<td>'+xxx.severity_of_inspection+'</td>';
                        details_body += '<td>'+xxx.inspection_lvl+'</td>';
                        details_body += '<td>'+xxx.aql+'</td>';
                        details_body += '<td>'+xxx.accept+'</td>';
                        details_body += '<td>'+xxx.reject+'</td>';
                        details_body += '<td>'+xxx.date_inspected+'</td>';
                        details_body += '<td>'+xxx.ww+'</td>';
                        details_body += '<td>'+xxx.fy+'</td>';
                        details_body += '<td>'+xxx.time_ins_from+'-'+xxx.time_ins_to+'</td>';
                        details_body += '<td>'+xxx.shift+'</td>';
                        details_body += '<td>'+xxx.inspector+'</td>';
                        details_body += '<td>'+xxx.submission+'</td>';
                        details_body += '<td>'+xxx.coc_req+'</td>';
                        details_body += '<td>'+xxx.judgement+'</td>';
                        details_body += '<td>'+xxx.lot_qty+'</td>';
                        details_body += '<td>'+xxx.sample_size+'</td>';
                        details_body += '<td>'+xxx.lot_inspected+'</td>';
                        details_body += '<td>'+xxx.lot_accepted+'</td>';
                        details_body += '<td>'+xxx.num_of_defects+'</td>';
                        details_body += '<td>'+xxx.remarks+'</td>';
                        details_body += '<td>'+xxx.type+'</td>';
                        details_body += '</tr>';
                        cnt++;
                    });
                    
                    details += details_body;

                    details += '</tbody>';
                    details += '</table>';
                    //$('#child'+node_child_count.toString()).append(details);
                    nxt_node++;
                }

                grp1 += details;
                                    
                grp1 += '</div>';
                grp1 += '</div>';
                grp1 += '</div>';
                grp1 += '</div>';


                $('#group_by_pane').append(grp1);
                node_parent_count++;
                node_child_count++;
            });
        }

        if (i === 'node2' && x.length > 0) {
            console.log(x[counter1]);
            
            $.each(x, function(ii,xx) {
                var panelcolor1 = 'panel-primary';
                if (parseInt(xx.DPPM) > 0) {
                    panelcolor1 = 'panel-danger';
                }

                grp2 = '';
                grp2 += '<div class="panel-group accordion scrollable" id="grp'+node_parent_count.toString()+'">';
                grp2 += '<div class="panel '+panelcolor1+'">';
                grp2 += '<div class="panel-heading">';
                grp2 += '<h4 class="panel-title">';
                grp2 += '<a class="accordion-toggle" data-toggle="collapse" data-parent="#grp'+node_parent_count.toString()+'" href="#grp_val'+node_parent_count.toString()+'">';
                grp2 += jsUcfirst(xx.group)+': '+xx.group_val;
                grp2 += ' | LAR = '+xx.LAR+' ('+xx.no_of_accepted+' / '+xx.no_of_lots_inspected+')';
                grp2 += ' | DPPM = '+xx.DPPM+' ('+xx.no_of_defects+' / '+xx.sample_size+')';
                grp2 += '</a>';
                grp2 += '</h4>';
                grp2 += '</div>';
                grp2 += '<div id="grp_val'+node_parent_count.toString()+'" class="panel-collapse collapse">';
                grp2 += '<div class="panel-body table-responsive" style="height:200px" id="child'+node_child_count.toString()+'">';

                if (xx.details.length > 0) {
                    details = '';
                    details_body = '';
                    details += '<table style="font-size:9px" class="table table-condensed table-bordered">';
                    details += '<thead>';
                    details += '<tr>';
                    details += '<td></td>';
                    details += '<td></td>';
                    details += '<td><strong>PO</strong></td>';
                    details += '<td><strong>Device Name</strong></td>';
                    details += '<td><strong>Customer</strong></td>';
                    details += '<td><strong>PO Qty</strong></td>';
                    details += '<td><strong>Family</strong></td>';
                    details += '<td><strong>Assembly Line</strong></td>';
                    details += '<td><strong>Lot No</strong></td>';
                    details += '<td><strong>App. Date</strong></td>';
                    details += '<td><strong>App. Time</strong></td>';
                    details += '<td><strong>Category</strong></td>';
                    details += '<td><strong>Type of Inspection</strong></td>';
                    details += '<td><strong>Severity of Inspection</strong></td>';
                    details += '<td><strong>Inspection Level</strong></td>';
                    details += '<td><strong>AQL</strong></td>';
                    details += '<td><strong>Accept</strong></td>';
                    details += '<td><strong>Reject</strong></td>';
                    details += '<td><strong>Date inspected</strong></td>';
                    details += '<td><strong>WW</strong></td>';
                    details += '<td><strong>FY</strong></td>';
                    details += '<td><strong>Time Inspected</strong></td>';
                    details += '<td><strong>Shift</strong></td>';
                    details += '<td><strong>Inspector</strong></td>';
                    details += '<td><strong>Submission</strong></td>';
                    details += '<td><strong>COC Requirement</strong></td>';
                    details += '<td><strong>Judgement</strong></td>';
                    details += '<td><strong>Lot Qty</strong></td>';
                    details += '<td><strong>Sample Size</strong></td>';
                    details += '<td><strong>Lot Inspected</strong></td>';
                    details += '<td><strong>Lot Accepted</strong></td>';
                    details += '<td><strong>No. of Defects</strong></td>';
                    details += '<td><strong>Remarks</strong></td>';
                    details += '<td><strong>Type</strong></td>';
                    details += '</tr>';
                    details += '</thead>';
                    details += '<tbody id="details_tbody">';

                    var cnt = 1;

                    $.each(xx.details, function(iii,xxx) {
                        
                        details_body += '<tr>';
                        details_body += '<td>'+cnt+'</td>';
                        details_body += '<td><button class="btn btn-sm view_inspection blue" data-id="'+xxx.id+'"'+ 
                                                'data-assembly_line="'+xxx.assembly_line+'"'+
                                                'data-lot_no="'+xxx.lot_no+'"'+
                                                'data-app_date="'+xxx.app_date+'"'+
                                                'data-app_time="'+xxx.app_time+'"'+
                                                'data-prod_category="'+xxx.prod_category+'"'+
                                                'data-po_no="'+xxx.po_no+'"'+
                                                'data-device_name="'+xxx.device_name+'"'+
                                                'data-customer="'+xxx.customer+'"'+
                                                'data-po_qty="'+xxx.po_qty+'"'+
                                                'data-family="'+xxx.family+'"'+
                                                'data-type_of_inspection="'+xxx.type_of_inspection+'"'+
                                                'data-severity_of_inspection="'+xxx.severity_of_inspection+'"'+
                                                'data-inspection_lvl="'+xxx.inspection_lvl+'"'+
                                                'data-aql="'+xxx.aql+'"'+
                                                'data-accept="'+xxx.accept+'"'+
                                                'data-reject="'+xxx.reject+'"'+
                                                'data-date_inspected="'+xxx.date_inspected+'"'+
                                                'data-ww="'+xxx.ww+'"'+
                                                'data-fy="'+xxx.fy+'"'+
                                                'data-time_ins_from="'+xxx.time_ins_from+'"'+
                                                'data-time_ins_to="'+xxx.time_ins_to+'"'+
                                                'data-shift="'+xxx.shift+'"'+
                                                'data-inspector="'+xxx.inspector+'"'+
                                                'data-submission="'+xxx.submission+'"'+
                                                'data-coc_req="'+xxx.coc_req+'"'+
                                                'data-judgement="'+xxx.judgement+'"'+
                                                'data-lot_qty="'+xxx.lot_qty+'"'+
                                                'data-sample_size="'+xxx.sample_size+'"'+
                                                'data-lot_inspected="'+xxx.lot_inspected+'"'+
                                                'data-lot_accepted="'+xxx.lot_accepted+'"'+ 
                                                'data-num_of_defects="'+xxx.num_of_defects+'"'+
                                                'data-remarks="'+xxx.remarks+'"><i class="fa fa-edit"></i>'+
                                            '</button>'+
                                        '</td>';
                        details_body += '<td>'+xxx.po_no+'</td>';
                        details_body += '<td>'+xxx.device_name+'</td>';
                        details_body += '<td>'+xxx.customer+'</td>';
                        details_body += '<td>'+xxx.po_qty+'</td>';
                        details_body += '<td>'+xxx.family+'</td>';
                        details_body += '<td>'+xxx.assembly_line+'</td>';
                        details_body += '<td>'+xxx.lot_no+'</td>';
                        details_body += '<td>'+xxx.app_date+'</td>';
                        details_body += '<td>'+xxx.app_time+'</td>';
                        details_body += '<td>'+xxx.prod_category+'</td>';
                        details_body += '<td>'+xxx.type_of_inspection+'</td>';
                        details_body += '<td>'+xxx.severity_of_inspection+'</td>';
                        details_body += '<td>'+xxx.inspection_lvl+'</td>';
                        details_body += '<td>'+xxx.aql+'</td>';
                        details_body += '<td>'+xxx.accept+'</td>';
                        details_body += '<td>'+xxx.reject+'</td>';
                        details_body += '<td>'+xxx.date_inspected+'</td>';
                        details_body += '<td>'+xxx.ww+'</td>';
                        details_body += '<td>'+xxx.fy+'</td>';
                        details_body += '<td>'+xxx.time_ins_from+'-'+xxx.time_ins_to+'</td>';
                        details_body += '<td>'+xxx.shift+'</td>';
                        details_body += '<td>'+xxx.inspector+'</td>';
                        details_body += '<td>'+xxx.submission+'</td>';
                        details_body += '<td>'+xxx.coc_req+'</td>';
                        details_body += '<td>'+xxx.judgement+'</td>';
                        details_body += '<td>'+xxx.lot_qty+'</td>';
                        details_body += '<td>'+xxx.sample_size+'</td>';
                        details_body += '<td>'+xxx.lot_inspected+'</td>';
                        details_body += '<td>'+xxx.lot_accepted+'</td>';
                        details_body += '<td>'+xxx.num_of_defects+'</td>';
                        details_body += '<td>'+xxx.remarks+'</td>';
                        details_body += '<td>'+xxx.type+'</td>';
                        details_body += '</tr>';
                        cnt++;
                    });

                    details += details_body;

                    details += '</tbody>';
                    details += '</table>';
                    //$('#child'+node_child_count.toString()).append(details);
                }

                grp2 += details;
                                    
                grp2 += '</div>';
                grp2 += '</div>';
                grp2 += '</div>';
                grp2 += '</div>';


                $('#child'+nxt_node).append(grp2);
                node_parent_count++;
                node_child_count++;
                panelcolor1 = '';
            });
            nxt_node++;
        }

        if (i === 'node3' && x.length > 0) {
            console.log(x[counter1]);
            
            $.each(x, function(ii,xx) {
                var panelcolor = 'panel-success';

                if (parseInt(xx.DPPM) > 0) {
                    panelcolor = 'panel-danger';
                }

                grp3 = '';
                grp3 += '<div class="panel-group accordion scrollable" id="grp'+node_parent_count.toString()+'">';
                grp3 += '<div class="panel '+panelcolor+'">';
                grp3 += '<div class="panel-heading">';
                grp3 += '<h4 class="panel-title">';
                grp3 += '<a class="accordion-toggle" data-toggle="collapse" data-parent="#grp'+node_parent_count.toString()+'" href="#grp_val'+node_parent_count.toString()+'">';
                grp3 += jsUcfirst(xx.group)+': '+xx.group_val;
                grp3 += ' | LAR = '+xx.LAR+' ('+xx.no_of_accepted+' / '+xx.no_of_lots_inspected+')';
                grp3 += ' | DPPM = '+xx.DPPM+' ('+xx.no_of_defects+' / '+xx.sample_size+')';
                grp3 += '</a>';
                grp3 += '</h4>';
                grp3 += '</div>';
                grp3 += '<div id="grp_val'+node_parent_count.toString()+'" class="panel-collapse collapse">';
                grp3 += '<div class="panel-body table-responsive" id="child'+node_child_count.toString()+'">';

                if (xx.details.length > 0) {
                    details = '';
                    details_body = '';
                    details += '<table style="font-size:10px" class="table table-condensed table-bordered">';
                    details += '<thead>';
                    details += '<tr>';
                    details += '<td></td>';
                    details += '<td></td>';
                    details += '<td><strong>PO</strong></td>';
                    details += '<td><strong>Device Name</strong></td>';
                    details += '<td><strong>Customer</strong></td>';
                    details += '<td><strong>PO Qty</strong></td>';
                    details += '<td><strong>Family</strong></td>';
                    details += '<td><strong>Assembly Line</strong></td>';
                    details += '<td><strong>Lot No</strong></td>';
                    details += '<td><strong>App. Date</strong></td>';
                    details += '<td><strong>App. Time</strong></td>';
                    details += '<td><strong>Category</strong></td>';
                    details += '<td><strong>Type of Inspection</strong></td>';
                    details += '<td><strong>Severity of Inspection</strong></td>';
                    details += '<td><strong>Inspection Level</strong></td>';
                    details += '<td><strong>AQL</strong></td>';
                    details += '<td><strong>Accept</strong></td>';
                    details += '<td><strong>Reject</strong></td>';
                    details += '<td><strong>Date inspected</strong></td>';
                    details += '<td><strong>WW</strong></td>';
                    details += '<td><strong>FY</strong></td>';
                    details += '<td><strong>Time Inspected</strong></td>';
                    details += '<td><strong>Shift</strong></td>';
                    details += '<td><strong>Inspector</strong></td>';
                    details += '<td><strong>Submission</strong></td>';
                    details += '<td><strong>COC Requirement</strong></td>';
                    details += '<td><strong>Judgement</strong></td>';
                    details += '<td><strong>Lot Qty</strong></td>';
                    details += '<td><strong>Sample Size</strong></td>';
                    details += '<td><strong>Lot Inspected</strong></td>';
                    details += '<td><strong>Lot Accepted</strong></td>';
                    details += '<td><strong>No. of Defects</strong></td>';
                    details += '<td><strong>Remarks</strong></td>';
                    details += '<td><strong>Type</strong></td>';
                    details += '</tr>';
                    details += '</thead>';
                    details += '<tbody id="details_tbody">';

                    var cnt = 1;

                    $.each(xx.details, function(iii,xxx) {
                        
                        details_body += '<tr>';
                        details_body += '<td>'+cnt+'</td>';
                        details_body += '<td><button class="btn btn-sm view_inspection blue" data-id="'+xxx.id+'"'+ 
                                                'data-assembly_line="'+xxx.assembly_line+'"'+
                                                'data-lot_no="'+xxx.lot_no+'"'+
                                                'data-app_date="'+xxx.app_date+'"'+
                                                'data-app_time="'+xxx.app_time+'"'+
                                                'data-prod_category="'+xxx.prod_category+'"'+
                                                'data-po_no="'+xxx.po_no+'"'+
                                                'data-device_name="'+xxx.device_name+'"'+
                                                'data-customer="'+xxx.customer+'"'+
                                                'data-po_qty="'+xxx.po_qty+'"'+
                                                'data-family="'+xxx.family+'"'+
                                                'data-type_of_inspection="'+xxx.type_of_inspection+'"'+
                                                'data-severity_of_inspection="'+xxx.severity_of_inspection+'"'+
                                                'data-inspection_lvl="'+xxx.inspection_lvl+'"'+
                                                'data-aql="'+xxx.aql+'"'+
                                                'data-accept="'+xxx.accept+'"'+
                                                'data-reject="'+xxx.reject+'"'+
                                                'data-date_inspected="'+xxx.date_inspected+'"'+
                                                'data-ww="'+xxx.ww+'"'+
                                                'data-fy="'+xxx.fy+'"'+
                                                'data-time_ins_from="'+xxx.time_ins_from+'"'+
                                                'data-time_ins_to="'+xxx.time_ins_to+'"'+
                                                'data-shift="'+xxx.shift+'"'+
                                                'data-inspector="'+xxx.inspector+'"'+
                                                'data-submission="'+xxx.submission+'"'+
                                                'data-coc_req="'+xxx.coc_req+'"'+
                                                'data-judgement="'+xxx.judgement+'"'+
                                                'data-lot_qty="'+xxx.lot_qty+'"'+
                                                'data-sample_size="'+xxx.sample_size+'"'+
                                                'data-lot_inspected="'+xxx.lot_inspected+'"'+
                                                'data-lot_accepted="'+xxx.lot_accepted+'"'+ 
                                                'data-num_of_defects="'+xxx.num_of_defects+'"'+
                                                'data-remarks="'+xxx.remarks+'"><i class="fa fa-edit"></i>'+
                                            '</button>'+
                                        '</td>';
                        details_body += '<td>'+xxx.po_no+'</td>';
                        details_body += '<td>'+xxx.device_name+'</td>';
                        details_body += '<td>'+xxx.customer+'</td>';
                        details_body += '<td>'+xxx.po_qty+'</td>';
                        details_body += '<td>'+xxx.family+'</td>';
                        details_body += '<td>'+xxx.assembly_line+'</td>';
                        details_body += '<td>'+xxx.lot_no+'</td>';
                        details_body += '<td>'+xxx.app_date+'</td>';
                        details_body += '<td>'+xxx.app_time+'</td>';
                        details_body += '<td>'+xxx.prod_category+'</td>';
                        details_body += '<td>'+xxx.type_of_inspection+'</td>';
                        details_body += '<td>'+xxx.severity_of_inspection+'</td>';
                        details_body += '<td>'+xxx.inspection_lvl+'</td>';
                        details_body += '<td>'+xxx.aql+'</td>';
                        details_body += '<td>'+xxx.accept+'</td>';
                        details_body += '<td>'+xxx.reject+'</td>';
                        details_body += '<td>'+xxx.date_inspected+'</td>';
                        details_body += '<td>'+xxx.ww+'</td>';
                        details_body += '<td>'+xxx.fy+'</td>';
                        details_body += '<td>'+xxx.time_ins_from+'-'+xxx.time_ins_to+'</td>';
                        details_body += '<td>'+xxx.shift+'</td>';
                        details_body += '<td>'+xxx.inspector+'</td>';
                        details_body += '<td>'+xxx.submission+'</td>';
                        details_body += '<td>'+xxx.coc_req+'</td>';
                        details_body += '<td>'+xxx.judgement+'</td>';
                        details_body += '<td>'+xxx.lot_qty+'</td>';
                        details_body += '<td>'+xxx.sample_size+'</td>';
                        details_body += '<td>'+xxx.lot_inspected+'</td>';
                        details_body += '<td>'+xxx.lot_accepted+'</td>';
                        details_body += '<td>'+xxx.num_of_defects+'</td>';
                        details_body += '<td>'+xxx.remarks+'</td>';
                        details_body += '<td>'+xxx.type+'</td>';
                        details_body += '</tr>';
                        cnt++;
                    });

                    details += details_body;

                    details += '</tbody>';
                    details += '</table>';
                    //$('#child'+node_child_count.toString()).append(details);
                    //nxt_node++;
                }

                node_child_count++;

                grp3 += details;
                                    
                grp3 += '</div>';
                grp3 += '</div>';
                grp3 += '</div>';
                grp3 += '</div>';


                $('#child'+nxt_node).append(grp3);
                node_parent_count++;
            });
        }

    });
    //node_parent_count++;

}

function clearGrpByFields() {
    $('.grpfield').val('');
}
