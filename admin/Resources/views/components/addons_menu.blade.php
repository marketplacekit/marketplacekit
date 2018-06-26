
<ul class="nav nav- mb-4">
    <li class="nav-item {{ active(['panel.addons.index'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.addons.index')}}">Installed Addons</a>
    </li>
    <li class="nav-item {{ active(['panel.addons.create'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.addons.create')}}">Upload Zip</a>
    </li>
<!--<<li class="nav-item {{ active(['panel.menu.index'])  }}">
        <a class="nav-link" href="{{route('panel.menu.index')}}">Addon Repository</a>
    </li>
    <!--<li class="nav-item {{ active(['panel.settings.index'])  }}">
        <a class="nav-link" href="#">Emails</a>
    </li>
    <li class="nav-item {{ active(['panel.settings.index'])  }}">
        <a class="nav-link" href="#">Translations</a>
    </li>-->

</ul>