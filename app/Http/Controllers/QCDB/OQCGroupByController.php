<?php

namespace App\Http\Controllers\QCDB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Auth;
use DB;
use Config;
use PDF;
use Carbon\Carbon;
use Excel;

class OQCGroupByController extends Controller
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

    public function index()
    {
        if(!$this->com->getAccessRights(Config::get('constants.MODULE_CODE_OQCDB')
                                    , $userProgramAccess))
        {
            return redirect('/home');
        }
        else
        {
            return view('qcdb.oqc_groupby',['userProgramAccess' => $userProgramAccess]);
        }
    }

    public function CalculateDPPM(Request $req)
    {
        $g1 = (!isset($req->field1) || $req->field1 == '' || $req->field1 == null)? '': $req->field1;
        $g2 = (!isset($req->field2) || $req->field2 == '' || $req->field2 == null)? '': $req->field2;
        $g3 = (!isset($req->field3) || $req->field3 == '' || $req->field3 == null)? '': $req->field3;
        $content1 = (!isset($req->content1) || $req->content1 == '' || $req->content1 == null)? '%': $req->content1;
        $content2 = (!isset($req->content2) || $req->content2 == '' || $req->content2 == null)? '%': $req->content2;
        $content3 = (!isset($req->content3) || $req->content3 == '' || $req->content3 == null)? '%': $req->content3;

        DB::connection($this->mysql)
            ->select(
                DB::raw(
                    "CALL GetOQCGroupBy(
                    '".$this->com->convertDate($req->gfrom,'Y-m-d')."',
                    '".$this->com->convertDate($req->gto,'Y-m-d')."',
                    '".$g1."',
                    '".$content1."',
                    '".$g2."',
                    '".$content2."',
                    '".$g3."',
                    '".$content3."')"
                )
            );

        $data = [];
        $node1 = [];
        $node2 = [];
        $node3 = [];
        $details = [];

        $check = DB::connection($this->mysql)->table('oqc_inspection_group')->count();

        if ($check > 0) {
            if ($g1 !== '') {
                $grp1_query = DB::connection($this->mysql)->table('oqc_inspection_group')
                                ->select('g1','L1','DPPM1')
                                ->groupBy($g1)
                                ->orderBy('g1')
                                ->get();
                
                foreach ($grp1_query as $key => $gr1) {
                    if ($g2 == '') {
                        $details_query = DB::connection($this->mysql)->table('oqc_inspection_group')
                                        ->select('assembly_line',
                                                'lot_no',
                                                'app_date',
                                                'app_time',
                                                'prod_category',
                                                'po_no',
                                                'device_name',
                                                'customer',
                                                'po_qty',
                                                'family',
                                                'type_of_inspection',
                                                'severity_of_inspection',
                                                'inspection_lvl',
                                                'aql',
                                                'accept',
                                                'reject',
                                                'date_inspected',
                                                'ww',
                                                'fy',
                                                'time_ins_from',
                                                'time_ins_to',
                                                'shift',
                                                'inspector',
                                                'submission',
                                                'coc_req',
                                                'judgement',
                                                'lot_qty',
                                                'sample_size',
                                                'lot_inspected',
                                                'lot_accepted',
                                                'num_of_defects',
                                                'remarks',
                                                'type',
                                                'modid')
                                        ->where('g1',$gr1->g1)
                                        ->get();

                        array_push($node1, [
                            'group' => $gr1->g1,
                            'LAR' => $gr1->L1,
                            'DPPM' => $gr1->DPPM1,
                            'field' => $g1,
                            'details' => $details_query
                        ]);
                    } else {

                        $grp2_query = DB::connection($this->mysql)->table('oqc_inspection_group')
                                        ->select('g1','g2','L2','DPPM2')
                                        ->where('g1',$gr1->g1)
                                        ->groupBy($g2)
                                        ->orderBy('g2')
                                        ->get();

                        foreach ($grp2_query as $key => $gr2) {
                            if ($g3 == '') {
                                $details_query = DB::connection($this->mysql)->table('oqc_inspection_group')
                                                    ->select('assembly_line',
                                                            'lot_no',
                                                            'app_date',
                                                            'app_time',
                                                            'prod_category',
                                                            'po_no',
                                                            'device_name',
                                                            'customer',
                                                            'po_qty',
                                                            'family',
                                                            'type_of_inspection',
                                                            'severity_of_inspection',
                                                            'inspection_lvl',
                                                            'aql',
                                                            'accept',
                                                            'reject',
                                                            'date_inspected',
                                                            'ww',
                                                            'fy',
                                                            'time_ins_from',
                                                            'time_ins_to',
                                                            'shift',
                                                            'inspector',
                                                            'submission',
                                                            'coc_req',
                                                            'judgement',
                                                            'lot_qty',
                                                            'sample_size',
                                                            'lot_inspected',
                                                            'lot_accepted',
                                                            'num_of_defects',
                                                            'remarks',
                                                            'type',
                                                            'modid')
                                                    ->where('g1',$gr1->g1)
                                                    ->where('g2',$gr2->g2)
                                                    ->get();
                                array_push($node2, [
                                    'g1' => $gr1->g1,
                                    'group' => $gr2->g2,
                                    'LAR' => $gr2->L2,
                                    'DPPM' => $gr2->DPPM2,
                                    'field' => $g2,
                                    'details' => $details_query
                                ]);
                            } else {

                               $grp3_query = DB::connection($this->mysql)->table('oqc_inspection_group')
                                                ->select('g1','g2','g3','L3','DPPM3')
                                                ->where('g1',$gr1->g1)
                                                ->where('g2',$gr2->g2)
                                                ->groupBy($g3)
                                                ->orderBy('g3')
                                                ->get();

                                foreach ($grp3_query as $key => $gr3) {
                                    $details_query = DB::connection($this->mysql)->table('oqc_inspection_group')
                                                        ->select('assembly_line',
                                                                'lot_no',
                                                                'app_date',
                                                                'app_time',
                                                                'prod_category',
                                                                'po_no',
                                                                'device_name',
                                                                'customer',
                                                                'po_qty',
                                                                'family',
                                                                'type_of_inspection',
                                                                'severity_of_inspection',
                                                                'inspection_lvl',
                                                                'aql',
                                                                'accept',
                                                                'reject',
                                                                'date_inspected',
                                                                'ww',
                                                                'fy',
                                                                'time_ins_from',
                                                                'time_ins_to',
                                                                'shift',
                                                                'inspector',
                                                                'submission',
                                                                'coc_req',
                                                                'judgement',
                                                                'lot_qty',
                                                                'sample_size',
                                                                'lot_inspected',
                                                                'lot_accepted',
                                                                'num_of_defects',
                                                                'remarks',
                                                                'type',
                                                                'modid')
                                                        ->where('g1',$gr1->g1)
                                                        ->where('g2',$gr2->g2)
                                                        ->where('g3',$gr3->g3)
                                                        ->get();
                                    array_push($node3, [
                                        'g1' => $gr1->g1,
                                        'g2' => $gr2->g2,
                                        'group' => $gr3->g3,
                                        'LAR' => $gr3->L3,
                                        'DPPM' => $gr3->DPPM3,
                                        'field' => $g3,
                                        'details' => $details_query
                                    ]);
                                }

                                array_push($node2, [
                                    'g1' => $gr1->g1,
                                    'group' => $gr2->g2,
                                    'LAR' => $gr2->L2,
                                    'DPPM' => $gr2->DPPM2,
                                    'field' => $g2,
                                    'details' => []
                                ]);
                            }
                        }

                        array_push($node1, [
                            'group' => $gr1->g1,
                            'LAR' => $gr1->L1,
                            'DPPM' => $gr1->DPPM1,
                            'field' => $g1,
                            'details' => []
                        ]);
                    }
                }
            }

            $data = [
                'node1' => $node1,
                'node2' => $node2,
                'node3' => $node3
            ];
        } else {
            $data = [
                'msg' => "No data generated.",
                'status' => 'failed'
            ];
        }

            
        
        
        return response()->json($data);
    }

    public function GrpByPDFReport()
    {
        $date = '';
        $po = '';

        $header = DB::connection($this->mysql)->table('oqc_inspection_excel')
                    ->groupBy('prod_category',
                            'po_no',
                            'device_name')
                    ->select('prod_category',
                            'po_no',
                            'device_name',
                            'customer',
                            DB::raw('SUM(po_qty) AS po_qty'),
                            'severity_of_inspection',
                            'inspection_lvl',
                            'aql',
                            'accept',
                            'reject',
                            'coc_req')
                    ->get();

        $details = DB::connection($this->mysql)->table('oqc_inspection_excel')->get();

        $dt = Carbon::now();
        $company_info = $this->com->getCompanyInfo();
        $date = substr($dt->format('  M j, Y  h:i A '), 2);

        $data = [
            'company_info' => $company_info,
            'details' => $details,
            'date' => $date,
            'header' => $header
        ];

        $pdf = PDF::loadView('pdf.oqcwithpo', $data)
                    ->setPaper('A4')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-bottom', 5)
                    ->setOption('margin-left', 1)
                    ->setOption('margin-right', 1)
                    ->setOrientation('landscape');

        return $pdf->inline('OQC_Inspection_'.Carbon::now());
    }

    public function GrpByExcelReport()
    {
        $dt = Carbon::now();
        $dates = substr($dt->format('Ymd'), 2);

        Excel::create('OQC_Inspection_Report'.$dates, function($excel)
        {
            $excel->sheet('Sheet1', function($sheet)
            {
                $sheet->setFreeze('A12');
                $date = '';
                $po = '';

                $details = DB::connection($this->mysql)->table('oqc_inspection_excel')->get();


                $dt = Carbon::now();
                $com_info = $this->com->getCompanyInfo();

                $date = substr($dt->format('  M j, Y  h:i A '), 2);

                $sheet->setHeight(1, 15);
                $sheet->mergeCells('A1:AG1');
                $sheet->cells('A1:P1', function($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cell('A1',$com_info['name']);

                $sheet->setHeight(2, 15);
                $sheet->mergeCells('A2:AG2');
                $sheet->cells('A2:AG2', function($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cell('A2',$com_info['address']);

                $sheet->setHeight(4, 20);
                $sheet->mergeCells('A4:AG4');
                $sheet->cells('A4:AG4', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFont([
                        'family'     => 'Calibri',
                        'size'       => '14',
                        'bold'       =>  true,
                        'underline'  =>  true
                    ]);
                });
                $sheet->cell('A4',"OQC INSPECTION RESULT");

                $sheet->setHeight(6, 15);
                $sheet->cells('A6:AG6', function($cells) {
                    $cells->setBorder('thick','thick','thick','thick');
                    $cells->setFont([
                        'family'     => 'Calibri',
                        'size'       => '11',
                        'bold'       =>  true,
                    ]);
                });



                $sheet->cell('B6',"P.O.");
                $sheet->cell('C6',"Device Name");
                $sheet->cell('D6',"Customer");
                $sheet->cell('E6',"P.O. Qty.");
                $sheet->cell('F6',"Family");
                $sheet->cell('G6',"Assembly Line");
                $sheet->cell('H6',"Lot No.");
                $sheet->cell('I6',"App. date");
                $sheet->cell('J6',"App. time");
                $sheet->cell('K6',"Product Category");
                $sheet->cell('L6',"Type of Inspection");
                $sheet->cell('M6',"Severity of Inspection");
                $sheet->cell('N6',"Inspection Lvl");
                $sheet->cell('O6',"AQL");
                $sheet->cell('P6',"Accept");
                $sheet->cell('Q6',"Reject");
                $sheet->cell('R6',"Date Inspected");
                $sheet->cell('S6',"WW");
                $sheet->cell('T6',"FY");
                $sheet->cell('U6',"From");
                $sheet->cell('V6',"To");
                $sheet->cell('W6',"Shift");
                $sheet->cell('X6',"Inspector");
                $sheet->cell('Y6',"Submission");
                $sheet->cell('Z6',"COC Requirement");
                $sheet->cell('AA6',"Judgement");
                $sheet->cell('AB6',"Lot Qty.");
                $sheet->cell('AC6',"Sample_size");
                $sheet->cell('AD6',"Lot Inspected");
                $sheet->cell('AE6',"Lot Accepted");
                $sheet->cell('AF6',"No. of Defects");
                $sheet->cell('AG6',"Remarks");

                $row = 7;

                $sheet->setHeight(7, 15);

                $lot_qty = 0;
                $po_qty = 0;
                $balance = 0;

                foreach ($details as $key => $qc) {
                    $lot_qty += $qc->lot_qty;
                    $po_qty += $qc->po_qty;

                    $sheet->cells('A'.$row.':AG'.$row, function($cells) {
                        // Set all borders (top, right, bottom, left)
                        $cells->setBorder(array(
                            'top'   => array(
                                'style' => 'thick'
                            ),
                        ));
                        $cells->setFont([
                            'family'     => 'Calibri',
                            'size'       => '11',
                        ]);
                    });
                    $sheet->cell('B'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->po_no);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('C'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->device_name);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('D'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->customer);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('E'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->po_qty);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('F'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->family);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('G'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->assembly_line);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('H'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->lot_no);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('I'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->app_date);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('J'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->app_time);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('K'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->prod_category);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('L'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->type_of_inspection);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('M'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->severity_of_inspection);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('N'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->inspection_lvl);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('O'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->aql);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('P'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->accept);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('Q'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->reject);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('R'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->date_inspected);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('S'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->ww);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('T'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->fy);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('U'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->time_ins_from);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('V'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->time_ins_to);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('W'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->shift);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('X'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->inspector);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('Y'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->submission);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('Z'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->coc_req);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('AA'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->judgement);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('AB'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->lot_qty);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('AC'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->sample_size);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('AD'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->lot_inspected);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('AE'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->lot_accepted);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('AF'.$row, function($cell) use($qc) {
                        $cell->setValue(($qc->num_of_defects == 0)? '0.0':$qc->num_of_defects);
                        $cell->setBorder('thin','thin','thick','thin');
                    });

                    $sheet->cell('AG'.$row, function($cell) use($qc) {
                        $cell->setValue($qc->remarks);
                        $cell->setBorder('thin','thin','thick','thin');
                    });
                    
                    $sheet->row($row, function ($row) {
                        $row->setFontFamily('Calibri');
                        $row->setFontSize(11);
                    });
                    $sheet->setHeight($row,20);
                    $row++;
                }


                $balance = $po_qty - $lot_qty;

                $sheet->cell('B'.$row, "Total Qty:");
                $sheet->cell('C'.$row, $lot_qty);
                $sheet->setHeight($row,20);
                $row++;
                $sheet->cell('B'.$row, "Balance:");
                $sheet->cell('C'.$row, $balance);
                $sheet->setHeight($row,20);
                $row++;
                $sheet->cell('B'.$row, "Date:");
                $sheet->cell('C'.$row, $date);
                $sheet->setHeight($row,20);
            });

        })->download('xls');
    }
}
