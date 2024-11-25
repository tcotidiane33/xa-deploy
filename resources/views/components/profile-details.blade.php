<div class="profile_details">
    <ul>
        <li class="dropdown profile_details_drop">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <div class="profile_img">
                    <span class="prfil-img"><img src="{{ auth()->user()->avatar }}" alt=""> </span>
                    <div class="user-name">
                        <p>{{ auth()->user()->name }}</p>
                        <span>{{ auth()->user()->role }}</span>
                    </div>
                    <i class="fa fa-angle-down lnr"></i>
                    <i class="fa fa-angle-up lnr"></i>
                    <div class="clearfix"></div>
                </div>
            </a>
            <ul class="dropdown-menu drp-mnu">
                <li> <a href="{{ route('settings') }}"><i class="fa fa-cog"></i> Settings</a> </li>
                <li> <a href="{{ route('account') }}"><i class="fa fa-user"></i> My Account</a> </li>
                <li> <a href="{{ route('profile') }}"><i class="fa fa-suitcase"></i> Profile</a> </li>
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>


{{-- phpCopy@include('components.profile-details') --}}
