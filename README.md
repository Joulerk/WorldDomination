# World Domination Game

World Domination Game is a PHP-based strategy game where players control countries, manage resources, and attempt to dominate the world through various actions including building nuclear technology and launching nuclear missiles. The game features a dynamic environment where global ecology impacts the earnings of countries and the overall game outcome.

## Features

- **Country Management**: Manage the development, money, and cities of your country.
- **Nuclear Technology**: Research and build nuclear technology and missiles.
- **Strategic Actions**: Improve city development, build shields, invest in global ecology, and launch nuclear missiles at other countries.
- **Rounds System**: The game progresses in rounds, with players making their moves and an administrator processing the outcomes.
- **Global Ecology**: The global ecology percentage impacts the earnings of all countries.
- **Automated Cleanup**: After the 7th round, the game data and room are automatically deleted after 15 minutes.
- **Room Management**: Players can create and join rooms to start new games.
- **Player Notifications**: Players receive notifications about significant events in the game.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/Joulerk/WorldDomination.git
    ```

2. Navigate to the project directory:
    ```bash
    cd WorldDomination
    ```

3. Set up your web server to serve the project directory. You can use Apache, Nginx, or any other server that supports PHP.

4. Ensure that the `data` directory is writable by the web server. You can set the permissions using:
    ```bash
    chmod -R 777 data
    ```

## Project Structure

- `index.php`: The main entry point of the game.
- `admin/`: Contains all administrative pages for creating and managing the game.
- `assets/`: Contains CSS and JavaScript assets.
- `data/`: Stores JSON files for game data, actions, and notifications.
- `includes/`: Contains reusable PHP files such as headers, footers, and functions.
- `player/`: Contains player-specific pages for country management, notifications, and results.

## How to Play

### For Players

1. **Join the Game**: Navigate to the player index page and select your country, then enter the password provided by the game administrator.
2. **Manage Your Country**: On your country page, you can:
   - Improve the development of your cities.
   - Build shields to protect your cities.
   - Research and build nuclear technology and missiles.
   - Invest in global ecology.
   - Launch nuclear missiles at other countries.
3. **End Your Turn**: Once you've made your moves, click the "Ready" button to submit your actions for the round.
4. **View Notifications**: Players can view notifications about significant events that happen during the game.

### For Administrators

1. **Create a Game**: Navigate to the admin panel and create a new game. You can specify the number of countries and their initial settings.
2. **Manage Rounds**: In the admin panel, monitor the readiness of players and process the round when all players are ready. Ensure to check for the automated cleanup after the 7th round.
3. **Edit Game Data**: Administrators can manually edit the game data for countries and cities if needed. The settings can be reset to default if necessary.

## Game Mechanics

- **Development**: Increases the earnings of a country based on the average development of its cities.
- **Money**: Used to perform various actions like building shields, improving cities, and constructing nuclear missiles.
- **Nuclear Technology**: Required to build nuclear missiles and shields.
- **Global Ecology**: Affects the earnings of all countries. Players can invest in global ecology to improve it.
- **Automated Cleanup**: After the 7th round, a timer starts, and after 15 minutes, all game data and the room are automatically deleted.

## Contributing

I welcome contributions from the community. If you'd like to contribute, please fork the repository, create a new branch, and submit a pull request with your changes.

## Contact

If you have any questions or feedback, feel free to open an issue or contact the project maintainer at [madventcher@gmail.com](mailto:madventcher@gmail.com) or [lerk@joulerk.ru](mailto:lerk@joulerk.ru).


# Игра "Мировое Господство"

Игра "Мировое Господство" - это стратегическая игра на PHP, в которой игроки управляют странами, распоряжаются ресурсами и стремятся доминировать в мире, осуществляя различные действия, включая строительство ядерных технологий и запуск ядерных ракет. Игра предлагает динамичную среду, где глобальная экология влияет на доходы стран и общий исход игры.

## Возможности

- **Управление страной**: Управляйте развитием, деньгами и городами вашей страны.
- **Ядерные технологии**: Исследуйте и создавайте ядерные технологии и ракеты.
- **Стратегические действия**: Улучшайте развитие городов, строите щиты, вкладывайте в глобальную экологию и запускайте ядерные ракеты по другим странам.
- **Система раундов**: Игра проходит по раундам, в которых игроки совершают свои ходы, а администратор обрабатывает результаты.
- **Глобальная экология**: Процент глобальной экологии влияет на доходы всех стран.
- **Автоматическая очистка**: После 7-го раунда данные игры и комната автоматически удаляются через 15 минут.
- **Управление комнатами**: Игроки могут создавать и присоединяться к комнатам для начала новых игр.
- **Уведомления для игроков**: Игроки получают уведомления о значимых событиях в игре.

## Установка

1. Клонируйте репозиторий:
    ```bash
    git clone https://github.com/Joulerk/WorldDomination.git
    ```

2. Перейдите в каталог проекта:
    ```bash
    cd WorldDomination
    ```

3. Настройте ваш веб-сервер для обслуживания каталога проекта. Вы можете использовать Apache, Nginx или любой другой сервер, поддерживающий PHP.

4. Убедитесь, что каталог `data` доступен для записи веб-сервером. Вы можете установить разрешения с помощью:
    ```bash
    chmod -R 777 data
    ```

## Структура проекта

- `index.php`: Главная точка входа в игру.
- `admin/`: Содержит все административные страницы для создания и управления игрой.
- `assets/`: Содержит CSS и JavaScript ресурсы.
- `data/`: Содержит JSON файлы с данными игры, действиями и уведомлениями.
- `includes/`: Содержит переиспользуемые PHP файлы, такие как заголовки, нижние колонтитулы и функции.
- `player/`: Содержит страницы, специфичные для игроков, для управления страной, уведомлений и результатов.

## Как играть

### Для игроков

1. **Присоединиться к игре**: Перейдите на страницу игрока и выберите свою страну, затем введите пароль, предоставленный администратором игры.
2. **Управляйте своей страной**: На странице вашей страны вы можете:
   - Улучшать развитие ваших городов.
   - Строить щиты для защиты ваших городов.
   - Исследовать и строить ядерные технологии и ракеты.
   - Вкладывать в глобальную экологию.
   - Запускать ядерные ракеты по другим странам.
3. **Завершите свой ход**: После того, как вы совершили свои ходы, нажмите кнопку "Готов", чтобы отправить ваши действия на текущий раунд.
4. **Просмотр уведомлений**: Игроки могут просматривать уведомления о значимых событиях, происходящих во время игры.

### Для администраторов

1. **Создание игры**: Перейдите в панель администратора и создайте новую игру. Вы можете указать количество стран и их начальные настройки.
2. **Управление раундами**: В панели администратора следите за готовностью игроков и обрабатывайте раунд, когда все игроки готовы. Не забудьте проверить автоматическую очистку после 7-го раунда.
3. **Редактирование данных игры**: Администраторы могут вручную редактировать данные игры для стран и городов при необходимости. Настройки могут быть сброшены до значений по умолчанию, если это необходимо.

## Механика игры

- **Развитие**: Увеличивает доходы страны на основе среднего уровня развития её городов.
- **Деньги**: Используются для выполнения различных действий, таких как строительство щитов, улучшение городов и строительство ядерных ракет.
- **Ядерные технологии**: Необходимы для строительства ядерных ракет и щитов.
- **Глобальная экология**: Влияет на доходы всех стран. Игроки могут вкладывать в глобальную экологию, чтобы улучшить её.
- **Автоматическая очистка**: После 7-го раунда запускается таймер, и через 15 минут все данные игры и комната автоматически удаляются.

## Участие в проекте

Я приветствую участие сообщества. Если вы хотите внести свой вклад, пожалуйста, форкните репозиторий, создайте новую ветку и отправьте запрос на слияние с вашими изменениями.

## Контакты

Если у вас есть вопросы или отзывы, не стесняйтесь открывать issue или связаться с администратором проекта по электронной почте [madventcher@gmail.com](mailto:madventcher@gmail.com) или [lerk@joulerk.ru](mailto:lerk@joulerk.ru).