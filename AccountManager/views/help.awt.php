@extends('Dashboard.views.templates.main')
@section('content')
    <link rel="stylesheet" href="/awt_packages/AccountManager/views/assets/css/accounts.css">
    <div class="accounts-help-page">
        <header class="navbar border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 py-2">
                    <div class="navbar-brand">Accounts help</div>
                    <a class="btn btn-outline-secondary" href="/dashboard/accounts">Back to accounts</a>
                </div>
            </div>
        </header>
        <div class="py-5">
            <div class="container">
                <h1>Help with your accounts</h1>
                <p class="lead mb-4">Choose a topic below for guidance on profiles, pictures, passwords and account access.</p>

                <div class="card-stack content-panel accounts-help-panel">
                    <section class="card accounts-help-section" aria-labelledby="accounts-help-start">
                        <h2 id="accounts-help-start" class="h4">Getting started</h2>
                        <details open>
                            <summary>Where can I find my account?</summary>
                            <div class="accounts-help-answer">
                                <p>Choose My Account under Accounts in the sidebar, or use the My Account tab. Your profile shows your picture, first name, last name, email and username.</p>
                                <a href="/dashboard/accounts/me">Open My Account</a>
                            </div>
                        </details>
                        <details>
                            <summary>How do I edit and save my profile?</summary>
                            <div class="accounts-help-answer">
                                <p>Select Edit account to open the edit page. Update your details, then select Save changes. The button appears when a field or picture selection changes.</p>
                                <p>Each username and email address must be unique. If an address or username is already in use, choose another value and save again.</p>
                                <p>Cancel returns to the profile without saving. Save before leaving the page; changes are not saved automatically.</p>
                            </div>
                        </details>
                    </section>

                    <section class="card accounts-help-section" aria-labelledby="accounts-help-directory">
                        <h2 id="accounts-help-directory" class="h4">Account directory</h2>
                        <details>
                            <summary>Can I view another account?</summary>
                            <div class="accounts-help-answer">
                                <p>Every signed-in user can browse Accounts and select View profile. Account cards show a profile picture, full name and email address.</p>
                                <p>You can edit your own account. Only an administrator with permission level 0 can edit someone else's profile or reset their password.</p>
                                <a href="/dashboard/accounts">Browse accounts</a>
                            </div>
                        </details>
                        <details>
                            <summary>What does Send email do?</summary>
                            <div class="accounts-help-answer">
                                <p>Send email opens your configured email application with the account's address. Write and send the message in that application.</p>
                                <p>If nothing opens, check that your device or browser has an email application configured.</p>
                            </div>
                        </details>
                    </section>

                    <section class="card accounts-help-section" aria-labelledby="accounts-help-pictures">
                        <h2 id="accounts-help-pictures" class="h4">Profile pictures</h2>
                        <details>
                            <summary>How do I upload a picture?</summary>
                            <div class="accounts-help-answer">
                                <p>Open Edit account and select Choose image. The picker shows a preview and the selected filename. Select Save changes to upload the picture.</p>
                                <p>Choose a JPEG, PNG, GIF or WebP image up to 5 MB. Leave the picker empty to keep your current picture.</p>
                            </div>
                        </details>
                        <details>
                            <summary>What does Clear selection do?</summary>
                            <div class="accounts-help-answer">
                                <p>Clear selection removes the file you just picked and restores the previous preview. It does not delete a picture you have already saved.</p>
                                <p>Accounts without a picture use the included placeholder. An unavailable picture also falls back to the placeholder.</p>
                            </div>
                        </details>
                        <details>
                            <summary>Why was my upload rejected?</summary>
                            <div class="accounts-help-answer">
                                <p>Check the file format and size. Renaming a file to an image extension does not convert it into a supported image.</p>
                                <p>Choose the image again after an unsuccessful submission. If a valid image still cannot be saved, ask an administrator to check the server's upload limits and storage access.</p>
                            </div>
                        </details>
                    </section>

                    <section class="card accounts-help-section" aria-labelledby="accounts-help-passwords">
                        <h2 id="accounts-help-passwords" class="h4">Passwords and access</h2>
                        <details>
                            <summary>How do I change my password?</summary>
                            <div class="accounts-help-answer">
                                <p>On My Account, select Change password. Enter your current password, your new password and the new password again as confirmation.</p>
                                <p>The change button becomes available when all required fields are filled and the new passwords match. A confirmation appears after the password is changed.</p>
                            </div>
                        </details>
                        <details>
                            <summary>What if my current password is incorrect?</summary>
                            <div class="accounts-help-answer">
                                <p>Check the message in the password dialog and enter your current password again. Check your keyboard layout and Caps Lock if needed.</p>
                                <p>If you have forgotten your password, ask an administrator to reset it. Password fields must be entered again after an unsuccessful submission.</p>
                            </div>
                        </details>
                        <details>
                            <summary>Why do some accounts have more controls?</summary>
                            <div class="accounts-help-answer">
                                <p>Permission levels are 0 for Admin, 1 for Moderator and 2 for Author. All signed-in users can view profiles and update their own account.</p>
                                <p>Only permission 0 can create accounts, remove accounts, change permission levels or manage another account. Ask an administrator if your access needs to change.</p>
                            </div>
                        </details>
                        <details>
                            <summary>What if my session expires or saving fails?</summary>
                            <div class="accounts-help-answer">
                                <p>Reload the page and sign in again if prompted, then review and resubmit your changes. You may need to select your picture again.</p>
                                <p>If saving continues to fail, ask an administrator to check the account details, database connection and server storage.</p>
                            </div>
                        </details>
                    </section>

                    @if($manager)
                        @include('AccountManager.views.partials.help-administration')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
