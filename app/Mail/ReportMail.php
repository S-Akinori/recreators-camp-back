<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $materialName;
    public $commentContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->report = $report;
        $this->materialName = $report->material ? $report->material->name : null;
        $this->commentContent = $report->comment ? $report->comment->content : null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('新しい通報が届きました')
                    ->view('emails.report')
                    ->with([
                        'reporterName' => $this->report->user->name,
                        'reporterId' => $this->report->user->id,
                        'description' => $this->report->description,
                        'reportType' => $this->report->material_id ? '素材' : 'コメント',
                        'materialId' => $this->report->material_id,
                        'materialName' => $this->materialName,
                        'commentId' => $this->report->comment_id,
                        'commentContent' => $this->commentContent,
                    ]);
    }
}

