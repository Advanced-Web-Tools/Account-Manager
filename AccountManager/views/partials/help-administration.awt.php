<section class="card accounts-help-section" aria-labelledby="accounts-help-administration">
    <h2 id="accounts-help-administration" class="h4">Administrator tools</h2>
    <details>
        <summary>How do I create or manage an account?</summary>
        <div class="accounts-help-answer">
            <p>Open Manage accounts and select Create account. Enter the profile details, choose a permission level and enter an initial password twice. After creation, you can edit the account to upload a picture.</p>
            <p>Use Edit on an account card to change its details or permissions. Reset password sets a new password for another account without requiring its current password. Changing your own password still requires your current password.</p>
            <a href="/dashboard/accounts/create">Create an account</a>
        </div>
    </details>
    <details>
        <summary>How do I remove an account?</summary>
        <div class="accounts-help-answer">
            <p>Select Delete on the account card, review the account name in the confirmation dialog and confirm deletion. Cancel closes the dialog without removing anything.</p>
            <p>Deletion permanently removes the account and its dashboard access. You cannot delete your own account from the dashboard. Removal may fail if other records still reference the account.</p>
        </div>
    </details>
</section>

<section class="card accounts-help-section" aria-labelledby="accounts-help-cli">
    <h2 id="accounts-help-cli" class="h4">Command-line management</h2>
    <details>
        <summary>How do I list all accounts in the console?</summary>
        <div class="accounts-help-answer">
            <p>Run this command from your AWT application directory:</p>
            <pre><code>php awt account_list</code></pre>
            <p>The table shows every account in ID order, with its username, first name, last name, email and permission level. Use the ID when modifying or removing an account.</p>
            <p>Passwords and session tokens are excluded. If there are no accounts, the command displays No accounts found.</p>
        </div>
    </details>
    <details>
        <summary>Can I create an administrator without signing in?</summary>
        <div class="accounts-help-answer">
            <p>With access to the server console, run the following from your AWT application directory. Replace the example values with the new account's details.</p>
            <pre><code>php awt account_create username:alice password:'YourStrongPassword' email:alice@example.com firstname:Alice lastname:Smith permission:0</code></pre>
            <p>Username, password, email, firstname and lastname are required. Permission defaults to 0; use 1 for Moderator or 2 for Author. These commands work without a dashboard session, including when creating the first administrator.</p>
        </div>
    </details>
    <details>
        <summary>How do I modify or remove an account from the console?</summary>
        <div class="accounts-help-answer">
            <p>Use the account ID and provide only the fields you want to change. Omitted fields keep their current values.</p>
            <pre><code>php awt account_modify id:12 email:new@example.com permission:1
php awt account_modify id:12 password:'YourNewStrongPassword'</code></pre>
            <p>Modification supports username, email, firstname, lastname, permission and password. To permanently remove an account:</p>
            <pre><code>php awt account_remove id:12</code></pre>
            <p>Console removal is immediate and does not show the dashboard confirmation dialog.</p>
        </div>
    </details>
    <details>
        <summary>Where can I find command usage?</summary>
        <div class="accounts-help-answer">
            <p>Arguments use name:value in any order. Quote values containing spaces. Run:</p>
            <pre><code>php awt help account_list
php awt help account_create
php awt help account_modify
php awt help account_remove</code></pre>
            <p>The account_ commands coexist with Dashboard's admin_create command.</p>
        </div>
    </details>
</section>
