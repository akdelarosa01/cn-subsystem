<?php

namespace App\Http\Controllers\QCDB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Auth;
use DB;
use Config;

class IQCGroupByController extends Controller
{
    protected $mysql;
    protected $mssql;
    protected $common;
    protected $com;

    public function __construct()
    {
        $this->middleware('auth');
        $this->com = new CommonController;

        if (Auth::user() != null) {
            $this->mysql = $this->com->userDBcon(Auth::user()->productline,'mysql');
            $this->mssql = $this->com->userDBcon(Auth::user()->productline,'mssql');
            $this->common = $this->com->userDBcon(Auth::user()->productline,'common');
        } else {
            return redirect('/');
        }
    }

    public function CalculateDPPM(Request $req)
    {
        DB::connection($this->mysql)->table('iqc_inspection_excel')->truncate();
        $data =  $this->DPPMTablesv2($req);
        return response()->json($data);
    }

    public function dppmgroup1(Request $req)
    {
        $sub_date_ispected = '';
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $sub_date_ispected =   " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                    "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
        }
        $ins="";
        $list = array();
        $LARresult;
        $LARList = array();
        $DPPMresult;
        $DPPMList = array();
        $rejectednum;
        $rejectednumList = array();
        $DPPM;
        $DPPMList = array();

        $data = json_decode($req->data);

        for($x=0;$x<count($data);$x++){
             $chosen = $data[$x]->chosenfield;
                $ins = DB::connection($this->mysql)
                                ->select("SELECT *,".$req->firstData." as group_one
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND ".$req->firstData." = '".$chosen."'"
                                    );
                $this->insertToReportsv2($ins,"group1");    //FOR REPORTS

                $LARresult = DB::connection($this->mysql)
                                ->select("SELECT ROUND((SUM(lot_accepted)/COUNT(*))*(100),2) AS LAR
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND ".$req->firstData." = '".$chosen."'"
                                    );
                $rejectednum = DB::connection($this->mysql)
                                ->select("SELECT COUNT(*) AS rejects
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND judgement = 'Reject'
                                        AND ".$req->firstData." = '".$chosen."'"
                                    );
                $DPPM = DB::connection($this->mysql)
                                ->select("SELECT ROUND((SUM(no_of_defects)/SUM(sample_size)) * 1000000,2) AS DPPM, SUM(no_of_defects) as no_of_defects ,SUM(sample_size) as sample_size
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND judgement = 'Reject'
                                        AND ".$req->firstData." = '".$chosen."'"
                                    );
                
                array_push($list, $ins);
                array_push($LARList, $LARresult);
                array_push($rejectednumList, $rejectednum);
                array_push($DPPMList, $DPPM);
        }
         return response()->json([
            'returnData' => $list,
            'LARList' => $LARList,
            'rejectednumList' => $rejectednumList,
            'DPPMList' => $DPPMList,
         ]);
    }

    public function dppmgroup2(Request $req)
    {
        $sub_date_ispected = '';
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $sub_date_ispected = " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
        }
            $ins = DB::connection($this->mysql)
                                ->select("SELECT *,".$req->secondData." as chosenfield2
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        GROUP BY ".$req->secondData.""
                                    );


        return response()->json($ins);
    }

    public function dppmgroup3(Request $req)
    {
        $sub_date_ispected = '';
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $sub_date_ispected = " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
        }
            $ins = DB::connection($this->mysql)
                                ->select("SELECT *,".$req->secondData." as chosenfield2,".$req->thirdData." as chosenfield3
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        GROUP BY ".$req->secondData.", ".$req->thirdData." "
                                    );
        return response()->json($ins);
    }

    public function dppmgroup2_Details(Request $req)
    {
        $content1 = json_decode($req->content1);
        $content2 = json_decode($req->content2);

        $sub_date_ispected = '';
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $sub_date_ispected = " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
        }
        $insG1="";
        $listG1 = array();
        $LARresultG1;
        $LARListG1 = array();
        $DPPMresultG1;
        $DPPMListG1 = array();
        $rejectednumG1;
        $rejectednumListG1 = array();
        $DPPMG1;
        $DPPMListG1 = array();
        for($x=0;$x<count($content1);$x++){
                $insG1 = DB::connection($this->mysql)
                                ->select("SELECT *,".$req->firstData." as group_one
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );

                $LARresultG1 = DB::connection($this->mysql)
                                ->select("SELECT ROUND((SUM(lot_accepted)/COUNT(*))*(100),2) AS LAR
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );
                $rejectednumG1 = DB::connection($this->mysql)
                                ->select("SELECT COUNT(*) AS rejects
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND judgement = 'Reject'
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );
                $DPPMG1 = DB::connection($this->mysql)
                                ->select("SELECT ROUND((SUM(no_of_defects)/SUM(sample_size)) * 1000000,2) AS DPPM, SUM(no_of_defects) as no_of_defects ,SUM(sample_size) as sample_size
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND judgement = 'Reject'
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );
                
                array_push($listG1, $insG1);
                array_push($LARListG1, $LARresultG1);
                array_push($rejectednumListG1, $rejectednumG1);
                array_push($DPPMListG1, $DPPMG1);
        }

        $ins="";
        $list = array();
        $list2 = array();
        
        $LARresult;
        $LARList = array();
        $LARList2 = array();

        $DPPMresult;
        $DPPMList = array();
        $DPPMList2 = array();

        $rejectednum;
        $rejectednumList = array();
        $rejectednumList2 = array();

        for($x=0;$x<count($content1);$x++){
            $list = array();
            $LARList = array();
            $DPPMList = array();
            $rejectednumList = array();
                for($y=0;$y<count($content2);$y++)
                {
                        $ins = DB::connection($this->mysql)
                                        ->select("SELECT *,".$req->firstData." as chosenfield ,".$req->secondData." as chosenfield2 
                                                FROM iqc_inspections
                                                WHERE 1=1 ".$sub_date_ispected."
                                                AND ".$req->firstData." = '".$content1[$x]."'
                                                AND ".$req->secondData." = '".$content2[$y]."'"
                                            );

                        $LARresult = DB::connection($this->mysql)
                            ->select("SELECT ROUND((SUM(lot_accepted)/COUNT(*))*(100),2) AS LAR
                                    FROM iqc_inspections
                                    WHERE 1=1 ".$sub_date_ispected."
                                    AND ".$req->firstData." = '".$content1[$x]."'
                                    AND ".$req->secondData." = '".$content2[$y]."'"
                                );

                        $rejectednum = DB::connection($this->mysql)
                            ->select("SELECT COUNT(*) AS rejects
                                    FROM iqc_inspections
                                    WHERE 1=1 ".$sub_date_ispected."
                                    AND judgement = 'Reject'
                                    AND ".$req->firstData." = '".$content1[$x]."'
                                    AND ".$req->secondData." = '".$content2[$y]."'"
                                );

                        $DPPM = DB::connection($this->mysql)
                            ->select("SELECT ROUND((SUM(no_of_defects)/SUM(sample_size)) * 1000000,2) AS DPPM, SUM(no_of_defects) as no_of_defects ,SUM(sample_size) as sample_size
                                    FROM iqc_inspections
                                    WHERE 1=1 ".$sub_date_ispected."
                                    AND judgement = 'Reject'
                                    AND ".$req->firstData." = '".$content1[$x]."'
                                    AND ".$req->secondData." = '".$content2[$y]."'"
                                );

                        if(count($ins) > 0){
                            $this->insertToReportsv2($ins,"group2");
                            array_push($list, $ins);
                            array_push($LARList, $LARresult);
                            array_push($rejectednumList, $rejectednum);
                            array_push($DPPMList, $DPPM);
                        }
                }
            if(count($list) > 0){
                array_push($list2, $list);
                array_push($LARList2, $LARList);
                array_push($rejectednumList2, $rejectednumList);
                array_push($DPPMList2, $DPPMList);
            }
         }
     
     return response()->json([
            'returnData' => $list2,
            'LARList' => $LARList2,
            'rejectednumList' => $rejectednumList2,
            'DPPMList' => $DPPMList2,
            'LARListG1' => $LARListG1,
            'rejectednumListG1' => $rejectednumListG1,
            'DPPMListG1' => $DPPMListG1
         ]);
    }

    public function dppmgroup3_Details(Request $req)
    {
        $content1 = json_decode($req->content1);
        $content2 = json_decode($req->content2);
        $content3 = json_decode($req->content3);

        $sub_date_ispected = '';
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $sub_date_ispected = " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
        }

        $insG1="";
        $listG1 = array();
        $LARresultG1;
        $LARListG1 = array();
        $DPPMresultG1;
        $DPPMListG1 = array();
        $rejectednumG1;
        $rejectednumListG1 = array();
        $DPPMG1;
        $DPPMListG1 = array();

        for($x=0;$x<count($content1);$x++){
            if (isset($req->firstData)) {
                $insG1 = DB::connection($this->mysql)
                                ->select("SELECT *,".$req->firstData." as group_one
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );

                $LARresultG1 = DB::connection($this->mysql)
                                ->select("SELECT ROUND((SUM(lot_accepted)/COUNT(*))*(100),2) AS LAR
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );
                $rejectednumG1 = DB::connection($this->mysql)
                                ->select("SELECT COUNT(*) AS rejects
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND judgement = 'Reject'
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );
                $DPPMG1 = DB::connection($this->mysql)
                                ->select("SELECT ROUND((SUM(no_of_defects)/SUM(sample_size)) * 1000000,2) AS DPPM, SUM(no_of_defects) as no_of_defects ,SUM(sample_size) as sample_size
                                        FROM iqc_inspections
                                        WHERE 1=1 ".$sub_date_ispected."
                                        AND judgement = 'Reject'
                                        AND ".$req->firstData." = '".$content1[$x]."'"
                                    );
                
                array_push($listG1, $insG1);
                array_push($LARListG1, $LARresultG1);
                array_push($rejectednumListG1, $rejectednumG1);
                array_push($DPPMListG1, $DPPMG1);
            }
                
        }

        $ins="";
        $list = array();
        $list2 = array();
        
        $LARresult;
        $LARList = array();
        $LARList_2nd = array();

        $DPPMresult;
        $DPPMList = array();
        $DPPMList_2nd = array();

        $rejectednum;
        $rejectednumList = array();
        $rejectednumList_2nd = array();

        for($x=0;$x<count($content1);$x++){
            $list = array();
            $LARList = array();
            $DPPMList = array();
            $rejectednumList = array();
                for($y=0;$y<count($content2);$y++)
                {
                        $ins = DB::connection($this->mysql)
                                        ->select("SELECT *,".$req->firstData." as chosenfield ,".$req->secondData." as chosenfield2 
                                                FROM iqc_inspections
                                                WHERE 1=1 ".$sub_date_ispected."
                                                AND ".$req->firstData." = '".$content1[$x]."'
                                                AND ".$req->secondData." = '".$content2[$y]."'"
                                            );

                        $LARresult = DB::connection($this->mysql)
                            ->select("SELECT ROUND((SUM(lot_accepted)/COUNT(*))*(100),2) AS LAR
                                    FROM iqc_inspections
                                    WHERE 1=1 ".$sub_date_ispected."
                                    AND ".$req->firstData." = '".$content1[$x]."'
                                    AND ".$req->secondData." = '".$content2[$y]."'"
                                );

                        $rejectednum = DB::connection($this->mysql)
                            ->select("SELECT COUNT(*) AS rejects
                                    FROM iqc_inspections
                                    WHERE 1=1 ".$sub_date_ispected."
                                    AND judgement = 'Reject'
                                    AND ".$req->firstData." = '".$content1[$x]."'
                                    AND ".$req->secondData." = '".$content2[$y]."'"
                                );

                        $DPPM = DB::connection($this->mysql)
                            ->select("SELECT ROUND((SUM(no_of_defects)/SUM(sample_size)) * 1000000,2) AS DPPM, SUM(no_of_defects) as no_of_defects ,SUM(sample_size) as sample_size
                                    FROM iqc_inspections
                                    WHERE 1=1 ".$sub_date_ispected."
                                    AND judgement = 'Reject'
                                    AND ".$req->firstData." = '".$content1[$x]."'
                                    AND ".$req->secondData." = '".$content2[$y]."'"
                                );

                        if(count($ins) > 0){
                            array_push($list, $ins);
                            array_push($LARList, $LARresult);
                            array_push($rejectednumList, $rejectednum);
                            array_push($DPPMList, $DPPM);
                        }
                }
            if(count($list) > 0){
                array_push($list2, $list);
                array_push($LARList_2nd, $LARList);
                array_push($rejectednumList_2nd, $rejectednumList);
                array_push($DPPMList_2nd, $DPPMList);
            }
         }


        //=========================//
        $ins_3rd="";
        $list_3rd = array();
        $list2_3rd = array();
        $list3_3rd = array();

        $LARresult;
        $LARList = array();
        $LARList2 = array();
        $LARList3 = array();

        $DPPMresult;
        $DPPMList = array();
        $DPPMList2 = array();
        $DPPMList3 = array();

        $rejectednum;
        $rejectednumList = array();
        $rejectednumList2 = array();
        $rejectednumList3 = array();

            for($x=0;$x<count($content1);$x++){
                $list2_3rd = array();
                $LARList2 = array();
                $DPPMList2 = array();
                $rejectednumList2 = array();
                    for($y=0;$y<count($content2);$y++)
                    {
                        $list_3rd = array();
                        $LARList = array();
                        $DPPMList = array();
                        $rejectednumList = array();
                        for($z=0;$z<count($content3);$z++)
                        {
                            $ins_3rd = DB::connection($this->mysql)
                                            ->select("SELECT *,".$req->firstData." as chosenfield ,".$req->secondData." as chosenfield2,".$req->thirdData." as chosenfield3 
                                                    FROM iqc_inspections
                                                    WHERE 1=1 ".$sub_date_ispected."
                                                    AND ".$req->firstData." = '".$content1[$x]."'
                                                    AND ".$req->secondData." = '".$content2[$y]."'
                                                    AND ".$req->thirdData." = '".$content3[$z]."'"
                                                    // GROUP BY ".$req->thirdData.""
                                                );
                            $LARresult = DB::connection($this->mysql)
                                            ->select("SELECT ROUND((SUM(lot_accepted)/COUNT(*))*(100),2) AS LAR
                                                    FROM iqc_inspections
                                                    WHERE 1=1 ".$sub_date_ispected."
                                                    AND ".$req->firstData." = '".$content1[$x]."'
                                                    AND ".$req->secondData." = '".$content2[$y]."'
                                                    AND ".$req->thirdData." = '".$content3[$z]."'"
                                                );

                            $rejectednum = DB::connection($this->mysql)
                                            ->select("SELECT COUNT(*) AS rejects
                                                    FROM iqc_inspections
                                                    WHERE 1=1 ".$sub_date_ispected."
                                                    AND judgement = 'Reject'
                                                    AND ".$req->firstData." = '".$content1[$x]."'
                                                    AND ".$req->secondData." = '".$content2[$y]."'
                                                    AND ".$req->thirdData." = '".$content3[$z]."'"
                                                );

                            $DPPM = DB::connection($this->mysql)
                                            ->select("SELECT ROUND((SUM(no_of_defects)/SUM(sample_size)) * 1000000,2) AS DPPM, SUM(no_of_defects) as no_of_defects ,SUM(sample_size) as sample_size
                                                    FROM iqc_inspections
                                                    WHERE 1=1 ".$sub_date_ispected."
                                                    AND judgement = 'Reject'
                                                    AND ".$req->firstData." = '".$content1[$x]."'
                                                    AND ".$req->secondData." = '".$content2[$y]."'
                                                    AND ".$req->thirdData." = '".$content3[$z]."'"
                                                );
                        if(count($ins_3rd) > 0){
                            $this->insertToReportsv2($ins_3rd,"group3");
                            array_push($list_3rd, $ins_3rd);
                            array_push($LARList , $LARresult);
                            array_push($DPPMList , $DPPM);
                            array_push($rejectednumList , $rejectednum);

                        }
                    }
                    if(count($list_3rd) > 0 ){
                    array_push($list2_3rd, $list_3rd);
                    array_push($LARList2, $LARList);
                    array_push($DPPMList2, $DPPMList);
                    array_push($rejectednumList2, $rejectednumList);
                    }
                }
                if(count($list2_3rd) > 0){
                array_push($list3_3rd, $list2_3rd);
                array_push($LARList3, $LARList2);
                array_push($DPPMList3 , $DPPMList2);
                array_push($rejectednumList3 , $rejectednumList2);
                }
            }
     

        return response()->json([
            'returnData' => $list3_3rd,
            'LARList_3rd' => $LARList3,
            'DPPMList_3rd' => $DPPMList3,
            'rejectednumList_3rd' => $rejectednumList3,
            'LARList_2nd' => $LARList_2nd,
            'rejectednumList_2nd' => $rejectednumList_2nd,
            'DPPMList_2nd' => $DPPMList_2nd,
            'LARListG1' => $LARListG1,
            'rejectednumListG1' => $rejectednumListG1,
            'DPPMListG1' => $DPPMListG1
         ]);
        //return response()->json($list3);
    }

    public function DPPMTablesv2($req)
    {
        $ins="";
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $date_ispected = " AND main.date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
            $sub_date_ispected = " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
        }
        if(!empty($req->field3) && !empty($req->content3)){
            $ins = DB::connection($this->mysql)
                    ->select("SELECT *,".$req->field1." as chosenfield
                            FROM iqc_inspections
                            WHERE 1=1 ".$sub_date_ispected."
                            AND ".$req->field1." = '".$req->content1."'
                            AND ".$req->field2." = '".$req->content2."'
                            AND ".$req->field3." = '".$req->content3."'
                            GROUP BY ".$req->field1.""
                        );
        }
        else if(!empty($req->field2) && !empty($req->content2)){
            $ins = DB::connection($this->mysql)
                    ->select("SELECT *,".$req->field1." as chosenfield
                            FROM iqc_inspections
                            WHERE 1=1 ".$sub_date_ispected."
                            AND ".$req->field1." = '".$req->content1."'
                            AND ".$req->field2." = '".$req->content2."'
                            GROUP BY ".$req->field1.""
                        );
        }
        else if(!empty($req->field1) && !empty($req->content1)){
            $ins = DB::connection($this->mysql)
                    ->select("SELECT *,".$req->field1." as chosenfield
                            FROM iqc_inspections
                            WHERE 1=1 ".$sub_date_ispected."
                            AND ".$req->field1." = '".$req->content1."'
                            GROUP BY ".$req->field1.""
                        );
        }
        
        else{
            $ins = DB::connection($this->mysql)
                    ->select("SELECT *,".$req->field1." as chosenfield
                            FROM iqc_inspections
                            WHERE 1=1 ".$sub_date_ispected."
                            GROUP BY ".$req->field1.""
                        );
        }
        return $ins;
    }

    private function DPPMTables($req,$join)
    {
        $g1 = ''; $g2 = ''; $g3 = '';
        $g1c = ''; $g2c = ''; $g3c = '';
        $date_ispected = ''; $sub_date_ispected = '';
        $groupBy = []; $inVal; $wherein1 = []; $wherein2 = []; $wherein3 = [];
        $node1 = []; $node2 = []; $node3 = [];

        // wheres
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $date_ispected = " AND main.date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
            $sub_date_ispected = " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
        }

        DB::connection($this->mysql)->table('iqc_inspection_excel')->truncate();

        if (!empty($req->field1)) {
            $g1 = $req->field1;
            $g2 = $req->field2;

            if ($req->content1 == '' && $req->content2 == '') {
                $in = DB::connection($this->mysql)
                        ->select("SELECT ".$g1." as description,date_ispected, ".$g2." as g2
                                FROM iqc_inspections
                                WHERE 1=1 ".$sub_date_ispected." 
                                GROUP BY ".$g1.",".$g2
                            );

                
                foreach ($in as $key => $flds) {
                    array_push($wherein1,$flds->description);
                    array_push($wherein2,$flds->g2);
                }

                $inVal = implode("','",$wherein1);
                $inVal2 = implode("','",$wherein2);

                $query = DB::connection($this->mysql)
                            ->select("SELECT main.".$g1." as field1,
                                            IFNULL(acc.no_of_accepted,0) as no_of_accepted,
                                            IFNULL(ins.no_of_lots_inspected,0) as no_of_lots_inspected,
                                            IFNULL(SUM(main.no_of_defects),0) as no_of_defects,
                                            IFNULL(SUM(main.sample_size),0) as sample_size
                                        FROM iqc_inspections as main
                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_accepted,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE judgement = 'Accept' ".$sub_date_ispected." 
                                            AND ".$g1." IN ('".$inVal."') AND ".$g2." IN ('".$inVal2."')
                                            GROUP BY ".$g1.",".$g2."
                                        ) AS acc ON acc.".$g1." = main.".$g1."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE ".$g1." IN ('".$inVal."') AND ".$g2." IN ('".$inVal2."') ".$sub_date_ispected." 
                                            GROUP BY ".$g1.",".$g1."
                                        ) AS ins ON ins.".$g1." = main.".$g1."

                                        WHERE 1=1".$date_ispected." AND main.".$g1." IN ('".$inVal."') AND ".$g2." IN ('".$inVal2."')".
                                        "GROUP BY main.".$g1."");
                
            }

            if ($req->content1 == '') {
                $wherein1 = [];
                $in = DB::connection($this->mysql)
                        ->select("SELECT ".$g1." as description,date_ispected
                                FROM iqc_inspections
                                WHERE 1=1 ".$sub_date_ispected."
                                GROUP BY ".$g1
                            );

                
                foreach ($in as $key => $flds) {
                    array_push($wherein1,$flds->description);
                }

                $inVal = implode("','",$wherein1);

                $query = DB::connection($this->mysql)
                            ->select("SELECT main.".$g1." as field1,
                                            acc.no_of_accepted,
                                            ins.no_of_lots_inspected,
                                            SUM(main.no_of_defects) as no_of_defects,
                                            SUM(main.sample_size) as sample_size
                                        FROM iqc_inspections as main
                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_accepted,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE judgement = 'Accepted' ".$sub_date_ispected."
                                            AND ".$g1." IN ('".$inVal."')
                                            GROUP BY ".$g1."
                                        ) AS acc ON acc.".$g1." = main.".$g1."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE ".$g1." IN ('".$inVal."') ".$sub_date_ispected."
                                            GROUP BY ".$g1."
                                        ) AS ins ON ins.".$g1." = main.".$g1."

                                        WHERE 1=1".$date_ispected." AND main.".$g1." IN ('".$inVal."') ".
                                        "GROUP BY main.".$g1."");
                
            } else {
                $query = DB::connection($this->mysql)
                            ->select("SELECT main.".$g1." as field1,
                                            acc.no_of_accepted,
                                            ins.no_of_lots_inspected,
                                            SUM(main.no_of_defects) as no_of_defects,
                                            SUM(main.sample_size) as sample_size
                                        FROM iqc_inspections as main
                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_accepted,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE judgement = 'Accepted' ".$sub_date_ispected."
                                            AND ".$g1."='".$req->content1."'
                                            GROUP BY ".$g1."
                                        ) AS acc ON acc.".$g1." = main.".$g1."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE ".$g1."='".$req->content1."' ".$sub_date_ispected."
                                            GROUP BY ".$g1."
                                        ) AS ins ON ins.".$g1." = main.".$g1."

                                        WHERE 1=1".$date_ispected." AND main.".$g1."='".$req->content1."'
                                         GROUP BY main.".$g1."");
                
            }

            $lar = 0;
            $dppm = 0;

            $inVal2 = implode("','",$wherein2);

            foreach ($query as $key => $qy) {
                if ($qy->no_of_accepted >= 0 && $qy->no_of_lots_inspected >= 0) {
                    if ($qy->no_of_accepted == 0 || $qy->no_of_lots_inspected == 0) {
                        $lar = 0*100;
                    } else {
                        $lar = ($qy->no_of_accepted / $qy->no_of_lots_inspected)*100;
                    }
                }

                if ($qy->no_of_defects >= 0 && $qy->sample_size >= 0) {
                    if ($qy->no_of_defects == 0 || $qy->sample_size == 0) {
                        $dppm = 0*1000000;
                    } else {
                        $dppm = ($qy->no_of_defects / $qy->sample_size)*1000000;
                    }
                }

                $details = [];

                if ($req->field2 !== '' && $req->field1 !== '') {
                    if ($req->field2 !== '' && $req->field1 !== '') {
                        if (isset($wherein1[$key])) {
                            $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND main.".$g1." = '".$wherein1[$key]."'
                                        AND main.".$g2." IN ('".$inVal2."')");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND
                                        main.".$g1."='".$req->content1."'");
                    }

                    $this->insertToReports($details);
                }
                
                if ($req->field2 == '') {
                    if ($req->content1 == '') {
                        if (isset($wherein1[$key])) {
                            $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND main.".$g1." = '".$wherein1[$key]."'");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND
                                        main.".$g1."='".$req->content1."'");
                    }

                    $this->insertToReports($details);
                }

                array_push($node1,[
                    'group' => $req->field1,
                    'group_val' => $qy->field1,
                    'no_of_accepted' => $qy->no_of_accepted,
                    'no_of_lots_inspected' => $qy->no_of_lots_inspected,
                    'no_of_defects' => $qy->no_of_defects,
                    'sample_size' => $qy->sample_size,
                    'LAR' => number_format($lar,2),
                    'DPPM' => number_format($dppm,2),
                    'details' => $details
                ]);
            }
        }

        if (!empty($req->field2)) {
            $g2 = $req->field2;

            $wherein2 = [];

            if ($req->content2 == '') {
                $in = DB::connection($this->mysql)
                        ->select("SELECT ".$g2." as description,date_ispected,".$g1."
                                FROM iqc_inspections
                                WHERE ".$g1." = '".$req->content1."'
                                AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                GROUP BY ".$g1.",".$g2
                            );

                
                foreach ($in as $key => $flds) {
                    array_push($wherein2,$flds->description);
                }

                $inVal = implode("','",$wherein2);

                $query = DB::connection($this->mysql)
                            ->select("SELECT main.".$g2." as field2,
                                            IFNULL(acc.no_of_accepted,0) as no_of_accepted ,
                                            IFNULL(ins.no_of_lots_inspected,0) as no_of_lots_inspected ,
                                            IFNULL(SUM(main.no_of_defects),0) as no_of_defects,
                                            IFNULL(SUM(main.sample_size),0) as sample_size
                                        FROM iqc_inspections as main
                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_accepted,".$g2.",date_ispected
                                            FROM iqc_inspections
                                            WHERE judgement = 'Accepted' AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            GROUP BY ".$g1.",".$g2."
                                        ) AS acc ON acc.".$g2." = main.".$g2."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,".$g2.",date_ispected
                                            FROM iqc_inspections
                                            WHERE ".$g2." IN ('".$inVal."') AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            GROUP BY ".$g1.",".$g2."
                                        ) AS ins ON ins.".$g2." = main.".$g2."

                                        WHERE 1=1".$date_ispected." AND main.".$g1."='".$req->content1."' AND main.".$g2." IN ('".$inVal."') ".
                                        "GROUP BY main.".$g1.",main.".$g2."");
            } else {
                $query = DB::connection($this->mysql)
                            ->select("SELECT main.".$g2." as field2,
                                            IFNULL(acc.no_of_accepted,0) as no_of_accepted ,
                                            IFNULL(ins.no_of_lots_inspected,0) as no_of_lots_inspected ,
                                            IFNULL(SUM(main.no_of_defects),0) as no_of_defects,
                                            IFNULL(SUM(main.sample_size),0) as sample_size
                                        FROM iqc_inspections as main
                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_accepted,".$g2."
                                            FROM iqc_inspections
                                            WHERE judgement = 'Accepted' AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            AND ".$g2."='".$req->content2."'
                                            GROUP BY ".$g1.",".$g2."
                                        ) AS acc ON acc.".$g2." = main.".$g2."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,".$g2."
                                            FROM iqc_inspections
                                            WHERE ".$g2."='".$req->content2."' AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            GROUP BY ".$g1.",".$g2."
                                        ) AS ins ON ins.".$g2." = main.".$g2."

                                        WHERE 1=1".$date_ispected." AND main.".$g1."='".$req->content1."' AND main.".$g2."='".$req->content2."'
                                         GROUP BY main.".$g1.",main.".$g2."");
            }

            $lar = 0;
            $dppm = 0;
            foreach ($query as $key => $qy) {
                if ($qy->no_of_accepted >= 0 && $qy->no_of_lots_inspected >= 0) {
                    if ($qy->no_of_accepted == 0 || $qy->no_of_lots_inspected == 0) {
                        $lar = 0*100;
                    } else {
                        $lar = ($qy->no_of_accepted / $qy->no_of_lots_inspected)*100;
                    }
                }

                if ($qy->no_of_defects >= 0 && $qy->sample_size >= 0) {
                    if ($qy->no_of_defects == 0 || $qy->sample_size == 0) {
                        $dppm = 0*1000000;
                    } else {
                        $dppm = ($qy->no_of_defects / $qy->sample_size)*1000000;
                    }
                }

                $details = [];

                if ($req->field3 == '') {
                    if ($req->content2 == '') {
                        if (isset($wherein2[$key])) {
                            $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND main.".$g1."='".$req->content1."'
                                        AND main.".$g2." = '".$wherein2[$key]."'");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND
                                        main.".$g1."='".$req->content1."'
                                        AND main.".$g2."='".$req->content2."'");
                    }
                    $this->insertToReports($details);
                }


                array_push($node2,[
                    'group' => $req->field2,
                    'group_val' => $qy->field2,
                    'no_of_accepted' => $qy->no_of_accepted,
                    'no_of_lots_inspected' => $qy->no_of_lots_inspected,
                    'no_of_defects' => $qy->no_of_defects,
                    'sample_size' => $qy->sample_size,
                    'LAR' => number_format($lar,2),
                    'DPPM' => number_format($dppm,2),
                    'details' => $details,
                    'wherein' => $wherein2
                ]);
            }
        }

        if (!empty($req->field3)) {
            $g3 = $req->field3;

            $wherein3 = [];

            if ($req->content3 == '' && $req->content2 !== '') {
                $in = DB::connection($this->mysql)
                        ->select("SELECT ".$g3." as description,".$g1.",".$g2."
                                FROM iqc_inspections
                                WHERE ".$g1." = '".$req->content1."' AND ".$g2." = '".$req->content2."'
                                AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                GROUP BY ".$g1.",".$g2.",".$g3
                            );

                
                foreach ($in as $key => $flds) {
                    array_push($wherein3,$flds->description);
                }

                $inVal = implode("','",$wherein3);

                $query = DB::connection($this->mysql)
                            ->select("SELECT main.".$g3." as field3,
                                            IFNULL(acc.no_of_accepted,0) as no_of_accepted ,
                                            IFNULL(ins.no_of_lots_inspected,0) as no_of_lots_inspected ,
                                            IFNULL(SUM(main.no_of_defects),0) as no_of_defects,
                                            IFNULL(SUM(main.sample_size),0) as sample_size
                                        FROM iqc_inspections as main
                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_accepted,".$g3.",date_ispected
                                            FROM iqc_inspections
                                            WHERE judgement = 'Accepted' AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            GROUP BY ".$g1.",".$g2.",".$g3."
                                        ) AS acc ON acc.".$g3." = main.".$g3."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,".$g3.",date_ispected
                                            FROM iqc_inspections
                                            WHERE ".$g3." IN ('".$inVal."') AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            GROUP BY ".$g1.",".$g2.",".$g3."
                                        ) AS ins ON ins.".$g3." = main.".$g3."

                                        WHERE 1=1".$date_ispected." AND main.".$g1."='".$req->content1."' 
                                        AND main.".$g2."='".$req->content2."' AND main.".$g3." IN ('".$inVal."') ".
                                        "GROUP BY main.".$g1.",main.".$g2.",main.".$g3);
            } else {
                $query = DB::connection($this->mysql)
                            ->select("SELECT main.".$g3." as field3,
                                            IFNULL(acc.no_of_accepted,0) as no_of_accepted ,
                                            IFNULL(ins.no_of_lots_inspected,0) as no_of_lots_inspected ,
                                            IFNULL(SUM(main.no_of_defects),0) as no_of_defects,
                                            IFNULL(SUM(main.sample_size),0) as sample_size
                                        FROM iqc_inspections as main
                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_accepted,".$g3."
                                            FROM iqc_inspections
                                            WHERE judgement = 'Accepted' AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            AND ".$g3."='".$req->content3."'
                                            GROUP BY ".$g1.",".$g2.",".$g3."
                                        ) AS acc ON acc.".$g3." = main.".$g3."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,".$g3."
                                            FROM iqc_inspections
                                            WHERE ".$g3."='".$req->content3."' AND date_ispected BETWEEN 
                                            '".$this->com->convertDate($req->gfrom,'Y-m-d').
                                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'
                                            GROUP BY ".$g1.",".$g2.",".$g3."
                                        ) AS ins ON ins.".$g3." = main.".$g3."

                                        WHERE 1=1".$date_ispected." AND main.".$g1."='".$req->content1."' AND main.".$g2."='".$req->content2."'
                                         AND main.".$g3."='".$req->content3."'
                                         GROUP BY main.".$g1.",main.".$g2.",main.".$g3);
            }

            $lar = 0;
            $dppm = 0;
            foreach ($query as $key => $qy) {
                if ($qy->no_of_accepted >= 0 && $qy->no_of_lots_inspected >= 0) {
                    if ($qy->no_of_accepted == 0 || $qy->no_of_lots_inspected == 0) {
                        $lar = 0*100;
                    } else {
                        $lar = ($qy->no_of_accepted / $qy->no_of_lots_inspected)*100;
                    }
                }

                if ($qy->no_of_defects >= 0 && $qy->sample_size >= 0) {
                    if ($qy->no_of_defects == 0 || $qy->sample_size == 0) {
                        $dppm = 0*1000000;
                    } else {
                        $dppm = ($qy->no_of_defects / $qy->sample_size)*1000000;
                    }
                }

                $details = [];

                //if ($req->field3 == '') {
                    if ($req->content3 == '') {
                        if (isset($wherein3[$key])) {
                            $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND main.".$g1."='".$req->content1."'
                                        AND main.".$g2."='".$req->content2."'
                                        AND main.".$g3." = '".$wherein3[$key]."'");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_ispected." AND
                                        main.".$g1."='".$req->content1."'
                                        AND main.".$g2."='".$req->content2."'
                                        AND main.".$g3."='".$req->content3."'");
                    }

                    $this->insertToReports($details);
                //}


                array_push($node3,[
                    'group' => $req->field3,
                    'group_val' => $qy->field3,
                    'no_of_accepted' => $qy->no_of_accepted,
                    'no_of_lots_inspected' => $qy->no_of_lots_inspected,
                    'no_of_defects' => $qy->no_of_defects,
                    'sample_size' => $qy->sample_size,
                    'LAR' => number_format($lar,2),
                    'DPPM' => number_format($dppm,2),
                    'details' => $details,
                    'wherein' => $wherein3
                ]);
            }
        }


        $data = [
            'node1' => $node1,
            'node2' => $node2,
            'node3' => $node3,
        ];

        if ($this->com->checkIfExistObject($data) > 0) {
            return response()->json($data);
        }
    }

    private function insertToReports($details)
    {
        $fields = [];

        foreach ($details as $key => $x) {
            array_push($fields,[
                'invoice_no' => $x->invoice_no,
                'partcode' => $x->partcode,
                'partname' => $x->partname,
                'supplier' => $x->supplier,
                'app_date' => $this->com->convertDate($x->app_date,'Y-m-d'),
                'app_time' => $x->app_time,
                'app_no' => $x->app_no,
                'lot_no' => $x->lot_no,
                'lot_qty' => $x->lot_qty,
                'type_of_inspection' => $x->type_of_inspection,
                'severity_of_inspection' => $x->severity_of_inspection,
                'inspection_lvl' => $x->inspection_lvl,
                'aql' => $x->aql,
                'accept' => $x->accept,
                'reject' => $x->reject,
                'date_inspected' => $x->date_ispected,
                'ww' => $x->ww,
                'fy' => $x->fy,
                'shift' => $x->shift,
                'time_ins_from' => $x->time_ins_from,
                'time_ins_to' => $x->time_ins_to,
                'inspector' => $x->inspector,
                'submission' => $x->submission,
                'judgement' => $x->judgement,
                'lot_inspected' => $x->lot_inspected,
                'lot_accepted' => $x->lot_accepted,
                'sample_size' => $x->sample_size,
                'no_of_defects' => $x->no_of_defects,
                'remarks' => $x->remarks,
                'classification' => $x->classification,
            ]);
        }

        $insertBatchs = array_chunk($fields, 2000);
        foreach ($insertBatchs as $batch) {
            DB::connection($this->mysql)->table('iqc_inspection_excel')->insert($batch);
        }
    }

    public function GroupByValues(Request $req)
    {
        $data = DB::connection($this->mysql)->table('iqc_inspections')
                ->select($req->field.' as field')
                ->distinct()
                ->get();

        return $data;
    }

    private function insertToReportsv2($details,$type)
    {
        $fields = [];
        if($type == "group1"){
            foreach ($details as $key => $x) {
                array_push($fields,[
                    'invoice_no' => $x->invoice_no,
                    'partcode' => $x->partcode,
                    'partname' => $x->partname,
                    'supplier' => $x->supplier,
                    'app_date' => $this->com->convertDate($x->app_date,'Y-m-d'),
                    'app_time' => $x->app_time,
                    'app_no' => $x->app_no,
                    'lot_no' => $x->lot_no,
                    'lot_qty' => $x->lot_qty,
                    'type_of_inspection' => $x->type_of_inspection,
                    'severity_of_inspection' => $x->severity_of_inspection,
                    'inspection_lvl' => $x->inspection_lvl,
                    'aql' => $x->aql,
                    'accept' => $x->accept,
                    'reject' => $x->reject,
                    'date_inspected' => $x->date_ispected,
                    'ww' => $x->ww,
                    'fy' => $x->fy,
                    'shift' => $x->shift,
                    'time_ins_from' => $x->time_ins_from,
                    'time_ins_to' => $x->time_ins_to,
                    'inspector' => $x->inspector,
                    'submission' => $x->submission,
                    'judgement' => $x->judgement,
                    'lot_inspected' => $x->lot_inspected,
                    'lot_accepted' => $x->lot_accepted,
                    'sample_size' => $x->sample_size,
                    'no_of_defects' => $x->no_of_defects,
                    'remarks' => $x->remarks,
                    'classification' => $x->classification,
                    'group_one' => $x->group_one
                ]);
            }
        }
        else if($type == "group2")
        {
            foreach ($details as $key => $x) {
                array_push($fields,[
                    'invoice_no' => $x->invoice_no,
                    'partcode' => $x->partcode,
                    'partname' => $x->partname,
                    'supplier' => $x->supplier,
                    'app_date' => $this->com->convertDate($x->app_date,'Y-m-d'),
                    'app_time' => $x->app_time,
                    'app_no' => $x->app_no,
                    'lot_no' => $x->lot_no,
                    'lot_qty' => $x->lot_qty,
                    'type_of_inspection' => $x->type_of_inspection,
                    'severity_of_inspection' => $x->severity_of_inspection,
                    'inspection_lvl' => $x->inspection_lvl,
                    'aql' => $x->aql,
                    'accept' => $x->accept,
                    'reject' => $x->reject,
                    'date_inspected' => $x->date_ispected,
                    'ww' => $x->ww,
                    'fy' => $x->fy,
                    'shift' => $x->shift,
                    'time_ins_from' => $x->time_ins_from,
                    'time_ins_to' => $x->time_ins_to,
                    'inspector' => $x->inspector,
                    'submission' => $x->submission,
                    'judgement' => $x->judgement,
                    'lot_inspected' => $x->lot_inspected,
                    'lot_accepted' => $x->lot_accepted,
                    'sample_size' => $x->sample_size,
                    'no_of_defects' => $x->no_of_defects,
                    'remarks' => $x->remarks,
                    'classification' => $x->classification,
                    'group_one' => $x->chosenfield,
                    'group_two' => $x->chosenfield2
                ]);
            }
        }
        else{
            foreach ($details as $key => $x) {
                array_push($fields,[
                    'invoice_no' => $x->invoice_no,
                    'partcode' => $x->partcode,
                    'partname' => $x->partname,
                    'supplier' => $x->supplier,
                    'app_date' => $this->com->convertDate($x->app_date,'Y-m-d'),
                    'app_time' => $x->app_time,
                    'app_no' => $x->app_no,
                    'lot_no' => $x->lot_no,
                    'lot_qty' => $x->lot_qty,
                    'type_of_inspection' => $x->type_of_inspection,
                    'severity_of_inspection' => $x->severity_of_inspection,
                    'inspection_lvl' => $x->inspection_lvl,
                    'aql' => $x->aql,
                    'accept' => $x->accept,
                    'reject' => $x->reject,
                    'date_inspected' => $x->date_ispected,
                    'ww' => $x->ww,
                    'fy' => $x->fy,
                    'shift' => $x->shift,
                    'time_ins_from' => $x->time_ins_from,
                    'time_ins_to' => $x->time_ins_to,
                    'inspector' => $x->inspector,
                    'submission' => $x->submission,
                    'judgement' => $x->judgement,
                    'lot_inspected' => $x->lot_inspected,
                    'lot_accepted' => $x->lot_accepted,
                    'sample_size' => $x->sample_size,
                    'no_of_defects' => $x->no_of_defects,
                    'remarks' => $x->remarks,
                    'classification' => $x->classification,
                    'group_one' => $x->chosenfield,
                    'group_two' => $x->chosenfield2,
                    'group_three' => $x->chosenfield3
                ]);
            }
        }

        $insertBatchs = array_chunk($fields, 2000);
        foreach ($insertBatchs as $batch) {
            DB::connection($this->mysql)->table('iqc_inspection_excel')->insert($batch);
        }
    }
}
