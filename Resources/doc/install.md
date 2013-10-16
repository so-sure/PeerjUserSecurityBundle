Installing Peerj User SecurityBundle 1.x
======================================

## Dependencies:

No Dependencies.

## Installation:

Installation takes only 5 steps:

1. Download and install dependencies via Composer.
2. Register bundles with AppKernel.php.
3. Update your app/config/config.yml.
4. enable handlers

### Step 1: Download and install dependencies via Composer.

Append the following to end of your applications composer.json file (found in the root of your Symfony2 installation):

``` js
// composer.json
{
    // ...
    "require": {
        // ...
        "peerj/peerj-user-security-bundle": "dev-master"
    }
}
```

NOTE: Please replace ``dev-master`` in the snippet above with the latest stable branch, for example ``2.0.*``.

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

``` bash
$ php composer.phar update
```

### Step 2: Register bundles with AppKernel.php.

Now, Composer will automatically download all required files, and install them
for you. All that is left to do is to update your ``AppKernel.php`` file, and
register the new bundle:

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
		new Peerj\UserSecurityBundle\PeerjUserSecurityBundle(),
		...
	);
}
```

### Step 3: Update your app/config/config.yml.

In your app/config/config.yml add:

``` yml
#
# for Peerj User SecurityBundle
#
peerj_user_security:
    login_shield:
        enabled: true
        block_for_minutes: 1
        limit_failed_login_attempts: 2
        primary_login_route:
            name: fos_user_security_login
        redirect_when_denied_route: ~
        block_routes_when_denied:
            - fos_user_security_login
            - fos_user_security_check
    reset_shield:
        enabled: true
        block_for_minutes: 1
        limit_reset_attempts: 2
        primary_reset_route:
            name: fos_user_resetting_request
        redirect_when_denied_route: ~
        block_routes_when_denied:
            - fos_user_resetting_request
            - fos_user_resetting_send_email

```

Add or remove routes as you see fit to the list of routes to block when denied.

### Step 4: enable handlers

You have to enable your login-/logout-handlers via app/config/security.yml:

```
security:
    firewalls:
        main:
            form_login:
                failure_handler: peerj_user_security.component.authentication.handler.login_failure_handler
```


## Next Steps.

Installation should now be complete!

If you need further help/support, have suggestions or want to contribute please submit pull requests.

- [Return back to the docs index](index.md).
- [Configuration Reference](configuration_reference.md).
