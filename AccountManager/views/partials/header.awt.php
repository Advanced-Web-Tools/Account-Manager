<header class="navbar border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 py-2">
            <h1 class="navbar-brand mb-0">{{ $name }}</h1>
            <a class="btn btn-ghost" href="/dashboard/accounts/help"><i class="fa-solid fa-circle-question fa-fw" aria-hidden="true"></i> Help</a>
        </div>
    </div>
    <div class="container">
        <nav class="nav-tabs" aria-label="Account pages">
            <a href="/dashboard/accounts/me" class="{{ $tab === 'my-account' ? 'active' : '' }}" @if($tab === 'my-account') aria-current="page" @endif>My Account</a>
            <a href="/dashboard/accounts" class="{{ $tab === 'accounts' ? 'active' : '' }}" @if($tab === 'accounts') aria-current="page" @endif>{{ $manager ? 'Manage accounts' : 'Accounts' }}</a>
        </nav>
    </div>
</header>
