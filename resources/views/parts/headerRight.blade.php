<div class="header-right">
    <div class="dashboard-setting user-notification">
        <div class="dropdown">
            <a
                class="dropdown-toggle no-arrow"
                href="javascript:;"
                data-toggle="right-sidebar"
            >
                <i class="dw dw-settings2"></i>
            </a>
        </div>
    </div>
    <div class="user-notification">
        <div class="dropdown">
            <a
                class="dropdown-toggle no-arrow"
                href="#"
                role="button"
                data-toggle="dropdown"
            >
                <i class="icon-copy dw dw-notification"></i>
                <span class="badge notification-active"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="notification-list mx-h-350 customscroll">
                    Nothing new!
                </div>
            </div>
        </div>
    </div>
    <div class="user-info-dropdown">
        <div class="dropdown">
            <a
                class="dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
            >
                <span class="user-icon">
                    <img src="" alt="" />
                </span>
                <span class="user-name">{{session('name')}}</span>
            </a>
            <div
                class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
            >
                <a class="dropdown-item" href="{{route('employee.profile.edit')}}"
                    ><i class="dw dw-user1"></i> Profile</a
                >
                <a class="dropdown-item" href="{{route('employee.profile.edit')}}"
                    ><i class="dw dw-settings2"></i> Settings</a
                >
                <a class="dropdown-item" href="javascript:;"
                    ><i class="#"></i> Help</a
                >

            </div>
        </div>
    </div>

</div>
