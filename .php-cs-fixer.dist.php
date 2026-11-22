<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$config = new Config();
$finder = new Finder();

// Root project PHP only — compiled Blade in storage/framework/views bloated the
// finder (~900 files) and made parallel workers hit the 120s child timeout.
$finder
  ->in(__DIR__)
  ->exclude([
    'bootstrap/cache',
    'node_modules',
    'storage',
    'tmp',
    'vendor',
  ]);

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
    ],
  ])
  ->setFinder($finder);
