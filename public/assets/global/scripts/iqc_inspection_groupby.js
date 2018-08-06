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
        $('#group_by_pane').html('');
        var address = $(this).attr('action');
        var datas = $(this).serialize();
        $('#main_pane').hide();
        $('#group_by_pane').show();
        e.preventDefault();
        openloading();
        $('#group_by_pane').html('<div class="btn-group pull-right">'+
                                '<button type="button" class="btn btn-danger btn-sm" id="btn_close_groupby">'+
                                    '<i class="fa fa-times"></i> Close'+
                                '</button>'+
                                '<button type="button" class="btn btn-info btn-sm btn_pdf_groupby" id="btn_pdf_groupby">'+
                                    '<i class="fa fa-file-pdf-o"></i> PDF'+
                                '</button>'+
                                '<button type="button" class="btn btn-success btn-sm btn_excel_groupby" id="btn_excel_groupby">'+
                                    '<i class="fa fa-file-excel-o"></i> Excel'+
                                '</button></div><br><br>');

        if($('#field2').val() == "" && $('#field3').val() == ""){
            var datas = $(this).serialize();
            var desFirst = deparam(datas);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'JSON',
                data: $(this).serialize(),
                success:function(returnData){
                    if(returnData.length > 0){
                        var form = returnData;
                        var desFirst = deparam(datas);
                        console.log(desFirst);
                        $.ajax({
                                url: GetSingleGroupByURL,
                                type: 'GET',
                                dataType: 'JSON',
                                data:{ _token:token,
                                        data: JSON.stringify(form),
                                        firstData:desFirst.field1,
                                        gto:desFirst.gto,
                                        gfrom:desFirst.gfrom
                                },
                                success:function(returnDataDetails){
                                        var details = returnDataDetails.returnData;
                                        FirstTable(form,
                                                    datas,
                                                    details,
                                                    returnDataDetails.LARList,
                                                    returnDataDetails.rejectednumList,
                                                    returnDataDetails.DPPMList);
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                        alert(xhr.status);
                                        alert(thrownError);
                                    }
                                });
                    }
                    else{
                    closeloading();
                    msg("No Data Found","failed");
                    }
                    
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
        else if($('#field2').val() != "" && $('#field1').val() != "" && $('#field3').val() == ""){
            var start = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'JSON',
                data: $(this).serialize(),
                success:function(returnData){
                    if(returnData.length > 0){
                        var form = returnData;
                        var desFirst = deparam(datas);
                        $.ajax({
                                url: GetdoubleGroupByURL,
                                type: 'GET',
                                dataType: 'JSON',
                                data:{ _token:token,
                                        data: form,
                                        firstData:desFirst.field1,
                                        secondData:desFirst.field2,
                                        gto:desFirst.gto,
                                        gfrom:desFirst.gfrom,
                                },
                                success:function(returnDataDetails){
                                        var g1 = [], g2 =[];
                                        if($('#content1').val() != "")
                                        {
                                                    g1.push($('#content1').val());
                                        }
                                        else{
                                                for(var x=0;x<form.length;x++){
                                                    g1.push(form[x].chosenfield);
                                                }
                                        }
                                        if($('#content2').val() != "")
                                        {
                                                    g2.push($('#content2').val());
                                        }
                                        else{
                                                for(var x=0;x<returnDataDetails.length;x++){
                                                    g2.push(returnDataDetails[x].chosenfield2);
                                                }
                                        }
                                                        $.ajax({
                                                            url: GetdoubleGroupByURLdetails,
                                                            type: 'GET',
                                                            dataType: 'JSON',
                                                            data:{ _token:token,
                                                                    content1:JSON.stringify(g1),
                                                                    content2:JSON.stringify(g2),
                                                                    firstData:desFirst.field1,
                                                                    secondData:desFirst.field2,
                                                                    gto:desFirst.gto,
                                                                    gfrom:desFirst.gfrom,
                                                            },
                                                            success:function(returDetails){
                                                                 secondTable(returDetails.returnData,
                                                                    desFirst,
                                                                    returDetails.LARList,
                                                                    returDetails.rejectednumList,
                                                                    returDetails.DPPMList,
                                                                    returDetails.LARListG1,
                                                                    returDetails.rejectednumListG1,
                                                                    returDetails.DPPMListG1);
                                                            },
                                                            error: function (xhr, ajaxOptions, thrownError) {
                                                                    alert(xhr.status);
                                                                    alert(thrownError);
                                                            }
                                                        });

                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                        alert(xhr.status);
                                        alert(thrownError);
                                    }
                                });
                    }
                    else{
                        closeloading();
                        msg("No Data Found","failed");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
        else{
            var start = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                dataType: 'JSON',
                data: $(this).serialize(),
                success:function(returnData){
                    if(returnData.length > 0){
                        var form = returnData;
                        var desFirst = deparam(datas);

                        //console.log(desFirst);

                        $.ajax({
                                url: GettripleGroupByURL,
                                type: 'GET',
                                dataType: 'JSON',
                                data:{ _token:token,
                                        data:form,
                                        firstData:desFirst.field1,
                                        secondData:desFirst.field2,
                                        thirdData:desFirst.field3,
                                        gto:desFirst.gto,
                                        gfrom:desFirst.gfrom,
                                },
                                success:function(returnDataDetails){
                                        var g1 = [], g2 =[], g3 = [];
                                        if($('#content1').val() != ""){
                                                g1.push($('#content1').val());
                                        }
                                        else{
                                            for(var x=0;x<form.length;x++){
                                                g1.push(form[x].chosenfield);
                                            }
                                        }
                                        if($('#content2').val() != ""){
                                                g2.push($('#content2').val());
                                        }
                                        else{
                                            var uniqueField = [];
                                                for(var x=0;x<returnDataDetails.length;x++){
                                                    g2.push(returnDataDetails[x].chosenfield2);
                                                    $.each(g2, function(i, el){
                                                        if($.inArray(el, uniqueField) === -1) uniqueField.push(el);
                                                    });
                                                }
                                            g2 = uniqueField;
                                        }
                                        if($('#content3').val() != ""){
                                                g3.push($('#content3').val());
                                        }
                                        else{
                                            var uniqueField2 = [];
                                                for(var x=0;x<returnDataDetails.length;x++){
                                                    g3.push(returnDataDetails[x].chosenfield3);
                                                    $.each(g3, function(i, el){
                                                        if($.inArray(el, uniqueField2) === -1) uniqueField2.push(el);
                                                    });
                                                }
                                                g3 = uniqueField2;
                                        }
                                            var data = {
                                                _token:token,
                                                firstData:desFirst.field1,
                                                secondData:desFirst.field2,
                                                thirdData:desFirst.field3,
                                                gto:desFirst.gto,
                                                gfrom:desFirst.gfrom,
                                                content1:JSON.stringify(g1),
                                                content2:JSON.stringify(g2),
                                                content3:JSON.stringify(g3),
                                            };
                                                        $.ajax({
                                                            url: GettripleGroupByURLdetails,
                                                            type: 'GET',
                                                            dataType: 'JSON',
                                                            data: data,
                                                            success:function(returDetails){
                                                                thirdTable(returDetails.returnData,
                                                                    desFirst,
                                                                    returDetails.LARListG1,
                                                                    returDetails.rejectednumListG1,
                                                                    returDetails.DPPMListG1,
                                                                    returDetails.LARList_2nd,
                                                                    returDetails.rejectednumList_2nd,
                                                                    returDetails.DPPMList_2nd,
                                                                    returDetails.LARList_3rd,
                                                                    returDetails.rejectednumList_3rd,
                                                                    returDetails.DPPMList_3rd
                                                                    );
                                                            },
                                                            error: function (xhr, ajaxOptions, thrownError) {
                                                                alert(xhr.status);
                                                                alert(thrownError);
                                                            }
                                                        });

                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                        alert(xhr.status);
                                        alert(thrownError);
                                    }
                                });
                    }
                    else{
                        closeloading();
                        msg("No Data Found","failed");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    });

    $('.view_inspection').live('click', function(e) {

        getDropdowns();
        $('#invoice_no').prop('readonly',true);
        $('#invoice_no').val($(this).attr('data-invoice_no'));
        getItems();

        $('#partcodelbl').val($(this).attr('data-partcode'));
        getItemDetailsEdit();
        $('#partcode').hide();

        $('#partname').val($(this).attr('data-partname'));
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

        $('#no_defects_label').hide();
        $('#no_of_defects').hide();
        $('#mode_defects_label').hide();
        $('#btn_mod_ins').hide();

        $('#save_status').val('EDIT');
        $('#iqc_result_id').val($(this).attr('data-id'));

        $('#partcodelbl').show();
        $('#partcode').hide();
        $('#partcode').select2('container').hide();

        openModeOfDefects();

        $('#IQCresultModal').modal('show');
    });

    $('#btn_close_groupby').live('click', function() {
        $('#main_pane').show();
        $('#group_by_pane').hide();
    });

    $('#btn_clear_grpby').on('click', function() {
        clearGrpByFields();
    });

    $('#btn_pdf_groupby').live('click', function() {
        window.location.href= pdfURL;
    });

    $('#btn_excel_groupby').live('click', function() {
        window.location.href= excelURL;
    });
});

function toObject(arr) {
    var rv = {};
    for (var i = 0; i < arr.length; ++i)
        rv[i] = arr[i];
    return rv;
}

function deparam(query) {
    var pairs, i, keyValuePair, key, value, map = {};
    // remove leading question mark if its there
    if (query.slice(0, 1) === '?') {
        query = query.slice(1);
    }
    if (query !== '') {
        pairs = query.split('&');
        for (i = 0; i < pairs.length; i += 1) {
            keyValuePair = pairs[i].split('=');
            key = decodeURIComponent(keyValuePair[0]);
            value = (keyValuePair.length > 1) ? decodeURIComponent(keyValuePair[1]) : undefined;
            map[key] = value;
        }
    }
    return map;
}

function FirstTable(req,datas,details,LAR,REJ,DPPM){
    var d = deparam(datas);
    for(var x=0;x<details.length;x++){
        var gp1 = "";
            gp1 += "<div class='panel-group accordion scrollable' id='grp"+x+"'>";
                gp1 += "<div class='panel panel-info'>";
                    gp1 += "<div class='panel-heading'>";
                        gp1 += "<h4 class='panel-title'>";
                        //var n = ((MushRoomHead.poop/MushRoomHead.shit)*1000000 != "NaN")?(MushRoomHead.poop/MushRoomHead.shit)*1000000:0;
                        var acc = (details[x].length == 1)?1:details[x].length - REJ[x]["0"].rejects;
                        var Larc = ((acc/details[x].length)*100).toFixed(2);

                        if(DPPM[x]["0"].DPPM != null){
                            gp1 += "<a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#grp"+x+" 'href='#grp_val"+x+"' aria-expanded='false' style='background-color:#F3565D;'>";
                            gp1 += d.field1 + ": "+req[x].chosenfield+"  &emsp;"
                            gp1 += "LAR : "+Larc+"% ("+acc+"/"+details[x].length+") &emsp;"
                            gp1 += "DPPM: "+DPPM[x]["0"].DPPM+" &emsp;";
                            gp1 += "("+DPPM[x]["0"].no_of_defects+"/"+DPPM[x]["0"].sample_size+")</a>";
                        }
                        else{
                            gp1 += "<a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#grp"+x+" 'href='#grp_val"+x+"' aria-expanded='false'>";
                            gp1 += d.field1 + ": "+req[x].chosenfield+"  &emsp;"
                            gp1 += "LAR : "+LAR[x]["0"].LAR+"% ("+acc+"/"+details[x].length+") &emsp;"
                            gp1 += "DPPM: 0.00 &emsp;(0/0)</a>";
                        }

                        gp1 += "</h4>";
                    gp1 += "</div>";
                    gp1 += "<div id='grp_val"+x+"' class='panel-collapse collapse' aria-expanded='false'>";
                        gp1 += "<div class='panel-body table-responsive' id='child"+x+"'>";
                            gp1 += "<table style='font-size:9px;width:100%' class='table table-condensed table-striped table-bordered'>";
                                gp1 += "<thead>";
                                    gp1 += "<tr>";
                                        gp1 += "<td></td>";
                                        gp1 += "<td></td>";
                                        gp1 += '<td><strong>Invoice No.</strong></td>';
                                        gp1 += '<td><strong>Part Code</strong></td>';
                                        gp1 += '<td><strong>Part Name</strong></td>';
                                        gp1 += '<td><strong>Supplier</strong></td>';
                                        gp1 += '<td><strong>App. Date</strong></td>';
                                        gp1 += '<td><strong>App. Time</strong></td>';
                                        gp1 += '<td><strong>App. No.</strong></td>';
                                        gp1 += '<td><strong>Lot No.</strong></td>';
                                        gp1 += '<td><strong>Lot Qty.</strong></td>';
                                        gp1 += '<td><strong>Type of Inspection</strong></td>';
                                        gp1 += '<td><strong>Severity of Inspection</strong></td>';
                                        gp1 += '<td><strong>Inspection Lvl</strong></td>';
                                        gp1 += '<td><strong>AQL</strong></td>';
                                        gp1 += '<td><strong>Accept</strong></td>';
                                        gp1 += '<td><strong>Reject</strong></td>';
                                        gp1 += '<td><strong>Date Inspected</strong></td>';
                                        gp1 += '<td><strong>WW</strong></td>';
                                        gp1 += '<td><strong>FY</strong></td>';
                                        gp1 += '<td><strong>Shift</strong></td>';
                                        gp1 += '<td><strong>Time Inspected</strong></td>';
                                        gp1 += '<td><strong>Inspector</strong></td>';
                                        gp1 += '<td><strong>Submission</strong></td>';
                                        gp1 += '<td><strong>Judgement</strong></td>';
                                        gp1 += '<td><strong>Lot Inspected</strong></td>';
                                        gp1 += '<td><strong>Lot Accepted</strong></td>';
                                        gp1 += '<td><strong>Sample Size</strong></td>';
                                        gp1 += '<td><strong>No. of Defects</strong></td>';
                                        gp1 += '<td><strong>Classification</strong></td>';
                                        gp1 += '<td><strong>Remarks</strong></td>';
                                    gp1 += "</tr>";
                                gp1 += "</thead>";
                                gp1 += "<tbody id='details_tbody'>";
                                    for(y=0;y<details[x].length;y++){
                                        var num = y+1;
                                        if(details[x][y].judgement == "Reject")
                                        {
                                            gp1 += "<tr style='color:#F3565D;'>";
                                        }
                                        else{
                                            gp1 += "<tr>";
                                        }
                                       
                                            gp1 += "<td>"+num+"</td>";
                                            gp1 += '<td><button class="btn btn-sm view_inspection blue" data-id="'+details[x][y].id+'"'+ 
                                                        'data-invoice_no="'+details[x][y].invoice_no+'" '+
                                                        'data-partcode="'+details[x][y].partcode+'" '+
                                                        'data-partname="'+details[x][y].partname+'" '+
                                                        'data-supplier="'+details[x][y].supplier+'" '+
                                                        'data-app_date="'+details[x][y].app_date+'" '+
                                                        'data-app_time="'+details[x][y].app_time+'" '+
                                                        'data-app_no="'+details[x][y].app_no+'" '+
                                                        'data-lot_no="'+details[x][y].lot_no+'" '+
                                                        'data-lot_qty="'+details[x][y].lot_qty+'" '+
                                                        'data-type_of_inspection="'+details[x][y].type_of_inspection+'" '+
                                                        'data-severity_of_inspection="'+details[x][y].severity_of_inspection+'" '+
                                                        'data-inspection_lvl="'+details[x][y].inspection_lvl+'" '+
                                                        'data-aql="'+details[x][y].aql+'" '+
                                                        'data-accept="'+details[x][y].accept+'" '+
                                                        'data-reject="'+details[x][y].reject+'" '+
                                                        'data-date_ispected="'+details[x][y].date_ispected+'" '+
                                                        'data-ww="'+details[x][y].ww+'" '+
                                                        'data-fy="'+details[x][y].fy+'" '+
                                                        'data-shift="'+details[x][y].shift+'" '+
                                                        'data-time_ins_from="'+details[x][y].time_ins_from+'" '+
                                                        'data-time_ins_to="'+details[x][y].time_ins_to+'"'+
                                                        'data-inspector="'+details[x][y].inspector+'" '+
                                                        'data-submission="'+details[x][y].submission+'" '+
                                                        'data-judgement="'+details[x][y].judgement+'" '+
                                                        'data-lot_inspected="'+details[x][y].lot_inspected+'" '+
                                                        'data-lot_accepted="'+details[x][y].lot_accepted+'" '+
                                                        'data-sample_size="'+details[x][y].sample_size+'" '+
                                                        'data-no_of_defects="'+details[x][y].no_of_defects+'" '+
                                                        'data-classification="'+details[x][y].classification+'" '+
                                                        'data-remarks="'+details[x][y].remarks+'">'+
                                                        '<i class="fa fa-edit"></i>'+
                                                    '</button>'+
                                                '</td>';
                                       
                                            gp1 += '<td>'+details[x][y].invoice_no+'</td>';
                                            gp1 += '<td>'+details[x][y].partcode+'</td>';
                                            gp1 += '<td>'+details[x][y].partname+'</td>';
                                            gp1 += '<td>'+details[x][y].supplier+'</td>';
                                            gp1 += '<td>'+details[x][y].app_date+'</td>';
                                            gp1 += '<td>'+details[x][y].app_time+'</td>';
                                            gp1 += '<td>'+details[x][y].app_no+'</td>';
                                            gp1 += '<td>'+details[x][y].lot_no+'</td>';
                                            gp1 += '<td>'+details[x][y].lot_qty+'</td>';
                                            gp1 += '<td>'+details[x][y].type_of_inspection+'</td>';
                                            gp1 += '<td>'+details[x][y].severity_of_inspection+'</td>';
                                            gp1 += '<td>'+details[x][y].inspection_lvl+'</td>';
                                            gp1 += '<td>'+details[x][y].aql+'</td>';
                                            gp1 += '<td>'+details[x][y].accept+'</td>';
                                            gp1 += '<td>'+details[x][y].reject+'</td>';
                                            gp1 += '<td>'+details[x][y].date_ispected+'</td>';
                                            gp1 += '<td>'+details[x][y].ww+'</td>';
                                            gp1 += '<td>'+details[x][y].fy+'</td>';
                                            gp1 += '<td>'+details[x][y].shift+'</td>';
                                            gp1 += '<td>'+details[x][y].time_ins_from+'-'+details[x][y].time_ins_to+'</td>';
                                            gp1 += '<td>'+details[x][y].inspector+'</td>';
                                            gp1 += '<td>'+details[x][y].submission+'</td>';
                                            gp1 += '<td>'+details[x][y].judgement+'</td>';
                                            gp1 += '<td>'+details[x][y].lot_inspected+'</td>';
                                            gp1 += '<td>'+details[x][y].lot_accepted+'</td>';
                                            gp1 += '<td>'+details[x][y].sample_size+'</td>';
                                            gp1 += '<td>'+details[x][y].no_of_defects+'</td>';
                                            gp1 += '<td>'+details[x][y].classification+'</td>';
                                            gp1 += '<td>'+details[x][y].remarks+'</td>';
                                        gp1 += "</tr>";
                                    }
                                gp1 += "</tbody>";
                            gp1 += "</table>";
                        gp1 += "</div>";
                    gp1 += "</div>";
                gp1 += "</div>";
            gp1 += "</div>";
        $('#group_by_pane').append(gp1);
    }
    closeloading();
}


var JonnySins = 0;
var MariaOzawa = 0;


function GETDPPMsecond(req,DPPM,REJ,xvideos){
    var poop = 0;
    var shit = 0;
    for(var x=xvideos;x<req.length;x++){
        for(y=0;y<req[x].length;y++){
            poo = (DPPM[x][y]["0"].no_of_defects == null)?0:DPPM[x][y]["0"].no_of_defects;
            poop += parseInt(poo);
            scat = (DPPM[x][y]["0"].sample_size == null)?0:DPPM[x][y]["0"].sample_size;
            shit += parseInt(scat);
            var acc = req[x][y].length - REJ[x][y]["0"].rejects;
            JonnySins += acc;
            MariaOzawa += req[x][y].length;
        }
        break;
    }
    var n = (((poop/shit) * 1000000) != "NaN")?(poop/shit) * 1000000:0;
    if(isNaN(n)){
        n = 0;
    }
    var DeepPePeNice = n.toFixed(2);
    return {
        poop: poop,
        shit: shit,
        DPPM:DeepPePeNice,
        accepted: JonnySins,
        total: MariaOzawa
    };
}


function GETDPPMthird(req,DPPM,REJ,xvideos,youporn,stage){
    var poop = 0;
    var shit = 0;
    if(stage == 2)
    {
        for(var x1=xvideos;x1<req.length;x1++){
                JonnySins =0;
                MariaOzawa =0;
                for(var y1=youporn;y1<req[x1].length;y1++){
                        poo = (DPPM[x1][y1]["0"].no_of_defects == null)?0:DPPM[x1][y1]["0"].no_of_defects;
                        poop += parseInt(poo);
                        scat = (DPPM[x1][y1]["0"].sample_size == null)?0:DPPM[x1][y1]["0"].sample_size;
                        shit += parseInt(scat);
                        var acc =(req[x1][y1].length == 1)?1:req[x1][y1].length - REJ[x1][y1]["0"].rejects;
                     //(req[x][y].length == 1)?1:req[x][y].length - REJ_2nd[x][y]["0"].rejects;
                        JonnySins += acc;
                        MariaOzawa += req[x1][y1].length;
                      break;
                }
                break;
        }
    }
    else{
        for(var x1=xvideos;x1<req.length;x1++){
                JonnySins =0;
                MariaOzawa =0;
                for(var y1=0;y1<req[x1].length;y1++){
                        poo = (DPPM[x1][y1]["0"].no_of_defects == null)?0:DPPM[x1][y1]["0"].no_of_defects;
                        poop += parseInt(poo);
                        scat = (DPPM[x1][y1]["0"].sample_size == null)?0:DPPM[x1][y1]["0"].sample_size;
                        shit += parseInt(scat);
                        var acc =(req[x1][y1].length == 1)?1:req[x1][y1].length - REJ[x1][y1]["0"].rejects;
                     //(req[x][y].length == 1)?1:req[x][y].length - REJ_2nd[x][y]["0"].rejects;
                        JonnySins += acc;
                        MariaOzawa += req[x1][y1].length;
                     
                }
                break;
        }
    }
    var n = (((poop/shit) * 1000000) != "NaN")?(poop/shit) * 1000000:0;
    if(isNaN(n)){
        n = 0;
    }
    var DeepPePeNice = n.toFixed(2);
    return {
        poop: poop,
        shit: shit,
        DPPM:DeepPePeNice,
        accepted: JonnySins,
        total: MariaOzawa
    };
}

function secondTable(req,datas,LAR,REJ,DPPM,LARg1,REJg1,DPPMg1){
    for(var x=0;x<req.length;x++){
        var MushRoomHead = GETDPPMsecond(req,DPPM,REJ,x);
        var gp1 = "";
            gp1 += "<div class='panel-group accordion scrollable' id='grp"+x+"'>";
                gp1 += "<div class='panel panel-info'>";
                    gp1 += "<div class='panel-heading'>";
                        gp1 += "<h4 class='panel-title'><a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#grp"+x+"' href='#grp_val"+x+"' aria-expanded='false'>";
                       
                        var n = ((MushRoomHead.poop/MushRoomHead.shit)*1000000 != "NaN")?(MushRoomHead.poop/MushRoomHead.shit)*1000000:0;
                        var acc = (req[x].length == 1)?1:req[x].length - REJg1[x]["0"].rejects;
                        var Larc = ((MushRoomHead.accepted/MushRoomHead.total)*100).toFixed(2);
                        if(!isNaN(n)){
                            gp1 += datas.field1 + ": "+req[x]["0"]["0"].chosenfield+"  &emsp;"
                            gp1 += "LAR : "+Larc+"% ("+MushRoomHead.accepted+"/"+MushRoomHead.total+") &emsp;"
                            gp1 += "DPPM: "+MushRoomHead.DPPM+" &emsp;";
                            gp1 += "("+MushRoomHead.poop+"/"+MushRoomHead.shit+")</a>";
                        }
                        else{
                            var acc = (req[x].length == 1)?1:req[x].length - REJg1[x]["0"].rejects;
                            gp1 += datas.field1 + ": "+req[x]["0"]["0"].chosenfield+"  &emsp;"
                            gp1 += "LAR : "+LARg1[x]["0"].LAR+"% ("+acc+"/"+req[x].length+") &emsp;"
                            gp1 += "DPPM: 0.00 &emsp;(0/0)</a>";
                        }
                        gp1 += "</h4>";
                    gp1 += "</div>";
                    gp1 += "<div id='grp_val"+x+"' class='panel-collapse collapse' aria-expanded='false'>";
                        gp1 += "<div class='panel-body table-responsive' id='child"+x+"'>";

                            for(y=0;y<req[x].length;y++){
                                    JonnySins = 0;
                                    MariaOzawa = 0;
                                    var num = y+1;
                                    var idc = guidGenerator();
                                    // gp1 += "<tr>";
                                    // gp1 += "<td>";
                                    gp1 += "<div class='panel-group accordion scrollable' id='grp"+idc+"'>";
                                        gp1 += "<div class='panel panel-info'>";
                                            gp1 += "<div class='panel-heading'>";
                                                gp1 += "<h4 class='panel-title'>";

                                                    if(DPPM[x][y]["0"].DPPM != null){
                                                        gp1 += "<a class='accordion-toggle' data-toggle='collapse' data-parent='#grp"+idc+"' href='#grp_val"+idc+"' aria-expanded='false' style='background-color:#F3565D;'>";
                                                        gp1 += datas.field2 + ": "+req[x][y]["0"].chosenfield2+"  &emsp;"
                                                        var acc = req[x][y].length - REJ[x][y]["0"].rejects;
                                                        gp1 += "LAR : "+LAR[x][y]["0"].LAR+"% ("+acc+"/"+req[x][y].length+") &emsp;"
                                                        gp1 += "DPPM: "+DPPM[x][y]["0"].DPPM+" &emsp;";
                                                        var nd = DPPM[x][y]["0"].no_of_defects;
                                                        var ss = DPPM[x][y]["0"].sample_size;
                                                        gp1 += "("+DPPM[x][y]["0"].no_of_defects+"/"+DPPM[x][y]["0"].sample_size+")</a>";
                                                    }
                                                    else{
                                                        gp1 += "<a class='accordion-toggle' data-toggle='collapse' data-parent='#grp"+idc+"' href='#grp_val"+idc+"' aria-expanded='false'>";
                                                        gp1 += datas.field2 + ": "+req[x][y]["0"].chosenfield2+"  &emsp;"
                                                        var acc = req[x][y].length - REJ[x][y]["0"].rejects;
                                                        gp1 += "LAR : "+LAR[x][y]["0"].LAR+"% ("+acc+"/"+req[x][y].length+") &emsp;"
                                                        gp1 += "DPPM: 0.00 &emsp;(0/0)</a>";
                                                    }
                                                gp1 += "</h4>";
                                            gp1 += "</div>";
                                            gp1 += "<div id='grp_val"+idc+"' class='panel-collapse collapse' aria-expanded='false'>";
                                                gp1 += "<div class='panel-body table-responsive' id='child"+idc+"'>";
                                                    gp1 += "<table style='font-size:9px;width:100%' class='table table-condensed table-striped table-bordered'>";
                                                        gp1 += "<thead>";
                                                            gp1 += "<tr>";
                                                                gp1 += "<td></td>";
                                                                gp1 += "<td></td>";
                                                                gp1 += '<td><strong>Invoice No.</strong></td>';
                                                                gp1 += '<td><strong>Part Code</strong></td>';
                                                                gp1 += '<td><strong>Part Name</strong></td>';
                                                                gp1 += '<td><strong>Supplier</strong></td>';
                                                                gp1 += '<td><strong>App. Date</strong></td>';
                                                                gp1 += '<td><strong>App. Time</strong></td>';
                                                                gp1 += '<td><strong>App. No.</strong></td>';
                                                                gp1 += '<td><strong>Lot No.</strong></td>';
                                                                gp1 += '<td><strong>Lot Qty.</strong></td>';
                                                                gp1 += '<td><strong>Type of Inspection</strong></td>';
                                                                gp1 += '<td><strong>Severity of Inspection</strong></td>';
                                                                gp1 += '<td><strong>Inspection Lvl</strong></td>';
                                                                gp1 += '<td><strong>AQL</strong></td>';
                                                                gp1 += '<td><strong>Accept</strong></td>';
                                                                gp1 += '<td><strong>Reject</strong></td>';
                                                                gp1 += '<td><strong>Date Inspected</strong></td>';
                                                                gp1 += '<td><strong>WW</strong></td>';
                                                                gp1 += '<td><strong>FY</strong></td>';
                                                                gp1 += '<td><strong>Shift</strong></td>';
                                                                gp1 += '<td><strong>Time Inspected</strong></td>';
                                                                gp1 += '<td><strong>Inspector</strong></td>';
                                                                gp1 += '<td><strong>Submission</strong></td>';
                                                                gp1 += '<td><strong>Judgement</strong></td>';
                                                                gp1 += '<td><strong>Lot Inspected</strong></td>';
                                                                gp1 += '<td><strong>Lot Accepted</strong></td>';
                                                                gp1 += '<td><strong>Sample Size</strong></td>';
                                                                gp1 += '<td><strong>No. of Defects</strong></td>';
                                                                gp1 += '<td><strong>Classification</strong></td>';
                                                                gp1 += '<td><strong>Remarks</strong></td>';
                                                            gp1 += "</tr>";
                                                        gp1 += "</thead>";
                                                        gp1 += "<tbody>";
                                                            for(z=0;z<req[x][y].length;z++){
                                                                num = z+1;
                                                                if(req[x][y][z].judgement == "Reject")
                                                                {
                                                                    gp1 += "<tr style='color:#F3565D;'>";
                                                                }
                                                                else{
                                                                    gp1 += "<tr>";
                                                                }
                                                                    gp1 += "<td>"+num+"</td>";
                                                                    gp1 += '<td><button class="btn btn-sm view_inspection blue" data-id="'+req[x][y][z].id+'"'+ 
                                                                                'data-invoice_no="'+req[x][y][z].invoice_no+'" '+
                                                                                'data-partcode="'+req[x][y][z].partcode+'" '+
                                                                                'data-partname="'+req[x][y][z].partname+'" '+
                                                                                'data-supplier="'+req[x][y][z].supplier+'" '+
                                                                                'data-app_date="'+req[x][y][z].app_date+'" '+
                                                                                'data-app_time="'+req[x][y][z].app_time+'" '+
                                                                                'data-app_no="'+req[x][y][z].app_no+'" '+
                                                                                'data-lot_no="'+req[x][y][z].lot_no+'" '+
                                                                                'data-lot_qty="'+req[x][y][z].lot_qty+'" '+
                                                                                'data-type_of_inspection="'+req[x][y][z].type_of_inspection+'" '+
                                                                                'data-severity_of_inspection="'+req[x][y][z].severity_of_inspection+'" '+
                                                                                'data-inspection_lvl="'+req[x][y][z].inspection_lvl+'" '+
                                                                                'data-aql="'+req[x][y][z].aql+'" '+
                                                                                'data-accept="'+req[x][y][z].accept+'" '+
                                                                                'data-reject="'+req[x][y][z].reject+'" '+
                                                                                'data-date_ispected="'+req[x][y][z].date_ispected+'" '+
                                                                                'data-ww="'+req[x][y][z].ww+'" '+
                                                                                'data-fy="'+req[x][y][z].fy+'" '+
                                                                                'data-shift="'+req[x][y][z].shift+'" '+
                                                                                'data-time_ins_from="'+req[x][y][z].time_ins_from+'" '+
                                                                                'data-time_ins_to="'+req[x][y][z].time_ins_to+'"'+
                                                                                'data-inspector="'+req[x][y][z].inspector+'" '+
                                                                                'data-submission="'+req[x][y][z].submission+'" '+
                                                                                'data-judgement="'+req[x][y][z].judgement+'" '+
                                                                                'data-lot_inspected="'+req[x][y][z].lot_inspected+'" '+
                                                                                'data-lot_accepted="'+req[x][y][z].lot_accepted+'" '+
                                                                                'data-sample_size="'+req[x][y][z].sample_size+'" '+
                                                                                'data-no_of_defects="'+req[x][y][z].no_of_defects+'" '+
                                                                                'data-classification="'+req[x][y][z].classification+'" '+
                                                                                'data-remarks="'+req[x][y][z].remarks+'">'+
                                                                                '<i class="fa fa-edit"></i>'+
                                                                            '</button>'+
                                                                        '</td>';
                                                                    gp1 += '<td>'+req[x][y][z].invoice_no+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].partcode+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].partname+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].supplier+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].app_date+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].app_time+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].app_no+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].lot_no+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].lot_qty+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].type_of_inspection+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].severity_of_inspection+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].inspection_lvl+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].aql+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].accept+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].reject+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].date_ispected+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].ww+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].fy+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].shift+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].time_ins_from+'-'+req[x][y][z].time_ins_to+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].inspector+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].submission+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].judgement+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].lot_inspected+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].lot_accepted+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].sample_size+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].no_of_defects+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].classification+'</td>';
                                                                    gp1 += '<td>'+req[x][y][z].remarks+'</td>';
                                                                gp1 += "  </tr>";
                                                            }

                                                        gp1 += "</tbody>";
                                                    gp1 += "</table>";
                                                gp1 += "</div>";
                                            gp1 += "</div>";
                                        gp1 += "</div>";
                                    gp1 += "</div>";
                                
                            }
                        gp1 += "</div>";
                    gp1 += "</div>";
                gp1 += "</div>";
            gp1 += "</div>";
        $('#group_by_pane').append(gp1);

        // var pahabolbol = datas.field1 + ": "+req[x]["0"]["0"].chosenfield;
        // pahabolbol += "  LAR : "+Larc+"% ("+MushRoomHead.accepted+"/"+MushRoomHead.total+") &emsp;";
        // pahabolbol += "DPPM: "+MushRoomHead.DPPM+" &emsp;";
        // pahabolbol += "("+MushRoomHead.poop+"/"+MushRoomHead.shit+")";
        // document.getElementById("butthead"+x).innerHTML = pahabolbol;

    }
    closeloading();
}


function thirdTable(req,datas,LARg1,REJg1,DPPMg1,LAR_2nd,REJ_2nd,DPPM_2nd,LAR_3rd,REJ_3rd,DPPM_3rd){
    for(var x=0;x<req.length;x++){
        //var MushRoomHead = GETDPPMthird(req,DPPM_3rd,REJ_3rd,x,0,1);
        var MushRoomHead = GETDPPMthird(req,DPPM_2nd,REJ_2nd,x,0,1);
        var gp1 = "";
            gp1 += "<div class='panel-group accordion scrollable' id='grp"+x+"'>";
                gp1 += "<div class='panel panel-info'><div class='panel-heading'>";
                    gp1 += "<h4 class='panel-title'><a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#grp"+x+"' href='#grp_val"+x+"' aria-expanded='false'>";
                        var n = ((MushRoomHead.poop/MushRoomHead.shit)*1000000 != "NaN")?(MushRoomHead.poop/MushRoomHead.shit)*1000000:0;
                        var acc = (req[x].length == 1)?1:req[x].length - REJg1[x]["0"].rejects;
                        var Larc = ((MushRoomHead.accepted/MushRoomHead.total)*100).toFixed(2);
                        if(!isNaN(n)){
                            gp1 += datas.field1 + ": "+req[x]["0"]["0"]["0"].chosenfield+"  &emsp;"
                            gp1 += "LAR : "+Larc+"% ("+MushRoomHead.accepted+"/"+MushRoomHead.total+") &emsp;"
                            gp1 += "DPPM: "+n.toFixed(2)+" &emsp;";
                            gp1 += "("+MushRoomHead.poop+"/"+MushRoomHead.shit+")</a>";
                        }
                        else{
                            gp1 += datas.field1 + ": "+req[x]["0"]["0"]["0"].chosenfield+"  &emsp;"
                            gp1 += "LAR : "+Larc+"% ("+acc+"/"+req[x].length+") &emsp;"
                            gp1 += "DPPM: 0.00 &emsp;(0/0)</a>";
                        }
                    gp1 += "</h4>";
                gp1 += "</div>";
                gp1 += "<div id='grp_val"+x+"' class='panel-collapse collapse' aria-expanded='false'>";
                    gp1 += "<div class='panel-body' id='child"+x+"'>";
                     
                                for(y=0;y<req[x].length;y++){
                                    JonnySins =0;
                                    MariaOzawa =0;
                                    var MushRoomHead = GETDPPMthird(req,DPPM_2nd,REJ_2nd,x,y,2);
                                    var idc = guidGenerator();
                                    gp1 += "<div class='panel-group accordion scrollable' id='grp"+idc+"'>";
                                        gp1 += "<div class='panel panel-info'>";
                                            gp1 += "<div class='panel-heading'>";
                                                gp1 += "<h4 class='panel-title'><a class='accordion-toggle' data-toggle='collapse' data-parent='#grp"+idc+"' href='#grp_val"+idc+"' aria-expanded='false'>";
                                                    var n = ((MushRoomHead.poop/MushRoomHead.shit)*1000000 != "NaN")?(MushRoomHead.poop/MushRoomHead.shit)*1000000:0;
                                                    var acc = (req[x][y].length == 1)?1:req[x][y].length - REJ_2nd[x][y]["0"].rejects;
                                                    var Larc = ((acc/req[x][y].length)*100).toFixed(2);
                                                    if(!isNaN(n)){
                                                        gp1 += datas.field2 + ": "+req[x][y]["0"]["0"].chosenfield2+"  &emsp;"
                                                        gp1 += "LAR : "+Larc+"% ("+MushRoomHead.accepted+"/"+MushRoomHead.total+") &emsp;"
                                                        gp1 += "DPPM: "+n.toFixed(2)+" &emsp;";
                                                        gp1 += "("+MushRoomHead.poop+"/"+MushRoomHead.shit+")</a>";
                                                    }
                                                    else{
                                                        gp1 += datas.field2 + ": "+req[x][y]["0"]["0"].chosenfield2+"  &emsp;"
                                                        gp1 += "LAR : "+Larc+"% ("+acc+"/"+req[x][y].length+") &emsp;"
                                                        gp1 += "DPPM: 0.00 &emsp;(0/0)</a>";
                                                    }
                                                gp1 += "</h4>";
                                            gp1 += "</div>";

                                            gp1 += "<div id='grp_val"+idc+"' class='panel-collapse collapse' aria-expanded='false'>";
                                                gp1 += "<div class='panel-body' id='child"+idc+"'>";

                                                    for(z=0;z<req[x][y].length;z++){
                                                        var idc2 = guidGenerator();
                                                        gp1 += "<div class='panel-group accordion scrollable' id='grp"+idc2+"'>";
                                                            gp1 += "<div class='panel panel-info'>";
                                                                gp1 += "<div class='panel-heading'>";
                                                                    gp1 += "<h4 class='panel-title'>";
                                                                        if(DPPM_3rd[x][y][z]["0"].DPPM != null){
                                                                            gp1 += "<a class='accordion-toggle' data-toggle='collapse' data-parent='#grp"+idc2+"' href='#grp_val"+idc2+"' aria-expanded='false' style='background-color:#F3565D;'>";
                                                                            gp1 += datas.field3 + ": "+req[x][y][z]["0"].chosenfield3+"  &emsp;"
                                                                            var acc =(req[x][y][z].length == 1)?1:req[x][y][z].length - REJ_3rd[x][y][z]["0"].rejects;
                                                                            gp1 += "LAR : "+LAR_3rd[x][y][z]["0"].LAR+"% ("+acc+"/"+req[x][y][z].length+") &emsp;"
                                                                            gp1 += "DPPM: "+DPPM_3rd[x][y][z]["0"].DPPM+" &emsp;";
                                                                            gp1 += "("+DPPM_3rd[x][y][z]["0"].no_of_defects+"/"+DPPM_3rd[x][y][z]["0"].sample_size+")</a>";
                                                                        }
                                                                        else{
                                                                            gp1 += "<a class='accordion-toggle' data-toggle='collapse' data-parent='#grp"+idc2+"' href='#grp_val"+idc2+"' aria-expanded='false'>";
                                                                            gp1 += datas.field3 + ": "+req[x][y][z]["0"].chosenfield3+"  &emsp;"
                                                                            var acc =(req[x][y][z].length == 1)?1:req[x][y][z].length - REJ_3rd[x][y][z]["0"].rejects;
                                                                            gp1 += "LAR : "+LAR_3rd[x][y][z]["0"].LAR+"% ("+acc+"/"+req[x][y][z].length+") &emsp;"
                                                                            gp1 += "DPPM: 0.00 &emsp;(0/0)</a>";
                                                                        }
                                                                    gp1 += "</h4>";
                                                                gp1 += "</div>";

                                                                gp1 += "<div id='grp_val"+idc2+"' class='panel-collapse collapse in' aria-expanded='false' >";
                                                                    gp1 += "<div class='panel-body table-responsive' id='child"+idc2+"'>";
                                                                        gp1 += "<table style='font-size:9px;width:100%' class='table table-condensed table-striped table-bordered'>";
                                                                            gp1 += "<thead>";
                                                                                gp1 += "<tr>";
                                                                                    gp1 += "<td></td>";
                                                                                    gp1 += "<td></td>";
                                                                                    gp1 += '<td><strong>Invoice No.</strong></td>';
                                                                                    gp1 += '<td><strong>Part Code</strong></td>';
                                                                                    gp1 += '<td><strong>Part Name</strong></td>';
                                                                                    gp1 += '<td><strong>Supplier</strong></td>';
                                                                                    gp1 += '<td><strong>App. Date</strong></td>';
                                                                                    gp1 += '<td><strong>App. Time</strong></td>';
                                                                                    gp1 += '<td><strong>App. No.</strong></td>';
                                                                                    gp1 += '<td><strong>Lot No.</strong></td>';
                                                                                    gp1 += '<td><strong>Lot Qty.</strong></td>';
                                                                                    gp1 += '<td><strong>Type of Inspection</strong></td>';
                                                                                    gp1 += '<td><strong>Severity of Inspection</strong></td>';
                                                                                    gp1 += '<td><strong>Inspection Lvl</strong></td>';
                                                                                    gp1 += '<td><strong>AQL</strong></td>';
                                                                                    gp1 += '<td><strong>Accept</strong></td>';
                                                                                    gp1 += '<td><strong>Reject</strong></td>';
                                                                                    gp1 += '<td><strong>Date Inspected</strong></td>';
                                                                                    gp1 += '<td><strong>WW</strong></td>';
                                                                                    gp1 += '<td><strong>FY</strong></td>';
                                                                                    gp1 += '<td><strong>Shift</strong></td>';
                                                                                    gp1 += '<td><strong>Time Inspected</strong></td>';
                                                                                    gp1 += '<td><strong>Inspector</strong></td>';
                                                                                    gp1 += '<td><strong>Submission</strong></td>';
                                                                                    gp1 += '<td><strong>Judgement</strong></td>';
                                                                                    gp1 += '<td><strong>Lot Inspected</strong></td>';
                                                                                    gp1 += '<td><strong>Lot Accepted</strong></td>';
                                                                                    gp1 += '<td><strong>Sample Size</strong></td>';
                                                                                    gp1 += '<td><strong>No. of Defects</strong></td>';
                                                                                    gp1 += '<td><strong>Classification</strong></td>';
                                                                                    gp1 += '<td><strong>Remarks</strong></td>';
                                                                                gp1 += "</tr>";
                                                                            gp1 += "</thead>";
                                                                            gp1 += "<tbody>";
                                                                                for(a=0;a<req[x][y][z].length;a++){
                                                                                    num = a+1;
                                                                                   if(req[x][y][z][a].judgement == "Reject")
                                                                                    {
                                                                                        gp1 += "<tr style='color:#F3565D;'>";
                                                                                    }
                                                                                    else{
                                                                                        gp1 += "<tr>";
                                                                                    }
                                                                                        gp1 += "<td>"+num+"</td>";
                                                                                        gp1 += '<td><button class="btn btn-sm view_inspection blue" data-id="'+req[x][y][z][a].id+'"'+ 
                                                                                                    'data-invoice_no="'+req[x][y][z][a].invoice_no+'" '+
                                                                                                    'data-partcode="'+req[x][y][z][a].partcode+'" '+
                                                                                                    'data-partname="'+req[x][y][z][a].partname+'" '+
                                                                                                    'data-supplier="'+req[x][y][z][a].supplier+'" '+
                                                                                                    'data-app_date="'+req[x][y][z][a].app_date+'" '+
                                                                                                    'data-app_time="'+req[x][y][z][a].app_time+'" '+
                                                                                                    'data-app_no="'+req[x][y][z][a].app_no+'" '+
                                                                                                    'data-lot_no="'+req[x][y][z][a].lot_no+'" '+
                                                                                                    'data-lot_qty="'+req[x][y][z][a].lot_qty+'" '+
                                                                                                    'data-type_of_inspection="'+req[x][y][z][a].type_of_inspection+'" '+
                                                                                                    'data-severity_of_inspection="'+req[x][y][z][a].severity_of_inspection+'" '+
                                                                                                    'data-inspection_lvl="'+req[x][y][z][a].inspection_lvl+'" '+
                                                                                                    'data-aql="'+req[x][y][z][a].aql+'" '+
                                                                                                    'data-accept="'+req[x][y][z][a].accept+'" '+
                                                                                                    'data-reject="'+req[x][y][z][a].reject+'" '+
                                                                                                    'data-date_ispected="'+req[x][y][z][a].date_ispected+'" '+
                                                                                                    'data-ww="'+req[x][y][z][a].ww+'" '+
                                                                                                    'data-fy="'+req[x][y][z][a].fy+'" '+
                                                                                                    'data-shift="'+req[x][y][z][a].shift+'" '+
                                                                                                    'data-time_ins_from="'+req[x][y][z][a].time_ins_from+'" '+
                                                                                                    'data-time_ins_to="'+req[x][y][z][a].time_ins_to+'"'+
                                                                                                    'data-inspector="'+req[x][y][z][a].inspector+'" '+
                                                                                                    'data-submission="'+req[x][y][z][a].submission+'" '+
                                                                                                    'data-judgement="'+req[x][y][z][a].judgement+'" '+
                                                                                                    'data-lot_inspected="'+req[x][y][z][a].lot_inspected+'" '+
                                                                                                    'data-lot_accepted="'+req[x][y][z][a].lot_accepted+'" '+
                                                                                                    'data-sample_size="'+req[x][y][z][a].sample_size+'" '+
                                                                                                    'data-no_of_defects="'+req[x][y][z][a].no_of_defects+'" '+
                                                                                                    'data-classification="'+req[x][y][z][a].classification+'" '+
                                                                                                    'data-remarks="'+req[x][y][z][a].remarks+'">'+
                                                                                                    '<i class="fa fa-edit"></i>'+
                                                                                                '</button>'+
                                                                                            '</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].invoice_no+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].partcode+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].partname+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].supplier+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].app_date+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].app_time+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].app_no+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].lot_no+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].lot_qty+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].type_of_inspection+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].severity_of_inspection+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].inspection_lvl+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].aql+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].accept+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].reject+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].date_ispected+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].ww+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].fy+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].shift+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].time_ins_from+'-'+req[x][y][z][a].time_ins_to+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].inspector+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].submission+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].judgement+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].lot_inspected+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].lot_accepted+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].sample_size+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].no_of_defects+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].classification+'</td>';
                                                                                        gp1 += '<td>'+req[x][y][z][a].remarks+'</td>';
                                                                                    gp1 += "  </tr>";
                                                                                }
                                                                            gp1 += "</tbody>"
                                                                        gp1 += "</table>";
                                                                    gp1 += "</div>";
                                                                gp1 += "</div>";
                                                            gp1 += "</div>";
                                                        gp1 += "</div>";
                                                    }

                                                gp1 += "</div>";
                                            gp1 += "</div>";
                                        gp1 += "</div>";
                                    gp1 += "</div>";
                                }
                    gp1 += "</div>";
                gp1 += "</div>";
            gp1 += "</div>";
        $('#group_by_pane').append(gp1);

        // for(var x=0;x<req.length;x++){
        //     var maintotal = 0, mainreject=0;
        //     for(y=0;y<req[x].length;y++){
        //         var twoaccepted=0, twotal = 0, tworeject=0;
        //         for(z=0;z<req[x][y].length;z++){
        //                 var kup="";
                        
        //                 if(DPPM_3rd[x][y][z]["0"].DPPM != null){
        //                     kup = datas.field3 + ": "+req[x][y][z]["0"].chosenfield3;
        //                     var acc =(req[x][y][z].length == 1)?1:req[x][y][z].length - REJ_3rd[x][y][z]["0"].rejects;
        //                     kup += "LAR : "+LAR_3rd[x][y][z]["0"].LAR+"% ("+acc+"/"+req[x][y][z].length+") &emsp;"
        //                     kup += "DPPM: "+DPPM_3rd[x][y][z]["0"].DPPM+" &emsp;";
        //                     kup += "("+DPPM_3rd[x][y][z]["0"].num_of_defects+"/"+DPPM_3rd[x][y][z]["0"].sample_size+")";
        //                     document.getElementById("kups"+x+y+z).innerHTML = kup;
                      
        //                 }
        //                 else{
        //                     kup += datas.field3 + ": "+req[x][y][z]["0"].chosenfield3+"  &emsp;";
        //                     var acc =(req[x][y][z].length == 1)?1:req[x][y][z].length - REJ_3rd[x][y][z]["0"].rejects;
        //                     kup += "LAR : "+LAR_3rd[x][y][z]["0"].LAR+"% ("+acc+"/"+req[x][y][z].length+") &emsp;"
        //                     kup += "DPPM: 0.00 &emsp;(0/0)</a>";
        //                     document.getElementById("kups"+x+y+z).innerHTML = kup;
                          
        //                 }
        //                 twotal+=req[x][y][z].length;
        //         }
        //         var kupy = "";
        //         var MushRoomHead = GETDPPMthird(req,DPPM_2nd,REJ_2nd,x,y,2);
        //         var n = ((MushRoomHead.poop/MushRoomHead.shit)*1000000 != "NaN")?(MushRoomHead.poop/MushRoomHead.shit)*1000000:0;
        //         var acc = (req[x][y].length == 1)?1:req[x][y].length - REJ_2nd[x][y]["0"].rejects;
        //         var Larc = ((acc/req[x][y].length)*100).toFixed(2);
        //         if(!isNaN(n)){
        //             kupy = datas.field2 + ": "+req[x][y]["0"]["0"].chosenfield2+"  &emsp;";
        //             kupy += "LAR : "+Larc+"% ("+twotal+"/"+twotal+") &emsp;"
        //             kupy += "DPPM: "+n.toFixed(2)+" &emsp;";
        //             kupy += "("+MushRoomHead.poop+"/"+MushRoomHead.shit+")";
        //         }
        //         else{
        //             kupy += datas.field2 + ": "+req[x][y]["0"]["0"].chosenfield2+"  &emsp;"
        //             kupy += "LAR : "+Larc+"% ("+twotal+"/"+twotal+") &emsp;"
        //             kupy += "DPPM: 0.00 &emsp;(0/0)";
        //         }
        //         maintotal += twotal;

        //         document.getElementById("kups"+x+y).innerHTML = kupy;
        //     }

        //     var MushRoomHead = GETDPPMthird(req,DPPM_2nd,REJ_2nd,x,0,1);
        //     var n = ((MushRoomHead.poop/MushRoomHead.shit)*1000000 != "NaN")?(MushRoomHead.poop/MushRoomHead.shit)*1000000:0;
        //     var acc = (req[x].length == 1)?1:req[x].length - REJg1[x]["0"].rejects;
        //     var Larc = ((MushRoomHead.accepted/MushRoomHead.total)*100).toFixed(2);
        //     var gp1="";
        //     if(!isNaN(n)){
        //         gp1 = datas.field1 + ": "+req[x]["0"]["0"]["0"].chosenfield;
        //         gp1 += "LAR : "+Larc+"% ("+maintotal+"/"+maintotal+") &emsp;"
        //         gp1 += "DPPM: "+n.toFixed(2)+" &emsp;";
        //         gp1 += "("+MushRoomHead.poop+"/"+MushRoomHead.shit+")";
        //     }
        //     else{
        //         gp1 = datas.field1 + ": "+req[x]["0"]["0"]["0"].chosenfield;
        //         gp1 += "LAR : "+Larc+"% ("+maintotal+"/"+maintotal+") &emsp;"
        //         gp1 += "DPPM: 0.00 &emsp;(0/0)";
        //     }
        //     document.getElementById("kups"+x).innerHTML = gp1;
        // }
    }
    closeloading();
}

function guidGenerator() {
    var S4 = function() {
       return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    };
    return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}


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
    $('#group_by_pane').html('<button class="btn btn-danger btn-sm pull-right" id="btn_close_groupby">'+
                    '<i class="fa fa-times"></i> Close'+
                '</button><br><br>');
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
                    details += '<table style="font-size:10px" class="table table-condensed table-borderd">';
                    details += '<thead>';
                    details += '<tr>';
                    details += '<td></td>';
                    details += '<td></td>';
                    details += '<td><strong>Invoice No.</strong></td>';
                    details += '<td><strong>Part Code</strong></td>';
                    details += '<td><strong>Part Name</strong></td>';
                    details += '<td><strong>Supplier</strong></td>';
                    details += '<td><strong>App. Date</strong></td>';
                    details += '<td><strong>App. Time</strong></td>';
                    details += '<td><strong>App. No.</strong></td>';
                    details += '<td><strong>Lot No.</strong></td>';
                    details += '<td><strong>Lot Qty.</strong></td>';
                    details += '<td><strong>Type of Inspection</strong></td>';
                    details += '<td><strong>Severity of Inspection</strong></td>';
                    details += '<td><strong>Inspection Lvl</strong></td>';
                    details += '<td><strong>AQL</strong></td>';
                    details += '<td><strong>Accept</strong></td>';
                    details += '<td><strong>Reject</strong></td>';
                    details += '<td><strong>Date Inspected</strong></td>';
                    details += '<td><strong>WW</strong></td>';
                    details += '<td><strong>FY</strong></td>';
                    details += '<td><strong>Shift</strong></td>';
                    details += '<td><strong>Time Inspected</strong></td>';
                    details += '<td><strong>Inspector</strong></td>';
                    details += '<td><strong>Submission</strong></td>';
                    details += '<td><strong>Judgement</strong></td>';
                    details += '<td><strong>Lot Inspected</strong></td>';
                    details += '<td><strong>Lot Accepted</strong></td>';
                    details += '<td><strong>Sample Size</strong></td>';
                    details += '<td><strong>No. of Defects</strong></td>';
                    details += '<td><strong>Remarks</strong></td>';
                    details += '<td><strong>Classification</strong></td>';
                    details += '</tr>';
                    details += '</thead>';
                    details += '<tbody id="details_tbody">';

                    var cnt = 1;

                    $.each(xx.details, function(iii,xxx) {
                        
                        details_body += '<tr>';
                        details_body += '<td>'+cnt+'</td>';
                        details_body += '<td><button class="btn btn-sm view_inspection blue" data-id="'+xxx.id+'"'+ 
                                                'data-invoice_no="'+xxx.invoice_no+'" '+
                                                'data-partcode="'+xxx.partcode+'" '+
                                                'data-partname="'+xxx.partname+'" '+
                                                'data-supplier="'+xxx.supplier+'" '+
                                                'data-app_date="'+xxx.app_date+'" '+
                                                'data-app_time="'+xxx.app_time+'" '+
                                                'data-app_no="'+xxx.app_no+'" '+
                                                'data-lot_no="'+xxx.lot_no+'" '+
                                                'data-lot_qty="'+xxx.lot_qty+'" '+
                                                'data-type_of_inspection="'+xxx.type_of_inspection+'" '+
                                                'data-severity_of_inspection="'+xxx.severity_of_inspection+'" '+
                                                'data-inspection_lvl="'+xxx.inspection_lvl+'" '+
                                                'data-aql="'+xxx.aql+'" '+
                                                'data-accept="'+xxx.accept+'" '+
                                                'data-reject="'+xxx.reject+'" '+
                                                'data-date_ispected="'+xxx.date_ispected+'" '+
                                                'data-ww="'+xxx.ww+'" '+
                                                'data-fy="'+xxx.fy+'" '+
                                                'data-shift="'+xxx.shift+'" '+
                                                'data-time_ins_from="'+xxx.time_ins_from+'" '+
                                                'data-time_ins_to="'+xxx.time_ins_to+'"'+
                                                'data-inspector="'+xxx.inspector+'" '+
                                                'data-submission="'+xxx.submission+'" '+
                                                'data-judgement="'+xxx.judgement+'" '+
                                                'data-lot_inspected="'+xxx.lot_inspected+'" '+
                                                'data-lot_accepted="'+xxx.lot_accepted+'" '+
                                                'data-sample_size="'+xxx.sample_size+'" '+
                                                'data-no_of_defects="'+xxx.no_of_defects+'" '+
                                                'data-classification="'+xxx.classification+'" '+
                                                'data-remarks="'+xxx.remarks+'">'+
                                                '<i class="fa fa-edit"></i>'+
                                            '</button>'+
                                        '</td>';
                        details_body += '<td>'+xxx.invoice_no+'</td>';
                        details_body += '<td>'+xxx.partcode+'</td>';
                        details_body += '<td>'+xxx.partname+'</td>';
                        details_body += '<td>'+xxx.supplier+'</td>';
                        details_body += '<td>'+xxx.app_date+'</td>';
                        details_body += '<td>'+xxx.app_time+'</td>';
                        details_body += '<td>'+xxx.app_no+'</td>';
                        details_body += '<td>'+xxx.lot_no+'</td>';
                        details_body += '<td>'+xxx.lot_qty+'</td>';
                        details_body += '<td>'+xxx.type_of_inspection+'</td>';
                        details_body += '<td>'+xxx.severity_of_inspection+'</td>';
                        details_body += '<td>'+xxx.inspection_lvl+'</td>';
                        details_body += '<td>'+xxx.aql+'</td>';
                        details_body += '<td>'+xxx.accept+'</td>';
                        details_body += '<td>'+xxx.reject+'</td>';
                        details_body += '<td>'+xxx.date_ispected+'</td>';
                        details_body += '<td>'+xxx.ww+'</td>';
                        details_body += '<td>'+xxx.fy+'</td>';
                        details_body += '<td>'+xxx.shift+'</td>';
                        details_body += '<td>'+xxx.time_ins_from+'-'+xxx.time_ins_to+'</td>';
                        details_body += '<td>'+xxx.inspector+'</td>';
                        details_body += '<td>'+xxx.submission+'</td>';
                        details_body += '<td>'+xxx.judgement+'</td>';
                        details_body += '<td>'+xxx.lot_inspected+'</td>';
                        details_body += '<td>'+xxx.lot_accepted+'</td>';
                        details_body += '<td>'+xxx.sample_size+'</td>';
                        details_body += '<td>'+xxx.no_of_defects+'</td>';
                        details_body += '<td>'+xxx.classification+'</td>';
                        details_body += '<td>'+xxx.remarks+'</td>';
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
                    details += '<td><strong>Invoice No.</strong></td>';
                    details += '<td><strong>Part Code</strong></td>';
                    details += '<td><strong>Part Name</strong></td>';
                    details += '<td><strong>Supplier</strong></td>';
                    details += '<td><strong>App. Date</strong></td>';
                    details += '<td><strong>App. Time</strong></td>';
                    details += '<td><strong>App. No.</strong></td>';
                    details += '<td><strong>Lot No.</strong></td>';
                    details += '<td><strong>Lot Qty.</strong></td>';
                    details += '<td><strong>Type of Inspection</strong></td>';
                    details += '<td><strong>Severity of Inspection</strong></td>';
                    details += '<td><strong>Inspection Lvl</strong></td>';
                    details += '<td><strong>AQL</strong></td>';
                    details += '<td><strong>Accept</strong></td>';
                    details += '<td><strong>Reject</strong></td>';
                    details += '<td><strong>Date Inspected</strong></td>';
                    details += '<td><strong>WW</strong></td>';
                    details += '<td><strong>FY</strong></td>';
                    details += '<td><strong>Shift</strong></td>';
                    details += '<td><strong>Time Inspected</strong></td>';
                    details += '<td><strong>Inspector</strong></td>';
                    details += '<td><strong>Submission</strong></td>';
                    details += '<td><strong>Judgement</strong></td>';
                    details += '<td><strong>Lot Inspected</strong></td>';
                    details += '<td><strong>Lot Accepted</strong></td>';
                    details += '<td><strong>Sample Size</strong></td>';
                    details += '<td><strong>No. of Defects</strong></td>';
                    details += '<td><strong>Remarks</strong></td>';
                    details += '<td><strong>Classification</strong></td>';
                    details += '</tr>';
                    details += '</thead>';
                    details += '<tbody id="details_tbody">';
                    var cnt = 1;

                    $.each(xx.details, function(iii,xxx) {
                        
                        details_body += '<tr>';
                        details_body += '<td>'+cnt+'</td>';
                        details_body += '<td><button class="btn btn-sm view_inspection blue" data-id="'+xxx.id+'"'+ 
                                                'data-invoice_no="'+xxx.invoice_no+'"'+
                                                'data-partcode="'+xxx.partcode+'"'+
                                                'data-partname="'+xxx.partname+'"'+
                                                'data-supplier="'+xxx.supplier+'"'+
                                                'data-app_date="'+xxx.app_date+'"'+
                                                'data-app_time="'+xxx.app_time+'"'+
                                                'data-app_no="'+xxx.app_no+'"'+
                                                'data-lot_no="'+xxx.lot_no+'"'+
                                                'data-lot_qty="'+xxx.lot_qty+'"'+
                                                'data-type_of_inspection="'+xxx.type_of_inspection+'"'+
                                                'data-severity_of_inspection="'+xxx.severity_of_inspection+'"'+
                                                'data-inspection_lvl="'+xxx.inspection_lvl+'"'+
                                                'data-aql="'+xxx.aql+'"'+
                                                'data-accept="'+xxx.accept+'"'+
                                                'data-reject="'+xxx.reject+'"'+
                                                'data-date_ispected="'+xxx.date_ispected+'"'+
                                                'data-ww="'+xxx.ww+'"'+
                                                'data-fy="'+xxx.fy+'"'+
                                                'data-shift="'+xxx.shift+'"'+
                                                'data-time_ins_from="'+xxx.time_ins_from+'"'+
                                                'data-time_ins_to="'+xxx.time_ins_to+'"'+
                                                'data-inspector="'+xxx.inspector+'"'+
                                                'data-submission="'+xxx.submission+'"'+
                                                'data-judgement="'+xxx.judgement+'"'+
                                                'data-lot_inspected="'+xxx.lot_inspected+'"'+
                                                'data-lot_accepted="'+xxx.lot_accepted+'"'+
                                                'data-sample_size="'+xxx.sample_size+'"'+
                                                'data-no_of_defects="'+xxx.no_of_defects+'"'+
                                                'data-classification="'+xxx.classification+'"'+
                                                'data-remarks="'+xxx.remarks+'">'+
                                                '<i class="fa fa-edit"></i>'+
                                            '</button>'+
                                        '</td>';
                        details_body += '<td>'+xxx.invoice_no+'</td>';
                        details_body += '<td>'+xxx.partcode+'</td>';
                        details_body += '<td>'+xxx.partname+'</td>';
                        details_body += '<td>'+xxx.supplier+'</td>';
                        details_body += '<td>'+xxx.app_date+'</td>';
                        details_body += '<td>'+xxx.app_time+'</td>';
                        details_body += '<td>'+xxx.app_no+'</td>';
                        details_body += '<td>'+xxx.lot_no+'</td>';
                        details_body += '<td>'+xxx.lot_qty+'</td>';
                        details_body += '<td>'+xxx.type_of_inspection+'</td>';
                        details_body += '<td>'+xxx.severity_of_inspection+'</td>';
                        details_body += '<td>'+xxx.inspection_lvl+'</td>';
                        details_body += '<td>'+xxx.aql+'</td>';
                        details_body += '<td>'+xxx.accept+'</td>';
                        details_body += '<td>'+xxx.reject+'</td>';
                        details_body += '<td>'+xxx.date_ispected+'</td>';
                        details_body += '<td>'+xxx.ww+'</td>';
                        details_body += '<td>'+xxx.fy+'</td>';
                        details_body += '<td>'+xxx.shift+'</td>';
                        details_body += '<td>'+xxx.time_ins_from+'-'+xxx.time_ins_to+'</td>';
                        details_body += '<td>'+xxx.inspector+'</td>';
                        details_body += '<td>'+xxx.submission+'</td>';
                        details_body += '<td>'+xxx.judgement+'</td>';
                        details_body += '<td>'+xxx.lot_inspected+'</td>';
                        details_body += '<td>'+xxx.lot_accepted+'</td>';
                        details_body += '<td>'+xxx.sample_size+'</td>';
                        details_body += '<td>'+xxx.no_of_defects+'</td>';
                        details_body += '<td>'+xxx.classification+'</td>';
                        details_body += '<td>'+xxx.remarks+'</td>';
                        
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
                    details += '<td><strong>Invoice No.</strong></td>';
                    details += '<td><strong>Part Code</strong></td>';
                    details += '<td><strong>Part Name</strong></td>';
                    details += '<td><strong>Supplier</strong></td>';
                    details += '<td><strong>App. Date</strong></td>';
                    details += '<td><strong>App. Time</strong></td>';
                    details += '<td><strong>App. No.</strong></td>';
                    details += '<td><strong>Lot No.</strong></td>';
                    details += '<td><strong>Lot Qty.</strong></td>';
                    details += '<td><strong>Type of Inspection</strong></td>';
                    details += '<td><strong>Severity of Inspection</strong></td>';
                    details += '<td><strong>Inspection Lvl</strong></td>';
                    details += '<td><strong>AQL</strong></td>';
                    details += '<td><strong>Accept</strong></td>';
                    details += '<td><strong>Reject</strong></td>';
                    details += '<td><strong>Date Inspected</strong></td>';
                    details += '<td><strong>WW</strong></td>';
                    details += '<td><strong>FY</strong></td>';
                    details += '<td><strong>Shift</strong></td>';
                    details += '<td><strong>Time Inspected</strong></td>';
                    details += '<td><strong>Inspector</strong></td>';
                    details += '<td><strong>Submission</strong></td>';
                    details += '<td><strong>Judgement</strong></td>';
                    details += '<td><strong>Lot Inspected</strong></td>';
                    details += '<td><strong>Lot Accepted</strong></td>';
                    details += '<td><strong>Sample Size</strong></td>';
                    details += '<td><strong>No. of Defects</strong></td>';
                    details += '<td><strong>Remarks</strong></td>';
                    details += '<td><strong>Classification</strong></td>';
                    details += '</tr>';
                    details += '</thead>';
                    details += '<tbody id="details_tbody">';

                    var cnt = 1;

                    $.each(xx.details, function(iii,xxx) {
                        
                        details_body += '<tr>';
                        details_body += '<td>'+cnt+'</td>';
                        details_body += '<td><button class="btn btn-sm view_inspection blue" data-id="'+xxx.id+'"'+ 
                                                'data-invoice_no="'+xxx.invoice_no+'" '+
                                                'data-partcode="'+xxx.partcode+'" '+
                                                'data-partname="'+xxx.partname+'" '+
                                                'data-supplier="'+xxx.supplier+'" '+
                                                'data-app_date="'+xxx.app_date+'" '+
                                                'data-app_time="'+xxx.app_time+'" '+
                                                'data-app_no="'+xxx.app_no+'" '+
                                                'data-lot_no="'+xxx.lot_no+'" '+
                                                'data-lot_qty="'+xxx.lot_qty+'" '+
                                                'data-type_of_inspection="'+xxx.type_of_inspection+'" '+
                                                'data-severity_of_inspection="'+xxx.severity_of_inspection+'" '+
                                                'data-inspection_lvl="'+xxx.inspection_lvl+'" '+
                                                'data-aql="'+xxx.aql+'" '+
                                                'data-accept="'+xxx.accept+'" '+
                                                'data-reject="'+xxx.reject+'" '+
                                                'data-date_ispected="'+xxx.date_ispected+'" '+
                                                'data-ww="'+xxx.ww+'" '+
                                                'data-fy="'+xxx.fy+'" '+
                                                'data-shift="'+xxx.shift+'" '+
                                                'data-time_ins_from="'+xxx.time_ins_from+'" '+
                                                'data-time_ins_to="'+xxx.time_ins_to+'"'+
                                                'data-inspector="'+xxx.inspector+'" '+
                                                'data-submission="'+xxx.submission+'" '+
                                                'data-judgement="'+xxx.judgement+'" '+
                                                'data-lot_inspected="'+xxx.lot_inspected+'" '+
                                                'data-lot_accepted="'+xxx.lot_accepted+'" '+
                                                'data-sample_size="'+xxx.sample_size+'" '+
                                                'data-no_of_defects="'+xxx.no_of_defects+'" '+
                                                'data-classification="'+xxx.classification+'" '+
                                                'data-remarks="'+xxx.remarks+'"><i class="fa fa-edit"></i>'+
                                            '</button>'+
                                        '</td>';
                        details_body += '<td>'+xxx.invoice_no+'</td>';
                        details_body += '<td>'+xxx.partcode+'</td>';
                        details_body += '<td>'+xxx.partname+'</td>';
                        details_body += '<td>'+xxx.supplier+'</td>';
                        details_body += '<td>'+xxx.app_date+'</td>';
                        details_body += '<td>'+xxx.app_time+'</td>';
                        details_body += '<td>'+xxx.app_no+'</td>';
                        details_body += '<td>'+xxx.lot_no+'</td>';
                        details_body += '<td>'+xxx.lot_qty+'</td>';
                        details_body += '<td>'+xxx.type_of_inspection+'</td>';
                        details_body += '<td>'+xxx.severity_of_inspection+'</td>';
                        details_body += '<td>'+xxx.inspection_lvl+'</td>';
                        details_body += '<td>'+xxx.aql+'</td>';
                        details_body += '<td>'+xxx.accept+'</td>';
                        details_body += '<td>'+xxx.reject+'</td>';
                        details_body += '<td>'+xxx.date_ispected+'</td>';
                        details_body += '<td>'+xxx.ww+'</td>';
                        details_body += '<td>'+xxx.fy+'</td>';
                        details_body += '<td>'+xxx.shift+'</td>';
                        details_body += '<td>'+xxx.time_ins_from+'-'+xxx.time_ins_to+'</td>';
                        details_body += '<td>'+xxx.inspector+'</td>';
                        details_body += '<td>'+xxx.submission+'</td>';
                        details_body += '<td>'+xxx.judgement+'</td>';
                        details_body += '<td>'+xxx.lot_inspected+'</td>';
                        details_body += '<td>'+xxx.lot_accepted+'</td>';
                        details_body += '<td>'+xxx.sample_size+'</td>';
                        details_body += '<td>'+xxx.no_of_defects+'</td>';
                        details_body += '<td>'+xxx.classification+'</td>';
                        details_body += '<td>'+xxx.remarks+'</td>';
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

function getNumOfDefectives(invoice_no,partcode) {
    $.ajax({
        url: getNumOfDefectivesURL,
        type: 'GET',
        dataType: 'JSON',
        data: {
            _token:token,
            invoice_no:invoice_no,
            partcode:partcode
        }
    }).done(function(data,xhr,textStatus) {
        $('#no_of_defects').val(data);
        if (data > 0) {
            $('#lot_accepted').val(0);
        }
        checkLotAccepted($(this).attr('data-lot_accepted'),data);
    }).fail(function(data,xhr,textStatus) {
        msg("There was an error while calculating",'error');
    });
}
