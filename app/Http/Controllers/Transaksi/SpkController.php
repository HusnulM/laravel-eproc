<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Mail\NotifApproveWoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DataTables, Auth, DB;
use Validator,Redirect,Response;
use PDF;

class SpkController extends Controller 
{
    public function index(){
        return view('transaksi.spk.listchecklist');
    }

    public function create($ckID){
        $mekanik    = DB::table('t_mekanik')->get();
        $department = DB::table('t_department')->get();
        $cklist     = DB::table('v_checklist_kendaraan')->where('id', $ckID)->first();
        $kendaraan  = DB::table('t_kendaraan')->where('id', $cklist->no_plat)->first();
        // return $cklist;
        return view('transaksi.spk.createwo',
            [
                'mekanik'    => $mekanik, 
                'department' => $department, 
                'cklist'     => $cklist, 
                'kendaraan'  => $kendaraan
            ]
        );
    }

    public function detailChecklist($id){
        $header = DB::table('v_checklist_kendaraan')->where('id', $id)->first(); 
        $group1 = DB::table('t_ck_administrasi')->where('no_checklist', $header->no_checklist)->get();
        // return $header;
        $group2 = DB::table('t_ck_kelengkapan_kend')->where('no_checklist', $header->no_checklist)->get();
        $group3 = DB::table('t_ck_kondisi_kend')->where('no_checklist', $header->no_checklist)->get();
        $group4 = DB::table('t_ck_kondisi_ban')->where('no_checklist',  $header->no_checklist)->get();
        $attachments = DB::table('t_attachments')->where('doc_object','CKL')->where('doc_number', $header->no_checklist)->get();
        return view('transaksi.spk.detailceklisttidaklayak', 
            [
                'header' => $header,
                'group1' => $group1,
                'group2' => $group2,
                'group3' => $group3,
                'group4' => $group4,
                'attachments'   => $attachments
            ]);
    }

    public function listwoview(){
        return view('transaksi.spk.listspk');
    }

    public function processWO(){
        return view('transaksi.spk.process');
    }

    public function changeWO($id){
        $wohdr = DB::table('v_spk01')->where('id', $id)->first();
        if($wohdr){
            // $mekanik    = DB::table('t_mekanik')->where('id', $wohdr->mekanik)->first();
            $warehouse  = DB::table('t_warehouse')->where('id', $wohdr->whscode)->first();
            $kendaraan  = DB::table('t_kendaraan')->where('no_kendaraan', $wohdr->license_number)->first();
            $woitem     = DB::table('t_wo02')->where('wonum', $wohdr->wonum)->get();
            $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $wohdr->wonum)->get();
            // $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $prhdr->wonum)->get();
            $approvals  = DB::table('v_wo_approval')->where('wonum', $wohdr->wonum)->get();
            $cklist     = DB::table('v_checklist_kendaraan')->where('no_checklist', $wohdr->cheklistnumber)->first();
            // return $woitem;
            return view('transaksi.spk.change', 
                [
                    'prhdr'       => $wohdr, 
                    'pritem'      => $woitem,
                    'cklist'      => $cklist,
                    'warehouse'   => $warehouse,
                    'kendaraan'   => $kendaraan,
                    'attachments' => $attachments,
                    'approvals'   => $approvals, 
                ]);
        }else{
            return Redirect::to("/logistic/wo/listwo")->withError('Data SPK/Work Order tidak ditemukan');
        }
    }

    public function dataCekListTidakLayak(Request $request){
        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
        }
        $query = DB::table('v_checklist_kendaraan')->where('hasil_pemeriksaan','TIDAK LAYAK')->where('wocreated', 'N')
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }

    

    public function findkendaraan(Request $request){
        $query['data'] = DB::table('t_kendaraan')
                ->select('no_kendaraan', 'model_kendaraan', 'engine_model', 'engine_sn', 'chassis_sn')
                ->where('no_kendaraan', 'like', '%'. $request->search . '%')
                ->orWhere('model_kendaraan', 'like', '%'. $request->search . '%')
                ->orWhere('engine_sn', 'like', '%'. $request->search . '%')
                ->orWhere('chassis_sn', 'like', '%'. $request->search . '%')
                ->orWhere('engine_model', 'like', '%'. $request->search . '%')
                ->get();

        // return \Response::json($query);
        return $query;
    }

    public function listApprovedPbj(Request $request){
        // return $request->params;

        if(isset($request->params)){
            $params = $request->params;        
            $whereClause = $params['sac'];
            $noPol       = $params['nopol'];
        }
        $query = DB::table('v_pbj02')
                 ->where('pbj_status', 'A')
                 ->where('approvestat', 'A')
                 ->where('wocreated', 'N')
                 ->where('unit_desc', $noPol)
                 ->orderBy('id');
        return DataTables::queryBuilder($query)->toJson();
    }  

    public function wodetail($id){
        $wohdr = DB::table('v_spk01')->where('id', $id)->first();
        if($wohdr){
            // $mekanik    = DB::table('t_mekanik')->where('id', $wohdr->mekanik)->first();
            $warehouse  = DB::table('t_warehouse')->where('id', $wohdr->whscode)->first();
            // $kendaraan  = DB::table('t_kendaraan')->where('id', $wohdr->license_number)->first();
            $woitem     = DB::table('t_wo02')->where('wonum', $wohdr->wonum)->get();
            $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $wohdr->wonum)->get();
            // $attachments = DB::table('t_attachments')->where('doc_object','SPK')->where('doc_number', $prhdr->wonum)->get();
            $approvals  = DB::table('v_wo_approval')->where('wonum', $wohdr->wonum)->get();
            // $department = DB::table('v_wo_approval')->where('wonum', $prhdr->wonum)->first();
            // return $woitem;

            return view('transaksi.spk.detailspk', 
                [
                    'prhdr'       => $wohdr, 
                    'pritem'      => $woitem,
                    // 'mekanik'     => $mekanik,
                    'warehouse'   => $warehouse,
                    // 'kendaraan'   => $kendaraan,
                    'attachments' => $attachments,
                    'approvals'   => $approvals, 
                ]);
        }else{
            return Redirect::to("/logistic/wo/listwo")->withError('Data SPK/Work Order tidak ditemukan');
        }
    }

    public function listdatawo(Request $request){
        $query = DB::table('v_spk01');

        $checkObjAuth = DB::table('user_object_auth')
                        ->where('object_name', 'ALLOW_DISPLAY_ALL_DEPT')
                        ->where('object_val', 'Y')
                        ->where('userid', Auth::user()->id)
                        ->first();
        if($checkObjAuth){

        }else{
            $query->where('createdby', Auth::user()->email);
        }

        $query->orderBy('id', 'DESC');

        return DataTables::queryBuilder($query)
        ->editColumn('wodate', function ($query){
            return [
                'wodate1' => \Carbon\Carbon::parse($query->wodate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function listdatawotoprocess(Request $request){
        $query = DB::table('v_spk01');

        $query->where('createdby', Auth::user()->email);
        $query->where('wo_process', '<>', 'Closed');

        $query->orderBy('id', 'DESC');

        return DataTables::queryBuilder($query)
        ->editColumn('wodate', function ($query){
            return [
                'wodate1' => \Carbon\Carbon::parse($query->wodate)->format('d-m-Y')
             ];
        })
        ->toJson();
    }

    public function save(Request $req){
        DB::beginTransaction();
        try{
            $tgl   = substr($req['servicedate'], 8, 2);
            $bulan = substr($req['servicedate'], 5, 2);
            $tahun = substr($req['servicedate'], 0, 4);
            // return $tgl . ' - ' . $bulan . ' - ' . $tahun;
            $ptaNumber = generateWONumber($tahun, $bulan);

            // return $ptaNumber;

            // $amount = $req['nominal'];
            // $amount = str_replace(',','',$amount);
            $woID = DB::table('t_wo01')->insertGetId([
                'wonum'             => $ptaNumber,
                'wodate'            => $req['servicedate'],
                'description'       => $req['descr'],
                'mekanik'           => $req['mekanik'],
                'whscode'           => $req['whscode'],
                'license_number'    => $req['licenseNumber'],
                // 'last_odo_meter'    => $req['lastOdoMeter'],
                'schedule_type'     => $req['schedule'],
                'issued'            => $req['issued'],
                'cheklistnumber'    => $req['cknumber'],
                'createdon'         => date('Y-m-d H:m:s'),
                'createdby'         => Auth::user()->email ?? Auth::user()->username
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $pbjnum   = $req['pbjnum'];

            $insertData = array();
            $woItems    = array();
            $count = 0;            

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                // $latestStock = DB::table('v_inv_summary_stock')
                //                ->where('material', $parts[$i])
                //                ->where('whsid',  $req['whscode'])->first();
                // if($latestStock){
                //     if($latestStock->quantity < $qty){
                //         DB::rollBack();
                //         return Redirect::to("/logistic/wo/create/".$req['ckID'])->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                //     }
                // }else{
                //     DB::rollBack();
                //     return Redirect::to("/logistic/wo/create/".$req['ckID'])->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                // }

                $count = $count + 1;
                $data = array(
                    'wonum'        => $ptaNumber,
                    'woitem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'refdoc'       => $pbjnum[$i] ?? null,
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
                array_push($woItems, $data);
            }
            insertOrUpdate($insertData,'t_wo02');

            DB::table('t_checklist_kendaraan')->where('id', $req['ckID'])->update([
                'wonum'     => $ptaNumber,
                'wocreated' => 'Y'
            ]);

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();
    
                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'SPK',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/SPK/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
                    );
                    array_push($insertFiles, $upfiles);
    
                    $efile->move('files/SPK/', $filename);  
                    // $efile->move(public_path().'/files/SPK/', $filename);  
                }
    
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }

            //Set Approval
            $approval = DB::table('v_workflow_budget')->where('object', 'SPK')->where('requester', Auth::user()->id)->get();
            if(sizeof($approval) > 0){
                
                for($a = 0; $a < sizeof($woItems); $a++){
                    $insertApproval = array();
                    foreach($approval as $row){
                        $is_active = 'N';
                        if($row->approver_level == 1){
                            $is_active = 'Y';
                        }
                        $approvals = array(
                            'wonum'             => $ptaNumber,
                            'woitem'            => $woItems[$a]['woitem'],
                            'approver_level'    => $row->approver_level,
                            'approver'          => $row->approver,
                            'creator'           => Auth::user()->id,
                            'is_active'         => $is_active,
                            'createdon'         => getLocalDatabaseDateTime()
                        );
                        array_push($insertApproval, $approvals);
                    }
                    insertOrUpdate($insertApproval,'t_wo_approval');
                }
            }else{
                DB::rollBack();
                return Redirect::to("/logistic/wo/create/".$req['ckID'])->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();

            $approverId = DB::table('v_workflow_budget')->where('object', 'SPK')
                            ->where('requester', Auth::user()->id)
                            ->where('approver_level', '1')
                            ->pluck('approver');

            $mailto = DB::table('users')
                    ->whereIn('id', $approverId)
                    ->pluck('email');   

            $dataApproveWO = DB::table('v_rwo01')
                    ->where('wonum', $ptaNumber)
                    ->orderBy('id')->get();

            Mail::to($mailto)->queue(new NotifApproveWoMail($dataApproveWO, $woID, $ptaNumber));

            return Redirect::to("/logistic/wo")->withSuccess('WO Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/logistic/wo/create/".$req['ckID'])->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function saveWoProcess(Request $req)
    {
        DB::beginTransaction();
        try{
            DB::table('t_wo01')->where('wonum', $req['wonum'])->update([
                'wo_process' => $req['wo_process']
            ]);

            if($req['wo_process'] === "Closed"){
                $woitems = DB::table('v_wo_pbj_kendaraan')
                ->where('wonum', $req['wonum'])->get();

                DB::table('t_kendaraan')->whereIn('no_kendaraan', $woitems->pluck('unit_desc'))->update([
                    'layak_tidak' => 'Layak'
                ]);
            }
            DB::commit();
            return Redirect::to("/logistic/wo/process")->withSuccess('Status WO : '. $req['wonum'] . ' berhasil di update');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/logistic/wo/process")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function update(Request $req, $woid){
        DB::beginTransaction();
        try{

            
            $wodata = DB::table('t_wo01')->where('id', $woid)->first();
            $ptaNumber = $wodata->wonum;
            $checkApproval = DB::table('v_wo_approval')
                ->where('wonum', $wodata->wonum)->where('approval_status', 'A')->first();
            
            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'WO : '. $ptaNumber . ' sudah di approve, data tidak bisa diupdate'
                );
                return $result;
            }

            

            // return $ptaNumber;

            // $amount = $req['nominal'];
            // $amount = str_replace(',','',$amount);
            DB::table('t_wo01')->where('id', $woid)->update([
                // 'wonum'             => $ptaNumber,
                'wodate'            => $req['servicedate'],
                'description'       => $req['descr'],
                // 'mekanik'           => $req['mekanik'],
                'whscode'           => $req['whscode'],
                'license_number'    => $req['licenseNumber'],
                // 'last_odo_meter'    => $req['lastOdoMeter'],
                'schedule_type'     => $req['schedule'],
                'issued'            => $req['issued'],
            ]);

            $parts    = $req['parts'];
            $partdsc  = $req['partdesc'];
            $quantity = $req['quantity'];
            $uom      = $req['uoms'];
            $pbjnum   = $req['pbjnum'];
            // $pbjitm   = $req['pbjitm'];
            $woitem   = $req['woitem'];

            $insertData = array();
            $woItems    = array();
            $count = 0;            

            for($i = 0; $i < sizeof($parts); $i++){
                $qty    = $quantity[$i];
                $qty    = str_replace(',','',$qty);

                $latestStock = DB::table('v_inv_summary_stock')
                               ->where('material', $parts[$i])
                               ->where('whsid',  $req['whscode'])->first();
                if($latestStock){
                    if($latestStock->quantity < $qty){
                        DB::rollBack();
                        // return Redirect::to("/logistic/wo")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                    }else{
                        // DB::table('t_inv_stock')
                        // ->where('material', $parts[$i])
                        // ->where('whscode',  $req['whscode'])
                        // ->update([
                        //     'quantity'     => $latestStock->quantity - $qty
                        // ]);
                    }
                }else{
                    DB::rollBack();
                    // return Redirect::to("/logistic/wo")->withError('Stock Tidak Mencukupi untuk part : '. $parts[$i]);
                }

                if($woitem[$i]){
                    $count = $woitem[$i];
                }else{
                    $count += 1;
                }
                $data = array(
                    'wonum'        => $ptaNumber,
                    'woitem'       => $count,
                    'material'     => $parts[$i],
                    'matdesc'      => $partdsc[$i],
                    'quantity'     => $qty,
                    'unit'         => $uom[$i],
                    'refdoc'       => $pbjnum[$i] ?? null,
                    // 'refdocitem'   => $pbjitm[$i] ?? null,
                    'createdon'    => date('Y-m-d H:m:s'),
                    'createdby'    => Auth::user()->email ?? Auth::user()->username
                );
                array_push($insertData, $data);
                array_push($woItems, $data);

                // DB::table('t_pbj02')
                //     ->where('pbjnumber', $pbjnum[$i])
                //     ->where('pbjitem', $pbjitm[$i])->update([
                //         'wocreated' => 'Y'
                //     ]);
            }
            insertOrUpdate($insertData,'t_wo02');

            //Insert Attachments | t_attachments
            if(isset($req['efile'])){
                $files = $req['efile'];
                $insertFiles = array();
    
                foreach ($files as $efile) {
                    $filename = $efile->getClientOriginalName();
                    $upfiles = array(
                        'doc_object' => 'SPK',
                        'doc_number' => $ptaNumber,
                        'efile'      => $filename,
                        'pathfile'   => '/files/SPK/'. $filename,
                        'createdon'  => getLocalDatabaseDateTime(),
                        'createdby'  => Auth::user()->username ?? Auth::user()->email
                    );
                    array_push($insertFiles, $upfiles);
    
                    $efile->move('files/SPK/', $filename);  
                    // $efile->move(public_path().'/files/SPK/', $filename);  
                }
    
                if(sizeof($insertFiles) > 0){
                    insertOrUpdate($insertFiles,'t_attachments');
                }
            }

            $approval = DB::table('v_workflow_budget')->where('object', 'SPK')->where('requester', Auth::user()->id)->get();
            if(sizeof($approval) > 0){
                
                for($a = 0; $a < sizeof($woItems); $a++){
                    $checkData = DB::table('t_wo_approval')
                        ->where('wonum',$ptaNumber)->where('woitem', $woItems[$a]['woitem'])->first();
                    if(!$checkData){
                        $insertApproval = array();
                        foreach($approval as $row){
                            $is_active = 'N';
                            if($row->approver_level == 1){
                                $is_active = 'Y';
                            }
                            $approvals = array(
                                'wonum'             => $ptaNumber,
                                'woitem'            => $woItems[$a]['woitem'],
                                'approver_level'    => $row->approver_level,
                                'approver'          => $row->approver,
                                'creator'           => Auth::user()->id,
                                'is_active'         => $is_active,
                                'createdon'         => getLocalDatabaseDateTime()
                            );
                            array_push($insertApproval, $approvals);
                        }
                        insertOrUpdate($insertApproval,'t_wo_approval');
                    }
                }
            }else{
                // DB::rollBack();
                // return Redirect::to("/logistic/wo")->withError('Approval belum di tambahkan untuk user '. Auth::user()->name);
            }

            DB::commit();
            $result = array(
                'msgtype' => '200',
                'message' => 'WO : '. $ptaNumber . ' berhasil diupdate'
            );
            return $result;
            // return Redirect::to("/logistic/wo")->withSuccess('WO Berhasil dibuat dengan Nomor : '. $ptaNumber);
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/logistic/wo")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function deleteWO($id){
        DB::beginTransaction();
        try{
            $prhdr = DB::table('t_wo01')->where('id', $id)->first();
            $pbjdoc = DB::table('t_wo02')
                        ->where('wonum', $prhdr->wonum)->get();

            $checkApproval = DB::table('v_wo_approval')
                        ->where('wonum', $prhdr->wonum)->where('approval_status', 'A')->first();
                    
            if($checkApproval){
                return Redirect::to("/logistic/wo/listwo")->withError('WO : '. $prhdr->wonum . ' sudah di approve, data tidak bisa dihapus');   
            }                        

            DB::table('t_wo01')->where('id', $id)->delete();
            DB::table('t_attachments')->where('doc_object', 'SPK')->where('doc_number',$prhdr->wonum)->delete();
            DB::table('t_wo_approval')->where('wonum', $prhdr->wonum)->delete();
            
            DB::table('t_checklist_kendaraan')->where('no_checklist', $prhdr->cheklistnumber)->update([
                'wocreated' => 'N'
            ]);
            
            // return $pbjdoc;
            // foreach($pbjdoc as $row){
            //     DB::table('t_pbj02')
            //         ->where('pbjnumber', $row->refdoc)
            //         ->where('pbjitem', $row->refdocitem)->update([
            //             'wocreated' => 'N'
            //     ]);

            //     // DB::commit();
            // }

            DB::commit();
            return Redirect::to("/logistic/wo/listwo")->withSuccess('WO '. $prhdr->wonum .' Berhasil dihapus');
        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::to("/logistic/wo/listwo")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function deleteWOItem(Request $req){
        DB::beginTransaction();
        try{
            $checkApproval = DB::table('v_wo_approval')
                ->where('wonum', $req['wonum'])->where('approval_status', 'A')->first();
            
            if($checkApproval){
                $result = array(
                    'msgtype' => '500',
                    'message' => 'WO : '. $req['wonum'] . ' sudah di approve, data tidak bisa dihapus'
                );
                return $result;
            }

            $pbjdoc = DB::table('t_wo02')
                        ->where('wonum', $req['wonum'])
                        ->where('woitem', $req['woitem'])->get();

            // $prhdr = DB::table('t_pr01')->where('prnum', $req['prnum'])->first();
            // DB::table('t_pr02')->where('prnum', $req['prnum'])->where('pritem', $req['pritem'])->update([
            //     'isdeleted' => 'Y'
            // ]);
            DB::table('t_wo02')->where('wonum', $req['wonum'])->where('woitem', $req['woitem'])->delete();
            // DB::table('t_pr_approval')->where('prnum', $prhdr->prnum)->delete();

            foreach($pbjdoc as $row){
                DB::table('t_pbj02')
                    ->where('pbjnumber', $row->refdoc)
                    ->where('pbjitem', $row->refdocitem)->update([
                        'wocreated' => 'N'
                ]);

                // DB::commit();
            }

            DB::commit();

            $result = array(
                'msgtype' => '200',
                'message' => 'Item WO : '. $req['wonum'] . ' - ' . $req['woitem'] . ' berhasil dihapus'
            );
            // return Redirect::to("/approve/pr")->withSuccess('PR dengan Nomor : '. $ptaNumber . ' berhasil di approve');
            return $result;
        } catch(\Exception $e){
            DB::rollBack();
            $result = array(
                'msgtype' => '500',
                'message' => $e->getMessage()
            );
            return $result;
            // return Redirect::to("/proc/pr")->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }
}
