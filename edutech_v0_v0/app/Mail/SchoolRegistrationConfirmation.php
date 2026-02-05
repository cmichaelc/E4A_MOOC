<?php

namespace App\Mail;

use App\Models\User;
use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $manager;
    public $school;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(User $manager, School $school, string $password)
    {
        $this->manager = $manager;
        $this->school = $school;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'School Registration Confirmation - EduTech Benin',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.school-registration-confirmation',
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
