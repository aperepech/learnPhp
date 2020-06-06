<?php

class Date
   {  
      public $date;

      public function __construct($date = null)
      {
         if ($date) {
            $this->date = $date;
         }else{
            $this->date = date('Y-m-d');
         }

      }
      
      public function getDay()
      {
        $date = DateTime::createFromFormat('Y-m-d', $this->date);
        return $date->format('d');
      }
      
      public function getMonth($lang = null)
      {  
          $date = DateTime::createFromFormat('Y-m-d', $this->date);
          switch ($lang) {
             case 'en':
                return $date->format('F');
                break;
                case 'ru':
                $months_name = [
                  'января', 'февраля', 'марта',
                  'апреля', 'мая', 'июня',
                  'июля', 'августа', 'сентября',
                  'октября', 'ноября', 'декабря'
                    ]; 
                $month = $date->format('m');
                return $months_name[$month-1];
                break;
          }
                return $date->format('m');

         // возвращает месяц
         
         // переменная $lang может принимать значение ru или en
         // если эта не пуста - пусть месяц будет словом на заданном языке
      }
      
      public function getYear()
      {
         $date = DateTime::createFromFormat('Y-m-d', $this->date);
         return $date->format('Y');
      }
      
      public function getWeekDay($lang = null)
      {  
       $date = DateTime::createFromFormat('Y-m-d', $this->date);
       switch ($lang) {
          case 'ru':
             $days = [1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье'];
             $day = $date->format('w');
             return $days[$day];
             break;
             case 'en':
             return $date->format('l');
             break;
          
       }
       return $date->format('w');
         // возвращает день недели
         
         // переменная $lang может принимать значение ru или en
         // если эта не пуста - пусть месяц будет словом на заданном языке
      }
      
      public function addDay($value)
      {
         $date = date_create($this->date);
         date_modify($date, $value.'day');
         return date_format($date, 'Y-m-d');

         // добавляет значение $value к дню
      }
      
      public function subDay($value)
      {
         $date = date_create($this->date);
         date_modify($date, -$value.'day');
         $this->date =  date_format($date, 'Y-m-d');
         return $this;
         // отнимает значение $value от дня
      }
      
      public function addMonth($value)
      {
         $date = date_create($this->date);
         date_modify($date, $value.'month');
         return date_format($date, 'Y-m-d');
      }
      
      public function subMonth($value)
      {
          $date = date_create($this->date);
          date_modify($date,-$value.'month');
          return date_format($date, 'Y-m-d');
         // отнимает значение $value от месяца
      }
      
      public function addYear($value)
      {
         $date = date_create($this->date);
         date_modify($date, $value.'Year');
         return date_format($date, 'Y-m-d');
      }
      
      public function subYear($value)
      {
         $date = date_create($this->date);
         date_modify($date, -$value.'Year');
         return date_format($date, 'Y-m-d');
         // отнимает значение $value от года
      }
      
      public function format($format)
      {
         $date = DateTime::createFromFormat('Y-m-d', $this->date);
         return $date->format('d-m-Y');
         // выведет дату в указанном формате
         // формат пусть будет такой же, как в функции date
      }
      
      public function __toString()
      {
         return $this->date;
         // выведет дату в формате 'год-месяц-день'
      }
   }

   $date = new Date('2025-12-31');
 
  /**
   echo $date->getYear().'<br>';  // выведет '2025'
   echo $date->getMonth('ru').'<br>'; // выведет '12'
   echo $date->getDay().'<br>';   // выведет '31'
   
   echo $date->getWeekDay().'<br>';     // выведет '3'
   echo $date->getWeekDay('ru').'<br>'; // выведет 'среда'
   echo $date->getWeekDay('en').'<br>'; // выведет 'wednesday'
   echo $date->addYear(1).'<br>'; // '2026-12-31'
   echo $date->addDay(1).'<br>';  // '2026-01-01'
   echo $date->subDay(3)->addYear(1).'<br>'; // '2026-12-28'
   echo $date->addMonth(1).'<br>';
   echo $date->subMonth(1).'<br>';
   echo $date->subYear(1).'<br>';
   echo $date;
   */

   class Interval
   {  

      private $oneDate;
      private $secondDate;

      public function __construct(Date $date1, Date $date2)
      {
         $this->oneDate = $date1;
         $this->secondDate = $date2;
      }
      
      public function toDays()
      { 
         $datetime1 = new DateTime($this->oneDate);
         $datetime2 = new DateTime($this->secondDate);
         $interval = $datetime1->diff($datetime2);
         return $interval->format('%a');
         // вернет разницу в днях
      }
      
      public function toMonths()
      {
         $datetime1 = new DateTime($this->oneDate);
         $datetime2 = new DateTime($this->secondDate);
         $interval = $datetime1->diff($datetime2);
         return $interval->format('%m');
         // вернет разницу в месяцах
      }
      
      public function toYears()
      {
         $datetime1 = new DateTime($this->oneDate);
         $datetime2 = new DateTime($this->secondDate);
         $interval = $datetime1->diff($datetime2);
         return $interval->format('%y');
         // вернет разницу в годах
      }
      
      public function __toString()
      { 
         $str = 'Разница в днях '.$this->toDays().' Разница в месяцах '.$this->toMonths().' Разница в годах '.$this->toYears(); 
         return $str;
         //return  ['years' => '1', 'months' => '2', 'days' => '3'];
         // выведет результат в виде массива
         

      }
   }

   $date1 = new Date('2025-12-31');
   $date2 = new Date('2026-11-28');

   
   $interval = new Interval($date1, $date2);
   
   echo $interval->toDays().'<br>';   // выведет разницу в днях
   echo $interval->toMonths().'<br>'; // выведет разницу в месяцах
   echo $interval->toYears().'<br>';  // выведет разницу в годах
   print $interval;
   var_dump($interval); // массив вида ['years' => '', 'months' => '', 'days' => '']
  print 'change';
