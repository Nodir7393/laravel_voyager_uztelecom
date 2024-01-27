<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <a href="#" class="brand-link">
            <img src="{{asset('images/imagen-my-uztelecom-0big.png')}}" style="margin-left: 1px" alt="Uztelecom Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Uztelecom</span>
        </a>
        <!-- Sidebar Menu -->
        <nav class="mt-4 shadow-xl fixed bottom-0 relative h-screen z-10 w-full content-center">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                {{menu('site', 'site.dashboard.menu')}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

