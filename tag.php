<?php
interface iTag
	{
		// Геттер имени:
		public function getName();
		
		// Геттер текста:
		public function getText();
		
		// Геттер всех атрибутов:
		public function getAttrs();
		
		// Геттер одного атрибута по имени:
		public function getAttr($name);
		
		// Открывающий тег, текст и закрывающий тег:
		public function show();
		
		// Открывающий тег:
		public function open();
		
		// Закрывающий тег:
		public function close();
		
		// Установка текста:
		public function setText($text);
		
		// Установка атрибута:
		public function setAttr($name, $value = true);
		
		// Установка атрибутов:
		public function setAttrs($attrs);
		
		// Удаление атрибута:
		public function removeAttr($name);
		
		// Установка класса:
		public function addClass($className);
		
		// Удаление класса:
		public function removeClass($className);
	}

	class Tag implements iTag
	{
		private $name;
		private $attrs = [];
		private $text = '';
		
		public function __construct($name)
		{
			$this->name = $name;
		}
		
		public function getName()
		{
			return $this->name;
		}
		
		public function getText()
		{
			return $this->text;
		}
		
		public function getAttrs()
		{
			return $this->attrs;
		}
		
		public function getAttr($name)
		{
			if (isset($this->attrs[$name])) {
				return $this->attrs[$name];
			} else {
				return null;
			}
		}
		
		public function show()
		{
			return $this->open() . $this->text . $this->close();
		}
		
		public function open()
		{
			$name = $this->name;
			$attrsStr = $this->getAttrsStr($this->attrs);
			
			return "<$name$attrsStr>";
		}
		
		public function close()
		{
			$name = $this->name;
			return "</$name>";
		}
		
		public function setText($text)
		{
			$this->text = $text;
			return $this;
		}
		
		public function setAttr($name, $value = true)
		{
			$this->attrs[$name] = $value;
			return $this;
		}
		
		public function setAttrs($attrs)
		{
			foreach ($attrs as $name => $value) {
				$this->setAttr($name, $value);
			}
			
			return $this;
		}
		
		public function removeAttr($name)
		{
			unset($this->attrs[$name]);
			return $this;
		}
		
		public function addClass($className)
		{
			if (isset($this->attrs['class'])) {
				$classNames = explode(' ', $this->attrs['class']);
				
				if (!in_array($className, $classNames)) {
					$classNames[] = $className;
					$this->attrs['class'] = implode(' ', $classNames);
				}
			} else {
				$this->attrs['class'] = $className;
			}
			
			return $this;
		}
		
		public function removeClass($className)
		{
			if (isset($this->attrs['class'])) {
				$classNames = explode(' ', $this->attrs['class']);
				
				if (in_array($className, $classNames)) {
					$classNames = $this->removeElem($className, $classNames);
					$this->attrs['class'] = implode(' ', $classNames);
				}
			}
			
			return $this;
		}
		
		private function getAttrsStr($attrs)
		{
			if (!empty($attrs)) {
				$result = '';
				
				foreach ($attrs as $name => $value) {
					if ($value === true) {
						$result .= " $name";
					} else {
						$result .= " $name=\"$value\"";
					}
				}
				
				return $result;
			} else {
				return '';
			}
		}
		
		private function removeElem($elem, $arr)
		{
			$key = array_search($elem, $arr);
			array_splice($arr, $key, 1);
			
			return $arr;
		}
	}

	/**
	 * 
	 */
	class Image extends Tag
	{
		
		function __construct()
		{
			parent::__construct('img');
			$this->setAttr('src','')->setAttr('alt','');
		}

		public function __toString()
		{
			return parent::open();
		}
	}

	
   class Link extends Tag
   {
   	const MARKLINK = 'active';
   	
   	function __construct()
   	{
   		parent::__construct('a');
   		$this->setAttr('href','');
   	}

   	//Переопределяем метод родителя:
	public function open()
	{
		$this->activateSelf(); // вызываем активацию
		return parent::open(); // вызываем метод родителя
	}
		
	private function activateSelf()
	{
		$url = $_SERVER['REQUEST_URI'];         // обрезаем ненужное с URL проверять работу!!!!
        preg_match_all('#.+/(.+\..+)$#', $url, $matches);
        $myUrl = ($matches[1][0]);

		if ($this->getAttr('href') == $myUrl) {
				$this->addClass(Link::MARKLINK);
		}
	}

   }

   	class ListItem extends Tag
	{
		public function __construct()
		{
			// Вызываем конструктор родителя, передав в качестви имени 'li':
			parent::__construct('li');
		}
	}

	class HtmlList extends Tag
	{
		private $items = [];
		
		public function addItem(ListItem $li)
		{
			$this->items[] = $li;
			return $this;
		}
		
		public function show()
		{
			$result = $this->open();
			
			foreach ($this->items as $item) {
				$result .= $item->show();
			}
			
			$result .= $this->close();
			
			return $result;
		}

	}
 
    class Form extends Tag
	{
		public function __construct()
		{
			parent::__construct('form');
		}
	}
  
  
	
    class Input extends Tag
	{
		public function __construct()
		{
			parent::__construct('input');
		}
		
		public function open()
		{
			$inputName = $this->getAttr('name');
			
			// Если атрибут name задан у инпута:
			if ($inputName) {
				if (isset($_REQUEST[$inputName])) {
					$value = $_REQUEST[$inputName];
					$this->setAttr('value', $value);
				}
			}
			
			return parent::open();
		}
		
		public function __toString()
		{
			return $this->open();
		}
	}

    class Submit extends Input
	{
		public function __construct()
		{
			$this->setAttr('type', 'submit');
			parent::__construct();
		}
	}

	class Textarea extends Tag
	{
		public function __construct()
		{
			parent::__construct('textarea');
		}

		public function open()
		{
		    $textareaName = $this->getAttr('name');
			
			// Если атрибут name задан у инпута:
			if ($textareaName) {
				if (isset($_REQUEST[$textareaName])) {
					$value = $_REQUEST[$textareaName];
					$this->setText($value);
				}
			}
			
			return parent::open();
		}

		public function __toString()
		{
			return(string)parent::show();
		}
	}

	
/*
$form = (new Form)->setAttrs(['action'=>'','method','GET']);
print $form->open();
  print (new Input)->setAttr('name','login');
  print (new Input)->setAttr('name','password')->setAttr('type','password');
  echo (new Textarea)->setAttr('name', 'text')->setText('my mess');
  print (new Submit);
print $form->close();
*/