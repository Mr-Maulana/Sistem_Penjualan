<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helpers\RoleHelper;

class RoleCheck extends Component
{
    public bool $show = false;

    public function __construct(?array $roles = null, ?string $role = null)
    {
        if ($role) {
            $this->show = RoleHelper::hasRole($role);
        } elseif ($roles) {
            $this->show = RoleHelper::hasRole(...$roles);
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.role-check');
    }
}
