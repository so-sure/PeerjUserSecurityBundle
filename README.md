Peerj UserSecurityBundle README.
===============================


## Notes:  
  
This bundle is for the symfony framework and requires Symfony >=2.1 and PHP >=5.3.2
  
This project uses Doctrine >=2.1 and so does not require any specific database.
  

Available on:
* [Github](http://www.github.com/Peerj/UserSecurityBundle)
* [Packagist](https://packagist.org/packages/peerj/peerj-user-security-bundle)

For the full copyright and license information, please view the [LICENSE](http://github.com/PeerJ/PeerjUserSecurityBundle/blob/master/Resources/meta/LICENSE) file that was distributed with this source code.

## Description:

Use this bundle to mitigate brute force dictionary attacks on your sites login.

## Features.

SecurityBundle Provides the following features:

1. Prevent brute force attacks being carried out by limiting number of login attempts:
	1. When limit is reached, either turn a HTTP 500 status or redirect to a configurable route
4. All limits are configurable.
5. Routes to block are configurable.
6. Route for account recovery page is configurable.

## Documentation.

Documentation can be found in the `Resources/doc/index.md` file in this bundle:

[Read the Documentation](http://github.com/codeconsortium/PeejUserSecurityBundle/blob/master/Resources/doc/index.md).

## Installation.

All the installation instructions are located in [documentation](http://github.com/PeerJ/PeerjUserSecurityBundle/blob/master/Resources/doc/install.md).

## License.

This software is licensed under the MIT license. See the complete license file in the bundle:

	Resources/meta/LICENSE

[Read the License](http://github.com/peerj/PeerjUserSecurityBundle/blob/master/Resources/meta/LICENSE).

## Reporting an issue or feature request.

Issues and feature requests are tracked in the [Github issue tracker](http://github.com/Peerj/PeerjUserSecurityBundle/issues).

