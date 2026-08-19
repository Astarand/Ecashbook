<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $customerName;
    public $invoiceNumber;
    protected $pdfPath;
	public $fromEmail;
	public $fromName;

    public function __construct($customerName,$invoiceNumber,$pdfPath,$subjectText = null,$fromEmail,$fromName) {
        $this->customerName = $customerName;
        $this->invoiceNumber = $invoiceNumber;
        $this->pdfPath = $pdfPath;
        $this->subjectText = $subjectText ?? 'Invoice - ' . $invoiceNumber;
		$this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    public function build()
    {
        return $this
			//->from($this->fromEmail, $this->fromName)
			->subject($this->subjectText)
            ->view('emails.invoice-email')
            ->attach($this->pdfPath, [
                'as' => $this->invoiceNumber . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}