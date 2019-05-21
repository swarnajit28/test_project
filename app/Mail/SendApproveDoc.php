<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use helper;

class SendApproveDoc extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
        //helper::pre($this->content,1);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->content['subject'])
              ->attach($this->content['attach'], array('as' =>$this->content['file_name'], 'mime' => 'application/pdf'))
              ->markdown('emails.send_approve_doc_client')
              ->with('content',$this->content);
    }
}
