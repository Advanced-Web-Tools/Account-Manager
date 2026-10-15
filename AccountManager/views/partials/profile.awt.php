<section class="card account-panel mb-4" aria-labelledby="profile-title">
    <div class="account-heading">
        <div class="account-avatar">
            <img src="{{ \AccountManager\service\Accounts::picture($profile['profile_picture']) }}" alt="Profile picture" referrerpolicy="no-referrer" data-profile-picture>
        </div>
        <div><h2 id="profile-title" class="h3">{{ trim($profile['firstname'] . ' ' . $profile['lastname']) }}</h2><p class="text-muted mb-0">{{ $profile['email'] }}</p></div>
    </div>
    <div class="account-actions">
        @if($canEdit)
            @if(!$editing)<a class="btn btn-outline-secondary" href="{{ $editUrl }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit account</a>@endif
        <button id="open-password" class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-key" aria-hidden="true"></i> {{ $ownProfile ? 'Change password' : 'Reset password' }}</button>
        @endif
        <a class="btn btn-ghost" href="mailto:{{ rawurlencode($profile['email']) }}"><i class="fa-solid fa-envelope" aria-hidden="true"></i> Send email</a>
    </div>
    <dl id="profile-details" class="profile-fields" @if($editing) hidden @endif>
        <div><dt>First name</dt><dd>{{ $profile['firstname'] }}</dd></div>
        <div><dt>Last name</dt><dd>{{ $profile['lastname'] }}</dd></div>
        <div><dt>Email</dt><dd>{{ $profile['email'] }}</dd></div>
        <div><dt>Username</dt><dd>{{ $profile['username'] }}</dd></div>
        @if($manager)<div><dt>Permission level</dt><dd>{{ $profile['permission_level'] }} · {{ ['Admin', 'Moderator', 'Author'][(int)$profile['permission_level']] ?? 'Unknown' }}</dd></div>@endif
    </dl>
    @if($canEdit && $editing)
    @include('AccountManager.views.partials.profile-form')
    @endif
</section>
