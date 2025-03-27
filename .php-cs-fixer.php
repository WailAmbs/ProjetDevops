<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'strict_param' => true,
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_trim' => true,
        'phpdoc_scalar' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => true,
        'phpdoc_var_without_name' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_summary' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_tag_type' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_order' => true,
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_align' => true,
    ])
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder($finder); 