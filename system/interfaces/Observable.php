<?php

interface Observable
{
    public function attach( Observer $observer );
    public function detatch( Observer $observer );
    public function notify();
    public function status();
}
