<?php

/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 2016-04-13
 * Time: 20:02
 */
class MailHog_Mail {
    /**
     * @var string
     */
    public $content_type;
    /**
     * @var string
     */
    public $date;
    /**
     * @var string
     */
    public $subject;
    /**
     * @var string
     */
    public $from;

    /**
     * @var array<string>
     */
    public $to;
    /**
     * @var string
     */
    public $body;

    /**
     * @var array
     */
    public $raw_data;

    /**
     * MailHog_Mail constructor.
     * @param string $subject
     * @param string $from
     * @param array<string> $to
     * @param string $body
     */
    public function __construct($subject, $from, $to, $body) {
        $this->subject = $subject;
        $this->from = $from;
        $this->to = $to;
        $this->body = $body;
    }
    /**
     * @param array $mailhog_item MailHog mail item array
     * @return MailHog_Mail
     */
    public static function make($mailhog_item) {
        $from = $mailhog_item['Content']['Headers']['From'][0];
        $to = $mailhog_item['Content']['Headers']['To'];
        $subject = $mailhog_item['Content']['Headers']['Subject'][0];
        $mail = new MailHog_Mail( $subject, $from, $to, $mailhog_item['Content']['Body'][0] );
        $mail->date = $mailhog_item['Content']['Headers']['Date'][0];
        $mail->content_type = $mailhog_item['Content']['Headers']['Content-Type'][0];
        $mail->raw_data = $mailhog_item;
        return $mail;
    }
}