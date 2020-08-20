<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Project;
use App\User;
class projectLiked extends Notification implements ShouldQueue
{
    use Queueable;
    public $project;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'project' => $this->project
    //     ];
    // }

    // public function toBroadcast($notifiable)
    // {
    //     return [
    //         'project' => $this->project
    //     ];
    // }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->id,
            'read_at' => null,
            'project' => ['title'=>$this->project->title,'id'=>$this->project->id],
            'user' => ['username'=>$this->user->name, 'userImg' => $this->user->img],
        ];
    }
    
}
