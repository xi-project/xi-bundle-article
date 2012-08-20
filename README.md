# Article functionality for Symfony2

Article bundle provides support for articles inside a Symfony 2 project. Articles are managed by tags, which are provided by Xi tag bundle.


## Dependencies

xi-bundle-tag
* https://github.com/xi-project/xi-bundle-tag

xi-bundle-selector
* https://github.com/xi-project/xi-bundle-selector

## Installing

### deps -file
```
[XiSelectorBundle]
    git=http://github.com/xi-project/xi-bundle-selector.git
    target=/bundles/Xi/Bundle/SelectorBundle

[XiTagBundle]
    git=http://github.com/xi-project/xi-bundle-tag.git
    target=/bundles/Xi/Bundle/TagBundle

[XiArticleBundle]
    git=http://github.com/xi-project/xi-bundle-article.git
    target=/bundles/Xi/Bundle/ArticleBundle
```

### autoload.php file
```php
<?php
'Xi\\Bundle'       => __DIR__.'/../vendor/bundles',
?>
```

### appKernel.php -file
```php
<?php
            new Xi\Bundle\SelectorBundle\XiSelectorBundle(),
            new Xi\Bundle\TagBundle\XiTagBundle(),
            new Xi\Bundle\ArticleBundle\XiArticleBundle(),
 ?>
```

### routing.yml -file
```yml
XiTagBundle:
    resource: "@XiTagBundle/Resources/config/routing.yml"
    prefix:   /

XiArticleBundle:
    resource: "@XiArticleBundle/Resources/config/routing.yml"
    prefix:   /
```

### Styling

there is a search result render template, for integration with xi search bundle, be sure to style the respective admin buttons (search-result-edit, search-result-delete, search-result-change) to actually see them