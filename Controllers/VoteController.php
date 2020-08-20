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
use App\Vote;
class VoteController extends Controller
{
    public function votes()
    {
        return view('admin.votes.votesHome');
    }
    
    public function votesStati()
    {
        return view('admin.votes.votesStatistics');
    }

    public function votesManage()
    {
        return view('admin.votes.votesManage')->with('voting',$this->getVoting());
    }

    public function listUserVotes()
    {
        return view('admin.votes.listVotes')->with('votes',Vote::get()->groupBy('answer_id'))
                                            ->with('answers',$this->getVoting());
    }
    
    public function UpdateVoting($voting)
    {
        $voting = json_decode($voting);
        if(Auth::guard('admin')->check()){
             Storage::disk('local')->put('vote.txt',json_encode($voting));
             return json_encode(true);
        }
        return json_encode(false);
    }

    public function getVoting()
    {
        try{
            $data = json_decode(Storage::disk('local')->get('vote.txt'),true);
            return $data;
        }catch (\Exception $e){
             Storage::disk('local')->put('vote.txt',json_encode([]));
            return [];
        }
    }

    public function submitVote(Request $request)
    {
        $vote = new Vote();
        $vote->answer_id = $request->answer_id;
        $vote->user_ip = request()->ip();
        if($vote->save()){
            return json_encode(true);
        }
        return json_encode(false);
    }

    public function deleteAnswer($id)
    {
        if($id){
            Vote::where('answer_id',$id)->delete();
            return json_encode(true);
        }
        return json_encode(false);
    }
}
