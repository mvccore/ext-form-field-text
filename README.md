# MvcCore - Extension - Form - Field - Text

[![Latest Stable Version](https://img.shields.io/badge/Stable-v5.1.15-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-form-field-text/releases)
[![License](https://img.shields.io/badge/License-BSD%203-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.4-brightgreen.svg?style=plastic)

MvcCore form extension with input field types text, email, password, search, tel, url and textarea field.

## Installation
```shell
composer require mvccore/ext-form-field-text
```

## Fields And Default Validators
- `input:text`, `input:search`
	- `SafeString`
		- **configured by default**
		- XSS string protection to safely display submitted value in response, configured by default
	- `MinMaxLength`
		- not configured by default
		- validation by PHP `mb_strlen()` for min. and max.
	- `Pattern`
		- not configured by default
		- validation by PHP `preg_match()`
- `input:password` (extended from `input:text`)
	- `Password`
		- not configured by default
		- validation by configurable password strength rules
	- `SafeString`, `MinMaxLength`, `Pattern` - not configured by default, ...description above
- `input:email` (extended from `input:text`)
	- `Email`
		- **configured by default**
		- single/multiple email form validation by PHP `filter_var($rawValue, FILTER_VALIDATE_EMAIL);`
	- `SafeString`, `MinMaxLength`, `Pattern` - not configured by default, ...description above
- `input:tel` (extended from `input:text`)
	- `Tel`
		- **configured by default**
		- validation for not allowed chars in phone number, **no validation for international phone number form**
	- `SafeString`, `MinMaxLength`, `Pattern` - not configured by default, ...description above
- `input:url` (extended from `input:text`)
	- `Url`
		- **configured by default**
		- url validation by regular expression, SSRF save value, optional DNS validation and many other options
	- `SafeString`, `MinMaxLength`, `Pattern` - not configured by default, ...description above
- `textarea`
	- `SafeString` - **configured by default**
	- `MinMaxLength`, `Pattern` - not configured by default, ...description above

## Features
- always server side checked attributes `required`, `disabled` and `readonly`
- all HTML5 specific and global atributes (by [Mozilla Development Network Docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference))
- every field has it's build-in specific validator described above
- every build-in validator adds form error (when necessary) into session
  and than all errors are displayed/rendered and cleared from session on error page, 
  where user is redirected after submit
- any field is possible to render naturally or with custom template for specific field class/instance
- very extensible field classes - every field has public template methods:
	- `SetForm()`		- called immediatelly after field instance is added into form instance
	- `PreDispatch()`	- called immediatelly before any field instance rendering type
	- `Render()`		- called on every instance in form instance rendering process
		- submethods: `RenderNaturally()`, `RenderTemplate()`, `RenderControl()`, `RenderLabel()` ...
	- `Submit()`		- called on every instance when form is submitted

## Examples
- [**Example - CD Collection (mvccore/example-cdcol)**](https://github.com/mvccore/example-cdcol)
- [**Application - Questionnaires (mvccore/app-questionnaires)**](https://github.com/mvccore/app-questionnaires)

## Basic Example

```php
$form = (new \MvcCore\Ext\Form($controller))->SetId('demo');
...
$username = new \MvcCore\Ext\Forms\Fields\Text();
$username
	->SetName('username')
	->SetPlaceHolder('User');
$password = new \MvcCore\Ext\Forms\Fields\Password([
	'name'			=> 'password',
	'placeHolder'	=> 'Password',
	'validators'	=> ['Password'],
]);
...
$form->AddFields($username, $password);

```
