<?php

use PHPUnit\Framework\TestCase;

/**
 * This file was copied from CakePhps codesniffer tests before being modified
 * File: http://git.io/vkirb
 * From repository: https://github.com/cakephp/cakephp-codesniffer
 *
 * @license MIT
 * CakePHP(tm) : The Rapid Development PHP Framework (http://cakephp.org)
 * Copyright (c) 2005-2013, Cake Software Foundation, Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * @author Addshore
 * Modifications
 *  - Rename appropriately
 *  - Adapt $this->helper->runPhpCs call to pass second parameter $standard
 *
 * @author WonderNetwork
 * Modifications
 *  - Remove fixers and helper
 */
class WonderNetworkStandardTest extends TestCase {

	/**
	 * @var TestHelper
	 */
	private TestHelper $helper;

	/**
	 * @return void
	 */
	public function setUp(): void {
        $this->helper = new TestHelper();
	}

	/**
	 * TestFiles
	 *
	 * Run simple syntax checks, comparing the phpcs output for the test.
	 * file against an expected output.
	 * @return  array $tests The test string[].
	 */
	public static function dataProvider(): array {
        return array_map(
            static fn (string $filename) => [$filename],
            glob(__DIR__.'/files/**/*.php'),
        );
	}

    /**
     * @dataProvider dataProvider
     */
	public function testFile( string $file ): void {
        $standard = 'WonderNetwork';
        $expectedOutputFile = sprintf('%s.expect', $file);
        $outputStr = $this->helper->runPhpCs( $file, $standard );
        $expect = file_get_contents( $expectedOutputFile );
		self::assertEquals( $expect, $outputStr );
	}
}
