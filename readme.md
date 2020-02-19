# Conditions

## Usage

```php
use webdeveric\Conditions\Conditions;

$myRequirements = Conditions::all(
  Conditions::any('in_admin', 'is_cron_request'),
  Conditions::none('in_maintenance_mode'),
  'is_authenticated',
  'is_authorized'
);

$myRequirements->check();
```
