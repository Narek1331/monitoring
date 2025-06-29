<?php

namespace App\Livewire;

use Livewire\Component;

class MessageModal extends Component
{
    public bool $isOpen = false;

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.message-modal');
    }
}
