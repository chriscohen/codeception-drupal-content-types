# Drupal Content Type Registry

A [Codeception](http://www.codeception.com) module to provide a set of classes that encapsulate
[Drupal](http://drupal.org) content types. This makes it much easier to quickly test standard Drupal functionality
relating to content types, taking into account how they exist on your site.

It will test many things such as the content types admin page, the 'manage fields' page for each content type, and
provides a useful createNode() method that can be used to quickly create test nodes, where you can provide the test data
using specific values, random values, or a range of values where one is picked at random.

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

## Configuration

None.

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
ContentTypes:
    news:
        entityType:   node
        humanName:    News
        machineName:  news
        fields:
            globals:
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
                skipRoles:
                    - editor
                    - publisher
                testData:
                    - smiley
                    - grumpy
                    - happy
                    - wacky
        submit: "#edit-submit-me-please"
```

### GlobalFields

In the first section, you can define fields that will be used across all of the content types on the site. This is
useful for things like title and body fields, to save you having to redefine the exact same field on every content type.

GlobalFields are keyed by machine name (this should be the same name as the machine name of the Drupal field) and the
values are the same as they would be if they were declared for the fields of a content type (see below).

### ContentTypes

Each content type should be keyed according to its machine name (although this is just a hint as machineName takes care
of the actual naming, so you could give the content type any key you like).

- **entityType** is the machine name of the Drupal entity type. Mostly node but other types can be used. "node" is
  assumed unless something else is specified.
- **humanName** is the way that the content type is named in the UI (and is case-sensitive).
- **machineName** is the way that the content type is named to Drupal, and should match whatever is set in Drupal.
- **fields** is a list of all of the fields on the content type, with their properties.
  - **globals** is a simple list of the "reused" fields on this type. If your content type has a field that simply
    reuses exactly a field from another content type, set it up in GlobalFields (above) and just reference it here. An
    exception would be if it had a slight difference, such as when you set the same field, but you change its label from
    "Foo" to something else. In this case, it wouldn't be able to be a global field.
  - **properties**: fields can have the following properties...
    - **machineName** is the machine name of the field as seen by Drupal. In general these will start with field_ but
      there might be exceptions such as for body fields.
    - **label** is the human name (label) for this field, and should match exactly what is set in the UI, including case
      sensitivity.
    - **type** is the field type as set in the Drupal UI, on the "manage fields" page. Case-sensitive.
    - **selector** is the CSS or XPath selector used to pick out this field's element where it appears on a node create
      or edit page. Note that this is usually optional and if omitted, will be derived from the field name, which is
      usually enough.
    - **widget** is the name of the widget for this field as set in the Drupal UI, on the "manage fields" page. Case-
      sensitive. Some fields don't have widgets so just leave it out. Some fields have widgets on the node edit page,
      but have nothing listed on the "manage fields" page. If this is the case, set the widget here, but use
      **widgetNameVisible** below to indicate it's not visible on "manage fields". In these cases you will have to
      determine the widget for yourself. For example, the node title wigdet is a "Text field" widget even though it
      doesn't say that on the "manage fields" page.
    - **widgetNameVisible** allows you to specify that on the "manage fields" page, this row has nothing in the "widget"
      column. This applies to things like title fields etc.
    - **required** can be set to "true" if the field is required. If it's not, just leave this out altogether.
    - **pre** can be used to specify an XPath selector for an element that should be clicked before the field is filled.
      If this is set, this element will be clicked and then the field will be filled. If not set, nothing will be
      clicked before the field is filled. This can be useful for elements that are hidden behind vertical tabs and would
      not be visible to the user unless the vertical tab is selected first.
    - **skippedRoles** is an array of role names that will not be able to see this field and should not attempt to fill
      it in.
    - **testData** should contain the dummy data used to test this field. Each field can be instructed to fill itself
      with test data and this is the data that will be used. Note that unless the field is mandatory and Drupal provides
      no default value for the field, testData can be left out of the yaml. If an array of values if provided, one can
      be chosen at random by the Field class. Special values can also be used here. See 'special values' below.
- **extras** is a list of all extras (elements to interact with on the node edit form) that aren't fields in their own
  right. See below for more.
- **submit** is the CSS or XPath used to find the submit button on the node add or edit form for this content type. The
  Drupal default is "#edit-submit" and this can be omitted if you're using the default on your site.

#### Special values

You can use a special value that will be substituted each time the field is created for testing. This is useful, for
example, if you want to insert a random value. All special values begin with the identifier **special::** and then are
followed by the type of special value. The types are listed below:

- **randomText** uses eight random alphanumeric characters.

Example:

```yaml
testData: "special::randomText"
```

## Standard default required fields

Each entity type has a set of standard fields that always appear on that entity type. For example, there is always a
"title" field on the node entity and you have to have it.

Entity types are already aware of what their default fields are, because they are expressed in the
EntityType::getRequiredFields() method which is provided by each EntityType object.

This means that as long as it's a required field, you don't need to mention it in contentTypes.yml because they are
already defined. You can mention them in contentTypes.yml if you want to, and those that you mention in there will
override the defaults.

### Implementing your own standard default required fields

If you have a situation where you have a custom Drupal entity and it has its own required fields, you should define your
own EntityType subclass and implement the EntityType::getRequiredFields() method to define the required fields there.
Anything using your custom entity type in contentTypes.yml will automatically pick up these and look for them.

## Specific widget types

### AddressWidget

Set the selector for this widget as the first part of the selector for each individual address field inside the widget.
For example, if the machine name is **field_address** then you would set the selector as follows:

```yaml
selector:   "#edit-field-address-und-0"
```

You will need to define the individual elements that go into the address widget since these can be defined differently
on a per-widget basis.

You can do this with the **elements** key. The array key is the label for each of the fields and the value is the end
of the selector used, which is joined to the selector described above.

```yaml
elements:
    Company:        "-organisation"
    Address 1:      "-thoroughfare"
    Address 2:      "-locality"
    "Town/City":    "-locality"
```

If you need to set up testData for this field, you will need to put a wrapper around each group of test elements as
follows:

```yaml
testData:
    address1:
        Company:        Test location
        Address 1:      Test location thoroughfare
        Address 2:      Test location locality
        "Town/City":    Test location city
        County:         Test location county
        Postcode:       Test location postal code
    # Then, if necessary:
    address2:
        Company:        Test location 2
        Address 1:      Test location thoroughfare 2
        Address 2:      Test location locality 2
        "Town/City":    Test location city 2
        County:         Test location county 2
        Postcode:       Test location postal code 2
```

### CheckboxesWidget

Remember that you only need to set up testData if the value of each or any checkbox needs to be altered.

If you need to set up testData for a CheckboxesWidget, make sure you put each group of boxes in its own wrapper, as
follows:

```yaml
testData:
    values1:
        Grapefruit: true
        Melon:      false
        Avocado:    true
    values2:
        "Big Hairy Kiwi Fruit/Kiwi Fruits": true
```

### WysiwygWidget

Use this for WYSIWYG fields. The selector should be the ID of the text area element for this field, but without the
"-value" part at the end. For example, for a body field, you might use:

```yaml
selector: "#edit-body-und-0"
```

Currently the widget will switch to plain text format to enter data.

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

## Entity types

Mostly you will want to add fields and types for known entities such as users, taxonomy terms and nodes. But sometimes
you will need to add further entity types, such as for custom entities you have defined. You will need to create a class
that extends Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes\EntityType and implements
Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes\EntityTypeInterface and then you can define the type name and
also the page on which the "manage fields" is done for this entity type.

### Adding new entity types

If your site has a custom entity type that is not managed within this module's collection of entity types, you can
create a custom class for it and specify it using the yaml config:

```yaml
EntityTypes:
    banana: "Codeception\\MyTestSuite\\EntityTypes\\Banana"
```

Note that you will need to fully namespace your custom class and use the double backslash notation as shown in the
example.

Your custom class should extend EntityType and implement EntityTypeInterface. Mostly you can just copy an existing
entity type subclass and adopt it to suit your needs:

```php
<?php
/**
 * @file
 * Represents the banana entity type.
 */

namespace Codeception\MyTestSuite\EntityTypes;

use Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes\EntityType;
use Codeception\Module\Drupal\ContentTypeRegistry\EntityTypes\EntityTypeInterface;

class Banana extends EntityType implements EntityTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getManageFieldsUrl($bundle = '')
    {
        return 'admin/structure/fruit-types/manage/' . $this->getEntityType() . '/fields';
    }
}
```

Don't forget that you will need to make sure this class is loaded within the _bootstrap.php of your product using
Codeception's autoloading system or by loading it manually with require_once or something.

## Extras

Sometimes, you will want to simulate the user clicking things on the node edit form that are not actually fields. This
is where extras come in. You can do things like set the sticky status or the publication status of a node in this way.
Here is an example:

```yaml
ContentTypes:
    news:
        entityType:   node
        humanName:    News
        machineName:  news
        fields:
            globals:
                - body
            field_image:
                machineName:    field_image
                label:          Image
                type:           Image
                selector:       "#edit-field-image"
                widget:         Media file selector
                required:       true
                testData:       "image1.png"
        extras:
            published:
                machineName:    published
                label:          Published
                type:           List (text)
                selector:       "#edit-published"
                widget:         Select list
                testData:       Published
        submit: "#edit-submit-me-please"
```

As you can see, these slot alongside fields. You will normally have to set a selector manually for these because the
naming conventions that apply to fields do not apply here. You can still use the widget property to tell this module
what type of form widget is being used.

## Node creation/deletion
During createNode/deleteNode the success status is checked by looking for the standard Drupal messages in
an element e.g. `.messages`

Some themes have different selectors or may not display those messages at all. If that is the case, you can
implement `seeCreateNodeWasSuccessful()` and/or `seeDeleteNodeWasSuccessful()` in your suite helper.

e.g.

```php

class AcceptanceHelper extends \Codeception\Module
{
    /**
     * Check a node creation was successful.
     *
     * This overrides the default since the css selectors are different in
     * this site's theme.
     *
     * @see DrupalContentTypeRegistry::seeCreateNodeWasSuccessful()
     *
     * @param WebInterface $I
     *   A reference to the Actor being used.
     * @param string $msg
     *   The success message that should be displayed by Drupal.
     * @param int $nid
     *   The created nid.
     */
    public function seeCreateNodeWasSuccessful($I, $msg, $nid)
    {
        $I->see($msg, ".messages");
        $I->dontSee(" ", ".messages.error");
    }

    /**
     * Check a node deletion was successful.
     *
     * This overrides the default since this site redirects to the
     * homepage on node deletion and does not show a message. We
     * therefore do a check by editing the node and make sure it's
     * not found.
     *
     * @see DrupalContentTypeRegistry::seeDeleteNodeWasSuccessful()
     *
     * @param AuthenticatedSteps $I
     *   A reference to the Actor being used.
     * @param int $nid
     *   The deleted nid.
     */
    public function seeDeleteNodeWasSuccessful($I, $nid)
    {
        $I->amOnPage(NodePage::route($nid, true));
        $I->see("we can't find this page", "h1");
    }
}

```
