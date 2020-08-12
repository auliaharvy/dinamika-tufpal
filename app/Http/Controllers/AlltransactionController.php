<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SjpStatusCollection;
use Illuminate\Support\Facades\Auth;
use App\Alltransaction;
use App\PoolPallet;
use App\Transporter;
use DB;

class AlltransactionController extends Controller
{
    public function index()
    {

        $pool_pallet_id = Auth::user()->reference_pool_pallet_id;
        $transporter_id = Auth::user()->reference_transporter_id;
        if($pool_pallet_id != null && $transporter_id == null){
            $pool_pallet = PoolPallet::find($pool_pallet_id);
            $pool_name = $pool_pallet->pool_name;
        }elseif($pool_pallet_id == null && $transporter_id != null){
            $transporter = Transporter::find($transporter_id);
            $transporter_name = $transporter->transporter_name;
        }



        $role = Auth::user()->role;
        if($pool_name == 'Pool Pallet DLI'){
            $alltransaction = DB::table('all_transaction as a')
            ->orderBy('created_at', 'DESC')
            ->leftJoin('surat_jalan_pallet as b', 'a.reference_sjp_id', '=', 'b.sjp_id')
            ->leftJoin('sjp_status as c', 'a.reference_sjp_status_id', '=', 'c.sjp_status_id')
            ->leftJoin('pallet_transfer as d', 'a.reference_pallet_transfer_id', '=', 'd.pallet_transfer_id')
            ->leftJoin('ber_missing_pallet as e', 'a.reference_ber_missing_id', '=', 'e.ber_missing_pallet_id')
            ->leftJoin('new_pallet as f', 'a.reference_new_pallet_id', '=', 'f.new_pallet_id')
            ->leftJoin('damaged_pallet as g', 'a.reference_damaged_pallet_id', '=', 'g.damaged_pallet_id')
            ->leftJoin('repaired_pallet as h', 'a.reference_repaired_pallet_id', '=', 'h.repaired_pallet_id')
            ->leftJoin('transporter_adjusment as i', 'a.reference_transporter_adjusment_id', '=', 'i.transporter_adjusment_id')
            ->select('a.*', 'b.sjp_number', 'c.sjps_number', 'd.tp_number',
                    'e.bmp_number', 'f.np_number', 'g.dp_number', 'h.rp_number', 'i.ta_number')
            ->paginate(10000000)
            ->toArray();
        }
        elseif($pool_name != 'Pool Pallet DLI'){
            $alltransaction = DB::table('all_transaction as a')
            ->orderBy('created_at', 'DESC')
            ->leftJoin('surat_jalan_pallet as b', 'a.reference_sjp_id', '=', 'b.sjp_id')
            ->leftJoin('sjp_status as c', 'a.reference_sjp_status_id', '=', 'c.sjp_status_id')
            ->leftJoin('pallet_transfer as d', 'a.reference_pallet_transfer_id', '=', 'd.pallet_transfer_id')
            ->leftJoin('ber_missing_pallet as e', 'a.reference_ber_missing_id', '=', 'e.ber_missing_pallet_id')
            ->leftJoin('new_pallet as f', 'a.reference_new_pallet_id', '=', 'f.new_pallet_id')
            ->leftJoin('damaged_pallet as g', 'a.reference_damaged_pallet_id', '=', 'g.damaged_pallet_id')
            ->leftJoin('repaired_pallet as h', 'a.reference_repaired_pallet_id', '=', 'h.repaired_pallet_id')
            ->leftJoin('transporter_adjusment as i', 'a.reference_transporter_adjusment_id', '=', 'i.transporter_adjusment_id')
            ->select('a.*', 'b.sjp_number', 'c.sjps_number', 'd.tp_number',
                    'e.bmp_number', 'f.np_number', 'g.dp_number', 'h.rp_number', 'i.ta_number')
            ->where('a.departure_pool', $pool_name)
            ->orWhere('a.destination_pool', $pool_name)
            ->orWhere('a.pool_pallet', $pool_name)
            // ->orWhere('a.transporter', $transporter_name)
            ->paginate(10000000)
            ->toArray();
        }
        else{
            $alltransaction = DB::table('all_transaction as a')
            ->orderBy('created_at', 'DESC')
            ->leftJoin('surat_jalan_pallet as b', 'a.reference_sjp_id', '=', 'b.sjp_id')
            ->leftJoin('sjp_status as c', 'a.reference_sjp_status_id', '=', 'c.sjp_status_id')
            ->leftJoin('pallet_transfer as d', 'a.reference_pallet_transfer_id', '=', 'd.pallet_transfer_id')
            ->leftJoin('ber_missing_pallet as e', 'a.reference_ber_missing_id', '=', 'e.ber_missing_pallet_id')
            ->leftJoin('new_pallet as f', 'a.reference_new_pallet_id', '=', 'f.new_pallet_id')
            ->leftJoin('damaged_pallet as g', 'a.reference_damaged_pallet_id', '=', 'g.damaged_pallet_id')
            ->leftJoin('repaired_pallet as h', 'a.reference_repaired_pallet_id', '=', 'h.repaired_pallet_id')
            ->leftJoin('transporter_adjusment as i', 'a.reference_transporter_adjusment_id', '=', 'i.transporter_adjusment_id')
            ->select('a.*', 'b.sjp_number', 'c.sjps_number', 'd.tp_number',
                    'e.bmp_number', 'f.np_number', 'g.dp_number', 'h.rp_number', 'i.ta_number')
            ->where('a.transporter', $transporter_name)
            ->paginate(10000000)
            ->toArray();
        }
        return $alltransaction;
        // $sjpstatus = new SjpStatusCollection(SjpStatus::paginate(10));
		//  return $sjpstatus;
        // return response()->json(Sjp::all()->toArray());
    }
}
