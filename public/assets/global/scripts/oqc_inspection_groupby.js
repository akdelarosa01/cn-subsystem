$( function() {
    disabledContent();
    $('#field1').on('change',function(){
        if($('#field1').val() != ""){
            if($('#field1').val() == $('#field2').val() || $('#field1').val() == $('#field3').val()){
                msg("Duplicate Grouping","failed");
            }
            else{
                GroupByValues($(this).val(),$('#content1'));
                $("#content1").prop('disabled', false);
                $("#field2").prop('disabled', false);
                $("#calID").prop('disabled', false);
                
            }
        }
        else{
            disabledContent2();
        }
    });

    $('#field2').on('change',function(){
        if($('#field2').val() != ""){
            if($('#field1').val() == $('#field2').val() || $('#field2').val() == $('#field3').val()){
                msg("Duplicate Grouping","failed");
            }
            else{
                GroupByValues($(this).val(),$('#content2'));
                $("#content2").prop('disabled', false);
                $("#field3").prop('disabled', false);
            }
        }
        else{
            disabledContent2();
        }
    });

    $('#field3').on('change',function(){
        if($('#field3').val() != ""){
            if($('#field3').val() == $('#field2').val() || $('#field1').val() == $('#field3').val()){
                msg("Duplicate Grouping","failed");
            }
            else{
                GroupByValues($(this).val(),$('#content3'));
                $("#content3").prop('disabled', false);
            }
        }
        else{
            disabledContent2();
        }
    });

    $('#content2').on('change',function(){
        if($("#content1").val() == "")
        {
            msg("Please provide Data for First Group","failed");
            $("#content2").val('');
            $("#content2").prop('disabled', true);
        }
    });

    $('#content3').on('change',function(){
        if($("#content1").val() == "" || $("#content2").val() == "")
        {
            msg("Please provide Data for Group","failed");
            $("#content2").val('');
            $("#content2").prop('disabled', true);
            $("#content3").val('');
            $("#content3").prop('disabled', true);
        }
    });

    $('#gto').on('change',function(){
        if($("#gfrom").val() != "" || $("#gto").val() != "")
        {
            $("#field1").prop('disabled', false);
        }
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
                                '<button class="btn btn-danger btn-sm" id="btn_close_groupby">'+
                                    '<i class="fa fa-times"></i> Close'+
                                '</button>'+
                                '<button class="btn btn-info btn-sm" id="btn_pdf_groupby">'+
                                    '<i class="fa fa-file-pdf-o"></i> PDF'+
                                '</button>'+
                                '<button class="btn btn-success btn-sm" id="btn_excel_groupby">'+
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
                        $.ajax({
                                url: GetSingleGroupByURL,
                                type: 'GET',
                                dataType: 'JSON',
                                data:{ _token:token,
                                        data:form,
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
                                        data:form,
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
                                                                    content1:g1,
                                                                    content2:g2,
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
                                                        $.ajax({
                                                        url: GettripleGroupByURLdetails,
                                                        type: 'GET',
                                                        dataType: 'JSON',
                                                        data:{ _token:token,
                                                                content1:g1,
                                                                content2:g2,
                                                                content3:g3,
                                                                firstData:desFirst.field1,
                                                                secondData:desFirst.field2,
                                                                thirdData:desFirst.field3,
                                                                gto:desFirst.gto,
                                                                gfrom:desFirst.gfrom,
                                                        },
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
        disabledContent2();
    });

    $('#btn_pdf_groupby').live('click', function() {
        window.location.href=PDFGroupByReportURL;
    });

    $('#btn_excel_groupby').live('click', function() {
        window.location.href=ExcelGroupByReportURL;
    });
});

function disabledContent(){
    $("#field1").prop('disabled', true);
    $("#field1").val('');
    $("#field2").prop('disabled', true);
    $("#field2").val('');
    $("#field3").prop('disabled', true);
    $("#field3").val('');
    $("#content1").prop('disabled', true);
    $("#content1").val('');
    $("#content2").prop('disabled', true);
    $("#content2").val('');
    $("#content3").prop('disabled', true);
    $("#content3").val('');
    $("#calID").prop('disabled', true);
}

function disabledContent2(){
    $("#field2").prop('disabled', true);
    $("#field2").val('');
    $("#field3").prop('disabled', true);
    $("#field3").val('');
    $("#content1").prop('disabled', true);
    $("#content1").val('');
    $("#content2").prop('disabled', true);
    $("#content2").val('');
    $("#content3").prop('disabled', true);
    $("#content3").val('');
    $("#calID").prop('disabled', true);
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
                            gp1 += "("+DPPM[x]["0"].num_of_defects+"/"+DPPM[x]["0"].sample_size+")</a>";
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
                                        gp1 += "<td><strong>PO</strong></td>";
                                        gp1 += "<td><strong>Device Name</strong></td>";
                                        gp1 += "<td><strong>Customer</strong></td>";
                                        gp1 += "<td><strong>PO Qty</strong></td>";
                                        gp1 += "<td><strong>Family</strong></td>";
                                        gp1 += "<td><strong>Assembly Line</strong></td>";
                                        gp1 += "<td><strong>Lot No</strong></td>";
                                        gp1 += "<td><strong>App. Date</strong></td>";
                                        gp1 += "<td><strong>App. Time</strong></td>";
                                        gp1 += "<td><strong>Category</strong></td>";
                                        gp1 += "<td><strong>Type of Inspection</strong></td>";
                                        gp1 += "<td><strong>Severity of Inspection</strong></td>";
                                        gp1 += "<td><strong>Inspection Level</strong></td>";
                                        gp1 += "<td><strong>AQL</strong></td>";
                                        gp1 += "<td><strong>Accept</strong></td>";
                                        gp1 += "<td><strong>Reject</strong></td>";
                                        gp1 += "<td><strong>Date inspected</strong></td>";
                                        gp1 += "<td><strong>WW</strong></td>";
                                        gp1 += "<td><strong>FY</strong></td>";
                                        gp1 += "<td><strong>Time Inspected</strong></td>";
                                        gp1 += "<td><strong>Shift</strong></td>";
                                        gp1 += "<td><strong>Inspector</strong></td>";
                                        gp1 += "<td><strong>Submission</strong></td>";
                                        gp1 += "<td><strong>COC Requirement</strong></td>";
                                        gp1 += "<td><strong>Judgement</strong></td>";
                                        gp1 += "<td><strong>Lot Qty</strong></td>";
                                        gp1 += "<td><strong>Sample Size</strong></td>";
                                        gp1 += "<td><strong>Lot Inspected</strong></td>";
                                        gp1 += "<td><strong>Lot Accepted</strong></td>";
                                        gp1 += "<td><strong>No. of Defects</strong></td>";
                                        gp1 += "<td><strong>Remarks</strong></td>";
                                        gp1 += "<td><strong>Type</strong></td>";
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
                                            gp1 += "<td><button class='btn btn-sm view_inspection blue' ";
                                                gp1 += "data-id='"+details[x][y].id+"' ";
                                                gp1 += "data-assembly_line='"+details[x][y].assembly_line+"' ";
                                                gp1 += "data-lot_no='"+details[x][y].lot_no+"' ";
                                                gp1 += "data-app_date='"+details[x][y].app_date+"' ";
                                                gp1 += "data-app_time='"+details[x][y].app_time+"' ";
                                                gp1 += "data-prod_category='"+details[x][y].prod_category+"' ";
                                                gp1 += "data-po_no='"+details[x][y].po_no+"' ";
                                                gp1 += "data-device_name='"+details[x][y].device_name+"' ";
                                                gp1 += "data-customer='"+details[x][y].customer+"' ";
                                                gp1 += "data-po_qty='"+details[x][y].po_qty+"' ";
                                                gp1 += "data-family='"+details[x][y].family+"' ";
                                                gp1 += "data-type_of_inspection='"+details[x][y].type_of_inspection+"' ";
                                                gp1 += "data-severity_of_inspection='"+details[x][y].severity_of_inspection+"' ";
                                                gp1 += "data-inspection_lvl='"+details[x][y].inspection_lvl+"' ";
                                                gp1 += "data-aql='"+details[x][y].aql+"' ";
                                                gp1 += "data-accept='"+details[x][y].accept+"' ";
                                                gp1 += "data-reject='"+details[x][y].reject+"' ";
                                                gp1 += "data-date_inspected='"+details[x][y].date_inspected+"' ";
                                                gp1 += "data-ww='"+details[x][y].ww+"' ";
                                                gp1 += "data-fy='"+details[x][y].fy+"' ";
                                                gp1 += "data-time_ins_from='"+details[x][y].time_ins_from+"' ";
                                                gp1 += "data-time_ins_to='"+details[x][y].time_ins_to+"' ";
                                                gp1 += "data-shift='"+details[x][y].shift+"' ";
                                                gp1 += "data-inspector='"+details[x][y].inspector+"' ";
                                                gp1 += "data-submission='"+details[x][y].submission+"' ";
                                                gp1 += "data-coc_req='"+details[x][y].coc_req+"' ";
                                                gp1 += "data-judgement='"+details[x][y].judgement+"' ";
                                                gp1 += "data-lot_qty='"+details[x][y].lot_qty+"' ";
                                                gp1 += "data-sample_size='"+details[x][y].sample_size+"' ";
                                                gp1 += "data-lot_inspected='"+details[x][y].lot_inspected+"' ";
                                                gp1 += "data-lot_accepted='"+details[x][y].lot_accepted+"' ";
                                                gp1 += "data-num_of_defects='"+details[x][y].num_of_defects+"' ";
                                                gp1 += "data-remarks='"+details[x][y].remarks+"'> ";
                                                gp1 += "<i class='fa fa-edit'>";
                                            gp1 += "</i></button></td>";
                                       
                                            gp1 += "<td>"+details[x][y].po_no+"</td>";
                                            gp1 += "<td>"+details[x][y].device_name+"</td>";
                                            gp1 += "<td>"+details[x][y].customer+"</td>";
                                            gp1 += "<td>"+details[x][y].po_qty+"</td>";
                                            gp1 += "<td>"+details[x][y].family+"</td>";
                                            gp1 += "<td>"+details[x][y].assembly_line+"</td>";
                                            gp1 += "<td>"+details[x][y].lot_no+"</td>";
                                            gp1 += "<td>"+details[x][y].app_date+"</td>";
                                            gp1 += "<td>"+details[x][y].app_time+"</td>";
                                            gp1 += "<td>"+details[x][y].prod_category+"</td>";
                                            gp1 += "<td>"+details[x][y].type_of_inspection+" </td>";
                                            gp1 += "<td>"+details[x][y].severity_of_inspection+"</td>";
                                            gp1 += "<td>"+details[x][y].inspection_lvl+"</td>";
                                            gp1 += "<td>"+details[x][y].aql+"</td>";
                                            gp1 += "<td>"+details[x][y].accept+"</td>";
                                            gp1 += "<td>"+details[x][y].reject+"</td>";
                                            gp1 += "<td>"+details[x][y].date_inspected+"</td>";
                                            gp1 += "<td>"+details[x][y].ww+"</td>";
                                            gp1 += "<td>"+details[x][y].fy+"</td>";
                                            gp1 += "<td>"+details[x][y].time_ins_from+"</td>";
                                            gp1 += "<td>"+details[x][y].shift+"</td>";
                                            gp1 += "<td>"+details[x][y].inspector+"</td>";
                                            gp1 += "<td>"+details[x][y].submission+"</td>";
                                            gp1 += "<td>"+details[x][y].coc_req+"</td>";
                                            gp1 += "<td>"+details[x][y].judgement+"</td>";
                                            gp1 += "<td>"+details[x][y].lot_qty+"</td>";
                                            gp1 += "<td>"+details[x][y].sample_size+"</td>";
                                            gp1 += "<td>"+details[x][y].lot_inspected+"</td>";
                                            gp1 += "<td>"+details[x][y].lot_accepted+"</td>";
                                            gp1 += "<td>"+details[x][y].num_of_defects+"</td>";
                                            gp1 += "<td>"+details[x][y].remarks+"</td>";
                                            gp1 += "<td>"+details[x][y].type+"</td>";
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
            poo = (DPPM[x][y]["0"].num_of_defects == null)?0:DPPM[x][y]["0"].num_of_defects;
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
                        poo = (DPPM[x1][y1]["0"].num_of_defects == null)?0:DPPM[x1][y1]["0"].num_of_defects;
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
                        poo = (DPPM[x1][y1]["0"].num_of_defects == null)?0:DPPM[x1][y1]["0"].num_of_defects;
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
                                                        var nd = DPPM[x][y]["0"].num_of_defects;
                                                        var ss = DPPM[x][y]["0"].sample_size;
                                                        gp1 += "("+DPPM[x][y]["0"].num_of_defects+"/"+DPPM[x][y]["0"].sample_size+")</a>";
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
                                                                gp1 += "<td><strong>PO</strong></td>";
                                                                gp1 += "<td><strong>Device Name</strong></td>";
                                                                gp1 += "<td><strong>Customer</strong></td>";
                                                                gp1 += "<td><strong>PO Qty</strong></td>";
                                                                gp1 += "<td><strong>Family</strong></td>";
                                                                gp1 += "<td><strong>Assembly Line</strong></td>";
                                                                gp1 += "<td><strong>Lot No</strong></td>";
                                                                gp1 += "<td><strong>App. Date</strong></td>";
                                                                gp1 += "<td><strong>App. Time</strong></td>";
                                                                gp1 += "<td><strong>Category</strong></td>";
                                                                gp1 += "<td><strong>Type of Inspection</strong></td>";
                                                                gp1 += "<td><strong>Severity of Inspection</strong></td>";
                                                                gp1 += "<td><strong>Inspection Level</strong></td>";
                                                                gp1 += "<td><strong>AQL</strong></td>";
                                                                gp1 += "<td><strong>Accept</strong></td>";
                                                                gp1 += "<td><strong>Reject</strong></td>";
                                                                gp1 += "<td><strong>Date inspected</strong></td>";
                                                                gp1 += "<td><strong>WW</strong></td>";
                                                                gp1 += "<td><strong>FY</strong></td>";
                                                                gp1 += "<td><strong>Time Inspected</strong></td>";
                                                                gp1 += "<td><strong>Shift</strong></td>";
                                                                gp1 += "<td><strong>Inspector</strong></td>";
                                                                gp1 += "<td><strong>Submission</strong></td>";
                                                                gp1 += "<td><strong>COC Requirement</strong></td>";
                                                                gp1 += "<td><strong>Judgement</strong></td>";
                                                                gp1 += "<td><strong>Lot Qty</strong></td>";
                                                                gp1 += "<td><strong>Sample Size</strong></td>";
                                                                gp1 += "<td><strong>Lot Inspected</strong></td>";
                                                                gp1 += "<td><strong>Lot Accepted</strong></td>";
                                                                gp1 += "<td><strong>No. of Defects</strong></td>";
                                                                gp1 += "<td><strong>Remarks</strong></td>";
                                                                gp1 += "<td><strong>Type</strong></td>";
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
                                                                    gp1 += "<td><button class='btn btn-sm view_inspection blue' ";
                                                                        gp1 += "data-id='"+req[x][y][z].id+"' ";
                                                                        gp1 += "data-assembly_line='"+req[x][y][z].assembly_line+"' ";
                                                                        gp1 += "data-lot_no='"+req[x][y][z].lot_no+"' ";
                                                                        gp1 += "data-app_date='"+req[x][y][z].app_date+"' ";
                                                                        gp1 += "data-app_time='"+req[x][y][z].app_time+"' ";
                                                                        gp1 += "data-prod_category='"+req[x][y][z].prod_category+"' ";
                                                                        gp1 += "data-po_no='"+req[x][y][z].po_no+"' ";
                                                                        gp1 += "data-device_name='"+req[x][y][z].device_name+"' ";
                                                                        gp1 += "data-customer='"+req[x][y][z].customer+"' ";
                                                                        gp1 += "data-po_qty='"+req[x][y][z].po_qty+"' ";
                                                                        gp1 += "data-family='"+req[x][y][z].family+"' ";
                                                                        gp1 += "data-type_of_inspection='"+req[x][y][z].type_of_inspection+"' ";
                                                                        gp1 += "data-severity_of_inspection='"+req[x][y][z].severity_of_inspection+"' ";
                                                                        gp1 += "data-inspection_lvl='"+req[x][y][z].inspection_lvl+"' ";
                                                                        gp1 += "data-aql='"+req[x][y][z].aql+"' ";
                                                                        gp1 += "data-accept='"+req[x][y][z].accept+"' ";
                                                                        gp1 += "data-reject='"+req[x][y][z].reject+"' ";
                                                                        gp1 += "data-date_inspected='"+req[x][y][z].date_inspected+"' ";
                                                                        gp1 += "data-ww='"+req[x][y][z].ww+"' ";
                                                                        gp1 += "data-fy='"+req[x][y][z].fy+"' ";
                                                                        gp1 += "data-time_ins_from='"+req[x][y][z].time_ins_from+"' ";
                                                                        gp1 += "data-time_ins_to='"+req[x][y][z].time_ins_to+"' ";
                                                                        gp1 += "data-shift='"+req[x][y][z].shift+"' ";
                                                                        gp1 += "data-inspector='"+req[x][y][z].inspector+"' ";
                                                                        gp1 += "data-submission='"+req[x][y][z].submission+"' ";
                                                                        gp1 += "data-coc_req='"+req[x][y][z].coc_req+"' ";
                                                                        gp1 += "data-judgement='"+req[x][y][z].judgement+"' ";
                                                                        gp1 += "data-lot_qty='"+req[x][y][z].lot_qty+"' ";
                                                                        gp1 += "data-sample_size='"+req[x][y][z].sample_size+"' ";
                                                                        gp1 += "data-lot_inspected='"+req[x][y][z].lot_inspected+"' ";
                                                                        gp1 += "data-lot_accepted='"+req[x][y][z].lot_accepted+"' ";
                                                                        gp1 += "data-num_of_defects='"+req[x][y][z].num_of_defects+"' ";
                                                                        gp1 += "data-remarks='"+req[x][y][z].remarks+"'> ";
                                                                        gp1 += "<i class='fa fa-edit'>";
                                                                    gp1 += "</i></button></td>";
                                                                    gp1 += "<td>"+req[x][y][z].po_no+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].device_name+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].customer+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].po_qty+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].family+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].assembly_line+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].lot_no+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].app_date+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].app_time+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].prod_category+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].type_of_inspection+" </td>";
                                                                    gp1 += "<td>"+req[x][y][z].severity_of_inspection+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].inspection_lvl+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].aql+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].accept+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].reject+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].date_inspected+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].ww+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].fy+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].time_ins_from+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].shift+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].inspector+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].submission+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].coc_req+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].judgement+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].lot_qty+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].sample_size+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].lot_inspected+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].lot_accepted+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].num_of_defects+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].remarks+"</td>";
                                                                    gp1 += "<td>"+req[x][y][z].type+"</td>";
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
                                                                            gp1 += "("+DPPM_3rd[x][y][z]["0"].num_of_defects+"/"+DPPM_3rd[x][y][z]["0"].sample_size+")</a>";
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
                                                                                    gp1 += "<td><strong>PO</strong></td>";
                                                                                    gp1 += "<td><strong>Device Name</strong></td>";
                                                                                    gp1 += "<td><strong>Customer</strong></td>";
                                                                                    gp1 += "<td><strong>PO Qty</strong></td>";
                                                                                    gp1 += "<td><strong>Family</strong></td>";
                                                                                    gp1 += "<td><strong>Assembly Line</strong></td>";
                                                                                    gp1 += "<td><strong>Lot No</strong></td>";
                                                                                    gp1 += "<td><strong>App. Date</strong></td>";
                                                                                    gp1 += "<td><strong>App. Time</strong></td>";
                                                                                    gp1 += "<td><strong>Category</strong></td>";
                                                                                    gp1 += "<td><strong>Type of Inspection</strong></td>";
                                                                                    gp1 += "<td><strong>Severity of Inspection</strong></td>";
                                                                                    gp1 += "<td><strong>Inspection Level</strong></td>";
                                                                                    gp1 += "<td><strong>AQL</strong></td>";
                                                                                    gp1 += "<td><strong>Accept</strong></td>";
                                                                                    gp1 += "<td><strong>Reject</strong></td>";
                                                                                    gp1 += "<td><strong>Date inspected</strong></td>";
                                                                                    gp1 += "<td><strong>WW</strong></td>";
                                                                                    gp1 += "<td><strong>FY</strong></td>";
                                                                                    gp1 += "<td><strong>Time Inspected</strong></td>";
                                                                                    gp1 += "<td><strong>Shift</strong></td>";
                                                                                    gp1 += "<td><strong>Inspector</strong></td>";
                                                                                    gp1 += "<td><strong>Submission</strong></td>";
                                                                                    gp1 += "<td><strong>COC Requirement</strong></td>";
                                                                                    gp1 += "<td><strong>Judgement</strong></td>";
                                                                                    gp1 += "<td><strong>Lot Qty</strong></td>";
                                                                                    gp1 += "<td><strong>Sample Size</strong></td>";
                                                                                    gp1 += "<td><strong>Lot Inspected</strong></td>";
                                                                                    gp1 += "<td><strong>Lot Accepted</strong></td>";
                                                                                    gp1 += "<td><strong>No. of Defects</strong></td>";
                                                                                    gp1 += "<td><strong>Remarks</strong></td>";
                                                                                    gp1 += "<td><strong>Type</strong></td>";
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
                                                                                        gp1 += "<td><button class='btn btn-sm view_inspection blue' ";
                                                                                            gp1 += "data-id='"+req[x][y][z][a].id+"' ";
                                                                                            gp1 += "data-assembly_line='"+req[x][y][z][a].assembly_line+"' ";
                                                                                            gp1 += "data-lot_no='"+req[x][y][z][a].lot_no+"' ";
                                                                                            gp1 += "data-app_date='"+req[x][y][z][a].app_date+"' ";
                                                                                            gp1 += "data-app_time='"+req[x][y][z][a].app_time+"' ";
                                                                                            gp1 += "data-prod_category='"+req[x][y][z][a].prod_category+"' ";
                                                                                            gp1 += "data-po_no='"+req[x][y][z][a].po_no+"' ";
                                                                                            gp1 += "data-device_name='"+req[x][y][z][a].device_name+"' ";
                                                                                            gp1 += "data-customer='"+req[x][y][z][a].customer+"' ";
                                                                                            gp1 += "data-po_qty='"+req[x][y][z][a].po_qty+"' ";
                                                                                            gp1 += "data-family='"+req[x][y][z][a].family+"' ";
                                                                                            gp1 += "data-type_of_inspection='"+req[x][y][z][a].type_of_inspection+"' ";
                                                                                            gp1 += "data-severity_of_inspection='"+req[x][y][z][a].severity_of_inspection+"' ";
                                                                                            gp1 += "data-inspection_lvl='"+req[x][y][z][a].inspection_lvl+"' ";
                                                                                            gp1 += "data-aql='"+req[x][y][z][a].aql+"' ";
                                                                                            gp1 += "data-accept='"+req[x][y][z][a].accept+"' ";
                                                                                            gp1 += "data-reject='"+req[x][y][z][a].reject+"' ";
                                                                                            gp1 += "data-date_inspected='"+req[x][y][z][a].date_inspected+"' ";
                                                                                            gp1 += "data-ww='"+req[x][y][z][a].ww+"' ";
                                                                                            gp1 += "data-fy='"+req[x][y][z][a].fy+"' ";
                                                                                            gp1 += "data-time_ins_from='"+req[x][y][z][a].time_ins_from+"' ";
                                                                                            gp1 += "data-time_ins_to='"+req[x][y][z][a].time_ins_to+"' ";
                                                                                            gp1 += "data-shift='"+req[x][y][z][a].shift+"' ";
                                                                                            gp1 += "data-inspector='"+req[x][y][z][a].inspector+"' ";
                                                                                            gp1 += "data-submission='"+req[x][y][z][a].submission+"' ";
                                                                                            gp1 += "data-coc_req='"+req[x][y][z][a].coc_req+"' ";
                                                                                            gp1 += "data-judgement='"+req[x][y][z][a].judgement+"' ";
                                                                                            gp1 += "data-lot_qty='"+req[x][y][z][a].lot_qty+"' ";
                                                                                            gp1 += "data-sample_size='"+req[x][y][z][a].sample_size+"' ";
                                                                                            gp1 += "data-lot_inspected='"+req[x][y][z][a].lot_inspected+"' ";
                                                                                            gp1 += "data-lot_accepted='"+req[x][y][z][a].lot_accepted+"' ";
                                                                                            gp1 += "data-num_of_defects='"+req[x][y][z][a].num_of_defects+"' ";
                                                                                            gp1 += "data-remarks='"+req[x][y][z][a].remarks+"'> ";
                                                                                            gp1 += "<i class='fa fa-edit'></td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].po_no+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].device_name+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].customer+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].po_qty+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].family+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].assembly_line+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].lot_no+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].app_date+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].app_time+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].prod_category+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].type_of_inspection+" </td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].severity_of_inspection+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].inspection_lvl+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].aql+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].accept+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].reject+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].date_inspected+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].ww+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].fy+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].time_ins_from+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].shift+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].inspector+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].submission+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].coc_req+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].judgement+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].lot_qty+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].sample_size+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].lot_inspected+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].lot_accepted+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].num_of_defects+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].remarks+"</td>";
                                                                                        gp1 += "<td>"+req[x][y][z][a].type+"</td>";
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

function clearGrpByFields() {
    $('.grpfield').val('');
}