<ul class="nav nav- mb-4">
	@if(config('marketplace.allow_addon_uploads'))
    <li class="nav-item {{ active(['panel.addons.index'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.addons.index')}}">Installed Addons</a>
    </li>
    <li class="nav-item {{ active(['panel.addons.create'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.addons.create')}}">Upload Zip</a>
    </li>
    @endif
</ul>
