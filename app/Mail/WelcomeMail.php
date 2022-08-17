<?php


namespace App\Mail;

use App\Domain\Hiring\Models\EmailTemplate;
use Spatie\MailTemplates\TemplateMailable;

class WelcomeMail extends TemplateMailable
{
    protected static $templateModelClass = EmailTemplate::class;
    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public $template;

    /**
     * WelcomeMail constructor.
     * @param $emailData
     */
    public function __construct($emailData)
    {
        $this->name = $emailData['candidateName'];
        if ($emailData['template_id']) {
            $this->template=$emailData['template_id'];
        }
        //  $this->email = $emailData->email;
    }
    /**
     * @return int
     */
    public function getTamplateID(): int
    {
        if (!empty($this->template)) {
            return $this->template;
        } else {
            return 1;
        }
    }
}
