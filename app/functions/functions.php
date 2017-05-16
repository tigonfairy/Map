<?php

function showCategories($categories, $parent_id = 0)
{

    $cate_child = array();
    foreach ($categories as $key => $item)
    {

        if ($item['parent_id'] == $parent_id)
        {
            $cate_child[] = $item;
            unset($categories[$key]);
        }
    }


    if ($cate_child)
    {

        echo '<ol class="dd-list">';
        foreach ($cate_child as $key => $item)
        {
            echo '<li class="dd-item" data-id="'.$item['id']. '"><div class="dd-handle"><a href="'.route('Admin::category@showProduct',[$item['id']]).'">'. $item['name'] . '</a></div>';
            showCategories($categories, $item['id']);
            echo '</li>';
        }
        echo '</ol>';
    }
}

function IcheckshowCategories($categories, $parent_id = 0, $stt = 0)
{

    $cate_child = array();
    foreach ($categories as $key => $item)
    {

        if ($item['parent_id'] == $parent_id)
        {
            $cate_child[] = $item;
            unset($categories[$key]);
        }
    }


    if ($cate_child)
    {

        echo '<ol class="dd-list">';
        foreach ($cate_child as $key => $item)
        {
            echo '<li class="dd-item" data-id="'.$item['id']. '"><div class="dd-handle"><input type="radio" id = "icheck_category" class = "icheck_category" name="icheck_category" value="'.$item['id'].'">
            '. $item['name'] . '</div>';
            IcheckshowCategories($categories, $item['id'], ++$stt);
            echo '</li>';
        }
        echo '</ol>';
    }
}
function AmazoneshowCategories($categories, $parent_id = 0, $stt = 0)
{

    $cate_child = array();
    foreach ($categories as $key => $item)
    {

        if ($item['parent_id'] == $parent_id)
        {
            $cate_child[] = $item;
            unset($categories[$key]);
        }
    }


    if ($cate_child)
    {

        echo '<ol class="dd-list">';
        foreach ($cate_child as $key => $item)
        {
            echo '<li class="dd-item" data-id="'.$item['id']. '"><div class="dd-handle"><input type="radio" id = "amazone_category" class = "amazone_category" name="amazone_category" value="'.$item['id'].'">
            '. $item['name'] . '</div>';
            AmazoneshowCategories($categories, $item['id'], ++$stt);
            echo '</li>';
        }
        echo '</ol>';
    }
}
?>

