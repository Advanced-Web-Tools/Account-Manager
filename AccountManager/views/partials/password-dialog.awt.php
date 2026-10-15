<dialog id="password-dialog" class="account-dialog" aria-labelledby="password-title" data-error="{{ $passwordError ? 'true' : 'false' }}">
    <form id="password-form" action="/dashboard/accounts/password" method="post">
        <div class="account-heading"><h2 id="password-title" class="h4">{{ $ownProfile ? 'Change password' : 'Reset password' }}</h2><button class="btn btn-ghost btn-icon" type="button" id="close-password" aria-label="Close password dialog"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button></div>
        <p class="text-muted">{{ $ownProfile ? 'Enter your current password and choose a new one.' : 'Set a new password for this account.' }}</p>
        @if($passwordError)<div class="alert alert-danger" role="alert">{{ $error }}</div>@endif
        <input type="hidden" name="_token" value="{{ $token }}">
        <input type="hidden" name="account_id" value="{{ $profile['id'] }}">
        @if($ownProfile)
            <div class="form-field"><label class="form-label" for="current-password">Current password</label><input class="form-control" type="password" id="current-password" name="current_password" autocomplete="current-password" required></div>
        @endif
        <div class="form-field"><label class="form-label" for="new-password">New password</label><input class="form-control" type="password" id="new-password" name="new_password" autocomplete="new-password" maxlength="72" required></div>
        <div class="form-field"><label class="form-label" for="confirm-password">Confirm new password</label><input class="form-control" type="password" id="confirm-password" name="password_confirmation" autocomplete="new-password" maxlength="72" required aria-describedby="password-match"></div>
        <p id="password-match" class="text-small" aria-live="polite"></p>
        <div class="account-actions"><button id="save-password" class="btn btn-primary" type="submit" disabled><i class="fa-solid fa-key" aria-hidden="true"></i> {{ $ownProfile ? 'Change password' : 'Reset password' }}</button><button id="cancel-password" class="btn btn-ghost" type="button">Cancel</button></div>
    </form>
</dialog>
