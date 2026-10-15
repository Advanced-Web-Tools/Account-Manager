@extends("Dashboard.views.templates.main")
@section("content")
    <link rel="stylesheet" href="/awt_packages/AccountManager/views/assets/css/accounts.css">
    <div id="accounts-app">
        @include('AccountManager.views.partials.header')
        <div class="container py-5">
            @include('AccountManager.views.partials.notices')

            @if($showProfile)
                @include('AccountManager.views.partials.profile')
                @if($canEdit)
                    @include('AccountManager.views.partials.password-dialog')
                @endif
            @endif

            @if($creating)
                @include('AccountManager.views.partials.create-account')
            @endif

            @if($showDirectory)
                @include('AccountManager.views.partials.directory')
                @if($manager)
                    @include('AccountManager.views.partials.delete-dialog')
                @endif
            @endif
        </div>
    </div>
    <script src="/awt_packages/AccountManager/views/assets/js/accounts.js" defer></script>
@endsection
