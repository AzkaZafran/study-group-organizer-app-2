<nav class="navbar navbar-expand-lg mb-3" style="background-color: #1E3A8A;"
    data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand">{{ auth()->user()->username }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <form action="/logout" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn fw-medium" style="background-color: #4c0099;">Logout</button>
        </form>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav nav-underline ms-auto"> <!-- ms-auto pushes to right -->
                <li class="nav-item">
                    <a class="nav-link cnavbar-text {{ request()->is('dashboard*') ? 'active' : '' }}" 
                        aria-current="{{ request()->is('dashboard*') ? 'page' : '' }}" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link cnavbar-text {{ request()->is('friend*') ? 'active' : '' }}" 
                        aria-current="{{ request()->is('friend*') ? 'page' : '' }}" href="{{ route('friend list') }}">
                        Teman
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>