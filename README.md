#  Leaf Twig

[![Latest Stable Version](https://img.shields.io/badge/release-v1.0-blue)](https://packagist.org/packages/timworx/leaf-twig)
[![Twig Version](https://img.shields.io/badge/Twig-v3.21-BACF29)](https://packagist.org/packages/twig/twig)

The standalone version of [Symfony's Twig templating engine](https://twig.symfony.com/) for use in the Leaf framework.

This package is oriented towards the [Leaf/Blade](https://packagist.org/packages/leafs/blade) package. 

## Installation

Install using composer:

```bash
composer require timworx/leaf-twig
```

## Usage

Create a Twig instance by passing it the folder(s) where your template files are located, and the Twig options like a cache folder.
Render a template by calling the `render` method.
More information about the Twig templating engine can be found on https://twig.symfony.com/.

```php
use Leaf\Twig;

$twig = new Twig(['templates'], ['cache' => 'var/cache']);

```

You can also initialise it globally and configure the instace later.

```php
$twig = new Twig;

// somewhere, maybe in a different file
$twig->configure(['templates'], ['cache' => 'var/cache']);

// alternative
$twig->config(['templates'], ['cache' => 'var/cache']);
```

```php
echo $twig->render('index.html.twig', ['name' => 'John Doe']);
exit;
```

We can have this as our template `index.html.twig`

```html
<!Doctype html>
<html>
    <head>
        <title>{{ name }}</title>
    </head>
    <body>
        <div class="container">{{ name }}</div>
    </body>
</html>
```

You can extend Twig using TwigFilters, TwigFunctions and Extensions:

```php
// Function
$twig->addFunction('md5', function ($string) {
    return md5($string);
});

// Filter
$twig->addFilter('md5', function ($string) {
    return md5($string);
});

// Extension
$twig->addExtension(new \App\Extension\MyExtension()); // Your own created extension
```

Which allows you to use the following in your blade template:

```
MD5 hashed string with function: {{ md5(name) }}
MD5 hashed string with filter: {{ name|md5 }}
```

You can also set global variables:

```php
$twig->addGlobal('myGlobal', 'The Value');
```

If you want to use additional Twig functions, you can access the Twig instance.

```php
$twig->twig(); // The Twig environment instance
```

For more Twig directives check out the [original documentation](https://twig.symfony.com/doc/3.x/).
