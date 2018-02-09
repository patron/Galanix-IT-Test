<!DOCTYPE html>
<html>
<head>
	<title>Тестовое задание в Galanix IT</title>
	<!-- Подтянули Bootstrap -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container">

<!-- Форма отправки количества обработанных новостей -->
<form action="index.php" method="post" id="main_form">
    <input type="number" placeholder="Число новостей" class="form-control" style="width: 170px;" name="limit"/>
	<br>
    <div id="buttons">
        <input type="submit" value="Старт" id="start" class="btn btn-primary" />
        <input type="reset" value="Очистить" id="reset" class="btn btn-danger"/>
    </div>
</form>

<?php
set_time_limit(600);

//подгружаем библиотеку
include('simple_html_dom.php');
//создаём новый объект
$html = new simple_html_dom();
if (!empty( $_POST["limit"]) ) {
//загружаем в него данные
echo "<table class='table'>"; // Создаем таблицу с заголовком
echo "  <thead>
    <tr>
      <th>#</th>
      <th>Дата парсинга</th>
	  <th>Во сколько опубликована</th>
      <th>Заголовок</th>
      <th>Новость</th>
    </tr>
  </thead>";
$max_article_count = $_POST['limit']; // максимальное количество новостей
$k=1; // k - номер новости по порядку
for ($i = 0; $i < 365;$i++) {
$html = file_get_html('https://www.pravda.com.ua/rus/news/date_'.url_date( time() - $i * 24 * 60 * 60).'/'); // загрузили страницу по дате используя ЧПУ сайта

 if($html->innertext!='' and count($html->find('a'))) {
	for ($j=0; $j < count( $html->find('.block_news_all .article') ); $j++) {
		//$newsarticle = $html->find('.article')[$i]->plaintext;
		
		$newsarticle = $html->find('.block_news_all .article')[$j];
		// Разные поля данных для таблицы
		$article_time = $html->find('.article__time')[$j]->plaintext;
		$article_title = $html->find('#endless .article__title a')[$j]->plaintext;
		$article_subtitle = $html->find('#endless .article__subtitle')[$j]->plaintext;
		
		
		
		if ( $i <= 2 ){ // Механизм преобразования кодировки на разных страницах
			$encoding = "cp1251";
		}
		else {
			$encoding =  mb_detect_encoding( $newsarticle ); 
		}

		echo '<tr>'; // Собственно формирование содержимого таблицы
		echo '<td>'.$k.'</td>'.'<td>'.date("d.m.Y", time()).'</td>'.'<td>'.$article_time.'</td>'.'<td>'.mb_convert_encoding($article_title, "utf-8", $encoding).'</td>'.'<td>'.mb_convert_encoding($article_subtitle, "utf-8", $encoding).'</td>';
		echo '</tr>';
		$k++;
		if ($k == $max_article_count + 1) exit(); 
		
	}
}
}
echo "</table>";


}
function url_date($timestamp)
{
return date("dmY", $timestamp);
}
//освобождаем ресурсы
$html->clear(); 
unset($html);
?> 
</div>
</body>
</html>

