# MvcCore - Extension - Form - Field - Text

[![Latest Stable Version](https://img.shields.io/badge/Stable-v4.3.1-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-form-field-text/releases)
[![License](https://img.shields.io/badge/Licence-BSD-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.3-brightgreen.svg?style=plastic)

MvcCore form extension with input field types text, email, password, search, tel, url and textarea field.

## Installation
```shell
composer require mvccore/ext-form-field-text
```

## Features
- always server side checked attributes `required`, `disabled` and `readonly`
- all HTML5 specific and global atributes (by [Mozilla Development Network Docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference))
- every field has it's build-in specific validator described bellow
- every build-in validator adds form error into session displayed on error page, where user is redirected after submit
- any field is possible to render naturally or with custom template for specific field class/instance
- very extensible field classes - every field has public template methods:
	- `SetForm()`		- called immediatelly after field instance is added into form instance
	- `PreDispatch()`	- called immediatelly before any field instance rendering type
	- `Render()`		- called on every instance in form instance rendering process
		- submethods: `RenderNaturally()`, `RenderTemplate()`, `RenderControl()`, `RenderLabel()` ...
	- `Submit()`		- called on every instance when form is submitted

### Fields

## Examples
- [**Example - CD Collection (mvccore/example-cdcol)**](https://github.com/mvccore/example-cdcol)
- [**Application - Questionnaires (mvccore/app-questionnaires)**](https://github.com/mvccore/app-questionnaires)

## Basic Example
