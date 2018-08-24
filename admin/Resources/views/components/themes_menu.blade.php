<ul class="nav nav- mb-4">
	@if(config('marketplace.allow_addon_uploads'))
    <li class="nav-item {{ active(['panel.themes.index'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.themes.index')}}">Installed Themes</a>
    </li>
    <li class="nav-item {{ active(['panel.themes.create'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.themes.create')}}">Upload Zip</a>
    </li>
    @endif
<!--<<li class="nav-item {{ active(['panel.menu.index'])  }}">
        <a class="nav-link" href="{{route('panel.menu.index')}}">Themes Repository</a>
    </li>
    <!--<li class="nav-item {{ active(['panel.settings.index'])  }}">
        <a class="nav-link" href="#">Emails</a>
    </li>
    <li class="nav-item {{ active(['panel.settings.index'])  }}">
        <a class="nav-link" href="#">Translations</a>
    </li>-->

</ul>