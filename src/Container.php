<?php

namespace App;

use Exception;

final class Container implements ShapesParser
{
    /**
     * Function to parse the input string
     *
     * @param String $input
     * @return string
     */
    public function parse(string $input)
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

    // stores the labels for the shapes
    $labels = [];

    // the deepest depth of the shape
    $maximum_level = 0;

    // If first and last character don't match return false
    if(!$this->brackets_match($first, $last))
    {
        throw new Exception("Invalid input");
    }

    for($position = 0; $position < $input_length; $position++)
    {
        $character = $input[$position];

        if($this->is_bracket($character))
        {
            if($this->is_opening_bracket($character))
            {
                // push the character into the stack array
                array_push($stack, $character);

                array_push($stack_intact, [
                    'shape' => $character, 
                    'level' => count($stack) - 1, 
                    'label' => trim($current_tokens)]);
                $maximum_level = max($maximum_level, count($stack) - 1);
            } else if($this->is_closing_bracket($character)) 
            {
                // If the current closing bracket does not close the one at the top of the stack
                // return false. Else pop the last opening bracket/parenthesis from the stack
                if((!count($stack)) || (!$this->brackets_match($stack[count($stack) - 1], $character)))
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
    
    return $this->validate($stack_intact);
    }

    /**
     * Do the validation
     */
    private function validate($parse_results)
    {
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

            if(!$this->label_valid($parse_result['label'], $parse_result['shape']))
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
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the character is an opening bracker/parenthesis
     */
    private function is_opening_bracket($character)
    {
        return in_array($character, ['[', '(']);
    }

    /**
     * Check if the character is a closing bracker/parenthesis
     */
    private function is_closing_bracket($character)
    {
        return in_array($character, [')', ']']);
    }

    /**
     * Checks for a match in the first and last characters
     */
    private function brackets_match(string $opening, string $closing)
    {
        $final = $opening.$closing;
        return in_array($final, ['[]', '()']);
    }

    /**
     * Checks if the character is a bracket/parenthesis
     */
    private function is_bracket($character)
    {
        return in_array($character, ['[', ']', '(', ')']);
    }

    /**
     * Checks if the label is either a number or an uppercase string
     */
    private function label_valid($label, $shape)
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

}
