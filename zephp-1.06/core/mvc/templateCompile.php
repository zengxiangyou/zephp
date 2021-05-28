<?php
/**
 * @author	zengxy 1559261757@qq.com
 * @final	2015-01-05
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
		
		$r1[] = "/{$left}php{$right}(.*?){$left}\/php{$right}/is";
		$r2[] = "<?php \\3 ?>";
		
		$r1[] = "/{$left}__(.*?)[;]?\s*_{$right}/is";
		$r2[] = "<?php \\2; ?>";
		
		$r1[] = "/{$left}_(.*?)[;]?\s*{$right}/is";
		$r2[] = "<?php \\2; ?>";
		
		$r1[] = "/{$left}{$var}\s*\=\s*(.*?)[;]?\s*{$right}/";
		$r2[] = "<?php \$\\2 = \\3; ?>";
		
		$r1[] = "/{$left}{$var}{$right}/";
		$r2[] = "<?php echo \$\\2; ?>";
		
		$r1[] = "/{$left}{$var}\\.([a-zA-Z0-9_\x7f-\xff]+){$right}/";
		$r2[] = "<?php echo \$\\2['\\3']; ?>";
		
		$r1[] = "/{$left}\-\s*(.*?)[;]?\s*{$right}/i";
		$r2[] = "<?php echo \\2; ?>";
		
		$r1[] = "/{$left}if\s+(.*?)\s*{$right}/i";
		$r2[] = "<?php if (\\2) { ?>";
		
		$r1[] = "/{$left}else{$right}/i";
		$r2[] = "<?php } else { ?>";
		
		$r1[] = "/{$left}\/(if|loop|for){$right}/i";
		$r2[] = "<?php } ?>";
		
		$r1[] = "/{$left}(loop)\s+(.*?)\s*{$right}/i";
		$r2[] = "<?php foreach (\\3) { ?>";
		
		$r1[] = "/{$left}(for)\s+(.*?)\s*{$right}/i";
		$r2[] = "<?php for (\\3) { ?>";
		
		return preg_replace($r1, $r2, $html);
	}
	
	//递归读模板
	function inc ($f) {
		if (is_file($f)) {
			$this->incArr[] = $f;
			$dir = dirname($f) . '/';
			$f = zz::read($f);
			if (preg_match_all("/{$this->left}=\s*[\'\"]?([^\s].*?)[\'\"]?(;|\s?)\s*{$this->right}/i", $f, $m)) {
				$len = count($m[0]);
				for ($i = 0; $i < $len; $i++) {
					$f = str_replace($m[0][$i], $this->inc($dir . $m[2][$i]), $f);
				}
			}
			return $f;
		}
		return '';
	}
	
}