<?php

namespace App\Http\Controllers;
use App\Message;
use App\Chat;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\chatMessage as chatMessageNotification;
use App\Notifications\typingState;
use App\Notifications\seenState;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function sendChatMessage(Request $request)
    {
        $currentUser = Auth::guard('user')->user();
        if($currentUser->id != $request->destinationID){
                $message = new Message();
                $message->message = $request->message;
                $message->sourceID = $currentUser->id;
                $message->destinationID = $request->destinationID;
                #check if it that chat existes between source and destination
                if($request->chatID){
                    $message->chat_id = $request->chatID;
                }else{
                    $newChat = new Chat();
                    $newChat->save();
                    $newChat->User()->attach([$currentUser->id,$request->destinationID]);
                    $message->chat_id = $newChat->id;
                }
                if($message->save()){
                   User::find($request->destinationID)->notify(new chatMessageNotification($currentUser,$message));
                   return $message;
                }
        }
        return json_encode(false);
    }

    public function getChatMessages($chat_id = null,$user_id = null) 
    # user_id used if it is first time to chat with that user 
    {
        $currentUser = Auth::guard('user')->user();
        if((int)$user_id && $currentUser){
            $chat_id = Message::where([['sourceID',$currentUser->id],['destinationID',$user_id]])
                                                 ->orWhere([['destinationID',$currentUser->id],['sourceID',$user_id]])
                                                 ->pluck('chat_id')->first();
            if(!$chat_id){
                return json_encode(['chat'=>['id'=>$user_id,'user'=>[User::select('id','name','img')->find($user_id)]],
                                    'messages'=>[]]);
            }
        }
        if($currentUser && $chat_id){
            $authorized = User::find(Auth::id())->chat->pluck('id')->toArray();
            if(in_array((int)$chat_id,$authorized)){
                $chat = Chat::where('id',(int)$chat_id)->with("Message","User")->first();
                $chat->message = $chat->message()->latest()->first();
                if($chat->message->destinationState == 0 && $chat->message->destinationID == $currentUser->id){
                    $this->updateSeenState(0,$chat);
                }
                $messages = $this->chatMessages($chat_id,0);
                return json_encode(['chat'=>$chat->only('id','user'),'messages'=>$messages]);
            }
        }
        return json_encode(false);
    }
    public function chatMessages($chat_id,$skip)
    {
        try{
            return array_reverse(Chat::find($chat_id)->message()->latest()->skip($skip)->take(10)->get()->toArray());
        }catch(\Exception $e){
            return json_encode(false);
            exit;
       }
        
    }

    public function updateTypingState($chat_id)
    {
        try{
            (int)$chat_id;
            $chat = Chat::find($chat_id)->with('User')->first();
            if($user = $chat->user[0]){
                    $user->notify(new typingState($chat_id));
                    return json_encode(true);
            }
        }
        catch(\Exception $e){
            return json_encode(false);
            exit;
       }
    }

    public function updateSeenState($chat_id,$chat = null)
    {
        try{
            $chat = $chat;
            if($chat_id){
                $chat = Chat::find($chat_id)->with('Message','User')->first();
            }
           $message =  $chat->Message()->where([['destinationID',Auth::id()],['destinationState',0]])->latest()->get()->last();
           $chat->Message()->where([['destinationID',Auth::id()],['destinationState',0]])
            ->update(['destinationState' => 1]);
            User::find($chat->user[0]->id)->notify(new seenState($chat->id,$message->id));
        }
        catch(\Exception $e){
            return json_encode(false);
            exit;
       }
    }

}
