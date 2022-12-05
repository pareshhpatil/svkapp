<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MenuButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $menu_list =array();

    public function __construct($menuList)
    {
      // dd($menuList);
        $this->menu_list = $menuList;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu-button');
        //return view('components.menu-button')->with('title',$menu_list);
    }
}
