<div class="left-side-bar">
    <div class="brand-logo">
        <a href="index.html">
            <img src="{{ asset('src\images\cryno-long-logo.png') }}" alt="" class="dark-logo" />
            <img
                src="{{ asset('src\images\cryno-long-logo.png') }}"
                alt=""
                class="light-logo"
            />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="javascript:;" class="dropdown-toggle no-arrow">
						<span class="micon bi bi-house"></span
								></span><span class="mtext">Home</span>
					</a>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon icon-copy ti-shopping-cart"></span><span class="mtext">Orders</span>
                    </a>
                    <ul class="submenu">
                        <li><i class="fa-solid fa-palette"></i> <a href="{{route('orders.create')}}">Make Order</a></li>
                        <li><a href="{{route('orders.index')}}">All Orders</a></li>
                        <li><a href="{{route('ordered-books.index')}}">Ordered Books Status</a></li>
                    </ul>
                </li>
                <li>
                    <a href="sitemap.html" class="dropdown-toggle no-arrow">
						<span class="micon icon-copy ti-clipboard"></span><span class="mtext">Master Catalogue</span>
					</a>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon icon-copy ti-video-clapper"></span><span class="mtext">Production</span>
                    </a>
                    <ul class="submenu">
                        <li><i class="fa-solid fa-palette"></i> <a href="{{route('design-queues.index')}}">Design Queue</a></li>
                        <li><a href="{{route('printing-queues.index')}}">Printing Queue</a></li>
                        <li><a href="{{route('cover-printing-queues.index')}}">Cover Printing Queue</a></li>
                        <li><a href="{{route('binding-queues.index')}}">Binding Queue</a></li>
                        <li><a href="{{route('qc-queues.index')}}">QC Queue</a></li>
                        <li><a href="{{route('packaging-queues.index')}}">Packaging Queue</a></li>
                        <li><a href="{{route('shipment-queues.index')}}">Shipment Queue</a></li>
                    </ul>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Account Information</div>
                </li>

                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
						<span class="micon"><i class="icon-copy fa fa-users" aria-hidden="true"></i></span><span class="mtext">Users</span>
					</a>
                    <ul class="submenu">
                        <li><i class="fa-solid fa-palette"></i> <a href="{{route('employee.register')}}">Add User</a></li>
                        <li><a href="{{route('employee.all')}}">View Users</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{route('employee.profile.edit')}}" class="dropdown-toggle no-arrow">
						<span class="micon"><i class="icon-copy fa fa-user" aria-hidden="true"></i></span><span class="mtext">My Account</span>
					</a>
                </li>

                <li>
                    <span href="#" class="dropdown-toggle no-arrow">
						<span class="micon"><i class="icon-copy fa fa-sign-out" aria-hidden="true"></i></i></span>
                        <span class="mtext">
                            <form action="{{ route('employee.logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link">Logout</button>
                            </form>
                        </span>
					</span>
                </li>
            </ul>
        </div>
    </div>
</div>
