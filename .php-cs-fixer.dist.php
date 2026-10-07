<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$config = new Config();
$finder = new Finder();

// 💡 root folder to check
$finder->in(__DIR__);

return $config
  ->setIndent('  ') // 2 espaços
  ->setRiskyAllowed(false)
  ->setRules([
    '@PSR2'                              => true,
    'method_chaining_indentation'        => true,
    'array_indentation'                  => true,
    'phpdoc_indent'                      => true,
    'binary_operator_spaces'             => [
      'operators' => [
        '=>' => 'align_single_space_minimal_by_scope',
      ],
    ],
    'no_blank_lines_after_class_opening' => true,
    'braces_position'                    => [
      'functions_opening_brace' => 'same_line',
      'classes_opening_brace'   => 'same_line',
    ]
  ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
  ->setFinder($finder);
