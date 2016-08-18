<?php

/**
 * WonderNetwork_Sniffs_ControlStructures_MultiLineConditionSniff.
 *
 * Ensure multi-line IF conditions are defined correctly.
 *
 * @category  PHP
 * @package   wondersniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Gemma Anible <gemma@wonderproxy.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */
class WonderNetwork_Sniffs_ControlStructures_MultiLineConditionSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = ['PHP'];

    /**
     * The number of spaces code should be indented.
     *
     * @var int
     */
    public $indent = 4;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_IF, T_ELSEIF, T_WHILE];

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_opener']) === false) {
            return;
        }

        $openBracket    = $tokens[$stackPtr]['parenthesis_opener'];
        $closeBracket   = $tokens[$stackPtr]['parenthesis_closer'];

        // We need to work out how far indented the if statement
        // itself is, so we can work out how far to indent conditions.
        $statementIndent = 0;
        for ($i = ($stackPtr - 1); $i >= 0; $i--) {
            if ($tokens[$i]['line'] !== $tokens[$stackPtr]['line']) {
                $i++;
                break;
            }
        }

        if ($i >= 0 && $tokens[$i]['code'] === T_WHITESPACE) {
            $statementIndent = strlen($tokens[$i]['content']);
        }

        $this->processParens($phpcsFile, $tokens, $openBracket, $statementIndent);

        // From here on, we are checking the spacing of the opening and closing
        // braces. If this IF statement does not use braces, we end here.
        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }

        // The opening brace needs to be one space away from the closing parenthesis.
        $openBrace = $tokens[$stackPtr]['scope_opener'];
        $next      = $phpcsFile->findNext(T_WHITESPACE, ($closeBracket + 1), $openBrace, true);
        if ($next !== false) {
            // Probably comments in between tokens, so don't check.
            return;
        }

        if ($tokens[$openBrace]['line'] > $tokens[$closeBracket]['line']) {
            $length = -1;
        } else if ($openBrace === ($closeBracket + 1)) {
            $length = 0;
        } else if ($openBrace === ($closeBracket + 2)
            && $tokens[($closeBracket + 1)]['code'] === T_WHITESPACE
        ) {
            $length = strlen($tokens[($closeBracket + 1)]['content']);
        } else {
            // Confused, so don't check.
            $length = 1;
        }

        if ($length === 1) {
            return;
        }

        $data = array($length);
        $code = 'SpaceBeforeOpenBrace';

        $error = 'There must be a single space between the closing parenthesis and the opening brace of a multi-line IF statement; found ';
        if ($length === -1) {
            $error .= 'newline';
            $code   = 'NewlineBeforeOpenBrace';
        } else {
            $error .= '%s spaces';
        }

        $phpcsFile->addError($error, ($closeBracket + 1), $code, $data);

    }//end process()

    /**
     * @param PHP_CodeSniffer_File $phpcs
     * @param array                $tokens
     * @param int                  $open
     * @param int                  $indent
     */
    public function processParens(PHP_CodeSniffer_File $phpcs, array $tokens, $open, $indent) {
        $stackPtr = $open;
        $close = (int) $tokens[$stackPtr]['parenthesis_closer'];

        $spaceAfterOpen = 0;
        if ($tokens[($open + 1)]['code'] === T_WHITESPACE) {
            if (strpos($tokens[($open + 1)]['content'], $phpcs->eolChar) !== false) {
                $spaceAfterOpen = 'newline';
            } else {
                $spaceAfterOpen = strlen($tokens[($open + 1)]['content']);
            }
        }
        if ($spaceAfterOpen !== 0) {
            $error = 'First condition of a multi-line IF statement must directly follow the opening parenthesis';
            $phpcs->addError($error, ($open + 1), 'SpacingAfterOpenBrace');
        }

        // Each line between the parenthesis should be indented 4 spaces
        // and start with an operator, unless the line is inside a
        // function call, in which case it is ignored.
        $prevLine = $tokens[$open]['line'];
        for ($i = ($open + 1); $i < $close; $i++) {
            if ($tokens[$i]['code'] === T_OPEN_PARENTHESIS) {
                $this->processParens($phpcs, $tokens, $i, $indent + $this->indent);
                $i = $tokens[$i]['parenthesis_closer'] + 1;
                continue;
            }

            if ($tokens[$i]['line'] !== $prevLine) {
                if ($tokens[$i]['line'] === $tokens[$close]['line']) {
                    $next = $phpcs->findNext(T_WHITESPACE, $i, null, true);
                    if ($next !== $close) {
                        // Closing bracket is on the same line as a condition.
                        $error = 'Closing parenthesis of a multi-line IF statement must be on a new line';
                        $phpcs->addError($error, $close, 'CloseBracketNewLine');
                        $expectedIndent = ($indent + $this->indent);
                    } else {
                        // Closing brace needs to be indented to the same level
                        // as the statement.
                        $expectedIndent = $indent;
                    }//end if
                } else {
                    $expectedIndent = ($indent + $this->indent);
                }//end if

                if ($tokens[$i]['code'] === T_COMMENT) {
                    $prevLine = $tokens[$i]['line'];
                    continue;
                }

                // We changed lines, so this should be a whitespace indent token.
                if ($tokens[$i]['code'] !== T_WHITESPACE) {
                    $foundIndent = 0;
                } else {
                    $foundIndent = strlen($tokens[$i]['content']);
                }

                if ($expectedIndent !== $foundIndent) {
                    $error = 'Multi-line IF statement not indented correctly; expected %s spaces but found %s';
                    $data  = array(
                        $expectedIndent,
                        $foundIndent,
                    );

                    $phpcs->addError($error, $i, 'Alignment', $data);
                }

                $next = $phpcs->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $i, null, true);
                if ($next !== $close) {
                    if (isset(PHP_CodeSniffer_Tokens::$booleanOperators[$tokens[$next]['code']]) === false) {
                        $error = 'Each line in a multi-line IF statement must begin with a boolean operator';
                        $phpcs->addError($error, $i, 'StartWithBoolean');
                    }
                }//end if

                $prevLine = $tokens[$i]['line'];
            }//end if

            if ($tokens[$i]['code'] === T_STRING) {
                $next = (int) $phpcs->findNext(T_WHITESPACE, ($i + 1), null, true);
                if ($tokens[$next]['code'] === T_OPEN_PARENTHESIS) {
                    // This is a function call, so skip to the end as they
                    // have their own indentation rules.
                    $i        = (int) $tokens[$next]['parenthesis_closer'];
                    $prevLine = $tokens[$i]['line'];
                    continue;
                }
            }
        }//end for
    }

}//end class
