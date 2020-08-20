<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Project;
use App\User;
use App\Report;
class ReportsController extends Controller
{
    public function reports()
    {
        return view('admin.reports.reportsHome');
    }
    
    public function reportsStati()
    {
        return view('admin.reports.reportsStatistics');
    }

    public function reportsManage()
    {
        return view('admin.reports.reportsManage')->with('reports',$this->getReportsOptions());
    }

    public function listUserReports()
    {
        return view('admin.reports.listReport')->with('reports',Report::latest()->with('User')->with('Project')->get());
    }
    
    public function UpdateReportsOptions($options)
    {
        $options = json_decode($options);
        if(Auth::guard('admin')->check()){
             Storage::disk('local')->put('test.txt',json_encode($options));
             return json_encode(true);
        }
        return json_encode(false);
    }

    public function getReportsOptions()
    {
        try{
            $data = json_decode(Storage::disk('local')->get('test.txt'),true);
            return $data;
        }catch (\Exception $e){
             Storage::disk('local')->put('test.txt',json_encode([]));
            return [];
        }
    }

    public function submitReport(Request $request)
    {
      
        $report = new Report();
        $report->complain = $request->complain;
        $report->user_id = $request->userid;
        $report->project_id = $request->projectid;

        if($report->save()){
            return json_encode(true);
        }
        return json_encode(false);
    }

}
