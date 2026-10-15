<?php

use AccountManager\service\Accounts;
use admin\Admin;
use Dashboard\api\page\DashboardControllerBase;
use database\exceptions\ProviderException;
use redirect\Redirect;
use response\Response;
use router\security\Csrf;
use view\View;

class AccountManagerController extends DashboardControllerBase
{
    // AWT copies the view bundle to public properties during rendering.
    public string $name;
    public string $navigation;
    public string $tab;
    public bool $manager;
    public array $current;
    public array $profile;
    public array $accounts;
    public bool $showProfile;
    public bool $showDirectory;
    public bool $ownProfile;
    public bool $canEdit;
    public string $profileUrl;
    public string $editUrl;
    public bool $editing;
    public ?string $error;
    public bool $passwordError;
    public mixed $saved;
    public string $token;
    public bool $creating;
    public array $newAccount;

    public function __construct()
    {
        $this->checkAuth();
    }

    public function index(array|string $params): View|Response
    {
        return $this->renderPage('directory');
    }

    public function mine(array|string $params): View|Response
    {
        return $this->renderPage('profile');
    }

    public function show(array|string $params): View|Response
    {
        return $this->renderAccountPage('profile', $params);
    }

    public function edit(array|string $params): View|Response
    {
        return $this->renderAccountPage('edit', $params);
    }

    public function createForm(array|string $params): View|Response
    {
        return $this->renderPage('create');
    }

    public function help(array|string $params): View
    {
        $current = (new Accounts())->current(new Admin());
        $this->viewName = 'help';
        return $this->view([
            'name' => 'Accounts help',
            'navigation' => $this->buildNavigation(),
            'manager' => (int)$current['permission_level'] === 0,
        ]);
    }

    public function save(array|string $params): View|Redirect|Response
    {
        return $this->submitProfile(false);
    }

    public function password(array|string $params): View|Redirect|Response
    {
        return $this->submitProfile(true);
    }

    public function create(array|string $params): View|Redirect|Response
    {
        return $this->submitManagement(true);
    }

    public function delete(array|string $params): View|Redirect|Response
    {
        return $this->submitManagement(false);
    }

    public function view(array $data = []): View
    {
        $this->bundle = $data;
        $this->createDocument();
        return $this;
    }

    private function renderAccountPage(string $mode, array|string $params): View|Response
    {
        try {
            return $this->renderPage($mode, $this->accountId(is_array($params) ? ($params['account_id'] ?? null) : null));
        } catch (InvalidArgumentException $exception) {
            return Response::make(404, 'Account not found.');
        }
    }

    private function submitManagement(bool $create): View|Redirect|Response
    {
        $accounts = new Accounts();
        $actor = new Admin();
        if ((int)$accounts->current($actor)['permission_level'] !== 0) {
            return Response::make(403, 'Only administrators can manage accounts.');
        }
        try {
            $this->validateCsrf();
            if ($create) {
                $id = $accounts->create($actor, $_POST);
                return (new Redirect())->redirect('/dashboard/accounts/' . $id . '?saved=created');
            }
            $accounts->delete($actor, $this->accountId($_POST['account_id'] ?? null));
            return (new Redirect())->redirect('/dashboard/accounts?saved=deleted');
        } catch (InvalidArgumentException $exception) {
            http_response_code(422);
            return $this->renderPage($create ? 'create' : 'directory', error: $exception->getMessage());
        } catch (ProviderException|PDOException|RuntimeException $exception) {
            http_response_code(500);
            return $this->renderPage($create ? 'create' : 'directory', error: $create ? 'Unable to create the account. Please try again.' : 'Unable to delete this account. It may still be referenced by other records.');
        }
    }

    private function submitProfile(bool $password): View|Redirect|Response
    {
        $id = null;
        try {
            $actor = new Admin();
            $accounts = new Accounts();
            $id = $this->accountId($_POST['account_id'] ?? null);
            $current = $accounts->current($actor);
            if ($id !== (int)$current['id'] && (int)$current['permission_level'] !== 0) {
                return Response::make(403, 'You do not have permission to edit this account.');
            }
            $this->validateCsrf();
            if ($password) {
                $accounts->password($actor, $id, $_POST);
            } else {
                $upload = $_FILES['profile_picture'] ?? null;
                if ($upload !== null && !is_array($upload)) {
                    throw new InvalidArgumentException('Choose a valid profile picture upload.');
                }
                $accounts->save($actor, $id, $_POST, $upload);
            }
            $url = $id === (int)$current['id'] ? '/dashboard/accounts/me' : '/dashboard/accounts/' . $id;
            return (new Redirect())->redirect($url . '?saved=' . ($password ? 'password' : 'profile'));
        } catch (InvalidArgumentException $exception) {
            http_response_code(422);
            return $this->renderPage($password ? 'profile' : 'edit', $id, $exception->getMessage(), $password);
        } catch (ProviderException|PDOException|RuntimeException $exception) {
            http_response_code(500);
            return $this->renderPage($password ? 'profile' : 'edit', $id, 'Unable to save changes. Please try again.', $password);
        }
    }

    private function accountId(mixed $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            throw new InvalidArgumentException('Choose a valid account.');
        }
        return $id;
    }

    private function renderPage(string $mode, ?int $id = null, ?string $error = null, bool $passwordError = false): View|Response
    {
        $accounts = new Accounts();
        $actor = new Admin();
        $current = $accounts->current($actor);
        $manager = (int)$current['permission_level'] === 0;
        if ($mode === 'create' && !$manager) {
            return Response::make(403, 'Only administrators can create accounts.');
        }
        try {
            $profile = $id === null ? $current : $accounts->profile($actor, $id);
        } catch (InvalidArgumentException $exception) {
            return Response::make(404, 'Account not found.');
        }
        $ownProfile = (int)$profile['id'] === (int)$current['id'];
        $canEdit = $ownProfile || $manager;
        if ($mode === 'edit' && !$canEdit) {
            return Response::make(403, 'You do not have permission to edit this account.');
        }
        $editing = $mode === 'edit';
        if ($editing && $error !== null && (string)$profile['id'] === ($_POST['account_id'] ?? null)) {
            foreach (['firstname', 'lastname', 'email', 'username', 'permission_level'] as $field) {
                if (isset($_POST[$field]) && is_string($_POST[$field])) {
                    $profile[$field] = $_POST[$field];
                }
            }
        }
        $newAccount = ['firstname' => '', 'lastname' => '', 'email' => '', 'username' => '', 'permission_level' => '2'];
        if ($mode === 'create' && $error !== null) {
            foreach ($newAccount as $field => $value) {
                if (isset($_POST[$field]) && is_string($_POST[$field])) {
                    $newAccount[$field] = $_POST[$field];
                }
            }
        }
        $this->viewName = 'index';
        return $this->view([
            'name' => $this->pageTitle($mode, $ownProfile),
            'navigation' => $this->buildNavigation(),
            'tab' => $ownProfile && in_array($mode, ['profile', 'edit'], true) ? 'my-account' : 'accounts',
            'manager' => $manager, 'current' => $current, 'profile' => $profile,
            'accounts' => $mode === 'directory' ? $accounts->directory($actor) : [],
            'showProfile' => in_array($mode, ['profile', 'edit'], true), 'showDirectory' => $mode === 'directory',
            'creating' => $mode === 'create', 'newAccount' => $newAccount,
            'ownProfile' => $ownProfile, 'canEdit' => $canEdit,
            'profileUrl' => $ownProfile ? '/dashboard/accounts/me' : '/dashboard/accounts/' . $profile['id'],
            'editUrl' => '/dashboard/accounts/' . $profile['id'] . '/edit',
            'editing' => $editing, 'error' => $error, 'passwordError' => $passwordError,
            'saved' => $error === null ? ($_GET['saved'] ?? '') : '', 'token' => (new Csrf())->token(),
        ]);
    }

    private function validateCsrf(): void
    {
        if (!(new Csrf())->validateToken($_POST['_token'] ?? null)) {
            throw new InvalidArgumentException('Your session expired. Reload and try again.');
        }
    }

    private function pageTitle(string $mode, bool $ownProfile): string
    {
        return match ($mode) {
            'create' => 'Create account',
            'directory' => 'Accounts',
            default => $ownProfile ? 'My Account' : 'Account profile',
        };
    }
}
