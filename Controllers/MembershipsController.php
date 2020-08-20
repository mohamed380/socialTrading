<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membership;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembershipsController extends Controller
{
    //
    public function membership()
    {
        return view('admin.membership.adminMembership');
    }

    public function membershipStati()
    {
        return view('admin.adminStatistics');
    }
    public function manageMembership()
    {
        $memberships = Membership::all();
        return view('admin.membership.manageMembership')->with("memberships",$memberships);
    }
    public function membershipInfo($id)
    {
        $membership = Membership::find($id);
        if($membership){
            return view('admin.membership.membershipInfo')->with('membership',$membership);
        }
        return false;
    }
    public function addMembership(Request $request)
    {
        $response['state'] ='false';
        $response['find'] ='false';
        $x = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'duration' => 'required',
            'cost' => 'required',
            'storage' => 'required',
        ]);
        $membership = new Membership();
        if($request->id){
            $membership = Membership::find($request->id);
            $response['find'] = 'true';
        }
        $membership->title = $request->title;
        $membership->body = $request->body;
        $membership->duration = $request->duration;
        $membership->cost = $request->cost;
        $membership->storage = $request->storage;
        $membership->state = $request->state==null?0:1;
        $membership->admin_id = Auth::user()->id;
        if($membership->save()){
            $response['state'] = 'true';
        }
        echo json_encode($response);
        exit;
    }

    public function editMembershipState($id,$newVal)
    {
        $response = "false";
        $membership = Membership::find($id);
        $membership->state = $newVal ;
        if($membership->save()){
            $response = 'true';
        }
        echo json_encode($response);
        exit;
    }

    public function searchMembership($token)
    {
        $response['state'] = 'false';
        $memberships = Membership::where([['title','like', '%'. $token .'%']])->get();
        if($memberships->count()){
            $response['state'] = 'true';
            $response['memberships'] = $memberships;
        }
        echo json_encode($response);
        exit;
    }

    public function addMembershipToOffer($membershipId,$offerId)
    {
        $respose = 'false';
        $membership = Membership::find($membershipId);
        $membership->membership_offer_id = $offerId;
        if($membership->save()){
             $response = 'true';
        }
        echo json_encode($response);
             exit;
    }
    public function removeMembershipFromOffer($membershipId)
    {
        $respose = 'false';
        $membership = Membership::find($membershipId);
        $membership->membership_offer_id = null;
        if($membership->save()){
             $response = 'true';
        }
        echo json_encode($response);
             exit;
    }

    
}
