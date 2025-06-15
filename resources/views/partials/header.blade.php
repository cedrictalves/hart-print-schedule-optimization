<header>
    <div class="container">
        <div class="row">
            <nav role="navigation">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset('images/logo.png') }}" alt="HART Print Logo" />
                </a>
                <ul>
                    <li><a  href="{{ route('order.form') }}">Place an order</a></li>
                    <li><a  href="{{ route('schedule') }}">See the schedule</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
