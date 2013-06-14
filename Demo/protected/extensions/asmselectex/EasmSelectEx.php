<?php
/**
 * EasmSelectEx class file.
 *
 * @author ironic
 * @version 1.0
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008 ironic
 * @license
 *
 * Copyright © 2008 by ironic. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * - Neither the name of ironic nor the names of its contributors may
 *   be used to endorse or promote products derived from this software without
 *   specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * asmSelectEx generates a alternative multiple Select Box (with optgroup support!).
 *
 * The asmSelectEx widget is implemented based this jQuery plugin:
 * (see {@link http://code.google.com/p/jquery-asmselect/}).
 *
 * Credits for the OptGroup support goes to: 
 * Google-Code User: http://code.google.com/u/ideaoforder/
 * (details: http://code.google.com/p/jquery-asmselect/issues/detail?id=8)
 * 
 * This widget is way more useful as a <select multiple> (the default mode)
 *
 * @author ironic
 * @package application.extensions.asmselectex
 * @since 1.0
 */
class EasmSelectEx extends CWidget
{
//***************************************************************************
// Properties
//***************************************************************************
   /**
    * The Name of the html select-tag
    *
    * @var string
    */
	public $name = "";
	
   /**
    * The values (optgroup & nested option tags)
    *
    * @var array
    */	
	public $values = array();
	
   /**
    * The pre selected option tags
    *
    * @var array
    */		
	public $selected = array();
	
   /**
    * The html attributes for select tag
	* (attribute=>value)
    *
    * @var array
    */		
	public $attributes = array();

   /**
    * The options for the jquery.asmselectex script 
	* ("animate: true, highlight: true, sortable: false")
    *
    * @var string
    */		
	public $scriptOptions = "";

//***************************************************************************
// Paint the widget
//***************************************************************************
	public function run()
	{
		// type checking for our params
		if(!is_string($this->name))
			throw new CException(Yii::t('EasmSelectEx', 'Invalid type. name must be a string.'));	

		if(!is_array($this->values))
			throw new CException(Yii::t('EasmSelectEx', 'Invalid type. values must be an array.'));	

		if(!is_array($this->selected))
			throw new CException(Yii::t('EasmSelectEx', 'Invalid type. selected must be an array.'));	
		
		if(!is_array($this->attributes))
			throw new CException(Yii::t('EasmSelectEx', 'Invalid type. attributes must be an array.'));	

		if(!is_string($this->scriptOptions))
			throw new CException(Yii::t('EasmSelectEx', 'Invalid type. scriptOptions must be a string.'));	

		// lets check our required params
		if($this->name=="")
			throw new CException(Yii::t('EasmSelectEx', 'Invalid value. name can not be a empty string.'));	

		// register clientside widget files		
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery';
		$baseUrl =Yii::app()->getAssetManager()->publish($dir);
		
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
//		$cs->registerScriptFile($baseUrl.'/js/jquery.ui.js');
		$cs->registerScriptFile($baseUrl.'/js/jquery.tinysort.min.js');
		$cs->registerScriptFile($baseUrl.'/js/jquery.asmselectex.js');
		$cs->registerCssFile($baseUrl.'/css/asmselect.css');

		// register our document.ready function			
		$execScript = sprintf('$("#%s").asmSelectEx({%s});', $this->name, $this->scriptOptions);		
		$cs->registerScript($this->name, $execScript, CClientScript::POS_READY);					
					
		// parse the attributes for the select-tag
		$attr_html = '';
		if(!empty($this->attributes))
		{
			foreach ($this->attributes as $k=>$v)
				$attr_html .= ' '.$k.'="'.$v.'"';
		}
	
		// buffer the output
		$output = sprintf("<select multiple=\"multiple\" name=\"%s[]\" id=\"%s\"%s>\n", $this->name, $this->name, $attr_html);
		if(!empty($this->values))
		{								  
			foreach($this->values as $key=>$value)
			{
				if(is_array($value))
				{
					if(count($value)>0) // we dont render a empty optgroup
					{
						$output .= sprintf("<optgroup label=\"%s\">\n", $key);
						foreach($value as $k=>$v)
						{
							$sel = in_array($k,$this->selected) ? 'selected="selected"' : '';
							$output .= sprintf("<option value=\"%s\" %s>%s</option>\n", $k, $sel, $v);
						}
						$output .= "</optgroup>\n";
					}
				}
				else
				{
					$sel = in_array($key,$this->selected) ? ' selected="selected"' : '';
					$output .= sprintf("<option value=\"%s\" %s>%s</option>\n", $key, $sel, $value);
				}
			}
		}
		$output .= "</select>\n";				
			
		// paint the widget	
		echo $output;
	}
}