<?php
/**
 @copyright: zengxy.com 1559261757@qq.com
 @final: 2017年5月24日
 @todo: 
*/

class z_core_func_htmlfilter{
    
    
    /**
     * 过滤html，防xss攻击，用户提交内容
     * @param string|array $str
     * @return string|array
     */
    function htmlFilter ($str) {
        if (!is_array($str)) {
            if (is_numeric($str)) return $str;
            if (is_string($str)) {
                if ($str == '') return $str;
                
                $str = preg_replace("/\s+/", ' ', $str); //回车
                $str = preg_replace("/<[ ]+/si", '<', $str); //"<"后空格
                 
                $str = preg_replace("/<\!--.*?-->/si", '', $str); //注释
                $str = preg_replace("/<\!.*?>/si", '', $str); //DOCTYPE标签
                $str = preg_replace("/<\/?html.*?>/si", '', $str); //html标签
                $str = preg_replace("/<\/?head.*?>/si", '', $str); //head标签
                $str = preg_replace("/<\/?meta.*?>/si", '', $str); //meta标签
                $str = preg_replace("/<\/?body.*?>/si", '', $str); //body标签
                $str = preg_replace("/<\/?link.*?>/si", '', $str); //link标签
                $str = preg_replace("/<\/?form.*?>/si", '', $str); //form标签
                $str = preg_replace("/<\/?base.*?>/si", '', $str); //base标签
                 
                $str = preg_replace("/<applet.*?>.*?<\/applet.*?>/si", '', $str); //applet标签
                $str = preg_replace("/<\/?applet.*?>/si", '', $str); //applet
                 
                $str = preg_replace("/<style.*?>.*?<\/style.*?>/si", '', $str); //style标签
                $str = preg_replace("/<\/?style.*?>/si", '', $str); //style
                 
                $str = preg_replace("/<title.*?>.*?<\/title.*?>/si", '', $str); //title标签
                $str = preg_replace("/<\/?title.*?>/si", '', $str); //title
                 
                $str = preg_replace("/<object.*?>.*?<\/object.*?>/si", '', $str); //object标签
                $str = preg_replace("/<\/?objec.*?>/si", '', $str); //object
                 
                $str = preg_replace("/<noframes.*?>.*?<\/noframes.*?>/si", '', $str); //noframes标签
                $str = preg_replace("/<\/?noframes.*?>/si", '', $str); //noframes
                 
                $str = preg_replace("/<i?frame.*?>.*?<\/i?frame.*?>/si", '', $str); //frame标签
                $str = preg_replace("/<\/?i?frame.*?>/si", '', $str); //frame
                 
                $str = preg_replace("/<script.*?>.*?<\/script.*?>/si", '', $str); //script标签
                $str = preg_replace("/<\/?script.*?>/si", '', $str); //script
                 
                $str = preg_replace("/\bcookie\b/si", "coo-kie", $str); //cookie
                $str = preg_replace("/\bjavascript\b/si", "java-script", $str); //javascript
                $str = preg_replace("/\bvbscript\b/si", "vb-script", $str); //vbscript
                $str = preg_replace("/\bon([a-z]+)\s*=/si", "on-\\1=", $str); //js事件
                $str = preg_replace("/&#/si", "&﹟", $str); //转义字符
                 
                return addslashes(trim($str));
            }
            return addslashes($str);
        }
        if (!empty($str)) foreach ($str as $k => $v) $str[$k] = $this->htmlFilter($v);
        return $str;
    }
    
    
}