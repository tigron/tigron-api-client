<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineGlobalConstants;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\EmptyStatementSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\NoSilencedErrorsSniff;

return [
	'preset' => 'default',

	'remove' => [
		\PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\DisallowTabIndentSniff::class,
		\SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff::class,
		\SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff::class,
		\SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff::class, // disabled until min-support is PHP 8.0 (use union types)
//		\PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ValidClassNameSniff::class, // find a way to validate our class names instead
		\PHP_CodeSniffer\Standards\PSR1\Sniffs\Methods\CamelCapsMethodNameSniff::class, // find a way to validate our method names


		// should be enabled, just annoying for now
		PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer::class,
	],

// require ||, &&
//• [Style] Lower case keyword:
//  config/Config.php:86: PHP keywords must be lowercase; expected "or" but found "OR"
//  lib/model/Service/Module.php:71: PHP keywords must be lowercase; expected "and" but found "AND"

	'config' => [
		LineLengthSniff::class => [
			'lineLimit' => 120,
			'absoluteLineLimit' => 120,
			'ignoreComments' => true,
		],

		ForbiddenDefineGlobalConstants::class => [
			'ignore' => [
				'PHP_CODESNIFFER_VERBOSITY',
				'PHP_CODESNIFFER_CBF',
			],
		],

		\PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer::class => [
			'order' => [
				'use_trait',
				'constant_public',
				'constant_protected',
				'constant_private',
				'property_public',
				'property_public_static',
				'property_protected',
				'property_protected_static',
				'property_private',
				'property_private_static',
				'construct',
				'destruct',
				'magic',
				'method_public',
				'method_protected',
				'method_private',
				'method_public_static',
				'method_protected_static',
				'method_private_static',
			],
			'sort_algorithm' => 'none',
		],

		\PhpCsFixer\Fixer\Basic\BracesFixer::class => [
			'indent' => "\t",
			'allow_single_line_closure' => true,
			'position_after_anonymous_constructs' => 'same', // possible values ['same', 'next']
			'position_after_control_structures' => 'same', // possible values ['same', 'next']
			'position_after_functions_and_oop_constructs' => \PhpCsFixer\Fixer\Basic\BracesFixer::LINE_SAME,
		],

		\PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer::class => [
			'multi_line_extends_each_single_line' => false,
			'single_item_single_line' => false,
			'single_line' => true,
		],

// not supported in phpinsights 2.0 and up
//		\ObjectCalisthenics\Sniffs\NamingConventions\ElementNameMinimalLengthSniff::class => [
//			'minLength' => 3,
//			'allowedShortNames' => ['db', 'e', 'i', 'id', 'ip', 'to', 'up'],
//		],

// not supported in phpinsights 2.0 and up
//		\ObjectCalisthenics\Sniffs\Metrics\MaxNestingLevelSniff::class => [
//			'maxNestingLevel' => 4,
//		],
	],

	'requirements' => [
		'min-quality' => 90.0,
		'min-architecture' => 85.0,
		'min-style' => 98.0,
	],
];
