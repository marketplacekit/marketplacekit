<ul class="nav nav- mb-4">
    <li class="nav-item {{ active(['panel.users.index'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.users.index')}}">Users</a>
    </li>
    <li class="nav-item {{ active(['panel.roles.index'])  }}">
        <a class="nav-link" href="{{route('panel.roles.index')}}">Roles</a>
    </li>
</ul>