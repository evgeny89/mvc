-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 21 2020 г., 11:41
-- Версия сервера: 8.0.19
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mvc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `breadcrumbs`
--

CREATE TABLE `breadcrumbs` (
  `id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `path` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `breadcrumbs`
--

INSERT INTO `breadcrumbs` (`id`, `name`, `path`) VALUES
(1, 'Главная', '/'),
(2, 'Галерея', 'gallery'),
(3, 'Изображение', 'single.php');

-- --------------------------------------------------------

--
-- Структура таблицы `gallery`
--

CREATE TABLE `gallery` (
  `id` int NOT NULL COMMENT 'идентификатор',
  `name` varchar(32) NOT NULL DEFAULT 'photo' COMMENT 'имя файла',
  `path` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'images/' COMMENT 'путь до директории',
  `athor_id` int NOT NULL COMMENT 'автор',
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата загрузки',
  `views` int NOT NULL DEFAULT '0' COMMENT 'просмотры',
  `size` float NOT NULL COMMENT 'размер в кб'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `gallery`
--

INSERT INTO `gallery` (`id`, `name`, `path`, `athor_id`, `date_create`, `views`, `size`) VALUES
(3, 'photo-fontan.jpg', '/gallery/images/', 1, '2020-08-18 17:58:19', 2, 19.18),
(4, 'photo-memorial.jpg', '/gallery/images/', 1, '2020-08-18 17:58:19', 11, 59.7),
(5, 'photo-skvazhina.jpg', '/gallery/images/', 1, '2020-08-18 17:59:03', 2, 31.21),
(6, 'photo-stella.jpg', '/gallery/images/', 2, '2020-08-18 17:59:03', 7, 1499.38),
(7, 'photo-stella2.jpg', '/gallery/images/', 2, '2020-08-18 17:59:40', 2, 86.44),
(8, 'photo-tcerkov.jpg', '/gallery/images/', 1, '2020-08-18 17:59:40', 1, 131.61),
(9, 'photo-zvezdny.jpg', '/gallery/images/', 1, '2020-08-18 18:00:14', 2, 69.47);

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `parent_id` int NOT NULL DEFAULT '0' COMMENT 'для вложенных пунктов',
  `link` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `name`, `parent_id`, `link`) VALUES
(1, 'главная', 0, '/'),
(2, 'фотогалерея', 0, '/gallery-php/'),
(4, 'добавить&nbsp;фотографию', 2, '/gallery-php/addimage.php');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `date_create`) VALUES
(1, 'Евгений', '2020-08-18 15:13:41'),
(2, 'admin', '2020-08-18 15:13:45'),
(3, 'Mike', '2020-08-19 18:21:18'),
(4, 'John', '2020-08-19 18:43:32');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `breadcrumbs`
--
ALTER TABLE `breadcrumbs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `athor_id` (`athor_id`);

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `breadcrumbs`
--
ALTER TABLE `breadcrumbs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'идентификатор', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`athor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
