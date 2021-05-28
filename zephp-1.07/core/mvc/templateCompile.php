<?php
/**
 * @author	zengxy.com 1559261757@qq.com
 * @final	2018-10-12
 * @todo	
 */

class z_core_mvc_templateCompile {
	
	public $incArr = array();
	
	private $left = '(\{|\<\!\-\-\{)';
	private $right = '(\}\-\-\>|\})';
	
	function __destruct(){
		unset($this->incArr);
	}
	
	
	//编译模板
	function compile ($html, $php = TRUE) {
		if (empty($html)) return '';
		$r1 = array();
		$r2 = array();
		$left = $this->left;
		$right = $this->right;
		$v1 = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
		$v2 = '\[[\'\"]?[a-zA-Z0-9_\x7f-\xff]+[\"\']?\]';
		$var = '\\$('. $v1 .'|'. $v1 . $v2 .')';
		
		if (!$php) {
			$r1[] = "/<\\?(=|php)(.*?)\\?>/is";
			$r2[] = "&lt;?\\1\\2?&gt;";
		}
		
		// {/ ... /}
		$r1[] = "/{$left}\/.*?\/{$right}/is";
		$r2[] = "";
		
		// {php} ... {/php}
		$r1[] = "/{$left}php{$right}(.*?){$left}\/php{$right}/is";
		$r2[] = "<?php \\3 ?>";
		
		// {__ ... _}
		$r1[] = "/{$left}__(.*?)[;]?\s*_{$right}/is";
		$r2[] = "<?php \\2; ?>";
		
		// {_ ... }
		$r1[] = "/{$left}_(.*?)[;]?\s*{$right}/is";
		$r2[] = "<?php \\2; ?>";
		
		// {$k = ... }
		$r1[] = "/{$left}{$var}\s*\=\s*(.*?)[;]?\s*{$right}/";
		$r2[] = "<?php \$\\2 = \\3; ?>";
		
		// {$k}
		$r1[] = "/{$left}{$var}{$right}/";
		$r2[] = "<?php echo \$\\2; ?>";
		
		// {$arr.k}
		$r1[] = "/{$left}{$var}\\.([a-zA-Z0-9_\x7f-\xff]+){$right}/";
		$r2[] = "<?php echo \$\\2['\\3']; ?>";
		
		// {- $k }
		$r1[] = "/{$left}\-\s*(.*?)[;]?\s*{$right}/i";
		$r2[] = "<?php echo \\2; ?>";
		
		// {if ... }
		$r1[] = "/{$left}if\s+(.*?)\s*{$right}/i";
		$r2[] = "<?php if (\\2) { ?>";
		
		// {else}
		$r1[] = "/{$left}else{$right}/i";
		$r2[] = "<?php } else { ?>";
		
		// {/if} {/loop} {/for}
		$r1[] = "/{$left}\/(if|loop|for){$right}/i";
		$r2[] = "<?php } ?>";
		
		// {loop ... }
		$r1[] = "/{$left}(loop)\s+(.*?)\s*{$right}/i";
		$r2[] = "<?php foreach (\\3) { ?>";
		
		// {for ... }
		$r1[] = "/{$left}(for)\s+(.*?)\s*{$right}/i";
		$r2[] = "<?php for (\\3) { ?>";
		
		return $this->_rep(preg_replace($r1, $r2, $html));
	}
	
	// $arr.k -> $arr['k']
	private function _rep ($html) {
	    $arr = explode('<?php', $html);
	    $html = '';
	    foreach ($arr as $r) {
	        $b = explode('?>', $r);
	        $i = count($b);
	        if ($i == 2) {
	            $c = $b[0];
	            if ($c != '') {
	                $c = $this->_rep_1($c);
	                $c = preg_replace('/\$(\w+)\.(\w+)/is', '$\\1[\'\\2\']', $c);
	            }
	            $r = '<?php' . $c . '?>' . $b[1];
	        }
	        $html .= $r;
	    }
	    unset($arr);
	    return $html;
	}
	
	private function _rep_1 ($html) {
	    $re = '/\"([^\"]*)\$(\w+)\.(\w+)([^\"]*)\"/is';
	    if (preg_match($re, $html)) $html = $this->_rep_1(preg_replace($re, '"\\1{$\\2[\'\\3\']}\\4"', $html));
	    return $html;
	}
	
	//递归读模板 {= inc.html }
	function inc ($f) {
		if (is_file($f)) {
			$this->incArr[] = $f;
			$dir = dirname($f) . '/';
			$html = zz::read($f);
			if (preg_match_all("/{$this->left}=\s*[\'\"]?([^\s].*?)[\'\"]?(;|\s?)\s*{$this->right}/i", $html, $m)) {
				$len = count($m[0]);
				for ($i = 0; $i < $len; $i++) {
					$html = str_replace($m[0][$i], $this->inc($dir . $m[2][$i]), $html);
				}
			}
			return $html;
		}
		return '';
	}
	
}