<?php

interface Observer
{
    public function update( Observable $subject , $status);
    public function announce();
}
