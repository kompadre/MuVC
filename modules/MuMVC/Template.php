<?php
namespace MuMVC;

define('TPL_DEFAULT_LAYOUT', APP_PATH . '/views/layout.tpl');

class Template {
	var $vars, $blocks, $raw_blocks, $raw;

	// Construtor
	function __construct ($file) {
		$this->setFile($file);
	}
	public function setFile($file) {
		if (is_readable($file)) {
			$fh = fopen($file, 'r');
			$this->init($fh);
		}
		else {
			throw new \Exception("Template file < $file > couldn't be found.");
		}
	}
	/**
	 * Returns the final render of the template.
	 * @return string
	 */
	// Public Methods
	function render() {
		$_out = $this->replace($this->raw);
		if (defined('TPL_HARDTRIMMING')) {
			$_out = str_replace("\n", "", $_out);	
			$_out = str_replace("\r", "", $_out);
			$_out = str_replace("\t", "", $_out);
			$_out = str_replace("  +", "", $_out);
		}
		return $_out;
	}

	function asigna($var, $value=null) {
		if (null != $value) {
			$this->vars[$var] = $value;
		}
		else if (is_array($var)) {
			if (!is_array($this->vars))
				$this->vars = $var;
			else
				$this->vars = array_merge($this->vars, $var);
		}
	}

	function parse ($block) {
		if (!isset($this->blocks[$block]))
			$this->blocks[$block] = $this->replace($this->raw_blocks[$block]);
		else
			$this->blocks[$block] .= $this->replace($this->raw_blocks[$block]);
	}

	function parse_into ($bloc, $dest) {
		$this->blocks[$dest] .= $this->replace($this->raw_blocks[$bloc]);
	}
	
	function parse_copy ($bloc, $copy = "") {
		$this->blocks[$bloc] .= $this->replace($this->raw_blocks[$bloc]);
		if (is_array($copy)) {
			foreach ($copy as $cop)	
				$this->blocks[$cop] = $this->blocks[$bloc];
		}
		else if ($copy != "") {
			$this->blocks[$copy] = $this->blocks[$bloc];
		}
	}

	function clear($bloc) {
		$this->blocks[$bloc] = "";
	}

	// Private Methods
	function init($fh) {
		$open_blocks  = array();
		while (!feof($fh)) {
			$line = fgets($fh, 1024);
			if (strpos($line, "{/block}") !== FALSE)	{
				array_pop($open_blocks);
			}
			else {
				if ((is_array($open_blocks)&&(count($open_blocks)))) {
					@$this->raw_blocks[$open_blocks[count($open_blocks) - 1]] .= $line;
				}
				else {
					$this->raw .= $line;
				}
				if (preg_match('/\{block name="(.+?)"\}/', $line, $matches)) {
					array_push($open_blocks, $matches[1]);
				}
			}
		}
		fclose($fh);
	}

	function replace($_in) {
		$_out = preg_replace_callback('/{\$(\w+?)}/', Array(&$this, 'callback_var_content'), $_in);
		$_out = preg_replace_callback("/\{block name=\"(.+?)\"\}/", Array(&$this, 'callback_blocks_content'), $_out);
		$_out = trim($_out);
		return $_out;
	}

	// Callback Functions
	function callback_var_content ($matches) {
    	if (isset($this->vars[$matches[1]])) {
			return $this->vars[$matches[1]];
        }
        else {
			return ("&nbsp;");
		}
	}

	function callback_blocks_content ($matches) {
		$_out = $this->blocks[$matches[1]];
		$this->clear($matches[1]);
		return $_out;
	}
}
?>
