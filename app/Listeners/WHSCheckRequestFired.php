<?php

namespace App\Listeners;

use App\Events\WHSCheckRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class WHSCheckRequestFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WHSCheckRequest  $event
     * @return void
     */
    public function handle(WHSCheckRequest $event)
    {
        $db = DB::connection($event->conn)->table('tbl_request_detail')
                ->select('transno','code','requestqty')
                ->get();

        $status = "Closed";
        foreach ($db as $key => $pmr) {
            // $chck = DB::connection($event->conn)->table('tbl_wbs_warehouse_mat_issuance_details')
            //         ->select('issuance_no','item',DB::raw("SUM(issued_qty_t) as issued_qty"))
            //         ->groupBy('issuance_no','item')
            //         ->where('request_no',$pmr->transno)
            //         ->count();
            
            // if ($chck > 0) {
            //     $whs = DB::connection($event->conn)->table('tbl_wbs_warehouse_mat_issuance_details')
            //             ->select('issuance_no','item',DB::raw("SUM(issued_qty_t) as issued_qty"))
            //             ->groupBy('issuance_no','item')
            //             ->where('request_no',$pmr->transno)
            //             ->first();

            //     if ($pmr->requestqty == $whs->issued_qty) {
            //         DB::connection($event->conn)->table('tbl_request_summary')
            //             ->where('transno',$pmr->transno)
            //             ->update(['status' => 'Closed']);
            //     }
            // }
            $check = DB::connection($event->conn)->table('tbl_request_detail')
                        ->select('transno',DB::raw('SUM(requestqty) as requestqty'),
                                DB::raw('SUM(servedqty) as servedqty'))
                        ->where('transno',$pmr->transno)
                        ->groupBy('transno')
                        ->get();
            //if (isset($check[$key]->servedqty)) {
                if ( ($check[0]->servedqty > 0) && ($check[0]->requestqty == $check[0]->servedqty) ) {
                    $status = 'Closed';
                }

                if ( ($check[0]->servedqty < 1) ) {
                    $status = 'Alert';
                }

                if (($check[0]->servedqty > 0) && ($check[0]->requestqty > $check[0]->servedqty)) {
                    $status = 'Serving';
                }

                DB::connection($event->conn)->table('tbl_request_summary')
                    ->where('transno',$pmr->transno)
                    ->update(['status' => $status]);

                DB::connection($event->conn)->table('tbl_wbs_warehouse_mat_issuance')
                        ->where('request_no',$pmr->transno)
                        ->update(['status' => $status]);
            //}
            
        }

        // $db = DB::connection($event->conn)->table('tbl_request_detail')
        //         ->select('transno','code','requestqty')
        //         ->whereIn('status',['Alert','Serving'])
        //         ->get();
                
        // foreach ($db as $key => $pmr) {
        //     $chck = DB::connection($event->conn)->table('tbl_wbs_warehouse_mat_issuance_details')
        //             ->select('issuance_no','item',DB::raw("SUM(issued_qty_t) as issued_qty"))
        //             ->groupBy('issuance_no','item')
        //             ->where('request_no',$pmr->transno)
        //             ->count();
            
        //     if ($chck > 0) {
        //         $whs = DB::connection($event->conn)->table('tbl_wbs_warehouse_mat_issuance_details')
        //                 ->select('issuance_no','item',DB::raw("SUM(issued_qty_t) as issued_qty"))
        //                 ->groupBy('issuance_no','item')
        //                 ->where('request_no',$pmr->transno)
        //                 ->first();
        //         if ($pmr->requestqty == $whs->issued_qty) {
        //             DB::connection($event->conn)->table('tbl_request_summary')
        //                 ->where('transno',$pmr->transno)
        //                 ->update(['status' => 'Closed']);

        //             DB::connection($event->conn)->table('tbl_wbs_warehouse_mat_issuance')
        //                 ->where('request_no',$pmr->transno)
        //                 ->update(['status' => 'Closed']);
        //         }
        //     }
            
        // }
    }
}
