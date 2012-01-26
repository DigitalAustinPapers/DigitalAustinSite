<?php

function make_cloud($tags, $tag_count, $maxtags = 25, $options = array())
    {
        $controller = (isset($options['controller'])) ? $options['controller'] : 'tags';
        $action = (isset($options['action'])) ? $options['action'] : 'view';
        $params = (isset($options['params'])) ? $options['params'] : array();

        // sort the two arrays
        arsort($tag_count);
                
        if (count($tags) > $maxtags)
        {
            $tag_count = array_slice($tag_count, 0, $maxtags, true);
            $temp_tags = $tags;
            $tags = array();
            foreach ($tag_count as $tag_id => $frequency)
            {
                $tags[$tag_id] = $temp_tags[$tag_id]; 
            }
        }
        
        asort($tags);
        reset($tag_count);
        
        // most of this reproduces Bill Turkel's Python code at http://niche.uwo.ca/programming-historian/index.php/Tag_clouds
        // the max frequency is number of occurrances of the most frequently used tag; min frequency is obviously the minimum
        $maxfreq = current($tag_count);
        $minfreq = end($tag_count);
        $freqrange = $maxfreq - $minfreq;
        
        $minfont = 10;
        $maxfont = 30;
        $fontrange = $maxfont - $minfont;
        
        $spans = array();
        
        foreach($tags as $tag_id => $tag)
        {
            $location_array = array('controller'=>$controller,'action'=>$action);
            if (count($params) > 0)
            {
                $location_array = array_merge($location_array, $params);
            }
            $location_array[] = $tag_id;
            
            $freq = $tag_count[$tag_id];
            $scalingfactor = ($freqrange != 0) ? ($freq - $minfreq) / $freqrange : 1;
            $fontsize = round($minfont + floor($fontrange * $scalingfactor));
            $blue = round(255 - ceil(250 * $scalingfactor));
            $red = 250 -$blue;
            $spans[] = '<span style="font-size: '.$fontsize.'px;" class="tag_cloud_item">'.$this->Html->link($tag, $location_array, array('style' => 'color: rgb('.$red.',0,'.$blue.');'), false, false).'</span>';
        }
        
        return implode(' ', $spans);        
    }

?>