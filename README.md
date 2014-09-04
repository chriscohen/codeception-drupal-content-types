# Drupal Content Type Registry

[Codeception](http://www.codeception.com) module to provide a set of classes that encapsulate
[Drupal](http://drupal.org) content types.

## Install

Install using composer, as follows:

```javascript
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/chriscohen/codeception-drupal-content-types.git"
        }
    ],
    "require": {
        "codeception/codeception": "2.*",
        "chriscohen/codeception-drupal-content-types": "dev-master",
    },
}
```

Add the module to the suite configuration:

```yaml
modules:
    enabled:
        - DrupalContentTypeRegistry
```

## contentTypes.yml

Put a contentTypes.yml at your test root (unless you want a specific contentTypes.yml for each suite, in which case, see
below).

Here's an example file:

```yaml
GlobalFields:
    body:
        machineName:    body
        label:          Body
        type:           Long text and summary
        selector:       "#edit-body-und-0-value"
        widget:         Text area with a summary
        required:       true
    title:
        machineName:    title
        label:          Title
        type:           Node module element
        selector:       "#edit-title"
ContentTypes:
    news:
        humanName:    News
        machineName:  news
        fields:
            globals:
                - title
                - body
            field_image:
                machineName:    field_image
                label:          Image
                type:           Image
                selector:       "#edit-field-image"
                widget:         Media file selector
                required:       true
                testData:       "image1.png"
            field_icon:
                machineName:    field_icon
                label:          Icon
                type:           Text
                selector:       "#edit-field-icon"
                widget:         Text field
                testData:
                    - smiley
                    - grumpy
                    - happy
                    - wacky
```

### GlobalFields

In the first section, you can define fields that will be used across all of the content types on the site. This is
useful for things like title and body fields, to save you having to redefine the exact same field on every content type.

GlobalFields are keyed by machine name (this should be the same name as the machine name of the Drupal field) and the
values are the same as they would be if they were declared for the fields of a content type (see below).

### ContentTypes

Each content type should be keyed according to its machine name (although this is just a hint as machineName takes care
of the actual naming, so you could give the content type any key you like).

- **humanName** is the way that the content type is named in the UI (and is case-sensitive).
- **machineName** is the way that the content type is named to Drupal, and should match whatever is set in Drupal.
- **fields** is a list of all of the fields on the content type, with their properties.
  - **globals** is a simple list of the "reused" fields on this type. If your content type has a field that simply
    reuses exactly a field from another content type, set it up in GlobalFields (above) and just reference it here. An
    exception would be if it had a slight difference, such as when you set a title, but you change its label from
    "Title" to something else. In this case, it wouldn't be able to be a global field.
  - **properties**: fields can have the following properties...
    - **machineName** is the machine name of the field as seen by Drupal. In general these will start with field_ but
      there might be exceptions such as for title and body fields.
    - **label** is the human name (label) for this field, and should match exactly what is set in the UI, including case
      sensitivity.
    - **type** is the field type as set in the Drupal UI, on the "manage fields" page. Case-sensitive.
    - **selector** is the CSS or XPath selector used to pick out this field's element where it appears on a node create
      or edit page.
    - **widget** is the name of the widget for this field as set in the Druapl UI, on the "manage fields" page. Case-
      sensitive. Some fields don't have widgets (such as title) so just leave it out. There is a list of field types
      that are exempted from having a widget, so the ContentTypeRegistry will be aware of this.
    - **required** can be set to "true" if the field is required. If it's not, just leave this out altogether.
    - **testData** should contain the dummy data used to test this field. Each field can be instructed to fill itself
      with test data and this is the data that will be used. If an array of values if provided, one can be chosen at
      random by the Field class.

## Suite-specific contentTypes.yml

You can put a separate contentTypes.yml in each suite folder if you prefer, and these files can override the main
contentTypes.yml (or just don't create a main one in the test root folder).

If you do this, you will need to add the following into your *suite's* _bootstrap.php:

```php
\Codeception\Module\Drupal\ContentTypeRegistry\SuiteSettings::$suiteName = 'mysuite';
```

The suite name should match the name of the directory in which the suite lives. This is because Codeception has no other
way of knowing what directory to look in when it's trying to find the contentTypes.yml file. It knows the location of
the root tests directory and has a list of the suites it's supposed to run, but can't determine the directory in which
the current suite is running. If some way of doing this within Codeception is developed in the future, this extra step
can be dropped.
