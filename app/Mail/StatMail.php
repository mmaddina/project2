<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class StatMail extends Mailable
{
    use Queueable, SerializesModels;

    /*
     * Create a new message instance.
     */
    public function __construct(public int $commentCount, public int $articleCount)
    {
        //
    }

    /*
     * Get the message envelope.
     */
    public function envelope(): Envelope

    {
        return new Envelope(
            from: new Address('mamedovamadinaa8@gmail.com'),
            subject: 'Order Shipped',
        );
    }

    /*
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.stat',
            with:['commentcount'=>$this->commentCount, 'articleCount'=>$this->articleCount]
        );
    }

    /*
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}