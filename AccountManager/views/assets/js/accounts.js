(() => {
    const placeholder = '/awt_packages/AccountManager/views/assets/images/profile_placeholder.webp';
    document.querySelectorAll('[data-profile-picture]').forEach(image => {
        const fallback = () => { image.src = placeholder; };
        image.addEventListener('error', fallback, { once: true });
        if (image.complete && image.naturalWidth === 0) fallback();
    });

    document.querySelectorAll('[data-picture-picker]').forEach(picker => {
        const input = picker.querySelector('input[type="file"]');
        const preview = picker.querySelector('[data-picture-preview]');
        const name = picker.querySelector('[data-picture-name]');
        const clear = picker.querySelector('[data-clear-picture]');
        const original = preview.src;
        let temporaryUrl;
        const update = () => {
            if (temporaryUrl) URL.revokeObjectURL(temporaryUrl);
            const file = input.files[0];
            input.setCustomValidity('');
            if (file && (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(file.type) || file.size > 5 * 1024 * 1024)) {
                input.setCustomValidity('Choose a JPEG, PNG, GIF or WebP image up to 5 MB.');
                input.reportValidity();
            }
            temporaryUrl = file && !input.validationMessage ? URL.createObjectURL(file) : null;
            preview.src = temporaryUrl || original;
            name.textContent = file ? file.name : 'No file selected';
            clear.hidden = !file;
        };
        input.addEventListener('change', update);
        clear.addEventListener('click', () => {
            input.value = '';
            input.dispatchEvent(new Event('change', { bubbles: true }));
            input.focus();
        });
        input.form.addEventListener('reset', () => setTimeout(update, 0));
    });

    function validatePasswords(form, password, confirmation, submit, message, current = null) {
        if (!form) return;
        const validate = () => {
            const mismatch = confirmation.value !== '' && password.value !== confirmation.value;
            confirmation.setCustomValidity(mismatch ? 'Passwords do not match.' : '');
            message.textContent = mismatch ? 'Passwords do not match.' : '';
            submit.disabled = !password.value || password.value !== confirmation.value || Boolean(current && !current.value);
        };
        form.addEventListener('input', validate);
        form.addEventListener('submit', event => {
            validate();
            if (submit.disabled || !form.reportValidity()) event.preventDefault();
            else submit.disabled = true;
        });
        form.addEventListener('reset', () => setTimeout(validate, 0));
        validate();
    }
    validatePasswords(document.getElementById('create-account-form'), document.getElementById('create-password'), document.getElementById('create-confirmation'), document.getElementById('create-account-submit'), document.getElementById('create-password-match'));

    const deletion = document.getElementById('delete-account-dialog');
    if (deletion) {
        document.querySelectorAll('[data-delete-account]').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('delete-account-id').value = button.dataset.deleteAccount;
                document.getElementById('delete-account-name').textContent = button.dataset.accountName;
                deletion.showModal();
            });
        });
        document.getElementById('cancel-delete-account').addEventListener('click', () => deletion.close());
    }

    const dialog = document.getElementById('password-dialog');
    if (dialog) {
        const passwordForm = document.getElementById('password-form');
        validatePasswords(passwordForm, document.getElementById('new-password'), document.getElementById('confirm-password'), document.getElementById('save-password'), document.getElementById('password-match'), document.getElementById('current-password'));
        document.getElementById('open-password').addEventListener('click', () => dialog.showModal());
        document.getElementById('close-password').addEventListener('click', () => dialog.close());
        document.getElementById('cancel-password').addEventListener('click', () => dialog.close());
        dialog.addEventListener('close', () => passwordForm.reset());
        if (dialog.dataset.error === 'true') dialog.showModal();
    }

    const form = document.getElementById('profile-form');
    if (!form) return;
    const save = document.getElementById('save-profile');
    const fields = [...form.querySelectorAll('input:not([type="hidden"]), select')].filter(field => !field.disabled);
    const initial = fields.map(field => field.value);
    const changed = () => { save.hidden = !fields.some((field, i) => field.type === 'file' ? field.files.length > 0 : field.value !== initial[i]); };
    form.addEventListener('input', changed);
    form.addEventListener('change', changed);
})();
