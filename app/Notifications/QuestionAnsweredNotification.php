<?php

namespace App\Notifications;

use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuestionAnsweredNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Question $question,
        public QuestionAnswer $answer
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Jawaban atas Pertanyaan Anda: '.$this->question->subject)
            ->greeting('Halo, '.$this->question->name.'!')
            ->line('Pertanyaan yang Anda ajukan ke portal kami telah dijawab oleh petugas resmi:')
            ->line('Pertanyaan: "'.$this->question->subject.'"')
            ->line('Jawaban: '.strip_tags($this->answer->body))
            ->line('Terima kasih telah menghubungi layanan informasi masyarakat kami.');
    }
}
