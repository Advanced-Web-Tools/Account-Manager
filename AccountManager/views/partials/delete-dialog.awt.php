<dialog id="delete-account-dialog" class="account-dialog" aria-labelledby="delete-account-title" aria-describedby="delete-account-description">
    <form action="/dashboard/accounts/delete" method="post">
        <h2 id="delete-account-title" class="h4">Delete account?</h2>
        <p id="delete-account-description">Delete <strong id="delete-account-name"></strong>? This permanently removes the account and its access to the dashboard.</p>
        <input type="hidden" name="_token" value="{{ $token }}">
        <input type="hidden" name="account_id" id="delete-account-id">
        <div class="account-actions"><button class="btn btn-ghost" type="button" id="cancel-delete-account" autofocus>Cancel</button><button class="btn btn-danger" type="submit"><i class="fa-solid fa-trash-can" aria-hidden="true"></i> Delete account</button></div>
    </form>
</dialog>
