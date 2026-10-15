<div class="form-field">
    <label class="form-label" for="profile-picture">Profile picture</label>
    <div class="picture-picker" data-picture-picker>
        <img class="picture-picker-preview" src="{{ \AccountManager\service\Accounts::picture($profile['profile_picture']) }}" alt="Profile picture preview" data-picture-preview data-profile-picture>
        <div class="picture-picker-content">
            <span class="picture-picker-name" data-picture-name aria-live="polite">No file selected</span>
            <div class="picture-picker-actions">
                <span class="picture-picker-button">
                    <input class="picture-picker-input" id="profile-picture" type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp" aria-describedby="profile-picture-help">
                    <label class="btn btn-outline-secondary btn-sm" for="profile-picture"><i class="fa-solid fa-arrow-up-from-bracket" aria-hidden="true"></i> Choose image</label>
                </span>
                <button class="btn btn-ghost btn-sm" type="button" data-clear-picture hidden>Clear selection</button>
            </div>
        </div>
    </div>
    <small id="profile-picture-help" class="text-muted">JPEG, PNG, GIF or WebP, up to 5 MB. Leave empty to keep your current picture.</small>
</div>
