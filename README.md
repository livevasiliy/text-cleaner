# 🧼 Symfony Text Cleaner

Веб-приложение на Symfony, которое позволяет загружать текст или файл, очищать его от лишних пробелов, форматировать знаки препинания и скачивать результат. Поддержка темной темы, адаптивного дизайна и Ajax-интерфейса.

## 📦 Состав стека
- **Symfony 7**
- **PHP 8.3 FPM Alpine**
- **Nginx**
- **Redis**
- **Tailwind CSS**
- **Docker + Docker Compose**

## 🚀 Запуск проекта
1. Клонируйте репозиторий:
```bash
   git clone https://github.com/livevasiliy/text-cleaner.git
   cd text-cleaner
```
2. Соберите и запустите контейнеры:
```bash
   docker compose up -d --build
```
3. Установите зависимости Symfony:
```bash
   docker compose exec php composer install
```
4. Откройте приложение:
```
http://localhost:8080
```

## ⚙️ Структура проекта
```
├── docker/
│   ├── nginx/
│   │   ├── conf.d/default.conf
│   │   └── nginx.conf
│   └── php/
│       └── conf.d/custom.ini
├── public/
│   └── index.php
├── src/
│   └── ...
├── templates/
│   └── text_cleaner/index.html.twig
├── Dockerfile
├── docker-compose.yml
├── README.md
```

## 📂 Возможности
- ✅ Очистка текста от лишних пробелов, пунктуации и форматирование предложений
- ✅ Загрузка .txt файла
- ✅ Поддержка формы Symfony с валидацией
- ✅ Ajax-обработка текста без перезагрузки страницы
- ✅ Тёмная/светлая тема
- ✅ Скачивание результата как .txt файл
- ✅ Адаптивный и современный интерфейс на Tailwind CSS

## 📥 Пример использования
1. Введите текст вручную или загрузите файл.
2. Нажмите "Очистить текст".
3.  Посмотрите результат прямо на странице.
4. Нажмите "Скачать .txt", чтобы сохранить результат.

## 📖 Лицензия
MIT © 2025