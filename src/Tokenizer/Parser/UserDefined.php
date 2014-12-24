<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/23/14
 * Time: 1:26 PM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\SqlQueryFormatter\Tokenizer\Parser;

use NilPortugues\SqlQueryFormatter\Tokenizer\Tokenizer;

/**
 * Class UserDefined
 * @package NilPortugues\SqlQueryFormatter\Tokenizer\Parser
 */
final class UserDefined
{
    /**
     * @param Tokenizer $tokenizer
     * @param           $string
     *
     * @return array
     */
    public static function isUserDefinedVariable(Tokenizer $tokenizer, $string)
    {
        if (!$tokenizer->getNextToken() && self::isUserDefinedVariableString($string)) {
            $tokenizer->setNextToken(self::getUserDefinedVariableString($string));
        }
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    protected static function isUserDefinedVariableString(&$string)
    {
        return $string[0] === '@' && isset($string[1]);
    }

    /**
     * Gets the user defined variables for in quoted or non-quoted fashion.
     *
     * @param string $string
     *
     * @return array
     */
    protected static function getUserDefinedVariableString(&$string)
    {
        $returnData = [
            Tokenizer::TOKEN_VALUE => null,
            Tokenizer::TOKEN_TYPE  => Tokenizer::TOKEN_TYPE_VARIABLE
        ];

        if ($string[1] === '"' || $string[1] === '\'' || $string[1] === '`') {
            $returnData[Tokenizer::TOKEN_VALUE] = '@' . Quoted::wrapStringWithQuotes(substr($string, 1));
            return $returnData;
        }

        $matches = [];
        preg_match('/^(@[a-zA-Z0-9\._\$]+)/', $string, $matches);
        if ($matches) {
            $returnData[Tokenizer::TOKEN_VALUE] = $matches[1];
        }

        return $returnData;
    }
}
