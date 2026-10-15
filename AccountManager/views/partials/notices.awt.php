@if($error && !$passwordError)
    <div class="alert alert-danger" role="alert">{{ $error }}</div>
@endif
@if(in_array($saved, ['profile', 'password', 'created', 'deleted'], true))
    <div class="alert alert-success" role="status">{{ ['profile' => 'Account saved.', 'password' => 'Password changed.', 'created' => 'Account created.', 'deleted' => 'Account deleted.'][$saved] }}</div>
@endif
