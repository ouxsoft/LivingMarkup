<p align="center"><img src="https://github.com/ouxsoft/LivingMarkup/raw/master/assets/images/logo/434x100.jpg" width="400"></p>

<p align="center">
    <a href="https://app.codacy.com/gh/ouxsoft/LivingMarkup?utm_source=github.com&utm_medium=referral&utm_content=ouxsoft/LivingMarkup&utm_campaign=Badge_Grade_Dashboard"><img alt="Codacy grade" src="https://img.shields.io/codacy/grade/86210d48e2ca45e497be865ace8a4029"></a>
    <a href="https://codecov.io/gh/ouxsoft/LivingMarkup"> <img alt="Codecov" src="https://img.shields.io/codecov/c/github/ouxsoft/livingmarkup"> </a> 
    <a href="https://travis-ci.com/github/ouxsoft/LivingMarkup"><img src="https://api.travis-ci.com/ouxsoft/LivingMarkup.svg?branch=master&status=passed" alt="Build Status"></a>
    <a href="https://livingmarkup.readthedocs.io/en/latest/?badge=latest"><img src="https://readthedocs.org/projects/livingmarkup/badge/?version=latest" alt="Documentation Status"></a> 
</p>

<p align="center">
    <a href="https://packagist.org/packages/ouxsoft/livingmarkup"><img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/ouxsoft/livingmarkup"></a> 
    <a href="#tada-php-support" title="PHP Versions Supported"><img alt="PHP Versions Supported" src="https://img.shields.io/badge/php-7.3%20to%207.4-777bb3.svg?logo=php&logoColor=white&labelColor=555555"></a>  
    <a href="https://github.com/ouxsoft/livingmarkup/blob/master/LICENSE" title="license"><img alt="LICENSE" src="https://img.shields.io/badge/license-MIT-428f7e.svg?logo=open%20source%20initiative&logoColor=white&labelColor=555555"></a>
    <a href="https://packagist.org/packages/ouxsoft/livingmarkup"><img src="https://poser.pugx.org/ouxsoft/livingmarkup/downloads" alt="Total Downloads"></a>
</p>

## About
A server-side markup abstraction layer based on the [LHTML](https://github.com/ouxsoft/LHTML) standard programmed in PHP. 

## Usage
Start by creating an LHTML Element.
```php
namespace Partial;

class Alert extends LivingMarkup\Element\AbstractElement 
{
    public function onRender()
    {
        switch($this->getArgByName('type')){
            case 'success':
                $class = 'alert-success';
                break;
            case 'warning':
                $class = 'alert-warning';
                break;
            default:
                $class = 'alert-info';
                break;
        }
        return "<div class=\"alert {$class}\" role=\"alert\">{$this->innerText()}</div>";
    }
}
```

Add `Partial` namespace to a LHTML processor and `onRendor` to routine method calls.
```php
use LivingMarkup\Factory\ProcessorFactory;

$processor = ProcessorFactory::getInstance();

$processor->addElement('Partial', '//partial', 'Partial\{name}');

$processor->addRoutine('onRender','Execute for render', 'RETURN_CALL');

echo $processor->parseString('<html lang="en">
    <partial name="Alert" type="success">This is a success alert.</partial>
</html>');
```

Outputs processed HTML5 page. 

```html5
<!doctype html>
<html lang="en">
    <div class="alert success" role="alert">
        This is a success alert
    </div>
</html>
```
This markup abstraction layer make CSS framework changes, in this example Bootstrap, easy.

## Installation

### Via Composer
LivingMarkup is available on [Packagist](https://packagist.org/packages/ouxsoft/livingMarkup).

Install with [Composer](https://getcomposer.org/download/):
```shell script
composer require ouxsoft/livingmarkup
```

### Via Git
Install with [Git](https://git-scm.com/):
```shell script
git clone git@github.com:ouxsoft/LivingMarkup.git
```

### LHTML Elements
LivingMarkup comes packaged with only LHTML test Elements. For core Elements, see:
 * [Hoopless](https://github.com/ouxsoft/hoopless)

## Documentation
Check our docs for more info at [livingmarkup.readthedocs.io](https://livingmarkup.readthedocs.io).

## Creators

***Matthew Heroux***

  * [https://github.com/hxtree](https://github.com/hxtree)

## Acknowledgement

Thanks to Andy Beak for providing code review. Thanks to Bob Crowley for providing Project Management advising. Thanks to Aswin Vijayakumar for their useful comments. All of have led to changes to this implementation.
