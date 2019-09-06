# ESSE PROJETO É APENAS PARA APLICAÇÃO DE CONCEITO E APRENDIZADO, NÃO É PARA SER USADO EM PRODUÇÃO, PELO MENOS NÃO AINDA

# RESPECT ANNOTATIONS

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Lendo o [Developer Roadmap][dev-roadmap] um dos passos dizia que uma boa forma de aprender é criar e distribuir um pacote em algum repositorio 
de pacotes da liguagem que você está aprendendo, mas como nunca achei que tivesse algo bom o suficiente para disponibilizar. Então em um dos 
projetos em que trabalho houve a demanda de usar os validadores do [Respect][respect-validation] em entidades do [Doctrine][doctrine], após 
fazer isso pareceu uma boa ideia disponibilizar isso como pacote.

## Install

Via Composer

``` bash
$ composer require andresmeireles/respectannotation
```

## Usage

Nas entidades com propriedades publicas:

``` php
<?php declare(strict_types = 1);

[..]
use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class EntityX
{
    /**
    * @Respect(rules={"noBlank"})
    * Outras anotações do DOCTRINE
    */
    public $name
}


```

Nas entidades com propriedades `private` ou `protected`. É nescessário um getter para obter o valor da variavel para a validação:

``` php
<?php declare(strict_types = 1);

[..]
use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class EntityX
{
    /**
    * @Respect(rules={"noBlank"})
    * Outras anotações do DOCTRINE
    */
    private $name

    public getName()
    {
        return $this->name;
    }
}


```

Ja tendo as anotações em seus devidos lugares, para validar e retornar erros crie uma instancia de `RespectValidationAnnotation` e como parametro envie um objeto que contenha anotaçoes validas.

```
    $entity = new EntityX();
    $entity->setName('andre');
    $validator = new RespectValidationAnnotation()
    $validator->executeClassValidation($entity)
```

### Optional & Not Validators

Para usar os validadores optional & not são nescessários criar chaves com suas respectivas regras.

#### Optival parameter

``` php

<?php declare(strict_types = 1);

[..]
use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class EntityX
{
    /**
    * @Respect(optrules={"noWhitespace"})
    * Outras anotações do DOCTRINE
    */
    private $name

    public getName()
    {
        return $this->name;
    }
}

```

#### Not parameter

``` php

<?php declare(strict_types = 1);

[..]
use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class EntityX
{
    /**
    * @Respect(notrules={"noWhitespace"})
    * Outras anotações do DOCTRINE
    */
    private $name

    public getName()
    {
        return $this->name;
    }
}

```

O processo de validar as entidades continua o mesmo.

## Testing

``` bash
$ composer test
```

## Limitações

- Ainda não é possivel utilizar alguns tipos de validadores mais complexos como 
sf ou zend.
- Não é possivel usar os validadores optional e not em conunto na mesma chave. i.e `not(optional("noWhitespace"))`

## Contributing

TBD
<!-- Please see [CONTRIBUTING](CONTRIBUTING.md) for details. -->

## Code of Conduct

[Code of conduct](CODE_OF_CONDUCT.md).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/andresmeireles/respectannotation.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://travis-ci.org/andresmeireles/respectannotation.svg?branch=master
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/andresmeireles/respectannotation.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/andresmeireles/respectannotation.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/andresmeireles/respectannotation.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/andresmeireles/respectannotation
[link-travis]: https://travis-ci.org/andresmeireles/respectannotation.svg?branch=master
[link-scrutinizer]: https://scrutinizer-ci.com/g/andresmeireles/respectannotation/code-structure/master
[link-code-quality]: https://scrutinizer-ci.com/g/andresmeireles/respectannotation/<Paste>
[link-downloads]: https://packagist.org/packages/andresmeireles/respectannotation
[link-author]: https://andresmeireles.github.io/
[link-contributors]: ../../contributors

[dev-roadmap]: https://github.com/kamranahmedse/developer-roadmap
[respect-validation]: https://github.com/Respect/Validation
[doctrine]: https://www.doctrine-project.org/
