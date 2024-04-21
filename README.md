Here's a basic README to guide users on installing a Symfony project on Windows using Lando with WSL (Windows Subsystem for Linux):

---

# Installing Symfony Project with Lando on Windows using WSL

This guide will walk you through the steps to set up a Symfony project on Windows using Lando, a local development environment, with WSL (Windows Subsystem for Linux).

## Prerequisites

Before you begin, ensure you have the following installed:

1. **Windows Subsystem for Linux (WSL)**: Follow the [official documentation](https://docs.microsoft.com/en-us/windows/wsl/install) to install WSL on your Windows machine.
2. **Docker Desktop for Windows**: Install Docker Desktop for Windows on your system. You can download it from the [official Docker website](https://www.docker.com/products/docker-desktop/).
4. **Ubuntu in WSL 2**: Install Ubuntu. After enabling WSL 2, you can install Ubuntu from the Microsoft Store or by downloading the distribution package from the [Website](https://apps.microsoft.com/detail/9n6svws3rx71?rtc=1&hl=lv-lv&gl=LV).
5. **Lando (inside WSL 2)**: Install Lando inside your Ubuntu WSL 2 environment. Run /bin/bash -c "$(curl -fsSL https://get.lando.dev/setup-lando.sh)"


## Step 1: Clone Symfony Project

```ubuntu bash
mkdir src && cd src
git clone https://github.com/alexrodnovs/trodotest.git
cd trodotest
```

## Step 2: Start Lando

Start Lando for your Symfony project.

```bash
lando start
```

## Step 3: Run migrations
```bash
lando php bin/console doctrine:migrations:migrate
```
## Step 3: Run command to fill rates
```bash
lando php bin/console app:fetch-and-store-rates
```
## Step 4: Fill database with fixtures(for testing purposes)
Fixtures will fill currencyrates with random rates, for previous 25 days.
```bash
lando php bin/console doctrine:fixtures:load
```
## Step 5: Setup cronjobs
Cron will run every day at 2 AM
```bash
crontab -e
```
Select editor
Add && save 
* 2 * * * cd ~/src/trodotest/ && lando php bin/console app:fetch-and-store-rates >> ~/cron.log 2>&1

This command will build and start the Lando environment for your Symfony project.

## Additional Resources

- [Symfony Documentation](https://symfony.com/doc)
- [Lando Documentation](https://docs.lando.dev/)
- [Composer Documentation](https://getcomposer.org/doc/)

---
