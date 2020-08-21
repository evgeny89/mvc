<?php

namespace Classes\Interfaces;

interface View
{
    public function render($template, array $param);
}