# gravityforms-regex-validation
A WordPress plugin that adds regular expression validation options to Gravity Forms' single line text input.

Use regular expressions to validate data entered in the single line text input type.

## Usage

1. Add your single line text input.
2. On the Advanced field settings tab, check "Use Regular Expression Validation"
3. Enter your RegEx pattern in the "RegEx Pattern" field.
4. Optionally, enter a custom validation message.

### Example

* RegEx Pattern: ^1[0-9]{1}$
* Validation Message: Please enter a number between 10-19 (inclusive).

## Installation
Just download the ZIP for the latest release from this repository and install as normal via WordPress.

Note: this plugin optionally can be updated using the [Github Updater plugin](https://github.com/afragen/github-updater).
