<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MultiFilter extends Component
{
    public string $title;
    public array $options;
    public array $selected;

    public function __construct(
        string $title,
        array $options = [],
        array $selected = [],
    ) {
        $this->title = $title;
        $this->options = $options;
        $this->selected = $selected;
    }
    public function removeFilter($property, $value)
    {
        $this->{$property} = array_values(
            array_diff($this->{$property}, [$value])
        );
    }

    public function render(): View|Closure|string
    {
        return view('components.multi-filter');
    }
}