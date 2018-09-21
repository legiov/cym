# How to use
1. Git clone this
2. ```bash
        cp env-sample .env
    ```
    And modify it for your needs 
3. add in /etc/hosts - 127.0.0.1 cym.loc
4. ```bash
        docker-compose build
   ```
5. ```bash
    docker-compose up -d
   ```
6. 
    ```bash
    docker-compose exec --user dockuser php72 bash
    cd cym
    cp .env.dist .env
    composer install
    php bin/console do:da:cr
    php bin/console doc:mi:mi
    ``` 
7. PROFIT
