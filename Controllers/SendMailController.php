<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Mail\SendMail;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Project;
use App\User;
use App\Message;


class SendMailController extends Controller
{
    //
    public function emailMessages()
    {
        return view('emails.emailMessages')->with('emails',Message::all());
    }

    public function mailsend($project,$for)
    {
        try{
        $message = Message::where('for',$for)->first();
        $userid = $project->admin_id;
        $user   = User::find($userid);
        $adaptedMessage =str_replace('username',$user->name,$message->body);
        $adaptedMessage = str_replace('projectname',$project->title,$adaptedMessage);
        $details = [
            'title' => $message->title,
            'body'  => $adaptedMessage,
            'subject' => $message->subject,
            'color'   =>$message->color
        ];
        
            \Mail::to($user->email)->send(new SendMail($details,$project,$user));
            return true;
        }catch(\Exception $exception){
            return false;
        }
    }
    public function messageInfo($id)
    {
        $message = Message::find($id);
        if($message){
           return view('emails.emailMessageInfo')->with('message',$message); 
        }
        return false;
    }
    public function addMessage(Request $request)
    {
        $x = $request->validate([
            'title' => 'required',
            'for' => 'required',
            'body' => 'required',
            'subject' => 'required',
        ]);
        $response['state'] = 'false';
        $response['find'] = 'false';
        $message = new Message();
        if($request->id){
            $message = Message::find($request->id);
            $response['find'] = 'true';
        }
        
        $message->for = $request->for;
        $message->title = $request->title;
        $message->subject = $request->subject;
        $message->body = $request->body;
        if($message->save()){
            $response['state'] = 'true';
            $response['id'] =$message->id;
            $response['for'] = $message->for;
            $response['title'] = $message->title;

        }
        echo json_encode($response);
        exit;
    }
}
