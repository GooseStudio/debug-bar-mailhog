<?php

/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 2016-04-13
 * Time: 20:02
 */
class MailHog_MailCollection implements Iterator {
    /**
     * @var array<MailHog_Mail>
     */
    private $items;
    private $position = 0;
    /**
     * @var
     */
    public $total;
    /**
     * @var
     */
    public $count;
    /**
     * @var int
     */
    public $start;

    /**
     * MailHog_MailCollection constructor.
     * @param $total
     * @param $count
     * @param int $start
     */
    public function __construct($total, $count, $start = 0){
        $this->position = 0;
        $this->total = $total;
        $this->count = $count;
        $this->start = $start;
    }
    /**
     * @param array $data mailog data array
     * @return MailHog_MailCollection
     */
    public static function make($data) {
        $collection =  new MailHog_MailCollection($data['total'], $data['count'], $data['start']);
        require_once 'class-mailhog-mail.php';
        foreach ($data['items'] as $mailhog_item )
            $collection->addMail( MailHog_Mail::make( $mailhog_item ) );
        return $collection;
    }

    /**
     * @param MailHog_Mail $mailhog_mail
     */
    public function addMail($mailhog_mail) {
        $this->items[] = $mailhog_mail;
    }

    function rewind() {
        $this->position = 0;
    }

    /**
     * @return MailHog_Mail
     */
    function current() {
        return $this->items[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->items[$this->position]);
    }

}