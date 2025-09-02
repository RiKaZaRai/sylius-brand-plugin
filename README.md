# Sylius Brand Plugin by Rika

Plugin de gestion des marques pour Sylius 2.1

## Installation

### 1. Installer via Composer

```bash
composer require rika/sylius-brand-plugin
```

### 2. Activer le plugin

```php
// config/bundles.php
return [
    // ...
    Rika\SyliusBrandPlugin\RikaSyliusBrandPlugin::class => ['all' => true],
];
```

### 3. Importer la configuration

```yaml
# config/packages/rika_sylius_brand.yaml
imports:
    - { resource: "@RikaSyliusBrandPlugin/Resources/config/services.yaml" }
    - { resource: "@RikaSyliusBrandPlugin/Resources/config/sylius_resource.yaml" }
    - { resource: "@RikaSyliusBrandPlugin/Resources/config/grids/*.yaml" }
```

### 4. Importer les routes

```yaml
# config/routes/rika_sylius_brand.yaml
rika_sylius_brand_admin:
    resource: "@RikaSyliusBrandPlugin/Resources/config/routing/admin.yaml"
    prefix: /admin

rika_sylius_brand_shop:
    resource: "@RikaSyliusBrandPlugin/Resources/config/routing/shop.yaml"
    prefix: /{_locale}
    requirements:
        _locale: ^[a-z]{2}(?:_[A-Z]{2})?$
```

### 5. Étendre l'entité Product

```php
<?php
// src/Entity/Product/Product.php

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Rika\SyliusBrandPlugin\Entity\ProductBrandAwareInterface;
use Rika\SyliusBrandPlugin\Entity\ProductBrandAwareTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product')]
class Product extends BaseProduct implements ProductBrandAwareInterface
{
    use ProductBrandAwareTrait;
}
```

### 6. Configurer Sylius

```yaml
# config/packages/_sylius.yaml
sylius_product:
    resources:
        product:
            classes:
                model: App\Entity\Product\Product
```

### 7. Mettre à jour la base de données

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

### 8. Vider le cache

```bash
php bin/console cache:clear
```

## Utilisation

- Accès administration : `/admin/brands`
- Pages publiques : `/brands` et `/brands/{slug}`

## Hooks disponibles

- `rika_sylius_brand.hook.brand_pre_create`
- `rika_sylius_brand.hook.brand_post_create`
- `rika_sylius_brand.hook.brand_pre_update`
- `rika_sylius_brand.hook.brand_post_update`

## Licence

MIT

## Auteur

Rika - [https://github.com/rika](https://github.com/rika)
