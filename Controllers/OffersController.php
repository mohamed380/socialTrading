<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Project_offer;
use App\Membership_offer;
use App\Project;
class OffersController extends Controller
{
    public function offers()
    {
        return view('admin.offers.offersView')->with('offers',Membership_offer::all());
    }
    public function projectsOffers()
    {
        $projectOffers  = Project_offer::all();
         return view('admin.offers.projectsOffers')->with('projectOffers',$projectOffers);
    }
    public function addProjectOffer(Request $r)
    {
        $response['state'] ='false';
        $response['find'] ='false';
        $projectOffer = new Project_offer();
        if ($r->id){
            $projectOffer = Project_offer::find($r->id);
            $response['find'] = "true";
        }
        
        $projectOffer->discount = $r->discount;
        $projectOffer->title = $r->title;
        $projectOffer->body = $r->body;
        $projectOffer->enddate = $r->enddate;
        if($projectOffer->save()){
            $response['state'] ="true";
            $response['id'] = $projectOffer->id;
            $response['title'] = $r->title;
            $response['discount'] = $r->discount;
        }
        echo json_encode($response);
        exit;
    }
    public function projectOfferInfo($id)
    {
        $offer = Project_offer::find($id);
        return view('admin.offers.projectsOffersInfo')->with('offer',$offer);
    }
    public function membershipOfferInfo($id)
    {
        $offer = Membership_offer::find($id);
        return view('admin.offers.MembershipOffersInfo')->with('offer',$offer);
    }
    

    public function deleteProjectOffer($id)
    {
        $response = "false";
        $offer = Project_offer::find($id);
        $projects = $offer->Project;
        
        if($offer->delete()){
            foreach($projects as $project){
                $project->project_offer_id = null;
                $project->save();
            }
            $response = "true";
        }
        echo json_encode($response);
        exit;
    }
    public function deleteMembershipOffer($id)
    {
        $response = "false";
        $offer = Membership_offer::find($id);
        $memberships = $offer->Membership;
        
        if($offer->delete()){
            foreach($memberships as $membership){
                $membership->membership_offer_id = null;
                $membership->save();
            }
            $response = "true";
        }
        echo json_encode($response);
        exit;
    }
    
    public function membershipOffers()
    {
        $offers = Membership_offer::all();
        return view('admin.offers.membershipOffers')->with('offers',$offers);
    }
    public function addMembershipOffer(Request $r)
    {
        $response['state'] ='false';
        $response['find'] ='false';
        $membershipOffer = new Membership_offer();
        if ($r->id){
            $membershipOffer = Membership_offer::find($r->id);
            $response['find'] = "true";
        }
        $membershipOffer->discount = $r->discount;
        $membershipOffer->title = $r->title;
        $membershipOffer->body = $r->body;
        $membershipOffer->enddate = $r->enddate;
        $membershipOffer->storage = $r->storage;
        $membershipOffer->duration = $r->duration;
        $membershipOffer->admin_id = Auth::user()->id;
        if($membershipOffer->save()){
            $response['state'] ="true";
            $response['id'] = $membershipOffer->id;
            $response['title'] = $r->title;
            $response['discount'] = $r->discount;
        }
        echo json_encode($response);
        exit;
    }
   
   
}
