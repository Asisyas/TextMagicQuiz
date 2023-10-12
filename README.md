# Simple QUIZ platform

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull -d --wait` to start the project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Docs

### Starting the Quiz

- **Http:**
    - After launching the containers, open the main page and enter any name.

- **Cli:**
    - Run the command `make quiz <username>`. If you don't have `make` installed, you can also use:

      ```
      docker-compose exec php bin/console quiz:start <username>
      ```

## Features

- The quiz can be taken multiple times; however, you can only take one quiz at a time.
- Questions are selected randomly. However, if there are only a few questions, you might not notice the randomness.

## Additional Capabilities

- To add a new question, execute the command `make question` or:

    ```
    docker-compose exec php bin/console question:add
    ```

- Use `make` or `make sf` to get help on possible commands.



## Additional docs

5. [Debugging with Xdebug](docs/xdebug.md)
