<?php 
/* 
 * 
 * No Proxy Authentification Implemented; PHP 5 
 * 
 */ 

class RemoteFopenViaProxy { 

    private $result; 
    private $proxy_name; 
    private $proxy_port; 
    private $request_url; 

    public function get_proxy_name() { 
        return $this->proxy_name; 
    } 

    public function set_proxy_name($n) { 
        $this->proxy_name = $n; 
    } 

    public function get_proxy_port() { 
        return $this->proxy_port; 
    } 

    public function set_proxy_port($p) { 
        $this->proxy_port = $p; 
    } 

    public function get_request_url() { 
        return $this->request_url; 
    } 

    public function set_request_url($u) { 
        $this->request_url = $u; 
    } 

    public function get_result() { 
        return $this->result; 
    } 

    public function set_result($r) { 
        $this->result = $r; 
    } 

    private function get_url_via_proxy() { 

        $proxy_fp = fsockopen($this->get_proxy_name(), $this->get_proxy_port()); 

        if (!$proxy_fp) { 
            return false; 
        } 
        fputs($proxy_fp, "GET " . $this->get_request_url() . " HTTP/1.0\r\nHost: " . $this->get_proxy_name() . "\r\n\r\n"); 
        while (!feof($proxy_fp)) { 
            $proxy_cont .= fread($proxy_fp, 4096); 
        } 
        fclose($proxy_fp); 
        $proxy_cont = substr($proxy_cont, strpos($proxy_cont, "\r\n\r\n") + 4); 
        return $proxy_cont; 

    } 

    private function get_url($url) { 
        $fd = @ file($url); 
        if ($fd) { 
            return $fd; 
        } else { 
            return false; 
        } 
    } 

    private function logger($line, $file) { 
        $fd = fopen($file . ".log", "a+"); 
        fwrite($fd, date("Ymd G:i:s") . " - " . $file . " - " . $line . "\n"); 
        fclose($fd); 
    } 

    function __construct($url, $proxy_name = "", $proxy_port = "") { 

        $this->set_request_url($url); 
        $this->set_proxy_name($proxy_name); 
        $this->set_proxy_port($proxy_port); 

    } 

    public function request_via_proxy() { 

        $this->set_result($this->get_url_via_proxy()); 
        if (!$this->get_result()) { 
            $this->logger("FAILED: get_url_via_proxy(" . $this->get_proxy_name() . "," . $this->get_proxy_port() . "," . $this->get_request_url() . ")", "RemoteFopenViaProxyClass.log"); 
        } 
    } 

    public function request_without_proxy() { 

        $this->set_result($this->get_url($this->get_request_url())); 
        if (!$this->get_result()) { 
            $this->logger("FAILED: get_url(" . $url . ")", "RemoteFopenViaProxyClass.log"); 
        } 
    } 
} 


$obj = new RemoteFopenViaProxy($insert_request_url, $insert_proxy_name, $insert_proxy_port); 
// change settings after object generation 
$obj->set_proxy_name("student-proxy.ul.ie"); 
$obj->set_proxy_port("8080"); 
$obj->set_request_url("http://www.feeditout.com/mail"); 
$obj->request_via_proxy(); 
echo $obj->get_result(); 


?> 
