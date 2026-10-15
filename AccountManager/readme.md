<p align="center">
  <img src="data/icon/icon.webp" alt="AccountManager icon" width="112" height="112">
</p>

<h1 align="center">AccountManager</h1>

<p align="center">
  <strong>Account management for your AWT dashboard.</strong><br>
  Personal profiles, administrator controls and command-line tools in one package.
</p>

---

AccountManager gives your team a central place to manage their profiles and find colleagues. Users can update their own details, upload a profile picture and change their password. Administrators can create accounts, manage access and maintain account details from the dashboard or the command line.

Built for Advanced Web Tools, AccountManager shares the Dashboard's visual style and light and dark themes.

## Features

| Feature | What you get |
| --- | --- |
| Personal profiles | Edit first name, last name, username and email, with Save appearing when details change. |
| Profile pictures | An image picker with thumbnail preview, filename and clear-selection control. Missing pictures display the included placeholder. |
| Account directory | Browse profile pictures, full names and email addresses, and open individual profiles. |
| Email shortcuts | Contact an account through a Send email button that opens your email application. |
| Password changes | A focused dialog with current-password verification, confirmation matching and clear error messages. |
| Administrator controls | Create accounts, edit other profiles, reset passwords and assign permission levels. |
| Account removal | A confirmation dialog for dashboard deletion, with protection against deleting your own account. |
| Command-line management | List, create, modify and remove accounts without a dashboard session, including creating the first administrator. |
| Access controls | All signed-in users can view profiles and edit their own account. Managing other accounts requires permission 0. |
| Request protection | Server-side permission checks, CSRF protection, unique username and email validation, and framework password hashing. |
| Image uploads | Framework VFS storage with content validation for JPEG, PNG, GIF and WebP files up to 5 MB. |
| Built-in help | Expandable guidance for profiles, uploads, passwords and access, plus administrator and CLI topics. |
| Dashboard integration | Shared Dashboard styles, light and dark themes, responsive layouts and Font Awesome action icons. |

## Getting started

Install AccountManager and its dependencies through your AWT package manager, then sign in to the dashboard. Open **Accounts** to browse the directory or **My Account** to view your own profile. Administrators also have access to **Create account** and management actions.

| Requirement | Version |
| --- | --- |
| Advanced Web Tools | 27.0.0 or later |
| Dashboard | 1.0.0 or later |
| URPC | 1.0.0 or later |
| AccountManager | 1.0.0 |

AccountManager uses AWT's existing account and VFS storage tables; no additional account schema is required.

## Pages and permissions

| Page | Address | Available to |
| --- | --- | --- |
| Account directory | `/dashboard/accounts` | All signed-in users |
| My Account | `/dashboard/accounts/me` | All signed-in users |
| Account profile | `/dashboard/accounts/{account_id}` | All signed-in users |
| Edit account | `/dashboard/accounts/{account_id}/edit` | The account owner or an administrator |
| Create account | `/dashboard/accounts/create` | Administrators |
| Accounts help | `/dashboard/accounts/help` | All signed-in users |

Permission levels are **0 — Admin**, **1 — Moderator** and **2 — Author**. Only permission 0 can manage another account or change permission levels. Users at every level can edit their own profile and change their own password.

## Command-line tools

Run these commands from your AWT application directory with `php awt`. Console commands do not require a dashboard session.

| Command | Purpose | Arguments |
| --- | --- | --- |
| `account_list` | Show all accounts in a table with IDs, usernames, names, emails and permission levels. | None. |
| `account_create` | Create an account, including the first administrator. | Required: `username`, `password`, `email`, `firstname`, `lastname`. Optional: `permission` (defaults to `0`). |
| `account_modify` | Update selected account details or reset a password. | Required: `id` and at least one of `username`, `email`, `firstname`, `lastname`, `permission`, `password`. |
| `account_remove` | Permanently remove an account. | Required: `id`. |

Use `name:value` arguments in any order, and quote values containing spaces.

**List all accounts**

```sh
php awt account_list
```

The table is ordered by account ID and includes the permission number and role. Passwords and session tokens are excluded.

**Create an administrator**

```sh
php awt account_create username:alice password:'YourStrongPassword' email:alice@example.com firstname:Alice lastname:Smith permission:0
```

**Update an account**

```sh
php awt account_modify id:12 email:alice.new@example.com permission:1
```

Only supplied fields change. To reset a password:

```sh
php awt account_modify id:12 password:'YourNewStrongPassword'
```

**Remove an account**

```sh
php awt account_remove id:12
```

Console removal is immediate and does not display the dashboard confirmation dialog.

**Command help**

```sh
php awt help account_list
php awt help account_create
php awt help account_modify
php awt help account_remove
```

The `account_` commands coexist with Dashboard's existing `admin_create` command.

## Package information

**Author:** ElStefanos · **License:** MIT
