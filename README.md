<p align="center"><img src="https://github.com/hxtree/LivingMarkup/raw/master/assets/images/logo/434x100.jpg" width="400"></p>
 
<p align="center">
A <a href="https://github.com/ouxsoft/LHTML">LHTML processor</a> implementation in PHP.
</p>

<p align="center">
<a href="https://packagist.org/packages/hxtree/livingmarkup"><img src="https://poser.pugx.org/hxtree/livingmarkup/v/stable" alt="Latest Stable Version"></a> 
<a href="https://github.com/hxtree/livingMarkup/actions"><img src="https://github.com/hxtree/livingMarkup/workflows/CI/badge.svg" alt="CI Status"></a>
<a href="https://livingmarkup.readthedocs.io/en/latest/?badge=latest"><img src="https://readthedocs.org/projects/livingmarkup/badge/?version=latest" alt="Documentation Status"></a>
<a href="https://app.codacy.com/manual/hxtree/LivingMarkup?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=hxtree/LivingMarkup&amp;utm_campaign=Badge_Grade_Dashboard"><img src="https://api.codacy.com/project/badge/Grade/bfc76aaebde44a7fa239963e54883755" alt="Codacy Badge"></a>
<a href="https://packagist.org/packages/hxtree/livingmarkup"><img src="https://poser.pugx.org/hxtree/livingmarkup/downloads" alt="Total Downloads"></a> 
</p>

## About
LivingMarkup is a customizable HTML templating engine that processes markup into objects and orchestrates them to build
dynamic markup. 

## Usage
Create a module:
```php
namespace Partial;

class HelloWorld extends LivingMarkup\Module {
    public function onRender(){
        return 'Hello, World!';
    }
}
```
Run through processor
```php
// instantiate processor
$proc = new LivingMarkup\Processor();

// add object to processor
$proc->adObject('Partial', '//partial', 'Partial\{name}');

// automate method call
$proc->addMethod('onRender','Execute for render', 'RETURN_CALL');

// process LHTML string
echo $proc->parseString('<html><partial name="HelloWorld"/></html>');

```

## Installation

### Via Composer
LivingMarkup is available on [Packagist](https://packagist.org/packages/hxtree/livingMarkup).

Install with [Composer](https://getcomposer.org/download/):
```shell script
composer require ouxsoft/livingmarkup
```

Install with [Git](https://git-scm.com/):
```shell script
git clone git@github.com:ouxsoft/LivingMarkup.git
```

## Documentation
Check our docs for more info at [livingmarkup.readthedocs.io](https://livingmarkup.readthedocs.io).

## Contribute

Please refer to [CONTRIBUTING.md](https://github.com/hxtree/LivingMarkup/blob/master/.github/workflows/CONTRIBUTING.md) for 
information on how to contribute to LivingMarkup.

## Acknowledgement

Thanks to Aswin Vijayakumar for their useful comments that have led to changes to this implementation.
