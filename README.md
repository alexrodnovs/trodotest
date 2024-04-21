Here's a basic README to guide users on installing a Symfony project on Windows using Lando with WSL (Windows Subsystem for Linux):

---

# Installing Symfony Project with Lando on Windows using WSL

This guide will walk you through the steps to set up a Symfony project on Windows using Lando, a local development environment, with WSL (Windows Subsystem for Linux).

## Prerequisites

Before you begin, ensure you have the following installed:

1. **Windows Subsystem for Linux (WSL)**: Follow the [official documentation](https://docs.microsoft.com/en-us/windows/wsl/install) to install WSL on your Windows machine.
2. **Lando**: Install Lando on your Windows system. Visit the [official Lando website](https://docs.lando.dev/install/windows.html) for installation instructions.
3. **Ubuntu in WSL 2**: Install Ubuntu. After enabling WSL 2, you can install Ubuntu from the Microsoft Store or by downloading the distribution package from the [Website](https://apps.microsoft.com/detail/9n6svws3rx71?rtc=1&hl=lv-lv&gl=LV).
4. **Lando (inside WSL 2)**: Install Lando inside your Ubuntu WSL 2 environment. Run /bin/bash -c "$(curl -fsSL https://get.lando.dev/setup-lando.sh)"
6. **Docker Desktop for Windows**: Install Docker Desktop for Windows on your system. You can download it from the [official Docker website](https://www.docker.com/products/docker-desktop/).

## Step 1: Clone Symfony Project

Clone your Symfony project repository or create a new Symfony project.

```bash
mkdir src && cd src && mkdir trodotest && cd trodotest
git clone alexrodnovs/trodotest
```

## Step 2: Start Lando

Start Lando for your Symfony project.

```bash
lando start
```

This command will build and start the Lando environment for your Symfony project.

## Additional Resources

- [Symfony Documentation](https://symfony.com/doc)
- [Lando Documentation](https://docs.lando.dev/)
- [Composer Documentation](https://getcomposer.org/doc/)

---
