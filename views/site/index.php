<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<ol>
    <li>
        Возможно ли такое в PHP и как это реализуется, если возможно:<br/>
        <pre>$obj = new Building();<br/>$obj['name'] = 'Main tower';<br/>$obj['flats'] = 100;<br/>$obj->save();<br/></pre>
        <code>
            Да, нужно реализовать в Building интерфейс ArrayAccess
        </code>
    </li>
    <li>
        Возможно ли в PHP следущее?<br/>
        <pre>$datetime = new Datetime();<br/>echo (clone $datetime)->format('Y');</pre>
        <code>Да, но начиная с версии 7</code>
    </li>
    <li>
        Как проверить соответствует ли дата, хранимая в переменной $str, определенному
        формату? Используем описание формата такое же как в функции php date(). Пример
        описания формата:
        <pre>$format = 'd.m.Y';<br/>$format = 'H.i';</pre>
        <code>Ммм... Регулярки, например.</code><br/>
        <code>или так: \DateTime::createFromFormat($format, $time) instanceof \DateTime</code>
        <pre>$format = 'd.m.Y';<br/>$dt = \DateTime::createFromFormat($format, '12.12.2012 10:13:46');<br/>echo ($dt instanceof \DateTime ? 'Ok' : 'No');<br/><?php
            $format = 'd.m.Y';
            $dt = \DateTime::createFromFormat($format, '12.12.2012 10:13:46');
            echo '$dt has format: '. ($dt instanceof \DateTime ? 'Yes' : 'No');
            ?>
        </pre>
        <pre>$format = 'd.m.Y H:i:s';<br/>$dt = \DateTime::createFromFormat($format, '12.12.2012 10:13:46');<br/>echo ($dt instanceof \DateTime ? 'Ok' : 'No');<br/><?php
            $format = 'd.m.Y H:i:s';
            $dt = \DateTime::createFromFormat($format, '12.12.2012 10:13:46');
            echo '$dt has format: '. ($dt instanceof \DateTime ? 'Yes' : 'No');
            ?>
        </pre>
    </li>
    <li>
        Существует таблица, в которой хранятся записи о неких событиях (например, выставки
        или фестивали).
        <pre>CREATE TABLE events (
    id INTEGER PRIMARY KEY NOT NULL,
    name CHARACTER VARYING(255),
    begin_date TIMESTAMP(0) WITHOUT TIMEZONE,
    end_date TIMESTAMP(0) WITHOUT TIMEZONE
);</pre>
        Я так понял, что это должен быть Postgresql какой-то версии.<br/>
        <pre>CREATE TABLE events (
  id         INTEGER PRIMARY KEY NOT NULL,
  name       CHARACTER VARYING(255),
  begin_date TIMESTAMP WITHOUT TIME ZONE,
  end_date   TIMESTAMP WITHOUT TIME ZONE
);

INSERT INTO events (id, name, begin_date, end_date)
VALUES
  (1, 'event 1', '2018-12-01 12:00:00', '2018-12-01 20:00:00'),
  (2, 'event 2', '2018-12-05 12:00:00', '2018-12-05 20:00:00'),
  (3, 'event 3', '2018-12-06 12:00:00', '2018-12-06 20:00:00');

SELECT *
FROM events
WHERE EXTRACT(WEEK FROM begin_date) = EXTRACT(WEEK FROM now());</pre>
        Можно еще год проверить:
        <pre>SELECT *
FROM events
WHERE EXTRACT(YEAR FROM begin_date) = EXTRACT(YEAR FROM now()) AND
      EXTRACT(WEEK FROM begin_date) = EXTRACT(WEEK FROM now()) - 1;</pre>
    </li>
    <li>
        Каждый семинар характеризуется следующими атрибутами: название, дата начала, дата
        окончания, город, участники события. Необходимо спроектировать структуру таблиц БД для
        хранения записей о таких семинарах.
        <pre>-- Семинары
CREATE TABLE seminar (
  id         INTEGER PRIMARY KEY NOT NULL,
  name       CHARACTER VARYING(255),
  begin_date TIMESTAMP WITHOUT TIME ZONE,
  end_date   TIMESTAMP WITHOUT TIME ZONE
);

-- Города, в которых проходят семинары
CREATE TABLE cities (
  id   INTERVAL PRIMARY KEY NOT NULL,
  name CHARACTER VARYING(255)
);

-- Участники семинаров
CREATE TABLE seminar_participant (
  id         INTEGER PRIMARY KEY NOT NULL,
  -- Можно добавить внешний ключ на таблицу с пользователями, например
  -- или...
  first_name CHARACTER VARYING(255)
  -- ... и еще куча полей
);

-- Совсем просто(целостность проверяется кодом)
CREATE TABLE seminar_city (
  seminar_id     INTEGER,
  participant_id INTEGER
);
-- Cложнее, но более правильно (целостность проверяется)
ALTER TABLE ONLY seminar_city
  ADD CONSTRAINT seminar_cities_seminar_id_fk_seminar_city_seminar_id
FOREIGN KEY (seminar_id) REFERENCES seminar (id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE ONLY seminar_city
  ADD CONSTRAINT seminar_cities_participant_id_fk_seminar_participant_id
FOREIGN KEY (participant_id) REFERENCES seminar_participant (id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;</pre>
    </li>
    <li>
        Есть таблица с колонками a и b, обе колонки типа INT. Дан запрос "select a, count(*) from
        t group by a". Как изменить этот запрос, чтобы вывелись уникальные значения “a” которые
        встречаются в таблице более 2х раз.
        <pre>CREATE TABLE example_tbl (
  a INTEGER,
  b INTEGER
);

TRUNCATE example_tbl;

INSERT INTO example_tbl (a, b) VALUES
  (1, 1),
  (1, 2),
  (2, 3),
  (1, 3);

SELECT
  a,
  count(*)
FROM example_tbl
GROUP BY a
HAVING count(a) > 2;</pre>
    </li>
    <li>
        Написать простое веб-приложение, которое выводит таблицу со списком файлов в
        корневой директории хоста (DOCUMENT_ROOT).
        Столбцы таблицы:
        <ul>
            <li>Название файла/папки;</li>
            <li>Размер (для папок выводить [DIR]);</li>
            <li>Тип (вывести расширение файла, для папок пустая строка);</li>
            <li>Дата последней модификации.</li>
        </ul>
        При первом открытии страницы данные должны считываться и записываться в MYSQL<br/>
        таблицу. При последующих открытиях страницы данные должны выводиться из MYSQL<br/>
        таблицы игнорируя текущую ситуацию в корневой директории. Так называемый кэш в БД.<br/>
        Внизу вывести ссылку “Обновить”, которая обновит данные о файлах в MYSQL таблице и на
        экране.
        <pre>Welcome <a href="<?php echo Url::to('readdir/index') ?>">сюда</a></pre>
    </li>
</ol>
