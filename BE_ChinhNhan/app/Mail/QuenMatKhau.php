<?php

namespace App\Mail;

use App\Models\Member;
use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuenMatKhau extends Mailable
{
    use Queueable, SerializesModels;
    public $member;
    public $resetLink;
    /**
     * Create a new message instance.
     */
    public function __construct(Member $member,$resetLink)
    {
        $this->member = $member;
       $this->resetLink = $resetLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yêu cầu đổi mật khẩu',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.mailQuenMatKhau',
            with: [
                'member' => $this->member,
                'resetLink' => $this->resetLink,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
