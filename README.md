# Postman запити:
Додавання - todo add
Видалення - todo delete 
Позначити завдання як виконане - todo mark as completed
Змінити текст завдання - todo update title
Список всіх завдань із пагінацією - todo list with pagination


# Переглянута (та, що хоч раз переглянули + Кількість переглядів (при кожному зверненні кількість повинна збільшуватися)
- тут чітко вказано що **при кожному запросі** треба збільшувати каунтер (самий перший запрос як додавання 
  це те ж являється запросом, але якщо брати мою логіку то для запитів як 
  "Позначити завдання як виконане" та "Змінити текст завдання" та "Список всіх завдань із пагінацією"
  тільки добавляю каунтер на +1 більше від поточного значення (що завдання були переглянуті), 
  в інші запроси я не добавляв збільшення каунтера на +1 (бо не бачу сенсу, наприклад для запросів добавання чи видалення))

- тому для completeTodo, updateTodo, listTodos - тільки враховував збільшення каунтера на +1 більше від поточного значення


приклад запросів в постмані імпортував у файлі - devo.postman_collection.json 
(тобто в постмані треба зробити екпорт для файлу - devo.postman_collection.json, щоб запроси автоматично в колекцію зекспортувались в сам постман)


# Нова (тільки створена)
- реалізував автоматичний статус по-дефолту $todo->setStatus('new') для addTodo

# Важлива (завдання якої вже більше 1-го дня)
- тут звичайно запитань багато є, при яких подіях це потрібно робити ?
- варіанти:
  - тільки коли ми переглядаємо завдання ? (а ми переглядаємо завдання в трьох запросах, де вище описав)
  - чи це потрібно кроном перевіряти час від часу (брати інтервал якийсь і змінювати статус для всіх завдань які ще не в статусі completed наприклад)