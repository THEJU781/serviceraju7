use Lavary\Menu\Menu;

@php
    $url = '';
    $MyNavBar = \Menu::make('MenuList', function ($menu) use($url){
        $menus = App\Models\Menus::orderBy('menu_order','asc')->get();
        foreach ($menus as $menulist){
            if($menulist->url == null && $menulist->parent_id == 0){
             if($menulist->permission == 'admin'){
                $menu->add('<span>'.__($menulist->title).'</span>', ['class' => ''])
                ->prepend($menulist->icon)
                ->nickname($menulist->nickname)
                ->data('role', 'admin')
                ->link->attr(["class" => ""])
                ->href('#'.$menulist->nickname);
             }
             elseif($menulist->permission == 'provider'){
                $menu->add('<span>'.__($menulist->title).'</span>', ['class' => ''])
                ->prepend($menulist->icon)
                ->nickname($menulist->nickname)
                ->data('role', 'provider')
                ->link->attr(["class" => ""])
                ->href('#'.$menulist->nickname);
             }
             else{
                $menu->add('<span>'.__($menulist->title).'</span>', ['class' => ''])
                ->prepend($menulist->icon)
                ->nickname($menulist->nickname)
                ->data('permission', $menulist->permission)
                ->link->attr(["class" => ""])
                ->href('#'.$menulist->nickname);
             }
            }
            elseif($menulist->parent_id == 0){
                if($menulist->permission == null){
                    $menu->add('<span>'.__($menulist->title).'</span>', ['route' => $menulist->url])
                    ->prepend($menulist->icon)
                    ->link->attr(['class' => '']);
                }
                else{
                    if($menulist->permission == 'admin'){
                    $menu->add('<span>'.__($menulist->title).'</span>', ['route' => $menulist->url])
                    ->prepend($menulist->icon)
                    ->data('role', 'admin')
                    ->link->attr(['class' => '']);
                    }
                    elseif($menulist->permission == 'provider'){
                    $menu->add('<span>'.__($menulist->title).'</span>', ['route' => $menulist->url])
                    ->prepend($menulist->icon)
                    ->data('role', 'provider')
                    ->link->attr(['class' => '']);
                    }
                    else{
                     $menu->add('<span>'.__($menulist->title).'</span>', ['route' => $menulist->url])
                    ->prepend($menulist->icon)
                    ->data('permission', $menulist->permission)
                    ->link->attr(['class' => '']);
                    }
                 }
            }
            foreach($menus as $menuchild){
               if($menuchild->parent_id == $menulist->id){
                $nickname = $menulist->nickname;
                if($menuchild->url_params == null){
                    if($menuchild->permission == 'admin'){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => $menuchild->url])
                    ->prepend($menuchild->icon)
                    ->data('role', 'admin')
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'provider'){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => $menuchild->url])
                    ->prepend($menuchild->icon)
                    ->data('role', 'provider')
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'admin,provider' && auth()->user()->user_type=="Admin" || $menuchild->permission == 'handyman,provider' && auth()->user()->user_type=="provider" ){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => $menuchild->url])
                    ->prepend($menuchild->icon)
                    ->data('role', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'admin,provider' && auth()->user()->user_type=="provider" || $menuchild->permission == 'handyman,provider' && auth()->user()->user_type=="handyman"){
                    $menu->add('<span>'.__($menuchild->title).'</span>', ['route' => $menuchild->url])
                    ->prepend($menuchild->icon)
                    ->data('role', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'handyman,provider' && auth()->user()->user_type=="admin"){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'd-none sidebar-layout' , 'route' => $menuchild->url])
                    ->prepend($menuchild->icon)
                    ->data('permission', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                    else{
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => $menuchild->url])
                    ->prepend($menuchild->icon)
                    ->data('permission', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                }
                elseif($menuchild->url_params == 'pending'){
                    if($menuchild->permission == 'admin'){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => [$menuchild->url,'pending']])
                    ->prepend($menuchild->icon)
                    ->data('role', 'admin')
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'provider'){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => [$menuchild->url,'pending']])
                    ->prepend($menuchild->icon)
                    ->data('role', 'provider')
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'admin,provider' && auth()->user()->user_type=="Admin" || $menuchild->permission == 'handyman,provider' && auth()->user()->user_type=="provider" ){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => [$menuchild->url,'pending']])
                    ->prepend($menuchild->icon)
                    ->data('role', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'admin,provider' && auth()->user()->user_type=="provider" || $menuchild->permission == 'handyman,provider' && auth()->user()->user_type=="handyman"){
                    $menu->add('<span>'.__($menuchild->title).'</span>', ['route' => [$menuchild->url,'pending']])
                    ->prepend($menuchild->icon)
                    ->data('role', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                    elseif($menuchild->permission == 'handyman,provider' && auth()->user()->user_type=="admin"){
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'd-none sidebar-layout' , 'route' => [$menuchild->url,'pending']])
                    ->prepend($menuchild->icon)
                    ->data('permission', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                    else{
                    $menu->$nickname->add('<span>'.__($menuchild->title).'</span>', [ 'class' => 'sidebar-layout' , 'route' => [$menuchild->url,'pending']])
                    ->prepend($menuchild->icon)
                    ->data('permission', $menuchild->permission)
                    ->link->attr(array('class' => ''));
                    }
                }
               
               } 
            }
        }
    })->filter(function ($item) {
    return checkMenuRoleAndPermission($item);
});

@endphp
<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-end justify-content-between">
        <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal light-logo site_logo_preview" alt="logo">
            <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal darkmode-logo site_logo_preview" alt="logo">
            <span class="white-space-no-wrap">{{ ucfirst(str_replace("_"," ",auth()->user()->user_type)) }}</span>
        </a>
        <div class="side-menu-bt-sidebar-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-light wrapper-menu" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>
    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="side-menu">
                @include(config('laravel-menu.views.bootstrap-items'), ['items' => $MyNavBar->roots()])
            </ul>
        </nav>
        <div class="pt-5 pb-5"></div>
    </div>
</div>