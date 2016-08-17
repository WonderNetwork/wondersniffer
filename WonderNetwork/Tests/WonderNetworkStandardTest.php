<?php
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
class WonderNetworkStandardTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var TestHelper
	 */
	private $helper;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		if ( empty( $this->helper ) ) {
			$this->helper = new TestHelper();
		}
	}

	/**
	 * TestFiles
	 *
	 * Run simple syntax checks, comparing the phpcs output for the test.
	 * file against an expected output.
	 * @return  array $tests The test string[].
	 */
	public static function testProvider() {
		$tests = [];

		$standard = dirname( __DIR__ );
		$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/files' );
		$iterator = new RecursiveIteratorIterator( $directoryIterator );
		foreach ( $iterator as $dir ) {
			if ( $dir->isDir() ) {
				continue;
			}

			$file = $dir->getPathname();
			if ( substr( $file, -4 ) !== '.php' ) {
				continue;
			}
			$tests[] = [
				$file,
				$standard,
				"$file.expect"
			];
		}
		return $tests;
	}

	/**
	 * _testFile
	 *
	 * @dataProvider testProvider
	 *
	 * @param string $file The path string of file.
	 * @param string $standard The standard string.
	 * @param boolean $expectedOutputFile The path of expected file.
	 * @return void
	 */
	public function testFile( $file, $standard, $expectedOutputFile ) {
		$outputStr = $this->prepareOutput( $this->helper->runPhpCs( $file, $standard ) );
		$expect = $this->prepareOutput( file_get_contents( $expectedOutputFile ) );
		$this->assertEquals( $expect, $outputStr );
	}

	/**
	 * strip down the output to only the warnings
	 *
	 * @param string $outputStr PHPCS output.
	 * @return string $outputStr PHPCS output.
	 */
	private function prepareOutput( $outputStr ) {
		if ( $outputStr ) {
			$outputLines = array_filter(explode( "\n", $outputStr ), function ($line) {
				return preg_match('/^\s+\d*\s+\|\s+(ERROR)?\s+\| .*$/', $line);
			});
//			$outputLines = $this->stripTwoDashLines( $outputLines, true );
//			$outputLines = $this->stripTwoDashLines( $outputLines, false );
			$outputStr = implode( "\n", $outputLines );
		}

		return $outputStr;
	}

	/**
	 * @param string[] $lines The array of lines.
	 * @param boolean $front When true strip from the front of array. Otherwise the end.
	 * @return string[] $lines The processed array of lines.
	 */
	private function stripTwoDashLines( array $lines, $front = true ) {
		$dashLines = 0;
		while ( $lines && $dashLines < 2 ) {
			$line = $front ? array_shift( $lines ) : array_pop( $lines );
			if ( strlen( $line ) > 0 && $line[0] === '-' ) {
				$dashLines++;
			}
		}

		return $lines;
	}

}
