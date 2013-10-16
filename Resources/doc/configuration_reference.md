Peerj User SecurityBundle Configuration Reference.
================================================

All available configuration options are listed below with their default values.

``` yml
#
# for PeerjUserSecurityBundle
#
peerj_user_security:
    login_shield:
        enabled: true
        block_for_minutes: 10
        limit_failed_login_attempts: 25
        primary_login_route:
            name: fos_user_security_login
        redirect_when_denied_route: ~
        block_routes_when_denied:
            - fos_user_security_login
            - fos_user_security_check
    reset_shield:
        enabled: true
        block_for_minutes: 10
        limit_reset_attempts: 25
        primary_reset_route:
            name: fos_user_resetting_request
        redirect_when_denied_route: ~
        block_routes_when_denied:
            - fos_user_resetting_request
            - fos_user_resetting_send_email
			
```

- [Return back to the docs index](index.md).
