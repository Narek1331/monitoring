<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InfoBox extends Component
{
    public $label;
    public $placeholder;

    public function __construct($label = '', $placeholder = '')
    {
        $this->label = $label;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.info-box');
    }
}
