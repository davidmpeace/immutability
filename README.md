# Immutability

Immutability is a simple package that is used in your Eloquent models to enforce attribute immutability.  Immutable attributes may be set, and changed once, but once the model is saved, the value can not be changed.

## License

Immutability is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Installation

To get started with Immutability, add to your `composer.json` file as a dependency:

    composer require davidmpeace/immutability

### Basic Usage

To use the Immutability library, you simply need to use the Immutability trait for any model you wish to identify immutable attributes for.  Typically, you would want to implement the trait in your super-class so that all your sub-classes will automatically inherit the functionality.

```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent\Attributes\Immutability;

class MyAppSuperModel extends Model
{
    use Immutability;

    protected $immutable = ['id', 'uuid'];
}
```

Class override.

```
<?php
namespace App;

class User extends MyAppSuperModel
{
    protected $immutable = ['id', 'uuid', 'username'];
}
```

Catching exceptions

```
$user = User::find(1);

try {
    $user->username = "myNewUserName";
    $user->save();
} catch ( \Eloquent\Attributes\Exceptions\ImmutableFieldViolationException $e ) {
   // Handle immutable attribute violation error
} catch ( \Exception $e ) {
   // Handle error
}
```
