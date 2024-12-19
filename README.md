## Yu-Gi-Oh API

### Context

This project started as a way to explore the latest version of API Platform (4) and the Jolicode automapper. After finding a Yu-Gi-Oh card dataset on Kaggle, the idea for this project was born.

### Description

Yu-Gi-Oh API is a Symfony-based project for managing and interacting with Yu-Gi-Oh card data. It allows you to create, update, and retrieve information about Yu-Gi-Oh cards, card sets, and related entities. Key features include:

- Image uploading with unique UUID filenames
- Caching for fast data retrieval
- Solid test coverage for reliability

The project uses Docker for development and deployment, with a Makefile to handle common tasks. The development setup includes Yarn and Composer for managing JavaScript and PHP dependencies.

### Installation

1. Run `make dc-install` to install the project and run the fixtures.
2. To use real data, run `make sf c="app:import"`. Download the data from [Kaggle](https://www.kaggle.com/datasets/archanghosh/yugioh-database) and place `dataset.csv` and the images folder in the `src/Import/Resources` directory.

### Project Status

The API is considered complete. I might add new features to test certain parts of API Platform in the future.