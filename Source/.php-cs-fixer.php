<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('Migrations')
    ->in(__DIR__.'/src/')
    ->in(__DIR__.'/tests/')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'self_accessor' => false,
//        'declare_strict_types' => true,
        'no_superfluous_phpdoc_tags' => false,
        'no_empty_phpdoc' => false,
        'visibility_required' => false,
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
    ])
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/var/.php-cs-fixer.cache')
;
