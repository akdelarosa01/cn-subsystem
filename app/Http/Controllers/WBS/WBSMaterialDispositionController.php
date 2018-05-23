<?php

namespace App\Http\Controllers\WBS;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use DB;
use Config;
use Carbon\Carbon;
use PDF;
use App;
use Excel;

class WBSMaterialDispositionController extends Controller
{
    protected $mysql;
    protected $mssql;
    protected $common;
    
    public function __construct()
    {
        $this->middleware('auth');
        $com = new CommonController;

        if (Auth::user() != null) {
            $this->mysql = $com->userDBcon(Auth::user()->productline,'wbs');
            $this->mssql = $com->userDBcon(Auth::user()->productline,'mssql');
            $this->common = $com->userDBcon(Auth::user()->productline,'common');
        } else {
            return redirect('/');
        }
    }

    public function getMatDisposistion()
    {
    	$common = new CommonController;
        if(!$common->getAccessRights(Config::get('constants.MODULE_CODE_MATDIS'), $userProgramAccess))
        {
            return redirect('/home');
        }
        else
        {

	        return view('wbs.materialdisposition',['userProgramAccess' => $userProgramAccess]);
	    }
    }

    public function wbsdispositiongetrows(){
    	$table = DB::connection($this->mysql)->table('tbl_wbs_material_disposition')->orderBy('id','DESC')->get();
    	return $table;
    }

    public function dispositionsave(Request $input){
    	$itemcode = $input->itemcode;
    	$itemname = $input->itemname;
    	$lotno = $input->lotno;
    	$lotqty = $input->lotqty;
    	$disposition = $input->disposition;
    	$createdby = $input->createdby;
    	$createddate = $input->createddate;
    	$updatedby = $input->updatedby;
    	$updateddate = $input->updateddate;
    	$status = $input->status;
    	$id = $input->id;
    	$hdqty = $input->hdqty;

    	if($status == "ADD"){
    		$ok = DB::connection($this->mysql)->table('tbl_wbs_material_disposition')
		    		->insert([
		    				'itemcode'=>$itemcode,
		    				'itemname'=>$itemname,
		    				'lotno'=>$lotno,
		    				'lotqty'=>$lotqty,
		    				'disposition'=>$disposition,
		    				'createdby'=>$createdby,
		    				'createddate'=>$createddate,
		    				'updatedby'=>$updatedby,
		    				'updateddate'=>$updateddate
		    			]);
    				DB::connection($this->mysql)->table('tbl_wbs_inventory')
    					->where('item',$itemcode)
    					->where('item_desc',$itemname)
    					->where('lot_no',$lotno)	
    					->update(array(
    						'qty'=>$hdqty
    					));

    		if($ok){
    			return "SAVED";
    		}else{
    			return "NOT";
    		}
    	}
    	if($status == "EDIT"){
    		$ok = DB::connection($this->mysql)->table('tbl_wbs_material_disposition')
		    		->where('id',$id)
		    		->update(array(
		    				'itemcode'=>$itemcode,
		    				'itemname'=>$itemname,
		    				'lotno'=>$lotno,
		    				'lotqty'=>$lotqty,
		    				'disposition'=>$disposition,
		    				'createdby'=>$createdby,
		    				'createddate'=>$createddate,
		    				'updatedby'=>$updatedby,
		    				'updateddate'=>$updateddate
		    			));	
		    		DB::connection($this->mysql)->table('tbl_wbs_inventory')
    					->where('item',$itemcode)
    					->where('item_desc',$itemname)
    					->where('lot_no',$lotno)	
    					->update(array(
    						'qty'=>$hdqty
    					));
    		if($ok){
    			return "UPDATED";
    		}else{
    			return "NOT";
    		}
    	}
    }

    public function deleteDisposition(Request $input){
    	$id = $input->id;
    	$ok = DB::connection($this->mysql)->table('tbl_wbs_material_disposition')
    		->where('id',$id)
    		->delete();

    	if($ok){
			return "DELETED";
		}else{
			return "NOT";
		}	
    }

    public function dispositionExportToExcel(){
    	try
        { 
            $dt = Carbon::now();
            $date = substr($dt->format('Ymd'), 2);
            
            Excel::create('OQC_Inspection_Report'.$date, function($excel)
            {
                $excel->sheet('Sheet1', function($sheet)
                {
                    $dt = Carbon::now();
                    $date = $dt->format('m/d/Y');

                    $sheet->cell('A1',"Item/Part Code");
                    $sheet->cell('B1',"Item Name");
                    $sheet->cell('C1',"Quantity");
                    $sheet->cell('D1',"Lot No");
                    $sheet->cell('E1',"Expiration");
                    $sheet->cell('F1',"Remarks");
             
                    $sheet->setHeight(1,20);
                    $sheet->row(1, function ($row) {
                        $row->setFontFamily('Calibri');
                        $row->setBackground('#ADD8E6');
                        $row->setFontSize(15);
                        $row->setAlignment('center');
                    });
                   
                    $sheet->setStyle(array(
                        'font' => array(
                            'name'  =>  'Calibri',
                            'size'  =>  10
                        )
                    ));

			    	$field = DB::connection($this->mysql)->table('tbl_wbs_material_disposition')->orderBy('id','DESC')->get();
                    $row = 2;
                    foreach ($field as $key => $val) {
                        $sheet->cell('A'.$row, $val->itemcode);
                        $sheet->cell('B'.$row, $val->itemname);
                        $sheet->cell('C'.$row, $val->lotqty);
                        $sheet->cell('D'.$row, $val->lotno);
                        $sheet->cell('E'.$row, $val->createddate);
                        $sheet->cell('F'.$row, $val->disposition);
                
                        $sheet->row($row, function ($row) {
                            $row->setFontFamily('Calibri');
                            $row->setFontSize(10);
                            $row->setAlignment('center');
                        });
                        $sheet->setHeight($row,20);
                        $row++;
                    }
                });

            })->download('xls');
        } catch (Exception $e) {
            return redirect(url('/wbsprodmatreturn'))->with(['err_message' => $e]);
        }    
    }

    public function itemcodechange(Request $input){
    	$itemcode = $input->itemcode;
    	$table = DB::connection($this->mysql)->table('tbl_wbs_inventory')
    				->select('item','item_desc','qty','lot_no')
    				->where('item',$itemcode)
    				->get();

    	return $table;
    }

}
