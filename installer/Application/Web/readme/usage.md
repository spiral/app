To create your first controller effortlessly, use the scaffolding command:

```bash
php app.php create:controller CurrentDate
```

After executing this command, a new controller class will be created in the `src/Endpoint/Web` directory. The
class will look like this:

```php
namespace App\Endpoint\Web;

final class CurrentDateController
{
    public function show(): string
    {
        return \date('Y-m-d H:i:s');
    }
}
```

The next step involves associating a route with your controller.

Spiral simplifies route definition in your application by utilizing PHP attributes. You just need to add the #[Route]
attribute to the controller's method, as shown below:

```php
use Spiral\Router\Annotation\Route;

// ...

#[Route(route: '/date', name: 'current-date', methods: 'GET')]
public function show(): string
{
    return \date('Y-m-d H:i:s');
}
```

To view the list of routes, use the following command:

```bash
php app.php route:list
```

You should observe your current-date route within the displayed list:

```bash
+--------------+--------+----------+------------------------------------------------+--------+
| Name:        | Verbs: | Pattern: | Target:                                        | Group: |
+--------------+--------+----------+------------------------------------------------+--------+
| current-date | GET    | /date    | App\Endpoint\Web\CurrentDateController->show   | web    |
+--------------+--------+----------+------------------------------------------------+--------+
```

#### What's Next?

Now, dive deeper into the fundamentals by reading some articles:

* [Routing](https://spiral.dev/docs/http-routing)
* [Annotated Routing](https://spiral.dev/docs/http-routing#attribute-based-routing)
* [Middleware](https://spiral.dev/docs/http-middleware)
* [Error Pages](https://spiral.dev/docs/http-errors)
* [Custom HTTP handler](https://spiral.dev/docs/cookbook-psr-15)
* [Scaffolding](https://spiral.dev/docs/basics-scaffolding)
