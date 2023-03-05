![Ggaming logo](https://i.imgur.com/JCdzhQc.png)

## Introduction

Ggaming is a PiDev project created by the group Limitless which consists of:

| Name              | Role         |
| -----------------| ------------ |
| Ben Amor Sameh    | Developer    |
| Uwobikundiye Dhia | Developer    |
| Khiari Ons        | Developer    |
| Keita Balla Moussa| Developer    |
| Ben Khaled Haytham| Developer    |
| Ben Aissa Nour    | Developer    |

## Installation

To install Ggaming, follow these steps:

1. Clone the repository using the following command:

    ```
    git clone https://github.com/limitless-coding/Ggaming.git
    ```

2. Install Composer dependencies:

    ```
    composer install
    ```

3. Create a `.env.local` file and add your database configuration:

    ```
    # example database configuration
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    ```

4. Create the database schema:

    ```
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5. Load the fixtures to populate the database with initial data:

    ```
    php bin/console doctrine:fixtures:load
    ```

6. Start the development server:

    ```
    symfony serve
    ```

You can now access the application at `http://localhost:8000`.


### External Bundles

To use the full functionality of Ggaming, please make sure to install the following external bundles:


| Bundle Name                 | Command to Install                       |
| ---------------------------| ---------------------------------------- |
| symfony/mailer              | composer require symfony/mailer          |
| symfony/messenger           | composer require symfony/messenger       |
| symfony/google-mailer       | composer require symfony/google-mailer   |
| stof/doctrine-extensions-bundle | composer require stof/doctrine-extensions-bundle |
| knplabs/knp-paginator-bundle| composer require knplabs/knp-paginator-bundle:* |
| gedmo/doctrine-extensions   | composer require gedmo/doctrine-extensions|
| liip/imagine-bundle         | composer require liip/imagine-bundle     |

## Usage

Our website serves multiple functionalities. You should start by creating an account and then confirming it by clicking the link that'll be sent to your email. Then you can access your account and choose to join a team, join a tournament, create a blog, shop and much much more!

## Contributing

Our contribution period has ended, therefore we won't accept anymore contributions.

## Bug Reports and Feature Requests

If you encounter any bugs or want to request a feature, please contact us on our emails.

## Authors

The author of this project is Limitless.

## Contact

If you have any questions or concerns, you can contact us on our emails.
