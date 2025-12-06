<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class StudentLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // Ini memberi tahu Laravel:
        // "Saat <x-student-layout> dipanggil, gunakan file 'resources/views/layouts/student.blade.php'"
        return view('layouts.student');
    }
}