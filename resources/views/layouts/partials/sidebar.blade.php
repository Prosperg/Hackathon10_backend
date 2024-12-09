<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{route('home')}}" class="waves-effect">
                        <i class="bx bx-home-circle"></i><span class="badge rounded-pill bg-info float-end">04</span>
                        <span key="t-dashboards">Tableau de bord</span>
                    </a>
                </li>

                

                <li class="menu-title" key="t-apps">Application</li>

                <li>
                    <a href="{{route('chat')}}" class="waves-effect">
                        <i class="bx bx-chat"></i>
                        <span key="t-chat">Chat</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-detail"></i>
                        <span key="t-ecommerce">Article</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('poste')}}" key="t-products">Poste</a></li>
                        <li><a href="ecommerce-product-detail.html" key="t-product-detail">Commentaire</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-briefcase-alt-2"></i>
                        <span key="t-projects">Projets</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="projects-create.html" key="t-create-new">Nouveau projet</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-contacts">Contacts</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="contacts-grid.html" key="t-user-grid">Users Grid</a></li>
                        <li><a href="contacts-list.html" key="t-user-list">Users List</a></li>
                        <li><a href="contacts-profile.html" key="t-profile">Profile</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>