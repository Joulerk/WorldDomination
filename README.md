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
