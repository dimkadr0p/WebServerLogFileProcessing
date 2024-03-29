# WebServerLogFileProcessing
Тема: Разработка интернет-приложения по технологии PHP, методологии AJAX и с использованием БД. Обработка лог-файлов веб-сервера.

Цель: Разработать набор динамических страниц для обработки статистики посещаемости Web-сервера. Лог-файл веб-сервера должен загружаться в нормализованную базу данных. Структура (состав столбцов) выбирается студентом из фактической необходимости для построения отчета. Реализовать отчет о статистике посещаемости по индивидуальному варианту.

Требования к реализации: 
Загрузка лог-файла в БД должна быть реализована с использованием методологии AJAX. Исходить из условия, что лог-файл является большим — 200 Мб и больше. Лог-файл находится на сервере, страница инициирует загрузку лог-файла в БД порционно. Прогресс загрузки должен отображаться на экране (в странице).

Для всех отчетов реализовать:
а) Рейтинг от максимального к минимальному и наоборот
б) группировку по часам, дням, неделям, месяцам;
в) сортировку по произвольным полям;
г) конфигурирование отчетов, если это необходимо по варианту (например, для вар.17,18: настройка типов файлов – для разделения на “страницы”, “графические файлы”, “архивы” и т.п.)
г) выборка данных из БД для построения отчета должна осуществляться одним запросом.

Максимально оптимизировать всю обработку по времени. 
Возможно, по желанию, использование диаграмм.

Возможно выполнение в упрощенном вариантах, без п. а-г).
При выполнении упрощенного задания нельзя претендовать на оценку 4,5 на экзамене).

Файл с логами в действительности намного больше, чем запушен на github.
