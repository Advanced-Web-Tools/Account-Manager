<div class="account-list-heading">
    <div><h2 class="h3">{{ $manager ? 'Manage accounts' : 'Accounts' }}</h2><p class="text-muted">{{ $manager ? 'Create accounts or select one to view or edit its profile.' : 'View an account profile or get in touch.' }}</p></div>
    @if($manager)<a class="btn btn-primary" href="/dashboard/accounts/create"><i class="fa-solid fa-user-plus" aria-hidden="true"></i> Create account</a>@endif
</div>
<div class="accounts-grid">
    @foreach($accounts as $account)
        <article class="card account-card">
            <div class="account-avatar">
                <img src="{{ \AccountManager\service\Accounts::picture($account['profile_picture']) }}" alt="Profile picture for {{ $account['firstname'] }} {{ $account['lastname'] }}" loading="lazy" referrerpolicy="no-referrer" data-profile-picture>
            </div>
            <h3 class="h4">{{ trim($account['firstname'] . ' ' . $account['lastname']) }}</h3>
            <p class="text-muted">{{ $account['email'] }}</p>
            <div class="account-actions">
                <a class="btn btn-outline-secondary" href="/dashboard/accounts/{{ $account['id'] }}"><i class="fa-solid fa-user" aria-hidden="true"></i> View profile<span class="visually-hidden"> {{ $account['username'] }}</span></a>
                @if($manager)<a class="btn btn-outline-secondary" href="/dashboard/accounts/{{ $account['id'] }}/edit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit<span class="visually-hidden"> {{ $account['username'] }}</span></a>@endif
                @if($manager && (int)$account['id'] !== (int)$current['id'])
                    <button class="btn btn-outline-danger" type="button" data-delete-account="{{ $account['id'] }}" data-account-name="{{ trim($account['firstname'] . ' ' . $account['lastname']) }}"><i class="fa-solid fa-trash-can" aria-hidden="true"></i> Delete<span class="visually-hidden"> {{ $account['username'] }}</span></button>
                @endif
                <a class="btn btn-primary" href="mailto:{{ rawurlencode($account['email']) }}"><i class="fa-solid fa-envelope" aria-hidden="true"></i> Send email<span class="visually-hidden"> to {{ $account['username'] }}</span></a>
            </div>
        </article>
    @endforeach
</div>
