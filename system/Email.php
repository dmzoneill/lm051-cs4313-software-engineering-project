<?php

class Email
{
    private $to = null;
    private $from = null;
    private $replyto = null;
    private $headers = null;
    private $subject = null;
    private $message = null;
    private $cc = null;
    private $bcc = null;
    

    public function Email($to, $from, $subject, $message, $cc = null, $bcc = null, $replyto = null) 
    {
        $this->to = $to;
        $this->from = $from;
        $this->replyto = $replyto;
        $this->subject = $subject;
        $this->message = $message;
        $this->cc = $cc;
        $this->bcc = $bcc;       

        $this->defaultHeaders();
    }


    public function defaultHeaders()
    {
        $this->headers  = 'From: ' . $this->from . '\r\n';
        $this->headers .= 'To: ' . $this->to . '\r\n';

        if($this->replyto != null)
        {
            $this->headers .= 'Reply-To: ' . $this->replyto . '\r\n';
        }        
        if($this->cc != null)
        {
            $this->headers .= 'Cc: ' . $this->cc . '\r\n';
        }
        if($this->bcc != null)
        {
            $this->headers .= 'Bcc: ' . $this->bcc . '\r\n';
        }
    }


    public function htmlEmail()
    {
        $this->headers .= 'MIME-Version: 1.0' . '\r\n';
        $this->headers .= 'Content-type: text/html; charset=iso-8859-1' . '\r\n';
    }


    public function attachFiles()
    {

    }


    public function send()
    {
        //mail($this->to, $this->from, $this->message, $this->headers);
		
		$to      = 'dave@feeditout.com';
		$subject = 'the subject';
		$message = 'hello';
		$headers = 'From: webmaster@example.com';

		mail($to, $subject, $message, $headers);
		print "<h1>SENT</h1>";
    }
}

