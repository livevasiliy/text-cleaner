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
   bash
   Copy
   Edit
   docker compose up -d --build
3. Установите зависимости Symfony:
   bash
   Copy
   Edit
   docker compose exec php composer install
4. Откройте приложение:
   arduino
   Copy
   Edit
   http://localhost:8080