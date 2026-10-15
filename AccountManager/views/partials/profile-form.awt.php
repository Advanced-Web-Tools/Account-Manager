<form id="profile-form" enctype="multipart/form-data" action="/dashboard/accounts/save" method="post">
    <input type="hidden" name="_token" value="{{ $token }}">
    <input type="hidden" name="account_id" value="{{ $profile['id'] }}">
    <div class="profile-fields">
        @foreach(['firstname' => 'First name', 'lastname' => 'Last name', 'email' => 'Email', 'username' => 'Username'] as $field => $label)
            <div class="form-field">
                <label class="form-label" for="profile-{{ $field }}">{{ $label }}</label>
                <input class="form-control" id="profile-{{ $field }}" name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}" value="{{ $profile[$field] }}" maxlength="255" required>
            </div>
        @endforeach
        @include('AccountManager.views.partials.picture-picker')
        @if($manager)
            <div class="form-field"><label class="form-label" for="profile-permission">Permission level</label>
                <select class="form-control" id="profile-permission" name="permission_level">
                    @foreach([0 => 'Admin', 1 => 'Moderator', 2 => 'Author'] as $level => $role)
                        <option value="{{ $level }}" @if((string)$profile['permission_level'] === (string)$level) selected @endif>{{ $level }} · {{ $role }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    <div class="account-actions">
        <button id="save-profile" class="btn btn-primary" type="submit" hidden><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Save changes</button>
        <a class="btn btn-ghost" href="{{ $profileUrl }}">Cancel</a>
    </div>
</form>
