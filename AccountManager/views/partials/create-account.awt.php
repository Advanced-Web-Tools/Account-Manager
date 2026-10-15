<section class="card account-panel mb-4" aria-labelledby="create-title">
    <h2 id="create-title" class="h4">Create account</h2>
    <p class="text-muted">Add the account details and an initial password. You can upload a profile picture after creating the account.</p>
    <form id="create-account-form" method="post" action="/dashboard/accounts/create">
        <input type="hidden" name="_token" value="{{ $token }}">
        <div class="profile-fields">
            @foreach(['firstname' => 'First name', 'lastname' => 'Last name', 'email' => 'Email', 'username' => 'Username'] as $field => $label)
                <div class="form-field"><label class="form-label" for="create-{{ $field }}">{{ $label }}</label><input class="form-control" id="create-{{ $field }}" name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}" value="{{ $newAccount[$field] }}" maxlength="255" required></div>
            @endforeach
            <div class="form-field"><label class="form-label" for="create-permission">Permission level</label><select class="form-control" id="create-permission" name="permission_level" required>
                @foreach([0 => 'Admin', 1 => 'Moderator', 2 => 'Author'] as $level => $role)
                    <option value="{{ $level }}" @if((string)$newAccount['permission_level'] === (string)$level) selected @endif>{{ $level }} · {{ $role }}</option>
                @endforeach
            </select></div>
            <div class="form-field"><label class="form-label" for="create-password">Password</label><input class="form-control" id="create-password" type="password" name="new_password" autocomplete="new-password" maxlength="72" required></div>
            <div class="form-field"><label class="form-label" for="create-confirmation">Confirm password</label><input class="form-control" id="create-confirmation" type="password" name="password_confirmation" autocomplete="new-password" maxlength="72" required aria-describedby="create-password-match"><small id="create-password-match" aria-live="polite"></small></div>
        </div>
        <div class="account-actions"><button class="btn btn-primary" id="create-account-submit" type="submit"><i class="fa-solid fa-user-plus" aria-hidden="true"></i> Create account</button><a class="btn btn-ghost" href="/dashboard/accounts">Cancel</a></div>
    </form>
</section>
