<?php 

/**
 * Check if the character is an opening bracker/parenthesis
 */
function is_opening_bracket($character)
{
    return in_array($character, ['[', '(']);
}

/**
 * Check if the character is a closing bracker/parenthesis
 */
function is_closing_bracket($character)
{
    return in_array($character, [')', ']']);
}

function is_space(string $character)
{

    return $character === ' ';
}

/**
 * Checks for a match in the first and last characters
 */
function brackets_match(string $opening, string $closing)
{
    $final = $opening.$closing;
    return in_array($final, ['[]', '()']);
}

/**
 * Checks if the character is a bracket/parenthesis
 */
function is_bracket($character)
{
    return in_array($character, ['[', ']', '(', ')']);
}

/**
 * Checks if the label is either a number or an uppercase string
 */
function label_valid($label, $shape)
{
    $square = '/[0-9]/';
    $circle = '/[A-Z]/';
    $regex = $square;
    if($shape === 'Circle')
    {
        $regex = $circle;
    }

    if(preg_match($regex, $label))
    {
        return true;
    }
    return false;

}

function parser(string $input)
{
    // stores only the opening brackets
    $stack = [];

    // stores the opening brackets but is not popped when we find the closing
    $stack_intact = [];

    // Remove leading and trailing white spaces
    $input = trim($input);

    $input_length = strlen($input);

    //string character that's not a bracket/parenthesis
    $current_tokens = '';

    // last and first characters of the string
    $first = $input[0];
    $last = $input[$input_length - 1];
    
    // If first and last character don't match return false
    if(!brackets_match($first, $last))
    {
        return false;
    }

    // stores the labels for the shapes
    $labels = [];

    // the deepest depth of the shape
    $maximum_level = 0;

    for($position = 0; $position < $input_length; $position++)
    {
        $character = $input[$position];

        // // if(!is_valid_token($character))
        // // {
        //     throw new Exception("Not a valid label", 1);
        // }

        if(is_bracket($character))
        {
            if(is_opening_bracket($character))
            {
                // push the character into the stack array
                array_push($stack, $character);

                array_push($stack_intact, [
                    'shape' => $character, 
                    'level' => count($stack) - 1, 
                    'label' => trim($current_tokens)]);
                $maximum_level = max($maximum_level, count($stack) - 1);
            } else if(is_closing_bracket($character)) 
            {
                // If the current closing bracket does not close the one at the top of the stack
                // return false. Else pop the last opening bracket/parenthesis from the stack
                if((!count($stack)) || (!brackets_match($stack[count($stack) - 1], $character)))
                {
                    return false;
                } else
                {
                    array_pop($stack);
                }

            }

            // if label is not empty push it in the labels stack
            if($current_tokens !== ''){
                $labels[]= trim($current_tokens);
            }
            // reset it
            $current_tokens = '';
        } else 
        {   
            // appends current character to the label
            $current_tokens .= $character;
        }

    }

    // re-assign character depending in whether it's bracket or parenthesis
    foreach($stack_intact as $key => $token)
    {
        $modified_token = $token;
        $modified_token['label'] = $labels[$key];
        $modified_token['shape'] = $modified_token['shape'] === '(' ? 'Circle' : 'Square';
        $stack_intact[$key] = $modified_token;
    }
    // push maximum level to the results
    $stack_intact[] = $maximum_level;
    return $stack_intact;
}


function validate(string $input)
{
    $parse_results = parser($input);

    if(!$parse_results)
    {
        return false;
    }

    $maximum_levels = array_fill(0, $parse_results[count($parse_results) - 1] + 1, []);

    foreach($parse_results as $index => $parse_result)
    {
        // we don't need the extra item($maximum_level) which is not among the shapes
        if(($index + 1) === count($parse_results))
        {
            break;
        }

        if(!label_valid($parse_result['label'], $parse_result['shape']))
        {
            throw new Exception("Invalid label '{$parse_result['label']}' for shape '{$parse_result['shape']}'");
        }
        
        $maximum_levels[$parse_result['level']][$parse_result['shape']] = 1;
    }

    foreach ($maximum_levels as $level => $shape) {
        $current_level_circle = false;
        $square_in_previous_level = false;

        if($level > 0)
        {
            $previous_shapes = $maximum_levels[$level - 1];
            $current_level_circle = isset($shape['Circle']) ? true : false;
            $square_in_previous_level = isset($previous_shapes['Square']) ? true : false;
        }
        if($square_in_previous_level && $current_level_circle)
        {
            throw new Exception("A circle cannot be inside a square");
        }
    }
    return true;
}

// $case1 = "[13]";
// $case2 = "(GEEK)";
// $case3 = "[23[90]]";
// $case4 = "(CIRCLE(CIRCLE))";
// $case5 = "(HELLO[89])";
// $case6 = "[33][23]";

$case1 = "$@#";
$case2 = "[78)";
$case3 = "HELLO";
$case4 = "[72(HELLO)]";
$case5 = "[ALLOW]";
$case6 = "([12])";
$case7 = "[allow]";

$example = "[12](BALL(INK[1[35]](CHARLIE(LAZARUS)(SILAS(JAMES)(MBOYA(WORSE)(JAKE)[1019191])(FAKE)))))";
$cases = [$case1, $case2, $case3, $case4, $case5, $case6, $example];

var_dump(validate($example));
// foreach ($cases as $value) {
//     var_dump(validate($value));
// }