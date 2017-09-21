<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationDeployed extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * The application deployed
     */
    public $application;

    /**
     * The deployment status
     */
    public $status;

    /**
     * The server output
     */
    public $result;

    /**
     * The committer
     */
    public $committer;

    /**
     * The deployed branch
     */
    public $branch;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($application, $status, $result, $committer, $branch)
    {
        $this->application = $application;
        $this->status = $status;
        $this->result = $result;
        $this->committer = $committer;
        $this->branch = $branch;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        //Email parameters
        $params = array('application' => $this->application,
                        'status' => $this->status,
                        'result' => $this->result,
                        'committer' => $this->committer,
                        'branch' => $this->branch
                        );

        if( $this->status ){
            return (new MailMessage)
                ->subject('Application Sucessfully Deployed')
                ->markdown('mail.application.deployed', $params );    
        }
        else{
            return (new MailMessage)
                ->error()
                ->subject('Error on Deployment')
                ->markdown('mail.application.deployed-error', $params );       
        }

        
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
