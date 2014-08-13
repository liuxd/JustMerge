<?php
namespace core;

interface IController
{
    public function before();
    public function handle();
    public function after();
}

# end of this file
