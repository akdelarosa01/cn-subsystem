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
        return $this->DPPMTables($req,false);
    }

    private function DPPMTables($req,$join)
    {
        $g1 = ''; $g2 = ''; $g3 = '';
        $g1c = ''; $g2c = ''; $g3c = '';
        $date_inspected = ''; $sub_date_inspected = '';
        $groupBy = []; $inVal; $wherein1 = []; $wherein2 = []; $wherein3 = [];
        $node1 = []; $node2 = []; $node3 = [];

        // wheres
        if (!empty($req->gfrom) && !empty($req->gto)) {
            $date_inspected = " AND main.date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
                            "' AND '".$this->com->convertDate($req->gto,'Y-m-d')."'";
            $sub_date_inspected = " AND date_ispected BETWEEN '".$this->com->convertDate($req->gfrom,'Y-m-d').
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
                                WHERE 1=1 ".$sub_date_inspected." 
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
                                            WHERE judgement = 'Accept' ".$sub_date_inspected." 
                                            AND ".$g1." IN ('".$inVal."') AND ".$g2." IN ('".$inVal2."')
                                            GROUP BY ".$g1.",".$g2."
                                        ) AS acc ON acc.".$g1." = main.".$g1."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE ".$g1." IN ('".$inVal."') AND ".$g2." IN ('".$inVal2."') ".$sub_date_inspected." 
                                            GROUP BY ".$g1.",".$g1."
                                        ) AS ins ON ins.".$g1." = main.".$g1."

                                        WHERE 1=1".$date_inspected." AND main.".$g1." IN ('".$inVal."') AND ".$g2." IN ('".$inVal2."')".
                                        "GROUP BY main.".$g1."");
                
            }

            if ($req->content1 == '') {
                $wherein1 = [];
                $in = DB::connection($this->mysql)
                        ->select("SELECT ".$g1." as description,date_ispected
                                FROM iqc_inspections
                                WHERE 1=1 ".$sub_date_inspected."
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
                                        	WHERE judgement = 'Accepted' ".$sub_date_inspected."
                                            AND ".$g1." IN ('".$inVal."')
                                            GROUP BY ".$g1."
                                        ) AS acc ON acc.".$g1." = main.".$g1."

                                        LEFT JOIN (
                                        	SELECT COUNT(id) as no_of_lots_inspected,date_ispected,".$g1."
                                        	FROM iqc_inspections
                                            WHERE ".$g1." IN ('".$inVal."') ".$sub_date_inspected."
                                        	GROUP BY ".$g1."
                                        ) AS ins ON ins.".$g1." = main.".$g1."

                                        WHERE 1=1".$date_inspected." AND main.".$g1." IN ('".$inVal."') ".
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
                                            WHERE judgement = 'Accepted' ".$sub_date_inspected."
                                            AND ".$g1."='".$req->content1."'
                                            GROUP BY ".$g1."
                                        ) AS acc ON acc.".$g1." = main.".$g1."

                                        LEFT JOIN (
                                            SELECT COUNT(id) as no_of_lots_inspected,date_ispected,".$g1."
                                            FROM iqc_inspections
                                            WHERE ".$g1."='".$req->content1."' ".$sub_date_inspected."
                                            GROUP BY ".$g1."
                                        ) AS ins ON ins.".$g1." = main.".$g1."

                                        WHERE 1=1".$date_inspected." AND main.".$g1."='".$req->content1."'
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
                                        WHERE 1=1".$date_inspected." AND main.".$g1." = '".$wherein1[$key]."'
                                        AND main.".$g2." IN ('".$inVal2."')");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_inspected." AND
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
                                        WHERE 1=1".$date_inspected." AND main.".$g1." = '".$wherein1[$key]."'");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_inspected." AND
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

                                        WHERE 1=1".$date_inspected." AND main.".$g1."='".$req->content1."' AND main.".$g2." IN ('".$inVal."') ".
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

                                        WHERE 1=1".$date_inspected." AND main.".$g1."='".$req->content1."' AND main.".$g2."='".$req->content2."'
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
                                        WHERE 1=1".$date_inspected." AND main.".$g1."='".$req->content1."'
                                        AND main.".$g2." = '".$wherein2[$key]."'");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_inspected." AND
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

                                        WHERE 1=1".$date_inspected." AND main.".$g1."='".$req->content1."' 
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

                                        WHERE 1=1".$date_inspected." AND main.".$g1."='".$req->content1."' AND main.".$g2."='".$req->content2."'
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
                                        WHERE 1=1".$date_inspected." AND main.".$g1."='".$req->content1."'
                                        AND main.".$g2."='".$req->content2."'
                                        AND main.".$g3." = '".$wherein3[$key]."'");
                        }
                        
                    } else {
                        $details = DB::connection($this->mysql)
                                ->select("SELECT *
                                        FROM iqc_inspections as main
                                        WHERE 1=1".$date_inspected." AND
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
}
