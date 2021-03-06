# WP Settings Page Fields

A package to render form fields in WordPress settings pages.

You might need a settings page with a form and its fields and you have to render the fields every time you create a custom plugin.

Maybe, you already have some snippets that generates the form fields for you, otherwise this package is for you.

## Installation

You can install the package via composer:

```shell
composer require roelmagdaleno/wp-settings-page-fields
```

## Usage

Every supported form element extends the `Roel\WP\Settings\Element` class and must be instantiated with the same constructor:

```php
<?php

$element = new Element( $id, $settings, $option_name );
```

These are the supported parameters:

- `$id`: The form element id. Use the `id()` method to get the id.
- `$settings`: The form element settings (Some form elements have specific settings).
- `$option_name`: The database option name where the value is stored.

### WordPress

To register and render form elements in your settings page with this package, you can use:

```php
<?php

use Roel\WP\Settings\Elements\{
    Checkbox,
    Text,
};

$settings = array(
    new Checkbox( 'allow-user', array(
        'label'       => 'Allow user?',
        'description' => 'Allow user to do current action.',
    ), 'rmr_settings' ),
    new Text( 'api-key', array(
        'label'       => 'API Key',
        'description' => 'Insert the API Key.',
    ), 'rmr_settings' ),
);

foreach ( $settings as $setting ) {
    add_settings_field(
        $setting->id(),
        $setting->label(),
        array( $setting, 'print' ),
        'your-page'
    );
}
```

Remember to add the `array( $setting, 'print' )` as a callback to render the setting field. The `$setting` variable is an `Roel\WP\Settings\Element` instance.

### Render
This is how you can create a form element instance and render the HTML:

#### Using the `render` method

```php
<?php

use Roel\WP\Settings\Elements\Text;

$settings = array(
    'label'       => 'API Key',
    'description' => 'Insert your API Key in this form field.',
);

$text = new Text( 'api-key', $settings, 'rmr_settings' );

echo $text->render();
```

#### Using the `print` method

```php
<?php

use Roel\WP\Settings\Elements\Text;

$settings = array(
    'label'       => 'API Key',
    'description' => 'Insert your API Key in this form field.',
);

$text = new Text( 'api-key', $settings, 'rmr_settings' );

$text->print();
```

#### Using `echo` directly to the instantiated class

```php
<?php

use Roel\WP\Settings\Elements\Text;

$settings = array(
    'label'       => 'API Key',
    'description' => 'Insert your API Key in this form field.',
);

$text = new Text( 'api-key', $settings, 'rmr_settings' );

echo $text;
```

### Attributes

#### `name` attribute

Every form element includes a `name` attribute. This attribute will be used to assign the current element value to the `$_POST` variable, so you can manage that value like insert it into the database.

The `name` attribute value is generated by using the `option_name` and `id`. So, in the next example:

```php
<?php

use Roel\WP\Settings\Elements\Text;

$settings = array(
    'label'       => 'API Key',
    'description' => 'Insert your API Key in this form field.',
);

$text = new Text( 'api-key', $settings, 'rmr_settings' );

echo $text->render();
```

The `name` attribute would be:

```html
<input type="text" name="rmr_settings[api-key]" />
```

Then, in PHP, you would access the value of that input like this:

```php
$_POST['rmr_settings'] = array(
    'api-key' => 'my-api-key',
);
```

#### HTML attributes

You can add HTML attributes to your form elements:

```php
<?php

use Roel\WP\Settings\Elements\Text;

$settings = array(
    'label'       => 'API Key',
    'description' => 'Insert your API Key in this form field.',
    'attributes'  => array(
        'data-value'  => 'value',
        'data-custom' => 'custom',
        'oninput'     => 'myFunction(\'myValue'\)'
    ),
);

$text = new Text( 'api-key', $settings, 'rmr_settings' );

echo $text->render();
```

### Group

Look at this example:

```php
<?php

use Roel\WP\Settings\Elements\{
    Checkbox,
    Text,
};

$settings = array(
    new Checkbox( 'allow-user', array(
        'label'       => 'Allow user?',
        'description' => 'Allow user to do current action.',
    ), 'rmr_settings' ),
    new Checkbox( 'allow-admin', array(
        'label'       => 'Allow admin?',
        'description' => 'Allow admin to do current action.',
    ), 'rmr_settings' ),
    new Text( 'api-key', array(
        'label'       => 'API Key',
        'description' => 'Insert the API Key.',
    ), 'rmr_settings' ),
);

foreach ( $settings as $setting ) {
    add_settings_field(
        $setting->id(),
        $setting->label(),
        array( $setting, 'print' ),
        'your-page'
    );
}
```

You see how the `rmr_settings` option name is repetitive?  Now imagine to add more than 10 settings in the same page. For this kind of cases, you can use the `Roel\WP\Settings\Group` class.

The instance accepts two parameters:

- `$elements` (array): The elements to group.
- `$option_name` (string): The option name for each element.

The passed option name will be set to every passed form element.

```php
<?php

use Roel\WP\Settings\Group;
use Roel\WP\Settings\Elements\{
    Checkbox,
    Text,
};

$elements = array(
    new Checkbox( 'allow-user', array(
        'label'       => 'Allow user?',
        'description' => 'Allow user to do current action.',
    ) ),
    new Checkbox( 'allow-admin', array(
        'label'       => 'Allow admin?',
        'description' => 'Allow admin to do current action.',
    ) ),
    new Text( 'api-key', array(
        'label'       => 'API Key',
        'description' => 'Insert the API Key.',
    ) ),
);

$group = new Group( $elements, 'rmr_settings' );

foreach ( $group->elements() as $element ) {
    add_settings_field(
        $element->id(),
        $element->label(),
        array( $element, 'print' ),
        'your-page'
    );
}
```

After declaring the `Group` class with the required parameters, you can get the elements with `elements()` method, so you can loop and render them.

## Settings

Check the available settings and more in the [Wiki](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki) section.

## Filters

Every form element has two filters to change the HTML output:

- Change the HTML output for all registered elements.
- Change the HTML output for a specific element.

Check the filters for all form elements and more in the [Wiki](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki) section.

## Form Elements

These are the supported form elements so far:

- [Checkbox](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki/Checkbox)
- [Hidden](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki/Hidden)
- [Number](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki/Number)
- [Radio](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki/Radio)
- [Select](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki/Select)
- [Text](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki/Text)
- [TextArea](https://github.com/roelmagdaleno/wp-settings-page-fields/wiki/TextArea)
