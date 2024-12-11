<?php

use App\Models\Game;

function addPlusSymbol($number)
    {
        if($number>0)
            return '+'.$number;

        return $number;
    }

    function getLastWord($string)
    {
        $string = explode(' ', $string);
        $last_word = array_pop($string);
        return $last_word;
    }

    function getGameWeeks()
    {
        $weeks = [];
        for($index=1; $index<=22 ; $index++){

            if($index == 19)
                $weeks[$index] = 'Wild Card';
            elseif($index == 20)
                $weeks[$index] = 'Divisional Round';
            elseif($index == 21)
                $weeks[$index] = 'Conference Championship';
            elseif($index == 22)
                $weeks[$index] = 'Super Bowl';
            else
                $weeks[$index] = $index;
        }

        return $weeks;
    }

    function getGrades($grade){
        if($grade>=95)
            return 'A+';
        elseif($grade>=93)
            return 'A';
        elseif($grade>=90)
            return 'A-';
        elseif($grade>=87)
            return 'B+';
        elseif($grade>=84)
            return 'B';
        elseif($grade>=80)
            return 'B-';
        elseif($grade>=78)
            return 'C+';
        elseif($grade>=76)
            return 'C';
        else
            return 'C-';
    }


