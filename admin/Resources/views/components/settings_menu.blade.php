<ul class="nav nav- mb-4">
    <li class="nav-item {{ active(['panel.settings.index'])  }}">
        <a class="nav-link pl-0" href="{{route('panel.settings.index')}}">General</a>
    </li>
    <li class="nav-item {{ active(['panel.fields.index'])  }}">
        <a class="nav-link" href="{{route('panel.fields.index')}}">Fields &amp; filters</a>
    </li>
    <li class="nav-item {{ active(['panel.pricing-models.index'])  }}">
        <a class="nav-link" href="{{route('panel.pricing-models.index')}}">Pricing models</a>
    </li>

</ul>

