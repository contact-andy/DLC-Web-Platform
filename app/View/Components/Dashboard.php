<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\User;
use App\Models\Employee;
use App\Models\ContentCategory;
use App\Models\DigitalContent;
use App\Models\Customer;


use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $employee = Employee::count();
        view()->share('employee',$employee);
        
        $category = ContentCategory::count();
        view()->share('category',$category);
        
        $digitalContent = DigitalContent::count();
        view()->share('digitalContent',$digitalContent);
        
        $customer = Customer::count();
        view()->share('customer',$customer);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard');
    }
}
