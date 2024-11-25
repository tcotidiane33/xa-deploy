@auth
    <div class="user-notification">
        <div class="dropdown">
            <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                <i class="icon-copy dw dw-notification"></i>
                <span class="badge notification-active"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="notification-list mx-h-350 customscroll">
                    <ul>
                        @foreach (auth()->user()->unreadNotifications as $notification)
                            <li>
                                <a href="#">
                                    <h3>{{ $notification->data['user_name'] }}</h3>
                                    <p>a consulté le fichier {{ $notification->data['file_name'] }} à
                                        {{ $notification->data['accessed_at'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endauth


{{-- <li>
                        <a href="#">
                            <img src="v3/public/backoffice/vendors/images/img.jpg" alt="" />
                            <h3>John Doe</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing
                                elit, sed...
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="v3/public/backoffice/vendors/images/photo1.jpg" alt="" />
                            <h3>Lea R. Frith</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing
                                elit, sed...
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="v3/public/backoffice/vendors/images/photo2.jpg" alt="" />
                            <h3>Erik L. Richards</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing
                                elit, sed...
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="v3/public/backoffice/vendors/images/photo3.jpg" alt="" />
                            <h3>John Doe</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing
                                elit, sed...
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="v3/public/backoffice/vendors/images/photo4.jpg" alt="" />
                            <h3>Renee I. Hansen</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing
                                elit, sed...
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="v3/public/backoffice/vendors/images/img.jpg" alt="" />
                            <h3>Vicki M. Coleman</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing
                                elit, sed...
                            </p>
                        </a>
                    </li> --}}
