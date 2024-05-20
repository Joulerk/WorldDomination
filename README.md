# World Domination Game

World Domination Game is a PHP-based strategy game where players control countries, manage resources, and attempt to dominate the world through various actions including building nuclear technology and launching nuclear missiles. The game features a dynamic environment where global ecology impacts the earnings of countries.

## Features

- **Country Management**: Manage the development, money, and cities of your country.
- **Nuclear Technology**: Research and build nuclear technology and missiles.
- **Strategic Actions**: Improve city development, build shields, invest in global ecology, and launch nuclear missiles at other countries.
- **Rounds System**: The game progresses in rounds, with players making their moves and an administrator processing the outcomes.
- **Global Ecology**: The global ecology percentage impacts the earnings of all countries.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/Joulerk/world-domination-game.git
    ```

2. Navigate to the project directory:
    ```bash
    cd world-domination-game
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

### For Administrators

1. **Create a Game**: Navigate to the admin panel and create a new game. You can specify the number of countries.
2. **Manage Rounds**: In the admin panel, monitor the readiness of players and process the round when all players are ready.
3. **Edit Game Data**: Administrators can manually edit the game data for countries and cities if needed.

## Game Mechanics

- **Development**: Increases the earnings of a country based on the average development of its cities.
- **Money**: Used to perform various actions like building shields, improving cities, and constructing nuclear missiles.
- **Nuclear Technology**: Required to build nuclear missiles and shields.
- **Global Ecology**: Affects the earnings of all countries. Players can invest in global ecology to improve it.

## Contributing

I welcome contributions from the community. If you'd like to contribute, please fork the repository, create a new branch, and submit a pull request with your changes.

## Contact

If you have any questions or feedback, feel free to open an issue or contact the project maintainer at [madventcher@gmail.com].






# Игра "Мировое Господство"

Игра "Мировое Господство" - это стратегическая игра на PHP, в которой игроки управляют странами, управляют ресурсами и стремятся доминировать в мире с помощью различных действий, включая строительство ядерных технологий и запуск ядерных ракет. Игра характеризуется динамичной средой, где глобальная экология влияет на доходы стран.

## Особенности

- **Управление страной**: Управляйте развитием, деньгами и городами своей страны.
- **Ядерные технологии**: Исследуйте и стройте ядерные технологии и ракеты.
- **Стратегические действия**: Улучшайте развитие городов, стройте щиты, вкладывайтесь в глобальную экологию и запускайте ядерные ракеты в другие страны.
- **Система раундов**: Игра проходит по раундам, где игроки делают свои ходы, а администратор обрабатывает результаты.
- **Глобальная экология**: Процент глобальной экологии влияет на доходы всех стран.

## Установка

1. Клонируйте репозиторий:
    ```bash
    git clone https://github.com/Joulerk/world-domination-game.git
    ```

2. Перейдите в директорию проекта:
    ```bash
    cd world-domination-game
    ```

3. Настройте ваш веб-сервер для обслуживания директории проекта. Вы можете использовать Apache, Nginx или любой другой сервер, поддерживающий PHP.

4. Убедитесь, что директория `data` доступна для записи веб-сервером. Вы можете установить права доступа с помощью:
    ```bash
    chmod -R 777 data
    ```

## Структура проекта

- `index.php`: Основная точка входа в игру.
- `admin/`: Содержит все административные страницы для создания и управления игрой.
- `assets/`: Содержит CSS и JavaScript файлы.
- `data/`: Хранит JSON файлы с данными игры, действиями и уведомлениями.
- `includes/`: Содержит многоразовые PHP файлы, такие как заголовки, подвал и функции.
- `player/`: Содержит страницы, специфичные для игроков, для управления страной, уведомлений и результатов.

## Как играть

### Для игроков

1. **Присоединение к игре**: Перейдите на страницу входа игрока, выберите свою страну и введите пароль, предоставленный администратором игры.
2. **Управление страной**: На странице вашей страны вы можете:
   - Улучшать развитие ваших городов.
   - Строить щиты для защиты ваших городов.
   - Исследовать и строить ядерные технологии и ракеты.
   - Вкладываться в глобальную экологию.
   - Запускать ядерные ракеты в другие страны.
3. **Завершение хода**: После того, как вы сделали свои ходы, нажмите кнопку "Готов", чтобы отправить свои действия для текущего раунда.

### Для администраторов

1. **Создание игры**: Перейдите в админ-панель и создайте новую игру. Вы можете указать количество стран.
2. **Управление раундами**: В админ-панели следите за готовностью игроков и обрабатывайте раунд, когда все игроки готовы.
3. **Редактирование данных игры**: Администраторы могут вручную редактировать данные игры для стран и городов при необходимости.

## Механика игры

- **Развитие**: Увеличивает доход страны в зависимости от среднего уровня развития её городов.
- **Деньги**: Используются для выполнения различных действий, таких как строительство щитов, улучшение городов и строительство ядерных ракет.
- **Ядерные технологии**: Необходимы для строительства ядерных ракет и щитов.
- **Глобальная экология**: Влияет на доходы всех стран. Игроки могут вкладываться в глобальную экологию, чтобы улучшить её.

## Вклад

Я приветствую вклад от сообщества. Если вы хотите внести свой вклад, пожалуйста, форкните репозиторий, создайте новую ветку и отправьте pull request с вашими изменениями.

## Контакты

Если у вас есть вопросы или предложения, не стесняйтесь открыть issue или связаться с администратором проекта по адресу [madventcher@gmail.com].

