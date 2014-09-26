<?php

namespace DBWork;

class Debug
{
    public static function log($sql, $params)
    {
        $highlights    = array("SELECT ", "FROM ",  " JOIN ", " INSERT INTO", " VALUES ", " INNER JOIN ", " LEFT JOIN ", " RIGHT JOIN ", "USING", "UNION", "ALL", " ON ", "UPDATE", " SET ", "LIMIT");
        $highlights2   = array(" WHERE ", " AND ", " OR ", " BETWEEN ", " IN ", "ISNULL");
        $highlights3   = array("NOT", "DELETE ");

        $regHighlights  = '/' . implode("|", $highlights)  . '/';
        $regHighlights2 = '/' . implode("|", $highlights2) . '/';
        $regHighlights3 = '/' . implode("|", $highlights3) . '/';

        $sql = preg_replace($regHighlights, '<span style="color:#104BA9;font-weight:bold;">$0</span>', $sql);
        $sql = preg_replace($regHighlights2, '<span style="color:#ff6600;font-weight:bold;">$0</span>', $sql);
        $sql = preg_replace($regHighlights3, '<span style="color:#F5001D;font-weight:bold;">$0</span>', $sql);

        $sql = preg_replace_callback('/\?/', function ($matches) use (&$params) {
            return '<strong style="color:#1B8300;"> <em>' . array_shift($params) . '</em> </strong>';
        }, $sql);

        echo "<pre>";
        echo $sql;
        echo "</pre>";
        
    }
}
