# Modules

This folder contains core functionalities of the framework organized in modules.

Each subfolder represents a module and each of them contain a `README` file with informations about the functionality provided by that module and how they can be utilized.

## Available Modules
- [Database](./database) - Database connection management.
- [Logger](./logger) - Logging system for the application.
- [Router](./router) - Routing system for the application.
- [Session](./session) - Session management.
- [View](./view) - View rendering system.

if a module module is missing in your project, the module must be added manually, by following the instructions in the `Module Management/Adding a module` section below.


## Use a Module
To use a module follow the instruction in the `README` of the module you want to use.


## Personalized functionality
If you need some personalized functionality not available in the modules or if you want to extend an existing one.
It is recommanded to create your own module inside the [Features](../Features/) directory.

So personalized functionality will be separated from the one provided by the framework itself.

## Module Management

### Removing a Module
Module may depends on another modules, to safely remove a module it is necessary to verify the dependencie list in their `README` files.

### Adding a Module
Module may depends on other modules, to add a new module it is necessary to verify the dependencie list in their `README` files and add all dependencies along with the module itself.
